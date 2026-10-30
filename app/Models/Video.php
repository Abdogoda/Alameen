<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $course_id
 * @property int|null $level_id
 * @property string $title_ar
 * @property string|null $title_en
 * @property string $slug
 * @property string $video_source
 * @property string|null $video_url
 * @property string|null $mux_playback_id
 * @property string|null $mux_asset_id
 * @property string|null $mux_upload_id
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
    'level_id',
    'title_ar',
    'title_en',
    'slug',
    'video_source',
    'video_url',
    'mux_playback_id',
    'mux_asset_id',
    'mux_upload_id',
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
            'level_id' => 'integer',
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
     * Parent level within course.
     *
     * @return BelongsTo<Level, $this>
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Linked clinical task.
     *
     * @return HasOne<ClinicalTask, $this>
     */
    public function clinicalTask(): HasOne
    {
        return $this->hasOne(ClinicalTask::class);
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

    /**
     * Get effective streaming / embed / playback URL.
     */
    public function getEffectivePlaybackUrlAttribute(): string
    {
        if ($this->video_source === 'mux' && ! empty($this->mux_playback_id)) {
            return "https://stream.mux.com/{$this->mux_playback_id}.m3u8";
        }

        if (! empty($this->video_url)) {
            return $this->video_url;
        }

        if (! empty($this->bunny_video_id)) {
            return $this->bunny_video_id;
        }

        return '';
    }

    /**
     * Duration in human minutes string.
     */
    public function getDurationMinutesAttribute(): int
    {
        return (int) max(1, round($this->duration_seconds / 60));
    }
}
