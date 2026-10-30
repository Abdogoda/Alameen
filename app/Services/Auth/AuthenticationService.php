<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;

class AuthenticationService
{
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
                'has_password' => ! empty($user->password),
            ],
        ];
    }
}
