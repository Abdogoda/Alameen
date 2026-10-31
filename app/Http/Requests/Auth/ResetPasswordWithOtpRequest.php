<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordWithOtpRequest extends FormRequest
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
            'email' => ['required', 'string', 'email'],
            'otp' => ['required_without:token', 'nullable', 'string', 'digits:6'],
            'token' => ['required_without:otp', 'nullable', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::default()],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'otp.required_without' => (string) __('auth.enter_otp'),
            'otp.digits' => (string) __('auth.invalid_otp'),
        ];
    }
}
