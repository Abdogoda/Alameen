<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleAndPermissionEnforcementTest extends TestCase
{
    use RefreshDatabase;

    private function createRole(string $name, string $slug, array $permissions): Role
    {
        return Role::create([
            'name' => $name,
            'slug' => $slug,
            'description' => "Role for {$name}",
            'permissions' => $permissions,
        ]);
    }

    private function createAdminWithRole(Role $role, string $email): Admin
    {
        return Admin::create([
            'name' => "Admin {$role->name}",
            'email' => $email,
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
            'permissions' => [],
        ]);
    }

    public function test_super_admin_has_full_access_to_all_sections(): void
    {
        $superAdminRole = $this->createRole('المدير العام', 'super_admin', ['*']);
        $superAdmin = $this->createAdminWithRole($superAdminRole, 'super@alameen.com');

        $routesToTest = [
            route('admin.dashboard'),
            route('admin.courses.index'),
            route('admin.receipts.index'),
            route('admin.tasks.index'),
            route('admin.students.index'),
            route('admin.admins.index'),
            route('admin.roles.index'),
            route('admin.settings.index'),
            route('admin.support.index'),
            route('admin.notifications.index'),
            route('log-viewer.index'),
        ];

        foreach ($routesToTest as $routeUrl) {
            $response = $this->actingAs($superAdmin, 'admin')
                ->withSession(['admin_log_viewer_confirmed_at' => time()])
                ->get($routeUrl);
            $response->assertOk();
        }

        // Verify that sidebar contains navigation links for super admin
        $dashResponse = $this->actingAs($superAdmin, 'admin')->get(route('admin.dashboard'));
        $dashResponse->assertSee(route('admin.courses.index'));
        $dashResponse->assertSee(route('admin.receipts.index'));
        $dashResponse->assertSee(route('admin.tasks.index'));
        $dashResponse->assertSee(route('admin.students.index'));
        $dashResponse->assertSee(route('admin.admins.index'));
        $dashResponse->assertSee(route('admin.settings.index'));
    }

    public function test_finance_admin_can_only_access_receipts_and_is_forbidden_elsewhere(): void
    {
        $financeRole = $this->createRole('المشرف المالي', 'finance_officer', [
            'receipts.view',
            'receipts.verify',
            'receipts.approve',
            'receipts.reject',
            'reports.view',
        ]);
        $financeAdmin = $this->createAdminWithRole($financeRole, 'finance@alameen.com');

        // Can access receipts
        $response = $this->actingAs($financeAdmin, 'admin')->get(route('admin.receipts.index'));
        $response->assertOk();

        // Must be forbidden (403) on unauthorized modules
        $forbiddenRoutes = [
            route('admin.courses.index'),
            route('admin.tasks.index'),
            route('admin.students.index'),
            route('admin.admins.index'),
            route('admin.roles.index'),
            route('admin.settings.index'),
            route('admin.support.index'),
            route('admin.notifications.index'),
            route('log-viewer.index'),
        ];

        foreach ($forbiddenRoutes as $routeUrl) {
            $this->actingAs($financeAdmin, 'admin')
                ->get($routeUrl)
                ->assertForbidden();
        }

        $this->actingAs($financeAdmin, 'admin')
            ->post(route('admin.courses.store'), ['title_ar' => 'Test', 'price' => 100, 'access_duration_days' => 30])
            ->assertForbidden();

        // Finance admin's dashboard must NOT show links to forbidden areas
        $dashResponse = $this->actingAs($financeAdmin, 'admin')->get(route('admin.dashboard'));
        $dashResponse->assertOk();
        $dashResponse->assertSee(route('admin.receipts.index'));
        $dashResponse->assertDontSee(route('admin.courses.index'));
        $dashResponse->assertDontSee(route('admin.settings.index'));
        $dashResponse->assertDontSee(route('admin.admins.index'));
        $dashResponse->assertDontSee(route('admin.roles.index'));
        $dashResponse->assertDontSee(route('log-viewer.index'));
    }

    public function test_instructor_can_view_courses_and_grade_tasks_but_cannot_manage_system(): void
    {
        $instructorRole = $this->createRole('مدرب ومعلم', 'instructor', [
            'courses.view',
            'tasks.view',
            'tasks.grade',
            'students.view',
        ]);
        $instructor = $this->createAdminWithRole($instructorRole, 'instructor@alameen.com');

        // Can access courses index, tasks index, students index
        $this->actingAs($instructor, 'admin')->get(route('admin.courses.index'))->assertOk();
        $this->actingAs($instructor, 'admin')->get(route('admin.tasks.index'))->assertOk();
        $this->actingAs($instructor, 'admin')->get(route('admin.students.index'))->assertOk();

        // Cannot create courses (courses.manage is required)
        $this->actingAs($instructor, 'admin')
            ->post(route('admin.courses.store'), [
                'title_ar' => 'كورس تدريبي غير مصرح به',
                'price' => 500,
                'access_duration_days' => 30,
            ])
            ->assertForbidden();

        // Cannot access receipts, settings, admins, roles, log viewer
        $this->actingAs($instructor, 'admin')->get(route('admin.receipts.index'))->assertForbidden();
        $this->actingAs($instructor, 'admin')->get(route('admin.settings.index'))->assertForbidden();
        $this->actingAs($instructor, 'admin')->get(route('admin.admins.index'))->assertForbidden();
        $this->actingAs($instructor, 'admin')->get(route('admin.roles.index'))->assertForbidden();
        $this->actingAs($instructor, 'admin')->get(route('log-viewer.index'))->assertForbidden();

        // Course index for instructor should NOT render the "إضافة كورس جديد" button
        $coursesPage = $this->actingAs($instructor, 'admin')->get(route('admin.courses.index'));
        $coursesPage->assertOk();
        $coursesPage->assertDontSee('modal-add-course');
        $coursesPage->assertDontSee('إضافة كورس جديد');
    }

    public function test_support_agent_can_only_access_support_tickets(): void
    {
        $supportRole = $this->createRole('الدعم الفني', 'support_agent', [
            'support.view',
            'support.manage',
        ]);
        $supportAdmin = $this->createAdminWithRole($supportRole, 'support@alameen.com');

        // Can access support tickets
        $this->actingAs($supportAdmin, 'admin')->get(route('admin.support.index'))->assertOk();

        // Forbidden on finance, courses, students, settings, admins, log viewer
        $this->actingAs($supportAdmin, 'admin')->get(route('admin.receipts.index'))->assertForbidden();
        $this->actingAs($supportAdmin, 'admin')->get(route('admin.courses.index'))->assertForbidden();
        $this->actingAs($supportAdmin, 'admin')->get(route('admin.students.index'))->assertForbidden();
        $this->actingAs($supportAdmin, 'admin')->get(route('admin.settings.index'))->assertForbidden();
        $this->actingAs($supportAdmin, 'admin')->get(route('admin.admins.index'))->assertForbidden();
        $this->actingAs($supportAdmin, 'admin')->get(route('log-viewer.index'))->assertForbidden();

        // Sidebar for support admin only contains support
        $supportPage = $this->actingAs($supportAdmin, 'admin')->get(route('admin.support.index'));
        $supportPage->assertSee(route('admin.support.index'));
        $supportPage->assertDontSee(route('admin.receipts.index'));
        $supportPage->assertDontSee(route('admin.settings.index'));
    }

    public function test_student_actions_are_guarded_against_read_only_student_permission(): void
    {
        $viewerRole = $this->createRole('مطلع فقط للطلاب', 'student_viewer', [
            'students.view',
        ]);
        $viewerAdmin = $this->createAdminWithRole($viewerRole, 'viewer@alameen.com');

        $student = User::factory()->create([
            'status' => 'pending_approval',
        ]);

        // Can view the list
        $this->actingAs($viewerAdmin, 'admin')->get(route('admin.students.index'))->assertOk();

        // Cannot mutate student status
        $this->actingAs($viewerAdmin, 'admin')
            ->post(route('admin.students.approve', $student))
            ->assertForbidden();

        $this->actingAs($viewerAdmin, 'admin')
            ->post(route('admin.students.toggle', $student))
            ->assertForbidden();

        $this->actingAs($viewerAdmin, 'admin')
            ->delete(route('admin.students.destroy', $student))
            ->assertForbidden();

        // Action buttons are hidden in the view
        $studentsIndexView = $this->actingAs($viewerAdmin, 'admin')->get(route('admin.students.index'));
        $studentsIndexView->assertDontSee('موافقة واعتماد');
        $studentsIndexView->assertSee('عرض التفاصيل');
        $studentsIndexView->assertDontSee('إدارة وتفاصيل');
    }
}
