<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Video\VideoProgressService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $course_id
 * @property string $title_ar
 * @property string|null $title_en
 * @property string|null $description_ar
 * @property string|null $description_en
 * @property int $sort_order
 * @property bool $is_published
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'course_id',
    'title_ar',
    'title_en',
    'description_ar',
    'description_en',
    'sort_order',
    'is_published',
])]
class Level extends Model
{
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
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Scope query to only published levels.
     *
     * @param  Builder<Level>  $query
     * @return Builder<Level>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Get title based on current application locale.
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'en') {
            return ! empty($this->title_en) ? $this->title_en : __($this->title_ar);
        }

        return $this->title_ar;
    }

    /**
     * Get description based on current application locale.
     */
    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'en') {
            return ! empty($this->description_en)
                ? $this->description_en
                : (! empty($this->description_ar) ? (string) __($this->description_ar) : null);
        }

        return $this->description_ar;
    }

    /**
     * Parent Course.
     *
     * @return BelongsTo<Course, $this>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Videos / Lessons within this level.
     *
     * @return HasMany<Video, $this>
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('sort_order');
    }

    /**
     * Published Videos / Lessons within this level.
     *
     * @return HasMany<Video, $this>
     */
    public function publishedVideos(): HasMany
    {
        return $this->hasMany(Video::class)->where('is_published', true)->orderBy('sort_order');
    }

    /**
     * Clinical tasks linked to videos in this level.
     *
     * @return HasManyThrough<ClinicalTask, Video, $this>
     */
    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(ClinicalTask::class, Video::class);
    }

    /**
     * Check if a specific student has completed all lessons in this level.
     */
    public function isCompletedBy(User $user): bool
    {
        return app(VideoProgressService::class)->isLevelCompleted($user, $this);
    }

    /**
     * Check if a specific student can access this level.
     */
    public function canBeAccessedBy(User $user): bool
    {
        return app(VideoProgressService::class)->canAccessLevel($user, $this);
    }
}
