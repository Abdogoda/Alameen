<?php

namespace App\Http\Resources\Api\V1;

use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SupportMessage
 */
class SupportMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'support_ticket_id' => $this->support_ticket_id,
            'type' => $this->type,
            'is_student' => $this->isFromStudent(),
            'is_admin' => $this->isFromAdmin(),
            'is_system' => $this->isSystemEvent(),
            'sender_name' => $this->getSenderName(),
            'sender_avatar' => $this->getSenderAvatar(),
            'message' => $this->message,
            'attachments' => $this->attachments ?? [],
            'read_at' => $this->read_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'time_formatted' => $this->created_at?->diffForHumans(),
            'exact_time' => $this->created_at?->format('H:i'),
        ];
    }
}
