<?php

declare(strict_types=1);

namespace App\Services\Course;

use App\Models\Admin;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\Level;
use App\Models\PaymentReceipt;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoProgress;
use App\Notifications\UnifiedNotification;
use App\Services\Certificate\CertificateService;
use App\Services\Cms\SettingsService;
use App\Services\Video\BunnyStreamService;
use App\Services\Video\MuxTokenService;
use App\Services\Video\VideoProgressService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class StudentCourseWorkspaceService
{
    public function __construct(
        protected CourseEnrollmentService $enrollmentService,
        protected VideoProgressService $progressService,
        protected BunnyStreamService $bunnyService,
        protected CertificateService $certificateService,
        protected ?MuxTokenService $muxTokenService = null
    ) {
        $this->muxTokenService = $this->muxTokenService ?: app(MuxTokenService::class);
    }

    /**
     * Get published courses for public catalog with optional search filter.
     *
     * @return Collection<int, Course>
     */
    public function getPublicCatalog(?string $search = null): Collection
    {
        if ($defaultCourse = Course::first()) {
            $this->ensureCurriculumSeeded($defaultCourse);
        }

        $query = Course::query()
            ->where('is_published', true)
            ->withCount(['videos' => fn ($q) => $q->published()])
            ->orderBy('sort_order');

        if (! empty($search)) {
            $cleanSearch = trim($search);
            $query->where(function ($q) use ($cleanSearch) {
                $q->where('title_ar', 'like', "%{$cleanSearch}%")
                    ->orWhere('title_en', 'like', "%{$cleanSearch}%")
                    ->orWhere('description_ar', 'like', "%{$cleanSearch}%")
                    ->orWhere('description_en', 'like', "%{$cleanSearch}%");
            });
        }

        return $query->get();
    }

    /**
     * Resolve course by instance, slug, ID, or fallback to default/active course.
     */
    public function resolveCourse(Course|string|int|null $courseParam = null, ?User $user = null): ?Course
    {
        $course = null;

        if ($courseParam instanceof Course) {
            $course = $courseParam;
        } elseif (is_string($courseParam) || is_int($courseParam)) {
            $course = Course::query()
                ->where('slug', (string) $courseParam)
                ->orWhere('id', $courseParam)
                ->first();
        }

        if (! $course && $user instanceof User) {
            $course = $user->courses()->wherePivot('status', 'active')->first();
        }

        if (! $course) {
            $course = Course::query()->where('is_published', true)->orderBy('sort_order')->first() ?? Course::first();
        }

        return $course;
    }

    /**
     * Compute student academic dashboard data (shared by Web & API).
     *
     * @return array<string, mixed>
     */
    public function getDashboardData(?User $user = null): array
    {
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

        return compact(
            'enrolledCourses',
            'myCoursesData',
            'stats',
            'lastWatchedCourse',
            'lastWatchedVideo',
            'lastWatchedOverall',
            'suggestedCourses',
            'isDemo'
        );
    }

    /**
     * Compute student's "My Courses" workspace data (shared by Web & API).
     *
     * @return array<string, mixed>
     */
    public function getStudentCoursesData(User $user): array
    {
        $allCourses = $this->getPublicCatalog();

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

        return compact('myCoursesData', 'allCourses', 'stats', 'enrolledCourseIds');
    }

    /**
     * Compute detailed course curriculum, progress, levels, and clinical tasks data (supports both Guest & Student).
     *
     * @return array<string, mixed>
     */
    public function getCourseDetailsData(Course $course, ?User $user = null): array
    {
        $this->ensureCurriculumSeeded($course);

        $hasActiveAccess = $user ? $this->enrollmentService->hasActiveAccess($user, $course) : false;
        $pendingReceipt = $user ? PaymentReceipt::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->latest()
            ->first() : null;

        $courseProgressPercentage = $user ? $this->progressService->getCourseProgressPercentage($user, $course) : 0.0;

        $levels = $course->publishedLevels()
            ->with([
                'publishedVideos' => fn ($q) => $q->with('clinicalTask')->orderBy('sort_order')->orderBy('id'),
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $standaloneVideos = Video::query()
            ->published()
            ->where('course_id', $course->id)
            ->whereNull('level_id')
            ->with('clinicalTask')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $allCurriculumVideos = $this->progressService->getCurriculumVideos($course);
        $curriculumStatus = $user ? $this->progressService->getCurriculumStatus($user, $course) : [
            'completed_video_ids' => [],
            'accessible_video_ids' => $allCurriculumVideos->where('is_free_preview', true)->pluck('id')->all(),
            'completed_level_ids' => [],
            'accessible_level_ids' => [],
            'current_video_id' => $allCurriculumVideos->first()?->id,
        ];

        $userProgressMap = $user ? VideoProgress::query()
            ->where('user_id', $user->id)
            ->whereIn('video_id', $allCurriculumVideos->pluck('id'))
            ->get()
            ->keyBy('video_id') : collect();

        $totalVideosCount = $allCurriculumVideos->count();
        $totalDurationSeconds = (int) $allCurriculumVideos->sum('duration_seconds');
        $totalDurationMinutes = (int) round($totalDurationSeconds / 60);

        $watchedSeconds = (int) $userProgressMap->sum('watched_seconds');
        $watchedMinutes = (int) round($watchedSeconds / 60);

        $completedVideoIds = $curriculumStatus['completed_video_ids'];
        $completedVideosCount = count($completedVideoIds);
        $accessibleVideoIds = $curriculumStatus['accessible_video_ids'];
        $accessibleLevelIds = $curriculumStatus['accessible_level_ids'];
        $totalLevelsCount = $levels->count();

        $lastWatchedProgress = $user ? VideoProgress::query()
            ->where('user_id', $user->id)
            ->whereIn('video_id', $allCurriculumVideos->pluck('id'))
            ->orderByDesc('last_watched_at')
            ->first() : null;

        $resumeVideo = null;
        $resumeProgress = null;

        if ($lastWatchedProgress && ! $lastWatchedProgress->is_completed) {
            $resumeVideo = $allCurriculumVideos->firstWhere('id', $lastWatchedProgress->video_id);
            $resumeProgress = $lastWatchedProgress;
        }

        if (! $resumeVideo) {
            $currentVideoId = $curriculumStatus['current_video_id'] ?? null;
            if ($currentVideoId) {
                $resumeVideo = $allCurriculumVideos->firstWhere('id', $currentVideoId);
                $resumeProgress = $resumeVideo ? $userProgressMap->get($resumeVideo->id) : null;
            }
        }

        if (! $resumeVideo) {
            $resumeVideo = $allCurriculumVideos->first();
            $resumeProgress = $resumeVideo ? $userProgressMap->get($resumeVideo->id) : null;
        }

        $allTasks = ClinicalTask::query()
            ->where('course_id', $course->id)
            ->with('video')
            ->get();

        $totalTasksCount = $allTasks->count();
        $userSubmissions = $user ? TaskSubmission::query()
            ->where('user_id', $user->id)
            ->whereIn('task_id', $allTasks->pluck('id'))
            ->get()
            ->keyBy('task_id') : collect();

        $gradedTasksCount = $userSubmissions->where('status', 'graded')->count();
        $pendingTasksCount = $userSubmissions->where('status', 'pending')->count();
        $availableTasksCount = 0;
        $lockedTasksCount = 0;
        $nextAvailableTask = null;

        foreach ($allTasks as $task) {
            if ($userSubmissions->has($task->id)) {
                continue;
            }
            $videoDone = $task->video_id && in_array($task->video_id, $completedVideoIds, true);
            if ($videoDone) {
                $availableTasksCount++;
                if (! $nextAvailableTask) {
                    $nextAvailableTask = $task;
                }
            } else {
                $lockedTasksCount++;
            }
        }

        return [
            'course' => $course,
            'currentCourse' => $course,
            'hasActiveAccess' => $hasActiveAccess,
            'pendingReceipt' => $pendingReceipt,
            'courseProgressPercentage' => $courseProgressPercentage,
            'levels' => $levels,
            'standaloneVideos' => $standaloneVideos,
            'allCurriculumVideos' => $allCurriculumVideos,
            'userProgressMap' => $userProgressMap,
            'userSubmissions' => $userSubmissions,
            'completedVideoIds' => $completedVideoIds,
            'accessibleVideoIds' => $accessibleVideoIds,
            'accessibleLevelIds' => $accessibleLevelIds,
            'totalVideosCount' => $totalVideosCount,
            'totalDurationMinutes' => $totalDurationMinutes,
            'watchedMinutes' => $watchedMinutes,
            'completedVideosCount' => $completedVideosCount,
            'totalLevelsCount' => $totalLevelsCount,
            'resumeVideo' => $resumeVideo,
            'resumeProgress' => $resumeProgress,
            'totalTasksCount' => $totalTasksCount,
            'gradedTasksCount' => $gradedTasksCount,
            'pendingTasksCount' => $pendingTasksCount,
            'availableTasksCount' => $availableTasksCount,
            'lockedTasksCount' => $lockedTasksCount,
            'nextAvailableTask' => $nextAvailableTask,
            'cmsSettings' => app(SettingsService::class),
        ];
    }

    /**
     * Resolve video for lesson playback within a course.
     */
    public function resolveLessonVideo(User $user, Course $course, Video|string|int|null $videoParam = null): Video
    {
        $this->ensureCurriculumSeeded($course);

        $video = null;
        if ($videoParam instanceof Video) {
            $video = $videoParam;
        } elseif (is_string($videoParam) || is_int($videoParam)) {
            $video = Video::query()
                ->published()
                ->where('course_id', $course->id)
                ->where(function ($q) use ($videoParam) {
                    $q->where('slug', (string) $videoParam)->orWhere('id', $videoParam);
                })->firstOrFail();
        }

        if (! $video) {
            $curriculumVideos = $this->progressService->getCurriculumVideos($course);
            $completedIds = $this->progressService->getCompletedVideoIds($user, $course);
            $video = $curriculumVideos->first(fn ($v) => ! in_array($v->id, $completedIds, true))
                ?? $curriculumVideos->first()
                ?? $course->publishedVideos()->firstOrFail();
        }

        return $video;
    }

    /**
     * Compute lesson playback data, tokens, watermark, and curriculum status (shared by Web & API).
     *
     * @return array<string, mixed>
     */
    public function getLessonPlaybackData(User $user, Course $course, Video $video, ?string $clientIp = null): array
    {
        $hasCourseAccess = $this->enrollmentService->hasActiveAccess($user, $course);
        $canAccess = $this->progressService->canAccessVideo($user, $video);

        $playbackToken = null;
        $storyboardToken = null;
        $thumbnailToken = null;

        if ($canAccess && ! empty($video->mux_playback_id)) {
            $playbackToken = $this->muxTokenService->generatePlaybackToken($video->mux_playback_id);
            $storyboardToken = $this->muxTokenService->generateStoryboardToken($video->mux_playback_id);
            $thumbnailToken = $this->muxTokenService->generateThumbnailToken($video->mux_playback_id);
        }

        $embedUrl = $canAccess ? $this->bunnyService->getIframeEmbedUrl($video) : '';
        $watermarkPayload = $this->bunnyService->getWatermarkPayload($user, $clientIp);
        $curriculumStatus = $this->progressService->getCurriculumStatus($user, $course);

        $videos = $this->progressService->getCurriculumVideos($course);
        $publishedLevels = $course->publishedLevels()
            ->with(['publishedVideos' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        $currentProgress = $video->progressForUser($user->id);
        $courseProgressPercentage = $this->progressService->getCourseProgressPercentage($user, $course);
        $taskSubmission = $video->clinicalTask ? $video->clinicalTask->submissionForUser($user->id) : null;

        return compact(
            'course',
            'video',
            'videos',
            'publishedLevels',
            'canAccess',
            'hasCourseAccess',
            'embedUrl',
            'playbackToken',
            'storyboardToken',
            'thumbnailToken',
            'watermarkPayload',
            'currentProgress',
            'curriculumStatus',
            'courseProgressPercentage',
            'taskSubmission'
        );
    }

    /**
     * Refresh signed MUX playback tokens for an authorized student.
     *
     * @return array{status: int, data: array<string, mixed>}
     */
    public function refreshPlaybackTokens(?User $user, Video $video): array
    {
        if (! $user || ! $video->is_published || ($video->level && ! $video->level->is_published) || ! $this->progressService->canAccessVideo($user, $video)) {
            return [
                'status' => 403,
                'data' => [
                    'success' => false,
                    'message' => __('غير مصرح لك بمشاهدة هذا الفيديو أو انتهت صلاحية اشتراكك.'),
                ],
            ];
        }

        if (empty($video->mux_playback_id)) {
            return [
                'status' => 404,
                'data' => [
                    'success' => false,
                    'message' => __('معرّف البث غير متوفر لهذا الدرس.'),
                ],
            ];
        }

        $playbackToken = $this->muxTokenService->generatePlaybackToken($video->mux_playback_id);
        $storyboardToken = $this->muxTokenService->generateStoryboardToken($video->mux_playback_id);
        $thumbnailToken = $this->muxTokenService->generateThumbnailToken($video->mux_playback_id);

        return [
            'status' => 200,
            'data' => [
                'success' => true,
                'playback_token' => $playbackToken,
                'storyboard_token' => $storyboardToken,
                'thumbnail_token' => $thumbnailToken,
            ],
        ];
    }

    /**
     * Record video progress and return updated completion & next-video metadata (shared by Web & API).
     *
     * @return array<string, mixed>
     */
    public function recordProgressWithSummary(
        User $user,
        Video $video,
        int $watchedSeconds,
        bool $isEnded = false,
        ?int $actualDuration = null
    ): array {
        $progress = $this->progressService->recordProgress(
            user: $user,
            video: $video,
            currentWatchedSeconds: $watchedSeconds,
            isEnded: $isEnded,
            actualDuration: $actualDuration
        );

        $courseProgress = $this->progressService->getCourseProgressPercentage($user, $video->course);
        $certificate = $user->certificates()->where('course_id', $video->course_id)->first();

        $allVideos = $this->progressService->getCurriculumVideos($video->course);
        $currentIndex = $allVideos->search(fn ($v) => $v->id === $video->id);
        $nextVideo = null;
        if ($currentIndex !== false && isset($allVideos[$currentIndex + 1])) {
            $nextCandidate = $allVideos[$currentIndex + 1];
            if ($this->progressService->canAccessVideo($user, $nextCandidate)) {
                $nextVideo = [
                    'id' => $nextCandidate->id,
                    'slug' => $nextCandidate->slug,
                    'title' => $nextCandidate->title_ar,
                    'title_en' => $nextCandidate->title_en,
                    'url' => route('lessons.show', [$video->course->slug, $nextCandidate->slug]),
                ];
            }
        }

        return [
            'success' => true,
            'watched_seconds' => $progress->watched_seconds,
            'completion_percentage' => $progress->completion_percentage,
            'is_completed' => (bool) $progress->is_completed,
            'course_progress' => $courseProgress,
            'certificate_issued' => $certificate !== null,
            'next_video' => $nextVideo,
        ];
    }

    /**
     * Suspend student account, notify admins, revoke API tokens, and invalidate web session upon security violation.
     *
     * @return array<string, mixed>
     */
    public function reportSecurityViolation(
        User $user,
        ?Video $video = null,
        string $incidentType = 'screen_recording_attempt',
        ?Request $request = null
    ): array {
        // 1. Immediately deactivate/suspend the student account
        $user->update(['status' => 'suspended']);

        // 2. Incident metadata
        $ip = $request?->ip();
        $userAgent = $request?->userAgent();
        $course = $video?->course;
        $videoTitle = $video?->title_ar ?? __('غير محدد');
        $courseTitle = $course?->title_ar ?? __('غير محدد');

        Log::warning('[SECURITY_VIOLATION] Screen recording attempt intercepted. Student suspended immediately.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_phone' => $user->phone,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'video_id' => $video?->id,
            'video_title' => $videoTitle,
            'course_id' => $course?->id,
            'course_title' => $courseTitle,
            'incident_type' => $incidentType,
        ]);

        // 3. Send urgent notification to all administrators
        try {
            $admins = Admin::all();
            if ($admins->isNotEmpty()) {
                $notification = new UnifiedNotification(
                    title: [
                        'ar' => '🚨 رصد محاولة تسجيل شاشة — تم إيقاف الحساب فوراً',
                        'en' => '🚨 Screen Recording Detected — Account Suspended',
                    ],
                    body: [
                        'ar' => "تم رصد محاولة تسجيل شاشة للمحاضرة '{$videoTitle}' بواسطة الطالب {$user->name} (هاتف: {$user->phone}). تم إيقاف الحساب فوراً وتسجيل الخروج.",
                        'en' => "Screen recording attempt detected for lecture '{$videoTitle}' by student {$user->name} (Phone: {$user->phone}). Account suspended immediately.",
                    ],
                    channels: ['database'],
                    type: 'security',
                    actionUrl: route('admin.students.show', $user->id),
                    actionText: [
                        'ar' => 'فحص ملف الطالب وبيانات الانتهاك',
                        'en' => 'Review Student Profile & Violation',
                    ],
                    data: [
                        'incident' => 'screen_recording_blocked',
                        'student_id' => $user->id,
                        'student_name' => $user->name,
                        'student_phone' => $user->phone,
                        'student_email' => $user->email,
                        'ip_address' => $ip,
                        'user_agent' => $userAgent,
                        'video_id' => $video?->id,
                        'video_title' => $videoTitle,
                        'course_id' => $course?->id,
                        'course_title' => $courseTitle,
                        'incident_type' => $incidentType,
                        'reported_at' => now()->toIso8601String(),
                    ]
                );

                Notification::send($admins, $notification);
            }
        } catch (\Throwable $e) {
            Log::error('[SECURITY_VIOLATION] Failed to dispatch admin notification: '.$e->getMessage());
        }

        // 4. Revoke all Sanctum API tokens if present
        try {
            if (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }
        } catch (\Throwable) {
            // Ignore if personal_access_tokens table is not migrated yet
        }

        // 5. Invalidate web session if active
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        if ($request && $request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return [
            'status' => 'suspended',
            'message' => __('تم رصد محاولة تسجيل شاشة. تم إيقاف الحساب فوراً وتسجيل الخروج لأسباب أمنية.'),
            'redirect' => route('login'),
        ];
    }

    /**
     * Ensure default curriculum has all 20 canonical videos populated across the 4 levels.
     */
    public function ensureCurriculumSeeded(Course $course): void
    {
        if ($course->videos()->count() >= 20 || ($course->videos()->count() > 0 && $course->slug !== 'speech-therapy-diploma')) {
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
                'title_en' => 'Lesson 18: Family Counseling Strategies & Practical Observation Notes',
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
                'title_en' => 'Lesson 19: Practical Simulation Workshop & Progress Review',
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
                'task_title' => 'تقرير تحليل جلسة محاكاة عملية',
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

