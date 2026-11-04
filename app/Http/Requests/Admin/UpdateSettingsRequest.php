<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
            'settings' => ['nullable', 'array'],
            'settings.*.ar' => ['nullable', 'string'],
            'settings.*.en' => ['nullable', 'string'],
            'settings.*.group' => ['nullable', 'string', 'max:50'],
            'site_logo_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'site_logo_white_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'footer_logo_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'auth_login_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'certificate_default_template' => ['nullable', 'string', 'in:royal_gold,clinical_emerald,modern_navy,custom'],
            'certificate_custom_bg_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'certificate_stamp_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'certificate_signatory_name' => ['nullable', 'string', 'max:255'],
            'certificate_signatory_title' => ['nullable', 'string', 'max:255'],
        ];
    }
}
