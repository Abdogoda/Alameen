<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\Admin;
use App\Models\AdminPasskey;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Passkeys\Actions\GenerateRegistrationOptions;
use Laravel\Passkeys\Actions\GenerateVerificationOptions;
use Laravel\Passkeys\Exceptions\InvalidPasskeyException;
use Laravel\Passkeys\Passkeys;
use Laravel\Passkeys\Support\WebAuthn;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Throwable;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\CredentialRecord;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRequestOptions;

class AdminPasskeyService
{
    public function __construct(
        protected GenerateVerificationOptions $generateVerificationOptions,
        protected GenerateRegistrationOptions $generateRegistrationOptions
    ) {}

    /**
     * Generate WebAuthn verification assertion options for admin login.
     *
     * @return array{challenge_id: string, options: array<string, mixed>}
     */
    public function generateLoginOptions(?Admin $admin = null): array
    {
        $options = ($this->generateVerificationOptions)($admin);
        $challengeId = (string) Str::uuid();
        $serialized = WebAuthn::toJson($options);

        Cache::put("admin_passkey_login:{$challengeId}", $serialized, now()->addMinutes(5));
        if (request()->hasSession()) {
            request()->session()->put('admin_passkey_login_options', $serialized);
        }

        return [
            'challenge_id' => $challengeId,
            'options' => WebAuthn::toBrowserArray($options),
        ];
    }

    /**
     * Verify assertion response from browser and authenticate the administrator.
     *
     * @param  array<string, mixed>  $credential
     * @return array{admin: Admin, passkey: AdminPasskey}
     *
     * @throws ValidationException
     */
    public function verifyLogin(array $credential, ?string $challengeId = null, bool $remember = true): array
    {
        $serialized = null;

        if ($challengeId) {
            $serialized = Cache::pull("admin_passkey_login:{$challengeId}");
        }

        if (! $serialized && request()->hasSession()) {
            $serialized = request()->session()->pull('admin_passkey_login_options');
        }

        if (! $serialized) {
            throw ValidationException::withMessages([
                'credential' => [__('انتهت مهلة التحقق من مفتاح المرور أو أن الجلسة منتهية. يرجى المحاولة مرة أخرى.')],
            ]);
        }

        try {
            /** @var PublicKeyCredential $credentialObj */
            $credentialObj = WebAuthn::fromJson(
                json_encode($credential) ?: '{}',
                PublicKeyCredential::class
            );

            /** @var PublicKeyCredentialRequestOptions $optionsObj */
            $optionsObj = WebAuthn::fromJson($serialized, PublicKeyCredentialRequestOptions::class);

            if (! $credentialObj->response instanceof AuthenticatorAssertionResponse) {
                throw InvalidPasskeyException::make(__('تعذر التحقق من استجابة مفتاح المرور. يرجى المحاولة مجدداً.'));
            }

            $credentialId = Base64UrlSafe::encodeUnpadded($credentialObj->rawId);

            /** @var AdminPasskey|null $passkey */
            $passkey = AdminPasskey::where('credential_id', $credentialId)->with('admin.role')->first();

            if (! $passkey || ! $passkey->admin) {
                throw ValidationException::withMessages([
                    'credential' => [__('مفتاح المرور غير مسجل في بوابة المشرفين أو تم حذفه من الحساب.')],
                ]);
            }

            /** @var CredentialRecord $source */
            $source = WebAuthn::fromJson(
                json_encode($passkey->credential, JSON_THROW_ON_ERROR),
                CredentialRecord::class
            );

            $validatedSource = WebAuthn::assertionValidator()->check(
                credentialRecord: $source,
                authenticatorAssertionResponse: $credentialObj->response,
                publicKeyCredentialRequestOptions: $optionsObj,
                host: Passkeys::relyingPartyId(),
                userHandle: $source->userHandle,
            );

            $passkey->forceFill([
                'credential' => json_decode(WebAuthn::toJson($validatedSource), true, flags: JSON_THROW_ON_ERROR),
                'last_used_at' => now(),
            ])->save();

            // Authenticate the admin using the dedicated admin guard
            Auth::guard('admin')->login($passkey->admin, $remember);
            request()->session()->regenerate();

            return [
                'admin' => $passkey->admin,
                'passkey' => $passkey,
            ];
        } catch (InvalidPasskeyException $e) {
            throw ValidationException::withMessages([
                'credential' => [$e->getMessage()],
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'credential' => [__('فشل التحقق من مفتاح المرور: :message', ['message' => $e->getMessage()])],
            ]);
        }
    }

