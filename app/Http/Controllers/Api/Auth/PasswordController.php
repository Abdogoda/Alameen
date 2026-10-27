<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\UpdatePasswordRequest;
use App\Http\Requests\Api\VerifyOtpRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\PasswordSecurityService;
use Illuminate\Http\JsonResponse;

class PasswordController extends Controller
{
    public function __construct(
        protected PasswordSecurityService $passwordService
    ) {}

    /**
     * Update authenticated user's password.
     */
    public function update(UpdatePasswordRequest $request): JsonResponse
    {
        $this->passwordService->updatePassword(
            user: $request->user(),
            currentPassword: $request->string('current_password')->toString(),
            newPassword: $request->string('password')->toString()
        );

        return ApiResponse::success(
            message: __('Password updated successfully.')
        );
    }

    /**
     * Send password reset OTP code to user's email.
     */
    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $this->passwordService->sendResetOtp($request->string('email')->toString());

        return ApiResponse::success(
            message: __('A password reset code has been sent to your email.')
        );
    }

    /**
     * Verify password reset OTP code.
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $email = $request->string('email')->toString();
        $otp = $request->string('otp')->toString();

        $this->passwordService->verifyOtp($email, $otp);

        return ApiResponse::success(
            data: [
                'email' => $email,
                'verified' => true,
            ],
            message: __('Verification code is valid.')
        );
    }

    /**
     * Reset password using OTP code from email.
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $this->passwordService->resetPassword($request->validated());

        return ApiResponse::success(
            message: __('Password has been reset successfully.')
        );
    }
}
