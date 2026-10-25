<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class NotificationInbox extends Component
{
    use WithPagination;

    public string $filter = 'all';

    /**
     * Set the active filter.
     */
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(string $id, NotificationService $service): void
    {
        /** @var User|null $user */
        $user = auth()->user();
        if ($user !== null) {
            $service->markAsRead($user, $id);
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(NotificationService $service): void
    {
        /** @var User|null $user */
        $user = auth()->user();
        if ($user !== null) {
            $service->markAllAsRead($user);
        }
    }

    /**
     * Delete a notification.
     */
    public function delete(string $id, NotificationService $service): void
    {
        /** @var User|null $user */
        $user = auth()->user();
        if ($user !== null) {
            $service->deleteNotification($user, $id);
        }
    }

    /**
     * Render the inbox view.
     */
    public function render(NotificationService $service): View
    {
        /** @var User $user */
        $user = auth()->user();

        $unreadOnly = $this->filter === 'unread' ? true : null;
        $type = in_array($this->filter, ['info', 'success', 'warning', 'error', 'security', 'system'], true)
            ? $this->filter
            : null;

        $notifications = $service->getUserNotifications($user, 15, $type, $unreadOnly);
        $unreadCount = $service->getUnreadCount($user);

        return view('web.notifications.notification-inbox', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
