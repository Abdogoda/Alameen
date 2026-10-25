<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_login_button_is_rendered_on_login_screen(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee(route('auth.google.redirect'));
        $response->assertSee(__('المتابعة باستخدام Google'));
    }

    public function test_google_signup_button_is_rendered_on_register_screen(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee(route('auth.google.redirect'));
        $response->assertSee(__('التسجيل باستخدام Google'));
    }

    public function test_google_redirect_warns_if_credentials_not_set(): void
    {
        config(['services.google.client_id' => '']);
        config(['services.google.client_secret' => '']);

        $response = $this->get(route('auth.google.redirect'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');
    }

    public function test_existing_user_can_authenticate_via_google(): void
    {
        $user = User::factory()->create([
            'email' => 'student@example.com',
            'google_id' => null,
        ]);

        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn('google-unique-id-12345');
        $abstractUser->shouldReceive('getEmail')->andReturn('student@example.com');
        $abstractUser->shouldReceive('getName')->andReturn('Student Name');
        $abstractUser->shouldReceive('getNickname')->andReturn('Student');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/avatar.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        $user->refresh();
        $this->assertEquals('google-unique-id-12345', $user->google_id);
        $this->assertEquals('https://lh3.googleusercontent.com/avatar.jpg', $user->avatar);
    }

    public function test_new_user_can_register_and_authenticate_via_google(): void
    {
        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn('google-new-id-98765');
        $abstractUser->shouldReceive('getEmail')->andReturn('newstudent@example.com');
        $abstractUser->shouldReceive('getName')->andReturn('New Student');
        $abstractUser->shouldReceive('getNickname')->andReturn('newstudent');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/new.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'newstudent@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('google-new-id-98765', $user->google_id);
        $this->assertEquals('New Student', $user->name);
        $this->assertNotNull($user->email_verified_at);
    }
}
