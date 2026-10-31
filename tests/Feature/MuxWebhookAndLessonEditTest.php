<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Level;
use App\Models\Role;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MuxWebhookAndLessonEditTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected Course $course;

    protected Level $level;

    protected Video $video;

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
            'title_ar' => 'دبلوم التخاطب الشامل',
            'slug' => 'speech-therapy-diploma',
            'price' => 1200,
            'access_duration_days' => 365,
            'is_published' => true,
        ]);

        $this->level = Level::create([
            'course_id' => $this->course->id,
            'title_ar' => 'المستوى الأول: المدخل النظري',
            'sort_order' => 1,
        ]);

        $this->video = Video::create([
            'course_id' => $this->course->id,
            'level_id' => $this->level->id,
            'title_ar' => 'الدرس الأول: تشريح جهاز النطق',
            'slug' => 'lesson-speech-anatomy',
            'video_source' => 'mux',
            'mux_upload_id' => 'upload_test_123',
            'mux_playback_id' => 'playback_old_456',
            'duration_seconds' => 1200,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
        ]);
    }

    public function test_admin_can_access_dedicated_edit_lesson_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.courses.videos.edit', [$this->course, $this->video]));

        $response->assertOk()
            ->assertViewIs('admin.courses.lessons.edit')
            ->assertSee($this->video->title_ar)
            ->assertSee('تعديل الدرس')
            ->assertSee($this->level->title_ar);
    }

    public function test_admin_can_update_lesson_from_dedicated_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.courses.videos.update', [$this->course, $this->video]), [
                'level_id' => $this->level->id,
                'title_ar' => 'الدرس الأول: تشريح وتدريب جهاز النطق المحدث',
                'duration_minutes' => 35,
                'min_completion_percentage' => 85,
                'is_free_preview' => '1',
                'video_source' => 'local',
                'video_url' => 'https://alameen.online/storage/videos/lessons/test.mp4',
                'task_title_ar' => 'مهمة عملية: تقييم سقف الحلق',
                'task_instructions_ar' => 'سجل مقطعاً صوتياً عملياً يوضح فحص سقف الحلق الرخو.',
            ]);

        $response->assertRedirect(route('admin.courses.show', $this->course));

        $this->assertDatabaseHas('videos', [
            'id' => $this->video->id,
            'title_ar' => 'الدرس الأول: تشريح وتدريب جهاز النطق المحدث',
            'duration_seconds' => 35 * 60,
            'min_completion_percentage' => 85,
            'is_free_preview' => true,
            'video_source' => 'local',
            'video_url' => 'https://alameen.online/storage/videos/lessons/test.mp4',
        ]);

        $this->assertDatabaseHas('clinical_tasks', [
            'video_id' => $this->video->id,
            'title_ar' => 'مهمة عملية: تقييم سقف الحلق',
        ]);
    }

    public function test_mux_webhook_updates_video_on_asset_ready(): void
    {
        $payload = [
            'type' => 'video.asset.ready',
            'id' => 'evt_12345',
            'created_at' => '2026-10-31T12:00:00Z',
            'data' => [
                'id' => 'asset_mux_999',
                'upload_id' => 'upload_test_123',
                'status' => 'ready',
                'duration' => 1845.6,
                'aspect_ratio' => '16:9',
                'playback_ids' => [
                    [
                        'id' => 'playback_new_777',
                        'policy' => 'public',
                    ],
                ],
            ],
        ];

        $response = $this->postJson(route('webhooks.mux'), $payload);

        $response->assertOk()
            ->assertJson(['status' => 'success', 'asset_id' => 'asset_mux_999']);

        $this->assertDatabaseHas('videos', [
            'id' => $this->video->id,
            'mux_asset_id' => 'asset_mux_999',
            'mux_playback_id' => 'playback_new_777',
            'video_url' => 'https://stream.mux.com/playback_new_777.m3u8',
            'video_source' => 'mux',
            'duration_seconds' => 1846,
        ]);
    }

    public function test_mux_webhook_links_upload_to_asset_on_upload_asset_created(): void
    {
        $payload = [
            'type' => 'video.upload.asset_created',
            'data' => [
                'id' => 'upload_test_123',
                'asset_id' => 'asset_linked_888',
            ],
        ];

        $response = $this->postJson(route('webhooks.mux'), $payload);

        $response->assertOk()
            ->assertJson(['status' => 'linked']);

        $this->assertDatabaseHas('videos', [
            'id' => $this->video->id,
            'mux_asset_id' => 'asset_linked_888',
        ]);
    }

    public function test_mux_webhook_verifies_signature_when_configured(): void
    {
        Config::set('video.drivers.mux.webhook_secret', 'test_secret_123');

        $payload = json_encode([
            'type' => 'video.asset.ready',
            'data' => [
                'id' => 'asset_mux_verified',
                'upload_id' => 'upload_test_123',
                'playback_ids' => [['id' => 'playback_ver_1']],
            ],
        ]);

        $timestamp = (string) time();
        $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", 'test_secret_123');
        $header = "t={$timestamp},v1={$signature}";

        // Valid signature -> OK
        $response = $this->call(
            'POST',
            route('webhooks.mux'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_MUX_SIGNATURE' => $header,
            ],
            $payload
        );

        $response->assertOk();

        // Invalid signature -> 401 Unauthorized
        $badResponse = $this->call(
            'POST',
            route('webhooks.mux'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_MUX_SIGNATURE' => "t={$timestamp},v1=invalid_signature_hash",
            ],
            $payload
        );

        $badResponse->assertStatus(401);
    }

    public function test_mux_webhook_handles_unhandled_events_gracefully(): void
    {
        $payload = [
            'type' => 'video.live_stream.created',
            'data' => ['id' => 'live_123'],
        ];

        $response = $this->postJson(route('webhooks.mux'), $payload);

        $response->assertOk()
            ->assertJson(['status' => 'ignored']);
    }

    public function test_admin_can_request_mux_direct_upload_url_when_configured(): void
    {
        Config::set('video.drivers.mux.token_id', 'test_token');
        Config::set('video.drivers.mux.token_secret', 'test_secret');

        Http::fake([
            'https://api.mux.com/video/v1/uploads' => Http::response([
                'data' => [
                    'id' => 'upload_mux_api_001',
                    'url' => 'https://storage.googleapis.com/mux-uploads/chunk_test',
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.courses.videos.mux.direct_upload', $this->course));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'upload_id' => 'upload_mux_api_001',
                'upload_url' => 'https://storage.googleapis.com/mux-uploads/chunk_test',
            ]);
    }
}
