<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use App\Notifications\ResetPasswordOtpNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetOtpApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_user_can_request_forgot_password_otp_code(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'student@alameen.edu.sa',
        ]);

        $response = $this->postJson('/api/v1/auth/password/forgot', [
            'email' => 'student@alameen.edu.sa',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true);

        Notification::assertSentTo($user, ResetPasswordOtpNotification::class);
    }

    public function test_api_user_cannot_request_otp_for_nonexistent_email(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/v1/auth/password/forgot', [
            'email' => 'nonexistent@alameen.edu.sa',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        Notification::assertNothingSent();
    }

    public function test_api_user_can_verify_valid_otp_code(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'verify@alameen.edu.sa',
        ]);

        $this->postJson('/api/v1/auth/password/forgot', [
            'email' => 'verify@alameen.edu.sa',
        ]);

        $sentOtp = null;
        Notification::assertSentTo($user, ResetPasswordOtpNotification::class, function ($notification) use (&$sentOtp) {
            $sentOtp = $notification->otp;

            return true;
        });

        $this->assertNotNull($sentOtp);

        $response = $this->postJson('/api/v1/auth/password/verify-otp', [
            'email' => 'verify@alameen.edu.sa',
            'otp' => $sentOtp,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.verified', true);
    }

    public function test_api_user_cannot_verify_invalid_otp_code(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'invalid@alameen.edu.sa',
        ]);

        $this->postJson('/api/v1/auth/password/forgot', [
            'email' => 'invalid@alameen.edu.sa',
        ]);

        $response = $this->postJson('/api/v1/auth/password/verify-otp', [
            'email' => 'invalid@alameen.edu.sa',
            'otp' => '999999',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['otp']);
    }

    public function test_api_user_can_reset_password_with_valid_otp(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'reset@alameen.edu.sa',
            'password' => Hash::make('old-password-123'),
        ]);

        $this->postJson('/api/v1/auth/password/forgot', [
            'email' => 'reset@alameen.edu.sa',
        ]);

        $sentOtp = null;
        Notification::assertSentTo($user, ResetPasswordOtpNotification::class, function ($notification) use (&$sentOtp) {
            $sentOtp = $notification->otp;

            return true;
        });

        $this->assertNotNull($sentOtp);

        $response = $this->postJson('/api/v1/auth/password/reset', [
            'email' => 'reset@alameen.edu.sa',
            'otp' => $sentOtp,
            'password' => 'BrandNewPassword123!',
            'password_confirmation' => 'BrandNewPassword123!',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertTrue(Hash::check('BrandNewPassword123!', $user->fresh()->password));
    }
}
