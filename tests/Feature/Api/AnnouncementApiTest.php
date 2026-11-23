<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AnnouncementApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_eligible_announcements_for_mobile_platform(): void
    {
        // 1. Mobile-targeted active announcement
        Announcement::create([
            'title_ar' => 'تحديث تطبيق الموبايل',
            'title_en' => 'Mobile App Update',
            'slug' => 'mobile-app-update',
            'content_type' => 'text',
            'display_mode' => 'popup',
            'target_platforms' => ['mobile', 'web'],
            'target_audience' => 'all',
            'is_active' => true,
            'priority' => 5,
        ]);

        // 2. Web-only announcement (should NOT be returned for mobile)
        Announcement::create([
            'title_ar' => 'إعلان للموقع فقط',
            'slug' => 'web-only-announcement',
            'content_type' => 'text',
            'display_mode' => 'banner',
            'target_platforms' => ['web'],
            'target_audience' => 'all',
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.v1.announcements.index', ['platform' => 'mobile']));

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'slug',
                    'title',
                    'title_ar',
                    'summary',
                    'content_type',
                    'display_mode',
                    'is_forced',
                    'target_platforms',
                    'action_button',
                ],
            ],
            'meta' => [
                'platform',
                'count',
                'has_forced',
            ],
        ]);

        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('تحديث تطبيق الموبايل', $response->json('data.0.title_ar'));
    }

    public function test_api_returns_forced_announcement_and_records_acknowledgment(): void
    {
        $user = User::factory()->create([
            'status' => 'approved',
        ]);
        Sanctum::actingAs($user);

        $announcement = Announcement::create([
            'title_ar' => 'تعليمات إلزامية بالمتدربين',
            'title_en' => 'Mandatory Student Instructions',
            'slug' => 'mandatory-instructions',
            'content_type' => 'video',
            'video_type' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=12345678901',
            'display_mode' => 'force',
            'frequency' => 'until_dismissed',
            'target_platforms' => ['mobile'],
            'target_audience' => 'students',
            'button_text_ar' => 'فهمت وموافق',
            'button_url' => '/ready',
            'is_active' => true,
            'priority' => 10,
        ]);

        // 1. Fetch announcements - must include the forced announcement
        $initialResponse = $this->getJson(route('api.v1.announcements.index', ['platform' => 'mobile']));
        $initialResponse->assertOk();
        $this->assertTrue($initialResponse->json('meta.has_forced'));
        $this->assertTrue($initialResponse->json('data.0.is_forced'));

        // 2. Acknowledge the announcement via API
        $interactResponse = $this->postJson(route('api.v1.announcements.interact', $announcement->id), [
            'action' => 'acknowledged',
            'platform' => 'mobile',
        ]);
        $interactResponse->assertOk();
        $interactResponse->assertJson(['success' => true]);

        // 3. Query announcements again for same user - forced announcement should no longer be returned!
        $afterResponse = $this->getJson(route('api.v1.announcements.index', ['platform' => 'mobile']));
        $afterResponse->assertOk();
        $this->assertCount(0, $afterResponse->json('data'));
        $this->assertFalse($afterResponse->json('meta.has_forced'));
    }

    public function test_public_bootstrap_endpoint_includes_mobile_announcements(): void
    {
        Announcement::create([
            'title_ar' => 'إعلان فوري في البوتستراب',
            'content_type' => 'text',
            'display_mode' => 'popup',
            'target_platforms' => ['mobile'],
            'target_audience' => 'all',
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.v1.public.bootstrap'));

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'data' => [
                'announcements',
                'has_forced_announcement',
            ],
        ]);
    }
}
