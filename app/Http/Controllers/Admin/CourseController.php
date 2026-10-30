<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\Level;
use App\Models\Video;
use App\Services\Video\MuxVideoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:courses,slug'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'access_duration_days' => ['required', 'integer', 'min:1'],
            'thumbnail_file' => ['nullable', 'image', 'max:4096'],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'promo_video_source' => ['nullable', 'string', 'in:mux,link,upload,youtube,vimeo'],
            'promo_video_url' => ['nullable', 'string', 'max:1000'],
            'promo_video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm,video/x-matroska', 'max:512000'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title_en'] ?: Str::random(8));

        $validated['is_published'] = $request->boolean('is_published', true);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('courses/thumbnails', 'public');
            $validated['thumbnail'] = Storage::url($path);
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
    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:courses,slug,'.$course->id],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'access_duration_days' => ['required', 'integer', 'min:1'],
            'thumbnail_file' => ['nullable', 'image', 'max:4096'],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'promo_video_source' => ['nullable', 'string', 'in:mux,link,upload,youtube,vimeo'],
            'promo_video_url' => ['nullable', 'string', 'max:1000'],
            'promo_video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm,video/x-matroska', 'max:512000'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_published'] = $request->boolean('is_published', true);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('courses/thumbnails', 'public');
            $validated['thumbnail'] = Storage::url($path);
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
    public function updatePrice(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'currentPrice' => ['required', 'numeric', 'min:0'],
            'originalPrice' => ['nullable', 'numeric', 'min:0'],
        ]);

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
    public function storeLevel(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['course_id'] = $course->id;
        $validated['sort_order'] = $validated['sort_order'] ?? (($course->levels()->max('sort_order') ?? 0) + 1);

        $course->levels()->create($validated);

        return back()->with('success', __('تمت إضافة المستوى التدريبي الجديد بنجاح.'));
    }

    /**
     * Update an existing level.
     */
    public function updateLevel(Request $request, Course $course, Level $level): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
        ]);

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
    public function storeVideo(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'level_id' => ['nullable', 'exists:levels,id'],
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'min_completion_percentage' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_free_preview' => ['nullable', 'boolean'],
            // Video sources
            'video_source_type' => ['nullable', 'string', 'in:mux_upload,link,mux_playback_id,bunny'],
            'video_url' => ['nullable', 'string', 'max:1000'],
            'mux_playback_id' => ['nullable', 'string', 'max:255'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm,video/x-matroska', 'max:1048576'],
            // Attached Clinical Task
            'task_title_ar' => ['nullable', 'string', 'max:255'],
            'task_instructions_ar' => ['nullable', 'string'],
        ]);

        $durationMinutes = $request->filled('duration_minutes') ? (int) $request->input('duration_minutes') : 20;
        $durationSeconds = $request->filled('duration_seconds') ? (int) $request->input('duration_seconds') : ($durationMinutes * 60);

        $videoData = [
            'course_id' => $course->id,
            'level_id' => $validated['level_id'] ?? null,
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'] ?? null,
            'slug' => Str::slug($validated['title_en'] ?: 'video-'.Str::random(6)),
            'duration_seconds' => $durationSeconds,
            'min_completion_percentage' => (int) ($validated['min_completion_percentage'] ?? 80),
            'sort_order' => $validated['sort_order'] ?? (($course->videos()->max('sort_order') ?? 0) + 1),
            'is_free_preview' => $request->boolean('is_free_preview', false),
            'video_source' => 'link',
            'video_url' => null,
            'mux_playback_id' => null,
            'mux_asset_id' => null,
        ];

        // 1. Video file uploaded to MUX
        if ($request->hasFile('video_file')) {
            $uploadResult = $this->muxService->handleVideoUpload($request->file('video_file'));
            $videoData['video_source'] = $uploadResult['video_source'];
            $videoData['video_url'] = $uploadResult['video_url'];
            $videoData['mux_playback_id'] = $uploadResult['mux_playback_id'];
            $videoData['mux_asset_id'] = $uploadResult['mux_asset_id'];
        } elseif (! empty($validated['mux_playback_id'])) {
            $videoData['video_source'] = 'mux';
            $videoData['mux_playback_id'] = trim($validated['mux_playback_id']);
            $videoData['video_url'] = "https://stream.mux.com/{$videoData['mux_playback_id']}.m3u8";
        } elseif (! empty($validated['video_url'])) {
            $parsed = $this->muxService->parseVideoInput($validated['video_url']);
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

        return back()->with('success', __('تمت إضافة المحاضرة/الدرس بنجاح إلى المنهج.'));
    }

    /**
     * Update an existing video/lesson.
     */
    public function updateVideo(Request $request, Course $course, Video $video): RedirectResponse
    {
        $validated = $request->validate([
            'level_id' => ['nullable', 'exists:levels,id'],
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'video_url' => ['nullable', 'string', 'max:1000'],
            'mux_playback_id' => ['nullable', 'string', 'max:255'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm,video/x-matroska', 'max:1048576'],
            'is_free_preview' => ['nullable', 'boolean'],
            'task_title_ar' => ['nullable', 'string', 'max:255'],
        ]);

        $updateData = [
            'level_id' => $validated['level_id'] ?? $video->level_id,
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'] ?? $video->title_en,
            'is_free_preview' => $request->boolean('is_free_preview', false),
        ];

        if ($request->filled('duration_minutes')) {
            $updateData['duration_seconds'] = ((int) $request->input('duration_minutes')) * 60;
        }

        if ($request->hasFile('video_file')) {
            $uploadResult = $this->muxService->handleVideoUpload($request->file('video_file'));
            $updateData['video_source'] = $uploadResult['video_source'];
            $updateData['video_url'] = $uploadResult['video_url'];
            $updateData['mux_playback_id'] = $uploadResult['mux_playback_id'];
            $updateData['mux_asset_id'] = $uploadResult['mux_asset_id'];
        } elseif (! empty($validated['mux_playback_id'])) {
            $updateData['video_source'] = 'mux';
            $updateData['mux_playback_id'] = trim($validated['mux_playback_id']);
            $updateData['video_url'] = "https://stream.mux.com/{$updateData['mux_playback_id']}.m3u8";
        } elseif (! empty($validated['video_url'])) {
            $parsed = $this->muxService->parseVideoInput($validated['video_url']);
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
                    'instructions_ar' => __('قم بإعداد وتسجيل التطبيق السريري المرفق بهذا الدرس.'),
                    'max_score' => 100,
                ]
            );
        }

        return back()->with('success', __('تم تحديث الدرس والمهمة المرفقة بنجاح.'));
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
