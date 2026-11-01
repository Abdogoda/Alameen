<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Support\AdminAssignRequest;
use App\Http\Requests\Admin\Support\AdminSendMessageRequest;
use App\Http\Requests\Admin\Support\AdminUpdatePriorityRequest;
use App\Http\Requests\Admin\Support\AdminUpdateStatusRequest;
use App\Http\Requests\Student\Support\CloseStudentTicketRequest;
use App\Http\Requests\Student\Support\ReopenStudentTicketRequest;
use App\Models\Admin;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\Support\SupportTicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function __construct(
        protected SupportTicketService $ticketService
    ) {}

    /**
     * Admin Support Control Desk.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $priority = $request->query('priority', 'all');
        $category = $request->query('category', 'all');
        $assigned = $request->query('assigned', 'all');
        $search = $request->query('search');

        $query = SupportTicket::with(['user', 'assignedAdmin', 'course'])
            ->withCount(['messages as unread_user_count' => function ($q) {
                $q->where('type', 'message')
                    ->where('sender_type', User::class)
                    ->whereNull('read_at');
            }])
            ->latest('last_message_at');

        if ($status !== 'all' && in_array($status, ['open', 'in_progress', 'waiting_student', 'waiting_admin', 'resolved', 'closed'], true)) {
            $query->where('status', $status);
        }

        if ($priority !== 'all' && in_array($priority, ['low', 'medium', 'high', 'urgent'], true)) {
            $query->where('priority', $priority);
        }

        if ($category !== 'all' && in_array($category, ['technical', 'academic', 'billing', 'courses', 'certificates', 'general'], true)) {
            $query->where('category', $category);
        }

        if ($assigned === 'unassigned') {
            $query->whereNull('assigned_admin_id');
        } elseif ($assigned === 'mine') {
            $query->where('assigned_admin_id', auth('admin')->id());
        } elseif (is_numeric($assigned)) {
            $query->where('assigned_admin_id', (int) $assigned);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('ticket_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $tickets = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => SupportTicket::count(),
            'open' => SupportTicket::whereIn('status', ['open', 'waiting_admin'])->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'waiting_student' => SupportTicket::where('status', 'waiting_student')->count(),
            'urgent' => SupportTicket::where('priority', 'urgent')->whereNotIn('status', ['resolved', 'closed'])->count(),
            'unassigned' => SupportTicket::whereNull('assigned_admin_id')->whereNotIn('status', ['resolved', 'closed'])->count(),
            'mine' => SupportTicket::where('assigned_admin_id', auth('admin')->id())->whereNotIn('status', ['resolved', 'closed'])->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
        ];

        $admins = Admin::orderBy('name')->get();

        return view('admin.support.index', compact(
            'tickets',
            'counts',
            'status',
            'priority',
            'category',
            'assigned',
            'search',
            'admins'
        ));
    }

    /**
     * Show ticket conversation and action center for admins.
     */
    public function show(Request $request, SupportTicket $ticket): View
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();

        $this->ticketService->markMessagesAsRead($ticket, $admin);

        $ticket->load([
            'user.courses',
            'assignedAdmin',
            'course',
            'messages.sender',
        ]);

        $admins = Admin::orderBy('name')->get();
        $cannedResponses = $this->ticketService->getCannedResponses();

        return view('admin.support.show', compact('ticket', 'admins', 'cannedResponses'));
    }

    /**
     * Reply or post internal note from admin.
     */
    public function reply(AdminSendMessageRequest $request, SupportTicket $ticket): RedirectResponse|JsonResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();
        $validated = $request->validated();

        $message = $this->ticketService->sendAdminReply(
            ticket: $ticket,
            admin: $admin,
            data: $validated,
            files: $request->file('attachments')
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        }

        $notice = ! empty($validated['is_internal_note'])
            ? __('تمت إضافة الملاحظة الداخلية بنجاح (مرئية للمشرفين فقط).')
            : __('تم إرسال الرد إلى الطالب بنجاح.');

        return back()->with('success', $notice);
    }

    /**
     * Update ticket status.
     */
    public function updateStatus(AdminUpdateStatusRequest $request, SupportTicket $ticket): RedirectResponse|JsonResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();
        $validated = $request->validated();

        $this->ticketService->updateStatus($ticket, $validated['status'], $admin);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $ticket->status,
                'status_label' => $ticket->getStatusLabel(),
            ]);
        }

        return back()->with('success', __('تم تحديث حالة التذكرة بنجاح.'));
    }

    /**
     * Update ticket priority.
     */
    public function updatePriority(AdminUpdatePriorityRequest $request, SupportTicket $ticket): RedirectResponse|JsonResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();
        $validated = $request->validated();

        $this->ticketService->updatePriority($ticket, $validated['priority'], $admin);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'priority' => $ticket->priority,
                'priority_label' => $ticket->getPriorityLabel(),
            ]);
        }

        return back()->with('success', __('تم تعديل أولوية التذكرة بنجاح.'));
    }

    /**
     * Assign ticket to an admin.
     */
    public function assign(AdminAssignRequest $request, SupportTicket $ticket): RedirectResponse|JsonResponse
    {
        /** @var Admin|null $currentAdmin */
        $currentAdmin = auth('admin')->user();
        $validated = $request->validated();

        $assignee = null;
        if (! empty($validated['admin_id'])) {
            $found = Admin::query()->find((int) $validated['admin_id']);
            if ($found instanceof Admin) {
                $assignee = $found;
            }
        }
        $this->ticketService->assignAdmin($ticket, $assignee, $currentAdmin);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'assigned_admin' => $assignee instanceof Admin ? $assignee->name : __('غير مسند'),
            ]);
        }

        return back()->with('success', __('تم تحديث إسناد التذكرة بنجاح.'));
    }

    /**
     * Close ticket by admin.
     */
    public function close(CloseStudentTicketRequest $request, SupportTicket $ticket): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();
        $validated = $request->validated();

        $this->ticketService->closeTicket($ticket, $admin, $validated['reason'] ?? null);

        return back()->with('success', __('تم إغلاق التذكرة بنجاح.'));
    }

    /**
     * Reopen ticket by admin.
     */
    public function reopen(ReopenStudentTicketRequest $request, SupportTicket $ticket): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();
        $validated = $request->validated();

        $this->ticketService->reopenTicket($ticket, $admin, $validated['reason'] ?? null);

        return back()->with('success', __('تم إعادة فتح التذكرة بنجاح.'));
    }

    /**
     * Polling endpoint for real-time messages for admin (includes internal notes).
     */
    public function poll(Request $request, SupportTicket $ticket): JsonResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();
        $lastId = (int) $request->query('last_id', 0);

        $messages = $ticket->messages()
            ->with('sender')
            ->where('id', '>', $lastId)
            ->get();

        if ($messages->isNotEmpty()) {
            $this->ticketService->markMessagesAsRead($ticket, $admin);
        }

        return response()->json([
            'status' => $ticket->status,
            'status_label' => $ticket->getStatusLabel(),
            'status_badge' => $ticket->getStatusBadgeClass(),
            'priority' => $ticket->priority,
            'priority_label' => $ticket->getPriorityLabel(),
            'priority_badge' => $ticket->getPriorityBadgeClass(),
            'assigned_admin_id' => $ticket->assigned_admin_id,
            'is_closed' => $ticket->isClosed(),
            'messages' => $messages->map(function (SupportMessage $msg): array {
                return [
                    'id' => $msg->id,
                    'type' => $msg->type,
                    'is_student' => $msg->isFromStudent(),
                    'is_admin' => $msg->isFromAdmin(),
                    'is_internal' => $msg->isInternalNote(),
                    'is_system' => $msg->isSystemEvent(),
                    'sender_name' => $msg->getSenderName(),
                    'sender_avatar' => $msg->getSenderAvatar(),
                    'message' => $msg->message,
                    'attachments' => $msg->attachments,
                    'time_formatted' => $msg->created_at?->diffForHumans() ?? '',
                    'exact_time' => $msg->created_at?->format('H:i') ?? '',
                    'read_at' => $msg->read_at?->format('H:i') ?? null,
                ];
            }),
        ]);
    }
}
