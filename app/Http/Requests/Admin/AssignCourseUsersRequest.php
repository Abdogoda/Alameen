<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignCourseUsersRequest extends FormRequest
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
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', Rule::exists('users', 'id')],
            'duration_type' => ['nullable', 'string', Rule::in(['default', 'custom_days', 'custom_date'])],
            'duration_days' => ['nullable', 'integer', 'min:1', 'max:3650'],
            'access_expires_at' => ['nullable', 'date', 'after:today'],
            'sync_mode' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', Rule::in(['active', 'suspended'])],
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
            'user_ids' => __('المستخدمون المحددون'),
            'duration_type' => __('نوع مدة الصلاحية'),
            'duration_days' => __('عدد أيام الصلاحية'),
            'access_expires_at' => __('تاريخ انتهاء الصلاحية'),
            'sync_mode' => __('وضع المزامنة'),
            'status' => __('حالة الاشتراك'),
        ];
    }
}

