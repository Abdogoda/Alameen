<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Notifications;

use App\Models\Admin;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layouts.admin')]
class NotificationManager extends Component
{
    use WithPagination;

    // Send Notification Form State
    public string $target = 'all'; // 'all' or 'user'

    public ?int $selectedUserId = null;

    /** @var array<int, string> */
    public array $channels = ['database'];

    public string $type = 'info';

    public string $titleEn = '';

    public string $titleAr = '';

    public string $bodyEn = '';

    public string $bodyAr = '';

    public string $actionUrl = '';

    public string $actionTextEn = '';

    public string $actionTextAr = '';

    // History Table Filters
    public string $filterType = 'all';

    public ?int $filterUserId = null;

    public ?string $feedbackMessage = null;

    /**
     * Rules for validation.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'target' => ['required', 'string', 'in:all,user'],
            'selectedUserId' => ['required_if:target,user', 'nullable', 'integer', 'exists:users,id'],
            'channels' => ['required', 'array', 'min:1'],
            'channels.*' => ['string', 'in:database,mail'],
            'type' => ['required', 'string', 'in:info,success,warning,error,security,system'],
            'titleEn' => ['required', 'string', 'max:255'],
            'titleAr' => ['nullable', 'string', 'max:255'],
            'bodyEn' => ['required', 'string'],
            'bodyAr' => ['nullable', 'string'],
            'actionUrl' => ['nullable', 'string', 'url'],
            'actionTextEn' => ['nullable', 'string', 'max:100'],
            'actionTextAr' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Mount the component and check admin permission.
     */
    public function mount(): void
    {
        /** @var Admin|null $admin */
        $admin = auth('admin')->user();
        if ($admin === null || ! $admin->hasPermission('notifications.send')) {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Dispatch the notification through NotificationService.
     */
    public function sendNotification(NotificationService $service): void
    {
        $this->validate();

        $title = [
            'en' => $this->titleEn,
            'ar' => ! empty($this->titleAr) ? $this->titleAr : $this->titleEn,
        ];

        $body = [
            'en' => $this->bodyEn,
            'ar' => ! empty($this->bodyAr) ? $this->bodyAr : $this->bodyEn,
        ];

        $actionText = null;
        if (! empty($this->actionTextEn) || ! empty($this->actionTextAr)) {
            $actionText = [
                'en' => ! empty($this->actionTextEn) ? $this->actionTextEn : (! empty($this->actionTextAr) ? $this->actionTextAr : 'View'),
                'ar' => ! empty($this->actionTextAr) ? $this->actionTextAr : (! empty($this->actionTextEn) ? $this->actionTextEn : 'عرض'),
            ];
        }

        /** @var Admin|null $admin */
        $admin = auth('admin')->user();
        $senderId = $admin?->id;

        if ($this->target === 'all') {
            $service->sendToAll(
                title: $title,
                body: $body,
                channels: $this->channels,
                type: $this->type,
                actionUrl: ! empty($this->actionUrl) ? $this->actionUrl : null,
                actionText: $actionText,
                senderId: $senderId,
            );
        } else {
            $service->sendToUser(
                user: (int) $this->selectedUserId,
                title: $title,
                body: $body,
                channels: $this->channels,
                type: $this->type,
                actionUrl: ! empty($this->actionUrl) ? $this->actionUrl : null,
                actionText: $actionText,
                senderId: $senderId,
            );
        }

        // Reset form inputs
        $this->titleEn = '';
        $this->titleAr = '';
        $this->bodyEn = '';
        $this->bodyAr = '';
        $this->actionUrl = '';
        $this->actionTextEn = '';
        $this->actionTextAr = '';
        $this->feedbackMessage = __('notifications.admin.sent_successfully');
    }

    /**
     * Delete a notification from history.
     */
    public function deleteNotification(string $id, NotificationService $service): void
    {
        $service->adminDeleteNotification($id);
    }

    /**
     * Render the manager view.
     */
    public function render(NotificationService $service): View
    {
        $stats = $service->getNotificationStats();
        $type = $this->filterType !== 'all' ? $this->filterType : null;
        $history = $service->getAllSystemNotifications(15, $type, $this->filterUserId);
        /** @var Collection<int, User> $users */
        $users = User::query()->select('id', 'name', 'email')->orderBy('name')->limit(100)->get();

        return view('admin.notifications.notification-manager', [
            'stats' => $stats,
            'history' => $history,
            'users' => $users,
        ]);
    }
}
