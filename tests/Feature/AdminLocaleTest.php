<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_switch_locale_to_en(): void
    {
        $role = Role::create([
            'slug' => 'super_admin',
            'name' => 'Super Admin',
            'permissions' => ['*'],
            'is_system' => true,
        ]);
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'locale' => 'ar',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('locale.switch', 'en'));

        $response->assertRedirect();
        $this->assertEquals('en', $admin->fresh()->locale);

        $dashboardResponse = $this->actingAs($admin->fresh(), 'admin')
            ->get(route('admin.dashboard'));
        $dashboardResponse->assertSee('dir="ltr"', false);
    }

    public function test_guest_switches_to_en_on_login_page_then_logs_in_stays_en(): void
    {
        $role = Role::create([
            'slug' => 'super_admin',
            'name' => 'Super Admin',
            'permissions' => ['*'],
            'is_system' => true,
        ]);
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'locale' => 'ar', // Default in database is 'ar'
        ]);

        // Step 1: User switches to English on login page
        $this->get(route('locale.switch', 'en'))
            ->assertRedirect();

        // Step 2: User logs in
        $response = $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $response->assertRedirect(route('admin.dashboard'));

        // Step 3: Following the redirect to dashboard
        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertSee('dir="ltr"', false);
    }

    public function test_logged_in_admin_switches_language_in_dashboard(): void
    {
        $role = Role::create([
            'slug' => 'super_admin',
            'name' => 'Super Admin',
            'permissions' => ['*'],
            'is_system' => true,
        ]);
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'locale' => 'ar',
        ]);

        // Login as admin
        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        // Now in dashboard, admin clicks English language switcher:
        $response = $this->get(route('locale.switch', 'en'));
        $response->assertRedirect();

        // Now admin views dashboard again
        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertSee('dir="ltr"', false);
    }

    public function test_admin_can_update_locale_from_profile_page(): void
    {
        $role = Role::create([
            'slug' => 'super_admin',
            'name' => 'Super Admin',
            'permissions' => ['*'],
            'is_system' => true,
        ]);
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'locale' => 'ar',
        ]);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => 'Test Admin',
                'email' => 'admin@example.com',
                'locale' => 'en',
            ])
            ->assertRedirect(route('admin.profile.edit'));

        $this->assertEquals('en', $admin->fresh()->locale);
        $this->assertEquals('en', session('locale'));

        $dashboardResponse = $this->actingAs($admin->fresh(), 'admin')
            ->get(route('admin.dashboard'));
        $dashboardResponse->assertSee('dir="ltr"', false);
    }

    public function test_admin_can_switch_from_en_back_to_ar(): void
    {
        $role = Role::create([
            'slug' => 'super_admin',
            'name' => 'Super Admin',
            'permissions' => ['*'],
            'is_system' => true,
        ]);
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'locale' => 'en',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('locale.switch', 'ar'))
            ->assertRedirect();

        $this->assertEquals('ar', $admin->fresh()->locale);
        $this->assertEquals('ar', session('locale'));

        $dashboardResponse = $this->actingAs($admin->fresh(), 'admin')
            ->get(route('admin.dashboard'));
        $dashboardResponse->assertSee('dir="rtl"', false);
    }
}
