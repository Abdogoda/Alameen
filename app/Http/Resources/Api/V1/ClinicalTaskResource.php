<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\ClinicalTask;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ClinicalTask
 */
class ClinicalTaskResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'video_id' => $this->video_id,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'instructions' => $this->instructions,
            'instructions_ar' => $this->instructions_ar,
            'instructions_en' => $this->instructions_en,
            'max_score' => (int) $this->max_score,
            'course' => $this->whenLoaded('course', fn () => $this->course ? [
                'id' => $this->course->id,
                'slug' => $this->course->slug,
                'title' => $this->course->title,
            ] : null),
            'video' => $this->whenLoaded('video', fn () => $this->video ? [
                'id' => $this->video->id,
                'slug' => $this->video->slug,
                'title' => $this->video->title,
                'level_id' => $this->video->level_id,
            ] : null),
        ];
    }
}

