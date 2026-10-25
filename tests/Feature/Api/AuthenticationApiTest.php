<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use App\Services\Auth\TwoFactorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class AuthenticationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_via_api(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'device_name' => 'Test Device',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                    'token_type',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_user_can_login_via_api_without_2fa(): void
    {
        $user = User::factory()->create([
            'email' => 'alice@example.com',
            'password' => Hash::make('secret-password'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'alice@example.com',
            'password' => 'secret-password',
            'device_name' => 'Mobile App',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'success')
            ->assertJsonStructure([
                'success',
                'data' => [
                    'status',
                    'user' => ['id', 'email'],
                    'token',
                    'token_type',
                ],
            ]);
    }

    public function test_login_with_invalid_credentials_fails(): void
    {
        User::factory()->create([
            'email' => 'alice@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'alice@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_with_2fa_enabled_returns_challenge_token(): void
    {
        $user = User::factory()->create([
            'email' => 'bob@example.com',
            'password' => Hash::make('password123'),
            'two_factor_secret' => encrypt('secret123'),
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => encrypt(json_encode(['code-1', 'code-2'])),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'bob@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'requires_two_factor')
            ->assertJsonStructure([
                'success',
                'data' => [
                    'status',
                    'two_factor_challenge_token',
                ],
            ]);
    }

    public function test_can_complete_2fa_challenge_with_recovery_code(): void
    {
        $twoFactorService = app(TwoFactorService::class);

        $user = User::factory()->create([
            'email' => 'carol@example.com',
            'password' => Hash::make('password123'),
            'two_factor_secret' => encrypt('test-secret'),
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-123', 'recovery-code-456'])),
        ]);

        $challengeToken = $twoFactorService->createTwoFactorChallenge($user);

        $response = $this->postJson('/api/v1/auth/2fa/challenge', [
            'challenge_token' => $challengeToken,
            'recovery_code' => 'recovery-code-123',
            'device_name' => 'API Client',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'token',
                    'token_type',
                ],
            ]);
    }

    public function test_authenticated_user_can_enable_confirm_and_disable_2fa_via_api(): void
    {
        $user = User::factory()->create();

        // 1. Enable 2FA
        $enableResponse = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/auth/2fa/enable');

        $enableResponse->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'secret',
                    'qr_code_svg',
                    'qr_code_url',
                    'manual_setup_key',
                ],
            ]);

        $user->refresh();
        $this->assertNotNull($user->two_factor_secret);

        // 2. Confirm 2FA with valid TOTP code
        $provider = app(TwoFactorAuthenticationProvider::class);
        $secret = decrypt($user->two_factor_secret);
        $validOtp = (string) $provider->verify($secret, '000000') ? '000000' : null;

        // Mock confirmation by creating a valid code through engine
        $engine = new Google2FA;
        $otp = $engine->getCurrentOtp($secret);

        $confirmResponse = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/auth/2fa/confirm', [
                'code' => $otp,
            ]);

        $confirmResponse->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'recovery_codes',
                ],
            ]);

        $user->refresh();
        $this->assertTrue($user->hasEnabledTwoFactorAuthentication());

        // 3. Retrieve recovery codes
        $recResponse = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/auth/2fa/recovery-codes');

        $recResponse->assertOk()
            ->assertJsonPath('success', true);

        // 4. Regenerate recovery codes
        $regenResponse = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/auth/2fa/recovery-codes');

        $regenResponse->assertOk()
            ->assertJsonPath('success', true);

        // 5. Disable 2FA
        $disableResponse = $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/v1/auth/2fa/disable');

        $disableResponse->assertOk()
            ->assertJsonPath('success', true);

        $user->refresh();
        $this->assertFalse($user->hasEnabledTwoFactorAuthentication());
    }

    public function test_user_can_view_me_security_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/auth/me');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email', 'email_verified'],
                    'security' => [
                        'two_factor_enabled',
                        'passkeys_count',
                        'api_tokens_count',
                        'has_password',
                    ],
                ],
            ]);
    }

    public function test_user_can_update_password_via_api(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password-123'),
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/auth/password/update', [
                'current_password' => 'old-password-123',
                'password' => 'brand-new-password-123',
                'password_confirmation' => 'brand-new-password-123',
            ]);

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertTrue(Hash::check('brand-new-password-123', $user->refresh()->password));
    }

    public function test_api_token_crud_management(): void
    {
        $user = User::factory()->create();

        // Create token
        $createResponse = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/auth/tokens', [
                'name' => 'Mobile App Token',
                'abilities' => ['read', 'write'],
            ]);

        $createResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'token',
                    'token_type',
                ],
            ]);

        $tokenId = $createResponse->json('data.id');

        // List tokens
        $listResponse = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/auth/tokens');

        $listResponse->assertOk()
            ->assertJsonPath('success', true);

        // Delete token
        $deleteResponse = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/auth/tokens/{$tokenId}");

        $deleteResponse->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_passkeys_login_and_registration_options_endpoints(): void
    {
        $user = User::factory()->create();

        // Public login options
        $loginOptResponse = $this->getJson('/api/v1/auth/passkeys/login/options');
        $loginOptResponse->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'challenge_id',
                    'options' => ['challenge'],
                ],
            ]);

        // Authenticated registration options
        $regOptResponse = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/auth/passkeys/register/options');

        $regOptResponse->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'challenge_id',
                    'options' => ['challenge'],
                ],
            ]);
    }

    public function test_user_can_logout_and_revoke_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/logout');

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertCount(0, $user->fresh()->tokens);
    }
}
