<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Course;
use App\Services\Cms\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Course
 */
class CourseSummaryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $settings = app(SettingsService::class);

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'price' => (float) $this->price,
            'original_price' => $this->original_price !== null ? (float) $this->original_price : null,
            'currency_symbol' => $settings->getCurrencySymbol(),
            'currency_name' => $settings->getCurrencyName(),
            'access_duration_days' => $this->access_duration_days,
            'thumbnail' => $this->thumbnail ?: '/assets/images/course-topic-basics.png',
            'promo_video_source' => $this->promo_video_source,
            'promo_video_url' => $this->effective_promo_video_url,
            'videos_count' => $this->whenCounted('videos', $this->videos_count ?? null),
            'is_published' => (bool) $this->is_published,
            'sort_order' => (int) $this->sort_order,
        ];
    }
}

