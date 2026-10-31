<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileAndRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_admin_login_page_renders_passkey_login_component(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertSee('تسجيل الدخول عبر مفتاح المرور (Passkey)');
        $response->assertSee(route('admin.passkey.login-options'));
        $response->assertSee(route('admin.passkey.login'));
    }

    public function test_guest_is_redirected_away_from_admin_management_and_profile(): void
    {
        $this->get(route('admin.profile.edit'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.admins.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.roles.index'))->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_view_profile_page(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.profile.edit'));

        $response->assertStatus(200);
        $response->assertSee($admin->name);
        $response->assertSee($admin->email);
        $response->assertSee('مفاتيح المرور (Passkeys)');
        $response->assertSee('الصلاحيات والأدوار');
    }

    public function test_admin_can_update_profile_information(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.profile.update'), [
            'name' => 'Abdo Updated Name',
            'email' => 'updated.email@alameen.edu.sa',
            'locale' => 'ar',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertSame('Abdo Updated Name', $admin->name);
        $this->assertSame('updated.email@alameen.edu.sa', $admin->email);
    }

    public function test_admin_can_update_password(): void
    {
        $admin = Admin::first();
        $admin->update(['password' => Hash::make('CurrentSecret123!')]);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.profile.password'), [
            'current_password' => 'CurrentSecret123!',
            'password' => 'NewSecretPassword456!',
            'password_confirmation' => 'NewSecretPassword456!',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertTrue(Hash::check('NewSecretPassword456!', $admin->password));
    }

    public function test_admin_passkey_login_options_endpoint_returns_json(): void
    {
        $response = $this->getJson(route('admin.passkey.login-options'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'challenge_id',
            'options' => ['challenge'],
        ]);
    }

    public function test_admin_can_request_passkey_registration_options(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->getJson(route('admin.passkey.registration-options'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'challenge_id',
            'options' => ['challenge', 'rp', 'user'],
        ]);
    }

    public function test_admin_can_view_admins_list_and_create_new_admin(): void
    {
        $superAdmin = Admin::first();
        $role = Role::where('slug', 'instructor')->first();

        // 1. View admins list
        $response = $this->actingAs($superAdmin, 'admin')->get(route('admin.admins.index'));
        $response->assertStatus(200);
        $response->assertSee('إدارة المشرفين وفريق العمل');

        // 2. View create form
        $createResponse = $this->actingAs($superAdmin, 'admin')->get(route('admin.admins.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('إضافة مشرف جديد');

        // 3. Store new admin
        $storeResponse = $this->actingAs($superAdmin, 'admin')->post(route('admin.admins.store'), [
            'name' => 'مشرف تجريبي جديد',
            'email' => 'new.supervisor@alameen.edu.sa',
            'password' => 'SecurePass1234!',
            'role_id' => $role->id,
            'permissions' => ['courses.view', 'tasks.view'],
        ]);

        $storeResponse->assertRedirect(route('admin.admins.index'));
        $storeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('admins', [
            'email' => 'new.supervisor@alameen.edu.sa',
            'role_id' => $role->id,
        ]);
    }

    public function test_admin_can_view_roles_and_create_custom_role(): void
    {
        $superAdmin = Admin::first();

        // 1. View roles index
        $indexResponse = $this->actingAs($superAdmin, 'admin')->get(route('admin.roles.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('إدارة الأدوار والصلاحيات');

        // 2. Create new dynamic role
        $storeResponse = $this->actingAs($superAdmin, 'admin')->post(route('admin.roles.store'), [
            'name' => 'مراجع محتوى سريري',
            'name_ar' => 'مراجع محتوى سريري',
            'name_en' => 'Clinical Content Reviewer',
            'slug' => 'clinical_reviewer',
            'description' => 'مراجعة المواد والدروس المصورة قبل النشر',
            'permissions' => ['courses.view', 'courses.manage'],
        ]);

        $storeResponse->assertRedirect(route('admin.roles.index'));
        $storeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('roles', [
            'slug' => 'clinical_reviewer',
            'is_system' => false,
        ]);
    }

    public function test_system_roles_and_admins_are_protected_from_deletion(): void
    {
        $superAdmin = Admin::first();
        $systemRole = Role::where('slug', 'super_admin')->first();

        // System role cannot be deleted
        $roleDeleteResponse = $this->actingAs($superAdmin, 'admin')->delete(route('admin.roles.destroy', $systemRole));
        $roleDeleteResponse->assertSessionHasErrors('role');
        $this->assertDatabaseHas('roles', ['id' => $systemRole->id]);

        // Self-deletion is prevented
        $adminDeleteResponse = $this->actingAs($superAdmin, 'admin')->delete(route('admin.admins.destroy', $superAdmin));
        $adminDeleteResponse->assertSessionHasErrors('admin');
        $this->assertDatabaseHas('admins', ['id' => $superAdmin->id]);
    }
}
