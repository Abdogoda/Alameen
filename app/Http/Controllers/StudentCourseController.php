<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Student\RecordProgressRequest;
use App\Http\Requests\Student\SubmitAssignmentRequest;
use App\Http\Requests\Student\SubmitReceiptRequest;
use App\Models\Certificate;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoProgress;
use App\Services\Certificate\CertificateService;
use App\Services\Clinical\ClinicalTaskService;
use App\Services\Course\CourseEnrollmentService;
use App\Services\Payment\PaymentReceiptService;
use App\Services\Video\BunnyStreamService;
use App\Services\Video\MuxTokenService;
use App\Services\Video\VideoProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentCourseController extends Controller
{
    public function __construct(
        protected CourseEnrollmentService $enrollmentService,
        protected VideoProgressService $progressService,
        protected BunnyStreamService $bunnyService,
        protected PaymentReceiptService $receiptService,
        protected CertificateService $certificateService,
        protected ClinicalTaskService $clinicalTaskService,
        protected ?MuxTokenService $muxTokenService = null
    ) {
        $this->muxTokenService = $this->muxTokenService ?: app(MuxTokenService::class);
    }

    /**
     * Display student academic dashboard with live statistics,
     * resume learning hero, and enrolled courses.
     */
    public function dashboard(Request $request): View
    {
        /** @var User|null $user */
        $user = $request->user();

        $enrolledCourses = $user ? $user->courses()
            ->with(['publishedVideos'])
            ->get() : collect();

        $allPublishedCourses = Course::query()
            ->where('is_published', true)
            ->withCount(['videos' => fn ($q) => $q->published()])
            ->orderBy('sort_order')
            ->get();

        $certificates = $user ? $user->certificates()->get()->keyBy('course_id') : collect();
        $taskSubmissionsCount = $user ? $user->taskSubmissions()->count() : 0;

        $myCoursesData = [];
        $totalCompletedLessonsCount = 0;
        $totalCourseVideosCount = 0;
        $lastWatchedOverall = null;
        $lastWatchedCourse = null;
        $lastWatchedVideo = null;

        foreach ($enrolledCourses as $course) {
            $progressPct = $user ? $this->progressService->getCourseProgressPercentage($user, $course) : 0;
            $videos = $course->publishedVideos;
            $totalVideos = $videos->count();
            $totalCourseVideosCount += $totalVideos;
            $videoIds = $videos->pluck('id')->all();

            $completedVideoIds = $user ? VideoProgress::query()
                ->where('user_id', $user->id)
                ->whereIn('video_id', $videoIds)
                ->where('is_completed', true)
                ->pluck('video_id')
                ->all() : [];

            $completedCount = count($completedVideoIds);
            $totalCompletedLessonsCount += $completedCount;

            $lastWatched = $user ? VideoProgress::query()
                ->where('user_id', $user->id)
                ->whereIn('video_id', $videoIds)
                ->orderByDesc('last_watched_at')
                ->first() : null;

            if ($lastWatched) {
                if (! $lastWatchedOverall || ($lastWatched->last_watched_at && $lastWatched->last_watched_at > $lastWatchedOverall->last_watched_at)) {
                    $lastWatchedOverall = $lastWatched;
                    $lastWatchedCourse = $course;
                    $lastWatchedVideo = $videos->firstWhere('id', $lastWatched->video_id);
                }
            }

            $resumeVideo = null;
            if ($lastWatched) {
                $resumeVideo = $videos->firstWhere('id', $lastWatched->video_id);
            }
            if (! $resumeVideo) {
                $resumeVideo = $videos->firstWhere(fn ($v) => ! in_array($v->id, $completedVideoIds, true))
                    ?? $videos->first();
            }

            $cert = $certificates->get($course->id);

            $myCoursesData[] = [
                'course' => $course,
                'progress' => $progressPct,
                'total_lessons' => $totalVideos,
                'completed_lessons' => $completedCount,
                'resume_video' => $resumeVideo,
                'certificate' => $cert,
                'is_eligible_for_cert' => $progressPct >= CertificateService::MIN_PROGRESS_FOR_CERTIFICATE,
                'status' => $course->pivot->status ?? 'active',
                'expires_at' => $course->pivot->access_expires_at ?? null,
            ];
        }

        $isDemo = false;
        if (! $lastWatchedVideo && count($myCoursesData) > 0) {
            $lastWatchedCourse = $myCoursesData[0]['course'];
            $lastWatchedVideo = $myCoursesData[0]['resume_video'];
        } elseif (count($myCoursesData) === 0 && $allPublishedCourses->isNotEmpty()) {
            $lastWatchedCourse = $allPublishedCourses->first();
            $lastWatchedVideo = $lastWatchedCourse->publishedVideos->first();
            $isDemo = true;
        }

        $totalEnrolled = count($myCoursesData);
        $completedCoursesCount = count(array_filter($myCoursesData, fn ($c) => $c['progress'] >= 100));
        $inProgressCoursesCount = count(array_filter($myCoursesData, fn ($c) => $c['progress'] > 0 && $c['progress'] < 100));
        $avgProgress = $totalEnrolled > 0
            ? round(array_sum(array_column($myCoursesData, 'progress')) / $totalEnrolled, 1)
            : 0;

        $stats = [
            'total_enrolled' => $totalEnrolled,
            'completed_courses' => $completedCoursesCount,
            'in_progress_courses' => $inProgressCoursesCount,
            'completed_lessons' => $totalCompletedLessonsCount,
            'total_lessons' => $totalCourseVideosCount,
            'avg_progress' => $avgProgress,
            'certificates_count' => $certificates->count(),
            'tasks_count' => $taskSubmissionsCount,
        ];

        $suggestedCourses = $allPublishedCourses
            ->whereNotIn('id', $enrolledCourses->pluck('id')->all())
            ->take(3);

        return view('web.dashboard', compact(
            'enrolledCourses',
            'myCoursesData',
            'stats',
            'lastWatchedCourse',
            'lastWatchedVideo',
            'lastWatchedOverall',
            'suggestedCourses',
            'isDemo'
        ));
    }

    /**
     * Display courses:
     * - If guest: public marketing catalog (web.pages.courses)
     * - If authenticated student: dashboard my courses & progress workspace (web.pages.my-courses)
     */
    public function index(Request $request): View
    {
        $allCourses = Course::query()
            ->where('is_published', true)
            ->withCount(['videos' => fn ($q) => $q->published()])
            ->orderBy('sort_order')
            ->get();

        /** @var User|null $user */
        $user = $request->user();

        if (! $user) {
            return view('web.pages.courses', compact('allCourses'));
        }

        $enrolledCourses = $user->courses()
            ->with(['publishedVideos'])
            ->get();

        $certificates = $user->certificates()->get()->keyBy('course_id');

        $myCoursesData = [];
        $totalCompletedLessonsCount = 0;

        foreach ($enrolledCourses as $course) {
            $progressPct = $this->progressService->getCourseProgressPercentage($user, $course);
            $videos = $course->publishedVideos;
            $totalVideos = $videos->count();
            $videoIds = $videos->pluck('id')->all();

            $completedVideoIds = VideoProgress::query()
                ->where('user_id', $user->id)
                ->whereIn('video_id', $videoIds)
                ->where('is_completed', true)
                ->pluck('video_id')
                ->all();

            $completedCount = count($completedVideoIds);
            $totalCompletedLessonsCount += $completedCount;

            // Find last watched or next up video to continue
            $lastWatched = VideoProgress::query()
                ->where('user_id', $user->id)
                ->whereIn('video_id', $videoIds)
                ->orderByDesc('last_watched_at')
                ->first();

            $resumeVideo = null;
            if ($lastWatched) {
                $resumeVideo = $videos->firstWhere('id', $lastWatched->video_id);
            }
            if (! $resumeVideo) {
                $resumeVideo = $videos->firstWhere(fn ($v) => ! in_array($v->id, $completedVideoIds, true))
                    ?? $videos->first();
            }

            $cert = $certificates->get($course->id);

            $myCoursesData[] = [
                'course' => $course,
                'progress' => $progressPct,
                'total_lessons' => $totalVideos,
                'completed_lessons' => $completedCount,
                'resume_video' => $resumeVideo,
                'certificate' => $cert,
                'is_eligible_for_cert' => $progressPct >= CertificateService::MIN_PROGRESS_FOR_CERTIFICATE,
                'status' => $course->pivot->status ?? 'active',
                'expires_at' => $course->pivot->access_expires_at ?? null,
            ];
        }

        $totalEnrolled = count($myCoursesData);
        $completedCount = count(array_filter($myCoursesData, fn ($c) => $c['progress'] >= 100));
        $inProgressCount = count(array_filter($myCoursesData, fn ($c) => $c['progress'] > 0 && $c['progress'] < 100));
        $notStartedCount = count(array_filter($myCoursesData, fn ($c) => $c['progress'] == 0));
        $avgProgress = $totalEnrolled > 0
            ? round(array_sum(array_column($myCoursesData, 'progress')) / $totalEnrolled, 1)
            : 0;

        $stats = [
            'total_enrolled' => $totalEnrolled,
            'completed' => $completedCount,
            'in_progress' => $inProgressCount,
            'not_started' => $notStartedCount,
            'completed_lessons' => $totalCompletedLessonsCount,
            'avg_progress' => $avgProgress,
        ];

        $enrolledCourseIds = $enrolledCourses->pluck('id')->all();

        return view('web.pages.my-courses', compact('myCoursesData', 'allCourses', 'stats', 'enrolledCourseIds'));
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
     * View lesson with Sequential Video Locking and Dynamic Watermark Player.
     */
    public function lesson(Request $request, Course|string|null $course = null, Video|string|null $video = null): View|RedirectResponse|JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (is_string($course)) {
            $course = Course::query()->where('slug', $course)->orWhere('id', $course)->firstOrFail();
        }

        // If no course passed, load first active enrolled course or default course
        $course = $course ?? $user->courses()->wherePivot('status', 'active')->first() ?? Course::firstOrFail();

        if (is_string($video)) {
            $video = Video::query()
                ->published()
                ->where('course_id', $course->id)
                ->where(function ($q) use ($video) {
                    $q->where('slug', $video)->orWhere('id', $video);
                })->firstOrFail();
        }

        if (! $video) {
            $curriculumVideos = $this->progressService->getCurriculumVideos($course);
            $completedIds = $this->progressService->getCompletedVideoIds($user, $course);
            $video = $curriculumVideos->first(fn ($v) => ! in_array($v->id, $completedIds, true))
                ?? $curriculumVideos->first()
                ?? $course->publishedVideos()->firstOrFail();
        }

        // Ensure the requested video belongs to the current course and is published
        if ($video->course_id !== $course->id || ! $video->is_published || ($video->level && ! $video->level->is_published)) {
            abort(404, __('هذا الدرس غير متوفر أو غير منشور حالياً.'));
        }

        // Active subscription check: If not free preview and no active course subscription, redirect
        $hasCourseAccess = $this->enrollmentService->hasActiveAccess($user, $course);
        if (! $video->is_free_preview && ! $hasCourseAccess) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('انتهت صلاحية اشتراكك في هذه الدورة أو لم يتم تفعيلها بعد.'),
                    'course_id' => $course->id,
                ], 403);
            }

            return redirect()->route('courses.show', $course->slug)
                ->with('error', __('ليس لديك اشتراك مفعّل في هذه الدورة لمشاهدة هذا الدرس. يرجى الاشتراك للمتابعة.'));
        }

        // 1. Sequential Video & Level Lock Verification
        $canAccess = $this->progressService->canAccessVideo($user, $video);

        // 2. Generate cryptographically signed MUX tokens (RS256) for maximum security
        $playbackToken = null;
        $storyboardToken = null;
        $thumbnailToken = null;

        if ($canAccess && ! empty($video->mux_playback_id)) {
            $playbackToken = $this->muxTokenService->generatePlaybackToken($video->mux_playback_id);
            $storyboardToken = $this->muxTokenService->generateStoryboardToken($video->mux_playback_id);
            $thumbnailToken = $this->muxTokenService->generateThumbnailToken($video->mux_playback_id);
        }

        // 3. Generate secure Bunny.net stream embed URL (legacy fallback if bunny driver)
        $embedUrl = $canAccess ? $this->bunnyService->getIframeEmbedUrl($video) : '';

        // 4. Prepare watermark payload for Anti Screen-Recording Canvas Overlay
        $watermarkPayload = $this->bunnyService->getWatermarkPayload($user, $request->ip());

        // 5. Curriculum and progress status map (batch loaded, 0 N+1)
        $curriculumStatus = $this->progressService->getCurriculumStatus($user, $course);

        $videos = $this->progressService->getCurriculumVideos($course);
        $publishedLevels = $course->publishedLevels()
            ->with(['publishedVideos' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        $currentProgress = $video->progressForUser($user->id);

        return view('web.pages.lesson', compact(
            'course',
            'video',
            'videos',
            'publishedLevels',
            'canAccess',
            'embedUrl',
            'playbackToken',
            'storyboardToken',
            'thumbnailToken',
            'watermarkPayload',
            'currentProgress',
            'curriculumStatus'
        ));
    }

    /**
     * AJAX/API endpoint to generate or refresh a signed MUX playback token for active students.
     */
    public function getPlaybackToken(Request $request, Video $video): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        if (! $user || ! $video->is_published || ($video->level && ! $video->level->is_published) || ! $this->progressService->canAccessVideo($user, $video)) {
            return response()->json([
                'success' => false,
                'message' => __('غير مصرح لك بمشاهدة هذا الفيديو أو انتهت صلاحية اشتراكك.'),
            ], 403);
        }

        if (empty($video->mux_playback_id)) {
            return response()->json([
                'success' => false,
                'message' => __('معرّف البث غير متوفر لهذا الدرس.'),
            ], 404);
        }

        $playbackToken = $this->muxTokenService->generatePlaybackToken($video->mux_playback_id);
        $storyboardToken = $this->muxTokenService->generateStoryboardToken($video->mux_playback_id);

        return response()->json([
            'success' => true,
            'playback_token' => $playbackToken,
            'storyboard_token' => $storyboardToken,
        ]);
    }

    /**
     * AJAX/API endpoint to record video watch progress in real time.
     */
    public function recordProgress(RecordProgressRequest $request, Video $video): JsonResponse
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = $request->user();

        $progress = $this->progressService->recordProgress(
            user: $user,
            video: $video,
            currentWatchedSeconds: (int) $validated['watched_seconds']
        );

        $courseProgress = $this->progressService->getCourseProgressPercentage($user, $video->course);

        // Auto-issue certificate if >= 95% reached!
        $certificate = null;
        if ($courseProgress >= CertificateService::MIN_PROGRESS_FOR_CERTIFICATE) {
            $certificate = $this->certificateService->issueCertificate($user, $video->course);
        }

        return response()->json([
            'success' => true,
            'completion_percentage' => $progress->completion_percentage,
            'is_completed' => $progress->is_completed,
            'course_progress' => $courseProgress,
            'certificate_issued' => $certificate !== null,
        ]);
    }

    /**
     * Public Certificate Verification view by QR code code.
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
    public function assignment(Request $request, ?ClinicalTask $task = null): View
    {
        /** @var User $user */
        $user = $request->user();

        $task = $task ?? ClinicalTask::with('course')->first();

        if ($task && $task->course instanceof Course && ! $this->enrollmentService->hasActiveAccess($user, $task->course)) {
            abort(403, __('لا تملك صلاحية الوصول إلى هذه المهمة السريرية.'));
        }

        $submission = $task ? TaskSubmission::where('task_id', $task->id)->where('user_id', $user->id)->first() : null;

        return view('web.pages.assignment', compact('task', 'submission'));
    }

    /**
     * Submit an audio recording or document for a clinical voice exercise.
     */
    public function submitAssignment(SubmitAssignmentRequest $request, ClinicalTask $task): RedirectResponse
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = $request->user();

        $fileToSubmit = null;
        if ($request->hasFile('audio_file')) {
            $fileToSubmit = $request->file('audio_file');
        } elseif (! empty($validated['audio_data'])) {
            // Base64 recorded blob
            $data = (string) $validated['audio_data'];
            if (preg_match('/^data:audio\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]);
                $data = base64_decode($data);
                $fileName = 'voice_'.$user->id.'_'.time().'.'.($type === 'webm' ? 'webm' : 'wav');
                Storage::disk('public')->put('clinical_audio/'.$fileName, $data);
                $fileToSubmit = 'clinical_audio/'.$fileName;
            }
        }

        if (! $fileToSubmit) {
            return back()->withErrors(['audio_file' => __('يرجى تسجيل صوتك أو رفع ملف صوتي صالح.')]);
        }

        $this->clinicalTaskService->submitRecording(
            user: $user,
            task: $task,
            audioFile: $fileToSubmit,
            studentNotes: $request->input('student_notes')
        );

        return back()->with('success', __('تم تسليم المهمة السريرية بنجاح! بانتظار مراجعة وتقييم المحاضر.'));
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

        $certificates = $user->certificates()
            ->with('course')
            ->orderByDesc('issued_at')
            ->get();

        return view('web.settings.certificates', compact('certificates'));
    }
}
