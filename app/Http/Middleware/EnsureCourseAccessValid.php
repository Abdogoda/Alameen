<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\User;
use App\Services\Course\CourseEnrollmentService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCourseAccessValid
{
    public function __construct(
        protected CourseEnrollmentService $enrollmentService
    ) {}

    /**
     * Handle an incoming request and ensure user has active time-bound access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if ($user->isSuspended()) {
            abort(Response::HTTP_FORBIDDEN, __('تم تعليق حسابك. يرجى التواصل مع إدارة المنصة.'));
        }

        // Retrieve course from route parameter (can be 'course' or 'course:slug')
        $course = $request->route('course');

        if (is_string($course)) {
            $course = Course::query()->where('slug', $course)->orWhere('id', $course)->first();
        }

        if ($course instanceof Course) {
            if (! $this->enrollmentService->hasActiveAccess($user, $course)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => __('انتهت صلاحية اشتراكك في هذه الدورة أو لم يتم تفعيلها بعد.'),
                        'course_id' => $course->id,
                        'expired' => true,
                    ], Response::HTTP_FORBIDDEN);
                }

                return redirect()->route('courses.show', $course->slug)
                    ->with('error', __('انتهت صلاحية اشتراكك في هذا المحتوى. يرجى تجديد الاشتراك للمتابعة.'));
            }
        }

        return $next($request);
    }
}
