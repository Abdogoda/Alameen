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
            return 0;
        }

        $expiresAt = Carbon::parse($subscription->access_expires_at);

        if ($expiresAt->isPast()) {
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
}
