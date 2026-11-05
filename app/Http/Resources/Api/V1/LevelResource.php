<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Level;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Level
 */
class LevelResource extends JsonResource
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
     * @var array<int>
     */
    protected array $accessibleLevelIds = [];

    /**
     * @var array<int, mixed>
     */
    protected array $userProgressMap = [];

    /**
     * @param  array<int>  $completedVideoIds
     * @param  array<int>  $accessibleVideoIds
     * @param  array<int>  $accessibleLevelIds
     * @param  array<int, mixed>  $userProgressMap
     */
    public function withUserStatus(
        array $completedVideoIds = [],
        array $accessibleVideoIds = [],
        array $accessibleLevelIds = [],
        array $userProgressMap = []
    ): self {
        $this->completedVideoIds = $completedVideoIds;
        $this->accessibleVideoIds = $accessibleVideoIds;
        $this->accessibleLevelIds = $accessibleLevelIds;
        $this->userProgressMap = $userProgressMap;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $videos = $this->relationLoaded('publishedVideos') ? $this->publishedVideos : collect();

        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'sort_order' => (int) $this->sort_order,
            'is_published' => (bool) $this->is_published,
            'is_accessible' => in_array($this->id, $this->accessibleLevelIds, true),
            'videos' => $videos->map(fn (Video $video) => (new VideoResource($video))->withUserStatus(
                $this->completedVideoIds,
                $this->accessibleVideoIds,
                $this->userProgressMap
            )->toArray($request))->values()->all(),
        ];
    }
}

