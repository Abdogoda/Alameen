<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(array $permissions = ['*']): Admin
    {
        $role = Role::create([
            'name' => 'Admin Role',
            'slug' => 'admin_role_'.uniqid(),
            'permissions' => $permissions,
        ]);

        return Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin_'.uniqid().'@alameen.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
            'permissions' => [],
        ]);
    }

    public function test_admin_with_permission_can_access_announcements_index(): void
    {
        $admin = $this->createAdmin(['announcements.view']);

        Announcement::create([
            'title_ar' => 'فيديو تدريبي جديد',
            'title_en' => 'New Training Video',
            'slug' => 'new-training-video',
            'content_type' => 'video',
            'display_mode' => 'popup',
            'target_platforms' => ['web', 'mobile'],
            'target_audience' => 'all',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.announcements.index'));

        $response->assertOk();
        $response->assertSee('فيديو تدريبي جديد');
    }

    public function test_admin_can_create_announcement_with_video_and_forced_display_mode(): void
    {
        $admin = $this->createAdmin(['announcements.manage']);

        $payload = [
            'title_ar' => 'تنبيه هام وإرشادي سريري',
            'title_en' => 'Mandatory Clinical Guideline',
            'content_type' => 'mixed',
            'summary_ar' => 'يرجى مراجعة التعليمات قبل بدء التدريب',
            'body_ar' => 'تفاصيل التعليمات السريرية المعتمدة...',
            'video_type' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'target_platforms' => ['web', 'mobile'],
            'target_audience' => 'students',
            'display_mode' => 'force',
            'frequency' => 'until_dismissed',
            'button_text_ar' => 'موافق وفهمت',
            'button_url' => '/dashboard',
            'is_active' => 1,
            'priority' => 10,
        ];

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.announcements.store'), $payload);

        $response->assertRedirect(route('admin.announcements.index'));

        $this->assertDatabaseHas('announcements', [
            'title_ar' => 'تنبيه هام وإرشادي سريري',
            'display_mode' => 'force',
            'content_type' => 'mixed',
            'target_audience' => 'students',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_and_toggle_announcement_status(): void
    {
        $admin = $this->createAdmin(['announcements.manage']);

        $announcement = Announcement::create([
            'title_ar' => 'إعلان تجريبي',
            'content_type' => 'text',
            'display_mode' => 'banner',
            'target_platforms' => ['web'],
            'target_audience' => 'all',
            'is_active' => true,
        ]);

        // Toggle status
        $toggleResponse = $this->actingAs($admin, 'admin')
            ->post(route('admin.announcements.toggle', $announcement));

        $toggleResponse->assertOk();
        $toggleResponse->assertJson(['success' => true, 'is_active' => false]);
        $this->assertFalse($announcement->fresh()->is_active);

        // Update
        $updateResponse = $this->actingAs($admin, 'admin')
            ->put(route('admin.announcements.update', $announcement), [
                'title_ar' => 'عنوان محدث للإعلان',
                'content_type' => 'text',
                'display_mode' => 'popup',
                'frequency' => 'once',
                'target_platforms' => ['web', 'mobile'],
                'target_audience' => 'all',
                'is_active' => 1,
            ]);

        $updateResponse->assertRedirect(route('admin.announcements.index'));
        $this->assertEquals('عنوان محدث للإعلان', $announcement->fresh()->title_ar);
        $this->assertEquals('popup', $announcement->fresh()->display_mode);
    }

    public function test_web_user_can_record_interaction_for_announcement(): void
    {
        $user = User::factory()->create([
            'status' => 'approved',
        ]);

        $announcement = Announcement::create([
            'title_ar' => 'فيديو ترحيبي',
            'content_type' => 'video',
            'display_mode' => 'popup',
            'frequency' => 'once',
            'target_platforms' => ['web'],
            'target_audience' => 'all',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->post(route('announcements.interact', $announcement), [
                'action' => 'acknowledged',
                'platform' => 'web',
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true, 'action' => 'acknowledged']);

        $this->assertDatabaseHas('announcement_interactions', [
            'announcement_id' => $announcement->id,
            'user_id' => $user->id,
            'action' => 'acknowledged',
        ]);
    }
}
