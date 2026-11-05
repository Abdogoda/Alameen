<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminStudentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = Admin::first() ?? Admin::factory()->create();
    }

    public function test_admin_can_view_student_details_and_management_page(): void
    {
        $student = User::factory()->create([
            'name' => 'أحمد مصطفى التجريبي',
            'email' => 'ahmed.test@example.com',
            'phone' => '01012345678',
            'country' => 'مصر',
            'city' => 'القاهرة',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.students.show', $student));

        $response->assertStatus(200);
        $response->assertSee('أحمد مصطفى التجريبي');
        $response->assertSee('ahmed.test@example.com');
        $response->assertSee('01012345678');
        $response->assertSee('القاهرة');
        $response->assertSee('منح كورس جديد');
        $response->assertSee('سجل مشاهدة المحاضرات');
    }

    public function test_admin_can_update_student_profile(): void
    {
        $student = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'phone' => '01000000000',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.students.update', $student), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone' => '01222222222',
                'country' => 'مصر',
                'city' => 'الإسكندرية',
                'address' => 'شارع سيدي بشر',
                'status' => 'suspended',
            ]);

        $response->assertSessionHas('success');
        $student->refresh();
        $this->assertSame('Updated Name', $student->name);
        $this->assertSame('updated@example.com', $student->email);
        $this->assertSame('01222222222', $student->phone);
        $this->assertSame('suspended', $student->status);
    }

    public function test_admin_can_update_student_password(): void
    {
        $student = User::factory()->create([
            'password' => Hash::make('old_password'),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.students.password.update', $student), [
                'password' => 'new_secret_password_123',
            ]);

        $response->assertSessionHas('success');
        $student->refresh();
        $this->assertTrue(Hash::check('new_secret_password_123', $student->password));
    }

    public function test_admin_can_enroll_student_in_course_and_manage_enrollment(): void
    {
        $student = User::factory()->create();
        $course = Course::first() ?? Course::create([
            'title_ar' => 'كورس التخاطب المتقدم',
            'slug' => 'advanced-speech-course',
            'price' => 1200,
            'access_duration_days' => 180,
            'is_published' => true,
        ]);

        // Enroll
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.enroll', $student), [
                'course_id' => $course->id,
                'duration_days' => 90,
            ]);

        $response->assertSessionHas('success');
        $this->assertTrue($student->courses()->where('course_id', $course->id)->exists());

        // Update enrollment
        $newExpiry = now()->addDays(200)->format('Y-m-d');
        $updateResponse = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.students.enrollments.update', [$student, $course]), [
                'access_expires_at' => $newExpiry,
                'status' => 'active',
            ]);

        $updateResponse->assertSessionHas('success');

        // Revoke enrollment
        $revokeResponse = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.students.enrollments.destroy', [$student, $course]));

        $revokeResponse->assertSessionHas('success');
        $this->assertFalse($student->courses()->where('course_id', $course->id)->exists());
    }

    public function test_admin_can_issue_and_delete_certificate_for_student(): void
    {
        $student = User::factory()->create();
        $course = Course::first() ?? Course::create([
            'title_ar' => 'كورس صعوبات التعلم',
            'slug' => 'learning-disabilities-course',
            'price' => 1500,
            'access_duration_days' => 365,
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.certificates.store', $student), [
                'course_id' => $course->id,
                'completion_percentage' => 98.5,
            ]);

        $response->assertSessionHas('success');
        $cert = Certificate::where('user_id', $student->id)->where('course_id', $course->id)->first();
        $this->assertNotNull($cert);
        $this->assertEquals(98.5, $cert->completion_percentage);

        // Delete certificate
        $deleteResponse = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.students.certificates.destroy', [$student, $cert]));

        $deleteResponse->assertSessionHas('success');
        $this->assertNull(Certificate::find($cert->id));
    }

    public function test_admin_can_update_and_reset_video_progress(): void
    {
        $student = User::factory()->create();
        $course = Course::first() ?? Course::create([
            'title_ar' => 'كورس النطق',
            'slug' => 'speech-articulation',
            'price' => 800,
            'access_duration_days' => 180,
            'is_published' => true,
        ]);

        $video = Video::first() ?? Video::create([
            'course_id' => $course->id,
            'title_ar' => 'مقدمة التخاطب',
            'slug' => 'speech-intro',
            'video_source' => 'mux',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
        ]);

        // Complete 100%
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.video-progress.update', [$student, $video]), [
                'action' => 'complete',
            ]);

        $response->assertSessionHas('success');
        $progress = VideoProgress::where('user_id', $student->id)->where('video_id', $video->id)->first();
        $this->assertTrue($progress->is_completed);
        $this->assertEquals(100.0, $progress->completion_percentage);

        // Reset to 0%
        $resetResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.video-progress.update', [$student, $video]), [
                'action' => 'reset',
            ]);

        $resetResponse->assertSessionHas('success');
        $progress->refresh();
        $this->assertFalse($progress->is_completed);
        $this->assertEquals(0.0, $progress->completion_percentage);
    }

    public function test_student_does_not_get_certificate_automatically_upon_course_completion_admin_decides(): void
    {
        $student = User::factory()->create();
        $course = Course::create([
            'title_ar' => 'دورة التأهيل التخاطبي المتقدم',
            'slug' => 'advanced-speech-therapy',
            'price' => 1200,
            'access_duration_days' => 365,
            'is_published' => true,
        ]);

        $video = Video::create([
            'course_id' => $course->id,
            'title_ar' => 'محاضرة التقييم التشخيصي',
            'slug' => 'diagnostic-evaluation',
            'video_source' => 'mux',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
            'is_published' => true,
        ]);

        // Enroll student
        $student->courses()->attach($course->id, [
            'status' => 'active',
            'access_starts_at' => now(),
            'access_expires_at' => now()->addDays(365),
        ]);

        // Student completes 100% of the video via API
        $response = $this->actingAs($student)->postJson(route('videos.progress', $video), [
            'watched_seconds' => 600,
            'is_ended' => true,
            'duration' => 600,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'is_completed' => true,
            'course_progress' => 100,
            'certificate_issued' => false,
        ]);

        // Verify student does NOT receive certificate automatically
        $this->assertDatabaseMissing('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        // Admin decides to issue certificate to the student
        $adminResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.certificates.store', $student), [
                'course_id' => $course->id,
                'completion_percentage' => 100,
            ]);

        $adminResponse->assertSessionHas('success');

        // Now certificate exists
        $this->assertDatabaseHas('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        // Student can now view certificate in settings
        $certResponse = $this->actingAs($student)->get(route('settings.certificates'));
        $certResponse->assertOk();
        $certResponse->assertSee($course->title_ar);
    }

    public function test_delete_student_modal_triggers_rendered_on_index_and_show_pages(): void
    {
        $student = User::factory()->create([
            'name' => 'طالب للاختبار الحذف',
            'email' => 'to-delete@example.com',
        ]);

        // Index page should show delete button and delete modal
        $indexResponse = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.students.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('modal-delete-student');
        $indexResponse->assertSee('openDeleteStudentModal');
        $indexResponse->assertSee('كلمة مرور المشرف لتأكيد الحذف');

        // Show page should also show delete button and delete modal
        $showResponse = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.students.show', $student));
        $showResponse->assertOk();
        $showResponse->assertSee('modal-delete-student');
        $showResponse->assertSee('حذف حساب الطالب نهائياً');
        $showResponse->assertSee('كلمة مرور المشرف لتأكيد الحذف');
    }

    public function test_admin_cannot_delete_student_without_admin_password(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.students.destroy', $student), [
                'admin_password' => '',
            ]);

        $response->assertSessionHasErrors('admin_password');
        $this->assertDatabaseHas('users', ['id' => $student->id]);
    }

    public function test_admin_cannot_delete_student_with_invalid_admin_password(): void
    {
        $this->admin->password = Hash::make('real_admin_password');
        $this->admin->save();

        $student = User::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.students.destroy', $student), [
                'admin_password' => 'wrong_password_xyz',
            ]);

        $response->assertSessionHasErrors('admin_password');
        $this->assertDatabaseHas('users', ['id' => $student->id]);
    }

    public function test_admin_can_delete_student_with_valid_admin_password(): void
    {
        $this->admin->password = Hash::make('super_secure_admin_pass');
        $this->admin->save();

        $student = User::factory()->create([
            'name' => 'محمد سمير الحذفي',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.students.destroy', $student), [
                'admin_password' => 'super_secure_admin_pass',
            ]);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $student->id]);
    }
}
