<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $course_id
 * @property int|null $video_id
 * @property string $title_ar
 * @property string|null $title_en
 * @property string|null $instructions_ar
 * @property string|null $instructions_en
 * @property int $max_score
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'course_id',
    'video_id',
    'title_ar',
    'title_en',
    'instructions_ar',
    'instructions_en',
    'max_score',
])]
class ClinicalTask extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'course_id' => 'integer',
            'video_id' => 'integer',
            'max_score' => 'integer',
        ];
    }

    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->title_en))
            ? $this->title_en
            : $this->title_ar;
    }

    public function getInstructionsAttribute(): ?string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->instructions_en))
            ? $this->instructions_en
            : $this->instructions_ar;
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class, 'task_id');
    }

    public function submissionForUser(int $userId): ?TaskSubmission
    {
        return $this->submissions()->where('user_id', $userId)->first();
    }
}
