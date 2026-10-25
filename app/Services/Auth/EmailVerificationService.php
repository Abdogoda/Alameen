<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Validation\ValidationException;

class EmailVerificationService
{
    /**
     * Send email verification notification to the user if not already verified.
     */
    public function sendVerificationNotification(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        $user->sendEmailVerificationNotification();

        return true;
    }

    /**
     * Verify email with hash and dispatch Verified event.
     *
     * @throws ValidationException
     */
    public function verify(User $user, string $hash): bool
    {
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw ValidationException::withMessages([
                'email' => [__('The verification link is invalid or has expired.')],
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return true;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return true;
    }
}
