<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateAdminRequest extends FormRequest
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
        /** @var Admin|int|string|null $targetAdmin */
        $targetAdmin = $this->route('admin');
        $targetId = $targetAdmin instanceof Admin ? $targetAdmin->id : $targetAdmin;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($targetId),
            ],
            'password' => ['nullable', 'string', Password::defaults()],
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
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
            'name' => __('اسم المشرف'),
            'email' => __('البريد الإلكتروني'),
            'password' => __('كلمة المرور الجديدة'),
            'role_id' => __('الدور الوظيفي'),
            'permissions' => __('الصلاحيات الإضافية'),
        ];
    }
}
