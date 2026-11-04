<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateContentBlockRequest extends FormRequest
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
            'title_ar' => ['nullable', 'string', 'max:500'],
            'title_en' => ['nullable', 'string', 'max:500'],
            'subtitle_ar' => ['nullable', 'string', 'max:1500'],
            'subtitle_en' => ['nullable', 'string', 'max:1500'],
            'content_ar' => ['nullable', 'string'],
            'content_en' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
            'media_files' => ['nullable', 'array'],
            'media_files.*' => ['nullable', 'file', 'max:51200'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
