<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Level;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoProgress;
use App\Services\Course\CourseEnrollmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseDetailsDynamicProgressTest extends TestCase
{
    use RefreshDatabase;

    protected Course $course;
    protected Level $level1;
    protected Video $video1;
    protected Video $video2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->course = Course::create([
            'title_ar' => 'دورة اختبار التخاطب',
            'title_en' => 'Test Speech Therapy Course',
            'slug' => 'test-speech-therapy-course',
            'price' => 500,
            'access_duration_days' => 30,
            'is_published' => true,
        ]);

        $this->level1 = Level::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المستوى الأول التجريبي',
            'title_en' => 'Test Level 1',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->video1 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $this->level1->id,
            'title_ar' => 'المحاضرة الأولى التجريبية',
            'title_en' => 'Test Lesson 1',
            'slug' => 'test-lesson-1',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->video2 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $this->level1->id,
            'title_ar' => 'المحاضرة الثانية التجريبية',
            'title_en' => 'Test Lesson 2',
            'slug' => 'test-lesson-2',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'sort_order' => 2,
            'is_published' => true,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('courses.show', $this->course->slug));
        $response->assertRedirect(route('login'));
    }

    public function test_new_student_sees_zero_percent_and_no_mock_data(): void
    {
        $newStudent = User::factory()->create();

        $response = $this->actingAs($newStudent)->get(route('courses.show', $this->course->slug));

        $response->assertOk();
        $response->assertViewIs('web.pages.course-details');
        $response->assertViewHas('courseProgressPercentage', 0.0);
        $response->assertViewHas('watchedMinutes', 0);
        $response->assertViewHas('completedVideosCount', 0);

        // Ensure static Figma mock strings are NOT visible in the text
        $response->assertDontSeeText('168 of 400');
        $response->assertDontSeeText('١٦٨ من ٤٠٠');
        $response->assertSee('<span class="text-[22px] sm:text-[24px] text-[#0B2A8F] dark:text-blue-400 font-mono">0%</span>', false);
    }

    public function test_enrolled_student_with_progress_sees_real_dynamic_data(): void
    {
        $student = User::factory()->create();
        app(CourseEnrollmentService::class)->enroll($student, $this->course, 365);

        // Record 100% completion on video 1
        VideoProgress::create([
            'user_id' => $student->id,
            'video_id' => $this->video1->id,
            'watched_seconds' => 600,
            'completion_percentage' => 100.0,
            'is_completed' => true,
            'last_watched_at' => now(),
        ]);

        $response = $this->actingAs($student)->get(route('courses.show', $this->course->slug));

        $response->assertOk();
        // Since 1 of 2 videos is completed, progress should be 50%
        $response->assertViewHas('courseProgressPercentage', 50.0);
        $response->assertViewHas('completedVideosCount', 1);
        $response->assertViewHas('watchedMinutes', 10); // 600 seconds = 10 minutes
        $response->assertSee('50%');
    }
}
