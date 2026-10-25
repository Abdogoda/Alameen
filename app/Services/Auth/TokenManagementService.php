<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Laravel\Sanctum\PersonalAccessToken;

class TokenManagementService
{
    /**
     * Create a new Personal Access Token for the user.
     *
     * @param  array<int, string>  $abilities
     * @return array{plain_text_token: string, token: PersonalAccessToken}
     */
    public function createToken(
        User $user,
        string $name,
        array $abilities = ['*'],
        ?DateTimeInterface $expiresAt = null
    ): array {
        $token = $user->createToken($name, $abilities, $expiresAt);

        return [
            'plain_text_token' => $token->plainTextToken,
            'token' => $token->accessToken,
        ];
    }

    /**
     * List all personal access tokens for the user.
     *
     * @return Collection<int, array{id: int, name: string, abilities: array<mixed>|null, last_used_at: string|null, expires_at: string|null, created_at: string|null}>
     */
    public function listTokens(User $user): Collection
    {
        return $user->tokens()
            ->latest()
            ->get()
            ->map(fn (PersonalAccessToken $token) => [
                'id' => $token->id,
                'name' => $token->name,
                'abilities' => $token->abilities,
                'last_used_at' => $token->last_used_at?->toISOString(),
                'expires_at' => $token->expires_at?->toISOString(),
                'created_at' => $token->created_at?->toISOString(),
            ]);
    }

    /**
     * Revoke a specific token belonging to the user.
     */
    public function revokeToken(User $user, int $tokenId): bool
    {
        return (bool) $user->tokens()->where('id', $tokenId)->delete();
    }

    /**
     * Revoke the current access token being used.
     */
    public function revokeCurrentToken(User $user): bool
    {
        return (bool) $user->currentAccessToken()->delete();
    }

    /**
     * Revoke all tokens except the current one.
     */
    public function revokeOtherTokens(User $user): void
    {
        $currentToken = $user->currentAccessToken();

        $user->tokens()
            ->when($currentToken, fn ($query) => $query->where('id', '!=', $currentToken->id))
            ->delete();
    }

    /**
     * Revoke all tokens for the user.
     */
    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}
