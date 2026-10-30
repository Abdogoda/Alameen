<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
])]
class Level extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'course_id' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get title based on current application locale.
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->title_en))
            ? $this->title_en
            : $this->title_ar;
    }

    /**
     * Get description based on current application locale.
     */
    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->description_en))
            ? $this->description_en
            : $this->description_ar;
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
     * Clinical tasks linked to videos in this level.
     *
     * @return HasManyThrough<ClinicalTask, Video, $this>
     */
    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(ClinicalTask::class, Video::class);
    }
}
