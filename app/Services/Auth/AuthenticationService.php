<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use App\Services\Auth\DTOs\LoginResult;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticationService
{
    public function __construct(
        protected TwoFactorService $twoFactorService,
        protected TokenManagementService $tokenManagementService,
        protected CreateNewUser $createNewUserAction
    ) {}

    /**
     * Attempt login with credentials for Web or API.
     *
     * @throws ValidationException
     */
    public function attemptLogin(
        string $email,
        string $password,
        bool $isApi = true,
        ?string $deviceName = 'API Token',
        bool $remember = false,
        ?string $ip = null
    ): LoginResult {
        $throttleKey = Str::transliterate(Str::lower($email).'|'.($ip ?? request()->ip()));

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => [__('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ])],
            ]);
        }

        $user = User::where('email', Str::lower($email))->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        RateLimiter::clear($throttleKey);

        if ($user->hasEnabledTwoFactorAuthentication()) {
            if ($isApi) {
                $challengeToken = $this->twoFactorService->createTwoFactorChallenge($user);

                return LoginResult::requiresTwoFactor($user, $challengeToken);
            }

            if (request()->hasSession()) {
                request()->session()->put([
                    'login.id' => $user->getKey(),
                    'login.remember' => $remember,
                ]);
            }

            return LoginResult::requiresTwoFactor($user, '');
        }

        if ($isApi) {
            $tokenInfo = $this->tokenManagementService->createToken($user, $deviceName ?? 'API Token');

            return LoginResult::success($user, $tokenInfo['plain_text_token']);
        }

        Auth::guard('web')->login($user, $remember);

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        return LoginResult::success($user);
    }

    /**
     * Complete a two-factor authentication login challenge.
     *
     * @throws ValidationException
     */
    public function completeTwoFactorLogin(
        string $challengeToken,
        ?string $code = null,
        ?string $recoveryCode = null,
        bool $isApi = true,
        ?string $deviceName = 'API Token',
        bool $remember = false
    ): LoginResult {
        $user = $this->twoFactorService->verifyTwoFactorChallenge($challengeToken, $code, $recoveryCode);

        if ($isApi) {
            $tokenInfo = $this->tokenManagementService->createToken($user, $deviceName ?? 'API Token');

            return LoginResult::success($user, $tokenInfo['plain_text_token']);
        }

        Auth::guard('web')->login($user, $remember);

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        return LoginResult::success($user);
    }

    /**
     * Register a new user and return the user and optional API token.
     *
     * @param  array<string, mixed>  $data
     * @return array{user: User, token: string|null}
     */
    public function register(
        array $data,
        bool $isApi = true,
        ?string $deviceName = 'API Token'
    ): array {
        $user = $this->createNewUserAction->create($data);

        event(new Registered($user));

        $token = null;

        if ($isApi) {
            $tokenInfo = $this->tokenManagementService->createToken($user, $deviceName ?? 'API Token');
            $token = $tokenInfo['plain_text_token'];
        } else {
            Auth::guard('web')->login($user);

            if (request()->hasSession()) {
                request()->session()->regenerate();
            }
        }

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Log out the user from API or Web session.
     */
    public function logout(?User $user = null, bool $allDevices = false): void
    {
        $user ??= Auth::user();

        if ($user) {
            if ($allDevices) {
                $this->tokenManagementService->revokeAllTokens($user);
            } else {
                $this->tokenManagementService->revokeCurrentToken($user);
            }
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (request()->hasSession()) {
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
    }

    /**
     * Get a comprehensive security profile overview for the given user.
     *
     * @return array<string, mixed>
     */
    public function getSecurityProfile(User $user): array
    {
        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified' => $user->hasVerifiedEmail(),
                'email_verified_at' => $user->email_verified_at?->toISOString(),
            ],
            'security' => [
                'two_factor_enabled' => $user->hasEnabledTwoFactorAuthentication(),
                'two_factor_confirmed_at' => $user->two_factor_confirmed_at?->toISOString(),
                'passkeys_count' => $user->passkeys()->count(),
                'api_tokens_count' => $user->tokens()->count(),
                'has_password' => ! empty($user->password),
            ],
        ];
    }
}
