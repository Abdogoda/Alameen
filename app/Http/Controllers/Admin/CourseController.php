<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApplyVideoSettingsRequest;
use App\Http\Requests\Admin\AssignCourseUsersRequest;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\StoreLevelRequest;
use App\Http\Requests\Admin\StoreVideoRequest;
use App\Http\Requests\Admin\UpdateCoursePriceRequest;
use App\Http\Requests\Admin\UpdateCoursePromoMuxRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Http\Requests\Admin\UpdateLevelRequest;
use App\Http\Requests\Admin\UpdateVideoRequest;
use App\Http\Requests\Admin\UploadCoursePromoChunkRequest;
use App\Http\Requests\Admin\UploadLessonChunkRequest;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\Level;
use App\Models\User;
use App\Models\Video;
use App\Services\Course\CourseEnrollmentService;
use App\Services\Video\ChunkedUploadService;
use App\Services\Video\Drivers\MuxDriver;
use App\Services\Video\MuxTokenService;
use App\Services\Video\MuxVideoService;
use App\Services\Video\VideoServiceManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
        protected VideoServiceManager $videoManager,
        protected ChunkedUploadService $chunkedUploadService,
        protected MuxVideoService $muxService,
        protected CourseEnrollmentService $enrollmentService,
    ) {}

    /**
     * Display courses catalogue with stats.
     */
    public function index(): View
    {
        $courses = Course::withCount(['levels', 'videos', 'users'])
            ->orderBy('sort_order')
            ->get();

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Display a specific course's curriculum view (Dynamic Levels & Lessons Architecture).
     */
    public function show(Course $course): View
    {
        $course->load([
            'levels.videos.clinicalTask',
            'videos' => function ($q) {
                $q->whereNull('level_id')->with('clinicalTask')->orderBy('sort_order');
            },
        ]);

        return view('admin.courses.show', compact('course'));
    }

    /**
     * Display the course edit page.
     */
    public function edit(Course $course): View
    {
        $course->loadCount(['levels', 'videos', 'users']);
        $isMuxConfigured = $this->videoManager->isMuxConfigured();

        return view('admin.courses.edit', compact('course', 'isMuxConfigured'));
    }

    /**
     * Store a newly created course.
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['slug'] = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : (! empty($validated['title_en']) ? Str::slug($validated['title_en']) : 'course-'.strtolower(Str::random(8)));

        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['auto_enroll_students'] = $request->boolean('auto_enroll_students', false);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('courses/thumbnails', 'public');
            if (is_string($path)) {
                $validated['thumbnail'] = Storage::url($path);
            }
        }

        // Handle promo video file upload to MUX or storage
        if ($request->hasFile('promo_video_file')) {
            $uploadResult = $this->muxService->handleVideoUpload($request->file('promo_video_file'));
            $validated['promo_video_source'] = $uploadResult['video_source'];
            $validated['promo_video_url'] = $uploadResult['video_url'];
            $validated['mux_playback_id'] = $uploadResult['mux_playback_id'];
            $validated['mux_asset_id'] = $uploadResult['mux_asset_id'];
        } elseif (! empty($validated['promo_video_source']) && $validated['promo_video_source'] === 'local' && ! empty($validated['promo_video_url'])) {
            $validated['promo_video_source'] = 'local';
            $validated['promo_video_url'] = $validated['promo_video_url'];
            $validated['mux_playback_id'] = null;
            $validated['mux_asset_id'] = null;
        } elseif (! empty($validated['mux_playback_id'])) {
            $validated['promo_video_source'] = 'mux';
            $validated['promo_video_url'] = "https://stream.mux.com/{$validated['mux_playback_id']}.m3u8";
            $validated['mux_playback_id'] = $validated['mux_playback_id'];
            $validated['mux_asset_id'] = $validated['mux_asset_id'] ?? null;
        } elseif (! empty($validated['promo_video_url'])) {
            $parsed = $this->muxService->parseVideoInput($validated['promo_video_url']);
            $validated['promo_video_source'] = $parsed['video_source'];
            $validated['promo_video_url'] = $parsed['video_url'];
            $validated['mux_playback_id'] = $parsed['mux_playback_id'];
            if ($parsed['video_source'] !== 'mux') {
                $validated['mux_asset_id'] = null;
            }
        }

        $course = Course::create($validated);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', __('تم إنشاء الكورس التدريبي بنجاح. يمكنك الآن إضافة المستويات والدروس.'));
    }

    /**
     * Update an existing course.
     */
    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['auto_enroll_students'] = $request->boolean('auto_enroll_students', false);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('courses/thumbnails', 'public');
            if (is_string($path)) {
                $validated['thumbnail'] = Storage::url($path);
            }
        }

        // Handle promo video file upload to MUX or storage
        if ($request->hasFile('promo_video_file')) {
            $uploadResult = $this->muxService->handleVideoUpload($request->file('promo_video_file'));
            $validated['promo_video_source'] = $uploadResult['video_source'];
            $validated['promo_video_url'] = $uploadResult['video_url'];
            $validated['mux_playback_id'] = $uploadResult['mux_playback_id'];
            $validated['mux_asset_id'] = $uploadResult['mux_asset_id'];
        } elseif (! empty($validated['promo_video_source']) && $validated['promo_video_source'] === 'local' && ! empty($validated['promo_video_url'])) {
            $validated['promo_video_source'] = 'local';
            $validated['promo_video_url'] = $validated['promo_video_url'];
            $validated['mux_playback_id'] = null;
            $validated['mux_asset_id'] = null;
        } elseif (! empty($validated['mux_playback_id'])) {
            $validated['promo_video_source'] = 'mux';
            $validated['promo_video_url'] = "https://stream.mux.com/{$validated['mux_playback_id']}.m3u8";
            $validated['mux_playback_id'] = $validated['mux_playback_id'];
            $validated['mux_asset_id'] = $validated['mux_asset_id'] ?? $course->mux_asset_id;
        } elseif (! empty($validated['promo_video_url'])) {
            $parsed = $this->muxService->parseVideoInput($validated['promo_video_url']);
            $validated['promo_video_source'] = $parsed['video_source'];
            $validated['promo_video_url'] = $parsed['video_url'];
            $validated['mux_playback_id'] = $parsed['mux_playback_id'];
            if ($parsed['video_source'] !== 'mux') {
                $validated['mux_asset_id'] = null;
            }
        }

        // Handle custom certificate background upload
        if ($request->hasFile('certificate_custom_bg_file')) {
            $path = $request->file('certificate_custom_bg_file')->store('certificates/templates', 'public');
            if (is_string($path)) {
                $validated['certificate_custom_bg'] = Storage::url($path);
            }
        }

        $course->update($validated);

        return back()->with('success', __('تم تحديث بيانات الكورس التدريبي بنجاح.'));
    }

    /**
     * Fast price update from quick modal.
     */
    public function updatePrice(UpdateCoursePriceRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();

        $course->update([
            'price' => $validated['currentPrice'],
            'original_price' => $validated['originalPrice'] ?? null,
        ]);

        return back()->with('success', __('تم تحديث ونشر السعر الجديد بنجاح.'));
    }

    /**
     * Toggle auto-enroll status for all students.
     */
    public function toggleAutoEnroll(Course $course): RedirectResponse
    {
        $course->auto_enroll_students = ! $course->auto_enroll_students;
        $course->save();

        $message = $course->auto_enroll_students
            ? __('تم تفعيل إتاحة الكورس تلقائياً لجميع الطلاب بنجاح.')
            : __('تم إلغاء الإتاحة التلقائية؛ أصبح الكورس يتطلب اشتراكاً ورفع إيصال للدخول.');

        return back()->with('success', $message);
    }

    /**
     * Delete a course and all associated MUX assets.
     */
    public function destroy(Course $course): RedirectResponse
    {
        // Clean up MUX assets for all course videos
        foreach ($course->videos as $video) {
            if (! empty($video->mux_asset_id)) {
                $this->muxService->deleteAsset($video->mux_asset_id);
            }
        }

        // Clean up course promo video on MUX if applicable
        if (! empty($course->mux_asset_id)) {
            $this->muxService->deleteAsset($course->mux_asset_id);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', __('تم حذف الكورس التدريبي ومزامنة تنظيف الفيديوهات من MUX بنجاح.'));
    }

    /**
     * Store a new dynamic curriculum level.
     */
    public function storeLevel(StoreLevelRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();

        $validated['course_id'] = $course->id;
        $validated['sort_order'] = $validated['sort_order'] ?? (($course->levels()->max('sort_order') ?? 0) + 1);
        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['auto_enroll_students'] = $request->boolean('auto_enroll_students', false);

        $course->levels()->create($validated);

        return back()->with('success', __('تمت إضافة المستوى التدريبي الجديد بنجاح.'));
    }

    /**
     * Update an existing level.
     */
    public function updateLevel(UpdateLevelRequest $request, Course $course, Level $level): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_published'] = $request->has('is_published')
            ? $request->boolean('is_published')
            : $level->is_published;

        $level->update($validated);

        return back()->with('success', __('تم تحديث المستوى التدريبي بنجاح.'));
    }

    /**
     * Toggle publish status of a curriculum level.
     */
    public function togglePublishLevel(Course $course, Level $level): RedirectResponse
    {
        $level->update(['is_published' => ! $level->is_published]);

        $statusMsg = $level->is_published
            ? __('تم نشر المستوى التدريبي بنجاح.')
            : __('تم إخفاء المستوى التدريبي وتحويله إلى مسودة بنجاح.');

        return back()->with('success', $statusMsg);
    }

    /**
     * Delete a level.
     */
    public function destroyLevel(Course $course, Level $level): RedirectResponse
    {
        $level->delete();

        return back()->with('success', __('تم حذف المستوى التدريبي بنجاح.'));
    }

    /**
     * Store a video/lesson inside a level or course with MUX or direct link.
     */
    /**
     * Show dedicated page to add a new lesson to a course.
     */
    public function createLesson(Request $request, Course $course): View
    {
        $course->load('levels');
        $selectedLevelId = $request->query('level_id');
        $activeDriver = $this->videoManager->getActiveDriver();
        $isMuxConfigured = $this->videoManager->isMuxConfigured();

        return view('admin.courses.lessons.create', [
            'course' => $course,
            'selectedLevelId' => $selectedLevelId ? (int) $selectedLevelId : null,
            'activeDriverName' => $activeDriver->name(),
            'activeDriverTitle' => $activeDriver->title(),
            'isMuxConfigured' => $isMuxConfigured,
        ]);
    }

    /**
     * Show dedicated page to edit an existing lesson.
     */
    public function editLesson(Course $course, Video $video): View
    {
        abort_if($video->course_id !== $course->id, 404);

        $course->load('levels');
        $video->load('clinicalTask');
        $activeDriver = $this->videoManager->getActiveDriver();
        $isMuxConfigured = $this->videoManager->isMuxConfigured();

        return view('admin.courses.lessons.edit', [
            'course' => $course,
            'video' => $video,
            'activeDriverName' => $activeDriver->name(),
            'activeDriverTitle' => $activeDriver->title(),
            'isMuxConfigured' => $isMuxConfigured,
        ]);
    }

    /**
     * Create a MUX Direct Upload URL for client-side uploads.
     */
    public function getMuxDirectUploadUrl(Course $course): JsonResponse
    {
        Log::info('[VIDEO_UPLOAD] Mux Direct Upload URL requested by client', [
            'course_id' => $course->id,
            'course_title' => $course->title_ar,
            'client_ip' => request()->ip(),
            'host' => request()->getSchemeAndHttpHost(),
            'is_mux_configured' => $this->videoManager->isMuxConfigured(),
        ]);

        $muxDriver = $this->videoManager->driver('mux');

        if (! $muxDriver->isConfigured()) {
            Log::warning('[VIDEO_UPLOAD] Mux Direct Upload URL rejected: Mux driver is not configured.', [
                'course_id' => $course->id,
                'has_token_id' => ! empty($muxDriver->getTokenId()),
                'has_token_secret' => ! empty($muxDriver->getTokenSecret()),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('خدمة MUX غير مهيأة على السيرفر، يتم الرفع المباشر عبر السيرفر المحلي.'),
            ], 400);
        }

        $corsOrigin = config('video.drivers.mux.cors_origin', '*') ?: (request()->headers->get('Origin') ?: '*');
        $upload = $muxDriver->createDirectUploadUrl($corsOrigin);
        if (! $upload || empty($upload['url'])) {
            Log::error('[VIDEO_UPLOAD] Failed to generate MUX Direct Upload URL from MuxDriver.', [
                'course_id' => $course->id,
                'cors_origin' => $corsOrigin,
            ]);

            return response()->json([
                'success' => false,
                'message' => __('فشل إنشاء رابط الرفع لمنصة MUX.'),
            ], 500);
        }

        Log::info('[VIDEO_UPLOAD] Mux Direct Upload URL successfully created', [
            'course_id' => $course->id,
            'upload_id' => $upload['id'],
            'upload_url' => substr($upload['url'], 0, 40).'...',
        ]);

        return response()->json([
            'success' => true,
            'upload_url' => $upload['url'],
            'upload_id' => $upload['id'],
        ]);
    }

    /**
     * Check the status of a MUX direct upload session from client.
     */
    public function checkMuxUploadStatus(Course $course, string $uploadId): JsonResponse
    {
        Log::info('[VIDEO_UPLOAD] Checking MUX direct upload status', [
            'course_id' => $course->id,
            'upload_id' => $uploadId,
        ]);

        /** @var MuxDriver $muxDriver */
        $muxDriver = $this->videoManager->driver('mux');

        if (! $muxDriver->isConfigured()) {
            Log::warning('[VIDEO_UPLOAD] checkMuxUploadStatus: Mux driver not configured.', ['upload_id' => $uploadId]);

            return response()->json(['success' => false, 'message' => __('خدمة MUX غير مهيأة.')], 400);
        }

        $upload = $muxDriver->getUpload($uploadId);
        if (! $upload) {
            Log::warning('[VIDEO_UPLOAD] checkMuxUploadStatus: Upload session not found on MUX.', ['upload_id' => $uploadId]);

            return response()->json(['success' => false, 'message' => __('لم يتم العثور على جلسة الرفع.')], 404);
        }

        $assetId = $upload['asset_id'] ?? null;
        $uploadStatus = $upload['status'] ?? 'waiting';

        Log::info('[VIDEO_UPLOAD] MUX upload session polled', [
            'upload_id' => $uploadId,
            'upload_status' => $uploadStatus,
            'asset_id' => $assetId,
        ]);

        if (! $assetId) {
            return response()->json([
                'success' => true,
                'status' => 'uploading_or_processing',
                'upload_status' => $uploadStatus,
                'asset_id' => null,
                'playback_id' => null,
            ]);
        }

        $asset = $muxDriver->getAsset($assetId);
        $assetStatus = $asset['status'] ?? 'preparing';

        $playbackIds = is_array($asset['playback_ids'] ?? null) ? $asset['playback_ids'] : [];
        $signedPlayback = null;
        foreach ($playbackIds as $playback) {
            if (is_array($playback) && ($playback['policy'] ?? null) === 'signed') {
                $signedPlayback = $playback;
                break;
            }
        }
        $playbackId = $signedPlayback['id'] ?? ($playbackIds[0]['id'] ?? null);
        $durationSeconds = isset($asset['duration']) ? (int) round((float) $asset['duration']) : null;

        $playbackToken = null;
        if ($playbackId) {
            $playbackToken = app(MuxTokenService::class)->generatePlaybackToken($playbackId);
        }

        Log::info('[VIDEO_UPLOAD] MUX Asset details retrieved', [
            'asset_id' => $assetId,
            'asset_status' => $assetStatus,
            'playback_id' => $playbackId,
            'duration_seconds' => $durationSeconds,
            'has_playback_token' => ! empty($playbackToken),
        ]);

        return response()->json([
            'success' => true,
            'status' => $assetStatus,
            'upload_status' => $uploadStatus,
            'asset_id' => $assetId,
            'playback_id' => $playbackId,
            'playback_token' => $playbackToken,
            'duration_seconds' => $durationSeconds,
            'duration_minutes' => $durationSeconds ? (int) ceil($durationSeconds / 60) : null,
        ]);
    }

    /**
     * Handle asynchronous chunked promo video file upload with real-time progress and resilient processing.
     */
    public function uploadPromoChunk(UploadCoursePromoChunkRequest $request, Course $course): JsonResponse
    {
        $validated = $request->validated();

        $uploadId = $this->chunkedUploadService->sanitizeUploadId($validated['upload_id']);
        $chunkIndex = (int) $validated['chunk_index'];
        $totalChunks = (int) $validated['total_chunks'];
        $originalFilename = $validated['original_filename'];

        // 1. Save this chunk
        $this->chunkedUploadService->saveChunk($uploadId, $chunkIndex, $request->file('file_chunk'));

        // 2. Check if all chunks have arrived
        if (! $this->chunkedUploadService->isComplete($uploadId, $totalChunks)) {
            if (config('database.default') !== 'sqlite') {
                DB::disconnect();
            }

            return response()->json([
                'status' => 'chunk_received',
                'chunk_index' => $chunkIndex,
                'total_chunks' => $totalChunks,
                'progress_percentage' => round((($chunkIndex + 1) / $totalChunks) * 100, 1),
            ]);
        }

        // 3. All chunks received -> Merge & Process promo video file
        try {
            @ini_set('max_execution_time', '0');
            @set_time_limit(0);
            @ini_set('memory_limit', '1024M');
            if (function_exists('ignore_user_abort')) {
                @ignore_user_abort(true);
            }

            $mergedFile = $this->chunkedUploadService->mergeChunks($uploadId, $totalChunks, $originalFilename);

            // Process video locally for instant response & zero gateway timeouts
            $uploadResult = $this->videoManager->handleVideoUpload($mergedFile, 'local');

            // Clean up temporary merged file
            @unlink($mergedFile->getRealPath());

            // If replacing old MUX promo video asset, clean it up
            if (! empty($course->mux_asset_id) && ! empty($uploadResult['mux_asset_id']) && $course->mux_asset_id !== $uploadResult['mux_asset_id']) {
                $this->muxService->deleteAsset($course->mux_asset_id);
            }

            // Immediately update course promo video fields
            $course->update([
                'promo_video_source' => $uploadResult['video_source'],
                'promo_video_url' => $uploadResult['video_url'],
                'mux_playback_id' => $uploadResult['mux_playback_id'] ?? null,
                'mux_asset_id' => $uploadResult['mux_asset_id'] ?? null,
            ]);

            $previewToken = null;
            if (! empty($uploadResult['mux_playback_id'])) {
                $previewToken = app(MuxTokenService::class)->generatePlaybackToken($uploadResult['mux_playback_id']);
            }

            return response()->json([
                'status' => 'completed',
                'message' => $uploadResult['driver_used'] === 'mux'
                    ? __('تمت معالجة وتهيئة الفيديو التعريفي بنجاح عبر MUX.')
                    : __('تم حفظ ومعالجة الفيديو التعريفي بنجاح على سيرفر المنصة المحلي.'),
                'driver_used' => $uploadResult['driver_used'],
                'promo_video_source' => $uploadResult['video_source'],
                'promo_video_url' => $uploadResult['video_url'],
                'mux_playback_id' => $uploadResult['mux_playback_id'] ?? null,
                'mux_asset_id' => $uploadResult['mux_asset_id'] ?? null,
                'playback_token' => $previewToken,
                'original_filename' => $originalFilename,
            ]);
        } catch (\Throwable $e) {
            $this->chunkedUploadService->cleanup($uploadId);

            return response()->json([
                'status' => 'error',
                'message' => __('حدث خطأ أثناء معالجة وتجميع ملف الفيديو: :error', ['error' => $e->getMessage()]),
            ], 500);
        }
    }

    /**
     * Update course promo video attributes from client-side MUX direct upload.
     */
    public function updatePromoMux(UpdateCoursePromoMuxRequest $request, Course $course): JsonResponse
    {
        $validated = $request->validated();

        if (! empty($course->mux_asset_id) && ! empty($validated['mux_asset_id']) && $course->mux_asset_id !== $validated['mux_asset_id']) {
            $this->muxService->deleteAsset($course->mux_asset_id);
        }

        $course->update([
            'promo_video_source' => 'mux',
            'promo_video_url' => "https://stream.mux.com/{$validated['mux_playback_id']}.m3u8",
            'mux_playback_id' => $validated['mux_playback_id'],
            'mux_asset_id' => $validated['mux_asset_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('تم ربط الفيديو التعريفي بمنصة MUX بنجاح.'),
        ]);
    }

    /**
     * Handle asynchronous chunked video file upload with real-time progress and resilient processing.
     */
    public function uploadLessonChunk(UploadLessonChunkRequest $request, Course $course): JsonResponse
    {
        $validated = $request->validated();

        $uploadId = $this->chunkedUploadService->sanitizeUploadId($validated['upload_id']);
        $chunkIndex = (int) $validated['chunk_index'];
        $totalChunks = (int) $validated['total_chunks'];
        $originalFilename = $validated['original_filename'];

        // 1. Save this chunk
        $this->chunkedUploadService->saveChunk($uploadId, $chunkIndex, $request->file('file_chunk'));

        if ($chunkIndex === 0) {
            Log::info('[VIDEO_UPLOAD] Local chunked upload initiated', [
                'course_id' => $course->id,
                'upload_id' => $uploadId,
                'filename' => $originalFilename,
                'total_chunks' => $totalChunks,
            ]);
        }

        // 2. Check if all chunks have arrived
        if (! $this->chunkedUploadService->isComplete($uploadId, $totalChunks)) {
            // Disconnect database connection immediately to prevent connection exhaustion on shared hosting (avoid on sqlite :memory: tests)
            if (config('database.default') !== 'sqlite') {
                DB::disconnect();
            }

            return response()->json([
                'status' => 'chunk_received',
                'chunk_index' => $chunkIndex,
                'total_chunks' => $totalChunks,
                'progress_percentage' => round((($chunkIndex + 1) / $totalChunks) * 100, 1),
            ]);
        }

        Log::info('[VIDEO_UPLOAD] All video chunks received, merging files', [
            'course_id' => $course->id,
            'upload_id' => $uploadId,
            'total_chunks' => $totalChunks,
            'filename' => $originalFilename,
        ]);

        // 3. All chunks received -> Merge & Process video file
        try {
            @ini_set('max_execution_time', '0');
            @set_time_limit(0);
            @ini_set('memory_limit', '1024M');
            if (function_exists('ignore_user_abort')) {
                @ignore_user_abort(true);
            }

            $mergedFile = $this->chunkedUploadService->mergeChunks($uploadId, $totalChunks, $originalFilename);

            // If chunks were uploaded to the local server, save to local server storage directly
            // to ensure an instantaneous response (< 1s) and prevent 504 Gateway Timeouts from reverse proxies (e.g. Hostinger hcdn).
            // Direct browser-to-MUX upload is handled asynchronously by the client.
            $uploadResult = $this->videoManager->handleVideoUpload($mergedFile, 'local');

            Log::info('[VIDEO_UPLOAD] Chunks merged and saved to local server', [
                'course_id' => $course->id,
                'upload_id' => $uploadId,
                'video_source' => $uploadResult['video_source'],
                'video_url' => $uploadResult['video_url'],
                'driver_used' => $uploadResult['driver_used'],
            ]);

            // Clean up temporary merged file
            @unlink($mergedFile->getRealPath());

            // If a specific video is being edited, update it immediately in database so refresh keeps it
            if (! empty($validated['video_id'])) {
                /** @var Video|null $targetVideo */
                $targetVideo = Video::where('course_id', $course->id)->where('id', $validated['video_id'])->first();
                if ($targetVideo) {
                    $targetVideo->update([
                        'video_source' => $uploadResult['video_source'],
                        'video_url' => $uploadResult['video_url'],
                        'mux_playback_id' => $uploadResult['mux_playback_id'] ?? null,
                        'mux_asset_id' => $uploadResult['mux_asset_id'] ?? null,
                        'bunny_video_id' => null,
                    ]);
                }
            }

            $previewToken = null;
            if (! empty($uploadResult['mux_playback_id'])) {
                $previewToken = app(MuxTokenService::class)->generatePlaybackToken($uploadResult['mux_playback_id']);
            }

            return response()->json([
                'status' => 'completed',
                'message' => $uploadResult['driver_used'] === 'mux'
                    ? __('تمت معالجة وتهيئة الفيديو بنجاح عبر MUX.')
                    : __('تم حفظ ومعالجة الفيديو بنجاح على سيرفر المنصة المحلي.'),
                'driver_used' => $uploadResult['driver_used'],
                'video_source' => $uploadResult['video_source'],
                'video_url' => $uploadResult['video_url'],
                'mux_playback_id' => $uploadResult['mux_playback_id'] ?? null,
                'mux_asset_id' => $uploadResult['mux_asset_id'] ?? null,
                'playback_token' => $previewToken,
                'original_filename' => $originalFilename,
            ]);
        } catch (\Throwable $e) {
            $this->chunkedUploadService->cleanup($uploadId);

            return response()->json([
                'status' => 'error',
                'message' => __('حدث خطأ أثناء معالجة وتجميع ملف الفيديو: :error', ['error' => $e->getMessage()]),
            ], 500);
        }
    }

    /**
     * Store a newly created lesson/video in storage.
     */
    public function storeVideo(StoreVideoRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();

        $durationMinutes = $request->filled('duration_minutes') ? (int) $request->input('duration_minutes') : 20;
        $durationSeconds = $request->filled('duration_seconds') ? (int) $request->input('duration_seconds') : ($durationMinutes * 60);

        $videoData = [
            'course_id' => $course->id,
            'level_id' => $validated['level_id'] ?? null,
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'] ?? null,
            'slug' => Str::slug(($validated['title_en'] ?? null) ?: 'lesson-'.Str::random(6)),
            'duration_seconds' => $durationSeconds,
            'min_completion_percentage' => (int) ($validated['min_completion_percentage'] ?? 80),
            'sort_order' => $validated['sort_order'] ?? (($course->videos()->max('sort_order') ?? 0) + 1),
            'is_free_preview' => $request->boolean('is_free_preview', false),
            'is_published' => $request->boolean('is_published', true),
            'video_source' => $validated['video_source'] ?? 'link',
            'video_url' => $validated['video_url'] ?? null,
            'mux_playback_id' => $validated['mux_playback_id'] ?? null,
            'mux_asset_id' => $validated['mux_asset_id'] ?? null,
            'mux_upload_id' => $validated['mux_upload_id'] ?? null,
        ];

        // 1. Direct standard video file upload fallback
        if ($request->hasFile('video_file')) {
            $uploadResult = $this->videoManager->handleVideoUpload($request->file('video_file'));
            $videoData['video_source'] = $uploadResult['video_source'];
            $videoData['video_url'] = $uploadResult['video_url'];
            $videoData['mux_playback_id'] = $uploadResult['mux_playback_id'];
            $videoData['mux_asset_id'] = $uploadResult['mux_asset_id'];
        } elseif (! empty($validated['mux_playback_id'])) {
            $videoData['video_source'] = 'mux';
            $videoData['mux_playback_id'] = trim($validated['mux_playback_id']);
            $videoData['video_url'] = "https://stream.mux.com/{$videoData['mux_playback_id']}.m3u8";
        } elseif (! empty($validated['mux_upload_id'])) {
            $videoData['video_source'] = 'mux';
            $videoData['mux_upload_id'] = trim($validated['mux_upload_id']);
            if (empty($videoData['video_url'])) {
                $videoData['video_url'] = 'mux://upload/'.$videoData['mux_upload_id'];
            }
        } elseif (! empty($validated['video_url']) && empty($validated['video_source'])) {
            $parsed = $this->videoManager->parseVideoInput($validated['video_url']);
            $videoData['video_source'] = $parsed['video_source'];
            $videoData['video_url'] = $parsed['video_url'];
            $videoData['mux_playback_id'] = $parsed['mux_playback_id'];
        }

        Log::info('[VIDEO_UPLOAD] storeVideo form submitted', [
            'course_id' => $course->id,
            'title_ar' => $validated['title_ar'] ?? null,
            'input_video_source' => $validated['video_source'] ?? null,
            'input_mux_playback_id' => $validated['mux_playback_id'] ?? null,
            'input_mux_asset_id' => $validated['mux_asset_id'] ?? null,
            'input_mux_upload_id' => $validated['mux_upload_id'] ?? null,
            'has_file' => $request->hasFile('video_file'),
            'input_video_url' => $validated['video_url'] ?? null,
        ]);

        $video = Video::create($videoData);

        Log::info('[VIDEO_UPLOAD] Video record created in database', [
            'video_id' => $video->id,
            'course_id' => $course->id,
            'final_video_source' => $video->video_source,
            'video_url' => $video->video_url,
            'mux_playback_id' => $video->mux_playback_id,
            'mux_asset_id' => $video->mux_asset_id,
        ]);

        // Optional attached Clinical Task
        if (! empty($validated['task_title_ar'])) {
            ClinicalTask::create([
                'course_id' => $course->id,
                'video_id' => $video->id,
                'title_ar' => $validated['task_title_ar'],
                'title_en' => ! empty($validated['title_en']) ? 'Task: '.$validated['title_en'] : null,
                'instructions_ar' => $validated['task_instructions_ar'] ?? __('قم بتسجيل مقطع صوتي يوضح التطبيق العملي للدرس.'),
                'max_score' => 100,
            ]);
        }

        return redirect()->route('admin.courses.show', $course)->with('success', __('تمت إضافة الدرس بنجاح إلى المنهج.'));
    }

    /**
     * Update an existing video/lesson.
     */
    public function updateVideo(UpdateVideoRequest $request, Course $course, Video $video): RedirectResponse
    {
        $validated = $request->validated();

        $updateData = [
            'level_id' => $validated['level_id'] ?? $video->level_id,
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'] ?? $video->title_en,
            'is_free_preview' => $request->boolean('is_free_preview', false),
            'is_published' => $request->has('is_published') ? $request->boolean('is_published') : $video->is_published,
        ];

        if ($request->filled('min_completion_percentage')) {
            $updateData['min_completion_percentage'] = (int) $request->input('min_completion_percentage');
        }

        if ($request->filled('duration_minutes')) {
            $updateData['duration_seconds'] = ((int) $request->input('duration_minutes')) * 60;
        }

        $uploadTab = $validated['upload_tab'] ?? null;

        if ($request->hasFile('video_file')) {
            $uploadResult = $this->videoManager->handleVideoUpload($request->file('video_file'));
            $updateData['video_source'] = $uploadResult['video_source'];
            $updateData['video_url'] = $uploadResult['video_url'];
            $updateData['mux_playback_id'] = $uploadResult['mux_playback_id'];
            $updateData['mux_asset_id'] = $uploadResult['mux_asset_id'];
            $updateData['bunny_video_id'] = null;
        } elseif ($uploadTab === 'chunked' || ($uploadTab === null && ($validated['video_source'] ?? '') === 'local')) {
            $videoSource = $validated['video_source'] ?? 'local';
            $updateData['video_source'] = $videoSource;
            if ($videoSource === 'local') {
                $updateData['video_url'] = $validated['video_url'] ?? $video->video_url;
                $updateData['mux_playback_id'] = null;
                $updateData['mux_asset_id'] = null;
                $updateData['bunny_video_id'] = null;
            } elseif ($videoSource === 'mux' && (! empty($validated['mux_playback_id']) || ! empty($validated['mux_upload_id']))) {
                $updateData['mux_playback_id'] = ! empty($validated['mux_playback_id']) ? trim($validated['mux_playback_id']) : $video->mux_playback_id;
                $updateData['mux_asset_id'] = $validated['mux_asset_id'] ?? $video->mux_asset_id;
                $updateData['mux_upload_id'] = $validated['mux_upload_id'] ?? $video->mux_upload_id;
                if (! empty($updateData['mux_playback_id'])) {
                    $updateData['video_url'] = "https://stream.mux.com/{$updateData['mux_playback_id']}.m3u8";
                }
                $updateData['bunny_video_id'] = null;
            }
        } elseif ($uploadTab === 'link' || ($uploadTab === null && ! empty($validated['video_url']))) {
            $videoUrl = $validated['video_url'] ?? '';
            if (! empty($videoUrl)) {
                $parsed = $this->videoManager->parseVideoInput($videoUrl);
                $updateData['video_source'] = $parsed['video_source'];
                $updateData['video_url'] = $parsed['video_url'];
                $updateData['mux_playback_id'] = $parsed['mux_playback_id'];
                $updateData['bunny_video_id'] = null;
                if ($parsed['video_source'] !== 'mux') {
                    $updateData['mux_asset_id'] = null;
                }
            }
        } elseif ($uploadTab === null && ! empty($validated['mux_playback_id']) && ($validated['video_source'] ?? '') === 'mux') {
            $updateData['video_source'] = 'mux';
            $updateData['mux_playback_id'] = trim($validated['mux_playback_id']);
            $updateData['mux_asset_id'] = $validated['mux_asset_id'] ?? $video->mux_asset_id;
            $updateData['video_url'] = "https://stream.mux.com/{$updateData['mux_playback_id']}.m3u8";
            $updateData['bunny_video_id'] = null;
        }

        Log::info('[VIDEO_UPLOAD] updateVideo form submitted', [
            'course_id' => $course->id,
            'video_id' => $video->id,
            'title_ar' => $validated['title_ar'] ?? null,
            'upload_tab' => $uploadTab,
            'input_video_source' => $validated['video_source'] ?? null,
            'input_mux_playback_id' => $validated['mux_playback_id'] ?? null,
            'input_mux_asset_id' => $validated['mux_asset_id'] ?? null,
            'has_file' => $request->hasFile('video_file'),
            'input_video_url' => $validated['video_url'] ?? null,
        ]);

        $video->update($updateData);

        Log::info('[VIDEO_UPLOAD] Video record updated in database', [
            'video_id' => $video->id,
            'course_id' => $course->id,
            'final_video_source' => $video->video_source,
            'video_url' => $video->video_url,
            'mux_playback_id' => $video->mux_playback_id,
            'mux_asset_id' => $video->mux_asset_id,
        ]);

        // Update or create linked clinical task
        if (! empty($validated['task_title_ar'])) {
            ClinicalTask::updateOrCreate(
                ['video_id' => $video->id],
                [
                    'course_id' => $course->id,
                    'title_ar' => $validated['task_title_ar'],
                    'instructions_ar' => $validated['task_instructions_ar'] ?? __('قم بإعداد وتسجيل التطبيق العملي المرفق بهذا الدرس.'),
                    'max_score' => 100,
                ]
            );
        }

        return redirect()->route('admin.courses.show', $course)->with('success', __('تم تحديث الدرس والمهمة المرفقة بنجاح.'));
    }

    /**
     * Delete a video/lesson and clean up MUX cloud asset.
     */
    public function destroyVideo(Course $course, Video $video): RedirectResponse
    {
        if (! empty($video->mux_asset_id)) {
            $this->muxService->deleteAsset($video->mux_asset_id);
        }

        $video->delete();

        return back()->with('success', __('تم حذف الدرس بنجاح ومزامنة الحذف مع سحابة MUX.'));
    }

    /**
     * Toggle publish status of a video/lesson.
     */
    public function togglePublishVideo(Course $course, Video $video): RedirectResponse
    {
        $video->update(['is_published' => ! $video->is_published]);

        $statusMsg = $video->is_published
            ? __('تم نشر الدرس بنجاح.')
            : __('تم إخفاء الدرس وتحويله إلى مسودة بنجاح.');

        return back()->with('success', $statusMsg);
    }

    /**
     * Re-sync video asset metadata and ensure signed playback ID exists from MUX.
     */
    public function syncMuxVideo(Course $course, Video $video): RedirectResponse
    {
        if (empty($video->mux_asset_id)) {
            return back()->with('error', __('هذا الدرس غير مرتبط بأصل فيديو على MUX.'));
        }

        $asset = $this->muxService->getAsset($video->mux_asset_id);
        if (! $asset) {
            return back()->with('error', __('تعذر العثور على بيانات الفيديو على سيرفرات MUX.'));
        }

        $playbackIds = is_array($asset['playback_ids'] ?? null) ? $asset['playback_ids'] : [];
        $signedPlayback = null;
        foreach ($playbackIds as $playback) {
            if (is_array($playback) && ($playback['policy'] ?? null) === 'signed') {
                $signedPlayback = $playback;
                break;
            }
        }

        // If no signed playback ID exists yet, create one!
        if (! $signedPlayback) {
            $newSignedId = $this->muxService->createSignedPlaybackId($video->mux_asset_id);
            if ($newSignedId) {
                $video->mux_playback_id = $newSignedId;
                $video->video_url = "https://stream.mux.com/{$newSignedId}.m3u8";
            }
        } elseif (! empty($signedPlayback['id'])) {
            $video->mux_playback_id = $signedPlayback['id'];
            $video->video_url = "https://stream.mux.com/{$signedPlayback['id']}.m3u8";
        }

        if (isset($asset['duration']) && (float) $asset['duration'] > 0) {
            $video->duration_seconds = (int) round((float) $asset['duration']);
        }

        $video->video_source = 'mux';
        $video->save();

        return back()->with('success', __('تمت مزامنة بيانات الفيديو وتأمين البث المشفر مع MUX بنجاح.'));
    }

    /**
     * Apply video settings or bulk operations (specific video, multiple selection, by level, or by course).
     */
    public function applyVideoSettings(ApplyVideoSettingsRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();

        $scope = $validated['scope'];
        $query = $course->videos();

        if ($scope === 'level') {
            if (empty($validated['level_id'])) {
                return back()->with('error', __('يرجى تحديد المستوى المستهدف لتطبيق الإعدادات.'));
            }
            $query->where('level_id', $validated['level_id']);
        } elseif ($scope === 'bulk' || $scope === 'specific') {
            $videoIds = $validated['video_ids'] ?? [];
            if (empty($videoIds)) {
                return back()->with('error', __('لم يتم تحديد أي دروس لتطبيق الإعدادات عليها.'));
            }
            $query->whereIn('id', $videoIds);
        }

        $totalMatching = (clone $query)->count();
        if ($totalMatching === 0) {
            return back()->with('error', __('لا توجد دروس مطابقة لنطاق الإعدادات المحدد.'));
        }

        $action = $validated['bulk_action'] ?? 'update_settings';

        // 1. Bulk Delete (with Mux cloud asset cleanup)
        if ($action === 'delete') {
            $matchingVideos = (clone $query)->get();
            foreach ($matchingVideos as $v) {
                if (! empty($v->mux_asset_id)) {
                    $this->muxService->deleteAsset($v->mux_asset_id);
                }
            }
            $deletedCount = $matchingVideos->count();
            $query->delete();

            return back()->with('success', __('تم حذف :count درس بنجاح ومزامنة الحذف مع سحابة MUX.', ['count' => $deletedCount]));
        }

        // 2. Bulk Move Level
        if ($action === 'move_level') {
            $targetLevelId = $validated['target_level_id'] ?? null;
            if (! $targetLevelId) {
                return back()->with('error', __('يرجى اختيار المستوى المراد نقل الدروس إليه.'));
            }

            $movedCount = (clone $query)->count();
            $query->update(['level_id' => $targetLevelId]);

            return back()->with('success', __('تم نقل :count درس إلى المستوى المحدد بنجاح.', ['count' => $movedCount]));
        }

        // 3. Update Settings (Completion %, Free Preview, optional target level)
        if ($request->filled('min_completion_percentage')) {
            (clone $query)->update([
                'min_completion_percentage' => (int) $validated['min_completion_percentage'],
            ]);
        }

        $policy = $validated['free_preview_policy'] ?? 'keep';
        if ($policy === 'enable_all') {
            (clone $query)->update(['is_free_preview' => true]);
        } elseif ($policy === 'disable_all') {
            (clone $query)->update(['is_free_preview' => false]);
        } elseif ($policy === 'first_only') {
            // Disable preview for all, enable only for the first video in the scope
            (clone $query)->update(['is_free_preview' => false]);
            $firstVideo = (clone $query)->orderBy('sort_order')->orderBy('id')->first();
            if ($firstVideo) {
                $firstVideo->update(['is_free_preview' => true]);
            }
        }

        $publishPolicy = $validated['publish_policy'] ?? 'keep';
        if ($publishPolicy === 'publish_all') {
            (clone $query)->update(['is_published' => true]);
        } elseif ($publishPolicy === 'unpublish_all') {
            (clone $query)->update(['is_published' => false]);
        }

        if (! empty($validated['target_level_id'])) {
            (clone $query)->update(['level_id' => $validated['target_level_id']]);
        }

        $scopeLabels = [
            'specific' => __('الدرس المحدد'),
            'bulk' => __('الدروس المحددة (:count درس)', ['count' => $totalMatching]),
            'level' => __('دروس المستوى المحدد (:count درس)', ['count' => $totalMatching]),
            'course' => __('كافة دروس الكورس (:count درس)', ['count' => $totalMatching]),
        ];

        $scopeName = $scopeLabels[$scope] ?? __('الدروس');

        return back()->with('success', __('تم تحديث إعدادات :scope بنجاح.', ['scope' => $scopeName]));
    }

    /**
     * Generate Mux Direct Upload URL for front-end async upload.
     */
    public function createMuxDirectUpload(Request $request): JsonResponse
    {
        $corsOrigin = config('video.drivers.mux.cors_origin', '*') ?: ($request->headers->get('Origin') ?: '*');
        $upload = $this->muxService->createDirectUploadUrl($corsOrigin);

        if (! $upload) {
            return response()->json([
                'success' => false,
                'message' => __('MUX API غير مهيأ حالياً، يرجى تفعيل مفاتيح MUX في ملف البيئة أو استخدام خيار الرابط/الرفع المباشر.'),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'upload' => $upload,
        ]);
    }

    /**
     * Display interface to assign and manage course enrollments for users.
     */
    public function assignUsers(Course $course): View
    {
        $course->loadCount(['users', 'levels', 'videos']);

        $students = User::query()
            ->select(['id', 'name', 'email', 'phone', 'city', 'country', 'status', 'email_verified_at', 'created_at'])
            ->orderBy('name')
            ->get();

        $enrolledUsers = $course->users()
            ->get()
            ->keyBy('id');

        return view('admin.courses.assign-users', [
            'course' => $course,
            'students' => $students,
            'enrolledUsers' => $enrolledUsers,
        ]);
    }

    /**
     * Bulk assign, synchronize, or update users enrolled in this course.
     */
    public function updateAssignedUsers(AssignCourseUsersRequest $request, Course $course): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $userIds = $validated['user_ids'] ?? [];
        $syncMode = $request->boolean('sync_mode', true);
        $status = $validated['status'] ?? 'active';

        $durationType = $validated['duration_type'] ?? 'default';
        $durationDays = null;
        $expiresAt = null;

        if ($durationType === 'custom_days' && ! empty($validated['duration_days'])) {
            $durationDays = (int) $validated['duration_days'];
        } elseif ($durationType === 'custom_date' && ! empty($validated['access_expires_at'])) {
            $expiresAt = Carbon::parse($validated['access_expires_at']);
        } else {
            $durationDays = (int) ($course->access_duration_days ?: 365);
        }

        $adminId = auth('admin')->id();

        $result = $this->enrollmentService->syncCourseUsers(
            course: $course,
            userIds: $userIds,
            syncMode: $syncMode,
            durationDays: $durationDays,
            expiresAt: $expiresAt,
            status: $status,
            adminId: $adminId,
        );

        $message = __('تم تحديث تعيينات الطلاب للكورس بنجاح (تم تعيين/تحديث :assigned طالب، وإلغاء :detached طالب).', [
            'assigned' => $result['assigned_count'],
            'detached' => $result['detached_count'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'assigned_count' => $result['assigned_count'],
                'detached_count' => $result['detached_count'],
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Quick toggle single user enrollment in the course (AJAX or form).
     */
    public function toggleUserEnrollment(Request $request, Course $course): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
        ]);

        /** @var User $user */
        $user = User::query()->findOrFail($request->input('user_id'));
        $adminId = auth('admin')->id();
        $durationDays = $request->filled('duration_days') ? (int) $request->input('duration_days') : null;

        $isEnrolled = $course->users()->where('users.id', $user->id)->exists();

        if ($isEnrolled) {
            $course->users()->detach($user->id);
            $enrolled = false;
            $message = __('تم إلغاء تعيين الطالب (:name) من الكورس بنجاح.', ['name' => $user->name]);
        } else {
            $this->enrollmentService->enroll($user, $course, $durationDays, $adminId);
            $enrolled = true;
            $message = __('تم تعيين الطالب (:name) في الكورس بنجاح.', ['name' => $user->name]);
        }

        return response()->json([
            'success' => true,
            'enrolled' => $enrolled,
            'message' => $message,
            'total_enrolled' => $course->users()->count(),
        ]);
    }
}
