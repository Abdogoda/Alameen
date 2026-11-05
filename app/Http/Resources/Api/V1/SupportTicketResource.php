<?php

namespace App\Http\Resources\Api\V1;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SupportTicket
 */
class SupportTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $messagesRelation = $this->relationLoaded('publicMessages')
            ? $this->publicMessages
            : ($this->relationLoaded('messages') ? $this->messages : null);

        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'subject' => $this->subject,
            'category' => $this->category,
            'category_label' => $this->getCategoryLabel(),
            'priority' => $this->priority,
            'priority_label' => $this->getPriorityLabel(),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'is_closed' => $this->isClosed(),
            'unread_count' => (int) ($this->unread_count ?? 0),
            'last_message_at' => $this->last_message_at?->toIso8601String(),
            'resolved_at' => $this->resolved_at?->toIso8601String(),
            'closed_at' => $this->closed_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'course' => $this->whenLoaded('course', fn () => $this->course ? new CourseSummaryResource($this->course) : null),
            'assigned_admin' => $this->whenLoaded('assignedAdmin', fn () => $this->assignedAdmin ? [
                'id' => $this->assignedAdmin->id,
                'name' => $this->assignedAdmin->name,
            ] : null),
            'messages' => $messagesRelation !== null ? SupportMessageResource::collection($messagesRelation) : [],
        ];
    }
}
