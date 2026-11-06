<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\RecordProgressRequest;
use App\Http\Requests\Student\ReportViolationRequest;
use App\Http\Resources\Api\V1\ClinicalTaskResource;
use App\Http\Resources\Api\V1\CourseSummaryResource;
use App\Http\Resources\Api\V1\LevelResource;
use App\Http\Resources\Api\V1\TaskSubmissionResource;
use App\Http\Resources\Api\V1\VideoResource;
use App\Models\Course;
use App\Models\Video;
use App\Services\Course\StudentCourseWorkspaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function __construct(
        protected StudentCourseWorkspaceService $workspaceService,
    ) {}

    /**
     * GET /api/v1/student/courses/{slug}/lessons/{lesson?}
     * Return lesson video player data, DRM signed tokens, watermark metadata, curriculum, and clinical task.
     */
    public function show(Request $request, string $slug, ?string $lesson = null): JsonResponse
    {
        $course = Course::query()
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->first();

        if (! $course) {
            return response()->json([
                'success' => false,
                'message' => __('المساق المطلوب غير موجود.'),
            ], 404);
        }

        $video = $this->workspaceService->resolveLessonVideo($request->user(), $course, $lesson);
        $data = $this->workspaceService->getLessonPlaybackData($request->user(), $course, $video, $request->ip());

        if (! $data['canAccess']) {
            return response()->json([
                'success' => false,
                'has_course_access' => $data['hasCourseAccess'],
                'message' => __('يجب إتمام المحاضرات السابقة أولاً أو تفعيل الاشتراك للوصول إلى هذا الدرس.'),
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'course' => new CourseSummaryResource($data['course']),
                'current_video' => new VideoResource($data['video']),
                'levels' => LevelResource::collection($data['publishedLevels']),
                'curriculum_status' => $data['curriculumStatus'],
                'progress_percent' => $data['courseProgressPercentage'],
                'current_progress' => [
                    'watched_seconds' => (int) ($data['currentProgress']?->watched_seconds ?? 0),
                    'completion_percentage' => (float) ($data['currentProgress']?->completion_percentage ?? 0),
                    'is_completed' => (bool) ($data['currentProgress']?->is_completed ?? false),
                ],
                'playback' => [
                    'embed_url' => $data['embedUrl'],
                    'playback_token' => $data['playbackToken'],
                    'storyboard_token' => $data['storyboardToken'],
                    'thumbnail_token' => $data['thumbnailToken'],
                    'watermark' => $data['watermarkPayload'],
                ],
                'clinical_task' => $data['video']->clinicalTask ? new ClinicalTaskResource($data['video']->clinicalTask) : null,
                'task_submission' => $data['taskSubmission'] ? new TaskSubmissionResource($data['taskSubmission']) : null,
            ],
        ]);
    }

    /**
     * POST /api/v1/student/courses/{slug}/lessons/{lesson}/playback-token
     * Refresh short-lived signed MUX playback tokens for the active lesson.
     */
    public function playbackToken(Request $request, string $slug, string $lesson): JsonResponse
    {
        $course = Course::query()
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->first();

        if (! $course) {
            return response()->json([
                'success' => false,
                'message' => __('المساق غير موجود.'),
            ], 404);
        }

        $video = $this->workspaceService->resolveLessonVideo($request->user(), $course, $lesson);
        $result = $this->workspaceService->refreshPlaybackTokens($request->user(), $video);

        return response()->json($result['data'], $result['status']);
    }

    /**
     * POST /api/v1/student/courses/{slug}/lessons/{lesson}/progress
     * Record video watch progress heartbeat and trigger automatic certificate check if course is completed.
     */
    public function recordProgress(RecordProgressRequest $request, string $slug, string $lesson): JsonResponse
    {
        $course = Course::query()
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->first();

        if (! $course) {
            return response()->json([
                'success' => false,
                'message' => __('المساق غير موجود.'),
            ], 404);
        }

        $video = $this->workspaceService->resolveLessonVideo($request->user(), $course, $lesson);
        $validated = $request->validated();

        $result = $this->workspaceService->recordProgressWithSummary(
            $request->user(),
            $video,
            (int) ($validated['watched_seconds'] ?? 0),
            (bool) ($validated['is_ended'] ?? $validated['is_completed'] ?? false),
            isset($validated['actual_duration']) ? (int) $validated['actual_duration'] : null
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * POST /api/v1/student/security/violation
     * Log a client-side DRM/anti-piracy violation (e.g., devtools, screen capture attempt).
     */
    public function reportViolation(ReportViolationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $video = ! empty($validated['video_id']) ? Video::find($validated['video_id']) : null;
        $incidentType = (string) ($validated['type'] ?? $validated['incident_type'] ?? 'screen_recording_attempt');

        $result = $this->workspaceService->reportSecurityViolation(
            $request->user(),
            $video,
            $incidentType,
            $request
        );

        return response()->json([
            'success' => true,
            'logged' => true,
            'data' => $result,
        ]);
    }
}
