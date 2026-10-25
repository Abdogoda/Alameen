<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Events\NotificationRequestedEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Send a notification to a single user.
     *
     * @param  string|array<string, string>  $title
     * @param  string|array<string, string>  $body
     * @param  array<int, string>  $channels
     * @param  string|array<string, string>|null  $actionText
     * @param  array<string, mixed>  $data
     */
    public function sendToUser(
        User|int $user,
        string|array $title,
        string|array $body,
        array $channels = ['database'],
        string $type = 'info',
        ?string $actionUrl = null,
        string|array|null $actionText = null,
        ?string $icon = null,
        array $data = [],
        ?int $senderId = null,
    ): void {
        NotificationRequestedEvent::dispatch(
            $user,
            $title,
            $body,
            $channels,
            $type,
            $actionUrl,
            $actionText,
            $icon,
            $data,
            $senderId,
        );
    }

    /**
     * Send a notification to multiple specific users.
     *
     * @param  Collection<int, User>|array<int, int|User>  $users
     * @param  string|array<string, string>  $title
     * @param  string|array<string, string>  $body
     * @param  array<int, string>  $channels
     * @param  string|array<string, string>|null  $actionText
     * @param  array<string, mixed>  $data
     */
    public function sendToUsers(
        Collection|array $users,
        string|array $title,
        string|array $body,
        array $channels = ['database'],
        string $type = 'info',
        ?string $actionUrl = null,
        string|array|null $actionText = null,
        ?string $icon = null,
        array $data = [],
        ?int $senderId = null,
    ): void {
        NotificationRequestedEvent::dispatch(
            $users,
            $title,
            $body,
            $channels,
            $type,
            $actionUrl,
            $actionText,
            $icon,
            $data,
            $senderId,
        );
    }

    /**
     * Broadcast a notification to all users in the system.
     *
     * @param  string|array<string, string>  $title
     * @param  string|array<string, string>  $body
     * @param  array<int, string>  $channels
     * @param  string|array<string, string>|null  $actionText
     * @param  array<string, mixed>  $data
     */
    public function sendToAll(
        string|array $title,
        string|array $body,
        array $channels = ['database'],
        string $type = 'info',
        ?string $actionUrl = null,
        string|array|null $actionText = null,
        ?string $icon = null,
        array $data = [],
        ?int $senderId = null,
    ): void {
        NotificationRequestedEvent::dispatch(
            'all',
            $title,
            $body,
            $channels,
            $type,
            $actionUrl,
            $actionText,
            $icon,
            $data,
            $senderId,
        );
    }

    /**
     * Get paginated notifications for a given user.
     *
     * @return LengthAwarePaginator<int, DatabaseNotification>
     */
    public function getUserNotifications(
        User $user,
        int $perPage = 15,
        ?string $type = null,
        ?bool $unreadOnly = null,
    ): LengthAwarePaginator {
        $query = $user->notifications();

        if ($unreadOnly === true) {
            $query->whereNull('read_at');
        } elseif ($unreadOnly === false) {
            $query->whereNotNull('read_at');
        }

        if ($type !== null && $type !== '' && $type !== 'all') {
            $query->where('data->type', $type);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get the count of unread notifications for a user.
     */
    public function getUnreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    /**
     * Get the most recent notifications for user quick-view/dropdown.
     *
     * @return Collection<int, DatabaseNotification>
     */
    public function getRecentNotifications(User $user, int $limit = 5): Collection
    {
        return $user->notifications()->latest()->limit($limit)->get();
    }

    /**
     * Mark a specific notification as read for a user.
     */
    public function markAsRead(User $user, string $notificationId): bool
    {
        /** @var DatabaseNotification|null $notification */
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if ($notification === null) {
            return false;
        }

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return true;
    }

    /**
     * Mark all notifications as read for a user.
     *
     * @return int Number of updated notifications
     */
    public function markAllAsRead(User $user): int
    {
        $count = $user->unreadNotifications()->count();
        $user->unreadNotifications()->update(['read_at' => now()]);

        return $count;
    }

    /**
     * Delete a notification for a user.
     */
    public function deleteNotification(User $user, string $notificationId): bool
    {
        $deleted = $user->notifications()->where('id', $notificationId)->delete();

        return $deleted > 0;
    }

    /**
     * Get all system notifications across all users (for admin management).
     *
     * @return LengthAwarePaginator<int, DatabaseNotification>
     */
    public function getAllSystemNotifications(
        int $perPage = 20,
        ?string $type = null,
        ?int $userId = null,
        ?bool $unreadOnly = null,
    ): LengthAwarePaginator {
        $query = DatabaseNotification::query()->with('notifiable');

        if ($userId !== null) {
            $query->where('notifiable_id', $userId)
                ->where('notifiable_type', User::class);
        }

        if ($unreadOnly === true) {
            $query->whereNull('read_at');
        } elseif ($unreadOnly === false) {
            $query->whereNotNull('read_at');
        }

        if ($type !== null && $type !== '' && $type !== 'all') {
            $query->where('data->type', $type);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Delete any notification by ID (admin privilege).
     */
    public function adminDeleteNotification(string $notificationId): bool
    {
        $deleted = DatabaseNotification::query()->where('id', $notificationId)->delete();

        return $deleted > 0;
    }

    /**
     * Retrieve aggregated statistics for admin dashboard.
     *
     * @return array{
     *     total: int,
     *     unread: int,
     *     read: int,
     *     recipients_count: int,
     *     by_type: array<string, int>
     * }
     */
    public function getNotificationStats(): array
    {
        $total = DatabaseNotification::query()->count();
        $unread = DatabaseNotification::query()->whereNull('read_at')->count();
        $read = $total - $unread;
        $recipientsCount = DatabaseNotification::query()
            ->where('notifiable_type', User::class)
            ->distinct('notifiable_id')
            ->count('notifiable_id');

        $byTypeRows = DB::table('notifications')
            ->select(DB::raw("json_extract(data, '$.type') as notif_type, count(*) as total_count"))
            ->groupBy('notif_type')
            ->get();

        $byType = [];
        foreach ($byTypeRows as $row) {
            $type = trim((string) ($row->notif_type ?? 'info'), '"');
            $byType[$type] = (int) $row->total_count;
        }

        return [
            'total' => $total,
            'unread' => $unread,
            'read' => $read,
            'recipients_count' => $recipientsCount,
            'by_type' => $byType,
        ];
    }
}
