<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FirebaseAuthRequest;
use App\Http\Requests\Auth\ForgotPasswordOtpRequest;
use App\Http\Requests\Auth\GoogleTokenLoginRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasskeyLoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordWithOtpRequest;
use App\Http\Requests\Auth\TwoFactorLoginRequest;
use App\Http\Requests\Auth\VerifyEmailOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Services\Auth\EmailVerificationOtpService;
use App\Services\Auth\PasskeyService;
use App\Services\Auth\PasswordSecurityService;
use App\Services\Auth\StudentAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct(
        protected StudentAuthService $studentAuthService,
        protected PasskeyService $passkeyService,
        protected PasswordSecurityService $passwordSecurityService,
    ) {}

    /**
     * POST /api/v1/auth/register
     * Register a new student account (status = 'pending' awaiting admin approval).
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->studentAuthService->register($request->validated());

        return response()->json([
            'success' => true,
            'status' => $user->status ?? 'pending',
            'message' => __('تم إنشاء حسابك بنجاح وهو الآن قيد المراجعة من قِبل إدارة المنصة.'),
            'data' => [
                'user' => new UserResource($user),
            ],
        ], 201);
    }

    /**
     * POST /api/v1/auth/login
     * Authenticate a student via email & password and issue a Sanctum Bearer token (or 2FA challenge).
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->studentAuthService->attemptLogin(
            (string) $request->validated('email'),
            (string) $request->validated('password'),
            (string) ($request->validated('device_name') ?: 'api'),
            true
        );

        if ($result['two_factor_required']) {
            return response()->json([
                'success' => true,
                'status' => 'two_factor_required',
                'two_factor_required' => true,
                'message' => __('يرجى إدخال رمز المصادقة الثنائية لإتمام تسجيل الدخول.'),
                'data' => [
                    'two_factor_token' => $result['two_factor_token'],
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 'authenticated',
            'two_factor_required' => false,
            'message' => __('تم تسجيل الدخول بنجاح.'),
            'data' => [
                'token' => $result['token'],
                'token_type' => 'Bearer',
                'user' => new UserResource($result['user']),
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/two-factor-challenge
     * Complete 2FA challenge using TOTP code or recovery code and issue a Sanctum token.
     */
    public function twoFactorChallenge(TwoFactorLoginRequest $request): JsonResponse
    {
        $result = $this->studentAuthService->verifyTwoFactorChallenge(
            (string) $request->validated('two_factor_token'),
            $request->validated('code'),
            $request->validated('recovery_code'),
            $request->validated('device_name')
        );

        return response()->json([
            'success' => true,
            'status' => 'authenticated',
            'message' => __('تم التحقق من رمز المصادقة الثنائية وتسجيل الدخول بنجاح.'),
            'data' => [
                'token' => $result['token'],
                'token_type' => 'Bearer',
                'user' => new UserResource($result['user']),
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/firebase
     * Authenticate or register a student using a Firebase ID Token.
     */
    public function firebase(FirebaseAuthRequest $request): JsonResponse
    {
        $result = $this->studentAuthService->loginWithFirebase(
            (string) $request->validated('id_token'),
            (string) ($request->input('device_name') ?: 'firebase-api'),
            false,
            (bool) $request->boolean('remember', true)
        );

        return response()->json([
            'success' => true,
            'status' => 'authenticated',
            'message' => __('تم تسجيل الدخول عبر Firebase بنجاح.'),
            'data' => [
                'token' => $result['token'],
                'token_type' => 'Bearer',
                'user' => new UserResource($result['user']),
            ],
        ]);
    }

    /**
     * GET /api/v1/auth/google/redirect
     * Return Google OAuth redirect URL for web/mobile OAuth flow.
     */
    public function googleRedirect(): JsonResponse
    {
        $redirectUrl = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'success' => true,
            'data' => [
                'redirect_url' => $redirectUrl,
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/google/token
     * Authenticate or register a student using a Google OAuth access token.
     */
    public function googleTokenLogin(GoogleTokenLoginRequest $request): JsonResponse
    {
        $result = $this->studentAuthService->loginWithGoogleAccessToken(
            (string) $request->validated('access_token'),
            (string) ($request->validated('device_name') ?: 'google-api')
        );

        return response()->json([
            'success' => true,
            'status' => 'authenticated',
            'message' => __('تم تسجيل الدخول عبر Google بنجاح.'),
            'data' => [
                'token' => $result['token'],
                'token_type' => 'Bearer',
                'user' => new UserResource($result['user']),
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/passkeys/login-options
     * Generate WebAuthn passkey authentication challenge options.
     */
    public function passkeyLoginOptions(Request $request): JsonResponse
    {
        $user = null;
        if ($request->filled('email')) {
            $user = User::where('email', strtolower(trim((string) $request->input('email'))))->first();
        }

        $data = $this->passkeyService->generateLoginOptions($user);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * POST /api/v1/auth/passkeys/login
     * Verify WebAuthn passkey assertion and issue a Sanctum Bearer token.
     */
    public function passkeyLogin(PasskeyLoginRequest $request): JsonResponse
    {
        $result = $this->studentAuthService->loginWithPasskey(
            (array) $request->validated('credential'),
            (string) $request->validated('challenge_id'),
            (string) ($request->validated('device_name') ?: 'passkey-api')
        );

        return response()->json([
            'success' => true,
            'status' => 'authenticated',
            'message' => __('تم تسجيل الدخول بواسطة مفتاح المرور (Passkey) بنجاح.'),
            'data' => [
                'token' => $result['token'],
                'token_type' => 'Bearer',
                'user' => new UserResource($result['user']),
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/forgot-password
     * Send a 6-digit OTP to the student's email for password recovery.
     */
    public function forgotPassword(ForgotPasswordOtpRequest $request): JsonResponse
    {
        $this->passwordSecurityService->sendResetOtp((string) $request->validated('email'));

        return response()->json([
            'success' => true,
            'message' => __('تم إرسال رمز التحقق المكون من 6 أرقام إلى بريدك الإلكتروني بنجاح.'),
        ]);
    }

    /**
     * POST /api/v1/auth/verify-otp
     * Verify the 6-digit password reset OTP before submitting a new password.
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $this->passwordSecurityService->verifyOtp(
            (string) $request->validated('email'),
            (string) $request->validated('otp')
        );

        return response()->json([
            'success' => true,
            'valid' => true,
            'message' => __('رمز التحقق صحيح. يمكنك الآن تعيين كلمة المرور الجديدة.'),
        ]);
    }

    /**
     * POST /api/v1/auth/reset-password
     * Reset password using the 6-digit OTP and optionally return an active Sanctum token.
     */
    public function resetPassword(ResetPasswordWithOtpRequest $request): JsonResponse
    {
        $this->passwordSecurityService->resetPassword($request->validated());

        /** @var User|null $user */
        $user = User::where('email', strtolower(trim((string) $request->validated('email'))))->first();
        $token = ($user && $user->isActive())
            ? $this->studentAuthService->issueToken($user, (string) $request->input('device_name', 'api'))
            : null;

        return response()->json([
            'success' => true,
            'message' => __('تم استعادة وتغيير كلمة المرور بنجاح.'),
            'data' => [
                'token' => $token,
                'token_type' => $token ? 'Bearer' : null,
                'user' => $user ? new UserResource($user) : null,
            ],
        ]);
    }

    /**
     * GET /api/v1/auth/me
     * Return the currently authenticated student's profile.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($request->user()),
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/email/verification-notification
     * Resend 6-digit email verification OTP code to the authenticated student.
     */
    public function resendVerification(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'already_verified' => true,
                'message' => __('البريد الإلكتروني مؤكد بالفعل.'),
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'already_verified' => false,
            'message' => __('تم إرسال رمز تأكيد البريد الإلكتروني (OTP) بنجاح.'),
        ]);
    }

    /**
     * POST /api/v1/auth/email/verify-otp
     * Verify the student's email using the 6-digit OTP code.
     */
    public function verifyEmailOtp(VerifyEmailOtpRequest $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'already_verified' => true,
                'message' => __('البريد الإلكتروني مؤكد بالفعل.'),
                'data' => [
                    'user' => new UserResource($user),
                ],
            ]);
        }

        app(EmailVerificationOtpService::class)->verifyOtp(
            $user,
            (string) $request->validated('otp')
        );

        return response()->json([
            'success' => true,
            'already_verified' => false,
            'message' => __('تم تأكيد البريد الإلكتروني بنجاح.'),
            'data' => [
                'user' => new UserResource($user->fresh()),
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/logout
     * Revoke the current Sanctum token (or all tokens if `all_devices` is true).
     */
    public function logout(Request $request): JsonResponse
    {
        $this->studentAuthService->logout(
            $request->user(),
            (bool) $request->boolean('all_devices')
        );

        return response()->json([
            'success' => true,
            'message' => __('تم تسجيل الخروج بنجاح.'),
        ]);
    }
}
