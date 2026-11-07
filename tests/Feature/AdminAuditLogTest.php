<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AdminActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminAuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $superAdmin;

    protected Role $superAdminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdminRole = Role::create([
            'slug' => 'super_admin',
            'name' => 'المالك / مدير النظام العام',
            'permissions' => ['*'],
            'is_system' => true,
        ]);

        $this->superAdmin = Admin::create([
            'name' => 'د. هنا المكاوي',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password123'),
            'role_id' => $this->superAdminRole->id,
            'permissions' => ['*'],
            'locale' => 'ar',
        ]);
    }

    public function test_admin_page_visit_is_automatically_logged(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.dashboard'));

        $response->assertOk();

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $this->superAdmin->id,
            'module' => 'dashboard',
            'action' => 'view',
            'method' => 'GET',
            'status' => 'success',
        ]);
    }

    public function test_mutating_admin_action_records_sanitized_payload(): void
    {
        $student = User::factory()->create([
            'name' => 'طالب تجريبي',
            'email' => 'student@example.com',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->put(route('admin.students.update', $student), [
                'name' => 'طالب معدل',
                'email' => 'student_updated@example.com',
                'password' => 'supersecretpass',
                'password_confirmation' => 'supersecretpass',
                'status' => 'active',
            ]);

        $response->assertRedirect();

        $log = AdminActivityLog::where('module', 'students')
            ->where('action', 'update')
            ->latest('id')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals($this->superAdmin->id, $log->admin_id);
        $this->assertEquals('PUT', $log->method);

        // Verify sensitive password was sanitized
        $this->assertNotNull($log->properties);
        $this->assertArrayHasKey('request_params', $log->properties);
        $this->assertEquals('••••••••', $log->properties['request_params']['password']);
        $this->assertEquals('••••••••', $log->properties['request_params']['password_confirmation']);
        $this->assertEquals('طالب معدل', $log->properties['request_params']['name']);
    }

    public function test_admin_successful_login_and_logout_events_are_recorded(): void
    {
        // 1. Login
        $response = $this->post(route('admin.login.store'), [
            'email' => 'superadmin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('admin_activity_logs', [
            'module' => 'auth',
            'action' => 'login',
            'admin_id' => $this->superAdmin->id,
            'status' => 'success',
        ]);

        // 2. Logout
        $logoutResponse = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.logout'));

        $logoutResponse->assertRedirect(route('admin.login'));

        $this->assertDatabaseHas('admin_activity_logs', [
            'module' => 'auth',
            'action' => 'logout',
            'admin_id' => $this->superAdmin->id,
            'status' => 'success',
        ]);
    }

    public function test_failed_login_attempt_is_recorded(): void
    {
        $this->post(route('admin.login.store'), [
            'email' => 'hacker@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertDatabaseHas('admin_activity_logs', [
            'module' => 'auth',
            'action' => 'failed_login',
            'status' => 'warning',
            'admin_email' => 'hacker@example.com',
        ]);
    }

    public function test_audit_logs_dashboard_loads_and_displays_kpi_stats(): void
    {
        // Generate sample log
        AdminActivityLog::create([
            'admin_id' => $this->superAdmin->id,
            'admin_name' => $this->superAdmin->name,
            'admin_email' => $this->superAdmin->email,
            'module' => 'courses',
            'action' => 'create',
            'description' => 'إنشاء دورة تدريبية تجريبية',
            'method' => 'POST',
            'url' => 'http://localhost/admin/courses',
            'status' => 'success',
        ]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.audit-logs.index'));

        $response->assertOk();
        $response->assertViewIs('admin.logs.index');
        $response->assertSee('إنشاء دورة تدريبية تجريبية');
        $response->assertSee($this->superAdmin->name);
    }

    public function test_filtering_audit_logs(): void
    {
        AdminActivityLog::create([
            'admin_id' => $this->superAdmin->id,
            'admin_name' => $this->superAdmin->name,
            'module' => 'courses',
            'action' => 'create',
            'description' => 'كورس نطق وتخاطب متقدم',
            'method' => 'POST',
            'url' => 'http://localhost/admin/courses',
            'status' => 'success',
        ]);

        AdminActivityLog::create([
            'admin_id' => $this->superAdmin->id,
            'admin_name' => $this->superAdmin->name,
            'module' => 'students',
            'action' => 'approve',
            'description' => 'تفعيل اشتراك الطالب',
            'method' => 'POST',
            'url' => 'http://localhost/admin/students/1/approve',
            'status' => 'success',
        ]);

        // Filter by module courses
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.audit-logs.index', ['module' => 'courses']));

        $response->assertOk();
        $response->assertSee('كورس نطق وتخاطب متقدم');
        $response->assertDontSee('تفعيل اشتراك الطالب');

        // Filter by keyword search
        $searchResponse = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.audit-logs.index', ['search' => 'تفعيل']));

        $searchResponse->assertOk();
        $searchResponse->assertSee('تفعيل اشتراك الطالب');
        $searchResponse->assertDontSee('كورس نطق وتخاطب متقدم');
    }

    public function test_exporting_audit_logs_as_csv(): void
    {
        AdminActivityLog::create([
            'admin_id' => $this->superAdmin->id,
            'admin_name' => $this->superAdmin->name,
            'module' => 'courses',
            'action' => 'create',
            'description' => 'سجل للتصدير',
            'method' => 'POST',
            'url' => 'http://localhost/admin/courses',
            'status' => 'success',
        ]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.audit-logs.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_json_details_inspection_endpoint(): void
    {
        $log = AdminActivityLog::create([
            'admin_id' => $this->superAdmin->id,
            'admin_name' => $this->superAdmin->name,
            'module' => 'roles',
            'action' => 'update',
            'description' => 'تعديل صلاحيات الدور',
            'method' => 'PUT',
            'url' => 'http://localhost/admin/roles/1',
            'status' => 'success',
            'properties' => [
                'request_params' => ['name' => 'مشرف مالي'],
            ],
        ]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->getJson(route('admin.audit-logs.show', $log));

        $response->assertOk();
        $response->assertJson([
            'id' => $log->id,
            'admin_name' => $this->superAdmin->name,
            'module' => 'roles',
            'description' => 'تعديل صلاحيات الدور',
            'properties' => [
                'request_params' => ['name' => 'مشرف مالي'],
            ],
        ]);
    }

    public function test_super_admin_can_prune_old_logs(): void
    {
        $oldLog = AdminActivityLog::create([
            'admin_id' => $this->superAdmin->id,
            'admin_name' => $this->superAdmin->name,
            'module' => 'courses',
            'action' => 'view',
            'description' => 'سجل قديم',
            'method' => 'GET',
            'url' => 'http://localhost/admin/courses',
            'status' => 'success',
        ]);
        DB::table('admin_activity_logs')
            ->where('id', $oldLog->id)
            ->update(['created_at' => Carbon::now()->subDays(45)]);

        $recentLog = AdminActivityLog::create([
            'admin_id' => $this->superAdmin->id,
            'admin_name' => $this->superAdmin->name,
            'module' => 'courses',
            'action' => 'view',
            'description' => 'سجل حديث',
            'method' => 'GET',
            'url' => 'http://localhost/admin/courses',
            'status' => 'success',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.audit-logs.clear'), [
                'retention_days' => 30,
            ]);

        $response->assertRedirect(route('admin.audit-logs.index'));

        $this->assertDatabaseMissing('admin_activity_logs', ['id' => $oldLog->id]);
        $this->assertDatabaseHas('admin_activity_logs', ['id' => $recentLog->id]);
    }

    public function test_unauthorized_admin_cannot_access_audit_logs(): void
    {
        $limitedRole = Role::create([
            'slug' => 'support_only',
            'name' => 'دعم فني فقط',
            'permissions' => ['support.view', 'support.manage'],
            'is_system' => false,
        ]);

        $limitedAdmin = Admin::create([
            'name' => 'مشرف دعم',
            'email' => 'support_staff@example.com',
            'password' => bcrypt('password123'),
            'role_id' => $limitedRole->id,
            'locale' => 'ar',
        ]);

        $response = $this->actingAs($limitedAdmin, 'admin')
            ->get(route('admin.audit-logs.index'));

        $response->assertForbidden();
    }
}
