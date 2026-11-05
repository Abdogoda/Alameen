<?php

declare(strict_types=1);

namespace App\Services\Clinical;

use App\Models\Admin;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Services\Course\CourseEnrollmentService;
use App\Services\Video\VideoProgressService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ClinicalTaskService
{
    public function __construct(
        protected ?CourseEnrollmentService $enrollmentService = null,
        protected ?VideoProgressService $progressService = null
    ) {
        $this->enrollmentService = $this->enrollmentService ?: app(CourseEnrollmentService::class);
        $this->progressService = $this->progressService ?: app(VideoProgressService::class);
    }

    /**
     * Resolve uploaded audio file or decode base64 recorded blob into public storage.
     */
    public function resolveAudioInput(
        ?UploadedFile $audioFile,
        ?string $base64AudioData,
        int $userId
    ): UploadedFile|string|null {
        if ($audioFile instanceof UploadedFile) {
            return $audioFile;
        }

        if (! empty($base64AudioData) && preg_match('/^data:audio\/(\w+);base64,/', $base64AudioData, $type)) {
            $data = substr($base64AudioData, strpos($base64AudioData, ',') + 1);
            $ext = strtolower($type[1]) === 'webm' ? 'webm' : 'wav';
            $decoded = base64_decode($data);
            if ($decoded !== false) {
                $fileName = 'voice_'.$userId.'_'.time().'.'.$ext;
                Storage::disk('public')->put('clinical_audio/'.$fileName, $decoded);

                return 'clinical_audio/'.$fileName;
            }
        }

        return null;
    }

    /**
     * Get clinical task and student's latest submission with access verification.
     *
     * @return array{task: ClinicalTask|null, submission: TaskSubmission|null}
     */
    public function getTaskForStudent(User $user, ?ClinicalTask $task = null): array
    {
        $task = $task ?? ClinicalTask::with(['course', 'video'])->first();

        if ($task && $task->course instanceof Course && ! $this->enrollmentService->hasActiveAccess($user, $task->course)) {
            throw new AccessDeniedHttpException(__('لا تملك صلاحية الوصول إلى هذه المهمة التدريبية.'));
        }

        $submission = $task ? TaskSubmission::where('task_id', $task->id)->where('user_id', $user->id)->first() : null;

        return [
            'task' => $task,
            'submission' => $submission,
        ];
    }

    /**
     * Get all clinical tasks across student's enrolled courses with lock and submission status.
     *
     * @return array<string, mixed>
     */
    public function getStudentGradesOverview(User $user, ?int $courseId = null): array
    {
        $enrolledCoursesQuery = $user->courses()->wherePivot('status', 'active');
        if ($courseId) {
            $enrolledCoursesQuery->where('courses.id', $courseId);
        }

        $enrolledCourses = $enrolledCoursesQuery->get();
        if ($enrolledCourses->isEmpty()) {
            $fallbackCourse = $courseId ? Course::find($courseId) : Course::where('is_published', true)->orderBy('sort_order')->first();
            $enrolledCourses = $fallbackCourse ? collect([$fallbackCourse]) : collect();
        }

        $courseIds = $enrolledCourses->pluck('id')->all();

        $tasks = ClinicalTask::query()
            ->whereIn('course_id', $courseIds)
            ->with(['course', 'video.level'])
            ->orderBy('id')
            ->get();

        $submissions = TaskSubmission::query()
            ->where('user_id', $user->id)
            ->whereIn('task_id', $tasks->pluck('id')->all())
            ->with('grader')
            ->get()
            ->keyBy('task_id');

        $completedVideoIdsByCourse = [];
        foreach ($enrolledCourses as $course) {
            $completedVideoIdsByCourse[$course->id] = $this->progressService->getCompletedVideoIds($user, $course);
        }

        $gradedScores = [];
        $gradedCount = 0;
        $pendingCount = 0;
        $availableCount = 0;
        $lockedCount = 0;
        $items = [];

        foreach ($tasks as $task) {
            /** @var TaskSubmission|null $sub */
            $sub = $submissions->get($task->id);
            $completedVideoIds = $completedVideoIdsByCourse[$task->course_id] ?? [];
            $isUnlocked = ! $task->video_id || in_array($task->video_id, $completedVideoIds, true);

            if ($sub) {
                if ($sub->status === 'graded') {
                    $gradedCount++;
                    if ($sub->score !== null) {
                        $gradedScores[] = (int) $sub->score;
                    }
                } else {
                    $pendingCount++;
                }
            } elseif ($isUnlocked) {
                $availableCount++;
            } else {
                $lockedCount++;
            }

            $items[] = [
                'task' => $task,
                'submission' => $sub,
                'is_unlocked' => $isUnlocked,
                'status' => $sub ? $sub->status : ($isUnlocked ? 'available' : 'locked'),
            ];
        }

        $averageScore = count($gradedScores) > 0
            ? round(array_sum($gradedScores) / count($gradedScores), 1)
            : 0.0;

        return [
            'courses' => $enrolledCourses,
            'summary' => [
                'total_tasks' => $tasks->count(),
                'submissions_count' => $submissions->count(),
                'graded_count' => $gradedCount,
                'pending_count' => $pendingCount,
                'available_count' => $availableCount,
                'locked_count' => $lockedCount,
                'average_score' => $averageScore,
            ],
            'items' => $items,
        ];
    }

    /**
     * Get detailed grading result for a specific submission belonging to the student.
     */
    public function getSubmissionResultForStudent(User $user, TaskSubmission $submission): TaskSubmission
    {
        if ($submission->user_id !== $user->id) {
            throw new AccessDeniedHttpException(__('غير مصرح لك بالوصول إلى نتيجة هذه المهمة.'));
        }

        return $submission->load(['task.course', 'task.video.level', 'grader']);
    }

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
