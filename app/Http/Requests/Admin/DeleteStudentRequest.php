<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DeleteStudentRequest extends FormRequest
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
            'admin_password' => ['required', 'string', 'current_password:admin'],
            'student_id' => ['nullable', 'integer'],
            'student_name' => ['nullable', 'string'],
            'student_email' => ['nullable', 'string'],
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
            'admin_password' => __('كلمة مرور المشرف'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'admin_password.required' => __('يرجى إدخال كلمة مرور المشرف لتأكيد حذف حساب الطالب.'),
            'admin_password.current_password' => __('كلمة مرور المشرف غير صحيحة. يرجى التأكد وإعادة المحاولة.'),
        ];
    }
}
