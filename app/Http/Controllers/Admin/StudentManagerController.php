<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteStudentRequest;
use App\Http\Requests\Admin\EnrollStudentCourseRequest;
use App\Http\Requests\Admin\IssueStudentCertificateRequest;
use App\Http\Requests\Admin\UpdateStudentEnrollmentRequest;
use App\Http\Requests\Admin\UpdateStudentPasswordRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Http\Requests\Admin\UpdateVideoProgressRequest;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoProgress;
use App\Services\Auth\StudentProfileService;
use App\Services\Certificate\CertificateService;
use App\Services\Course\CourseEnrollmentService;
use App\Services\Video\VideoProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentManagerController extends Controller
{
    public function __construct(
        protected StudentProfileService $profileService,
        protected CourseEnrollmentService $enrollmentService,
        protected VideoProgressService $progressService,
        protected CertificateService $certificateService
    ) {}

    /**
     * Display listing of students with enrollment stats and location data.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');

        $query = User::with(['courses', 'videoProgress', 'certificates'])->latest();

        if ($status !== 'all' && in_array($status, ['active', 'pending', 'suspended'], true)) {
            $query->where('status', $status);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => User::count(),
            'pending' => User::where('status', 'pending')->count(),
            'active' => User::where('status', 'active')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
        ];

        return view('admin.students.index', compact('students', 'counts', 'status', 'search'));
    }

    /**
     * Display comprehensive details and management dashboard for a student.
     */
    public function show(User $user): View
    {
        $user->load([
            'courses' => function ($q) {
                $q->withCount('videos');
            },
            'videoProgress.video.course',
            'certificates.course',
            'paymentReceipts.course',
            'paymentReceipts.reviewer',
            'taskSubmissions.task.course',
            'taskSubmissions.grader',
        ]);

        $allCourses = Course::where('is_published', true)->orderBy('sort_order')->get();

        // Calculate progress percentage for each enrolled course
        $enrolledCourseProgress = [];
        foreach ($user->courses as $course) {
            $enrolledCourseProgress[$course->id] = $this->progressService->getCourseProgressPercentage($user, $course);
        }

        // Load granting admins
        $grantedByIds = $user->courses->pluck('pivot.granted_by')->filter()->unique()->all();
        $grantingAdmins = ! empty($grantedByIds)
            ? Admin::whereIn('id', $grantedByIds)->get()->keyBy('id')
            : collect();

        // Key stats calculations
        $totalSecondsWatched = (int) $user->videoProgress->sum('watched_seconds');
        $hoursWatched = (int) floor($totalSecondsWatched / 3600);
        $minutesWatched = (int) floor(($totalSecondsWatched % 3600) / 60);

        $completedVideosCount = $user->videoProgress->where('is_completed', true)->count();
        $totalTasksSubmitted = $user->taskSubmissions->count();
        $gradedTasks = $user->taskSubmissions->where('status', 'graded');
        $gradedTasksCount = $gradedTasks->count();
        $avgScore = $gradedTasksCount > 0 ? round((float) $gradedTasks->avg('score'), 1) : 0;

        $approvedReceiptsTotal = (float) $user->paymentReceipts->where('status', 'approved')->sum('amount');

        return view('admin.students.show', compact(
            'user',
            'allCourses',
            'enrolledCourseProgress',
            'grantingAdmins',
            'totalSecondsWatched',
            'hoursWatched',
            'minutesWatched',
            'completedVideosCount',
            'totalTasksSubmitted',
            'gradedTasksCount',
            'avgScore',
            'approvedReceiptsTotal'
        ));
    }

    /**
     * Update student profile information.
     */
    public function update(UpdateStudentRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $this->profileService->updateProfile($user, $validated);

        if (isset($validated['status'])) {
            $this->profileService->setStatus($user, $validated['status']);
        }

        return back()->with('success', __('تم تحديث بيانات الطالب بنجاح.'));
    }

    /**
     * Update student account password.
     */
    public function updatePassword(UpdateStudentPasswordRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', __('تم تعيين كلمة مرور جديدة لحساب الطالب بنجاح.'));
    }

    /**
     * Approve and activate a newly registered student account.
     */
    public function approve(User $user): RedirectResponse
    {
        $this->profileService->activate($user);

        return back()->with('success', __('تم اعتماد وتفعيل حساب الطالب (:name) بنجاح ويمكنه الآن تسجيل الدخول.', ['name' => $user->name]));
    }

    /**
     * Toggle active/suspended status of a student account.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->isActive()) {
            $this->profileService->suspend($user);
            $msg = __('تم تعليق حساب الطالب بنجاح.');
        } else {
            $this->profileService->activate($user);
            $msg = __('تم تنشيط حساب الطالب بنجاح.');
        }

        return back()->with('success', $msg);
    }

    /**
     * Enroll student in a course or grant access.
     */
    public function enrollCourse(EnrollStudentCourseRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        /** @var Course $course */
        $course = Course::query()->findOrFail($validated['course_id']);
        /** @var Admin|null $admin */
        $admin = auth('admin')->user();
        $adminId = $admin?->id;

        if (! empty($validated['access_expires_at'])) {
            $startsAt = Carbon::now();
            $expiresAt = Carbon::parse($validated['access_expires_at']);

            $user->courses()->syncWithoutDetaching([
                $course->id => [
                    'access_starts_at' => $startsAt,
                    'access_expires_at' => $expiresAt,
                    'status' => 'active',
                    'granted_by' => $adminId,
                ],
            ]);
        } else {
            $days = ! empty($validated['duration_days']) ? (int) $validated['duration_days'] : null;
            $this->enrollmentService->enroll($user, $course, $days, $adminId);
        }

        return back()->with('success', __('تم منح صلاحية الوصول للكورس (:title) بنجاح.', ['title' => $course->title]));
    }

    /**
     * Update an existing course subscription (dates & status).
     */
    public function updateEnrollment(UpdateStudentEnrollmentRequest $request, User $user, Course $course): RedirectResponse
    {
        $validated = $request->validated();

        $user->courses()->updateExistingPivot($course->id, [
            'access_expires_at' => Carbon::parse($validated['access_expires_at']),
            'status' => $validated['status'],
        ]);

        return back()->with('success', __('تم تحديث اشتراك وصلاحية الكورس بنجاح.'));
    }

    /**
     * Revoke / cancel student access to a course.
     */
    public function revokeEnrollment(User $user, Course $course): RedirectResponse
    {
        $user->courses()->detach($course->id);

        return back()->with('success', __('تم إلغاء صلاحية الوصول إلى كورس (:title) بنجاح.', ['title' => $course->title]));
    }

    /**
     * Manually issue an official certificate for a student with uploaded certificate file.
     */
    public function issueCertificate(IssueStudentCertificateRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        /** @var Course $course */
        $course = Course::query()->findOrFail($validated['course_id']);

        $filePath = null;
        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
            $stored = $file?->store("certificates/{$user->id}", 'public');
            $filePath = is_string($stored) ? $stored : null;
        }

        $percentage = isset($validated['completion_percentage'])
            ? (float) $validated['completion_percentage']
            : null;

        $issuedAt = ! empty($validated['issued_at'])
            ? Carbon::parse($validated['issued_at'])
            : null;

        $certificate = $this->certificateService->forceIssueCertificate(
            $user,
            $course,
            $percentage,
            $filePath,
            $issuedAt
        );

        return back()->with('success', __('تم رفع وتوثيق الشهادة بنجاح برقم كودي (:code).', ['code' => $certificate->certificate_code]));
    }

    /**
     * Revoke / delete an issued certificate.
     */
    public function destroyCertificate(User $user, Certificate $certificate): RedirectResponse
    {
        if ($certificate->user_id !== $user->id) {
            abort(403);
        }

        if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
            Storage::disk('public')->delete($certificate->file_path);
        }

        $certificate->delete();

        return back()->with('success', __('تم حذف الشهادة بنجاح.'));
    }

    /**
     * Update or reset video watch progress for a student.
     */
    public function updateVideoProgress(UpdateVideoProgressRequest $request, User $user, Video $video): RedirectResponse
    {
        $validated = $request->validated();
        $action = $validated['action'];

        $progress = VideoProgress::firstOrNew([
            'user_id' => $user->id,
            'video_id' => $video->id,
        ]);

        if ($action === 'complete') {
            $progress->watched_seconds = max(1, $video->duration_seconds);
            $progress->completion_percentage = 100.00;
            $progress->is_completed = true;
            $progress->last_watched_at = Carbon::now();
            $progress->save();
            $msg = __('تم تعيين الدرس (:title) كمكتمل بنسبة 100%.', ['title' => $video->title_ar]);
        } else {
            $progress->watched_seconds = 0;
            $progress->completion_percentage = 0.00;
            $progress->is_completed = false;
            $progress->last_watched_at = null;
            $progress->save();
            $msg = __('تمت إعادة تعيين تقدم مشاهدة الدرس (:title) بنجاح.', ['title' => $video->title_ar]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Delete student account permanently after verifying admin password.
     */
    public function destroy(DeleteStudentRequest $request, User $user): RedirectResponse
    {
        $request->validated();
        $name = $user->name;

        // Revoke student Sanctum API tokens if existing
        try {
            if (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }
        } catch (\Throwable) {
            // Ignore if tokens table is not applicable
        }

        // Delete student avatar file if stored locally
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Delete any manual certificate files uploaded for this student
        foreach ($user->certificates as $certificate) {
            if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
                Storage::disk('public')->delete($certificate->file_path);
            }
        }

        $user->delete();

        return redirect()->route('admin.students.index')->with('success', __('تم حذف حساب الطالب (:name) وجميع بياناته بنجاح.', ['name' => $name]));
    }
}
