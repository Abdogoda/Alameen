<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TaskSubmission
 */
class TaskSubmissionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $audioUrl = null;
        if (! empty($this->audio_recording_path)) {
            $audioUrl = str_starts_with($this->audio_recording_path, 'http')
                ? $this->audio_recording_path
                : asset('storage/'.ltrim($this->audio_recording_path, '/'));
        }

        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'user_id' => $this->user_id,
            'audio_recording_path' => $this->audio_recording_path,
            'audio_recording_url' => $audioUrl,
            'student_notes' => $this->student_notes,
            'status' => $this->status,
            'score' => $this->score !== null ? (int) $this->score : null,
            'clinical_feedback' => $this->clinical_feedback,
            'graded_by' => $this->graded_by,
            'grader_name' => $this->whenLoaded('grader', fn () => $this->grader?->name),
            'graded_at' => $this->graded_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'task' => $this->whenLoaded('task', fn () => $this->task ? new ClinicalTaskResource($this->task) : null),
        ];
    }
}

