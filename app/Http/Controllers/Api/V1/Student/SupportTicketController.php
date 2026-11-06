<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Support\ListTicketsRequest;
use App\Http\Requests\Student\Support\PollMessagesRequest;
use App\Http\Requests\Student\Support\SendStudentMessageRequest;
use App\Http\Requests\Student\Support\StoreTicketRequest;
use App\Http\Resources\Api\V1\SupportMessageResource;
use App\Http\Resources\Api\V1\SupportTicketResource;
use App\Models\SupportTicket;
use App\Services\Support\SupportTicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct(
        protected SupportTicketService $ticketService,
    ) {}

    /**
     * GET /api/v1/student/support-tickets
     * List student support tickets with status counts and filterable pagination.
     */
    public function index(ListTicketsRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $status = (string) ($filters['status'] ?? 'all');
        $search = isset($filters['search']) ? (string) $filters['search'] : null;
        $perPage = (int) ($filters['per_page'] ?? 15);

        $data = $this->ticketService->getStudentTicketsWithCounts(
            $request->user(),
            $status,
            $search,
            $perPage
        );

        $paginator = $data['tickets'];

        return response()->json([
            'success' => true,
            'data' => [
                'counts' => $data['counts'],
                'unread_support_count' => $data['unreadSupportCount'],
                'tickets' => SupportTicketResource::collection($paginator->items()),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ],
        ]);
    }

    /**
     * POST /api/v1/student/support-tickets
     * Create a new student support ticket with optional file attachments.
     */
    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = $this->ticketService->createTicket(
            $request->user(),
            $request->validated(),
            $request->file('attachments', [])
        );

        $ticket->loadMissing(['course', 'assignedAdmin', 'publicMessages.sender']);

        return response()->json([
            'success' => true,
            'message' => __('تم إنشاء تذكرة الدعم الفني رقم #:number بنجاح.', ['number' => $ticket->ticket_number]),
            'data' => [
                'ticket' => new SupportTicketResource($ticket),
            ],
        ], 201);
    }

    /**
     * GET /api/v1/student/support-tickets/{ticket}
     * Return full ticket conversation thread and mark admin replies as read.
     */
    public function show(Request $request, SupportTicket $ticket): JsonResponse
    {
        if ((int) $ticket->user_id !== (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => __('غير مصرح لك بعرض هذه التذكرة.'),
            ], 403);
        }

        $resolved = $this->ticketService->getStudentTicketDetails($request->user(), $ticket);

        return response()->json([
            'success' => true,
            'data' => [
                'ticket' => new SupportTicketResource($resolved),
            ],
        ]);
    }

    /**
     * POST /api/v1/student/support-tickets/{ticket}/reply
     * Send a student reply message (with optional attachments) on a support ticket.
     */
    public function reply(SendStudentMessageRequest $request, SupportTicket $ticket): JsonResponse
    {
        if ((int) $ticket->user_id !== (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => __('غير مصرح لك بالرد على هذه التذكرة.'),
            ], 403);
        }

        $message = $this->ticketService->sendStudentReply(
            $ticket,
            $request->user(),
            $request->validated(),
            $request->file('attachments', [])
        );

        return response()->json([
            'success' => true,
            'message' => __('تم إرسال ردك بنجاح.'),
            'data' => [
                'reply' => new SupportMessageResource($message),
                'ticket_status' => $ticket->fresh()->status,
            ],
        ], 201);
    }

    /**
     * POST /api/v1/student/support-tickets/{ticket}/close
     * Close a support ticket by the student.
     */
    public function close(Request $request, SupportTicket $ticket): JsonResponse
    {
        if ((int) $ticket->user_id !== (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => __('غير مصرح لك بإغلاق هذه التذكرة.'),
            ], 403);
        }

        $this->ticketService->closeTicket($ticket, $request->user());

        return response()->json([
            'success' => true,
            'message' => __('تم إغلاق التذكرة بنجاح.'),
            'data' => [
                'ticket' => new SupportTicketResource($ticket->fresh(['course', 'assignedAdmin'])),
            ],
        ]);
    }

    /**
     * POST /api/v1/student/support-tickets/{ticket}/reopen
     * Reopen a closed or resolved support ticket by the student.
     */
    public function reopen(Request $request, SupportTicket $ticket): JsonResponse
    {
        if ((int) $ticket->user_id !== (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => __('غير مصرح لك بإعادة فتح هذه التذكرة.'),
            ], 403);
        }

        $this->ticketService->reopenTicket($ticket, $request->user());

        return response()->json([
            'success' => true,
            'message' => __('تمت إعادة فتح التذكرة بنجاح.'),
            'data' => [
                'ticket' => new SupportTicketResource($ticket->fresh(['course', 'assignedAdmin'])),
            ],
        ]);
    }

    /**
     * GET /api/v1/student/support-tickets/{ticket}/poll
     * Poll for new messages on a support ticket after `after_id` or `last_id`.
     */
    public function poll(PollMessagesRequest $request, SupportTicket $ticket): JsonResponse
    {
        if ((int) $ticket->user_id !== (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => __('غير مصرح لك بعرض هذه التذكرة.'),
            ], 403);
        }

        $validated = $request->validated();
        $lastId = (int) ($validated['after_id'] ?? $validated['last_id'] ?? 0);
        $data = $this->ticketService->pollStudentMessages($request->user(), $ticket, $lastId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
