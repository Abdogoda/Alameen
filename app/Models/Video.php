<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Video\VideoProgressService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
 * @property bool $is_published
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
    'is_published',
])]
class Video extends Model
{
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_published' => true,
    ];

    protected function casts(): array
    {
        return [
            'course_id' => 'integer',
            'level_id' => 'integer',
            'duration_seconds' => 'integer',
            'min_completion_percentage' => 'integer',
            'sort_order' => 'integer',
            'is_free_preview' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Scope query to only published videos (and where level, if assigned, is published).
     *
     * @param  Builder<Video>  $query
     * @return Builder<Video>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('videos.is_published', true)
            ->where(function (Builder $q) {
                $q->whereNull('videos.level_id')
                    ->orWhereHas('level', function (Builder $lq) {
                        $lq->where('levels.is_published', true);
                    });
            });
    }

    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'en') {
            return ! empty($this->title_en) ? $this->title_en : __($this->title_ar);
        }

        return $this->title_ar;
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
     * Check if a specific student has completed this video.
     */
    public function isCompletedBy(User $user): bool
    {
        return app(VideoProgressService::class)->isVideoCompleted($user, $this);
    }

    /**
     * Check if a specific student can access this video.
     */
    public function canBeAccessedBy(User $user): bool
    {
        return app(VideoProgressService::class)->canAccessVideo($user, $this);
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
            if ($this->video_source === 'local' && str_contains($this->video_url, '/storage/')) {
                return '/storage/'.Str::after($this->video_url, '/storage/');
            }

            return $this->video_url;
        }

        if (! empty($this->bunny_video_id)) {
            $libraryId = $this->bunny_library_id ?: config('services.bunny.library_id', '');
            $pullZone = config('services.bunny.pull_zone', 'iframe.mediadelivery.net');

            return "https://{$pullZone}/embed/{$libraryId}/{$this->bunny_video_id}";
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
