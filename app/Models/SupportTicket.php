<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $ticket_number
 * @property int $user_id
 * @property int|null $assigned_admin_id
 * @property int|null $course_id
 * @property string $subject
 * @property string $category
 * @property string $priority
 * @property string $status
 * @property Carbon|null $last_message_at
 * @property Carbon|null $resolved_at
 * @property Carbon|null $closed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read Admin|null $assignedAdmin
 * @property-read Course|null $course
 * @property-read Collection<int, SupportMessage> $messages
 * @property-read Collection<int, SupportMessage> $publicMessages
 */
#[Fillable([
    'ticket_number',
    'user_id',
    'assigned_admin_id',
    'course_id',
    'subject',
    'category',
    'priority',
    'status',
    'last_message_at',
    'resolved_at',
    'closed_at',
])]
class SupportTicket extends Model
{
    /**
     * @var string
     */
    protected $table = 'support_tickets';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'last_message_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (SupportTicket $ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'TK-'.date('ymd').'-'.strtoupper(Str::random(4));
            }
            if (empty($ticket->last_message_at)) {
                $ticket->last_message_at = Carbon::now();
            }
        });
    }

    /**
     * The student who created this ticket.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The administrator assigned to this ticket.
     *
     * @return BelongsTo<Admin, $this>
     */
    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_admin_id');
    }

    /**
     * Associated course if relevant.
     *
     * @return BelongsTo<Course, $this>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * All messages and notes on this ticket.
     *
     * @return HasMany<SupportMessage, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'support_ticket_id')->orderBy('created_at', 'asc');
    }

    /**
     * Messages visible to the student (excludes internal notes).
     *
     * @return HasMany<SupportMessage, $this>
     */
    public function publicMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'support_ticket_id')
            ->where('type', '!=', 'internal_note')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Category label with translation support.
     */
    public function getCategoryLabel(): string
    {
        return match ($this->category) {
            'technical' => __('دعم فني وتقني'),
            'academic' => __('استفسار سريري وأكاديمي'),
            'billing' => __('فواتير ومدفوعات'),
            'courses' => __('محتوى الدورات والدروس'),
            'certificates' => __('الشهادات والاعتمادات'),
            default => __('استفسار عام'),
        };
    }

    /**
     * Priority label with translation support.
     */
    public function getPriorityLabel(): string
    {
        return match ($this->priority) {
            'urgent' => __('عاجل جداً'),
            'high' => __('مرتفعة'),
            'low' => __('منخفضة'),
            default => __('متوسطة'),
        };
    }

    /**
     * Priority badge classes for styling.
     */
    public function getPriorityBadgeClass(): string
    {
        return match ($this->priority) {
            'urgent' => 'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-950/50 dark:text-rose-400 dark:border-rose-900',
            'high' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/50 dark:text-amber-400 dark:border-amber-900',
            'low' => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
            default => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-900',
        };
    }

    /**
     * Status label with translation support.
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'open' => __('جديدة ومفتوحة'),
            'in_progress' => __('قيد المعالجة'),
            'waiting_student' => __('بانتظار رد الطالب'),
            'waiting_admin' => __('بانتظار رد المشرف'),
            'resolved' => __('تم الحل بنجاح'),
            'closed' => __('مغلقة'),
            default => $this->status,
        };
    }

    /**
     * Status badge classes.
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'open' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800',
            'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800',
            'waiting_student' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800',
            'waiting_admin' => 'bg-purple-100 text-purple-800 border-purple-200 dark:bg-purple-950/50 dark:text-purple-300 dark:border-purple-800',
            'resolved' => 'bg-teal-100 text-teal-800 border-teal-200 dark:bg-teal-950/50 dark:text-teal-300 dark:border-teal-800',
            'closed' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }

    /**
     * Whether the ticket can be replied to.
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Count unread messages for student.
     */
    public function unreadCountForStudent(): int
    {
        return $this->messages()
            ->where('type', 'message')
            ->where('sender_type', Admin::class)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Count unread messages for admin.
     */
    public function unreadCountForAdmin(): int
    {
        return $this->messages()
            ->where('type', 'message')
            ->where('sender_type', User::class)
            ->whereNull('read_at')
            ->count();
    }
}
