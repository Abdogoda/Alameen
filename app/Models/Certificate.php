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
    'file_path',
    'completion_percentage',
    'issued_at',
    'template',
    'custom_bg',
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

    /**
     * Get the public asset URL of the uploaded certificate file (Image or PDF).
     */
    public function fileUrl(): ?string
    {
        $path = $this->file_path ?: $this->pdf_path;
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/'.ltrim($path, '/'));
    }

    /**
     * Check if the certificate file is a PDF.
     */
    public function isPdf(): bool
    {
        $path = $this->file_path ?: $this->pdf_path;

        return $path ? str_ends_with(strtolower($path), '.pdf') : false;
    }

    /**
     * Check if the certificate file is an image.
     */
    public function isImage(): bool
    {
        $path = $this->file_path ?: $this->pdf_path;
        if (! $path) {
            return false;
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg']);
    }

    /**
     * Get readable file extension or type.
     */
    public function fileType(): string
    {
        return $this->isPdf() ? 'PDF' : ($this->isImage() ? 'IMAGE' : 'FILE');
    }
}
