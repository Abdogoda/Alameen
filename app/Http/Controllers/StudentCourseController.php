<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Student\RecordProgressRequest;
use App\Http\Requests\Student\SubmitAssignmentRequest;
use App\Http\Requests\Student\SubmitReceiptRequest;
use App\Models\Certificate;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use App\Services\Certificate\CertificateService;
use App\Services\Clinical\ClinicalTaskService;
use App\Services\Cms\SettingsService;
use App\Services\Course\CourseEnrollmentService;
use App\Services\Course\StudentCourseWorkspaceService;
use App\Services\Payment\PaymentReceiptService;
use App\Services\Video\BunnyStreamService;
use App\Services\Video\MuxTokenService;
use App\Services\Video\VideoProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StudentCourseController extends Controller
{
    public function __construct(
        protected CourseEnrollmentService $enrollmentService,
        protected VideoProgressService $progressService,
        protected BunnyStreamService $bunnyService,
        protected PaymentReceiptService $receiptService,
        protected CertificateService $certificateService,
        protected ClinicalTaskService $clinicalTaskService,
        protected ?MuxTokenService $muxTokenService = null,
        protected ?StudentCourseWorkspaceService $workspaceService = null
    ) {
        $this->muxTokenService = $this->muxTokenService ?: app(MuxTokenService::class);
        $this->workspaceService = $this->workspaceService ?: app(StudentCourseWorkspaceService::class);
    }

    /**
     * Display student academic dashboard with live statistics,
     * resume learning hero, and enrolled courses.
     */
    public function dashboard(Request $request): View|RedirectResponse
    {
        if (! app(SettingsService::class)->isPageEnabled('student_dashboard')) {
            return redirect()->route('courses.index');
        }

        /** @var User|null $user */
        $user = $request->user();

        $data = $this->workspaceService->getDashboardData($user);

        return view('web.dashboard', $data);
    }

    /**
     * Display courses:
     * - If guest: public marketing catalog (web.pages.courses)
     * - If authenticated student: dashboard my courses & progress workspace (web.pages.my-courses)
     */
    public function index(Request $request): View
    {
        /** @var User|null $user */
        $user = $request->user();

        if (! $user instanceof User) {
            $allCourses = $this->workspaceService->getPublicCatalog();

            return view('web.pages.courses', compact('allCourses'));
        }

        $data = $this->workspaceService->getStudentCoursesData($user);

        return view('web.pages.my-courses', $data);
    }

    /**
     * Submit a manual payment receipt for a course.
     */
    public function submitReceipt(SubmitReceiptRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = $request->user();

        $this->receiptService->submitReceipt($user, $course, [
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount'],
            'sender_account' => $validated['sender_account'] ?? null,
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'receipt_image' => $request->file('receipt_image'),
        ]);

        return back()->with('success', __('تم رفع الإيصال بنجاح. ستتم مراجعته واعتماد وصولك خلال دقائق.'));
    }

    /**
     * Display detailed curriculum, real progress overview, and tasks for a course.
     */
    public function show(Request $request, Course|string|null $course = null): View|RedirectResponse
    {
        if (! auth()->check()) {
            return redirect()->guest(route('login'));
        }

        /** @var User $user */
        $user = $request->user();

        $courseParam = $course ?? $request->query('course');
        $resolvedCourse = $this->workspaceService->resolveCourse($courseParam);

        if (! $resolvedCourse instanceof Course) {
            abort(404, __('لا توجد دورات تدريبية متاحة حالياً.'));
        }

        $data = $this->workspaceService->getCourseDetailsData($resolvedCourse, $user);

        return view('web.pages.course-details', $data);
    }

    /**
     * View lesson with Sequential Video Locking and Dynamic Watermark Player.
     */
    public function lesson(Request $request, Course|string|null $course = null, Video|string|null $video = null): View|RedirectResponse|JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (is_string($course)) {
            $course = Course::query()->where('slug', $course)->orWhere('id', $course)->firstOrFail();
        }

        $course = $course ?? $this->workspaceService->getStudentEnrolledCourses($user)->first() ?? Course::firstOrFail();

        $videoModel = $this->workspaceService->resolveLessonVideo($user, $course, $video);

        if ($videoModel->course_id !== $course->id || ! $videoModel->is_published || ($videoModel->level && ! $videoModel->level->is_published)) {
            abort(404, __('هذا الدرس غير متوفر أو غير منشور حالياً.'));
        }

        $hasCourseAccess = $this->enrollmentService->hasActiveAccess($user, $course);
        if (! $videoModel->is_free_preview && ! $hasCourseAccess) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('انتهت صلاحية اشتراكك في هذه الدورة أو لم يتم تفعيلها بعد.'),
                    'course_id' => $course->id,
                ], 403);
            }

            return redirect()->route('courses.show', $course->slug)
                ->with('error', __('ليس لديك اشتراك مفعّل في هذه الدورة لمشاهدة هذا الدرس. يرجى الاشتراك للمتابعة.'));
        }

        $data = $this->workspaceService->getLessonPlaybackData($user, $course, $videoModel, $request->ip());

        return view('web.pages.lesson', $data);
    }

    /**
     * AJAX/API endpoint to generate or refresh a signed MUX playback token for active students.
     */
    public function getPlaybackToken(Request $request, Video $video): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        $result = $this->workspaceService->refreshPlaybackTokens($user, $video);

        return response()->json($result['data'], $result['status']);
    }

    /**
     * AJAX/API endpoint to record video watch progress in real time.
     */
    public function recordProgress(RecordProgressRequest $request, Video $video): JsonResponse
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = $request->user();

        $isEnded = $request->boolean('is_ended', false);
        $actualDuration = $request->filled('duration') ? (int) round((float) $request->input('duration')) : null;

        $summary = $this->workspaceService->recordProgressWithSummary(
            user: $user,
            video: $video,
            watchedSeconds: (int) round((float) $validated['watched_seconds']),
            isEnded: $isEnded,
            actualDuration: $actualDuration
        );

        return response()->json($summary);
    }

    /**
     * Public Certificate Verification view by QR code.
     */
    public function verifyCertificate(string $code): View
    {
        $certificate = $this->certificateService->verify($code);

        if (! $certificate) {
            abort(404, __('كود التحقق غير صحيح أو لم يتم العثور على الشهادة.'));
        }

        return view('web.pages.certificate-verify', compact('certificate'));
    }

    /**
     * Student Assignment view with interactive voice recorder and submission history.
     */
    public function assignment(Request $request, ?ClinicalTask $task = null): View|RedirectResponse
    {
        if (! app(SettingsService::class)->isPageEnabled('tasks')) {
            return redirect()->route('courses.index');
        }

        /** @var User $user */
        $user = $request->user();

        try {
            $data = $this->clinicalTaskService->getTaskForStudent($user, $task);
        } catch (AccessDeniedHttpException $e) {
            abort(403, $e->getMessage());
        }

        return view('web.pages.assignment', $data);
    }

    /**
     * Submit an audio recording or document for a clinical voice exercise.
     */
    public function submitAssignment(SubmitAssignmentRequest $request, ClinicalTask $task): RedirectResponse
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = $request->user();

        $fileToSubmit = $this->clinicalTaskService->resolveAudioInput(
            audioFile: $request->file('audio_file'),
            base64AudioData: isset($validated['audio_data']) ? (string) $validated['audio_data'] : null,
            userId: $user->id
        );

        if (! $fileToSubmit) {
            return back()->withErrors(['audio_file' => __('يرجى تسجيل صوتك أو رفع ملف صوتي صالح.')]);
        }

        $this->clinicalTaskService->submitRecording(
            user: $user,
            task: $task,
            audioFile: $fileToSubmit,
            studentNotes: $request->input('student_notes')
        );

        return back()->with('success', __('تم تسليم المهمة التدريبية بنجاح! بانتظار مراجعة وتقييم المحاضر.'));
    }

    /**
     * Redirect to profile certificates tab.
     */
    public function certificates(Request $request): RedirectResponse
    {
        return redirect()->route('settings.certificates');
    }

    /**
     * Redirect to profile certificates tab.
     */
    public function certificate(Request $request, ?Certificate $certificate = null): RedirectResponse
    {
        return redirect()->route('settings.certificates');
    }

    /**
     * Display student certificates inside profile settings.
     */
    public function studentCertificates(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $certificates = $this->certificateService->getStudentCertificates($user);

        return view('web.settings.certificates', compact('certificates'));
    }

    /**
     * Ensure default curriculum has all 20 canonical videos populated across the 4 levels.
     */
    protected function ensureCurriculumSeeded(Course $course): void
    {
        $this->workspaceService->ensureCurriculumSeeded($course);
    }

    /**
     * Handle security violation / screen recording incident.
     */
    public function reportViolation(Request $request, ?Video $video = null): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user('web') ?: Auth::user();

        if (! $user) {
            return response()->json([
                'status' => 'unauthenticated',
                'redirect' => route('login'),
            ], 401);
        }

        if (! $video && $request->filled('video_id')) {
            $video = Video::find($request->input('video_id'));
        }

        $incidentType = (string) $request->input('incident_type', 'screen_recording_attempt');
        $response = $this->workspaceService->reportSecurityViolation($user, $video, $incidentType, $request);

        return response()->json($response);
    }
}
