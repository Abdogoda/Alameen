<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\NotificationRequestedEvent;
use App\Models\User;
use App\Notifications\UnifiedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;

class SendNotificationListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(NotificationRequestedEvent $event): void
    {
        $notification = new UnifiedNotification(
            title: $event->title,
            body: $event->body,
            channels: $event->channels,
            type: $event->type,
            actionUrl: $event->actionUrl,
            actionText: $event->actionText,
            icon: $event->icon,
            data: $event->data,
            senderId: $event->senderId,
        );

        // Broadcast to all users
        if ($event->recipients === 'all') {
            User::query()->chunk(200, function ($users) use ($notification): void {
                Notification::send($users, $notification);
            });

            return;
        }

        // Single User model
        if ($event->recipients instanceof User) {
            $event->recipients->notify($notification);

            return;
        }

        // Eloquent Collection of users
        if ($event->recipients instanceof Collection) {
            Notification::send($event->recipients, $notification);

            return;
        }

        // Array of IDs or User models
        if (is_array($event->recipients)) {
            if (empty($event->recipients)) {
                return;
            }

            $first = reset($event->recipients);
            if ($first instanceof User) {
                Notification::send($event->recipients, $notification);

                return;
            }

            $ids = [];
            foreach ($event->recipients as $item) {
                if ($item instanceof User) {
                    $ids[] = $item->id;
                } else {
                    $ids[] = $item;
                }
            }
            User::query()->whereIn('id', $ids)->chunk(200, function ($users) use ($notification): void {
                Notification::send($users, $notification);
            });

            return;
        }

        // Single integer ID
        if (is_int($event->recipients)) {
            $user = User::query()->find($event->recipients);
            if ($user instanceof User) {
                $user->notify($notification);
            }
        }
    }
}
