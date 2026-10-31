<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\Level;
use App\Models\Role;
use App\Models\Video;
use App\Services\Video\Contracts\VideoDriverInterface;
use App\Services\Video\MuxVideoService;
use App\Services\Video\VideoServiceManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class CourseManagementAndMuxTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

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
            'title_ar' => 'دبلوم التخاطب واللغة المتقدم',
            'title_en' => 'Advanced Speech Therapy Diploma',
            'slug' => 'advanced-speech-therapy',
            'description_ar' => 'برنامج تدريبي شامل مع مستويات ديناميكية',
            'description_en' => 'Comprehensive training program with dynamic levels',
            'price' => 1200.00,
            'original_price' => 1800.00,
            'access_duration_days' => 365,
            'thumbnail' => '/assets/images/course-topic-basics.png',
            'promo_video_source' => 'mux',
            'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
            'promo_video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
            'is_published' => true,
            'sort_order' => 1,
        ]);
    }

    /**
     * 1. Test Courses Index Catalogue renders properly.
     */
    public function test_admin_can_view_courses_index_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.courses.index'));

        $response->assertOk();
        $response->assertSee('دليل وإدارة الكورسات التدريبية');
        $response->assertSee($this->course->title_ar);
        $response->assertSee('عرض المنهج والمستويات');
        $response->assertSee('تعديل بيانات الكورس');
        $response->assertSee('إضافة كورس جديد');
    }

    /**
     * 2. Test specific Course View Page (Dynamic Levels & Lessons Architecture).
     */
    public function test_admin_can_view_course_view_page_with_dynamic_levels(): void
    {
        $level = Level::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المستوى الأول: مدخل إلى علوم التخاطب',
            'title_en' => 'Level 1: Speech Science Intro',
            'description_ar' => 'أساسيات عملية النطق والتشريح',
            'sort_order' => 1,
        ]);

        $video = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $level->id,
            'title_ar' => 'الدرس ١: تشريح الجهاز النطقي',
            'title_en' => 'Lesson 1: Anatomy of Speech',
            'slug' => 'lesson-1-anatomy',
            'duration_seconds' => 1200,
            'video_source' => 'mux',
            'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
            'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
            'sort_order' => 1,
        ]);

        ClinicalTask::create([
            'course_id' => $this->course->id,
            'video_id' => $video->id,
            'title_ar' => 'مهمة مرتبطة: مخطط أركان التواصل',
            'instructions_ar' => 'تسجيل مقطع صوتي يوضح مخارج الأصوات',
            'max_score' => 100,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.courses.show', $this->course));

        $response->assertOk();
        $response->assertSee('إدارة محتوى الكورس والمستويات');
        $response->assertSee($this->course->title_ar);
        $response->assertSee('المستوى الأول: مدخل إلى علوم التخاطب');
        $response->assertSee('الدرس ١: تشريح الجهاز النطقي');
        $response->assertSee('مخطط أركان التواصل');
        $response->assertSee('MUX Video');
        $response->assertSee('تشغيل ↗');
    }

    /**
     * 3. Test Course Edit Page renders with MUX options and live player preview.
     */
    public function test_admin_can_view_course_edit_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.courses.edit', $this->course));

        $response->assertOk();
        $response->assertSee($this->course->title_ar);
        $response->assertSee('الفيديو التعريفي للكورس (MUX Platform / Link)');
        $response->assertSee('رفع ملف فيديو إلى MUX');
        $response->assertSee('رابط خارجي أو Mux ID');
        $response->assertSee('معاينة الفيديو مباشرة (Live Player Preview)');
    }

    /**
     * 4. Test Course update details and pricing.
     */
    public function test_admin_can_update_course_details_and_pricing(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.courses.update', $this->course), [
                'title_ar' => 'دبلوم التخاطب المحدث',
                'title_en' => 'Updated Speech Therapy Diploma',
                'slug' => 'updated-speech-diploma',
                'description_ar' => 'وصف معدل',
                'price' => 1500.00,
                'original_price' => 2000.00,
                'access_duration_days' => 180,
                'is_published' => true,
                'promo_video_url' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
            ]);

        $response->assertRedirect();
        $this->course->refresh();

        $this->assertEquals('دبلوم التخاطب المحدث', $this->course->title_ar);
        $this->assertEquals(1500.00, (float) $this->course->price);
        $this->assertEquals(2000.00, (float) $this->course->original_price);
        $this->assertEquals(180, $this->course->access_duration_days);
        $this->assertEquals('mux', $this->course->promo_video_source);
        $this->assertEquals('DS00Spx1CV902MCtP7ToneAwBpR00uoJq', $this->course->mux_playback_id);
    }

    /**
     * 5. Test Quick Price update modal endpoint.
     */
    public function test_admin_can_quick_update_price(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.price.update', $this->course), [
                'currentPrice' => 999.00,
                'originalPrice' => 1499.00,
            ]);

        $response->assertRedirect();
        $this->course->refresh();

        $this->assertEquals(999.00, (float) $this->course->price);
        $this->assertEquals(1499.00, (float) $this->course->original_price);
    }

    /**
     * 6. Test Dynamic Levels CRUD operations.
     */
    public function test_admin_can_manage_dynamic_levels(): void
    {
        // Create Level
        $storeResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.levels.store', $this->course), [
                'title_ar' => 'المستوى الجديد التجريبي',
                'title_en' => 'Experimental Level',
                'description_ar' => 'أهداف المستوى',
                'sort_order' => 1,
            ]);

        $storeResponse->assertRedirect();
        $this->assertDatabaseHas('levels', [
            'course_id' => $this->course->id,
            'title_ar' => 'المستوى الجديد التجريبي',
        ]);

        $level = Level::where('title_ar', 'المستوى الجديد التجريبي')->firstOrFail();

        // Update Level
        $updateResponse = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.courses.levels.update', [$this->course, $level]), [
                'title_ar' => 'المستوى المعدل',
                'title_en' => 'Updated Level',
                'description_ar' => 'وصف معدل',
                'sort_order' => 2,
            ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('levels', [
            'id' => $level->id,
            'title_ar' => 'المستوى المعدل',
            'sort_order' => 2,
        ]);

        // Delete Level
        $deleteResponse = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.courses.levels.destroy', [$this->course, $level]));

        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('levels', ['id' => $level->id]);
    }

    /**
     * 7. Test Video and Lesson creation with MUX / Link and Clinical Task.
     */
    public function test_admin_can_add_lesson_with_mux_and_task(): void
    {
        $level = Level::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المستوى الأساسي',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.videos.store', $this->course), [
                'level_id' => $level->id,
                'title_ar' => 'محاضرة مهارات التمييز السمعي',
                'title_en' => 'Auditory Discrimination Lecture',
                'duration_minutes' => 25,
                'video_url' => 'https://stream.mux.com/sampleMuxPlaybackId123.m3u8',
                'task_title_ar' => 'تطبيق سريري على ٦ بطاقات مصورة',
                'task_instructions_ar' => 'قم بتسجيل النطق السريري وملاحظة الأطفال',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('videos', [
            'course_id' => $this->course->id,
            'level_id' => $level->id,
            'title_ar' => 'محاضرة مهارات التمييز السمعي',
            'video_source' => 'mux',
            'mux_playback_id' => 'sampleMuxPlaybackId123',
            'duration_seconds' => 1500, // 25 * 60
        ]);

        $video = Video::where('title_ar', 'محاضرة مهارات التمييز السمعي')->firstOrFail();

        $this->assertDatabaseHas('clinical_tasks', [
            'course_id' => $this->course->id,
            'video_id' => $video->id,
            'title_ar' => 'تطبيق سريري على ٦ بطاقات مصورة',
        ]);
    }

    /**
     * 8. Test MuxVideoService parsing various video sources.
     */
    public function test_mux_video_service_parses_various_video_sources(): void
    {
        $muxService = app(MuxVideoService::class);

        // MUX Stream URL
        $parsed1 = $muxService->parseVideoInput('https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8');
        $this->assertEquals('mux', $parsed1['video_source']);
        $this->assertEquals('DS00Spx1CV902MCtP7ToneAwBpR00uoJq', $parsed1['mux_playback_id']);

        // Raw MUX Playback ID
        $parsed2 = $muxService->parseVideoInput('DS00Spx1CV902MCtP7ToneAwBpR00uoJq');
        $this->assertEquals('mux', $parsed2['video_source']);
        $this->assertEquals('DS00Spx1CV902MCtP7ToneAwBpR00uoJq', $parsed2['mux_playback_id']);

        // YouTube URL
        $parsed3 = $muxService->parseVideoInput('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $this->assertEquals('youtube', $parsed3['video_source']);
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ', $parsed3['video_url']);

        // Vimeo URL
        $parsed4 = $muxService->parseVideoInput('https://vimeo.com/76979871');
        $this->assertEquals('vimeo', $parsed4['video_source']);
        $this->assertEquals('https://player.vimeo.com/video/76979871', $parsed4['video_url']);

        // Direct HLS
        $parsed5 = $muxService->parseVideoInput('https://example.com/playlist.m3u8');
        $this->assertEquals('hls', $parsed5['video_source']);

        // Generic Link
        $parsed6 = $muxService->parseVideoInput('https://example.com/video.mp4');
        $this->assertEquals('link', $parsed6['video_source']);
    }

    /**
     * 9. Test file upload fallback when MUX credentials are not configured.
     */
    public function test_video_file_upload_stores_locally_as_fallback(): void
    {
        Storage::fake('public');

        $muxService = app(MuxVideoService::class);
        $fakeVideo = UploadedFile::fake()->create('lecture.mp4', 1024, 'video/mp4');

        $result = $muxService->handleVideoUpload($fakeVideo);

        $this->assertEquals('local', $result['video_source']);
        $this->assertNotNull($result['video_url']);
        $this->assertNull($result['mux_playback_id']);
    }

    /**
     * 10. Test specific video settings update (completion % and free preview).
     */
    public function test_admin_can_update_specific_video_settings(): void
    {
        $level = Level::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المستوى الأول',
            'sort_order' => 1,
        ]);

        $video = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $level->id,
            'title_ar' => 'درس سريري تمهيدي',
            'slug' => 'intro-clinical-lesson',
            'duration_seconds' => 1800,
            'video_source' => 'link',
            'video_url' => 'https://example.com/stream.m3u8',
            'min_completion_percentage' => 80,
            'is_free_preview' => false,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.courses.videos.update', [$this->course, $video]), [
                'title_ar' => 'درس سريري تمهيدي محدث',
                'duration_minutes' => 30,
                'min_completion_percentage' => 90,
                'is_free_preview' => 1,
                'level_id' => $level->id,
                'video_url' => 'https://example.com/stream.m3u8',
            ]);

        $response->assertRedirect();
        $video->refresh();

        $this->assertEquals(90, $video->min_completion_percentage);
        $this->assertTrue($video->is_free_preview);
    }

    /**
     * 11. Test course-wide video settings application.
     */
    public function test_admin_can_apply_course_wide_video_settings(): void
    {
        $level = Level::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المستوى الشامل',
            'sort_order' => 1,
        ]);

        $v1 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $level->id,
            'title_ar' => 'فيديو ١',
            'slug' => 'video-1-course',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'is_free_preview' => false,
            'sort_order' => 1,
        ]);

        $v2 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $level->id,
            'title_ar' => 'فيديو ٢',
            'slug' => 'video-2-course',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'is_free_preview' => true,
            'sort_order' => 2,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.videos.settings.apply', $this->course), [
                'scope' => 'course',
                'min_completion_percentage' => 85,
                'free_preview_policy' => 'first_only',
            ]);

        $response->assertRedirect();
        $v1->refresh();
        $v2->refresh();

        $this->assertEquals(85, $v1->min_completion_percentage);
        $this->assertEquals(85, $v2->min_completion_percentage);
        $this->assertTrue($v1->is_free_preview);
        $this->assertFalse($v2->is_free_preview);
    }

    /**
     * 12. Test level-wide video settings application.
     */
    public function test_admin_can_apply_level_wide_video_settings(): void
    {
        $lvl1 = Level::create(['course_id' => $this->course->id, 'title_ar' => 'مستوى ١', 'sort_order' => 1]);
        $lvl2 = Level::create(['course_id' => $this->course->id, 'title_ar' => 'مستوى ٢', 'sort_order' => 2]);

        $v1 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $lvl1->id,
            'title_ar' => 'فيديو مستوى ١',
            'slug' => 'video-lvl1',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'is_free_preview' => false,
            'sort_order' => 1,
        ]);

        $v2 = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $lvl2->id,
            'title_ar' => 'فيديو مستوى ٢',
            'slug' => 'video-lvl2',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'is_free_preview' => false,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.videos.settings.apply', $this->course), [
                'scope' => 'level',
                'level_id' => $lvl1->id,
                'min_completion_percentage' => 95,
                'free_preview_policy' => 'enable_all',
            ]);

        $response->assertRedirect();
        $v1->refresh();
        $v2->refresh();

        // Level 1 updated
        $this->assertEquals(95, $v1->min_completion_percentage);
        $this->assertTrue($v1->is_free_preview);

        // Level 2 intact
        $this->assertEquals(80, $v2->min_completion_percentage);
        $this->assertFalse($v2->is_free_preview);
    }

    /**
     * 13. Test bulk selection video settings, move, and delete.
     */
    public function test_admin_can_bulk_update_move_and_delete_videos(): void
    {
        $lvl1 = Level::create(['course_id' => $this->course->id, 'title_ar' => 'مستوى أ', 'sort_order' => 1]);
        $lvl2 = Level::create(['course_id' => $this->course->id, 'title_ar' => 'مستوى ب', 'sort_order' => 2]);

        $v1 = Video::create(['course_id' => $this->course->id, 'level_id' => $lvl1->id, 'title_ar' => 'درس أ١', 'slug' => 'lesson-a1', 'duration_seconds' => 600, 'min_completion_percentage' => 80, 'sort_order' => 1]);
        $v2 = Video::create(['course_id' => $this->course->id, 'level_id' => $lvl1->id, 'title_ar' => 'درس أ٢', 'slug' => 'lesson-a2', 'duration_seconds' => 600, 'min_completion_percentage' => 80, 'sort_order' => 2]);
        $v3 = Video::create(['course_id' => $this->course->id, 'level_id' => $lvl1->id, 'title_ar' => 'درس أ٣', 'slug' => 'lesson-a3', 'duration_seconds' => 600, 'min_completion_percentage' => 80, 'sort_order' => 3]);

        // Bulk update settings for v1 and v2
        $response1 = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.videos.settings.apply', $this->course), [
                'scope' => 'bulk',
                'video_ids' => [$v1->id, $v2->id],
                'min_completion_percentage' => 70,
                'free_preview_policy' => 'enable_all',
            ]);

        $response1->assertRedirect();
        $v1->refresh();
        $v2->refresh();
        $v3->refresh();

        $this->assertEquals(70, $v1->min_completion_percentage);
        $this->assertEquals(70, $v2->min_completion_percentage);
        $this->assertEquals(80, $v3->min_completion_percentage);
        $this->assertTrue($v1->is_free_preview);
        $this->assertTrue($v2->is_free_preview);
        $this->assertFalse($v3->is_free_preview);

        // Bulk move v1 to lvl2
        $response2 = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.videos.settings.apply', $this->course), [
                'scope' => 'bulk',
                'bulk_action' => 'move_level',
                'video_ids' => [$v1->id],
                'target_level_id' => $lvl2->id,
            ]);

        $response2->assertRedirect();
        $v1->refresh();
        $this->assertEquals($lvl2->id, $v1->level_id);

        // Bulk delete v2
        $response3 = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.courses.videos.settings.apply', $this->course), [
                'scope' => 'bulk',
                'bulk_action' => 'delete',
                'video_ids' => [$v2->id],
            ]);

        $response3->assertRedirect();
        $this->assertDatabaseMissing('videos', ['id' => $v2->id]);
    }

    /**
     * 14. Test admin can access dedicated separate page to add a new lesson.
     */
    public function test_admin_can_view_separate_create_lesson_page(): void
    {
        $level = Level::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المستوى الأول',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.courses.videos.create', [
                'course' => $this->course,
                'level_id' => $level->id,
            ]));

        $response->assertOk();
        $response->assertSee($this->course->title_ar);
        $response->assertSee('المستوى الأول');
        $response->assertSee('رفع فيديو بالتجزئة المباشرة');
        $response->assertSee('حفظ ونشر الدرس في المنهج');
    }

    /**
     * 15. Test chunked video upload assembling, progress and processing fallback.
     */
    public function test_admin_can_upload_lesson_video_in_chunks_and_process(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $uploadId = 'test_upload_'.Str::random(12);
        $part1 = UploadedFile::fake()->create('chunk_0.part', 500, 'application/octet-stream');
        $part2 = UploadedFile::fake()->create('chunk_1.part', 500, 'application/octet-stream');

        // Chunk 1 of 2
        $response1 = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.courses.videos.chunk.upload', $this->course), [
                'upload_id' => $uploadId,
                'chunk_index' => 0,
                'total_chunks' => 2,
                'original_filename' => 'pediatric_session.mp4',
                'file_chunk' => $part1,
            ]);

        $response1->assertOk();
        $response1->assertJson([
            'status' => 'chunk_received',
            'chunk_index' => 0,
            'total_chunks' => 2,
        ]);

        // Chunk 2 of 2 (completion)
        $response2 = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.courses.videos.chunk.upload', $this->course), [
                'upload_id' => $uploadId,
                'chunk_index' => 1,
                'total_chunks' => 2,
                'original_filename' => 'pediatric_session.mp4',
                'file_chunk' => $part2,
            ]);

        $response2->assertOk();
        $response2->assertJson([
            'status' => 'completed',
            'video_source' => 'local',
        ]);
        $this->assertNotEmpty($response2->json('video_url'));
    }

    /**
     * 16. Test VideoServiceManager driver abstraction and local fallback.
     */
    public function test_video_service_manager_driver_abstraction_and_local_fallback(): void
    {
        Storage::fake('public');

        /** @var VideoServiceManager $manager */
        $manager = app(VideoServiceManager::class);

        $this->assertInstanceOf(VideoDriverInterface::class, $manager->driver('mux'));
        $this->assertInstanceOf(VideoDriverInterface::class, $manager->driver('local'));

        // When MUX is not configured, active driver falls back automatically to local
        $activeDriver = $manager->getActiveDriver();
        $this->assertEquals('local', $activeDriver->name());

        $fakeFile = UploadedFile::fake()->create('speech_lesson.mp4', 1024, 'video/mp4');
        $uploadResult = $manager->handleVideoUpload($fakeFile);

        $this->assertEquals('local', $uploadResult['video_source']);
        $this->assertEquals('local', $uploadResult['driver_used']);
        $this->assertNotNull($uploadResult['video_url']);
    }

    /**
     * 17. Test storage fallback route serves files and blocks path traversal.
     */
    public function test_storage_fallback_route_serves_files_and_blocks_traversal(): void
    {
        // Place a real file in storage/app/public
        $publicDir = storage_path('app/public');
        if (! file_exists($publicDir)) {
            mkdir($publicDir, 0755, true);
        }

        $testFile = $publicDir.'/test_playback.mp4';
        file_put_contents($testFile, 'fake mp4 content stream');

        try {
            // Accessible via fallback route
            $response = $this->get('/storage/test_playback.mp4');
            $response->assertOk();

            // Missing file yields 404
            $this->get('/storage/non_existent_video.mp4')->assertNotFound();

            // Directory traversal yields 404
            $this->get('/storage/../../.env')->assertNotFound();
        } finally {
            @unlink($testFile);
        }
    }

    /**
     * 18. Test Video effective playback URL normalizes local storage URLs.
     */
    public function test_video_effective_playback_url_normalizes_local_storage_urls(): void
    {
        $video = Video::create([
            'course_id' => $this->course->id,
            'title_ar' => 'درس تجريبي محلي',
            'slug' => 'local-test-lesson',
            'video_source' => 'local',
            'video_url' => 'https://alameen.test/storage/videos/lessons/lesson_123.mp4',
            'duration_seconds' => 600,
        ]);

        $this->assertEquals('/storage/videos/lessons/lesson_123.mp4', $video->effective_playback_url);
    }
}
