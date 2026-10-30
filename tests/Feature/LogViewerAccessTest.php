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

    public function test_admin_can_access_log_viewer(): void
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin@alameen.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('log-viewer.index'));

        $response->assertOk();
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
