<?php

declare(strict_types=1);

namespace App\Services\Auth\DTOs;

use App\Models\User;

readonly class LoginResult
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public bool $isSuccessful,
        public bool $requiresTwoFactor,
        public ?User $user = null,
        public ?string $token = null,
        public ?string $twoFactorChallengeToken = null,
        public ?string $message = null,
        public array $extra = []
    ) {}

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function success(User $user, ?string $token = null, string $message = 'Authenticated successfully.', array $extra = []): self
    {
        return new self(
            isSuccessful: true,
            requiresTwoFactor: false,
            user: $user,
            token: $token,
            message: $message,
            extra: $extra
        );
    }

    public static function requiresTwoFactor(User $user, string $twoFactorChallengeToken, string $message = 'Two-factor authentication code required.'): self
    {
        return new self(
            isSuccessful: false,
            requiresTwoFactor: true,
            user: $user,
            twoFactorChallengeToken: $twoFactorChallengeToken,
            message: $message
        );
    }

    public static function failed(string $message = 'Invalid credentials.'): self
    {
        return new self(
            isSuccessful: false,
            requiresTwoFactor: false,
            message: $message
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        if ($this->requiresTwoFactor) {
            return [
                'status' => 'requires_two_factor',
                'message' => $this->message,
                'two_factor_challenge_token' => $this->twoFactorChallengeToken,
            ];
        }

        if ($this->isSuccessful) {
            $data = [
                'status' => 'success',
                'message' => $this->message,
                'user' => [
                    'id' => $this->user?->id,
                    'name' => $this->user?->name,
                    'email' => $this->user?->email,
                    'email_verified_at' => $this->user?->email_verified_at,
                ],
            ];

            if ($this->token !== null) {
                $data['token'] = $this->token;
                $data['token_type'] = 'Bearer';
            }

            return array_merge($data, $this->extra);
        }

        return [
            'status' => 'failed',
            'message' => $this->message,
        ];
    }
}
