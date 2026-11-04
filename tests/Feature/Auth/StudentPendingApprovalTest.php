<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPendingApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_newly_registered_user_gets_pending_status_and_is_not_logged_in(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Pending Student',
            'email' => 'pending@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();

        $user = User::where('email', 'pending@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isPending());
        $this->assertFalse($user->isActive());
    }

    public function test_pending_user_cannot_login_via_login_form(): void
    {
        $user = User::factory()->pending()->create([
            'email' => 'pending-login@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'pending-login@example.com',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_pending_user_cannot_access_any_authenticated_student_page(): void
    {
        $user = User::factory()->pending()->create();

        // Even if session somehow exists, middleware must boot them out immediately
        $response = $this->actingAs($user, 'web')->get(route('dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest('web');
    }

    public function test_admin_dashboard_shows_pending_students_count(): void
    {
        $admin = Admin::factory()->create();
        User::factory()->count(3)->pending()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('بانتظار الموافقة والاعتماد');
    }

    public function test_admin_can_approve_pending_student(): void
    {
        $admin = Admin::factory()->create();
        $student = User::factory()->pending()->create();

        $this->assertTrue($student->isPending());

        $response = $this->actingAs($admin, 'admin')->post(route('admin.students.approve', $student));

        $response->assertRedirect();
        $student->refresh();

        $this->assertTrue($student->isActive());
        $this->assertFalse($student->isPending());

        // Approved student can now login
        $loginResponse = $this->post(route('login.store'), [
            'email' => $student->email,
            'password' => 'password',
        ]);

        $loginResponse->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($student, 'web');
    }
}
