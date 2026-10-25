<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool $open = false;

    /**
     * Toggle the notifications dropdown.
     */
    public function toggleDropdown(): void
    {
        $this->open = ! $this->open;
    }

    /**
     * Close the dropdown.
     */
    public function closeDropdown(): void
    {
        $this->open = false;
    }

    /**
     * Mark a single notification as read.
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
     * Render the component.
     */
    public function render(NotificationService $service): View
    {
        /** @var User|null $user */
        $user = auth()->user();

        $unreadCount = $user !== null ? $service->getUnreadCount($user) : 0;
        /** @var Collection<int, DatabaseNotification> $recentNotifications */
        $recentNotifications = $user !== null ? $service->getRecentNotifications($user, 6) : collect();

        return view('web.notifications.notification-bell', [
            'unreadCount' => $unreadCount,
            'recentNotifications' => $recentNotifications,
        ]);
    }
}
