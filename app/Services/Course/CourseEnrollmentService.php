<?php

declare(strict_types=1);

namespace App\Services\Course;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CourseEnrollmentService
{
    /**
     * Grant or renew student access to a course with a defined time-bound expiration.
     */
    public function enroll(
        User $user,
        Course $course,
        ?int $durationDays = null,
        ?int $adminId = null
    ): void {
        $days = $durationDays ?? $course->access_duration_days ?? 365;

        $startsAt = Carbon::now();
        $expiresAt = Carbon::now()->addDays($days);

        $existing = DB::table('course_user')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            // If already enrolled and active, extend from previous expiration
            $baseDate = ($existing->access_expires_at && Carbon::parse($existing->access_expires_at)->isFuture())
                ? Carbon::parse($existing->access_expires_at)
                : Carbon::now();

            DB::table('course_user')
                ->where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->update([
                    'status' => 'active',
                    'access_starts_at' => $startsAt,
                    'access_expires_at' => $baseDate->addDays($days),
                    'granted_by' => $adminId,
                    'updated_at' => Carbon::now(),
                ]);
        } else {
            DB::table('course_user')->insert([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'access_starts_at' => $startsAt,
                'access_expires_at' => $expiresAt,
                'status' => 'active',
                'granted_by' => $adminId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Determine if a user has valid, active, non-expired access to a course.
     */
    public function hasActiveAccess(User $user, Course $course): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        // Auto-enrolled courses are universally accessible to all active students
        if ($course->auto_enroll_students && $course->is_published && $user->isActive()) {
            return true;
        }

        $subscription = DB::table('course_user')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $subscription) {
            return false;
        }

        if ($subscription->status !== 'active') {
            return false;
        }

        return Carbon::parse($subscription->access_expires_at)->isFuture();
    }

    /**
     * Get remaining days of active access.
     */
    public function getRemainingDays(User $user, Course $course): int
    {
        $subscription = DB::table('course_user')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $subscription || ! $subscription->access_expires_at) {
            if ($course->auto_enroll_students && $course->is_published && $user->isActive()) {
                return (int) ($course->access_duration_days ?: 365);
            }

            return 0;
        }

        $expiresAt = Carbon::parse($subscription->access_expires_at);

        if ($expiresAt->isPast()) {
            if ($course->auto_enroll_students && $course->is_published && $user->isActive()) {
                return (int) ($course->access_duration_days ?: 365);
            }

            return 0;
        }

        return (int) Carbon::now()->diffInDays($expiresAt, false);
    }

    /**
     * Revoke access manually.
     */
    public function revokeAccess(User $user, Course $course): void
    {
        DB::table('course_user')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->update([
                'status' => 'expired',
                'updated_at' => Carbon::now(),
            ]);
    }

    /**
     * Bulk assign or synchronize users to a course.
     *
     * @param  Course  $course
     * @param  array<int>  $userIds
     * @param  bool  $syncMode If true, users currently enrolled who are not in $userIds will be detached.
     * @param  int|null  $durationDays
     * @param  Carbon|null  $expiresAt
     * @param  string  $status
     * @param  int|null  $adminId
     * @return array{assigned_count: int, detached_count: int}
     */
    public function syncCourseUsers(
        Course $course,
        array $userIds,
        bool $syncMode = true,
        ?int $durationDays = null,
        ?Carbon $expiresAt = null,
        string $status = 'active',
        ?int $adminId = null
    ): array {
        $normalizedUserIds = array_values(array_unique(array_filter(array_map('intval', $userIds))));

        $days = $durationDays ?? $course->access_duration_days ?? 365;
        $finalExpiresAt = $expiresAt ?? Carbon::now()->addDays($days);
        $now = Carbon::now();

        $detachedCount = 0;
        if ($syncMode) {
            $existingUserIds = DB::table('course_user')
                ->where('course_id', $course->id)
                ->pluck('user_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $toDetach = array_diff($existingUserIds, $normalizedUserIds);
            if (! empty($toDetach)) {
                $detachedCount = DB::table('course_user')
                    ->where('course_id', $course->id)
                    ->whereIn('user_id', $toDetach)
                    ->delete();
            }
        }

        $assignedCount = 0;
        if (! empty($normalizedUserIds)) {
            $syncData = [];
            foreach ($normalizedUserIds as $userId) {
                $syncData[$userId] = [
                    'access_starts_at' => $now,
                    'access_expires_at' => $finalExpiresAt,
                    'status' => $status,
                    'granted_by' => $adminId,
                    'updated_at' => $now,
                ];
            }

            $course->users()->syncWithoutDetaching($syncData);
            $assignedCount = count($normalizedUserIds);
        }

        return [
            'assigned_count' => $assignedCount,
            'detached_count' => $detachedCount,
        ];
    }
}
