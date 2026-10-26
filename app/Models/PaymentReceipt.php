<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property string $payment_method
 * @property string|null $sender_name
 * @property string|null $sender_account
 * @property float $amount
 * @property string|null $transaction_reference
 * @property string $receipt_image_path
 * @property string $status
 * @property string|null $rejection_reason
 * @property int|null $reviewed_by
 * @property Carbon|null $reviewed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'user_id',
    'course_id',
    'payment_method',
    'sender_name',
    'sender_account',
    'amount',
    'transaction_reference',
    'receipt_image_path',
    'status',
    'rejection_reason',
    'reviewed_by',
    'reviewed_at',
])]
class PaymentReceipt extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'course_id' => 'integer',
            'amount' => 'decimal:2',
            'reviewed_by' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function paymentMethodLabel(): string
    {
        return match ($this->payment_method) {
            'vodafone_cash' => __('فودافون كاش (Vodafone Cash)'),
            'instapay' => __('إنستاباي (InstaPay)'),
            'bank_transfer' => __('تحويل بنكي (Bank Transfer)'),
            default => $this->payment_method,
        };
    }
}
