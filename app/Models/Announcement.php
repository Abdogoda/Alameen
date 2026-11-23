<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $title_ar
 * @property string|null $title_en
 * @property string $slug
 * @property string $content_type // 'text', 'video', 'mixed'
 * @property string|null $summary_ar
 * @property string|null $summary_en
 * @property string|null $body_ar
 * @property string|null $body_en
 * @property string|null $video_type // 'url', 'upload', 'youtube', 'vimeo', 'mux'
 * @property string|null $video_url
 * @property string|null $video_mux_playback_id
 * @property string|null $thumbnail_url
 * @property array<string>|null $target_platforms // ['web', 'mobile', 'admin']
 * @property string $target_audience // 'all', 'students', 'admins', 'guests'
 * @property array<int>|null $target_role_ids
 * @property array<int>|null $target_course_ids
 * @property string $display_mode // 'popup', 'force', 'banner', 'inline'
 * @property string $frequency // 'once', 'until_dismissed', 'every_session', 'always'
 * @property string|null $button_text_ar
 * @property string|null $button_text_en
 * @property string|null $button_url
 * @property bool $is_active
 * @property int $priority
 * @property Carbon|null $start_at
 * @property Carbon|null $end_at
 * @property int|null $created_by_admin_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Admin|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, AnnouncementInteraction> $interactions
 */
class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';

    protected $fillable = [
        'title_ar',
        'title_en',
        'slug',
        'content_type',
        'summary_ar',
        'summary_en',
        'body_ar',
        'body_en',
        'video_type',
        'video_url',
        'video_mux_playback_id',
        'thumbnail_url',
        'target_platforms',
        'target_audience',
        'target_role_ids',
        'target_course_ids',
        'display_mode',
        'frequency',
        'button_text_ar',
        'button_text_en',
        'button_url',
        'is_active',
        'priority',
        'start_at',
        'end_at',
        'created_by_admin_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'target_platforms' => 'array',
            'target_role_ids' => 'array',
            'target_course_ids' => 'array',
            'is_active' => 'boolean',
            'priority' => 'integer',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Announcement $announcement): void {
            if (empty($announcement->slug)) {
                $base = ! empty($announcement->title_en) ? $announcement->title_en : $announcement->title_ar;
                $slug = Str::slug($base);
                if (empty($slug)) {
                    $slug = 'announcement-'.Str::random(8);
                }
                $count = static::where('slug', 'like', "{$slug}%")->count();
                $announcement->slug = $count > 0 ? "{$slug}-".($count + 1) : $slug;
            }

            if (empty($announcement->target_platforms)) {
                $announcement->target_platforms = ['web', 'mobile', 'admin'];
            }
        });
    }

    /**
     * Get title according to current locale.
     */
    public function getTitleAttribute(): string
    {
        if (app()->getLocale() === 'en' && ! empty($this->title_en)) {
            return $this->title_en;
        }

        return $this->title_ar ?? '';
    }

    /**
     * Get summary according to current locale.
     */
    public function getSummaryAttribute(): string
    {
        if (app()->getLocale() === 'en' && ! empty($this->summary_en)) {
            return $this->summary_en;
        }

        return $this->summary_ar ?? '';
    }

    /**
     * Get body content according to current locale.
     */
    public function getBodyAttribute(): string
    {
        if (app()->getLocale() === 'en' && ! empty($this->body_en)) {
            return $this->body_en;
        }

        return $this->body_ar ?? '';
    }

    /**
     * Get CTA button text according to current locale.
     */
    public function getButtonTextAttribute(): ?string
    {
        if (app()->getLocale() === 'en' && ! empty($this->button_text_en)) {
            return $this->button_text_en;
        }

        return $this->button_text_ar ?? null;
    }

    /**
     * Check if announcement is mandatory / forced.
     */
    public function getIsForcedAttribute(): bool
    {
        return $this->display_mode === 'force';
    }

    /**
     * Check if announcement has a playable video.
     */
    public function getHasVideoAttribute(): bool
    {
        return in_array($this->content_type, ['video', 'mixed'], true)
            && (! empty($this->video_url) || ! empty($this->video_mux_playback_id));
    }

    /**
     * Get sanitized video embed URL if video is from YouTube, Vimeo, etc.
     */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (empty($this->video_url)) {
            return null;
        }

        $url = $this->video_url;

        // YouTube Watch URL or Short URL
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/'.$matches[1].'?autoplay=0&rel=0';
        }

        // Vimeo URL
        if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/i', $url, $matches)) {
            return 'https://player.vimeo.com/video/'.$matches[3];
        }

        return $url;
    }

    /**
     * Admin who created this announcement.
     *
     * @return BelongsTo<Admin, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    /**
     * User interactions recorded for this announcement.
     *
     * @return HasMany<AnnouncementInteraction, $this>
     */
    public function interactions(): HasMany
    {
        return $this->hasMany(AnnouncementInteraction::class, 'announcement_id');
    }

    /**
     * Scope: Active announcements currently scheduled.
     *
     * @param  Builder<Announcement>  $query
     * @return Builder<Announcement>
     */
    public function scopeActive(Builder $query): Builder
    {
        $now = now();

        return $query->where('is_active', true)
            ->where(function (Builder $q) use ($now): void {
                $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
            })
            ->where(function (Builder $q) use ($now): void {
                $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
            });
    }

    /**
     * Scope: Targeted platform.
     *
     * @param  Builder<Announcement>  $query
     * @return Builder<Announcement>
     */
    public function scopeForPlatform(Builder $query, string $platform): Builder
    {
        return $query->where(function (Builder $q) use ($platform): void {
            $q->whereJsonContains('target_platforms', $platform)
                ->orWhereJsonContains('target_platforms', 'all')
                ->orWhereNull('target_platforms');
        });
    }

    /**
     * Scope: Targeted audience.
     *
     * @param  Builder<Announcement>  $query
     * @return Builder<Announcement>
     */
    public function scopeForAudience(Builder $query, string $audience): Builder
    {
        if ($audience === 'all') {
            return $query;
        }

        return $query->whereIn('target_audience', ['all', $audience]);
    }

    /**
     * Scope: Order by priority then latest.
     *
     * @param  Builder<Announcement>  $query
     * @return Builder<Announcement>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('priority')->latest('id');
    }
}
