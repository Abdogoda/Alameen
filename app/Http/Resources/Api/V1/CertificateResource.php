<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Certificate
 */
class CertificateResource extends JsonResource
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
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'certificate_code' => $this->certificate_code,
            'verification_url' => $this->verification_url,
            'qr_code_svg' => $this->qr_code_svg,
            'file_url' => $this->fileUrl(),
            'file_type' => $this->fileType(),
            'completion_percentage' => (float) ($this->completion_percentage ?? 100),
            'issued_at' => $this->issued_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'course' => $this->whenLoaded('course', fn () => $this->course ? new CourseSummaryResource($this->course) : null),
            'user' => $this->whenLoaded('user', fn () => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : null),
        ];
    }
}
