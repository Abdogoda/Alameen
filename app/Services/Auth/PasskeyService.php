<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Passkeys\Actions\DeletePasskey;
use Laravel\Passkeys\Actions\GenerateRegistrationOptions;
use Laravel\Passkeys\Actions\GenerateVerificationOptions;
use Laravel\Passkeys\Actions\StorePasskey;
use Laravel\Passkeys\Actions\VerifyPasskey;
use Laravel\Passkeys\Exceptions\InvalidPasskeyException;
use Laravel\Passkeys\Passkey;
use Laravel\Passkeys\Support\WebAuthn;
use Throwable;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRequestOptions;

class PasskeyService
{
    public function __construct(
        protected GenerateVerificationOptions $generateVerificationOptions,
        protected VerifyPasskey $verifyPasskey,
        protected GenerateRegistrationOptions $generateRegistrationOptions,
        protected StorePasskey $storePasskey,
        protected DeletePasskey $deletePasskey
    ) {}

    /**
     * Generate passkey authentication assertion options for login.
     *
     * @return array{challenge_id: string, options: array<string, mixed>}
     */
    public function generateLoginOptions(?User $user = null): array
    {
        $options = ($this->generateVerificationOptions)($user);
        $challengeId = (string) Str::uuid();

        Cache::put("passkey_login:{$challengeId}", WebAuthn::toJson($options), now()->addMinutes(3));

        return [
            'challenge_id' => $challengeId,
            'options' => WebAuthn::toBrowserArray($options),
        ];
    }

    /**
     * Verify passkey response and authenticate the user.
     *
     * @param  array<string, mixed>  $credential
     * @return array{user: User, passkey: Passkey}
     *
     * @throws ValidationException
     */
    public function verifyLogin(array $credential, string $challengeId, ?User $expectedUser = null): array
    {
        $serialized = Cache::pull("passkey_login:{$challengeId}");

        if (! $serialized) {
            throw ValidationException::withMessages([
                'challenge_id' => [__('Passkey verification challenge expired or is invalid. Please try again.')],
            ]);
        }

        try {
            $credentialObj = WebAuthn::fromJson(
                json_encode($credential) ?: '{}',
                PublicKeyCredential::class
            );

            $optionsObj = WebAuthn::fromJson($serialized, PublicKeyCredentialRequestOptions::class);

            $passkey = ($this->verifyPasskey)($credentialObj, $optionsObj, $expectedUser);
            /** @var User $user */
            $user = $passkey->user instanceof User ? $passkey->user : User::findOrFail($passkey->user_id);

            return [
                'user' => $user,
                'passkey' => $passkey,
            ];
        } catch (InvalidPasskeyException $e) {
            throw ValidationException::withMessages([
                'credential' => [$e->getMessage()],
            ]);
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'credential' => [__('Unable to verify passkey: :message', ['message' => $e->getMessage()])],
            ]);
        }
    }

    /**
     * Generate passkey creation options for registration for an authenticated user.
     *
     * @return array{challenge_id: string, options: array<string, mixed>}
     */
    public function generateRegistrationOptions(User $user): array
    {
        $options = ($this->generateRegistrationOptions)($user);
        $challengeId = (string) Str::uuid();

        Cache::put("passkey_reg:{$challengeId}:{$user->id}", WebAuthn::toJson($options), now()->addMinutes(3));

        return [
            'challenge_id' => $challengeId,
            'options' => WebAuthn::toBrowserArray($options),
        ];
    }

    /**
     * Store a newly created passkey for the authenticated user.
     *
     * @param  array<string, mixed>  $credential
     *
     * @throws ValidationException
     */
    public function registerPasskey(User $user, string $name, array $credential, string $challengeId): Passkey
    {
        $serialized = Cache::pull("passkey_reg:{$challengeId}:{$user->id}");

        if (! $serialized) {
            throw ValidationException::withMessages([
                'challenge_id' => [__('Passkey registration challenge expired or is invalid. Please try again.')],
            ]);
        }

        try {
            $credentialObj = WebAuthn::fromJson(
                json_encode($credential) ?: '{}',
                PublicKeyCredential::class
            );

            $optionsObj = WebAuthn::fromJson($serialized, PublicKeyCredentialCreationOptions::class);

            return ($this->storePasskey)($user, $name, $credentialObj, $optionsObj);
        } catch (InvalidPasskeyException $e) {
            throw ValidationException::withMessages([
                'credential' => [$e->getMessage()],
            ]);
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'credential' => [__('Unable to register passkey: :message', ['message' => $e->getMessage()])],
            ]);
        }
    }

    /**
     * List user passkeys.
     *
     * @return Collection<int, array{id: int, name: string, created_at: string|null, last_used_at: string|null, created_at_human: string, last_used_at_human: string|null}>
     */
    public function listPasskeys(User $user): Collection
    {
        return $user->passkeys()
            ->latest()
            ->get()
            ->map(fn (Passkey $passkey) => [
                'id' => $passkey->id,
                'name' => $passkey->name,
                'created_at' => $passkey->created_at?->toISOString(),
                'last_used_at' => $passkey->last_used_at?->toISOString(),
                'created_at_human' => $passkey->created_at?->diffForHumans() ?? '',
                'last_used_at_human' => $passkey->last_used_at?->diffForHumans(),
            ]);
    }

    /**
     * Delete a passkey belonging to the user.
     *
     * @throws ValidationException
     */
    public function deletePasskey(User $user, int $passkeyId): void
    {
        $passkey = $user->passkeys()->find($passkeyId);

        if (! $passkey) {
            throw ValidationException::withMessages([
                'passkey' => [__('Passkey not found or does not belong to the user.')],
            ]);
        }

        ($this->deletePasskey)($user, $passkey);
    }
}
