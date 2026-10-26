<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property string $audio_recording_path
 * @property string|null $student_notes
 * @property string $status
 * @property int|null $score
 * @property string|null $clinical_feedback
 * @property int|null $graded_by
 * @property Carbon|null $graded_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'task_id',
    'user_id',
    'audio_recording_path',
    'student_notes',
    'status',
    'score',
    'clinical_feedback',
    'graded_by',
    'graded_at',
])]
class TaskSubmission extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'task_id' => 'integer',
            'user_id' => 'integer',
            'score' => 'integer',
            'graded_by' => 'integer',
            'graded_at' => 'datetime',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(ClinicalTask::class, 'task_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'graded_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isGraded(): bool
    {
        return $this->status === 'graded';
    }
}
