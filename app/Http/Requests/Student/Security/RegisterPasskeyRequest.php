<?php

declare(strict_types=1);

namespace App\Http\Requests\Student\Security;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterPasskeyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'credential' => ['required', 'array'],
            'challenge_id' => ['required', 'string'],
        ];
    }
}

