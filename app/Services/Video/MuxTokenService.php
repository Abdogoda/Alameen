<?php

declare(strict_types=1);

namespace App\Services\Video;

use App\Services\Cms\SettingsService;
use Illuminate\Support\Facades\Log;

class MuxTokenService
{
    public function __construct(
        protected ?SettingsService $settingsService = null
    ) {
        $this->settingsService = $this->settingsService ?: app(SettingsService::class);
    }

    /**
     * Check if Mux signing keys are properly configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->getSigningKeyId()) && ! empty($this->getSigningPrivateKey());
    }

    /**
     * Retrieve Mux Signing Key ID from Settings (Dashboard) with fallback to config/env.
     */
    public function getSigningKeyId(): ?string
    {
        $id = $this->settingsService->get('mux_signing_key_id');

        return ! empty($id) ? trim($id) : config('video.drivers.mux.signing_key_id');
    }

    /**
     * Retrieve Mux Signing Private Key (PEM or base64) from Settings with fallback to config/env.
     */
    public function getSigningPrivateKey(): ?string
    {
        $key = $this->settingsService->get('mux_signing_key_private');

        return ! empty($key) ? trim($key) : config('video.drivers.mux.signing_key_private');
    }

    /**
     * Get default token lifetime in seconds (e.g. 3600 = 1 hour).
     */
    public function getTokenLifetime(): int
    {
        $val = $this->settingsService->get('mux_token_lifetime');
        if (! empty($val) && is_numeric($val) && (int) $val > 0) {
            return (int) $val;
        }

        return (int) config('video.drivers.mux.token_lifetime', 3600);
    }

    /**
     * Generate signed JWT playback token for video streaming (aud = 'v').
     */
    public function generatePlaybackToken(string $playbackId, ?int $lifetime = null): ?string
    {
        return $this->generateToken($playbackId, 'v', $lifetime);
    }

    /**
     * Generate signed JWT thumbnail token (aud = 't').
     */
    public function generateThumbnailToken(string $playbackId, ?int $lifetime = null): ?string
    {
        return $this->generateToken($playbackId, 't', $lifetime);
    }

    /**
     * Generate signed JWT storyboard token for player scrub previews (aud = 's').
     */
    public function generateStoryboardToken(string $playbackId, ?int $lifetime = null): ?string
    {
        return $this->generateToken($playbackId, 's', $lifetime);
    }

    /**
     * Generate a cryptographic RS256 JWT for Mux Signed URLs.
     *
     * @param  string  $playbackId  The Mux Playback ID (sub claim)
     * @param  string  $audience  'v' for video, 't' for thumbnail, 's' for storyboard
     * @param  int|null  $lifetime  Validity period in seconds
     * @param  array<string, mixed>  $customClaims  Additional claims if needed
     */
    public function generateToken(string $playbackId, string $audience = 'v', ?int $lifetime = null, array $customClaims = []): ?string
    {
        $keyId = $this->getSigningKeyId();
        $rawPrivateKey = $this->getSigningPrivateKey();

        if (empty($keyId) || empty($rawPrivateKey)) {
            Log::warning('MUX Token generation skipped: MUX Signing Key ID or Private Key is missing.');

            return null;
        }

        $privateKey = $this->normalizePrivateKey($rawPrivateKey);
        if (! $privateKey) {
            Log::error('MUX Token generation failed: Invalid RSA Private Key format.');

            return null;
        }

        $now = time();
        $lifetime = $lifetime ?: $this->getTokenLifetime();
        $exp = $now + $lifetime;

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
            'kid' => $keyId,
        ];

        $payload = array_merge([
            'sub' => $playbackId,
            'aud' => $audience,
            'exp' => $exp,
            'iat' => $now,
        ], $customClaims);

        return $this->signRs256($header, $payload, $privateKey);
    }

    /**
     * Sign Header & Payload using OpenSSL RS256 with zero external dependencies.
     *
     * @param  array<string, mixed>  $header
     * @param  array<string, mixed>  $payload
     */
    protected function signRs256(array $header, array $payload, \OpenSSLAsymmetricKey $privateKey): ?string
    {
        $encodedHeader = $this->base64UrlEncode((string) json_encode($header, JSON_UNESCAPED_SLASHES));
        $encodedPayload = $this->base64UrlEncode((string) json_encode($payload, JSON_UNESCAPED_SLASHES));
        $dataToSign = "{$encodedHeader}.{$encodedPayload}";

        $signature = '';
        $success = openssl_sign($dataToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        if (! $success) {
            Log::error('OpenSSL failed to sign MUX JWT: '.openssl_error_string());

            return null;
        }

        $encodedSignature = $this->base64UrlEncode($signature);

        return "{$dataToSign}.{$encodedSignature}";
    }

    /**
     * Normalize Mux RSA private key into an OpenSSL resource/key.
     * Handles:
     * 1. Standard PEM format (with BEGIN RSA PRIVATE KEY / BEGIN PRIVATE KEY)
     * 2. Base64-encoded PEM string
     * 3. Base64-encoded raw DER key
     */
    protected function normalizePrivateKey(string $rawKey): ?\OpenSSLAsymmetricKey
    {
        $trimmed = trim($rawKey);

        // Case A: Key is already PEM with headers
        if (str_contains($trimmed, '-----BEGIN')) {
            $key = openssl_pkey_get_private($trimmed);
            if ($key !== false) {
                return $key;
            }
        }

        // Case B: Base64-encoded string that decodes to PEM
        $decoded = base64_decode($trimmed, true);
        if ($decoded !== false) {
            if (str_contains($decoded, '-----BEGIN')) {
                $key = openssl_pkey_get_private($decoded);
                if ($key !== false) {
                    return $key;
                }
            }

            // Case C: Raw DER key wrapped into PEM
            $wrappedPkcs8 = "-----BEGIN PRIVATE KEY-----\n".chunk_split(base64_encode($decoded), 64, "\n")."-----END PRIVATE KEY-----\n";
            $key = openssl_pkey_get_private($wrappedPkcs8);
            if ($key !== false) {
                return $key;
            }

            $wrappedRsa = "-----BEGIN RSA PRIVATE KEY-----\n".chunk_split(base64_encode($decoded), 64, "\n")."-----END RSA PRIVATE KEY-----\n";
            $key = openssl_pkey_get_private($wrappedRsa);
            if ($key !== false) {
                return $key;
            }
        }

        // Case D: String is unadorned base64 text
        $wrapped = "-----BEGIN RSA PRIVATE KEY-----\n".chunk_split($trimmed, 64, "\n")."-----END RSA PRIVATE KEY-----\n";
        $key = openssl_pkey_get_private($wrapped);
        if ($key !== false) {
            return $key;
        }

        $wrapped8 = "-----BEGIN PRIVATE KEY-----\n".chunk_split($trimmed, 64, "\n")."-----END PRIVATE KEY-----\n";
        $key = openssl_pkey_get_private($wrapped8);
        if ($key !== false) {
            return $key;
        }

        return null;
    }

    /**
     * Base64URL-encode data according to RFC 7515.
     */
    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
