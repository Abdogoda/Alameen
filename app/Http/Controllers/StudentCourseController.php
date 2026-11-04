<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Student\RecordProgressRequest;
use App\Http\Requests\Student\SubmitAssignmentRequest;
use App\Http\Requests\Student\SubmitReceiptRequest;
use App\Models\Certificate;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\Level;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoProgress;
use App\Services\Certificate\CertificateService;
use App\Services\Clinical\ClinicalTaskService;
use App\Services\Cms\SettingsService;
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
    public function dashboard(Request $request): View|RedirectResponse
    {
        if (! app(SettingsService::class)->isPageEnabled('student_dashboard')) {
            return redirect()->route('courses.index');
        }

        /** @var User|null $user */
        $user = $request->user();

        if ($defaultCourse = Course::first()) {
            $this->ensureCurriculumSeeded($defaultCourse);
        }

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
        if ($defaultCourse = Course::first()) {
            $this->ensureCurriculumSeeded($defaultCourse);
        }

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

        $this->ensureCurriculumSeeded($course);

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
    public function assignment(Request $request, ?ClinicalTask $task = null): View|RedirectResponse
    {
        if (! app(SettingsService::class)->isPageEnabled('tasks')) {
            return redirect()->route('courses.index');
        }

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

    /**
     * Ensure default curriculum has all 20 canonical videos populated across the 4 levels.
     */
    protected function ensureCurriculumSeeded(Course $course): void
    {
        if ($course->videos()->count() >= 20) {
            return;
        }

        $level1 = Level::query()->firstOrCreate(
            ['course_id' => $course->id, 'sort_order' => 1],
            [
                'title_ar' => 'المستوى الأول: مدخل إلى علوم التخاطب والتواصل',
                'title_en' => 'Level 1: Introduction to Speech & Communication Sciences',
                'description_ar' => 'أساسيات عملية التواصل، التشريح الصوتي، وتصنيف اضطرابات النطق',
                'description_en' => 'Fundamentals of communication, vocal anatomy, and articulation classification.',
                'is_published' => true,
            ]
        );

        $level2 = Level::query()->firstOrCreate(
            ['course_id' => $course->id, 'sort_order' => 2],
            [
                'title_ar' => 'المستوى الثاني: مهارات الصوت والاستماع والتمييز',
                'title_en' => 'Level 2: Vocal Skills, Auditory Listening & Discrimination',
                'description_ar' => 'الوعي الصوتي، التمييز السمعي للأصوات، وبناء الأنشطة المنزلية',
                'description_en' => 'Phonological awareness, auditory discrimination, and home activity design.',
                'is_published' => true,
            ]
        );

        $level3 = Level::query()->firstOrCreate(
            ['course_id' => $course->id, 'sort_order' => 3],
            [
                'title_ar' => 'المستوى الثالث: مخارج الحروف وتصحيح اللدغات والتشويه',
                'title_en' => 'Level 3: Articulation Points & Lisp Correction Protocols',
                'description_ar' => 'تصحيح نطق الراء والسين والكاف والتمارين العضلية المصاحبة',
                'description_en' => 'Articulatory corrections for /r/, /s/, /k/ sounds and accompanying motor drills.',
                'is_published' => true,
            ]
        );

        $level4 = Level::query()->firstOrCreate(
            ['course_id' => $course->id, 'sort_order' => 4],
            [
                'title_ar' => 'المستوى الرابع: التطبيقات العملية ودراسة الحالات والتقييم النهائي',
                'title_en' => 'Level 4: Practical Applications, Case Studies & Final Evaluation',
                'description_ar' => 'دراسة حالات واقعية، كتابة الخطة العلاجية الفردية، وتأهيل شهادة الـ ٩٥٪',
                'description_en' => 'Real-world case analysis, individualized treatment planning, and 95% qualification.',
                'is_published' => true,
            ]
        );

        $allVideosData = [
            // Level 1 Lessons (1 to 5)
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ١: مدخل إلى عملية التواصل البشري',
                'title_en' => 'Lesson 1: Introduction to Human Communication Process',
                'slug' => 'intro-human-communication',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-01',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 1,
                'is_free_preview' => true,
                'is_published' => true,
                'task_title' => 'مخطط أركان التواصل',
            ],
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ٢: مكونات عملية التواصل وعناصر الرسالة',
                'title_en' => 'Lesson 2: Communication Components & Message Channels',
                'slug' => 'communication-components-message',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-02',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 2,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'تحليل تشويش الرسالة',
            ],
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ٣: مراحل النمو اللغوي عند الأطفال',
                'title_en' => 'Lesson 3: Child Language Development Stages',
                'slug' => 'child-language-development-stages',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-03',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 3,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'جدول مقارنة المعالم اللغوية',
            ],
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ٤: بيئة التعلّم الداعمة وتصنيف اضطرابات النطق',
                'title_en' => 'Lesson 4: Supportive Learning Environment & Speech Disorders',
                'slug' => 'supportive-learning-environment',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-04',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 4,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'استمارة تجهيز بيئة الجلسة',
            ],
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ٥: مراجعة المفاهيم الأساسية للتخاطب',
                'title_en' => 'Lesson 5: Review of Core Concepts in Speech Pathology',
                'slug' => 'foundational-concepts-review',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-05',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 5,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'ملخص مراجعة المستوى الأول',
            ],

            // Level 2 Lessons (6 to 10)
            [
                'level_id' => $level2->id,
                'title_ar' => 'الدرس ٦: تشريح الجهاز الصوتي والتنفس ودعم الصوت',
                'title_en' => 'Lesson 6: Vocal Anatomy, Respiration & Breath Support',
                'slug' => 'vocal-anatomy-breath-support',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-06',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 6,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'تمرين التحكم في النفس الصوتي',
            ],
            [
                'level_id' => $level2->id,
                'title_ar' => 'الدرس ٧: آليات إنتاج الصوت والإيقاع وسرعة الكلام',
                'title_en' => 'Lesson 7: Sound Production, Rhythm & Speech Rate Mechanisms',
                'slug' => 'speech-rate-rhythm-mechanisms',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-07',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 7,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'مقياس ضبط إيقاع الكلام',
            ],
            [
                'level_id' => $level2->id,
                'title_ar' => 'الدرس ٨: مهارات التمييز السمعي للأصوات المتشابهة',
                'title_en' => 'Lesson 8: Auditory Discrimination for Minimal Word Pairs',
                'slug' => 'auditory-discrimination-minimal-pairs',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-08',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 8,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'ورقة نشاط التمييز الصوتي (٦ أزواج)',
            ],
            [
                'level_id' => $level2->id,
                'title_ar' => 'الدرس ٩: تدريبات الوعي الصوتي والتقطيع',
                'title_en' => 'Lesson 9: Phonological Awareness & Syllable Segmentation',
                'slug' => 'phonological-awareness-segmentation',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-09',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 85,
                'sort_order' => 9,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'استراتيجية التقطيع الصوتي للكلمات',
            ],
            [
                'level_id' => $level2->id,
                'title_ar' => 'الدرس ١٠: تقنيات تعديل السلوك ومراجعة المهارات الصوتية',
                'title_en' => 'Lesson 10: Behavior Modification & Voice Skills Review',
                'slug' => 'behavior-modification-vocal-review',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-10',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 10,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'خطة تعزيز وتعديل السلوك بالجلسة',
            ],

            // Level 3 Lessons (11 to 15)
            [
                'level_id' => $level3->id,
                'title_ar' => 'الدرس ١١: الانتباه المشترك وتدريبات مخرج حرف الراء',
                'title_en' => 'Lesson 11: Joint Attention & /r/ Sound Articulation Drills',
                'slug' => 'joint-attention-r-sound',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-11',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 11,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'جدول تدرج تصحيح صوت الراء',
            ],
            [
                'level_id' => $level3->id,
                'title_ar' => 'الدرس ١٢: التقليد اللفظي وتصحيح لدغة السين',
                'title_en' => 'Lesson 12: Verbal Imitation & /s/ Lisp Correction Protocols',
                'slug' => 'verbal-imitation-s-lisp-correction',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-12',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 12,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'استمارة تقييم اللدغة السينّية',
            ],
            [
                'level_id' => $level3->id,
                'title_ar' => 'الدرس ١٣: توسيع المفردات وعلاج إبدال الكاف والتاء',
                'title_en' => 'Lesson 13: Vocabulary Expansion & /k/ to /t/ Substitution Therapy',
                'slug' => 'vocabulary-expansion-k-t-substitution',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-13',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 13,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'بطاقات التمييز بين الكاف والتاء',
            ],
            [
                'level_id' => $level3->id,
                'title_ar' => 'الدرس ١٤: بناء الجملة البسيطة والتمارين الحركية لأعضاء النطق',
                'title_en' => 'Lesson 14: Simple Sentence Construction & Oral-Motor Drills (OMEs)',
                'slug' => 'simple-sentence-oral-motor-drills',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-14',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 14,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'بروتوكول التمارين العضلية للفم',
            ],
            [
                'level_id' => $level3->id,
                'title_ar' => 'الدرس ١٥: توظيف الأسئلة وبروتوكول التثبيت في الكلام العفوي',
                'title_en' => 'Lesson 15: Question Usage & Generalization to Spontaneous Speech',
                'slug' => 'question-usage-spontaneous-speech',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-15',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 15,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'مصفوفة قياس الكلام التلقائي',
            ],

            // Level 4 Lessons (16 to 20)
            [
                'level_id' => $level4->id,
                'title_ar' => 'الدرس ١٦: خطة التقييم واختيار أنشطة التدريب ودراسة الحالة',
                'title_en' => 'Lesson 16: Assessment Plan, Training Activity Selection & Case Study',
                'slug' => 'assessment-plan-case-study',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-16',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 16,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'نموذج دراسة الحالة والتشخيص الأولي',
            ],
            [
                'level_id' => $level4->id,
                'title_ar' => 'الدرس ١٧: تصميم الخطة العلاجية الفردية (IEP) وتدرج صعوبة النشاط',
                'title_en' => 'Lesson 17: Individualized Treatment Plan (IEP) & Difficulty Progression',
                'slug' => 'iep-individual-treatment-plan',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-17',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 17,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'صياغة خطة علاجية فردية متكاملة',
            ],
            [
                'level_id' => $level4->id,
                'title_ar' => 'الدرس ١٨: استراتيجيات الإرشاد الأسري والمتابعة المنزلية وتسجيل الملاحظات',
                'title_en' => 'Lesson 18: Family Counseling Strategies & Clinical Observation Notes',
                'slug' => 'family-counseling-observation-notes',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-18',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 18,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'دليل الإرشاد والمتابعة المنزلية للأسرة',
            ],
            [
                'level_id' => $level4->id,
                'title_ar' => 'الدرس ١٩: ورشة المحاكاة العملية ومراجعة التقدم للحالات الواقعية',
                'title_en' => 'Lesson 19: Clinical Simulation Workshop & Progress Review',
                'slug' => 'simulation-workshop-progress-review',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-19',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 19,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'تقرير تحليل جلسة محاكاة سريرية',
            ],
            [
                'level_id' => $level4->id,
                'title_ar' => 'الدرس ٢٠: الاختبار النهائي والتقييم الختامي لتأهيل الاعتماد',
                'title_en' => 'Lesson 20: Final Comprehensive Evaluation & 95% Certification Exam',
                'slug' => 'final-evaluation-certification-exam',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-20',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 20,
                'is_free_preview' => false,
                'is_published' => true,
                'task_title' => 'مشروع التخرج والتقييم الختامي الشامل',
            ],
        ];

        foreach ($allVideosData as $vData) {
            $taskTitle = $vData['task_title'];
            unset($vData['task_title']);

            $videoModel = Video::query()->firstOrCreate(
                ['course_id' => $course->id, 'slug' => $vData['slug']],
                $vData
            );

            ClinicalTask::query()->firstOrCreate(
                [
                    'course_id' => $course->id,
                    'video_id' => $videoModel->id,
                ],
                [
                    'title_ar' => "مهمة تطبيقية: {$taskTitle}",
                    'title_en' => "Practical Task: {$vData['title_en']}",
                    'instructions_ar' => 'قم بإعداد وتسجيل التطبيق العملي للدرس وفق المعايير المحددة.',
                    'instructions_en' => 'Record a clear practical demonstration according to specified criteria.',
                    'max_score' => 100,
                ]
            );
        }
    }
}
