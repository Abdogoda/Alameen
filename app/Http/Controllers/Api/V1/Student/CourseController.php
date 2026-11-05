<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\SubmitPaymentReceiptRequest;
use App\Http\Resources\Api\V1\CertificateResource;
use App\Http\Resources\Api\V1\CourseSummaryResource;
use App\Http\Resources\Api\V1\LevelResource;
use App\Http\Resources\Api\V1\PaymentReceiptResource;
use App\Http\Resources\Api\V1\VideoResource;
use App\Models\Course;
use App\Services\Course\StudentCourseWorkspaceService;
use App\Services\Payment\PaymentReceiptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        protected StudentCourseWorkspaceService $workspaceService,
        protected PaymentReceiptService $paymentReceiptService,
    ) {}

    /**
     * GET /api/v1/student/courses
     * Return student's enrolled courses with progress, stats, and all available catalog courses.
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->workspaceService->getStudentCoursesData($request->user());

        $myCourses = array_map(fn (array $item) => [
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
                'enrolled_course_ids' => $data['enrolledCourseIds'],
                'enrolled_courses' => $myCourses,
                'all_courses' => CourseSummaryResource::collection($data['allCourses']),
            ],
        ]);
    }

    /**
     * GET /api/v1/student/courses/{slug}
     * Return full course curriculum, student progress, access state, and certificate for the student.
     */
    public function show(Request $request, string $slug): JsonResponse
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

        $details = $this->workspaceService->getCourseDetailsData($course, $request->user());

        return response()->json([
            'success' => true,
            'data' => [
                'course' => new CourseSummaryResource($details['course']),
                'has_enrolled' => $details['hasActiveAccess'],
                'pending_receipt' => $details['pendingReceipt'] ? new PaymentReceiptResource($details['pendingReceipt']) : null,
                'completed_video_ids' => $details['completedVideoIds'],
                'accessible_video_ids' => $details['accessibleVideoIds'],
                'total_videos' => $details['totalVideosCount'],
                'completed_count' => $details['completedVideosCount'],
                'progress_percent' => $details['courseProgressPercentage'],
                'resume_video' => $details['resumeVideo'] ? new VideoResource($details['resumeVideo']) : null,
                'levels' => LevelResource::collection($details['levels']),
                'unleveled_videos' => VideoResource::collection($details['standaloneVideos']),
                'tasks_summary' => [
                    'total' => $details['totalTasksCount'],
                    'graded' => $details['gradedTasksCount'],
                    'pending' => $details['pendingTasksCount'],
                    'available' => $details['availableTasksCount'],
                    'locked' => $details['lockedTasksCount'],
                ],
            ],
        ]);
    }

    /**
     * POST /api/v1/student/courses/{slug}/receipt
     * Submit a manual payment receipt (Vodafone Cash / InstaPay) for a course.
     */
    public function submitReceipt(SubmitPaymentReceiptRequest $request, string $slug): JsonResponse
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

        $payload = $request->validated();
        if ($request->hasFile('receipt_image')) {
            $payload['receipt_image'] = $request->file('receipt_image');
        }

        $receipt = $this->paymentReceiptService->submitReceipt(
            $request->user(),
            $course,
            $payload
        );

        $receipt->loadMissing('course');

        return response()->json([
            'success' => true,
            'message' => __('تم إرسال إيصال الدفع بنجاح! سيتم مراجعته وتفعيل اشتراكك في أقرب وقت.'),
            'data' => [
                'receipt' => new PaymentReceiptResource($receipt),
            ],
        ], 201);
    }

    /**
     * GET /api/v1/student/receipts
     * Return all payment receipts submitted by the authenticated student.
     */
    public function receipts(Request $request): JsonResponse
    {
        $courseId = $request->filled('course_id') ? (int) $request->query('course_id') : null;
        $receipts = $this->paymentReceiptService->getStudentReceipts($request->user(), $courseId);

        return response()->json([
            'success' => true,
            'data' => [
                'receipts' => PaymentReceiptResource::collection($receipts),
            ],
        ]);
    }
}
