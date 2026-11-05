<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Notification\ListNotificationsRequest;
use App\Http\Resources\Api\V1\NotificationResource;
use App\Services\Notification\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
    ) {}

    /**
     * GET /api/v1/student/notifications
     * Return paginated student notifications, counts, and unread badge count.
     */
    public function index(ListNotificationsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $filter = (string) ($validated['filter'] ?? 'all');
        $search = (string) ($validated['search'] ?? '');
        $perPage = (int) ($validated['per_page'] ?? 15);

        $data = $this->notificationService->getStudentInboxData(
            $request->user(),
            $filter,
            $search,
            $perPage
        );

        $paginator = $data['notifications'];

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $data['unreadCount'],
                'unread_support_count' => $data['unreadSupportCount'],
                'counts' => $data['counts'],
                'notifications' => NotificationResource::collection($paginator->items()),
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
     * GET /api/v1/student/notifications/recent
     * Return the most recent notifications for navbar bell dropdown.
     */
    public function recent(Request $request): JsonResponse
    {
        $limit = min(max((int) $request->query('limit', 8), 1), 30);
        $user = $request->user();

        $notifications = $this->notificationService->getRecentNotifications($user, $limit);
        $unreadCount = $this->notificationService->getUnreadCount($user);

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $unreadCount,
                'notifications' => NotificationResource::collection($notifications),
            ],
        ]);
    }

    /**
     * POST /api/v1/student/notifications/{id}/read
     * Mark a single notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $this->notificationService->markAsRead($user, $id);

        return response()->json([
            'success' => true,
            'message' => __('تم تحديد الإشعار كمقروء.'),
            'data' => [
                'unread_count' => $this->notificationService->getUnreadCount($user),
            ],
        ]);
    }

    /**
     * POST /api/v1/student/notifications/read-all
     * Mark all unread notifications for the student as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $count = $this->notificationService->markAllAsRead($request->user());

        return response()->json([
            'success' => true,
            'message' => __('تم تحديد جميع الإشعارات كمقروءة.'),
            'data' => [
                'marked_count' => $count,
                'unread_count' => 0,
            ],
        ]);
    }

    /**
     * DELETE /api/v1/student/notifications/{id}
     * Delete a notification from the student's inbox.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $deleted = $this->notificationService->deleteNotification($user, $id);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => __('الإشعار غير موجود.'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => __('تم حذف الإشعار بنجاح.'),
            'data' => [
                'unread_count' => $this->notificationService->getUnreadCount($user),
            ],
        ]);
    }
}
