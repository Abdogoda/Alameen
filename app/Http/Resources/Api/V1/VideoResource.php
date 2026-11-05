<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Video
 */
class VideoResource extends JsonResource
{
    /**
     * @var array<int>
     */
    protected array $completedVideoIds = [];

    /**
     * @var array<int>
     */
    protected array $accessibleVideoIds = [];

    /**
     * @var array<int, mixed>
     */
    protected array $userProgressMap = [];

    /**
     * @param  array<int>  $completedIds
     * @param  array<int>  $accessibleIds
     * @param  array<int, mixed>  $progressMap
     */
    public function withUserStatus(array $completedIds = [], array $accessibleIds = [], array $progressMap = []): self
    {
        $this->completedVideoIds = $completedIds;
        $this->accessibleVideoIds = $accessibleIds;
        $this->userProgressMap = $progressMap;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $prog = $this->userProgressMap[$this->id] ?? null;
        $isCompleted = in_array($this->id, $this->completedVideoIds, true) || (bool) ($prog?->is_completed ?? false);
        $isAccessible = (bool) $this->is_free_preview || in_array($this->id, $this->accessibleVideoIds, true);

        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'level_id' => $this->level_id,
            'slug' => $this->slug,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'video_source' => $this->video_source,
            'duration_seconds' => (int) $this->duration_seconds,
            'duration_minutes' => $this->duration_minutes,
            'min_completion_percentage' => (int) $this->min_completion_percentage,
            'sort_order' => (int) $this->sort_order,
            'is_free_preview' => (bool) $this->is_free_preview,
            'is_published' => (bool) $this->is_published,
            'is_accessible' => $isAccessible,
            'is_completed' => $isCompleted,
            'progress' => $prog ? [
                'watched_seconds' => (int) $prog->watched_seconds,
                'completion_percentage' => (float) $prog->completion_percentage,
                'is_completed' => (bool) $prog->is_completed,
                'last_watched_at' => $prog->last_watched_at?->toIso8601String(),
            ] : null,
            'clinical_task' => $this->whenLoaded('clinicalTask', fn () => $this->clinicalTask ? new ClinicalTaskResource($this->clinicalTask) : null),
        ];
    }
}

