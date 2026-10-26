<?php

declare(strict_types=1);

namespace App\Services\Video;

use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoProgress;
use App\Services\Course\CourseEnrollmentService;
use Illuminate\Support\Carbon;

class VideoProgressService
{
    public function __construct(
        protected CourseEnrollmentService $enrollmentService
    ) {}

    /**
     * Check if student can watch the specified video based on Sequential Video Locking.
     *
     * Rule: Student cannot watch Video (N+1) until Video (N) reaches its min_completion_percentage.
     */
    public function canAccessVideo(User $user, Video $video): bool
    {
        // 1. Free preview is accessible
        if ($video->is_free_preview) {
            return true;
        }

        // 2. Must have active, non-expired course enrollment
        $course = $video->course;
        if (! $course || ! $this->enrollmentService->hasActiveAccess($user, $course)) {
            return false;
        }

        // 3. Find the immediately preceding video in the course
        $previousVideo = Video::query()
            ->where('course_id', $video->course_id)
            ->where('sort_order', '<', $video->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        // If this is the very first video, it is unlocked
        if (! $previousVideo) {
            return true;
        }

        // 4. Check progress of the previous video
        $prevProgress = VideoProgress::query()
            ->where('user_id', $user->id)
            ->where('video_id', $previousVideo->id)
            ->first();

        if (! $prevProgress) {
            return false;
        }

        $requiredPercentage = (float) $previousVideo->min_completion_percentage;

        return $prevProgress->is_completed || ($prevProgress->completion_percentage >= $requiredPercentage);
    }

    /**
     * Record watched time and recalculate completion percentage.
     */
    public function recordProgress(User $user, Video $video, int $currentWatchedSeconds): VideoProgress
    {
        $progress = VideoProgress::firstOrNew([
            'user_id' => $user->id,
            'video_id' => $video->id,
        ]);

        $maxWatched = max($progress->watched_seconds ?? 0, $currentWatchedSeconds);
        $duration = max(1, $video->duration_seconds);

        $percentage = min(100.0, round(($maxWatched / $duration) * 100, 2));
        $isCompleted = ($percentage >= $video->min_completion_percentage) || ($progress->is_completed ?? false);

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
        $videos = $course->videos;
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
