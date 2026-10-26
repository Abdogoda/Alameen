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
 * @property string $title_ar
 * @property string|null $title_en
 * @property string $slug
 * @property string|null $bunny_video_id
 * @property string|null $bunny_library_id
 * @property int $duration_seconds
 * @property int $min_completion_percentage
 * @property int $sort_order
 * @property bool $is_free_preview
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'course_id',
    'title_ar',
    'title_en',
    'slug',
    'bunny_video_id',
    'bunny_library_id',
    'duration_seconds',
    'min_completion_percentage',
    'sort_order',
    'is_free_preview',
])]
class Video extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'course_id' => 'integer',
            'duration_seconds' => 'integer',
            'min_completion_percentage' => 'integer',
            'sort_order' => 'integer',
            'is_free_preview' => 'boolean',
        ];
    }

    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->title_en))
            ? $this->title_en
            : $this->title_ar;
    }

    /**
     * Parent course.
     *
     * @return BelongsTo<Course, $this>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Video watch progress records.
     *
     * @return HasMany<VideoProgress, $this>
     */
    public function progress(): HasMany
    {
        return $this->hasMany(VideoProgress::class);
    }

    /**
     * Progress for a specific student.
     */
    public function progressForUser(int $userId): ?VideoProgress
    {
        return $this->progress()->where('user_id', $userId)->first();
    }
}
