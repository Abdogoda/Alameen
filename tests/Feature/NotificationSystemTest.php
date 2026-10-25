<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\UnifiedNotification;
use App\Services\Notification\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_service_dispatches_event_and_calls_unified_notification(): void
    {
        $user = User::factory()->create(['locale' => 'en']);
        $service = app(NotificationService::class);

        $service->sendToUser(
            user: $user,
            title: 'notifications.welcome_title',
            body: 'notifications.welcome_body',
            channels: ['database'],
            type: 'success',
            actionUrl: 'https://example.com/welcome',
            actionText: 'Get Started',
        );

        $this->assertCount(1, $user->fresh()->notifications);

        $notification = $user->fresh()->notifications->first();
        $this->assertNotNull($notification);
        $this->assertEquals('Welcome to Laravel!', $notification->data['title']);
        $this->assertEquals('success', $notification->data['type']);
        $this->assertEquals('https://example.com/welcome', $notification->data['action_url']);
        $this->assertEquals('Get Started', $notification->data['action_text']);
    }

    public function test_notification_sent_in_user_preferred_language_arabic(): void
    {
        $arUser = User::factory()->create(['locale' => 'ar']);
        $service = app(NotificationService::class);

        $service->sendToUser(
            user: $arUser,
            title: [
                'en' => 'Security Notice',
                'ar' => 'إشعار أمني',
            ],
            body: [
                'en' => 'Your password was changed.',
                'ar' => 'تم تغيير كلمة المرور الخاصة بك.',
            ],
            channels: ['database', 'mail'],
            type: 'security',
        );

        $notification = $arUser->fresh()->notifications->first();
        $this->assertNotNull($notification);
        $this->assertEquals('إشعار أمني', $notification->data['title']);
        $this->assertEquals('تم تغيير كلمة المرور الخاصة بك.', $notification->data['body']);
        $this->assertEquals('ar', $notification->data['locale']);

        // Verify mail representation resolves to Arabic
        $unified = new UnifiedNotification(
            title: ['en' => 'Security Notice', 'ar' => 'إشعار أمني'],
            body: ['en' => 'Your password was changed.', 'ar' => 'تم تغيير كلمة المرور الخاصة بك.'],
            channels: ['mail'],
            type: 'security',
        );

        $mailMessage = $unified->toMail($arUser);
        $this->assertEquals('إشعار أمني', $mailMessage->subject);
    }

    public function test_notification_sent_in_user_preferred_language_english(): void
    {
        $enUser = User::factory()->create(['locale' => 'en']);
        $service = app(NotificationService::class);

        $service->sendToUser(
            user: $enUser,
            title: [
                'en' => 'Security Notice',
                'ar' => 'إشعار أمني',
            ],
            body: [
                'en' => 'Your password was changed.',
                'ar' => 'تم تغيير كلمة المرور الخاصة بك.',
            ],
            channels: ['database'],
            type: 'security',
        );

        $notification = $enUser->fresh()->notifications->first();
        $this->assertNotNull($notification);
        $this->assertEquals('Security Notice', $notification->data['title']);
        $this->assertEquals('Your password was changed.', $notification->data['body']);
        $this->assertEquals('en', $notification->data['locale']);
    }

    public function test_admin_can_broadcast_notification_to_all_users_in_their_preferred_languages(): void
    {
        $enUser = User::factory()->create(['locale' => 'en']);
        $arUser = User::factory()->create(['locale' => 'ar']);
        $service = app(NotificationService::class);

        $service->sendToAll(
            title: ['en' => 'System Update', 'ar' => 'تحديث النظام'],
            body: ['en' => 'New features added.', 'ar' => 'تمت إضافة ميزات جديدة.'],
            channels: ['database'],
            type: 'system',
        );

        $enNotif = $enUser->fresh()->notifications->first();
        $arNotif = $arUser->fresh()->notifications->first();

        $this->assertNotNull($enNotif);
        $this->assertNotNull($arNotif);

        $this->assertEquals('System Update', $enNotif->data['title']);
        $this->assertEquals('تحديث النظام', $arNotif->data['title']);
    }

    public function test_user_can_list_notifications_via_api(): void
    {
        $user = User::factory()->create();
        $service = app(NotificationService::class);

        $service->sendToUser($user, 'Test Title', 'Test Body', ['database']);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/notifications');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'unread_count' => 1,
                ],
            ]);

        $this->assertCount(1, $response->json('data.notifications'));
    }

    public function test_user_can_mark_notification_as_read_via_api(): void
    {
        $user = User::factory()->create();
        $service = app(NotificationService::class);

        $service->sendToUser($user, 'Test Title', 'Test Body', ['database']);
        $notification = $user->fresh()->notifications->first();
        $this->assertNotNull($notification);

        Sanctum::actingAs($user);

        $response = $this->patchJson("/api/v1/notifications/{$notification->id}/read");

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'unread_count' => 0,
                ],
            ]);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_mark_all_notifications_as_read_via_api(): void
    {
        $user = User::factory()->create();
        $service = app(NotificationService::class);

        $service->sendToUser($user, 'Title 1', 'Body 1', ['database']);
        $service->sendToUser($user, 'Title 2', 'Body 2', ['database']);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/notifications/read-all');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'marked_count' => 2,
                    'unread_count' => 0,
                ],
            ]);

        $this->assertEquals(0, $service->getUnreadCount($user));
    }

    public function test_user_can_delete_notification_via_api(): void
    {
        $user = User::factory()->create();
        $service = app(NotificationService::class);

        $service->sendToUser($user, 'Title', 'Body', ['database']);
        $notification = $user->fresh()->notifications->first();
        $this->assertNotNull($notification);

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/v1/notifications/{$notification->id}");

        $response->assertOk();
        $this->assertCount(0, $user->fresh()->notifications);
    }

    public function test_non_admin_cannot_access_admin_notification_api(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/admin/notifications')->assertForbidden();
        $this->postJson('/api/v1/admin/notifications/send', [
            'target' => 'all',
            'title' => 'Test',
            'body' => 'Body',
        ])->assertForbidden();
    }

    public function test_admin_can_send_notification_via_api(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['locale' => 'ar']);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/v1/admin/notifications/send', [
            'target' => 'user',
            'user_id' => $user->id,
            'channels' => ['database'],
            'type' => 'warning',
            'title' => ['en' => 'Warning En', 'ar' => 'تحذير'],
            'body' => ['en' => 'Body En', 'ar' => 'نص التحذير'],
            'action_url' => 'https://example.com/check',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $notif = $user->fresh()->notifications->first();
        $this->assertNotNull($notif);
        $this->assertEquals('تحذير', $notif->data['title']);
        $this->assertEquals('نص التحذير', $notif->data['body']);
        $this->assertEquals('warning', $notif->data['type']);
    }

    public function test_admin_can_view_stats_and_delete_notifications_via_api(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $service = app(NotificationService::class);

        $service->sendToUser($user, 'Notice', 'Body', ['database'], 'info');
        $notif = $user->fresh()->notifications->first();
        $this->assertNotNull($notif);

        Sanctum::actingAs($admin);

        $statsResponse = $this->getJson('/api/v1/admin/notifications/stats');
        $statsResponse->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'total' => 1,
                    'unread' => 1,
                ],
            ]);

        $deleteResponse = $this->deleteJson("/api/v1/admin/notifications/{$notif->id}");
        $deleteResponse->assertOk();

        $this->assertCount(0, $user->fresh()->notifications);
    }

    public function test_web_routes_authorization(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $admin = User::factory()->admin()->create();

        // User can access their notification inbox
        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk();

        // Non-admin cannot access admin notifications
        $this->actingAs($user)
            ->get(route('admin.notifications.index'))
            ->assertForbidden();

        // Admin can access admin notifications
        $this->actingAs($admin)
            ->get(route('admin.notifications.index'))
            ->assertOk();
    }
}
