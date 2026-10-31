<?php

declare(strict_types=1);

namespace App\Http\Requests\Student;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAssignmentRequest extends FormRequest
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
            'audio_file' => ['nullable', 'file', 'max:25600'],
            'audio_data' => ['nullable', 'string'],
            'student_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
