<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use App\Notifications\UnifiedNotification;
use App\Services\Notification\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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
        $this->assertEquals('Welcome to '.config('app.name').'!', $notification->data['title']);
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

    public function test_web_routes_authorization(): void
    {
        $user = User::factory()->create();
        $admin = Admin::factory()->create();

        // User can access their notification inbox
        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk();

        // Non-admin cannot access admin notifications
        $this->actingAs($user)
            ->get(route('admin.notifications.index'))
            ->assertRedirect(route('admin.login'));

        // Admin can access admin notifications
        $this->actingAs($admin, 'admin')
            ->get(route('admin.notifications.index'))
            ->assertOk();
    }

    public function test_admin_notifications_log_displays_details_of_violating_user(): void
    {
        $admin = Admin::factory()->create(['permissions' => ['*']]);
        $student = User::factory()->create([
            'name' => 'Tariq Student Violator',
            'email' => 'tariq.violator@example.com',
            'phone' => '01099887766',
            'status' => 'suspended',
        ]);

        $notification = new UnifiedNotification(
            title: [
                'ar' => '🚨 رصد محاولة تسجيل شاشة — تم إيقاف الحساب فوراً',
                'en' => '🚨 Screen Recording Detected — Account Suspended',
            ],
            body: [
                'ar' => "تم رصد محاولة تسجيل شاشة للمحاضرة 'مخارج الحروف' بواسطة الطالب {$student->name} (هاتف: {$student->phone}). تم إيقاف الحساب فوراً وتسجيل الخروج.",
                'en' => "Screen recording attempt detected for lecture 'مخارج الحروف' by student {$student->name} (Phone: {$student->phone}). Account suspended immediately.",
            ],
            channels: ['database'],
            type: 'security',
            actionUrl: route('admin.students.show', $student->id),
            actionText: [
                'ar' => 'فحص ملف الطالب وبيانات الانتهاك',
                'en' => 'Review Student Profile & Violation',
            ],
            data: [
                'incident' => 'screen_recording_blocked',
                'student_id' => $student->id,
                'student_name' => $student->name,
                'student_phone' => $student->phone,
                'student_email' => $student->email,
                'ip_address' => '192.168.10.55',
                'user_agent' => 'Mozilla/5.0 TestBrowser',
                'video_title' => 'مخارج الحروف',
                'course_title' => 'دورة التخاطب',
                'incident_type' => 'screen_recording_attempt',
                'reported_at' => now()->toIso8601String(),
            ]
        );

        Notification::send($admin, $notification);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.notifications.index'));

        $response->assertOk();
        $response->assertSee('Tariq Student Violator');
        $response->assertSee('01099887766');
        $response->assertSee('tariq.violator@example.com');
        $response->assertSee(__('notifications.admin.violator'));
        $response->assertSee($admin->name);
    }
}
