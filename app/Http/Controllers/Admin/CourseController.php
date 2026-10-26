<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display courses catalogue with stats.
     */
    public function index(): View
    {
        $courses = Course::withCount(['videos', 'users'])->orderBy('sort_order')->get();

        return view('admin.courses.index', compact('courses'));
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
            'access_duration_days' => ['required', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title_en'] ?: Str::random(8));

        $validated['is_published'] = $request->boolean('is_published', true);

        Course::create($validated);

        return back()->with('success', __('تم إنشاء الكورس التدريبي بنجاح.'));
    }

    /**
     * Store a video inside a course with sequential lock parameter and Bunny ID.
     */
    public function storeVideo(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'bunny_video_id' => ['nullable', 'string', 'max:255'],
            'duration_seconds' => ['required', 'integer', 'min:0'],
            'min_completion_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_free_preview' => ['nullable', 'boolean'],
        ]);

        $validated['course_id'] = $course->id;
        $validated['slug'] = Str::slug($validated['title_en'] ?: 'video-'.Str::random(6));
        $validated['is_free_preview'] = $request->boolean('is_free_preview', false);
        $validated['sort_order'] = $validated['sort_order'] ?? ($course->videos()->max('sort_order') + 1);

        Video::create($validated);

        return back()->with('success', __('تمت إضافة المحاضرة/الفيديو بنجاح مع إعدادات القفل المتسلسل.'));
    }
}