    /**
     * Generate passkey creation options for the currently authenticated admin.
     *
     * @return array{challenge_id: string, options: array<string, mixed>}
     */
    public function generateRegistrationOptions(Admin $admin): array
    {
        $options = ($this->generateRegistrationOptions)($admin);
        $challengeId = (string) Str::uuid();
        $serialized = WebAuthn::toJson($options);

        Cache::put("admin_passkey_reg:{$challengeId}:{$admin->id}", $serialized, now()->addMinutes(5));
        if (request()->hasSession()) {
            request()->session()->put('admin_passkey_reg_options', $serialized);
        }

        return [
            'challenge_id' => $challengeId,
            'options' => WebAuthn::toBrowserArray($options),
        ];
    }

    /**
     * Store and register a newly created passkey for the authenticated admin.
     *
     * @param  array<string, mixed>  $credential
     *
     * @throws ValidationException
     */
    public function registerPasskey(Admin $admin, string $name, array $credential, ?string $challengeId = null): AdminPasskey
    {
        $serialized = null;

        if ($challengeId) {
            $serialized = Cache::pull("admin_passkey_reg:{$challengeId}:{$admin->id}");
        }

        if (! $serialized && request()->hasSession()) {
            $serialized = request()->session()->pull('admin_passkey_reg_options');
        }

        if (! $serialized) {
            throw ValidationException::withMessages([
                'credential' => [__('انتهت مهلة تسجيل مفتاح المرور أو أن الجلسة منتهية. يرجى إعادة المحاولة.')],
            ]);
        }

        try {
            /** @var PublicKeyCredential $credentialObj */
            $credentialObj = WebAuthn::fromJson(
                json_encode($credential) ?: '{}',
                PublicKeyCredential::class
            );

            /** @var PublicKeyCredentialCreationOptions $optionsObj */
            $optionsObj = WebAuthn::fromJson($serialized, PublicKeyCredentialCreationOptions::class);

            if (! $credentialObj->response instanceof AuthenticatorAttestationResponse) {
                throw InvalidPasskeyException::make(__('تعذر تسجيل مفتاح المرور، الاستجابة غير مطابقة.'));
            }

            /** @var CredentialRecord $source */
            $source = WebAuthn::attestationValidator()->check(
                authenticatorAttestationResponse: $credentialObj->response,
                publicKeyCredentialCreationOptions: $optionsObj,
                host: Passkeys::relyingPartyId(),
            );

            $credentialId = Base64UrlSafe::encodeUnpadded($source->publicKeyCredentialId);

            if (AdminPasskey::where('credential_id', $credentialId)->exists()) {
                throw ValidationException::withMessages([
                    'credential' => [__('مفتاح المرور هذا مسجل بالفعل على حسابك أو جهاز آخر.')],
                ]);
            }

            return $admin->passkeys()->create([
                'name' => $name,
                'credential_id' => $credentialId,
                'credential' => json_decode(WebAuthn::toJson($source), true, flags: JSON_THROW_ON_ERROR),
                'last_used_at' => now(),
            ]);
        } catch (InvalidPasskeyException $e) {
            throw ValidationException::withMessages([
                'credential' => [$e->getMessage()],
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'credential' => [__('تعذر حفظ مفتاح المرور: :message', ['message' => $e->getMessage()])],
            ]);
        }
    }

    /**
     * List all passkeys registered to this admin.
     *
     * @return Collection<int, array{id: int, name: string, authenticator: string|null, created_at: string|null, last_used_at: string|null, created_at_human: string, last_used_at_human: string|null}>
     */
    public function listPasskeys(Admin $admin): Collection
    {
        return $admin->passkeys()
            ->latest()
            ->get()
            ->map(fn (AdminPasskey $passkey) => [
                'id' => $passkey->id,
                'name' => $passkey->name,
                'authenticator' => $passkey->authenticator,
                'created_at' => $passkey->created_at?->toISOString(),
                'last_used_at' => $passkey->last_used_at?->toISOString(),
                'created_at_human' => $passkey->created_at?->diffForHumans() ?? '',
                'last_used_at_human' => $passkey->last_used_at?->diffForHumans(),
            ]);
    }

    /**
     * Delete a passkey belonging to the admin.
     *
     * @throws ValidationException
     */
    public function deletePasskey(Admin $admin, int $passkeyId): void
    {
        $passkey = $admin->passkeys()->find($passkeyId);

        if (! $passkey) {
            throw ValidationException::withMessages([
                'passkey' => [__('مفتاح المرور غير موجود أو لا ينتمي إلى حسابك.')],
            ]);
        }

        $passkey->delete();
    }
}
