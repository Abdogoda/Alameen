<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Level;
use App\Models\Setting;
use App\Models\User;
use App\Models\Video;
use App\Services\Course\CourseEnrollmentService;
use App\Services\Video\VideoProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tests\TestCase;

class SequentialLevelAndLessonAccessTest extends TestCase
{
    use RefreshDatabase;

    protected Course $course;

    protected Level $level1;

    protected Level $level2;

    protected Video $lesson1;

    protected Video $lesson2;

    protected Video $lesson3;

    protected Video $lesson4;

    protected User $student;

    protected VideoProgressService $progressService;

    protected CourseEnrollmentService $enrollmentService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->progressService = app(VideoProgressService::class);
        $this->enrollmentService = app(CourseEnrollmentService::class);

        Setting::updateOrCreate(
            ['key' => 'sequential_video_lock'],
            ['value_ar' => '1', 'value_en' => '1', 'group' => 'security']
        );

        $this->course = Course::create([
            'title_ar' => 'دبلوم التخاطب الإكلينيكي',
            'slug' => 'speech-diploma',
            'price' => 1000,
            'access_duration_days' => 365,
            'is_published' => true,
        ]);

        $this->level1 = Level::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المستوى الأول: الأساسيات',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->level2 = Level::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المستوى الثاني: التطبيق السريري',
            'sort_order' => 2,
            'is_published' => true,
        ]);

        $this->lesson1 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $this->level1->id,
            'title_ar' => 'المحاضرة 1: تشريح جهاز النطق',
            'slug' => 'lesson-1-anatomy',
            'duration_seconds' => 1000,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
            'is_free_preview' => false,
            'is_published' => true,
            'mux_playback_id' => 'sample-mux-1',
        ]);

        $this->lesson2 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $this->level1->id,
            'title_ar' => 'المحاضرة 2: فيزيولوجيا الصوت',
            'slug' => 'lesson-2-physiology',
            'duration_seconds' => 1000,
            'min_completion_percentage' => 80,
            'sort_order' => 2,
            'is_free_preview' => false,
            'is_published' => true,
            'mux_playback_id' => 'sample-mux-2',
        ]);

        $this->lesson3 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $this->level2->id,
            'title_ar' => 'المحاضرة 3: تقييم التأتأة',
            'slug' => 'lesson-3-stuttering',
            'duration_seconds' => 1000,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
            'is_free_preview' => false,
            'is_published' => true,
            'mux_playback_id' => 'sample-mux-3',
        ]);

        $this->lesson4 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $this->level2->id,
            'title_ar' => 'المحاضرة 4: خطة العلاج السريري',
            'slug' => 'lesson-4-clinical-plan',
            'duration_seconds' => 1000,
            'min_completion_percentage' => 80,
            'sort_order' => 2,
            'is_free_preview' => false,
            'is_published' => true,
            'mux_playback_id' => 'sample-mux-4',
        ]);

        $this->student = User::factory()->create();
        $this->enrollmentService->enroll($this->student, $this->course, 365, null);
    }

    /**
     * 1. Initially: Lesson 1 is unlocked (first lesson), Lesson 2 is locked, Level 2 is locked.
     */
    public function test_initial_curriculum_state_only_first_lesson_unlocked(): void
    {
        $this->assertTrue($this->progressService->canAccessLevel($this->student, $this->level1));
        $this->assertFalse($this->progressService->canAccessLevel($this->student, $this->level2));

        $this->assertTrue($this->progressService->canAccessVideo($this->student, $this->lesson1));
        $this->assertFalse($this->progressService->canAccessVideo($this->student, $this->lesson2));
        $this->assertFalse($this->progressService->canAccessVideo($this->student, $this->lesson3));
        $this->assertFalse($this->progressService->canAccessVideo($this->student, $this->lesson4));
    }

    /**
     * 2. After completing Lesson 1:
     * - Lesson 1 can still be opened any time (completed lesson).
     * - Lesson 2 unlocks (current lesson).
     * - Lesson 3 and Level 2 remain locked until Lesson 2 is finished.
     */
    public function test_completed_lesson_can_be_reopened_anytime_and_unlocks_next_lesson(): void
    {
        // Student completes Lesson 1 (85% watched >= 80% threshold)
        $this->progressService->recordProgress($this->student, $this->lesson1, 850);

        $this->assertTrue($this->progressService->isVideoCompleted($this->student, $this->lesson1));

        // Lesson 1 can be opened ANY TIME
        $this->assertTrue($this->progressService->canAccessVideo($this->student, $this->lesson1));

        // Lesson 2 is now unlocked
        $this->assertTrue($this->progressService->canAccessVideo($this->student, $this->lesson2));

        // Level 2 and Lesson 3/4 are still locked
        $this->assertFalse($this->progressService->canAccessLevel($this->student, $this->level2));
        $this->assertFalse($this->progressService->canAccessVideo($this->student, $this->lesson3));
        $this->assertFalse($this->progressService->canAccessVideo($this->student, $this->lesson4));
    }

    /**
     * 3. After completing all lessons in Level 1:
     * - Level 1 is marked as completed.
     * - Both Lesson 1 and Lesson 2 can be reopened anytime.
     * - Level 2 unlocks.
     * - Lesson 3 (first lesson of Level 2) unlocks.
     * - Lesson 4 remains locked until Lesson 3 is completed.
     */
    public function test_completed_level_can_be_reopened_anytime_and_unlocks_next_level(): void
    {
        // Complete Lesson 1
        $this->progressService->recordProgress($this->student, $this->lesson1, 850);
        // Complete Lesson 2
        $this->progressService->recordProgress($this->student, $this->lesson2, 900);

        $this->assertTrue($this->progressService->isLevelCompleted($this->student, $this->level1));

        // Level 1 and all its lessons can be opened ANY TIME
        $this->assertTrue($this->progressService->canAccessLevel($this->student, $this->level1));
        $this->assertTrue($this->progressService->canAccessVideo($this->student, $this->lesson1));
        $this->assertTrue($this->progressService->canAccessVideo($this->student, $this->lesson2));

        // Level 2 is now unlocked!
        $this->assertTrue($this->progressService->canAccessLevel($this->student, $this->level2));

        // Lesson 3 is the current lesson and is unlocked
        $this->assertTrue($this->progressService->canAccessVideo($this->student, $this->lesson3));

        // Lesson 4 is locked until Lesson 3 finishes
        $this->assertFalse($this->progressService->canAccessVideo($this->student, $this->lesson4));
    }

    /**
     * 4. Once all lessons are completed:
     * - All lessons and levels can be opened and rewatched any time.
     */
    public function test_all_completed_lessons_and_levels_can_be_opened_anytime(): void
    {
        $this->progressService->recordProgress($this->student, $this->lesson1, 850);
        $this->progressService->recordProgress($this->student, $this->lesson2, 900);
        $this->progressService->recordProgress($this->student, $this->lesson3, 850);
        $this->progressService->recordProgress($this->student, $this->lesson4, 950);

        $this->assertTrue($this->progressService->isLevelCompleted($this->student, $this->level1));
        $this->assertTrue($this->progressService->isLevelCompleted($this->student, $this->level2));

        // Any lesson can be opened anytime
        $this->assertTrue($this->progressService->canAccessVideo($this->student, $this->lesson1));
        $this->assertTrue($this->progressService->canAccessVideo($this->student, $this->lesson2));
        $this->assertTrue($this->progressService->canAccessVideo($this->student, $this->lesson3));
        $this->assertTrue($this->progressService->canAccessVideo($this->student, $this->lesson4));
    }

    /**
     * 5. Attempting to record progress on a locked video throws AccessDeniedHttpException.
     */
    public function test_cannot_record_progress_on_locked_video(): void
    {
        $this->expectException(AccessDeniedHttpException::class);

        // Lesson 2 is locked before completing Lesson 1
        $this->progressService->recordProgress($this->student, $this->lesson2, 100);
    }

    /**
     * 6. Playback token API endpoint rejects locked videos with 403.
     */
    public function test_playback_token_api_rejects_locked_videos(): void
    {
        // Try getting playback token for locked lesson 2
        $response = $this->actingAs($this->student)->getJson(
            route('videos.playback-token', $this->lesson2)
        );

        $response->assertStatus(403);

        // Complete lesson 1
        $this->progressService->recordProgress($this->student, $this->lesson1, 850);

        // Now token for lesson 2 is accessible
        $tokenResponse = $this->actingAs($this->student)->getJson(
            route('videos.playback-token', $this->lesson2)
        );

        $tokenResponse->assertOk()->assertJson(['success' => true]);
    }

    /**
     * 7. Web lesson view shows Sequential Lock card for locked videos and player for accessible videos.
     */
    public function test_web_lesson_view_enforces_locking_and_shows_completed_status(): void
    {
        // Accessible lesson 1 view
        $response1 = $this->actingAs($this->student)->get(
            route('lessons.show', [$this->course->slug, $this->lesson1->slug])
        );
        $response1->assertOk();
        $response1->assertViewHas('canAccess', true);

        // Locked lesson 2 view
        $response2 = $this->actingAs($this->student)
            ->withSession(['locale' => 'ar'])
            ->get(route('lessons.show', [$this->course->slug, $this->lesson2->slug]));
        $response2->assertOk();
        $response2->assertViewHas('canAccess', false);
        $response2->assertSee(__('المحاضرة مقيدة بالتسلسل الإلزامي'));

        // Complete lesson 1
        $this->progressService->recordProgress($this->student, $this->lesson1, 850);

        // Revisit completed lesson 1 -> opens without lock card
        $response1Again = $this->actingAs($this->student)
            ->withSession(['locale' => 'ar'])
            ->get(route('lessons.show', [$this->course->slug, $this->lesson1->slug]));
        $response1Again->assertOk();
        $response1Again->assertViewHas('canAccess', true);
        $response1Again->assertSee(__('مكتمل'));
        $response1Again->assertSee(__('يمكنك مراجعته بأي وقت'));
    }

    /**
     * 8. Batch curriculum status map accurately tracks completed and accessible IDs.
     */
    public function test_curriculum_status_batch_map(): void
    {
        $status1 = $this->progressService->getCurriculumStatus($this->student, $this->course);
        $this->assertEmpty($status1['completed_video_ids']);
        $this->assertContains($this->lesson1->id, $status1['accessible_video_ids']);
        $this->assertNotContains($this->lesson2->id, $status1['accessible_video_ids']);
        $this->assertEquals($this->lesson1->id, $status1['current_video_id']);

        // Complete lesson 1
        $this->progressService->recordProgress($this->student, $this->lesson1, 850);

        $status2 = $this->progressService->getCurriculumStatus($this->student, $this->course);
        $this->assertContains($this->lesson1->id, $status2['completed_video_ids']);
        $this->assertContains($this->lesson1->id, $status2['accessible_video_ids']);
        $this->assertContains($this->lesson2->id, $status2['accessible_video_ids']);
        $this->assertEquals($this->lesson2->id, $status2['current_video_id']);
    }

    /**
     * 9. When sequential video lock is disabled globally, all lessons become accessible.
     */
    public function test_disabling_sequential_lock_opens_all_lessons(): void
    {
        Setting::updateOrCreate(
            ['key' => 'sequential_video_lock'],
            ['value_ar' => '0', 'value_en' => '0', 'group' => 'security']
        );

        $this->assertTrue($this->progressService->canAccessLevel($this->student, $this->level2));
        $this->assertTrue($this->progressService->canAccessVideo($this->student, $this->lesson4));
    }
}
