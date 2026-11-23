<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Announcement
 */
class AnnouncementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'summary' => $this->summary,
            'body' => $this->body,
            'content_type' => $this->content_type,
            'video' => [
                'type' => $this->video_type,
                'url' => $this->video_url,
                'mux_playback_id' => $this->video_mux_playback_id,
                'embed_url' => $this->video_embed_url,
                'has_video' => $this->has_video,
            ],
            'thumbnail_url' => $this->thumbnail_url,
            'display_mode' => $this->display_mode,
            'is_forced' => $this->is_forced,
            'frequency' => $this->frequency,
            'target_platforms' => $this->target_platforms ?? ['web', 'mobile', 'admin'],
            'target_audience' => $this->target_audience,
            'action_button' => [
                'text' => $this->button_text,
                'text_ar' => $this->button_text_ar,
                'text_en' => $this->button_text_en,
                'url' => $this->button_url,
                'has_button' => ! empty($this->button_text) && ! empty($this->button_url),
            ],
            'priority' => (int) $this->priority,
            'is_active' => (bool) $this->is_active,
            'start_at' => $this->start_at?->toIso8601String(),
            'end_at' => $this->end_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
