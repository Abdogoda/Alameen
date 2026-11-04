<?php

declare(strict_types=1);

namespace App\Services\Video;

use App\Models\Course;
use App\Models\Level;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoProgress;
use App\Services\Cms\SettingsService;
use App\Services\Course\CourseEnrollmentService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VideoProgressService
{
    public function __construct(
        protected CourseEnrollmentService $enrollmentService
    ) {}

    /**
     * Check if a student has completed a specific video.
     */
    public function isVideoCompleted(User $user, Video $video): bool
    {
        $progress = VideoProgress::query()
            ->where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->first();

        if (! $progress) {
            return false;
        }

        $requiredPercentage = (float) ($video->min_completion_percentage ?: 80);

        return (bool) $progress->is_completed || ($progress->completion_percentage >= $requiredPercentage);
    }

    /**
     * Check if a student has completed all published videos in a level.
     * Empty levels with no published videos are never considered completed.
     */
    public function isLevelCompleted(User $user, Level $level): bool
    {
        $videos = $level->publishedVideos;

        if ($videos->isEmpty()) {
            return false;
        }

        $videoIds = $videos->pluck('id')->all();

        $progressList = VideoProgress::query()
            ->where('user_id', $user->id)
            ->whereIn('video_id', $videoIds)
            ->get()
            ->keyBy('video_id');

        foreach ($videos as $video) {
            $prog = $progressList->get($video->id);
            if (! $prog) {
                return false;
            }

            $required = (float) ($video->min_completion_percentage ?: 80);
            if (! $prog->is_completed && $prog->completion_percentage < $required) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if student can access the specified level.
     *
     * Rule:
     * - Completed levels can be accessed anytime.
     * - Future levels cannot open until all preceding levels are completed.
     */
    public function canAccessLevel(User $user, Level $level): bool
    {
        if (! $level->is_published) {
            return false;
        }

        $course = $level->course;
        if (! $course || ! $this->enrollmentService->hasActiveAccess($user, $course)) {
            return false;
        }

        $isLockEnabled = app(SettingsService::class)->get('sequential_video_lock', '1') === '1';
        if (! $isLockEnabled) {
            return true;
        }

        // 1. If this level is already completed, allow access anytime
        if ($this->isLevelCompleted($user, $level)) {
            return true;
        }

        // 2. Preceding levels in the course must all be completed
        $precedingLevels = Level::query()
            ->published()
            ->where('course_id', $level->course_id)
            ->where(function ($q) use ($level) {
                $q->where('sort_order', '<', $level->sort_order)
                    ->orWhere(function ($sub) use ($level) {
                        $sub->where('sort_order', $level->sort_order)
                            ->where('id', '<', $level->id);
                    });
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        foreach ($precedingLevels as $prevLevel) {
            if (! $this->isLevelCompleted($user, $prevLevel)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get all published videos of a course in canonical curriculum sequence:
     * Levels in sort_order/id, each level's published videos in sort_order/id,
     * followed by standalone published videos without a level.
     *
     * @return Collection<int, Video>
     */
    public function getCurriculumVideos(Course $course): Collection
    {
        $publishedLevels = $course->publishedLevels()
            ->with(['publishedVideos' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($publishedLevels->isNotEmpty()) {
            $orderedVideos = collect();

            foreach ($publishedLevels as $lvl) {
                foreach ($lvl->publishedVideos as $vid) {
                    $orderedVideos->push($vid);
                }
            }

            $standaloneVideos = Video::query()
                ->published()
                ->where('course_id', $course->id)
                ->whereNull('level_id')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            foreach ($standaloneVideos as $vid) {
                $orderedVideos->push($vid);
            }

            return $orderedVideos;
        }

        return $course->publishedVideos()->orderBy('sort_order')->orderBy('id')->get();
    }

    /**
     * Get IDs of all videos completed by the user in this course.
     *
     * @return array<int>
     */
    public function getCompletedVideoIds(User $user, Course $course): array
    {
        $videos = $this->getCurriculumVideos($course);
        if ($videos->isEmpty()) {
            return [];
        }

        $progressList = VideoProgress::query()
            ->where('user_id', $user->id)
            ->whereIn('video_id', $videos->pluck('id')->all())
            ->get()
            ->keyBy('video_id');

        $completedIds = [];
        foreach ($videos as $video) {
            $prog = $progressList->get($video->id);
            if ($prog) {
                $required = (float) ($video->min_completion_percentage ?: 80);
                if ($prog->is_completed || $prog->completion_percentage >= $required) {
                    $completedIds[] = $video->id;
                }
            }
        }

        return $completedIds;
    }

    /**
     * Check if student can watch the specified video based on Sequential Video & Level Locking.
     *
     * Rule:
     * 1. Completed lessons or levels can be opened and rewatched ANY TIME.
     * 2. Incomplete lessons or levels cannot open until the current one is completed.
     */
    public function canAccessVideo(User $user, Video $video): bool
    {
        // 0. Unpublished video or video in unpublished level is never accessible to students
        if ($video->is_published === false || ($video->level && $video->level->is_published === false)) {
            return false;
        }

        // 1. Free preview is accessible
        if ($video->is_free_preview) {
            return true;
        }

        // 2. Must have active, non-expired course enrollment
        $course = $video->course;
        if (! $course || ! $this->enrollmentService->hasActiveAccess($user, $course)) {
            return false;
        }

        // 3. Check if sequential video locking policy is enabled globally
        $isLockEnabled = app(SettingsService::class)->get('sequential_video_lock', '1') === '1';
        if (! $isLockEnabled) {
            return true;
        }

        // 4. Completed videos can be opened and rewatched any time!
        if ($this->isVideoCompleted($user, $video)) {
            return true;
        }

        // 5. If video belongs to a level, verify access to that level
        if ($video->level_id && ! $this->canAccessLevel($user, $video->level)) {
            return false;
        }

        // 6. Find all preceding videos in canonical curriculum sequence
        $allVideos = $this->getCurriculumVideos($course);
        $videoIndex = $allVideos->search(fn ($v) => $v->id === $video->id);

        if ($videoIndex === false) {
            // Fallback for tests or dynamic videos not yet mapped in levels
            $previousVideo = Video::query()
                ->published()
                ->where('course_id', $video->course_id)
                ->where('sort_order', '<', $video->sort_order)
                ->orderByDesc('sort_order')
                ->first();

            if (! $previousVideo) {
                return true;
            }

            return $this->isVideoCompleted($user, $previousVideo);
        }

        // First video in sequence is unlocked
        if ($videoIndex === 0) {
            return true;
        }

        // Preceding videos must all be completed for this next video to open
        $precedingVideos = $allVideos->slice(0, $videoIndex);
        $precedingIds = $precedingVideos->pluck('id')->all();

        $progressRecords = VideoProgress::query()
            ->where('user_id', $user->id)
            ->whereIn('video_id', $precedingIds)
            ->get()
            ->keyBy('video_id');

        foreach ($precedingVideos as $prevVid) {
            $prog = $progressRecords->get($prevVid->id);
            if (! $prog) {
                return false;
            }

            $req = (float) ($prevVid->min_completion_percentage ?: 80);
            if (! $prog->is_completed && $prog->completion_percentage < $req) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get a comprehensive curriculum status map for a user and course in a single batch pass.
     *
     * @return array{
     *     completed_video_ids: array<int>,
     *     accessible_video_ids: array<int>,
     *     completed_level_ids: array<int>,
     *     accessible_level_ids: array<int>,
     *     current_video_id: int|null
     * }
     */
    public function getCurriculumStatus(User $user, Course $course): array
    {
        $publishedLevels = $course->publishedLevels()
            ->with(['publishedVideos' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $curriculumVideos = $this->getCurriculumVideos($course);
        $allVideoIds = $curriculumVideos->pluck('id')->all();

        $progressMap = VideoProgress::query()
            ->where('user_id', $user->id)
            ->whereIn('video_id', $allVideoIds)
            ->get()
            ->keyBy('video_id');

        // Identify completed videos
        $completedVideoIds = [];
        foreach ($curriculumVideos as $vid) {
            $prog = $progressMap->get($vid->id);
            if ($prog) {
                $req = (float) ($vid->min_completion_percentage ?: 80);
                if ($prog->is_completed || $prog->completion_percentage >= $req) {
                    $completedVideoIds[] = $vid->id;
                }
            }
        }

        // Identify completed levels
        $completedLevelIds = [];
        foreach ($publishedLevels as $lvl) {
            $lvlVideos = $lvl->publishedVideos;
            if ($lvlVideos->isEmpty()) {
                continue;
            }

            $allDone = true;
            foreach ($lvlVideos as $lv) {
                if (! in_array($lv->id, $completedVideoIds, true)) {
                    $allDone = false;
                    break;
                }
            }

            if ($allDone) {
                $completedLevelIds[] = $lvl->id;
            }
        }

        $isLockEnabled = app(SettingsService::class)->get('sequential_video_lock', '1') === '1';

        // Identify accessible levels
        $accessibleLevelIds = [];
        $precedingLevelsCompleted = true;
        foreach ($publishedLevels as $lvl) {
            if (! $isLockEnabled || in_array($lvl->id, $completedLevelIds, true)) {
                $accessibleLevelIds[] = $lvl->id;
            } elseif ($precedingLevelsCompleted) {
                // First incomplete level is accessible (the current level)
                $accessibleLevelIds[] = $lvl->id;
                $precedingLevelsCompleted = false;
            } else {
                // Future incomplete levels are locked
                $precedingLevelsCompleted = false;
            }
        }

        // Identify accessible videos & the current video
        $accessibleVideoIds = [];
        $currentVideoId = null;
        $foundCurrent = false;

        foreach ($curriculumVideos as $vid) {
            // Free preview is always accessible
            if ($vid->is_free_preview) {
                $accessibleVideoIds[] = $vid->id;
            }

            // Completed video is ALWAYS accessible anytime
            if (in_array($vid->id, $completedVideoIds, true)) {
                if (! in_array($vid->id, $accessibleVideoIds, true)) {
                    $accessibleVideoIds[] = $vid->id;
                }

                continue;
            }

            // If lock disabled, all are accessible
            if (! $isLockEnabled) {
                if (! in_array($vid->id, $accessibleVideoIds, true)) {
                    $accessibleVideoIds[] = $vid->id;
                }
                if (! $foundCurrent) {
                    $currentVideoId = $vid->id;
                    $foundCurrent = true;
                }

                continue;
            }

            // If this is the first uncompleted video and its level is accessible
            if (! $foundCurrent) {
                $levelAccessible = ! $vid->level_id || in_array($vid->level_id, $accessibleLevelIds, true);
                if ($levelAccessible) {
                    if (! in_array($vid->id, $accessibleVideoIds, true)) {
                        $accessibleVideoIds[] = $vid->id;
                    }
                    $currentVideoId = $vid->id;
                }
                $foundCurrent = true;
            }
            // Future uncompleted videos remain locked!
        }

        return [
            'completed_video_ids' => $completedVideoIds,
            'accessible_video_ids' => $accessibleVideoIds,
            'completed_level_ids' => $completedLevelIds,
            'accessible_level_ids' => $accessibleLevelIds,
            'current_video_id' => $currentVideoId,
        ];
    }

    /**
     * Record watched time and recalculate completion percentage.
     */
    public function recordProgress(User $user, Video $video, int $currentWatchedSeconds, bool $isEnded = false, ?int $actualDuration = null): VideoProgress
    {
        if (! $this->canAccessVideo($user, $video)) {
            throw new AccessDeniedHttpException(
                __('لا تملك صلاحية الوصول أو تسجيل التقدم لهذا الفيديو.')
            );
        }

        $progress = VideoProgress::firstOrNew([
            'user_id' => $user->id,
            'video_id' => $video->id,
        ]);

        // Auto-heal video duration if player detected actual media duration (e.g. video was shorter/longer than default 1200s placeholder)
        if ($actualDuration && $actualDuration > 0 && ($video->duration_seconds === 1200 || empty($video->duration_seconds) || abs($video->duration_seconds - $actualDuration) > 10)) {
            $video->update(['duration_seconds' => $actualDuration]);
            $video->refresh();
        }

        $effectiveDuration = ($actualDuration && $actualDuration > 0) ? $actualDuration : max(1, $video->duration_seconds);
        $maxWatched = max($progress->watched_seconds ?? 0, $currentWatchedSeconds);

        if ($isEnded) {
            $percentage = 100.0;
            $isCompleted = true;
            $maxWatched = max($maxWatched, $effectiveDuration);
        } else {
            $percentage = min(100.0, round(($maxWatched / $effectiveDuration) * 100, 2));
            $requiredPercentage = (float) ($video->min_completion_percentage ?: 80);
            $isCompleted = ($percentage >= $requiredPercentage) || ($progress->is_completed ?? false);
        }

        $progress->watched_seconds = $maxWatched;
        $progress->completion_percentage = $percentage;
        $progress->is_completed = $isCompleted;
        $progress->last_watched_at = Carbon::now();
        $progress->save();

        return $progress;
    }

    /**
     * Calculate overall course watch completion percentage (0 - 100).
     */
    public function getCourseProgressPercentage(User $user, Course $course): float
    {
        $videos = $course->publishedVideos()->get();
        $totalVideos = $videos->count();

        if ($totalVideos === 0) {
            return 0.0;
        }

        $videoIds = $videos->pluck('id')->all();

        $progressRecords = VideoProgress::query()
            ->where('user_id', $user->id)
            ->whereIn('video_id', $videoIds)
            ->get()
            ->keyBy('video_id');

        $totalPercentage = 0.0;
        foreach ($videos as $video) {
            $record = $progressRecords->get($video->id);
            if ($record) {
                $totalPercentage += min(100.0, (float) $record->completion_percentage);
            }
        }

        return round($totalPercentage / $totalVideos, 2);
    }
}
