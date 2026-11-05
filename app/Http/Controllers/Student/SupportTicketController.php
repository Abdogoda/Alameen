<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Support\CloseStudentTicketRequest;
use App\Http\Requests\Student\Support\ReopenStudentTicketRequest;
use App\Http\Requests\Student\Support\SendStudentMessageRequest;
use App\Http\Requests\Student\Support\StoreTicketRequest;
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
     * Display student tickets listing & support hub.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $status = (string) $request->query('status', 'all');
        $search = $request->query('search') ? (string) $request->query('search') : null;

        $data = $this->ticketService->getStudentTicketsWithCounts($user, $status, $search, 15);

        return view('web.support.index', $data);
    }

    /**
     * Show create ticket form.
     */
    public function create(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $enrolledCourses = $user->courses()->get();

        return view('web.support.create', compact('enrolledCourses'));
    }

    /**
     * Store new support ticket.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        /** @var User $user */
        $user = $request->user();

        $ticket = $this->ticketService->createTicket(
            user: $user,
            data: $validated,
            files: $request->file('attachments')
        );

        return redirect()->route('support.show', $ticket->id)
            ->with('success', __('تم فتح تذكرة الدعم بنجاح برقم :number. سنقوم بالرد عليك في أقرب وقت.', ['number' => $ticket->ticket_number]));
    }

    /**
     * Display ticket conversation and messages.
     */
    public function show(Request $request, SupportTicket $ticket): View
    {
        /** @var User $user */
        $user = $request->user();

        if ($ticket->user_id !== $user->id) {
            abort(403, __('غير مصرح لك بالوصول إلى هذه التذكرة.'));
        }

        $ticket = $this->ticketService->getStudentTicketDetails($user, $ticket);

        return view('web.support.show', compact('ticket'));
    }

    /**
     * Send message reply from student.
     */
    public function reply(SendStudentMessageRequest $request, SupportTicket $ticket): RedirectResponse|JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($ticket->user_id !== $user->id) {
            abort(403);
        }

        if ($ticket->isClosed()) {
            return back()->with('error', __('هذه التذكرة مغلقة حالياً. يرجى إعادة فتحها للمتابعة.'));
        }

        $validated = $request->validated();

        $message = $this->ticketService->sendStudentReply(
            ticket: $ticket,
            user: $user,
            data: $validated,
            files: $request->file('attachments')
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        }

        return back()->with('success', __('تم إرسال رسالتك بنجاح.'));
    }

    /**
     * Close ticket by student.
     */
    public function close(CloseStudentTicketRequest $request, SupportTicket $ticket): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($ticket->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validated();
        $this->ticketService->closeTicket($ticket, $user, $validated['reason'] ?? null);

        return back()->with('success', __('تم إغلاق التذكرة بنجاح. شكراً لتواصلك معنا!'));
    }

    /**
     * Reopen ticket by student.
     */
    public function reopen(ReopenStudentTicketRequest $request, SupportTicket $ticket): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($ticket->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validated();
        $this->ticketService->reopenTicket($ticket, $user, $validated['reason'] ?? null);

        return back()->with('success', __('تم إعادة فتح التذكرة بنجاح وجاري متابعتها.'));
    }

    /**
     * Polling endpoint for real-time messages in chat.
     */
    public function poll(Request $request, SupportTicket $ticket): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($ticket->user_id !== $user->id) {
            abort(403);
        }

        $lastId = (int) $request->query('last_id', 0);
        $data = $this->ticketService->pollStudentMessages($user, $ticket, $lastId);

        return response()->json($data);
    }
}
