<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApplyVideoSettingsRequest extends FormRequest
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
            'scope' => ['required', 'string', 'in:specific,bulk,level,course'],
            'video_ids' => ['nullable', 'array'],
            'video_ids.*' => ['integer', 'exists:videos,id'],
            'level_id' => ['nullable', 'integer', 'exists:levels,id'],
            'target_level_id' => ['nullable', 'integer', 'exists:levels,id'],
            'bulk_action' => ['nullable', 'string', 'in:update_settings,move_level,delete'],
            'min_completion_percentage' => ['nullable', 'integer', 'min:1', 'max:100'],
            'free_preview_policy' => ['nullable', 'string', 'in:keep,enable_all,disable_all,first_only'],
        ];
    }
}
