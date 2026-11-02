<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use App\Models\Admin;
use App\Models\SupportMessage;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('صندوق الإشعارات والتنبيهات')]
#[Layout('layouts.app')]
class NotificationInbox extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public string $search = '';

    /**
     * Reset pagination when search query updates.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

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

        $query = $user->notifications();

        // Apply filter
        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif (in_array($this->filter, ['info', 'success', 'warning', 'error', 'security', 'system'], true)) {
            $query->where('data->type', $this->filter);
        }

        // Apply search
        $cleanSearch = trim($this->search);
        if ($cleanSearch !== '') {
            $query->where(function ($q) use ($cleanSearch) {
                $q->where('data->title', 'like', "%{$cleanSearch}%")
                    ->orWhere('data->body', 'like', "%{$cleanSearch}%");
            });
        }

        $notifications = $query->latest()->paginate(12);

        $unreadCount = $user->unreadNotifications()->count();
        $totalCount = $user->notifications()->count();
        $securityCount = $user->notifications()->where('data->type', 'security')->count();
        $systemCount = $user->notifications()->where('data->type', 'system')->count();
        $academicCount = $user->notifications()->whereIn('data->type', ['info', 'success', 'warning'])->count();

        $counts = [
            'all' => $totalCount,
            'unread' => $unreadCount,
            'info' => $user->notifications()->where('data->type', 'info')->count(),
            'security' => $securityCount,
            'system' => $systemCount,
            'warning' => $user->notifications()->where('data->type', 'warning')->count(),
        ];

        $unreadSupportCount = SupportMessage::whereHas('ticket', fn ($q) => $q->where('user_id', $user->id))
            ->where('sender_type', Admin::class)
            ->where('type', 'message')
            ->whereNull('read_at')
            ->count();

        return view('web.notifications.notification-inbox', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'unreadSupportCount' => $unreadSupportCount,
            'totalCount' => $totalCount,
            'securityCount' => $securityCount,
            'systemCount' => $systemCount,
            'academicCount' => $academicCount,
            'counts' => $counts,
        ]);
    }
}
