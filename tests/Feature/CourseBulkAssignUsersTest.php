<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use App\Services\Course\CourseEnrollmentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseBulkAssignUsersTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected Course $course;
    protected CourseEnrollmentService $enrollmentService;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super_admin',
            'permissions' => ['*'],
            'is_system' => true,
        ]);

        $this->admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@alameen.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
            'locale' => 'ar',
        ]);

        $this->course = Course::create([
            'title_ar' => 'دورة تأهيل أخصائي التخاطب',
            'title_en' => 'Speech Specialist Course',
            'slug' => 'speech-specialist-course',
            'price' => 1200,
            'access_duration_days' => 365,
            'is_published' => true,
            'auto_enroll_students' => false,
        ]);

        $this->enrollmentService = app(CourseEnrollmentService::class);
    }

    public function test_admin_can_view_assign_users_page(): void
    {
        $user1 = User::factory()->create(['name' => 'Ahmed Ali', 'email' => 'ahmed@test.com']);
        $user2 = User::factory()->create(['name' => 'Sara Omar', 'email' => 'sara@test.com']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.courses.assign-users', $this->course));

        $response->assertOk();
        $response->assertViewIs('admin.courses.assign-users');
        $response->assertSee('Ahmed Ali');
        $response->assertSee('Sara Omar');
        $response->assertSee($this->course->title_ar);
    }

    public function test_admin_can_bulk_assign_selected_users(): void
    {
        $user1 = User::factory()->create(['name' => 'User One']);
        $user2 = User::factory()->create(['name' => 'User Two']);
        $user3 = User::factory()->create(['name' => 'User Three']);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.assign-users.update', $this->course), [
                'user_ids' => [$user1->id, $user2->id],
                'duration_type' => 'default',
                'sync_mode' => '1',
            ]);

        $response->assertSessionHas('success');

        $this->assertTrue($this->enrollmentService->hasActiveAccess($user1, $this->course));
        $this->assertTrue($this->enrollmentService->hasActiveAccess($user2, $this->course));
        $this->assertFalse($this->enrollmentService->hasActiveAccess($user3, $this->course));
    }

    public function test_admin_can_update_assignments_and_sync_removes_deselected_users(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        // Initially enroll user1 and user2
        $this->enrollmentService->enroll($user1, $this->course, 365, $this->admin->id);
        $this->enrollmentService->enroll($user2, $this->course, 365, $this->admin->id);

        $this->assertTrue($this->enrollmentService->hasActiveAccess($user1, $this->course));
        $this->assertTrue($this->enrollmentService->hasActiveAccess($user2, $this->course));

        // Update anytime: now select user2 and user3, user1 is deselected
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.assign-users.update', $this->course), [
                'user_ids' => [$user2->id, $user3->id],
                'duration_type' => 'default',
                'sync_mode' => '1',
            ]);

        $response->assertSessionHas('success');

        // user1 should no longer be enrolled in the course
        $this->assertFalse($this->enrollmentService->hasActiveAccess($user1, $this->course));
        $this->assertTrue($this->enrollmentService->hasActiveAccess($user2, $this->course));
        $this->assertTrue($this->enrollmentService->hasActiveAccess($user3, $this->course));
    }

    public function test_admin_can_assign_with_custom_days(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.assign-users.update', $this->course), [
                'user_ids' => [$user->id],
                'duration_type' => 'custom_days',
                'duration_days' => 90,
                'sync_mode' => '1',
            ]);

        $response->assertSessionHas('success');
        $this->assertTrue($this->enrollmentService->hasActiveAccess($user, $this->course));

        $remaining = $this->enrollmentService->getRemainingDays($user, $this->course);
        $this->assertGreaterThanOrEqual(89, $remaining);
        $this->assertLessThanOrEqual(91, $remaining);
    }

    public function test_admin_can_quick_toggle_user_enrollment_via_ajax(): void
    {
        $user = User::factory()->create();

        // 1. First toggle -> should enroll
        $response1 = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.courses.assign-users.toggle', $this->course), [
                'user_id' => $user->id,
            ]);

        $response1->assertOk();
        $response1->assertJson([
            'success' => true,
            'enrolled' => true,
        ]);
        $this->assertTrue($this->enrollmentService->hasActiveAccess($user, $this->course));

        // 2. Second toggle -> should revoke
        $response2 = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.courses.assign-users.toggle', $this->course), [
                'user_id' => $user->id,
            ]);

        $response2->assertOk();
        $response2->assertJson([
            'success' => true,
            'enrolled' => false,
        ]);
        $this->assertFalse($this->enrollmentService->hasActiveAccess($user, $this->course));
    }

    public function test_course_index_and_show_pages_display_assign_users_links(): void
    {
        $responseIndex = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.courses.index'));
        $responseIndex->assertOk();
        $responseIndex->assertSee(route('admin.courses.assign-users', $this->course));

        $responseShow = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.courses.show', $this->course));
        $responseShow->assertOk();
        $responseShow->assertSee(route('admin.courses.assign-users', $this->course));
    }
}

