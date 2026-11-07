<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogViewerAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_log_viewer(): void
    {
        $response = $this->get(route('log-viewer.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_regular_user_cannot_access_log_viewer(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('log-viewer.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_cannot_access_log_viewer_without_password_confirmation(): void
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin@alameen.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('log-viewer.index'));

        $response->assertRedirect(route('admin.log-viewer.confirm'));
    }

    public function test_admin_can_view_confirm_password_page(): void
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin-confirm@alameen.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.log-viewer.confirm'));

        $response->assertOk();
        $response->assertSee('تأكيد هوية المسؤول');
        $response->assertSee('admin-confirm@alameen.com');
        $response->assertSee(route('admin.log-viewer.confirm.store'));
    }

    public function test_admin_fails_confirmation_with_wrong_password(): void
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin-wrong@alameen.com',
            'password' => bcrypt('correctPassword123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.log-viewer.confirm.store'), [
            'password' => 'wrongPassword',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertNull(session('admin_log_viewer_confirmed_at'));
    }

    public function test_admin_succeeds_confirmation_with_correct_password(): void
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin-correct@alameen.com',
            'password' => bcrypt('correctPassword123'),
        ]);

        // Visit log-viewer first so intended URL is registered
        $this->actingAs($admin, 'admin')->get(route('log-viewer.index'));

        $response = $this->actingAs($admin, 'admin')->post(route('admin.log-viewer.confirm.store'), [
            'password' => 'correctPassword123',
        ]);

        $response->assertRedirect(route('log-viewer.index'));
        $this->assertNotNull(session('admin_log_viewer_confirmed_at'));
    }

    public function test_admin_with_confirmed_password_can_access_log_viewer(): void
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin-unlocked@alameen.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->withSession(['admin_log_viewer_confirmed_at' => time()])
            ->get(route('log-viewer.index'));

        $response->assertOk();
    }

    public function test_admin_with_expired_confirmation_is_redirected_to_confirm_again(): void
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin-expired@alameen.com',
            'password' => bcrypt('password123'),
        ]);

        // Confirmation timestamp older than 1800 seconds (e.g. 2000 seconds ago)
        $expiredTime = time() - 2000;

        $response = $this->actingAs($admin, 'admin')
            ->withSession(['admin_log_viewer_confirmed_at' => $expiredTime])
            ->get(route('log-viewer.index'));

        $response->assertRedirect(route('admin.log-viewer.confirm'));
    }

    public function test_unconfirmed_admin_gets_423_on_api_endpoints(): void
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin-api@alameen.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->getJson('/admin/log-viewer/api/files');

        $response->assertStatus(423);
        $response->assertJson([
            'locked' => true,
        ]);
    }

    public function test_confirmed_admin_can_access_api_endpoints(): void
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin-api-ok@alameen.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->withSession(['admin_log_viewer_confirmed_at' => time()])
            ->getJson('/admin/log-viewer/api/files', [
                'Referer' => 'http://localhost/admin/log-viewer',
            ]);

        $response->assertOk();
    }

    public function test_admin_can_lock_log_viewer_access(): void
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin-lock@alameen.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->withSession(['admin_log_viewer_confirmed_at' => time()])
            ->post(route('admin.log-viewer.lock'));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertNull(session('admin_log_viewer_confirmed_at'));
    }

    public function test_admin_dashboard_contains_log_viewer_link(): void
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin-dash@alameen.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee(route('log-viewer.index'));
        $response->assertSee('سجلات النظام');
    }

    public function test_log_viewer_shortcut_redirects_to_admin_log_viewer(): void
    {
        $response = $this->get('/log-viewer');

        $response->assertRedirect('/admin/log-viewer');
    }

    public function test_regular_user_dashboard_does_not_show_admin_log_banner(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('dashboard'));

        $response->assertOk();
        $response->assertDontSee('وضع المسؤول');
    }
}
