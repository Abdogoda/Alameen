<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Models\Video;
use App\Services\Certificate\CertificateService;
use App\Services\Clinical\ClinicalTaskService;
use App\Services\Course\CourseEnrollmentService;
use App\Services\Payment\PaymentReceiptService;
use App\Services\Video\BunnyStreamService;
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
        protected ClinicalTaskService $clinicalTaskService
    ) {}

    /**
     * Submit a manual payment receipt for a course.
     */
    public function submitReceipt(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'string', 'in:vodafone_cash,instapay,bank_transfer'],
            'amount' => ['required', 'numeric', 'min:1'],
            'sender_account' => ['nullable', 'string', 'max:100'],
            'transaction_reference' => ['nullable', 'string', 'max:100'],
            'receipt_image' => ['required', 'image', 'max:5120'], // up to 5MB
        ]);

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
    public function lesson(Request $request, ?Course $course = null, ?Video $video = null): View
    {
        /** @var User $user */
        $user = $request->user();

        // If no course passed, load first enrolled course or default course
        $course = $course ?? $user->courses()->first() ?? Course::firstOrFail();
        $video = $video ?? $course->videos()->orderBy('sort_order')->firstOrFail();

        // 1. Sequential Video Lock Verification
        $canAccess = $this->progressService->canAccessVideo($user, $video);

        // 2. Generate secure Bunny.net stream embed URL
        $embedUrl = $canAccess ? $this->bunnyService->getIframeEmbedUrl($video) : '';

        // 3. Prepare watermark payload for Anti Screen-Recording Canvas Overlay
        $watermarkPayload = $this->bunnyService->getWatermarkPayload($user, $request->ip());

        // 4. Progress list for all course videos
        $videos = $course->videos()->orderBy('sort_order')->get();
        $currentProgress = $video->progressForUser($user->id);

        return view('web.pages.lesson', compact(
            'course',
            'video',
            'videos',
            'canAccess',
            'embedUrl',
            'watermarkPayload',
            'currentProgress'
        ));
    }

    /**
     * AJAX/API endpoint to record video watch progress in real time.
     */
    public function recordProgress(Request $request, Video $video): JsonResponse
    {
        $validated = $request->validate([
            'watched_seconds' => ['required', 'integer', 'min:0'],
        ]);

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
        $submission = $task ? TaskSubmission::where('task_id', $task->id)->where('user_id', $user->id)->first() : null;

        return view('web.pages.assignment', compact('task', 'submission'));
    }

    /**
     * Submit an audio recording or document for a clinical voice exercise.
     */
    public function submitAssignment(Request $request, ClinicalTask $task): RedirectResponse
    {
        $request->validate([
            'audio_file' => ['nullable', 'file', 'max:25600'], // 25MB
            'audio_data' => ['nullable', 'string'],
            'student_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $fileToSubmit = null;
        if ($request->hasFile('audio_file')) {
            $fileToSubmit = $request->file('audio_file');
        } elseif ($request->filled('audio_data')) {
            // Base64 recorded blob
            $data = $request->input('audio_data');
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
     * Certificates index showing eligibility progress across enrolled courses.
     */
    public function certificates(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $courses = $user->courses()->with('videos')->get();
        $certificates = $user->certificates()->with('course')->get();

        $courseProgressData = [];
        foreach ($courses as $c) {
            $pct = $this->progressService->getCourseProgressPercentage($user, $c);
            $cert = $certificates->firstWhere('course_id', $c->id);
            $courseProgressData[] = [
                'course' => $c,
                'progress' => $pct,
                'certificate' => $cert,
                'is_eligible' => $pct >= CertificateService::MIN_PROGRESS_FOR_CERTIFICATE,
            ];
        }

        return view('web.pages.certificates', compact('courses', 'certificates', 'courseProgressData'));
    }

    /**
     * View full certificate ready for presentation and print.
     */
    public function certificate(Request $request, ?Certificate $certificate = null): View
    {
        /** @var User $user */
        $user = $request->user();

        $certificate = $certificate ?? $user->certificates()->latest()->first();

        if (! $certificate) {
            return redirect()->route('certificates.index')->with('info', __('لم يتم إصدار شهادة لك بعد. أكمل مشاهدة ٩٥٪ من أي كورس لإصدار شهادتك فوراً.'));
        }

        return view('web.pages.certificate', compact('certificate'));
    }
}
