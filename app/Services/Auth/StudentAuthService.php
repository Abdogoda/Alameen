<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Throwable;

class StudentAuthService
{
    public function __construct(
        protected CreateNewUser $createNewUser,
        protected TwoFactorService $twoFactorService,
        protected PasskeyService $passkeyService,
        protected FirebaseAuthenticationService $firebaseAuthService,
        protected PasswordResetOtpService $otpService
    ) {}

    /**
     * Register a new student account (status = 'pending' awaiting admin approval).
     *
     * @param  array<string, mixed>  $input
     */
    public function register(array $input): User
    {
        $user = $this->createNewUser->create([
            'name' => (string) ($input['name'] ?? ''),
            'email' => (string) ($input['email'] ?? ''),
            'password' => (string) ($input['password'] ?? ''),
            'password_confirmation' => (string) ($input['password_confirmation'] ?? ($input['password'] ?? '')),
            'locale' => (string) ($input['locale'] ?? app()->getLocale()),
        ]);

        $extra = array_intersect_key($input, array_flip(['phone', 'country', 'city', 'address', 'locale']));
        if (! empty($extra)) {
            $user->fill($extra)->save();
        }

        return $user;
    }

    /**
     * Ensure user account is active (throws ValidationException if pending or suspended).
     *
     * @throws ValidationException
     */
    public function ensureAccountCanAuthenticate(User $user): void
    {
        if ($user->isPending()) {
            throw ValidationException::withMessages([
                'email' => [__('حسابك قيد المراجعة والموافقة من قِبل إدارة المنصة. يرجى الانتظار حتى يتم تفعيل الحساب لتتمكن من تسجيل الدخول.')],
            ]);
        }

        if ($user->isSuspended()) {
            throw ValidationException::withMessages([
                'email' => [__('تم إيقاف هذا الحساب. يرجى التواصل مع إدارة المنصة.')],
            ]);
        }
    }

