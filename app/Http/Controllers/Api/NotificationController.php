<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
    ) {}

    /**
     * Display a listing of the user's notifications.
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $perPage = (int) $request->query('per_page', 15);
        $type = $request->query('type');
        $unreadOnly = $request->has('unread_only') ? $request->boolean('unread_only') : null;

        $notifications = $this->notificationService->getUserNotifications(
            $user,
            $perPage,
            is_string($type) ? $type : null,
            $unreadOnly,
        );

        return ApiResponse::success([
            'unread_count' => $this->notificationService->getUnreadCount($user),
            'notifications' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $success = $this->notificationService->markAsRead($user, $id);

        if (! $success) {
            return ApiResponse::error(__('Notification not found.'), 404);
        }

        return ApiResponse::success([
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ], __('notifications.marked_read'));
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $count = $this->notificationService->markAllAsRead($user);

        return ApiResponse::success([
            'marked_count' => $count,
            'unread_count' => 0,
        ], __('notifications.marked_all_read'));
    }

    /**
     * Delete a notification for the authenticated user.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $deleted = $this->notificationService->deleteNotification($user, $id);

        if (! $deleted) {
            return ApiResponse::error(__('Notification not found.'), 404);
        }

        return ApiResponse::success([
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ], __('notifications.deleted'));
    }
}
