<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class UpdateContentBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data before validation:
     * Filter out empty file inputs (UPLOAD_ERR_NO_FILE) from media_files array
     * while preserving slot keys (e.g. slot 7) so Laravel validates and the controller
     * maps the uploaded file to the exact slot index.
     */
    protected function prepareForValidation(): void
    {
        $rawMedia = $this->files->get('media_files');
        if (is_array($rawMedia)) {
            $cleaned = [];
            foreach ($rawMedia as $idx => $f) {
                if ($f instanceof SymfonyUploadedFile && $f->getError() !== \UPLOAD_ERR_NO_FILE) {
                    $cleaned[$idx] = $f;
                }
            }

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

    /**
     * Custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'media_files.*.uploaded' => __('فشل رفع الملف. قد يكون حجم الملف كبيراً جداً (يرجى التأكد من أن حجم الملف لا يتجاوز الحد المسموح به في السيرفر).'),
            'media_files.*.max' => __('حجم الملف المرفوع لا يمكن أن يتجاوز 50 ميجابايت.'),
            'media_files.*.file' => __('الملف المرفوع غير صالح أو تالف.'),
        ];
    }
}
