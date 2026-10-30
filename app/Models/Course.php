<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title_ar
 * @property string|null $title_en
 * @property string $slug
 * @property string|null $description_ar
 * @property string|null $description_en
 * @property float $price
 * @property float|null $original_price
 * @property int $access_duration_days
 * @property string|null $thumbnail
 * @property string|null $promo_video_source
 * @property string|null $promo_video_url
 * @property string|null $mux_playback_id
 * @property string|null $mux_asset_id
 * @property bool $is_published
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'title_ar',
    'title_en',
    'slug',
    'description_ar',
    'description_en',
    'price',
    'original_price',
    'access_duration_days',
    'thumbnail',
    'promo_video_source',
    'promo_video_url',
    'mux_playback_id',
    'mux_asset_id',
    'is_published',
    'sort_order',
])]
class Course extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'access_duration_days' => 'integer',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get title based on locale.
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->title_en))
            ? $this->title_en
            : $this->title_ar;
    }

    /**
     * Get description based on locale.
     */
    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->description_en))
            ? $this->description_en
            : $this->description_ar;
    }

    /**
     * Enrolled students.
     *
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot(['id', 'access_starts_at', 'access_expires_at', 'status', 'granted_by'])
            ->withTimestamps();
    }

    /**
     * Curriculum Levels in this course.
     *
     * @return HasMany<Level, $this>
     */
    public function levels(): HasMany
    {
        return $this->hasMany(Level::class)->orderBy('sort_order');
    }

    /**
     * Lectures / Videos in this course.
     *
     * @return HasMany<Video, $this>
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('sort_order');
    }

    /**
     * Payment receipts for this course.
     *
     * @return HasMany<PaymentReceipt, $this>
     */
    public function paymentReceipts(): HasMany
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    /**
     * Certificates issued for this course.
     *
     * @return HasMany<Certificate, $this>
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Clinical tasks for this course.
     *
     * @return HasMany<ClinicalTask, $this>
     */
    public function clinicalTasks(): HasMany
    {
        return $this->hasMany(ClinicalTask::class);
    }

    /**
     * Get effective promo video preview URL or stream URL.
     */
    public function getEffectivePromoVideoUrlAttribute(): ?string
    {
        if ($this->promo_video_source === 'mux' && ! empty($this->mux_playback_id)) {
            return "https://stream.mux.com/{$this->mux_playback_id}.m3u8";
        }

        return $this->promo_video_url;
    }
}
