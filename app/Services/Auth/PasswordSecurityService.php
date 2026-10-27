<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordSecurityService
{
    public function __construct(
        protected PasswordResetOtpService $otpService
    ) {}

    /**
     * Update the user's password after verifying the current password.
     *
     * @throws ValidationException
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => [__('The provided password does not match your current password.')],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($newPassword),
            'remember_token' => Str::random(60),
        ])->save();
    }

    /**
     * Send password reset OTP code to user's email.
     *
     * @throws ValidationException
     */
    public function sendResetOtp(string $email): string
    {
        return $this->otpService->sendOtp($email);
    }

    /**
     * Send password reset OTP (backward-compatible method).
     *
     * @throws ValidationException
     */
    public function sendResetLink(string $email): string
    {
        $this->otpService->sendOtp($email);

        return Password::RESET_LINK_SENT;
    }

    /**
     * Verify OTP code.
     *
     * @throws ValidationException
     */
    public function verifyOtp(string $email, string $otp): bool
    {
        return $this->otpService->verifyOtp($email, $otp);
    }

    /**
     * Reset password using OTP credentials (or fallback broker token).
     *
     * @param  array<string, mixed>  $credentials
     * @return string Status constant (Password::PASSWORD_RESET)
     *
     * @throws ValidationException
     */
    public function resetPassword(array $credentials): string
    {
        $email = (string) ($credentials['email'] ?? '');
        $code = (string) ($credentials['otp'] ?? $credentials['token'] ?? '');
        $password = (string) ($credentials['password'] ?? '');

        // If code is numeric 6-digit OTP or present, use OTP service
        if (! empty($code) && ! empty($email) && ! empty($password)) {
            $this->otpService->resetPassword($email, $code, $password);

            return Password::PASSWORD_RESET;
        }

        // Fallback to default password broker
        $status = Password::broker()->reset(
            $credentials,
            function (User $user, string $pwd) {
                $user->forceFill([
                    'password' => Hash::make($pwd),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return $status;
    }

    /**
     * Get underlying OTP service.
     */
    public function getOtpService(): PasswordResetOtpService
    {
        return $this->otpService;
    }
}
