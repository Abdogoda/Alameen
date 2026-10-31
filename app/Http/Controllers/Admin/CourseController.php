<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApplyVideoSettingsRequest;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\StoreLevelRequest;
use App\Http\Requests\Admin\StoreVideoRequest;
use App\Http\Requests\Admin\UpdateCoursePriceRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Http\Requests\Admin\UpdateLevelRequest;
use App\Http\Requests\Admin\UpdateVideoRequest;
use App\Http\Requests\Admin\UploadLessonChunkRequest;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\Level;
use App\Models\Video;
use App\Services\Video\ChunkedUploadService;
use App\Services\Video\MuxVideoService;
use App\Services\Video\VideoServiceManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
        protected VideoServiceManager $videoManager,
        protected ChunkedUploadService $chunkedUploadService,
        protected MuxVideoService $muxService
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

        return view('admin.courses.edit', compact('course'));
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
        } elseif (! empty($validated['promo_video_url'])) {
            $parsed = $this->muxService->parseVideoInput($validated['promo_video_url']);
            $validated['promo_video_source'] = $parsed['video_source'];
            $validated['promo_video_url'] = $parsed['video_url'];
            $validated['mux_playback_id'] = $parsed['mux_playback_id'];
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
        } elseif (! empty($validated['promo_video_url'])) {
            $parsed = $this->muxService->parseVideoInput($validated['promo_video_url']);
            $validated['promo_video_source'] = $parsed['video_source'];
            $validated['promo_video_url'] = $parsed['video_url'];
            $validated['mux_playback_id'] = $parsed['mux_playback_id'];
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
     * Delete a course.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', __('تم حذف الكورس التدريبي بنجاح.'));
    }

    /**
     * Store a new dynamic curriculum level.
     */
    public function storeLevel(StoreLevelRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();

        $validated['course_id'] = $course->id;
        $validated['sort_order'] = $validated['sort_order'] ?? (($course->levels()->max('sort_order') ?? 0) + 1);

        $course->levels()->create($validated);

        return back()->with('success', __('تمت إضافة المستوى التدريبي الجديد بنجاح.'));
    }

    /**
     * Update an existing level.
     */
    public function updateLevel(UpdateLevelRequest $request, Course $course, Level $level): RedirectResponse
    {
        $validated = $request->validated();

        $level->update($validated);

        return back()->with('success', __('تم تحديث المستوى التدريبي بنجاح.'));
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
        $muxDriver = $this->videoManager->driver('mux');

        if (! $muxDriver->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => __('خدمة MUX غير مهيأة على السيرفر، يتم الرفع المباشر عبر السيرفر المحلي.'),
            ], 400);
        }

        $upload = $muxDriver->createDirectUploadUrl();
        if (! $upload || empty($upload['url'])) {
            return response()->json([
                'success' => false,
                'message' => __('فشل إنشاء رابط الرفع لمنصة MUX.'),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'upload_url' => $upload['url'],
            'upload_id' => $upload['id'],
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

        // 2. Check if all chunks have arrived
        if (! $this->chunkedUploadService->isComplete($uploadId, $totalChunks)) {
            return response()->json([
                'status' => 'chunk_received',
                'chunk_index' => $chunkIndex,
                'total_chunks' => $totalChunks,
                'progress_percentage' => round((($chunkIndex + 1) / $totalChunks) * 100, 1),
            ]);
        }

        // 3. All chunks received -> Merge & Process video file
        try {
            $mergedFile = $this->chunkedUploadService->mergeChunks($uploadId, $totalChunks, $originalFilename);

            // Resilient upload: tries configured default driver (e.g. MUX), falls back to local server storage
            $uploadResult = $this->videoManager->handleVideoUpload($mergedFile);

            // Clean up temporary merged file
            @unlink($mergedFile->getRealPath());

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
            'slug' => Str::slug($validated['title_en'] ?: 'lesson-'.Str::random(6)),
            'duration_seconds' => $durationSeconds,
            'min_completion_percentage' => (int) ($validated['min_completion_percentage'] ?? 80),
            'sort_order' => $validated['sort_order'] ?? (($course->videos()->max('sort_order') ?? 0) + 1),
            'is_free_preview' => $request->boolean('is_free_preview', false),
            'video_source' => $validated['video_source'] ?? 'link',
            'video_url' => $validated['video_url'] ?? null,
            'mux_playback_id' => $validated['mux_playback_id'] ?? null,
            'mux_asset_id' => $validated['mux_asset_id'] ?? null,
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
        } elseif (! empty($validated['video_url']) && empty($validated['video_source'])) {
            $parsed = $this->videoManager->parseVideoInput($validated['video_url']);
            $videoData['video_source'] = $parsed['video_source'];
            $videoData['video_url'] = $parsed['video_url'];
            $videoData['mux_playback_id'] = $parsed['mux_playback_id'];
        }

        $video = Video::create($videoData);

        // Optional attached Clinical Task
        if (! empty($validated['task_title_ar'])) {
            ClinicalTask::create([
                'course_id' => $course->id,
                'video_id' => $video->id,
                'title_ar' => $validated['task_title_ar'],
                'title_en' => $validated['title_en'] ? 'Task: '.$validated['title_en'] : null,
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
        ];

        if ($request->filled('min_completion_percentage')) {
            $updateData['min_completion_percentage'] = (int) $request->input('min_completion_percentage');
        }

        if ($request->filled('duration_minutes')) {
            $updateData['duration_seconds'] = ((int) $request->input('duration_minutes')) * 60;
        }

        if (! empty($validated['video_source'])) {
            $updateData['video_source'] = $validated['video_source'];
        }

        if ($request->hasFile('video_file')) {
            $uploadResult = $this->videoManager->handleVideoUpload($request->file('video_file'));
            $updateData['video_source'] = $uploadResult['video_source'];
            $updateData['video_url'] = $uploadResult['video_url'];
            $updateData['mux_playback_id'] = $uploadResult['mux_playback_id'];
            $updateData['mux_asset_id'] = $uploadResult['mux_asset_id'];
        } elseif (! empty($validated['mux_playback_id'])) {
            $updateData['video_source'] = 'mux';
            $updateData['mux_playback_id'] = trim($validated['mux_playback_id']);
            $updateData['mux_asset_id'] = $validated['mux_asset_id'] ?? $video->mux_asset_id;
            $updateData['video_url'] = "https://stream.mux.com/{$updateData['mux_playback_id']}.m3u8";
        } elseif (! empty($validated['video_url'])) {
            $parsed = $this->videoManager->parseVideoInput($validated['video_url']);
            $updateData['video_source'] = $parsed['video_source'];
            $updateData['video_url'] = $parsed['video_url'];
            $updateData['mux_playback_id'] = $parsed['mux_playback_id'];
        }

        $video->update($updateData);

        // Update or create linked clinical task
        if (! empty($validated['task_title_ar'])) {
            ClinicalTask::updateOrCreate(
                ['video_id' => $video->id],
                [
                    'course_id' => $course->id,
                    'title_ar' => $validated['task_title_ar'],
                    'instructions_ar' => $validated['task_instructions_ar'] ?? __('قم بإعداد وتسجيل التطبيق السريري المرفق بهذا الدرس.'),
                    'max_score' => 100,
                ]
            );
        }

        return redirect()->route('admin.courses.show', $course)->with('success', __('تم تحديث الدرس والمهمة المرفقة بنجاح.'));
    }

    /**
     * Delete a video/lesson.
     */
    public function destroyVideo(Course $course, Video $video): RedirectResponse
    {
        $video->delete();

        return back()->with('success', __('تم حذف الدرس بنجاح من المنهج.'));
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

        // 1. Bulk Delete
        if ($action === 'delete') {
            $deletedCount = (clone $query)->count();
            $query->delete();

            return back()->with('success', __('تم حذف :count درس بنجاح من المنهج.', ['count' => $deletedCount]));
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
        $upload = $this->muxService->createDirectUploadUrl();

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
}
