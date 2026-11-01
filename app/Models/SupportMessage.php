<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $support_ticket_id
 * @property string|null $sender_type
 * @property int|null $sender_id
 * @property string $type
 * @property string $message
 * @property array<array<string, mixed>>|null $attachments
 * @property Carbon|null $read_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SupportTicket $ticket
 * @property-read Model|null $sender
 */
#[Fillable([
    'support_ticket_id',
    'sender_type',
    'sender_id',
    'type',
    'message',
    'attachments',
    'read_at',
])]
class SupportMessage extends Model
{
    /**
     * @var string
     */
    protected $table = 'support_messages';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'attachments' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * The parent ticket.
     *
     * @return BelongsTo<SupportTicket, $this>
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    /**
     * The sender (User, Admin, or null for system events).
     *
     * @return MorphTo<Model, $this>
     */
    public function sender(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if message is from student.
     */
    public function isFromStudent(): bool
    {
        return $this->sender_type === User::class;
    }

    /**
     * Check if message is from admin.
     */
    public function isFromAdmin(): bool
    {
        return $this->sender_type === Admin::class;
    }

    /**
     * Check if message is internal admin note.
     */
    public function isInternalNote(): bool
    {
        return $this->type === 'internal_note';
    }

    /**
     * Check if message is a system timeline event.
     */
    public function isSystemEvent(): bool
    {
        return $this->type === 'system_event';
    }

    /**
     * Get sender display name.
     */
    public function getSenderName(): string
    {
        if ($this->isSystemEvent()) {
            return __('النظام الأكاديمي');
        }

        if ($this->sender) {
            /** @var User|Admin $sender */
            $sender = $this->sender;

            return $sender->name;
        }

        return __('مستخدم مجهول');
    }

    /**
     * Get sender avatar or placeholder URL.
     */
    public function getSenderAvatar(): string
    {
        if ($this->sender && ! empty($this->sender->avatar)) {
            return $this->sender->avatar;
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->getSenderName()).'&color=012383&background=EAF1FB&bold=true';
    }
}
