<?php

declare(strict_types=1);

namespace App\Services\Support;

use App\Models\Admin;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupportTicketService
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * Get paginated student support tickets along with status counts and unread badge counts.
     *
     * @return array<string, mixed>
     */
    public function getStudentTicketsWithCounts(
        User $user,
        string $status = 'all',
        ?string $search = null,
        int $perPage = 15
    ): array {
        $query = $user->supportTickets()
            ->with(['assignedAdmin', 'course'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('type', 'message')
                    ->where('sender_type', Admin::class)
                    ->whereNull('read_at');
            }])
            ->latest('last_message_at');

        if ($status !== 'all' && in_array($status, ['open', 'in_progress', 'waiting_student', 'waiting_admin', 'resolved', 'closed'], true)) {
            $query->where('status', $status);
        }

        if (! empty($search)) {
            $cleanSearch = trim($search);
            $query->where(function ($q) use ($cleanSearch) {
                $q->where('subject', 'like', "%{$cleanSearch}%")
                    ->orWhere('ticket_number', 'like', "%{$cleanSearch}%");
            });
        }

        $tickets = $query->paginate($perPage)->withQueryString();

        $counts = [
            'all' => $user->supportTickets()->count(),
            'open' => $user->supportTickets()->whereIn('status', ['open', 'in_progress', 'waiting_admin'])->count(),
            'waiting_student' => $user->supportTickets()->where('status', 'waiting_student')->count(),
            'resolved' => $user->supportTickets()->where('status', 'resolved')->count(),
            'closed' => $user->supportTickets()->where('status', 'closed')->count(),
        ];

        $unreadNotificationsCount = $user->unreadNotifications()->count();
        $unreadSupportCount = SupportMessage::whereHas('ticket', fn ($q) => $q->where('user_id', $user->id))
            ->where('sender_type', Admin::class)
            ->where('type', 'message')
            ->whereNull('read_at')
            ->count();

        return compact('tickets', 'counts', 'status', 'search', 'unreadNotificationsCount', 'unreadSupportCount');
    }

    /**
     * Load ticket details for a student and mark admin messages as read.
     */
    public function getStudentTicketDetails(User $user, SupportTicket $ticket): SupportTicket
    {
        $this->markMessagesAsRead($ticket, $user);

        return $ticket->load(['assignedAdmin', 'course', 'publicMessages.sender']);
    }

    /**
     * Poll new public messages on a ticket for real-time chat updates.
     *
     * @return array<string, mixed>
     */
    public function pollStudentMessages(User $user, SupportTicket $ticket, int $lastId = 0): array
    {
        $messages = $ticket->publicMessages()
            ->with('sender')
            ->where('id', '>', $lastId)
            ->get();

        if ($messages->isNotEmpty()) {
            $this->markMessagesAsRead($ticket, $user);
        }

        return [
            'status' => $ticket->status,
            'status_label' => $ticket->getStatusLabel(),
            'status_badge' => $ticket->getStatusBadgeClass(),
            'is_closed' => $ticket->isClosed(),
            'messages' => $messages->map(function (SupportMessage $msg): array {
                return [
                    'id' => $msg->id,
                    'type' => $msg->type,
                    'is_student' => $msg->isFromStudent(),
                    'is_system' => $msg->isSystemEvent(),
                    'sender_name' => $msg->getSenderName(),
                    'sender_avatar' => $msg->getSenderAvatar(),
                    'message' => $msg->message,
                    'attachments' => $msg->attachments,
                    'time_formatted' => $msg->created_at?->diffForHumans() ?? '',
                    'exact_time' => $msg->created_at?->format('H:i') ?? '',
                    'read_at' => $msg->read_at?->format('H:i') ?? null,
                ];
            })->values()->all(),
        ];
    }

    /**
     * Create a new support ticket from a student.
     *
     * @param  array<string, mixed>  $data
     * @param  array<UploadedFile>|null  $files
     */
    public function createTicket(User $user, array $data, ?array $files = null): SupportTicket
    {
        $attachments = $this->processAttachments($files);

        $ticket = SupportTicket::create([
            'user_id' => $user->id,
            'subject' => $data['subject'],
            'category' => $data['category'],
            'priority' => $data['priority'] ?? 'medium',
            'course_id' => ! empty($data['course_id']) ? (int) $data['course_id'] : null,
            'status' => 'open',
            'last_message_at' => Carbon::now(),
        ]);

        // First message from student
        $ticket->messages()->create([
            'sender_type' => User::class,
            'sender_id' => $user->id,
            'type' => 'message',
            'message' => $data['message'],
            'attachments' => $attachments,
            'read_at' => null,
        ]);

        // System event
        $ticket->messages()->create([
            'sender_type' => null,
            'sender_id' => null,
            'type' => 'system_event',
            'message' => __('تم إنشاء التذكرة بنجاح من قِبل الطالب (:name).', ['name' => $user->name]),
            'read_at' => Carbon::now(),
        ]);

        // Notify admins about new ticket
        $this->notifyAdmins(
            title: __('تذكرة دعم جديدة: :number', ['number' => $ticket->ticket_number]),
            body: __('أنشأ الطالب :student تذكرة دعم جديدة بعنوان: :subject', ['student' => $user->name, 'subject' => $ticket->subject]),
            ticket: $ticket
        );

        return $ticket;
    }

    /**
     * Student sends a reply to an existing ticket.
     *
     * @param  array<string, mixed>  $data
     * @param  array<UploadedFile>|null  $files
     */
    public function sendStudentReply(SupportTicket $ticket, User $user, array $data, ?array $files = null): SupportMessage
    {
        $attachments = $this->processAttachments($files);

        $message = $ticket->messages()->create([
            'sender_type' => User::class,
            'sender_id' => $user->id,
            'type' => 'message',
            'message' => $data['message'] ?? '',
            'attachments' => $attachments,
            'read_at' => null,
        ]);

        // Reopen if closed or waiting for student
        $newStatus = in_array($ticket->status, ['resolved', 'closed'], true) ? 'open' : 'waiting_admin';

        $ticket->update([
            'status' => $newStatus,
            'last_message_at' => Carbon::now(),
        ]);

        // Notify assigned admin or all admins
        $this->notifyAdmins(
            title: __('رد جديد على التذكرة :number', ['number' => $ticket->ticket_number]),
            body: __('أضاف الطالب :student رداً جديداً على تذكرة الدعم.', ['student' => $user->name]),
            ticket: $ticket
        );

        return $message;
    }

    /**
     * Admin sends a reply or internal note to a ticket.
     *
     * @param  array<string, mixed>  $data
     * @param  array<UploadedFile>|null  $files
     */
    public function sendAdminReply(SupportTicket $ticket, Admin $admin, array $data, ?array $files = null): SupportMessage
    {
        $isInternal = ! empty($data['is_internal_note']);
        $attachments = $this->processAttachments($files);

        $message = $ticket->messages()->create([
            'sender_type' => Admin::class,
            'sender_id' => $admin->id,
            'type' => $isInternal ? 'internal_note' : 'message',
            'message' => $data['message'] ?? '',
            'attachments' => $attachments,
            'read_at' => $isInternal ? Carbon::now() : null,
        ]);

        if (! $isInternal) {
            $ticket->update([
                'status' => 'waiting_student',
                'last_message_at' => Carbon::now(),
                'assigned_admin_id' => $ticket->assigned_admin_id ?? $admin->id,
            ]);

            // Send notification to the student
            $this->notificationService->sendToUser(
                user: $ticket->user,
                title: __('رد جديد من الدعم الفني: :number', ['number' => $ticket->ticket_number]),
                body: __('قام فريق الدعم بالرد على استفسارك: :subject', ['subject' => $ticket->subject]),
                channels: ['database'],
                type: 'info',
                actionUrl: route('support.show', $ticket->id),
                actionText: __('عرض المحادثة والرد')
            );
        }

        return $message;
    }

    /**
     * Update ticket status and record timeline event.
     */
    public function updateStatus(SupportTicket $ticket, string $status, ?Admin $admin = null): void
    {
        if ($ticket->status === $status) {
            return;
        }

        $oldStatus = $ticket->getStatusLabel();
        $ticket->status = $status;

        if ($status === 'resolved') {
            $ticket->resolved_at = Carbon::now();
        } elseif ($status === 'closed') {
            $ticket->closed_at = Carbon::now();
        }

        $ticket->save();

        $adminName = $admin ? $admin->name : __('المسؤول');
        $newLabel = $ticket->getStatusLabel();

        $ticket->messages()->create([
            'sender_type' => $admin ? Admin::class : null,
            'sender_id' => $admin?->id,
            'type' => 'system_event',
            'message' => __('تم تحديث حالة التذكرة من (:old) إلى (:new) بواسطة :actor.', [
                'old' => $oldStatus,
                'new' => $newLabel,
                'actor' => $adminName,
            ]),
            'read_at' => Carbon::now(),
        ]);

        // Notify student if resolved
        if ($status === 'resolved') {
            $this->notificationService->sendToUser(
                user: $ticket->user,
                title: __('تم حل تذكرة الدعم: :number', ['number' => $ticket->ticket_number]),
                body: __('تمت معالجة استفسارك وحله بنجاح. يمكنك مراجعة الحل أو إعادة فتحها إذا احتجت مساعدة.', ['subject' => $ticket->subject]),
                channels: ['database'],
                type: 'success',
                actionUrl: route('support.show', $ticket->id),
                actionText: __('مراجعة التذكرة')
            );
        }
    }

    /**
     * Update priority of ticket.
     */
    public function updatePriority(SupportTicket $ticket, string $priority, ?Admin $admin = null): void
    {
        if ($ticket->priority === $priority) {
            return;
        }

        $ticket->priority = $priority;
        $ticket->save();

        $adminName = $admin ? $admin->name : __('المسؤول');

        $ticket->messages()->create([
            'sender_type' => $admin ? Admin::class : null,
            'sender_id' => $admin?->id,
            'type' => 'system_event',
            'message' => __('تم تغيير أولوية التذكرة إلى (:priority) بواسطة :actor.', [
                'priority' => $ticket->getPriorityLabel(),
                'actor' => $adminName,
            ]),
            'read_at' => Carbon::now(),
        ]);
    }

    /**
     * Assign ticket to an admin or unassign.
     */
    public function assignAdmin(SupportTicket $ticket, ?Admin $assignee, ?Admin $byAdmin = null): void
    {
        $ticket->assigned_admin_id = $assignee?->id;
        if ($assignee && $ticket->status === 'open') {
            $ticket->status = 'in_progress';
        }
        $ticket->save();

        $message = $assignee
            ? __('تم إسناد التذكرة إلى المشرف (:name).', ['name' => $assignee->name])
            : __('تم إلغاء إسناد التذكرة وإعادتها لقائمة الانتظار.');

        $ticket->messages()->create([
            'sender_type' => $byAdmin ? Admin::class : null,
            'sender_id' => $byAdmin?->id,
            'type' => 'system_event',
            'message' => $message,
            'read_at' => Carbon::now(),
        ]);
    }

    /**
     * Close ticket.
     */
    public function closeTicket(SupportTicket $ticket, User|Admin $actor, ?string $reason = null): void
    {
        $ticket->update([
            'status' => 'closed',
            'closed_at' => Carbon::now(),
        ]);

        $reasonText = $reason ? ' - '.__('السبب: :reason', ['reason' => $reason]) : '';

        $ticket->messages()->create([
            'sender_type' => $actor instanceof Admin ? Admin::class : User::class,
            'sender_id' => $actor->id,
            'type' => 'system_event',
            'message' => __('تم إغلاق التذكرة بواسطة :actor:reason', [
                'actor' => $actor->name,
                'reason' => $reasonText,
            ]),
            'read_at' => Carbon::now(),
        ]);
    }

    /**
     * Reopen ticket.
     */
    public function reopenTicket(SupportTicket $ticket, User|Admin $actor, ?string $reason = null): void
    {
        $ticket->update([
            'status' => 'open',
            'closed_at' => null,
            'last_message_at' => Carbon::now(),
        ]);

        $reasonText = $reason ? ' - '.__('السبب: :reason', ['reason' => $reason]) : '';

        $ticket->messages()->create([
            'sender_type' => $actor instanceof Admin ? Admin::class : User::class,
            'sender_id' => $actor->id,
            'type' => 'system_event',
            'message' => __('تم إعادة فتح التذكرة بواسطة :actor:reason', [
                'actor' => $actor->name,
                'reason' => $reasonText,
            ]),
            'read_at' => Carbon::now(),
        ]);
    }

    /**
     * Mark unread messages as read for a viewer.
     */
    public function markMessagesAsRead(SupportTicket $ticket, User|Admin $actor): void
    {
        if ($actor instanceof User) {
            $ticket->messages()
                ->where('sender_type', Admin::class)
                ->whereNull('read_at')
                ->update(['read_at' => Carbon::now()]);
        } elseif ($actor instanceof Admin) {
            $ticket->messages()
                ->where('sender_type', User::class)
                ->whereNull('read_at')
                ->update(['read_at' => Carbon::now()]);
        }
    }

    /**
     * Process and store uploaded files.
     *
     * @param  array<UploadedFile>|null  $files
     * @return array<array<string, mixed>>|null
     */
    protected function processAttachments(?array $files): ?array
    {
        if (empty($files)) {
            return null;
        }

        $attachments = [];
        foreach ($files as $file) {
            if (! $file->isValid()) {
                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType() ?: 'application/octet-stream';

            $safeFileName = Str::uuid()->toString().'.'.$extension;
            $path = $file->storeAs('support/attachments/'.date('Y/m'), $safeFileName, 'public');

            if ($path === false) {
                continue;
            }

            $attachments[] = [
                'name' => $originalName,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'size' => $fileSize,
                'mime' => $mimeType,
                'is_image' => str_starts_with($mimeType, 'image/'),
                'is_audio' => str_starts_with($mimeType, 'audio/'),
                'is_pdf' => $extension === 'pdf',
            ];
        }

        return ! empty($attachments) ? $attachments : null;
    }

    /**
     * Dispatch notification to admins.
     */
    protected function notifyAdmins(string $title, string $body, SupportTicket $ticket): void
    {
        $admins = Admin::all();
        foreach ($admins as $admin) {
            // Using database notification on Admin
            $admin->notifications()->create([
                'id' => (string) Str::uuid(),
                'type' => 'App\Notifications\SupportTicketUpdated',
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'ticket_id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'url' => route('admin.support.show', $ticket->id),
                    'created_at' => Carbon::now()->toIso8601String(),
                ],
                'read_at' => null,
            ]);
        }
    }

    /**
     * Standard canned responses for fast admin replies.
     *
     * @return array<array<string, string>>
     */
    public function getCannedResponses(): array
    {
        return [
            [
                'title' => __('ترحيب وبدء المراجعة'),
                'text' => __('أهلاً بك يا بطل، نشكر تواصلك معنا. جاري مراجعة طلبك بدقة من قِبل المشرف المختص وسنوافيك بالرد الشافي خلال وقت قصير.'),
            ],
            [
                'title' => __('طلب لقطة شاشة أو تفاصيل'),
                'text' => __('مرحباً بك، لمساعدتك بشكل أفضل وحل المشكلة سريعاً، يرجى تزويدنا بلقطة شاشة (Screenshot) للخطأ مع توضيح المتصفح والجهاز المستخدم.'),
            ],
            [
                'title' => __('تأكيد تفعيل الكورس أو الإيصال'),
                'text' => __('تم التحقق وتأكيد حسابك بنجاح، أصبح بإمكانك الآن الدخول إلى الكورس ومتابعة المحاضرات والمهام السريرية مباشرة.'),
            ],
            [
                'title' => __('إصلاح مشكلة تقنية'),
                'text' => __('تم حل المشكلة التقنية بنجاح وتحديث بيانات الحساب. يرجى تجربة تسجيل الخروج ثم الدخول مجدداً، ونحن معك إذا استمرت أي صعوبة.'),
            ],
            [
                'title' => __('توجيه سريري وأكاديمي'),
                'text' => __('تمت مراجعة استفسارك الأكاديمي بواسطة المشرف السريري، وننصحك بمراجعة معايير التقييم السريري المسجلة في تفاصيل المهمة.'),
            ],
            [
                'title' => __('إغلاق التذكرة بعد الحل'),
                'text' => __('يسعدنا أن المشكلة قد حُلّت بالكامل. نتمنى لك دوام التوفيق والتميز الأكاديمي، ولا تتردد بفتح تذكرة جديدة إذا احتجت لأي مساعدة مستقبلاً.'),
            ],
        ];
    }
}
