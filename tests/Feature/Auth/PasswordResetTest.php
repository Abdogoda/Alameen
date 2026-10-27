<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\ResetPasswordOtpNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_request_screen_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
    }

    public function test_reset_password_otp_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post(route('password.email'), ['email' => $user->email]);

        $response->assertRedirect(route('password.reset', ['email' => $user->email]));
        $response->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPasswordOtpNotification::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $response = $this->get(route('password.reset'));

        $response->assertOk();
        $response->assertSee(__('رمز التحقق (OTP)'));
    }

    public function test_password_can_be_reset_with_valid_otp(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'password' => Hash::make('old-password-123'),
        ]);

        $this->post(route('password.email'), ['email' => $user->email]);

        $sentOtp = null;
        Notification::assertSentTo($user, ResetPasswordOtpNotification::class, function ($notification) use (&$sentOtp) {
            $sentOtp = $notification->otp;

            return true;
        });

        $this->assertNotNull($sentOtp);

        $response = $this->post(route('password.update'), [
            'email' => $user->email,
            'otp' => $sentOtp,
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('NewSecurePassword123!', $user->fresh()->password));
    }

    public function test_password_cannot_be_reset_with_invalid_otp(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'password' => Hash::make('old-password-123'),
        ]);

        $this->post(route('password.email'), ['email' => $user->email]);

        $response = $this->post(route('password.update'), [
            'email' => $user->email,
            'otp' => '000000',
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertTrue(Hash::check('old-password-123', $user->fresh()->password));
    }

    public function test_otp_request_is_throttled_when_requested_too_quickly(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        // First request succeeds
        $this->post(route('password.email'), ['email' => $user->email]);

        // Second request immediately after triggers throttling
        $response = $this->post(route('password.email'), ['email' => $user->email]);

        $response->assertSessionHasErrors('email');
    }
}