    /**
     * Attempt email & password login for API or Web.
     * If 2FA is enabled on the account, returns a temporary challenge token (`two_factor_token`).
     *
     * @return array{
     *     two_factor_required: bool,
     *     two_factor_token?: string,
     *     user?: User,
     *     token?: string
     * }
     *
     * @throws ValidationException
     */
    public function attemptLogin(
        string $email,
        string $password,
        string $deviceName = 'api',
        bool $issueApiToken = true
    ): array {
        $normalizedEmail = Str::lower(trim($email));
        /** @var User|null $user */
        $user = User::where('email', $normalizedEmail)->first();

        if (! $user || ! Hash::check($password, (string) $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $this->ensureAccountCanAuthenticate($user);

        if ($user->hasEnabledTwoFactorAuthentication()) {
            $challengeToken = (string) Str::uuid();
            Cache::put("api_2fa_challenge:{$challengeToken}", [
                'user_id' => $user->id,
                'device_name' => $deviceName,
            ], now()->addMinutes(5));

            return [
                'two_factor_required' => true,
                'two_factor_token' => $challengeToken,
            ];
        }

        $token = $issueApiToken ? $this->issueToken($user, $deviceName) : null;

        return [
            'two_factor_required' => false,
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Complete 2FA login challenge using TOTP code or recovery code and issue Sanctum token.
     *
     * @return array{user: User, token: string}
     *
     * @throws ValidationException
     */
    public function verifyTwoFactorChallenge(
        string $twoFactorToken,
        ?string $code = null,
        ?string $recoveryCode = null,
        ?string $deviceName = null
    ): array {
        $payload = Cache::get("api_2fa_challenge:{$twoFactorToken}");
        if (! is_array($payload) || empty($payload['user_id'])) {
            throw ValidationException::withMessages([
                'two_factor_token' => [__('رمز التحقق الثنائي غير صالح أو منتهي الصلاحية. يرجى تسجيل الدخول مرة أخرى.')],
            ]);
        }

        /** @var User|null $user */
        $user = User::find($payload['user_id']);
        if (! $user) {
            Cache::forget("api_2fa_challenge:{$twoFactorToken}");
            throw ValidationException::withMessages([
                'two_factor_token' => [__('المستخدم غير موجود.')],
            ]);
        }

        $this->ensureAccountCanAuthenticate($user);

        $verified = false;
        if (! empty($code)) {
            $verified = $this->twoFactorService->verifyCode($user, $code);
        } elseif (! empty($recoveryCode)) {
            $verified = $this->twoFactorService->verifyRecoveryCode($user, $recoveryCode);
        }

        if (! $verified) {
            throw ValidationException::withMessages([
                'code' => [__('رمز المصادقة الثنائية غير صحيح.')],
            ]);
        }

        Cache::forget("api_2fa_challenge:{$twoFactorToken}");

        $effectiveDevice = $deviceName ?: (string) ($payload['device_name'] ?? 'api');

        return [
            'user' => $user,
            'token' => $this->issueToken($user, $effectiveDevice),
        ];
    }

    /**
     * Authenticate with WebAuthn Passkey assertion and issue Sanctum token.
     *
     * @param  array<string, mixed>  $credential
     * @return array{user: User, token: string}
     *
     * @throws ValidationException
     */
    public function loginWithPasskey(
        array $credential,
        string $challengeId,
        string $deviceName = 'passkey-api'
    ): array {
        $result = $this->passkeyService->verifyLogin($credential, $challengeId);
        /** @var User $user */
        $user = $result['user'];

        $this->ensureAccountCanAuthenticate($user);

        return [
            'user' => $user,
            'token' => $this->issueToken($user, $deviceName),
        ];
    }

    /**
     * Authenticate or register via Firebase ID Token and issue Sanctum token.
     *
     * @return array{
     *     user: User,
     *     status?: string,
     *     type?: string,
     *     message?: string,
     *     token?: string|null,
     *     redirect?: string
     * }
     *
     * @throws ValidationException
     */
    public function loginWithFirebase(
        string $idToken,
        string $deviceName = 'firebase-api',
        bool $loginWebSession = false,
        bool $remember = true
    ): array {
        $result = $this->firebaseAuthService->authenticateUser(
            idToken: $idToken,
            remember: $remember,
            loginWebSession: $loginWebSession
        );

        /** @var User $user */
        $user = $result['user'];

        if (($result['status'] ?? null) === 'pending') {
            return [
                'user' => $user,
                'status' => 'pending',
                'type' => 'info',
                'message' => $result['message'] ?? __('حسابك قيد المراجعة وبانتظار موافقة إدارة المنصة. يرجى الانتظار حتى يتم اعتماد الحساب لتتمكن من تسجيل الدخول.'),
                'token' => null,
            ];
        }

        return [
            'user' => $user,
            'status' => 'authenticated',
            'token' => $this->issueToken($user, $deviceName),
            'redirect' => $result['redirect'] ?? route('dashboard'),
        ];
    }

    /**
     * Authenticate or register a student using a Socialite Google User object (shared by Web callback & API token login).
     */
    public function resolveOrCreateGoogleUser(SocialiteUser $googleUser): User
    {
        $email = $googleUser->getEmail();
        if (blank($email)) {
            throw ValidationException::withMessages([
                'email' => [__('لم يتم العثور على بريد إلكتروني مرتبط بحساب Google.')],
            ]);
        }

        /** @var User|null $user */
        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            $user = User::where('email', $email)->first();
        }

        if ($user) {
            $updates = [];
            if (empty($user->google_id)) {
                $updates['google_id'] = $googleUser->getId();
            }
            if (empty($user->avatar) && $googleUser->getAvatar()) {
                $updates['avatar'] = $googleUser->getAvatar();
            }
            if (is_null($user->email_verified_at)) {
                $updates['email_verified_at'] = now();
            }
            if (! empty($updates)) {
                $user->forceFill($updates)->save();
            }
        } else {
            $user = new User;
            $user->forceFill([
                'name' => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'طالب الأمين'),
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'locale' => app()->getLocale(),
                'status' => 'pending',
                'password' => Hash::make(Str::random(32)),
            ]);
            $user->save();
        }

        return $user;
    }

    /**
     * Authenticate via Google OAuth access token for API clients.
     *
     * @return array{user: User, token: string}
     *
     * @throws ValidationException
     */
    public function loginWithGoogleAccessToken(string $accessToken, string $deviceName = 'google-api'): array
    {
        try {
            /** @var GoogleProvider $driver */
            $driver = Socialite::driver('google');
            $googleUser = $driver->stateless()->userFromToken($accessToken);
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'access_token' => [__('تعذر الاتصال بـ Google لتسجيل الدخول: ').$e->getMessage()],
            ]);
        }

        $user = $this->resolveOrCreateGoogleUser($googleUser);
        $this->ensureAccountCanAuthenticate($user);

        return [
            'user' => $user,
            'token' => $this->issueToken($user, $deviceName),
        ];
    }

    /**
     * Issue a Sanctum personal access token for the user.
     */
    public function issueToken(User $user, string $deviceName = 'api'): string
    {
        return $user->createToken($deviceName ?: 'api')->plainTextToken;
    }

    /**
     * Revoke current or all Sanctum tokens and log out active web session.
     */
    public function logout(User $user, bool $allDevices = false): void
    {
        try {
            if ($allDevices) {
                $user->tokens()->delete();
            } elseif ($user->currentAccessToken()) {
                /** @var \Laravel\Sanctum\PersonalAccessToken $accessToken */
                $accessToken = $user->currentAccessToken();
                if (method_exists($accessToken, 'delete')) {
                    $accessToken->delete();
                }
            }
        } catch (Throwable) {
            // Ignore if session-based token (TransientToken)
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
    }
}

