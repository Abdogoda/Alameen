<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UpdateContentBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data before validation:
     * Filter out empty file inputs (UPLOAD_ERR_NO_FILE) from media_files array
     * so Laravel's 'file' validation rule does not fail when slots are left empty.
     */
    protected function prepareForValidation(): void
    {
        $rawMedia = $this->files->get('media_files');
        if (is_array($rawMedia)) {
            $cleaned = array_filter($rawMedia, function ($f) {
                return $f instanceof UploadedFile && $f->isValid();
            });

            if (empty($cleaned)) {
                $this->files->remove('media_files');
            } else {
                $this->files->set('media_files', $cleaned);
            }
        }
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
