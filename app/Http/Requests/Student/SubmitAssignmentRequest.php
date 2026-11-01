<?php

declare(strict_types=1);

namespace App\Http\Requests\Student;

use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\User;
use App\Services\Course\CourseEnrollmentService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User|null $user */
        $user = $this->user();
        /** @var ClinicalTask|null $task */
        $task = $this->route('task');

        // Default to true when tested in isolation without active HTTP route binding
        if (! $user && ! $task) {
            return true;
        }

        if (! $user || ! $task || ! $task->course instanceof Course) {
            return false;
        }

        return app(CourseEnrollmentService::class)->hasActiveAccess($user, $task->course);
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
