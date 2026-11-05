<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Level;
use App\Models\User;
use App\Models\Video;
use App\Services\Course\CourseEnrollmentService;
use App\Services\Course\StudentCourseWorkspaceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoEnrollCourseFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected CourseEnrollmentService $enrollmentService;
    protected StudentCourseWorkspaceService $workspaceService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@alameen.com',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->enrollmentService = app(CourseEnrollmentService::class);
        $this->workspaceService = app(StudentCourseWorkspaceService::class);
    }

    public function test_admin_can_toggle_auto_enroll_option_on_course(): void
    {
        $course = Course::create([
            'title_ar' => 'كورس تدريبي اختباري',
            'slug' => 'test-auto-course',
            'price' => 500,
            'access_duration_days' => 365,
            'is_published' => true,
            'auto_enroll_students' => false,
        ]);

        $this->assertFalse($course->auto_enroll_students);

        // Toggle ON
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.toggle-auto-enroll', $course));

        $response->assertSessionHas('success');
        $course->refresh();
        $this->assertTrue($course->auto_enroll_students);

        // Toggle OFF
        $response2 = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.toggle-auto-enroll', $course));

        $response2->assertSessionHas('success');
        $course->refresh();
        $this->assertFalse($course->auto_enroll_students);
    }

    public function test_admin_can_update_course_auto_enroll_students_field(): void
    {
        $course = Course::create([
            'title_ar' => 'دورة تأهيل التخاطب',
            'slug' => 'speech-therapy',
            'price' => 1000,
            'access_duration_days' => 180,
            'is_published' => true,
            'auto_enroll_students' => false,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.courses.update', $course), [
                'title_ar' => 'دورة تأهيل التخاطب المعدلة',
                'slug' => 'speech-therapy',
                'price' => 1000,
                'access_duration_days' => 180,
                'is_published' => '1',
                'auto_enroll_students' => '1',
            ])
            ->assertRedirect();

        $course->refresh();
        $this->assertTrue($course->auto_enroll_students);
    }

    public function test_auto_enrolled_course_automatically_shows_for_approved_student(): void
    {
        $autoCourse = Course::create([
            'title_ar' => 'دورة مجانية للجميع',
            'slug' => 'free-course-for-all',
            'price' => 0,
            'access_duration_days' => 365,
            'is_published' => true,
            'auto_enroll_students' => true,
        ]);

        $paidCourse = Course::create([
            'title_ar' => 'دورة مدفوعة بإيصال',
            'slug' => 'paid-course',
            'price' => 1200,
            'access_duration_days' => 365,
            'is_published' => true,
            'auto_enroll_students' => false,
        ]);

        $student = User::factory()->create([
            'status' => 'active',
        ]);

        // Student has active access to autoCourse, but not paidCourse
        $this->assertTrue($this->enrollmentService->hasActiveAccess($student, $autoCourse));
        $this->assertFalse($this->enrollmentService->hasActiveAccess($student, $paidCourse));

        // In student courses data workspace
        $data = $this->workspaceService->getStudentCoursesData($student);

        $courseIdsInWorkspace = collect($data['myCoursesData'])->pluck('course.id')->all();
        $this->assertContains($autoCourse->id, $courseIdsInWorkspace);
        $this->assertNotContains($paidCourse->id, $courseIdsInWorkspace);

        // In enrolled course IDs
        $this->assertContains($autoCourse->id, $data['enrolledCourseIds']);
        $this->assertNotContains($paidCourse->id, $data['enrolledCourseIds']);

        // In Dashboard data
        $dashData = $this->workspaceService->getDashboardData($student);
        $dashCourseIds = collect($dashData['myCoursesData'])->pluck('course.id')->all();
        $this->assertContains($autoCourse->id, $dashCourseIds);
        $this->assertNotContains($paidCourse->id, $dashCourseIds);

        // In User::getAccessibleCourses
        $accessible = $student->getAccessibleCourses();
        $this->assertTrue($accessible->contains('id', $autoCourse->id));
        $this->assertFalse($accessible->contains('id', $paidCourse->id));
    }

    public function test_disabling_auto_enroll_immediately_hides_course_from_non_subscribed_students(): void
    {
        $course = Course::create([
            'title_ar' => 'دورة عامة',
            'slug' => 'public-general-course',
            'price' => 500,
            'access_duration_days' => 365,
            'is_published' => true,
            'auto_enroll_students' => true,
        ]);

        $student = User::factory()->create(['status' => 'active']);

        $this->assertTrue($this->enrollmentService->hasActiveAccess($student, $course));

        // Admin disables auto_enroll
        $course->update(['auto_enroll_students' => false]);

        $this->assertFalse($this->enrollmentService->hasActiveAccess($student, $course));

        $data = $this->workspaceService->getStudentCoursesData($student);
        $this->assertNotContains($course->id, $data['enrolledCourseIds']);
    }

    public function test_suspended_student_cannot_access_even_if_course_is_auto_enrolled(): void
    {
        $course = Course::create([
            'title_ar' => 'دورة عامة',
            'slug' => 'general-course',
            'price' => 0,
            'access_duration_days' => 365,
            'is_published' => true,
            'auto_enroll_students' => true,
        ]);

        $suspendedStudent = User::factory()->create(['status' => 'suspended']);

        $this->assertFalse($this->enrollmentService->hasActiveAccess($suspendedStudent, $course));
        $this->assertEmpty($suspendedStudent->getAccessibleCourses());
    }

    public function test_student_can_view_lessons_of_auto_enrolled_course_without_receipt(): void
    {
        $course = Course::create([
            'title_ar' => 'دورة أوتوماتيكية كاملة',
            'slug' => 'auto-full-course',
            'price' => 0,
            'access_duration_days' => 365,
            'is_published' => true,
            'auto_enroll_students' => true,
        ]);

        $level = Level::create([
            'course_id' => $course->id,
            'title_ar' => 'المستوى الأول',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $video = Video::create([
            'course_id' => $course->id,
            'level_id' => $level->id,
            'title_ar' => 'الدرس الأول',
            'slug' => 'first-lesson',
            'is_published' => true,
            'is_free_preview' => false,
            'sort_order' => 1,
        ]);

        $student = User::factory()->create(['status' => 'active']);

        // Authenticated student visits lesson of auto-enrolled course
        $response = $this->actingAs($student)
            ->get(route('lessons.show', ['course' => $course->slug, 'video' => $video->slug]));

        $response->assertOk();
    }
}

