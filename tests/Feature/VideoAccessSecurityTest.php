<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use App\Services\Course\CourseEnrollmentService;
use App\Services\Video\VideoProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoAccessSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected Course $course;

    protected Video $freePreviewVideo;

    protected Video $paidVideo1;

    protected Video $paidVideo2;

    protected CourseEnrollmentService $enrollmentService;

    protected VideoProgressService $progressService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->enrollmentService = app(CourseEnrollmentService::class);
        $this->progressService = app(VideoProgressService::class);

        $this->course = Course::create([
            'title_ar' => 'دورة النطق الشاملة',
            'slug' => 'speech-course',
            'price' => 500,
            'access_duration_days' => 30,
        ]);

        $this->freePreviewVideo = Video::create([
            'course_id' => $this->course->id,
            'title_ar' => 'مقدمة مجانية',
            'slug' => 'free-intro',
            'duration_seconds' => 300,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
            'is_free_preview' => true,
            'bunny_video_id' => 'bunny-free-123',
        ]);

        $this->paidVideo1 = Video::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المحاضرة الأولى المدفوعة',
            'slug' => 'paid-lesson-1',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'sort_order' => 2,
            'is_free_preview' => false,
            'bunny_video_id' => 'bunny-paid-1',
        ]);

        $this->paidVideo2 = Video::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المحاضرة الثانية المدفوعة',
            'slug' => 'paid-lesson-2',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'sort_order' => 3,
            'is_free_preview' => false,
            'bunny_video_id' => 'bunny-paid-2',
        ]);
    }

    /**
     * Guests cannot view any lesson without authentication.
     */
    public function test_guest_is_redirected_to_login_when_accessing_lesson(): void
    {
        $response = $this->get(route('lessons.show', [$this->course->slug, $this->paidVideo1->slug]));

        $response->assertRedirect(route('login'));
    }

    /**
     * Unsubscribed user is prevented from viewing paid videos.
     */
    public function test_unsubscribed_student_cannot_view_paid_video(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(
            route('lessons.show', [$this->course->slug, $this->paidVideo1->slug])
        );

        $response->assertRedirect(route('courses.show', $this->course->slug));
        $response->assertSessionHas('error');
    }

    /**
     * Unsubscribed user CAN view a free preview video.
     */
    public function test_unsubscribed_student_can_view_free_preview_video(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(
            route('lessons.show', [$this->course->slug, $this->freePreviewVideo->slug])
        );

        $response->assertOk();
        $response->assertViewIs('web.pages.lesson');
        $response->assertViewHas('canAccess', true);
    }

    /**
     * Student with expired subscription cannot view paid videos.
     */
    public function test_expired_subscription_student_cannot_view_paid_video(): void
    {
        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course, durationDays: -1);

        $response = $this->actingAs($user)->get(
            route('lessons.show', [$this->course->slug, $this->paidVideo1->slug])
        );

        $response->assertRedirect(route('courses.show', $this->course->slug));
        $response->assertSessionHas('error');
    }

    /**
     * Subscribed student cannot access a sequentially locked video before completing predecessor.
     */
    public function test_subscribed_student_is_blocked_from_sequentially_locked_video(): void
    {
        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course);

        // Accessing paidVideo2 directly before finishing paidVideo1
        $response = $this->actingAs($user)->get(
            route('lessons.show', [$this->course->slug, $this->paidVideo2->slug])
        );

        $response->assertOk();
        $response->assertViewHas('canAccess', false);
        $response->assertViewHas('embedUrl', '');
    }

    /**
     * Subscribed student can access video once predecessor is completed.
     */
    public function test_subscribed_student_can_access_video_after_completing_predecessor(): void
    {
        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course);

        // Complete free preview and paidVideo1
        $this->progressService->recordProgress($user, $this->freePreviewVideo, 300);
        $this->progressService->recordProgress($user, $this->paidVideo1, 600);

        $response = $this->actingAs($user)->get(
            route('lessons.show', [$this->course->slug, $this->paidVideo2->slug])
        );

        $response->assertOk();
        $response->assertViewHas('canAccess', true);
        $this->assertNotEmpty($response->viewData('embedUrl'));
    }

    /**
     * Unauthorized user cannot record progress on a video they cannot access.
     */
    public function test_unauthorized_user_cannot_record_progress(): void
    {
        $user = User::factory()->create();

        // User is not subscribed and tries to POST progress to a paid video
        $response = $this->actingAs($user)->postJson(
            route('videos.progress', $this->paidVideo1->id),
            ['watched_seconds' => 300]
        );

        $response->assertForbidden();
    }

    /**
     * Subscribed user cannot record progress on sequentially locked video.
     */
    public function test_subscribed_user_cannot_record_progress_on_locked_video(): void
    {
        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course);

        // paidVideo2 is locked until paidVideo1 is finished
        $response = $this->actingAs($user)->postJson(
            route('videos.progress', $this->paidVideo2->id),
            ['watched_seconds' => 300]
        );

        $response->assertForbidden();
    }

    /**
     * Video from a different course returns 404 when requested under mismatched course.
     */
    public function test_mismatched_course_and_video_returns_404(): void
    {
        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course);

        $otherCourse = Course::create([
            'title_ar' => 'دورة أخرى',
            'slug' => 'other-course',
        ]);

        $response = $this->actingAs($user)->get(
            route('lessons.show', [$otherCourse->slug, $this->paidVideo1->slug])
        );

        $response->assertNotFound();
    }

    /**
     * Lesson page renders with watermark canvas and does not expose direct download links.
     */
    public function test_video_player_renders_without_exposing_download_or_direct_links(): void
    {
        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course);

        $secureVideo = Video::create([
            'course_id' => $this->course->id,
            'title_ar' => 'محاضرة آمنة',
            'slug' => 'secure-lecture',
            'mux_playback_id' => 'mux-sec-123',
            'video_source' => 'mux',
            'duration_seconds' => 300,
            'min_completion_percentage' => 80,
            'sort_order' => 0,
            'is_free_preview' => true,
            'is_published' => true,
        ]);

        $response = $this->actingAs($user)->get(
            route('lessons.show', [$this->course->slug, $secureVideo->slug])
        );

        $response->assertOk();
        // Check that watermark canvas is rendered
        $response->assertSee('x-ref="watermarkCanvas"', false);
        // Check that controlslist nodownload is present
        $response->assertSee('nodownload', false);
        // Check that anti-screen recording shield is present
        $response->assertSee('shieldActive', false);
        // Check user info is present in watermark player data and fallbacks are absent
        $response->assertSee(e($user->email), false);
        $response->assertDontSee('STD-001', false);
        $response->assertDontSee('طالب الأمين', false);
        $response->assertDontSee('01000000000', false);
        // Ensure no direct download link or raw stream link is presented as a downloadable anchor
        $response->assertDontSee('download="'.$secureVideo->slug.'"', false);
    }

    /**
     * Storage fallback returns secure non-downloadable inline streaming headers.
     */
    public function test_storage_video_streaming_returns_secure_anti_download_headers(): void
    {
        // Create a dummy video file in public storage for testing
        Storage::disk('public')->put('videos/lessons/test_lecture.mp4', 'dummy_video_stream');

        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course);

        // Sort order 0 so it is the very first video and accessible
        $storedVideo = Video::create([
            'course_id' => $this->course->id,
            'title_ar' => 'محاضرة التخزين',
            'slug' => 'stored-lecture',
            'video_url' => '/storage/videos/lessons/test_lecture.mp4',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'sort_order' => 0,
            'is_free_preview' => true,
            'is_published' => true,
        ]);

        $response = $this->actingAs($user)->get('/storage/videos/lessons/test_lecture.mp4');

        $response->assertOk();
        $response->assertHeader('Content-Disposition', 'inline');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');

        Storage::disk('public')->delete('videos/lessons/test_lecture.mp4');
    }
}
