<?php

declare(strict_types=1);

namespace App\Http\Requests\Student\Support;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PollMessagesRequest extends FormRequest
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
            'last_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}

