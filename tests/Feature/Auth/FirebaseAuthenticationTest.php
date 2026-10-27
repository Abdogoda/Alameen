<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use App\Services\Auth\FirebaseTokenVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FirebaseAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.firebase.project_id' => 'alameen-test-firebase',
            'services.firebase.api_key' => 'fake-api-key',
        ]);
    }

    protected function tearDown(): void
    {
        FirebaseTokenVerifier::restore();
        parent::tearDown();
    }

    public function test_admin_login_screen_renders_firebase_google_button(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertSee('data-firebase-auth="admin"', false);
        $response->assertSee(__('تسجيل الدخول عبر Google (Firebase)'));
    }

    public function test_existing_admin_can_authenticate_via_firebase_google(): void
    {
        $role = Role::create([
            'name' => 'مشرف رئيسي',
            'slug' => 'super_admin',
            'permissions' => ['*'],
            'is_system' => true,
        ]);

        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@alameen.edu.sa',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
            'locale' => 'ar',
        ]);

        FirebaseTokenVerifier::fake([
            'uid' => 'firebase-admin-uid-123',
            'email' => 'admin@alameen.edu.sa',
            'name' => 'Admin User',
            'google_id' => 'google-admin-123',
            'avatar' => 'https://lh3.googleusercontent.com/admin.jpg',
            'email_verified' => true,
        ]);

        $response = $this->postJson(route('admin.auth.google.firebase'), [
            'id_token' => 'valid-mocked-firebase-token',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => route('admin.dashboard'),
            ]);

        $this->assertAuthenticatedAs($admin, 'admin');

        $admin->refresh();
        $this->assertEquals('firebase-admin-uid-123', $admin->firebase_uid);
        $this->assertEquals('google-admin-123', $admin->google_id);
        $this->assertEquals('https://lh3.googleusercontent.com/admin.jpg', $admin->avatar);
    }

    public function test_unregistered_admin_cannot_login_via_firebase_google(): void
    {
        FirebaseTokenVerifier::fake([
            'uid' => 'unauthorized-uid',
            'email' => 'intruder@example.com',
            'name' => 'Intruder',
            'email_verified' => true,
        ]);

        $response = $this->postJson(route('admin.auth.google.firebase'), [
            'id_token' => 'valid-token-unauthorized-email',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertGuest('admin');
    }

    public function test_existing_student_user_can_authenticate_via_firebase_google(): void
    {
        $user = User::factory()->create([
            'email' => 'student@alameen.edu.sa',
            'firebase_uid' => null,
            'status' => 'active',
        ]);

        FirebaseTokenVerifier::fake([
            'uid' => 'firebase-user-uid-456',
            'email' => 'student@alameen.edu.sa',
            'name' => 'Ali Student',
            'google_id' => 'google-user-456',
            'avatar' => 'https://lh3.googleusercontent.com/student.jpg',
            'email_verified' => true,
        ]);

        $response = $this->postJson(route('auth.google.firebase'), [
            'id_token' => 'valid-mocked-firebase-token',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => route('dashboard'),
            ]);

        $this->assertAuthenticatedAs($user, 'web');

        $user->refresh();
        $this->assertEquals('firebase-user-uid-456', $user->firebase_uid);
        $this->assertEquals('google-user-456', $user->google_id);
    }

    public function test_new_student_user_registers_automatically_via_firebase_google(): void
    {
        FirebaseTokenVerifier::fake([
            'uid' => 'firebase-new-student-789',
            'email' => 'newstudent@alameen.edu.sa',
            'name' => 'Fatima Student',
            'google_id' => 'google-user-789',
            'avatar' => 'https://lh3.googleusercontent.com/fatima.jpg',
            'email_verified' => true,
        ]);

        $response = $this->postJson(route('auth.google.firebase'), [
            'id_token' => 'valid-new-user-token',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => route('dashboard'),
            ]);

        $this->assertAuthenticated('web');

        $user = User::where('email', 'newstudent@alameen.edu.sa')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Fatima Student', $user->name);
        $this->assertEquals('firebase-new-student-789', $user->firebase_uid);
        $this->assertEquals('active', $user->status);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_suspended_student_cannot_authenticate_via_firebase(): void
    {
        User::factory()->create([
            'email' => 'suspended@alameen.edu.sa',
            'status' => 'suspended',
        ]);

        FirebaseTokenVerifier::fake([
            'uid' => 'firebase-suspended-uid',
            'email' => 'suspended@alameen.edu.sa',
            'email_verified' => true,
        ]);

        $response = $this->postJson(route('auth.google.firebase'), [
            'id_token' => 'valid-token-suspended-user',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertGuest('web');
    }

    public function test_api_v1_auth_firebase_authenticates_user(): void
    {
        FirebaseTokenVerifier::fake([
            'uid' => 'firebase-api-uid',
            'email' => 'apiuser@alameen.edu.sa',
            'name' => 'API Student',
            'email_verified' => true,
        ]);

        $response = $this->postJson(route('api.v1.auth.firebase'), [
            'id_token' => 'valid-token',
            'guard' => 'web',
            'device_name' => 'Mobile App',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'token',
                'token_type',
                'user' => ['id', 'name', 'email'],
            ]);
    }

    public function test_api_v1_auth_firebase_authenticates_admin(): void
    {
        $role = Role::create([
            'name' => 'مشرف',
            'slug' => 'supervisor',
            'permissions' => ['courses.*'],
            'is_system' => false,
        ]);

        Admin::create([
            'name' => 'Admin API',
            'email' => 'adminapi@alameen.edu.sa',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
            'locale' => 'ar',
        ]);

        FirebaseTokenVerifier::fake([
            'uid' => 'firebase-admin-api-uid',
            'email' => 'adminapi@alameen.edu.sa',
            'name' => 'Admin API',
            'email_verified' => true,
        ]);

        $response = $this->postJson(route('api.v1.auth.firebase'), [
            'id_token' => 'valid-token',
            'guard' => 'admin',
            'device_name' => 'Admin Tablet',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'token',
                'token_type',
                'admin' => ['id', 'name', 'email', 'role'],
            ]);
    }
}
