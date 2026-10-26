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
 * @property string $certificate_code
 * @property string $verification_url
 * @property string|null $qr_code_svg
 * @property string|null $pdf_path
 * @property float $completion_percentage
 * @property Carbon|null $issued_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'user_id',
    'course_id',
    'certificate_code',
    'verification_url',
    'qr_code_svg',
    'pdf_path',
    'completion_percentage',
    'issued_at',
])]
class Certificate extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'course_id' => 'integer',
            'completion_percentage' => 'float',
            'issued_at' => 'datetime',
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
}
