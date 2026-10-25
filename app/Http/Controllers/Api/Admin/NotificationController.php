<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

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
     * List all system notifications across all users.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 20);
        $type = $request->query('type');
        $userId = $request->has('user_id') ? (int) $request->query('user_id') : null;
        $unreadOnly = $request->has('unread_only') ? $request->boolean('unread_only') : null;

        $notifications = $this->notificationService->getAllSystemNotifications(
            $perPage,
            is_string($type) ? $type : null,
            $userId,
            $unreadOnly,
        );

        return ApiResponse::success([
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
     * Get aggregated notification stats.
     */
    public function stats(): JsonResponse
    {
        return ApiResponse::success($this->notificationService->getNotificationStats());
    }

    /**
     * Send a notification to all users or a specific user.
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'target' => ['required', 'string', 'in:all,user,users'],
            'user_id' => ['required_if:target,user', 'nullable', 'integer', 'exists:users,id'],
            'user_ids' => ['required_if:target,users', 'nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'channels' => ['nullable', 'array'],
            'channels.*' => ['string', 'in:database,mail'],
            'type' => ['nullable', 'string', 'in:info,success,warning,error,security,system'],
            'title' => ['required'],
            'body' => ['required'],
            'action_url' => ['nullable', 'string', 'url'],
            'action_text' => ['nullable'],
            'icon' => ['nullable', 'string'],
            'data' => ['nullable', 'array'],
        ]);

        $channels = ! empty($validated['channels']) ? (array) $validated['channels'] : ['database'];
        $type = (string) ($validated['type'] ?? 'info');
        $actionUrl = isset($validated['action_url']) ? (string) $validated['action_url'] : null;
        $actionText = $validated['action_text'] ?? null;
        $icon = isset($validated['icon']) ? (string) $validated['icon'] : null;
        $data = isset($validated['data']) ? (array) $validated['data'] : [];
        /** @var User $currentUser */
        $currentUser = $request->user();
        $senderId = $currentUser->id;

        /** @var string|array<string, string> $title */
        $title = $validated['title'];
        /** @var string|array<string, string> $body */
        $body = $validated['body'];
        /** @var string|array<string, string>|null $actionText */
        $actionText = $actionText;

        if ($validated['target'] === 'all') {
            $this->notificationService->sendToAll(
                title: $title,
                body: $body,
                channels: $channels,
                type: $type,
                actionUrl: $actionUrl,
                actionText: $actionText,
                icon: $icon,
                data: $data,
                senderId: $senderId,
            );
        } elseif ($validated['target'] === 'users' && ! empty($validated['user_ids'])) {
            $this->notificationService->sendToUsers(
                users: (array) $validated['user_ids'],
                title: $title,
                body: $body,
                channels: $channels,
                type: $type,
                actionUrl: $actionUrl,
                actionText: $actionText,
                icon: $icon,
                data: $data,
                senderId: $senderId,
            );
        } else {
            $this->notificationService->sendToUser(
                user: (int) $validated['user_id'],
                title: $title,
                body: $body,
                channels: $channels,
                type: $type,
                actionUrl: $actionUrl,
                actionText: $actionText,
                icon: $icon,
                data: $data,
                senderId: $senderId,
            );
        }

        return ApiResponse::success([
            'target' => $validated['target'],
            'channels' => $channels,
            'type' => $type,
        ], __('notifications.admin.sent_successfully'), 201);
    }

    /**
     * Delete any system notification.
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->notificationService->adminDeleteNotification($id);

        if (! $deleted) {
            return ApiResponse::error(__('Notification not found.'), 404);
        }

        return ApiResponse::success(null, __('notifications.deleted'));
    }
}
