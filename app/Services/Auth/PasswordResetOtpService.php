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

        // Throttle check (cooldown between OTP requests)
        $existing = DB::table('password_reset_tokens')
            ->where('email', $normalizedEmail)
            ->first();

        if ($existing && $existing->created_at) {
            $throttleUntil = Carbon::parse($existing->created_at)->addSeconds(self::THROTTLE_SECONDS);

            if ($throttleUntil->isFuture()) {
                $retryAfter = (int) ceil(Carbon::now()->diffInSeconds($throttleUntil, false));
                $retryAfter = max(1, min(self::THROTTLE_SECONDS, $retryAfter));

                throw ValidationException::withMessages([
                    'email' => [__('auth.otp_throttle', ['seconds' => $retryAfter])],
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
            Log::warning("Password reset OTP email failed to send to [{$normalizedEmail}] (SMTP/Transport error). Sent OTP to log instead. OTP: [{$otp}]", [
                'email' => $normalizedEmail,
                'otp' => $otp,
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);
        } catch (Throwable $e) {
            Log::warning("Password reset OTP email failed to send to [{$normalizedEmail}] (unexpected error). Sent OTP to log instead. OTP: [{$otp}]", [
                'email' => $normalizedEmail,
                'otp' => $otp,
                'error' => $e->getMessage(),
                'exception' => $e,
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
