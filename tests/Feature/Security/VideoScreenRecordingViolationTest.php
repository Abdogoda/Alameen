<?php

declare(strict_types=1);

namespace Tests\Feature\Security;

use App\Models\Admin;
use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use App\Notifications\UnifiedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class VideoScreenRecordingViolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_screen_recording_violation_immediately_suspends_student_and_notifies_admins(): void
    {
        Notification::fake();

        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@alameen-center.online',
            'password' => Hash::make('password'),
        ]);

        $student = User::factory()->create([
            'name' => 'أحمد علي',
            'phone' => '01012345678',
            'email' => 'ahmed@example.com',
            'status' => 'active',
        ]);

        $course = Course::create([
            'title_ar' => 'كورس التخاطب الشامل',
            'slug' => 'speech-course-security',
            'price' => 500,
        ]);

        $video = Video::create([
            'course_id' => $course->id,
            'title_ar' => 'الدرس الأول: مقدمة في النطق',
            'slug' => 'intro-security-test',
            'duration_seconds' => 300,
        ]);

        $this->actingAs($student, 'web');
        $this->assertTrue(Auth::guard('web')->check());

        $response = $this->postJson(route('videos.report-violation', $video), [
            'incident_type' => 'screen_recording_attempt',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'suspended',
            ]);

        // Student account must be suspended in database
        $student->refresh();
        $this->assertSame('suspended', $student->status);
        $this->assertTrue($student->isSuspended());

        // Student session must be logged out
        $this->assertFalse(Auth::guard('web')->check());

        // All admins must receive the urgent security notification
        Notification::assertSentTo(
            $admin,
            UnifiedNotification::class,
            function (UnifiedNotification $notification) use ($student, $video) {
                return $notification->type === 'security'
                    && $notification->data['student_id'] === $student->id
                    && $notification->data['video_id'] === $video->id;
            }
        );

        // Next attempt to access an authenticated page must be rejected by EnsureStudentIsApproved
        $nextResponse = $this->actingAs($student, 'web')->get(route('dashboard'));
        $nextResponse->assertRedirect(route('login'));
        $this->assertFalse(Auth::guard('web')->check());
    }

    public function test_guest_cannot_report_violation_without_auth(): void
    {
        $course = Course::create([
            'title_ar' => 'Course For Guest Test',
            'slug' => 'course-guest-test',
            'price' => 200,
        ]);

        $video = Video::create([
            'course_id' => $course->id,
            'title_ar' => 'Video For Guest Test',
            'slug' => 'video-guest-test',
            'duration_seconds' => 300,
        ]);

        $response = $this->postJson(route('videos.report-violation', $video), [
            'incident_type' => 'screen_recording_attempt',
        ]);

        $response->assertUnauthorized();
    }
}
