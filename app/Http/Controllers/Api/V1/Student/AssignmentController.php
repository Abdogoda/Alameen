<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\SubmitTaskVoiceRequest;
use App\Http\Resources\Api\V1\ClinicalTaskResource;
use App\Http\Resources\Api\V1\CourseSummaryResource;
use App\Http\Resources\Api\V1\TaskSubmissionResource;
use App\Models\ClinicalTask;
use App\Models\TaskSubmission;
use App\Services\Clinical\ClinicalTaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function __construct(
        protected ClinicalTaskService $clinicalTaskService,
    ) {}

    /**
     * GET /api/v1/student/assignments
     * Return all clinical tasks for the student's enrolled courses along with their lock and submission status.
     */
    public function index(Request $request): JsonResponse
    {
        $courseId = $request->filled('course_id') ? (int) $request->query('course_id') : null;
        $data = $this->clinicalTaskService->getStudentGradesOverview($request->user(), $courseId);

        $items = array_map(fn (array $item) => [
            'task' => new ClinicalTaskResource($item['task']),
            'submission' => $item['submission'] ? new TaskSubmissionResource($item['submission']) : null,
            'is_unlocked' => $item['is_unlocked'],
            'status' => $item['status'],
        ], $data['items']);

        return response()->json([
            'success' => true,
            'data' => [
                'courses' => CourseSummaryResource::collection($data['courses']),
                'summary' => $data['summary'],
                'items' => $items,
            ],
        ]);
    }

    /**
     * GET /api/v1/student/assignments/{task}
     * Return details of a specific clinical task and the student's current submission.
     */
    public function show(Request $request, ClinicalTask $task): JsonResponse
    {
        $data = $this->clinicalTaskService->getTaskForStudent($request->user(), $task);

        return response()->json([
            'success' => true,
            'data' => [
                'task' => $data['task'] ? new ClinicalTaskResource($data['task']) : null,
                'submission' => $data['submission'] ? new TaskSubmissionResource($data['submission']) : null,
            ],
        ]);
    }

    /**
     * POST /api/v1/student/assignments/{task}/submit
     * Submit a voice recording (multipart audio file or base64 audio) and notes for a clinical task.
     */
    public function submit(SubmitTaskVoiceRequest $request, ClinicalTask $task): JsonResponse
    {
        $this->clinicalTaskService->getTaskForStudent($request->user(), $task);

        $validated = $request->validated();
        $audioInput = $this->clinicalTaskService->resolveAudioInput(
            $request->file('audio_file'),
            $validated['audio_data'] ?? $validated['audio_base64'] ?? null,
            $request->user()->id
        );

        if (! $audioInput) {
            return response()->json([
                'success' => false,
                'message' => __('يرجى إرفاق ملف التسجيل الصوتي.'),
            ], 422);
        }

        $submission = $this->clinicalTaskService->submitRecording(
            $request->user(),
            $task,
            $audioInput,
            $validated['student_notes'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => __('تم رفع التسجيل الصوتي للتكليف السريري بنجاح! سيتم تقييمه من قبل المحاضر قريباً.'),
            'data' => [
                'submission' => new TaskSubmissionResource($submission),
            ],
        ], 201);
    }

    /**
     * GET /api/v1/student/grades
     * Return student grades and evaluation summary across all clinical task submissions.
     */
    public function grades(Request $request): JsonResponse
    {
        $courseId = $request->filled('course_id') ? (int) $request->query('course_id') : null;
        $data = $this->clinicalTaskService->getStudentGradesOverview($request->user(), $courseId);

        $items = array_map(fn (array $item) => [
            'task' => new ClinicalTaskResource($item['task']),
            'submission' => $item['submission'] ? new TaskSubmissionResource($item['submission']) : null,
            'is_unlocked' => $item['is_unlocked'],
            'status' => $item['status'],
        ], $data['items']);

        return response()->json([
            'success' => true,
            'data' => [
                'courses' => CourseSummaryResource::collection($data['courses']),
                'summary' => $data['summary'],
                'items' => $items,
            ],
        ]);
    }

    /**
     * GET /api/v1/student/submissions/{submission}
     * Return detailed evaluation and instructor feedback for a specific clinical task submission.
     */
    public function submissionResult(Request $request, TaskSubmission $submission): JsonResponse
    {
        $record = $this->clinicalTaskService->getSubmissionResultForStudent($request->user(), $submission);

        return response()->json([
            'success' => true,
            'data' => [
                'submission' => new TaskSubmissionResource($record),
            ],
        ]);
    }
}
