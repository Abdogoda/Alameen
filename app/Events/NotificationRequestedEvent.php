<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationRequestedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  string|int|User|Collection<int, User>|array<int, int|User>  $recipients
     * @param  string|array<string, string>  $title
     * @param  string|array<string, string>  $body
     * @param  array<int, string>  $channels
     * @param  string|array<string, string>|null  $actionText
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        public mixed $recipients,
        public string|array $title,
        public string|array $body,
        public array $channels = ['database'],
        public string $type = 'info',
        public ?string $actionUrl = null,
        public string|array|null $actionText = null,
        public ?string $icon = null,
        public array $data = [],
        public ?int $senderId = null,
    ) {}
}
