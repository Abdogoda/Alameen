<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:courses,slug'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'access_duration_days' => ['required', 'integer', 'min:1'],
            'thumbnail_file' => ['nullable', 'image', 'max:4096'],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'promo_video_source' => ['nullable', 'string', 'in:mux,link,upload,youtube,vimeo'],
            'promo_video_url' => ['nullable', 'string', 'max:1000'],
            'promo_video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm,video/x-matroska', 'max:512000'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }
}
