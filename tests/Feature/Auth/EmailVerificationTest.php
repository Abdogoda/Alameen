<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\EmailVerificationOtpNotification;
use App\Services\Auth\EmailVerificationOtpService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertOk();
        $response->assertSee(__('رمز التحقق (OTP)'));
    }

    public function test_unverified_users_are_redirected_to_the_email_verification_prompt(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('appearance.edit'));

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_email_verification_otp_can_be_resent(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'verification-otp-sent');

        Notification::assertSentTo($user, EmailVerificationOtpNotification::class);
    }

    public function test_email_can_be_verified_with_valid_otp(): void
    {
        Notification::fake();
        Event::fake();

        $user = User::factory()->unverified()->create();

        $otpService = app(EmailVerificationOtpService::class);
        $otp = $otpService->sendOtp($user);

        $response = $this->actingAs($user)->post(route('verification.verify-otp'), [
            'otp' => $otp,
        ]);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
    }

    public function test_email_cannot_be_verified_with_invalid_otp(): void
    {
        Notification::fake();
        Event::fake();

        $user = User::factory()->unverified()->create();

        $otpService = app(EmailVerificationOtpService::class);
        $otpService->sendOtp($user);

        $response = $this->actingAs($user)->post(route('verification.verify-otp'), [
            'otp' => '999999',
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        Event::assertNotDispatched(Verified::class);
    }

    public function test_email_cannot_be_verified_with_expired_otp(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $otpService = app(EmailVerificationOtpService::class);
        $otp = $otpService->sendOtp($user);

        // Simulate OTP expiration (older than 15 minutes)
        DB::table('email_verification_tokens')
            ->where('email', strtolower($user->email))
            ->update(['created_at' => Carbon::now()->subMinutes(20)]);

        $response = $this->actingAs($user)->post(route('verification.verify-otp'), [
            'otp' => $otp,
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_already_verified_user_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
    }

    public function test_legacy_verification_link_gracefully_redirects_to_otp_prompt(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.verify', [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]));

        $response->assertRedirect(route('verification.notice'));
    }
}
