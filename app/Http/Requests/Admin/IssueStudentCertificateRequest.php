<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IssueStudentCertificateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => ['required', 'integer', Rule::exists('courses', 'id')],
            'certificate_file' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg,webp', 'max:20480'],
            'completion_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'issued_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'course_id' => __('الكورس'),
            'certificate_file' => __('ملف الشهادة (صورة أو PDF)'),
            'completion_percentage' => __('نسبة الإنجاز المعتمدة'),
            'issued_at' => __('تاريخ الإصدار والاعتماد'),
        ];
    }
}
