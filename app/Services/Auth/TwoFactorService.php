<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Events\TwoFactorAuthenticationFailed;
use Laravel\Fortify\Events\ValidTwoFactorAuthenticationCodeProvided;
use Laravel\Fortify\Fortify;

class TwoFactorService
{
    public function __construct(
        protected EnableTwoFactorAuthentication $enableAction,
        protected ConfirmTwoFactorAuthentication $confirmAction,
        protected DisableTwoFactorAuthentication $disableAction,
        protected GenerateNewRecoveryCodes $generateRecoveryCodesAction,
        protected TwoFactorAuthenticationProvider $provider
    ) {}

    /**
     * Enable two factor authentication for the user and return setup data (QR code, manual key, URL).
     *
     * @return array{secret: string, qr_code_svg: string, qr_code_url: string, manual_setup_key: string}
     */
    public function enable(User $user, bool $force = false): array
    {
        ($this->enableAction)($user, $force);

        $secret = decrypt($user->two_factor_secret);

        return [
            'secret' => $secret,
            'qr_code_svg' => $user->twoFactorQrCodeSvg(),
            'qr_code_url' => $user->twoFactorQrCodeUrl(),
            'manual_setup_key' => $secret,
        ];
    }

    /**
     * Confirm two-factor authentication with the 6-digit TOTP code.
     *
     * @return array{recovery_codes: list<string>}
     *
     * @throws ValidationException
     */
    public function confirm(User $user, string $code): array
    {
        ($this->confirmAction)($user, $code);

        return [
            'recovery_codes' => $this->getRecoveryCodes($user),
        ];
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disable(User $user): void
    {
        ($this->disableAction)($user);
    }

    /**
     * Get the user's recovery codes.
     *
     * @return list<string>
     */
    public function getRecoveryCodes(User $user): array
    {
        if (! $user->two_factor_recovery_codes) {
            return [];
        }

        try {
            return json_decode(Fortify::currentEncrypter()->decrypt($user->two_factor_recovery_codes), true) ?: [];
        } catch (Exception) {
            return [];
        }
    }

    /**
     * Regenerate new recovery codes for the user.
     *
     * @return list<string>
     */
    public function regenerateRecoveryCodes(User $user): array
    {
        ($this->generateRecoveryCodesAction)($user);

        return $this->getRecoveryCodes($user);
    }

    /**
     * Verify a 6-digit TOTP code against the user's secret.
     */
    public function verifyCode(User $user, string $code): bool
    {
        if (empty($user->two_factor_secret) || empty($code)) {
            return false;
        }

        try {
            $secret = Fortify::currentEncrypter()->decrypt($user->two_factor_secret);

            return (bool) $this->provider->verify($secret, $code);
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Verify a recovery code and consume/replace it if valid.
     */
    public function verifyRecoveryCode(User $user, string $recoveryCode): bool
    {
        if (empty($recoveryCode) || ! $user->hasEnabledTwoFactorAuthentication()) {
            return false;
        }

        $codes = $this->getRecoveryCodes($user);

        $matchedCode = collect($codes)->first(fn ($stored) => hash_equals((string) $stored, $recoveryCode));

        if ($matchedCode) {
            $user->replaceRecoveryCode($matchedCode);

            return true;
        }

        return false;
    }

    /**
     * Create a short-lived challenge token for 2FA login (used by API).
     */
    public function createTwoFactorChallenge(User $user, int $ttlSeconds = 300): string
    {
        $challengeToken = Str::random(64);

        Cache::put("2fa_challenge:{$challengeToken}", $user->id, now()->addSeconds($ttlSeconds));

        return $challengeToken;
    }

    /**
     * Verify a 2FA challenge via token and either TOTP code or recovery code.
     * Consumes the challenge upon success.
     *
     * @throws ValidationException
     */
    public function verifyTwoFactorChallenge(
        string $challengeToken,
        ?string $code = null,
        ?string $recoveryCode = null
    ): User {
        $userId = Cache::get("2fa_challenge:{$challengeToken}");

        if (! $userId) {
            throw ValidationException::withMessages([
                'challenge_token' => [__('The two-factor authentication challenge has expired or is invalid.')],
            ]);
        }

        /** @var User|null $user */
        $user = User::where('id', $userId)->first();

        if (! $user) {
            Cache::forget("2fa_challenge:{$challengeToken}");
            throw ValidationException::withMessages([
                'challenge_token' => [__('User not found.')],
            ]);
        }

        $isValid = false;

        if (! empty($code)) {
            $isValid = $this->verifyCode($user, $code);
        } elseif (! empty($recoveryCode)) {
            $isValid = $this->verifyRecoveryCode($user, $recoveryCode);
        }

        if (! $isValid) {
            event(new TwoFactorAuthenticationFailed($user));

            throw ValidationException::withMessages([
                'code' => [__('The provided two-factor authentication code or recovery code was invalid.')],
            ]);
        }

        event(new ValidTwoFactorAuthenticationCodeProvided($user));

        Cache::forget("2fa_challenge:{$challengeToken}");

        return $user;
    }
}
