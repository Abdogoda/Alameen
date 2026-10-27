<?php

declare(strict_types=1);

namespace App\Services\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class FirebaseTokenVerifier
{
    /**
     * Google public x509 certificates URL for Firebase Auth.
     */
    protected const CERTS_URL = 'https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com';

    /**
     * Cache key for Google public certificates.
     */
    protected const CACHE_KEY = 'firebase_google_public_certs';

    /**
     * Fake user data for testing.
     *
     * @var array<string, mixed>|null
     */
    protected static ?array $fakeData = null;

    /**
     * Fake exception for testing.
     */
    protected static ?Throwable $fakeException = null;

    /**
     * Whether fake mode is active.
     */
    protected static bool $isFaked = false;

    /**
     * Check if Firebase Auth is enabled (configured).
     */
    public function isEnabled(): bool
    {
        return ! empty(config('services.firebase.project_id'));
    }

    /**
     * Verify a Firebase ID token and return normalized claims.
     *
     * @return array{
     *     uid: string,
     *     email: ?string,
     *     name: ?string,
     *     avatar: ?string,
     *     email_verified: bool,
     *     phone_number: ?string,
     *     provider: ?string,
     *     google_id: ?string
     * }
     *
     * @throws InvalidArgumentException|RuntimeException|Throwable
     */
    public function verify(string $idToken): array
    {
        if (static::$isFaked) {
            if (static::$fakeException !== null) {
                throw static::$fakeException;
            }

            return array_merge([
                'uid' => 'fake-firebase-uid',
                'email' => 'user@example.com',
                'name' => 'Firebase User',
                'avatar' => null,
                'email_verified' => true,
                'phone_number' => null,
                'provider' => 'google.com',
                'google_id' => 'fake-google-id',
            ], static::$fakeData ?? []);
        }

        $projectId = config('services.firebase.project_id');

        if (empty($projectId)) {
            throw new InvalidArgumentException('Firebase project ID is not configured.');
        }

        $keys = $this->getPublicKeys();

        $decoded = JWT::decode($idToken, $keys);

        if (! isset($decoded->aud) || $decoded->aud !== $projectId) {
            throw new RuntimeException('Invalid token audience.');
        }

        $expectedIssuer = 'https://securetoken.google.com/'.$projectId;
        if (! isset($decoded->iss) || $decoded->iss !== $expectedIssuer) {
            throw new RuntimeException('Invalid token issuer.');
        }

        if (empty($decoded->sub) || ! is_string($decoded->sub)) {
            throw new RuntimeException('Invalid token subject.');
        }

        $now = time();
        if (isset($decoded->auth_time) && $decoded->auth_time > ($now + 300)) {
            throw new RuntimeException('Token auth_time is in the future.');
        }

        $email = isset($decoded->email) ? strtolower((string) $decoded->email) : null;
        $name = isset($decoded->name) ? (string) $decoded->name : null;
        $avatar = isset($decoded->picture) ? (string) $decoded->picture : null;
        $emailVerified = ! empty($decoded->email_verified);
        $phone = isset($decoded->phone_number) ? (string) $decoded->phone_number : null;

        $provider = $decoded->firebase->sign_in_provider ?? null;
        $googleId = null;

        if (isset($decoded->firebase->identities->{'google.com'}[0])) {
            $googleId = (string) $decoded->firebase->identities->{'google.com'}[0];
        } elseif ($provider === 'google.com') {
            $googleId = (string) $decoded->sub;
        }

        return [
            'uid' => (string) $decoded->sub,
            'email' => $email,
            'name' => $name,
            'avatar' => $avatar,
            'email_verified' => $emailVerified,
            'phone_number' => $phone,
            'provider' => $provider,
            'google_id' => $googleId,
        ];
    }

    /**
     * Retrieve Google public certificates for token verification.
     *
     * @return array<string, Key>
     */
    protected function getPublicKeys(): array
    {
        $certs = Cache::remember(self::CACHE_KEY, now()->addHours(6), function (): array {
            $response = Http::timeout(5)->get(self::CERTS_URL);

            if (! $response->successful()) {
                throw new RuntimeException('Failed to fetch Firebase public certificates.');
            }

            return $response->json();
        });

        if (empty($certs) || ! is_array($certs)) {
            throw new RuntimeException('Invalid public certificates returned for Firebase.');
        }

        $keys = [];
        foreach ($certs as $kid => $cert) {
            $keys[(string) $kid] = new Key($cert, 'RS256');
        }

        return $keys;
    }

    /**
     * Fake the verifier for tests.
     *
     * @param  array<string, mixed>|null  $userData
     */
    public static function fake(?array $userData = null): void
    {
        static::$isFaked = true;
        static::$fakeData = $userData;
        static::$fakeException = null;
    }

    /**
     * Fake an exception during token verification.
     */
    public static function fakeException(Throwable $exception): void
    {
        static::$isFaked = true;
        static::$fakeData = null;
        static::$fakeException = $exception;
    }

    /**
     * Restore original verifier behavior.
     */
    public static function restore(): void
    {
        static::$isFaked = false;
        static::$fakeData = null;
        static::$fakeException = null;
    }
}
