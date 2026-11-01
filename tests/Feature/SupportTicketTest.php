<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_support_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('support.index'));

        $response->assertOk();
        $response->assertSeeText(__('مركز الدعم والمحادثات'));
    }

    public function test_student_can_create_support_ticket(): void
    {
        $user = User::factory()->create();
        $course = Course::create([
            'title_ar' => 'أساسيات التخاطب',
            'title_en' => 'Speech Basics',
            'slug' => 'speech-basics',
            'price' => 100,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('support.store'), [
            'subject' => 'مشكلة في تشغيل الدرس الثالث',
            'category' => 'technical',
            'priority' => 'high',
            'course_id' => $course->id,
            'message' => 'مرحباً، الفيديو لا يعمل وتظهر رسالة خطأ عند الوصول للدقيقة الخامسة.',
        ]);

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $user->id,
            'subject' => 'مشكلة في تشغيل الدرس الثالث',
            'category' => 'technical',
            'priority' => 'high',
            'status' => 'open',
        ]);

        $ticket = SupportTicket::first();
        $this->assertNotNull($ticket);

        $response->assertRedirect(route('support.show', $ticket->id));

        // Ensure first message was created
        $this->assertDatabaseHas('support_messages', [
            'support_ticket_id' => $ticket->id,
            'sender_type' => User::class,
            'sender_id' => $user->id,
            'type' => 'message',
        ]);
    }

    public function test_student_cannot_view_another_students_ticket(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $ticket = SupportTicket::create([
            'user_id' => $user1->id,
            'subject' => 'تذكرة سرية',
            'category' => 'general',
            'priority' => 'medium',
            'status' => 'open',
        ]);

        $response = $this->actingAs($user2)->get(route('support.show', $ticket->id));
        $response->assertForbidden();
    }

    public function test_admin_can_reply_and_post_internal_notes(): void
    {
        $user = User::factory()->create();
        $admin = Admin::factory()->create();

        $ticket = SupportTicket::create([
            'user_id' => $user->id,
            'subject' => 'استفسار عن التقييم',
            'category' => 'academic',
            'priority' => 'medium',
            'status' => 'open',
        ]);

        // 1. Admin sends public reply
        $replyResponse = $this->actingAs($admin, 'admin')->post(route('admin.support.reply', $ticket->id), [
            'message' => 'أهلاً بك، تم فحص تسليمك بنجاح.',
            'is_internal_note' => 0,
        ]);

        $replyResponse->assertSessionHas('success');
        $this->assertDatabaseHas('support_messages', [
            'support_ticket_id' => $ticket->id,
            'sender_type' => Admin::class,
            'sender_id' => $admin->id,
            'type' => 'message',
            'message' => 'أهلاً بك، تم فحص تسليمك بنجاح.',
        ]);

        // Ticket status should be waiting_student
        $this->assertEquals('waiting_student', $ticket->fresh()->status);

        // 2. Admin adds internal note
        $noteResponse = $this->actingAs($admin, 'admin')->post(route('admin.support.reply', $ticket->id), [
            'message' => 'ملاحظة سرية للمشرفين: تم الاتصال بالطالب هاتفياً.',
            'is_internal_note' => 1,
        ]);

        $noteResponse->assertSessionHas('success');
        $this->assertDatabaseHas('support_messages', [
            'support_ticket_id' => $ticket->id,
            'sender_type' => Admin::class,
            'sender_id' => $admin->id,
            'type' => 'internal_note',
        ]);

        // Student polls messages -> should see admin reply, but NEVER see internal note
        $pollResponse = $this->actingAs($user)->get(route('support.poll', $ticket->id));
        $pollResponse->assertOk();
        $pollData = $pollResponse->json();

        $messagesText = collect($pollData['messages'])->pluck('message')->implode(' ');
        $this->assertStringContainsString('أهلاً بك، تم فحص تسليمك بنجاح.', $messagesText);
        $this->assertStringNotContainsString('ملاحظة سرية للمشرفين', $messagesText);
    }

    public function test_admin_can_update_status_and_priority_and_assign(): void
    {
        $user = User::factory()->create();
        $admin1 = Admin::factory()->create();
        $admin2 = Admin::factory()->create();

        $ticket = SupportTicket::create([
            'user_id' => $user->id,
            'subject' => 'مشكلة تقنية',
            'category' => 'technical',
            'priority' => 'low',
            'status' => 'open',
        ]);

        // Update status
        $this->actingAs($admin1, 'admin')->post(route('admin.support.status.update', $ticket->id), [
            'status' => 'in_progress',
        ])->assertSessionHas('success');
        $this->assertEquals('in_progress', $ticket->fresh()->status);

        // Update priority
        $this->actingAs($admin1, 'admin')->post(route('admin.support.priority.update', $ticket->id), [
            'priority' => 'urgent',
        ])->assertSessionHas('success');
        $this->assertEquals('urgent', $ticket->fresh()->priority);

        // Assign admin
        $this->actingAs($admin1, 'admin')->post(route('admin.support.assign', $ticket->id), [
            'admin_id' => $admin2->id,
        ])->assertSessionHas('success');
        $this->assertEquals($admin2->id, $ticket->fresh()->assigned_admin_id);
    }

    public function test_student_and_admin_can_close_and_reopen_ticket(): void
    {
        $user = User::factory()->create();

        $ticket = SupportTicket::create([
            'user_id' => $user->id,
            'subject' => 'استفسار مغلق',
            'category' => 'general',
            'priority' => 'medium',
            'status' => 'open',
        ]);

        // Student closes ticket
        $this->actingAs($user)->post(route('support.close', $ticket->id), [
            'reason' => 'تم الحل شكراً',
        ])->assertSessionHas('success');

        $this->assertEquals('closed', $ticket->fresh()->status);
        $this->assertTrue($ticket->fresh()->isClosed());

        // Student reopens ticket
        $this->actingAs($user)->post(route('support.reopen', $ticket->id), [
            'reason' => 'عادت المشكلة للظهور',
        ])->assertSessionHas('success');

        $this->assertEquals('open', $ticket->fresh()->status);
        $this->assertFalse($ticket->fresh()->isClosed());
    }
}
