<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'level_id' => ['nullable', 'exists:levels,id'],
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'min_completion_percentage' => ['nullable', 'integer', 'min:1', 'max:100'],
            'video_source' => ['nullable', 'string', 'max:50'],
            'video_url' => ['nullable', 'string', 'max:1000'],
            'mux_playback_id' => ['nullable', 'string', 'max:255'],
            'mux_asset_id' => ['nullable', 'string', 'max:255'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm,video/x-matroska', 'max:1048576'],
            'is_free_preview' => ['nullable', 'boolean'],
            'task_title_ar' => ['nullable', 'string', 'max:255'],
            'task_instructions_ar' => ['nullable', 'string'],
        ];
    }
}
