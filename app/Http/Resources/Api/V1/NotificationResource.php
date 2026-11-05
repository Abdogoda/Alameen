<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\DatabaseNotification;

/**
 * @mixin DatabaseNotification
 */
class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payload = is_array($this->data) ? $this->data : [];
        $locale = app()->getLocale();

        $title = $payload['title'] ?? '';
        if (is_array($title)) {
            $title = $title[$locale] ?? $title['ar'] ?? reset($title) ?: '';
        }

        $body = $payload['body'] ?? ($payload['message'] ?? '');
        if (is_array($body)) {
            $body = $body[$locale] ?? $body['ar'] ?? reset($body) ?: '';
        }

        $actionText = $payload['action_text'] ?? null;
        if (is_array($actionText)) {
            $actionText = $actionText[$locale] ?? $actionText['ar'] ?? reset($actionText) ?: null;
        }

        return [
            'id' => (string) $this->id,
            'type' => (string) ($payload['type'] ?? 'info'),
            'title' => (string) $title,
            'body' => (string) $body,
            'action_url' => $payload['action_url'] ?? ($payload['url'] ?? null),
            'action_text' => $actionText,
            'icon' => $payload['icon'] ?? null,
            'data' => $payload['data'] ?? $payload,
            'is_read' => $this->read_at !== null,
            'read_at' => $this->read_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'time_formatted' => $this->created_at?->diffForHumans(),
        ];
    }
}
