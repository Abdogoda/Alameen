<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Profile\DeleteAccountRequest;
use App\Http\Requests\Student\Profile\UpdateProfileRequest;
use App\Http\Requests\Student\Security\ConfirmTwoFactorRequest;
use App\Http\Requests\Student\Security\RegisterPasskeyRequest;
use App\Http\Requests\Student\Security\UpdatePasswordRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\Auth\PasskeyService;
use App\Services\Auth\PasswordSecurityService;
use App\Services\Auth\StudentProfileService;
use App\Services\Auth\TwoFactorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(
        protected StudentProfileService $profileService,
        protected PasswordSecurityService $passwordSecurityService,
        protected TwoFactorService $twoFactorService,
        protected PasskeyService $passkeyService,
    ) {}

    /**
     * GET /api/v1/student/settings/profile
     * Return student profile details.
     */
    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($request->user()),
            ],
        ]);
    }

    /**
     * POST /api/v1/student/settings/profile
     * Update student profile (supports multipart avatar upload and locale sync).
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->profileService->updateProfile(
            $request->user(),
            $request->validated(),
            $request->file('avatar')
        );

        return response()->json([
            'success' => true,
            'message' => __('تم تحديث الملف الشخصي بنجاح.'),
            'data' => [
                'user' => new UserResource($user),
            ],
        ]);
    }

    /**
     * DELETE /api/v1/student/settings/profile
     * Permanently delete the student account after verifying the current password.
     */
    public function deleteAccount(DeleteAccountRequest $request): JsonResponse
    {
        $this->profileService->deleteAccount(
            $request->user(),
            (string) $request->validated('current_password')
        );

        return response()->json([
            'success' => true,
            'message' => __('تم حذف الحساب نهائياً.'),
        ]);
    }

    /**
     * PUT /api/v1/student/settings/password
     * Update student password after verifying the current password.
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $this->passwordSecurityService->updatePassword(
            $request->user(),
            (string) $request->validated('current_password'),
            (string) $request->validated('password')
        );

        return response()->json([
            'success' => true,
            'message' => __('تم تحديث كلمة المرور بنجاح.'),
        ]);
    }

    /**
     * GET /api/v1/student/settings/security
     * Return 2FA status and registered passkeys for the student.
     */
    public function securityOverview(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'two_factor_enabled' => $user->hasEnabledTwoFactorAuthentication(),
                'passkeys' => $this->passkeyService->listPasskeys($user),
            ],
        ]);
    }

    /**
     * POST /api/v1/student/settings/two-factor/enable
     * Enable 2FA setup and return secret, QR code SVG, and setup URL.
     */
    public function enableTwoFactor(Request $request): JsonResponse
    {
        $setup = $this->twoFactorService->enable(
            $request->user(),
            (bool) $request->boolean('force', false)
        );

        return response()->json([
            'success' => true,
            'message' => __('تم إنشاء مفتاح المصادقة الثنائية. يرجى مسح رمز QR وتأكيده بالرمز المكون من 6 أرقام.'),
            'data' => $setup,
        ]);
    }

    /**
     * POST /api/v1/student/settings/two-factor/confirm
     * Confirm 2FA setup with a 6-digit TOTP code and return recovery codes.
     */
    public function confirmTwoFactor(ConfirmTwoFactorRequest $request): JsonResponse
    {
        $result = $this->twoFactorService->confirm(
            $request->user(),
            (string) $request->validated('code')
        );

        return response()->json([
            'success' => true,
            'message' => __('تم تفعيل المصادقة الثنائية بنجاح.'),
            'data' => $result,
        ]);
    }

    /**
     * DELETE /api/v1/student/settings/two-factor
     * Disable 2FA for the authenticated student.
     */
    public function disableTwoFactor(Request $request): JsonResponse
    {
        $this->twoFactorService->disable($request->user());

        return response()->json([
            'success' => true,
            'message' => __('تم إيقاف المصادقة الثنائية.'),
        ]);
    }

    /**
     * GET /api/v1/student/settings/two-factor/recovery-codes
     * Return the student's current 2FA recovery codes.
     */
    public function recoveryCodes(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'recovery_codes' => $this->twoFactorService->getRecoveryCodes($request->user()),
            ],
        ]);
    }

    /**
     * POST /api/v1/student/settings/two-factor/recovery-codes
     * Regenerate new 2FA recovery codes for the student.
     */
    public function regenerateRecoveryCodes(Request $request): JsonResponse
    {
        $codes = $this->twoFactorService->regenerateRecoveryCodes($request->user());

        return response()->json([
            'success' => true,
            'message' => __('تم إنشاء رموز استرداد جديدة بنجاح.'),
            'data' => [
                'recovery_codes' => $codes,
            ],
        ]);
    }

    /**
     * GET /api/v1/student/settings/passkeys
     * List all WebAuthn passkeys registered by the student.
     */
    public function listPasskeys(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'passkeys' => $this->passkeyService->listPasskeys($request->user()),
            ],
        ]);
    }

    /**
     * POST /api/v1/student/settings/passkeys/options
     * Generate WebAuthn passkey registration challenge options.
     */
    public function passkeyOptions(Request $request): JsonResponse
    {
        $options = $this->passkeyService->generateRegistrationOptions($request->user());

        return response()->json([
            'success' => true,
            'data' => $options,
        ]);
    }

    /**
     * POST /api/v1/student/settings/passkeys
     * Store a newly registered WebAuthn passkey for the student.
     */
    public function registerPasskey(RegisterPasskeyRequest $request): JsonResponse
    {
        $passkey = $this->passkeyService->registerPasskey(
            $request->user(),
            (string) $request->validated('name'),
            (array) $request->validated('credential'),
            (string) $request->validated('challenge_id')
        );

        return response()->json([
            'success' => true,
            'message' => __('تمت إضافة مفتاح المرور (Passkey) بنجاح.'),
            'data' => [
                'passkey' => [
                    'id' => $passkey->id,
                    'name' => $passkey->name,
                    'created_at' => $passkey->created_at?->toISOString(),
                ],
            ],
        ], 201);
    }

    /**
     * DELETE /api/v1/student/settings/passkeys/{passkeyId}
     * Delete a registered WebAuthn passkey belonging to the student.
     */
    public function deletePasskey(Request $request, int $passkeyId): JsonResponse
    {
        $this->passkeyService->deletePasskey($request->user(), $passkeyId);

        return response()->json([
            'success' => true,
            'message' => __('تم حذف مفتاح المرور (Passkey) بنجاح.'),
        ]);
    }
}

