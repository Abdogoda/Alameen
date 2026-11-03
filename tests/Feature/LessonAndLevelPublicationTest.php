<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Level;
use App\Models\Role;
use App\Models\User;
use App\Models\Video;
use App\Services\Course\CourseEnrollmentService;
use App\Services\Video\VideoProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonAndLevelPublicationTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected Course $course;

    protected Level $level1;

    protected Video $lesson1;

    protected Video $lesson2;

    protected CourseEnrollmentService $enrollmentService;

    protected VideoProgressService $progressService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->enrollmentService = app(CourseEnrollmentService::class);
        $this->progressService = app(VideoProgressService::class);

        $role = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'description' => 'Full administrative access',
            'permissions' => ['*'],
        ]);

        $this->admin = Admin::create([
            'role_id' => $role->id,
            'name' => 'Admin User',
            'email' => 'admin@alameen.test',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $this->course = Course::create([
            'title_ar' => 'دبلوم التخاطب الشامل',
            'slug' => 'speech-therapy-diploma',
            'price' => 1200,
            'access_duration_days' => 365,
            'is_published' => true,
        ]);

        $this->level1 = Level::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المستوى الأول: الأساسيات',
            'sort_order' => 1,
        ]);

        $this->lesson1 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $this->level1->id,
            'title_ar' => 'الدرس الأول: مقدمة تشريحية',
            'slug' => 'lesson-intro-anatomy',
            'video_source' => 'link',
            'video_url' => 'https://example.com/video1.mp4',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
        ]);

        $this->lesson2 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $this->level1->id,
            'title_ar' => 'الدرس الثاني: الفحص الإكلينيكي',
            'slug' => 'lesson-clinical-exam',
            'video_source' => 'link',
            'video_url' => 'https://example.com/video2.mp4',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'sort_order' => 2,
        ]);
    }

    /**
     * 1. Levels and lessons default to is_published = true.
     */
    public function test_level_and_lesson_is_published_defaults_to_true(): void
    {
        $this->assertTrue($this->level1->fresh()->is_published);
        $this->assertTrue($this->lesson1->fresh()->is_published);
        $this->assertTrue($this->lesson2->fresh()->is_published);
    }

    /**
     * 2. Admin can create level with is_published flag (defaults to true if omitted).
     */
    public function test_admin_can_create_level_with_publish_option(): void
    {
        // Created with explicit false
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.levels.store', $this->course), [
                'title_ar' => 'المستوى الثاني (مسودة)',
                'is_published' => '0',
            ]);

        $response->assertSessionHas('success');
        $draftLevel = Level::where('title_ar', 'المستوى الثاني (مسودة)')->first();
        $this->assertNotNull($draftLevel);
        $this->assertFalse($draftLevel->is_published);

        // Created without is_published -> defaults to true
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.levels.store', $this->course), [
                'title_ar' => 'المستوى الثالث (منشور تلقائياً)',
            ]);

        $publishedLevel = Level::where('title_ar', 'المستوى الثالث (منشور تلقائياً)')->first();
        $this->assertNotNull($publishedLevel);
        $this->assertTrue($publishedLevel->is_published);
    }

    /**
     * 3. Admin can update level publication status.
     */
    public function test_admin_can_update_level_publication_status(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.courses.levels.update', [$this->course, $this->level1]), [
                'title_ar' => $this->level1->title_ar,
                'is_published' => '0',
            ]);

        $this->assertFalse($this->level1->fresh()->is_published);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.courses.levels.update', [$this->course, $this->level1]), [
                'title_ar' => $this->level1->title_ar,
                'is_published' => '1',
            ]);

        $this->assertTrue($this->level1->fresh()->is_published);
    }

    /**
     * 4. Admin can quick-toggle level publication status.
     */
    public function test_admin_can_toggle_level_publication_status(): void
    {
        $this->assertTrue($this->level1->is_published);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.levels.toggle-publish', [$this->course, $this->level1]));

        $response->assertSessionHas('success');
        $this->assertFalse($this->level1->fresh()->is_published);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.levels.toggle-publish', [$this->course, $this->level1]));

        $this->assertTrue($this->level1->fresh()->is_published);
    }

    /**
     * 5. Admin can create lesson with publish option.
     */
    public function test_admin_can_create_lesson_with_publish_option(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.videos.store', $this->course), [
                'level_id' => $this->level1->id,
                'title_ar' => 'درس مسودة جديد',
                'duration_minutes' => 15,
                'is_published' => '0',
                'video_source' => 'link',
                'video_url' => 'https://example.com/draft.mp4',
            ]);

        $response->assertRedirect(route('admin.courses.show', $this->course));
        $draftLesson = Video::where('title_ar', 'درس مسودة جديد')->first();
        $this->assertNotNull($draftLesson);
        $this->assertFalse($draftLesson->is_published);
    }

    /**
     * 6. Admin can update lesson publication status.
     */
    public function test_admin_can_update_lesson_publication_status(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.courses.videos.update', [$this->course, $this->lesson1]), [
                'level_id' => $this->level1->id,
                'title_ar' => $this->lesson1->title_ar,
                'duration_minutes' => 20,
                'is_published' => '0',
            ]);

        $this->assertFalse($this->lesson1->fresh()->is_published);
    }

    /**
     * 7. Admin can quick-toggle lesson publication status.
     */
    public function test_admin_can_toggle_lesson_publication_status(): void
    {
        $this->assertTrue($this->lesson1->is_published);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.videos.toggle-publish', [$this->course, $this->lesson1]));

        $response->assertSessionHas('success');
        $this->assertFalse($this->lesson1->fresh()->is_published);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.videos.toggle-publish', [$this->course, $this->lesson1]));

        $this->assertTrue($this->lesson1->fresh()->is_published);
    }

    /**
     * 8. Admin can bulk unpublish and publish lessons.
     */
    public function test_admin_can_bulk_publish_and_unpublish_lessons(): void
    {
        // Unpublish all course lessons
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.videos.settings.apply', $this->course), [
                'scope' => 'course',
                'publish_policy' => 'unpublish_all',
            ]);

        $response->assertSessionHas('success');
        $this->assertFalse($this->lesson1->fresh()->is_published);
        $this->assertFalse($this->lesson2->fresh()->is_published);

        // Publish all course lessons
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.videos.settings.apply', $this->course), [
                'scope' => 'course',
                'publish_policy' => 'publish_all',
            ]);

        $this->assertTrue($this->lesson1->fresh()->is_published);
        $this->assertTrue($this->lesson2->fresh()->is_published);
    }

    /**
     * 9. Unpublished level and its lessons are not visible to students.
     */
    public function test_unpublished_level_and_its_lessons_are_not_shown_to_students(): void
    {
        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course);

        // When level is published, both lessons appear in publishedVideos
        $this->assertCount(2, $this->course->publishedVideos);
        $this->assertCount(1, $this->course->publishedLevels);

        // Unpublish the level
        $this->level1->update(['is_published' => false]);

        // Neither the level nor its lessons should appear in published collections
        $this->assertCount(0, $this->course->fresh()->publishedLevels);
        $this->assertCount(0, $this->course->fresh()->publishedVideos);

        // Student accessing any lesson in the unpublished level receives 404
        $response = $this->actingAs($user)->get(route('lessons.show', [$this->course->slug, $this->lesson1->slug]));
        $response->assertNotFound();
    }

    /**
     * 10. Student requesting an unpublished lesson receives 404.
     */
    public function test_student_cannot_access_unpublished_lesson_returns_404(): void
    {
        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course);

        // Lesson 2 is unpublished
        $this->lesson2->update(['is_published' => false]);

        // Lesson 1 is accessible
        $response1 = $this->actingAs($user)->get(route('lessons.show', [$this->course->slug, $this->lesson1->slug]));
        $response1->assertOk();

        // Lesson 2 returns 404 Not Found
        $response2 = $this->actingAs($user)->get(route('lessons.show', [$this->course->slug, $this->lesson2->slug]));
        $response2->assertNotFound();
    }

    /**
     * 11. Unpublished lessons are excluded from course progress calculation and total counts.
     */
    public function test_unpublished_lessons_are_excluded_from_student_course_progress(): void
    {
        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course);

        // Initially 2 lessons. Student completes lesson 1 -> 50%
        $this->progressService->recordProgress($user, $this->lesson1, 600);
        $this->assertEquals(50.0, $this->progressService->getCourseProgressPercentage($user, $this->course));

        // Unpublish lesson 2 -> total published lessons is now 1 (lesson 1 only)
        $this->lesson2->update(['is_published' => false]);
        $this->assertEquals(100.0, $this->progressService->getCourseProgressPercentage($user, $this->course));
    }

    /**
     * 12. Sequential locking skips unpublished lessons.
     */
    public function test_sequential_locking_skips_unpublished_lessons(): void
    {
        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course);

        // Add lesson 3
        $lesson3 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $this->level1->id,
            'title_ar' => 'الدرس الثالث: التخاطب العملي',
            'slug' => 'lesson-speech-practice',
            'video_source' => 'link',
            'video_url' => 'https://example.com/video3.mp4',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'sort_order' => 3,
        ]);

        // Complete lesson 1
        $this->progressService->recordProgress($user, $this->lesson1, 600);

        // If lesson 2 is unpublished, lesson 3 should look back to lesson 1 as predecessor
        $this->lesson2->update(['is_published' => false]);

        // Because lesson 1 is complete and lesson 2 is unpublished, lesson 3 should be unlocked!
        $this->assertTrue($this->progressService->canAccessVideo($user, $lesson3));
    }

    /**
     * 13. API playback token cannot be fetched for unpublished lessons.
     */
    public function test_playback_token_fails_for_unpublished_lesson(): void
    {
        $user = User::factory()->create();
        $this->enrollmentService->enroll($user, $this->course);

        $this->lesson1->update([
            'is_published' => false,
            'mux_playback_id' => 'sample_playback_id',
        ]);

        $response = $this->actingAs($user)->getJson(route('videos.playback-token', $this->lesson1));
        $response->assertForbidden();
    }
}
