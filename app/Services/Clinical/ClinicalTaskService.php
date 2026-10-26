<?php

declare(strict_types=1);

namespace App\Services\Clinical;

use App\Models\Admin;
use App\Models\ClinicalTask;
use App\Models\TaskSubmission;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class ClinicalTaskService
{
    /**
     * Submit an audio recording for a clinical voice exercise.
     */
    public function submitRecording(
        User $user,
        ClinicalTask $task,
        UploadedFile|string $audioFile,
        ?string $studentNotes = null
    ): TaskSubmission {
        $path = '';
        if ($audioFile instanceof UploadedFile) {
            $path = $audioFile->store('clinical_audio', 'public');
        } elseif (is_string($audioFile)) {
            $path = $audioFile;
        }

        return TaskSubmission::updateOrCreate(
            ['task_id' => $task->id, 'user_id' => $user->id],
            [
                'audio_recording_path' => $path,
                'student_notes' => $studentNotes,
                'status' => 'pending',
                'score' => null,
                'clinical_feedback' => null,
            ]
        );
    }

    /**
     * Grade a clinical task voice recording.
     */
    public function gradeSubmission(
        TaskSubmission $submission,
        Admin $admin,
        int $score,
        ?string $feedback = null
    ): TaskSubmission {
        if ($score < 0 || $score > 100) {
            throw ValidationException::withMessages([
                'score' => __('الدرجة يجب أن تكون بين 0 و 100'),
            ]);
        }

        $submission->score = $score;
        $submission->clinical_feedback = $feedback;
        $submission->graded_by = $admin->id;
        $submission->graded_at = Carbon::now();
        $submission->status = 'graded';
        $submission->save();

        return $submission;
    }
}
