<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use App\Notifications\ResetPasswordOtpNotification;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;

class PasswordResetOtpService
{
    /**
     * Expiration time in minutes for OTP codes.
     */
    public const OTP_EXPIRATION_MINUTES = 15;

    /**
     * Throttle limit in seconds between OTP requests.
     */
    public const THROTTLE_SECONDS = 60;

    /**
     * Generate and email a 6-digit OTP code for password reset.
     *
     * @throws ValidationException
     */
    public function sendOtp(string $email): string
    {
        $normalizedEmail = Str::lower(trim($email));

        $user = User::where('email', $normalizedEmail)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => [__('passwords.user')],
            ]);
        }

        // Throttle check
        $existing = DB::table('password_reset_tokens')
            ->where('email', $normalizedEmail)
            ->first();

        if ($existing && $existing->created_at) {
            $createdAt = Carbon::parse($existing->created_at);
            $secondsSinceCreation = Carbon::now()->diffInSeconds($createdAt);

            if ($secondsSinceCreation < self::THROTTLE_SECONDS) {
                $retryAfter = (int) (self::THROTTLE_SECONDS - $secondsSinceCreation);

                throw ValidationException::withMessages([
                    'email' => [__('auth.throttle', ['seconds' => $retryAfter])],
                ]);
            }
        }

        // Generate 6-digit numeric OTP
        $otp = sprintf('%06d', random_int(100000, 999999));

        // Save hashed OTP in database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $normalizedEmail],
            [
                'token' => Hash::make($otp),
                'created_at' => Carbon::now(),
            ]
        );

        // Send OTP notification
        try {
            $user->notify(new ResetPasswordOtpNotification($otp, self::OTP_EXPIRATION_MINUTES));
        } catch (TransportExceptionInterface $e) {
            Log::error('Password reset OTP email failed to send (SMTP/Transport error): '.$e->getMessage(), [
                'email' => $normalizedEmail,
                'exception' => $e,
            ]);

            throw ValidationException::withMessages([
                'email' => [__('auth.mail_transport_error')],
            ]);
        } catch (Throwable $e) {
            Log::error('Password reset OTP email failed with unexpected error: '.$e->getMessage(), [
                'email' => $normalizedEmail,
                'exception' => $e,
            ]);

            throw ValidationException::withMessages([
                'email' => [__('auth.mail_transport_error')],
            ]);
        }

        if (app()->environment('local', 'testing')) {
            Log::debug("Password reset OTP generated for [{$normalizedEmail}]: {$otp}");
        }

        return $otp;
    }

    /**
     * Verify if the provided OTP code is valid and active.
     *
     * @throws ValidationException
     */
    public function verifyOtp(string $email, string $otp): bool
    {
        $normalizedEmail = Str::lower(trim($email));
        $cleanOtp = trim($otp);

        $record = DB::table('password_reset_tokens')
            ->where('email', $normalizedEmail)
            ->first();

        if (! $record || ! $record->token || ! $record->created_at) {
            throw ValidationException::withMessages([
                'otp' => [__('auth.invalid_otp')],
            ]);
        }

        $createdAt = Carbon::parse($record->created_at);
        if ($createdAt->addMinutes(self::OTP_EXPIRATION_MINUTES)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $normalizedEmail)->delete();

            throw ValidationException::withMessages([
                'otp' => [__('auth.otp_expired')],
            ]);
        }

        if (! Hash::check($cleanOtp, $record->token)) {
            throw ValidationException::withMessages([
                'otp' => [__('auth.invalid_otp')],
            ]);
        }

        return true;
    }

    /**
     * Reset the user password using OTP.
     *
     * @throws ValidationException
     */
    public function resetPassword(string $email, string $otp, string $password): void
    {
        $normalizedEmail = Str::lower(trim($email));

        $this->verifyOtp($normalizedEmail, $otp);

        $user = User::where('email', $normalizedEmail)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => [__('passwords.user')],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();

        // Immediately invalidate OTP upon successful password reset
        DB::table('password_reset_tokens')->where('email', $normalizedEmail)->delete();

        event(new PasswordReset($user));
    }
}
