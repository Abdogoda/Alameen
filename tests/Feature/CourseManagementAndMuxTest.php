<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\Level;
use App\Models\Role;
use App\Models\Video;
use App\Services\Video\MuxVideoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $response->assertSee('عرض المنهج والمستويات (View Page)');
        $response->assertSee('تعديل بيانات الكورس (Edit Page)');
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

        $this->assertEquals('upload', $result['video_source']);
        $this->assertNotNull($result['video_url']);
        $this->assertNull($result['mux_playback_id']);
    }
}
