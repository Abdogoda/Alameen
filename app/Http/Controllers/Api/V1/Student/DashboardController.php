<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CertificateResource;
use App\Http\Resources\Api\V1\CourseSummaryResource;
use App\Http\Resources\Api\V1\VideoResource;
use App\Services\Course\StudentCourseWorkspaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected StudentCourseWorkspaceService $workspaceService,
    ) {}

    /**
     * GET /api/v1/student/dashboard
     * Return student dashboard overview: enrolled courses with progress, stats, resume lesson, and suggested courses.
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->workspaceService->getDashboardData($request->user());

        $enrolledItems = array_map(fn (array $item) => [
            'course' => new CourseSummaryResource($item['course']),
            'progress' => $item['progress'],
            'total_lessons' => $item['total_lessons'],
            'completed_lessons' => $item['completed_lessons'],
            'resume_video' => $item['resume_video'] ? new VideoResource($item['resume_video']) : null,
            'certificate' => $item['certificate'] ? new CertificateResource($item['certificate']) : null,
            'is_eligible_for_cert' => $item['is_eligible_for_cert'],
            'status' => $item['status'],
            'expires_at' => $item['expires_at'],
        ], $data['myCoursesData']);

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $data['stats'],
                'enrolled_courses' => $enrolledItems,
                'suggested_courses' => CourseSummaryResource::collection($data['suggestedCourses']),
                'resume_course' => $data['lastWatchedCourse'] ? new CourseSummaryResource($data['lastWatchedCourse']) : null,
                'resume_video' => $data['lastWatchedVideo'] ? new VideoResource($data['lastWatchedVideo']) : null,
                'is_demo' => $data['isDemo'],
            ],
        ]);
    }
}
