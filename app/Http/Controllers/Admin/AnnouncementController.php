<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAnnouncementRequest;
use App\Http\Requests\Admin\UpdateAnnouncementRequest;
use App\Models\Admin;
use App\Models\AdminActivityLog;
use App\Models\Announcement;
use App\Services\Announcement\AnnouncementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function __construct(
        protected AnnouncementService $announcementService
    ) {}

    /**
     * Display a listing of announcements and educational items.
     */
    public function index(Request $request): View
    {
        $query = Announcement::query()->with('creator')->latest('id');

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search): void {
                $q->where('title_ar', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('summary_ar', 'like', "%{$search}%")
                    ->orWhere('summary_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('platform') && $request->input('platform') !== 'all') {
            $platform = (string) $request->input('platform');
            $query->forPlatform($platform);
        }

        if ($request->filled('display_mode') && $request->input('display_mode') !== 'all') {
            $query->where('display_mode', $request->input('display_mode'));
        }

        if ($request->filled('content_type') && $request->input('content_type') !== 'all') {
            $query->where('content_type', $request->input('content_type'));
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $announcements = $query->paginate(15)->withQueryString();

        // Metrics for summary cards
        $metrics = [
            'total' => Announcement::count(),
            'active' => Announcement::where('is_active', true)->count(),
            'forced' => Announcement::where('display_mode', 'force')->where('is_active', true)->count(),
            'popups' => Announcement::where('display_mode', 'popup')->where('is_active', true)->count(),
            'videos' => Announcement::whereIn('content_type', ['video', 'mixed'])->count(),
        ];

        return view('admin.announcements.index', [
            'announcements' => $announcements,
            'metrics' => $metrics,
            'filters' => $request->only(['search', 'platform', 'display_mode', 'content_type', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create(): View
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        /** @var Admin|null $admin */
        $admin = auth('admin')->user();
        $validated = $request->validated();

        // Handle uploaded video file
        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('announcements/videos', 'public');
            $validated['video_url'] = '/storage/'.$path;
            $validated['video_type'] = 'upload';
        }

        // Handle uploaded thumbnail
        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('announcements/thumbnails', 'public');
            $validated['thumbnail_url'] = '/storage/'.$path;
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['priority'] = (int) ($validated['priority'] ?? 0);

        unset($validated['video_file'], $validated['thumbnail_file']);

        $announcement = $this->announcementService->createAnnouncement($validated, $admin);

        // Activity log
        if ($admin) {
            AdminActivityLog::create([
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
                'role_name' => $admin->role?->name ?? 'Admin',
                'module' => 'announcements',
                'action' => 'create',
                'description' => "أنشأ إعلان/محتوى تعليمي جديد: {$announcement->title_ar} (النمط: {$announcement->display_mode})",
                'subject_type' => Announcement::class,
                'subject_id' => $announcement->id,
                'subject_name' => $announcement->title_ar,
                'method' => 'POST',
                'url' => $request->fullUrl(),
                'route_name' => 'admin.announcements.store',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'properties' => [
                    'display_mode' => $announcement->display_mode,
                    'platforms' => $announcement->target_platforms,
                    'content_type' => $announcement->content_type,
                ],
            ]);
        }

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', __('تم إنشاء الإعلان/الفيديو التعليمي وتفعيله بنجاح.'));
    }

    /**
     * Display the specified announcement with performance statistics and preview.
     */
    public function show(Announcement $announcement): View
    {
        $stats = $this->announcementService->getStats($announcement);
        $recentInteractions = $announcement->interactions()
            ->with(['user', 'admin'])
            ->latest('id')
            ->take(20)
            ->get();

        return view('admin.announcements.show', [
            'announcement' => $announcement,
            'stats' => $stats,
            'recentInteractions' => $recentInteractions,
        ]);
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', [
            'announcement' => $announcement,
        ]);
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(UpdateAnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        /** @var Admin|null $admin */
        $admin = auth('admin')->user();
        $validated = $request->validated();

        // Handle uploaded video file
        if ($request->hasFile('video_file')) {
            // Delete old file if present
            if (! empty($announcement->video_url) && str_starts_with($announcement->video_url, '/storage/announcements/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $announcement->video_url));
            }
            $path = $request->file('video_file')->store('announcements/videos', 'public');
            $validated['video_url'] = '/storage/'.$path;
            $validated['video_type'] = 'upload';
        }

        // Handle uploaded thumbnail
        if ($request->hasFile('thumbnail_file')) {
            if (! empty($announcement->thumbnail_url) && str_starts_with($announcement->thumbnail_url, '/storage/announcements/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $announcement->thumbnail_url));
            }
            $path = $request->file('thumbnail_file')->store('announcements/thumbnails', 'public');
            $validated['thumbnail_url'] = '/storage/'.$path;
        }

        $validated['is_active'] = $request->boolean('is_active', false);
        $validated['priority'] = (int) ($validated['priority'] ?? 0);

        unset($validated['video_file'], $validated['thumbnail_file']);

        $this->announcementService->updateAnnouncement($announcement, $validated);

        // Activity log
        if ($admin) {
            AdminActivityLog::create([
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
                'role_name' => $admin->role?->name ?? 'Admin',
                'module' => 'announcements',
                'action' => 'update',
                'description' => "حدّث بيانات الإعلان/الفيديو التعليمي: {$announcement->title_ar}",
                'subject_type' => Announcement::class,
                'subject_id' => $announcement->id,
                'subject_name' => $announcement->title_ar,
                'method' => 'PUT',
                'url' => $request->fullUrl(),
                'route_name' => 'admin.announcements.update',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'properties' => $validated,
            ]);
        }

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', __('تم تحديث بيانات الإعلان بنجاح.'));
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Request $request, Announcement $announcement): RedirectResponse|JsonResponse
    {
        /** @var Admin|null $admin */
        $admin = auth('admin')->user();
        $title = $announcement->title_ar;
        $id = $announcement->id;

        $this->announcementService->deleteAnnouncement($announcement);

        // Activity log
        if ($admin) {
            AdminActivityLog::create([
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
                'role_name' => $admin->role?->name ?? 'Admin',
                'module' => 'announcements',
                'action' => 'delete',
                'description' => "حذف الإعلان: {$title}",
                'subject_type' => Announcement::class,
                'subject_id' => $id,
                'subject_name' => $title,
                'method' => 'DELETE',
                'url' => $request->fullUrl(),
                'route_name' => 'admin.announcements.destroy',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('تم حذف الإعلان بنجاح.'),
            ]);
        }

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', __('تم حذف الإعلان بنجاح.'));
    }

    /**
     * Quick AJAX status toggle.
     */
    public function toggle(Announcement $announcement): JsonResponse
    {
        $newStatus = $this->announcementService->toggleStatus($announcement);

        return response()->json([
            'success' => true,
            'is_active' => $newStatus,
            'message' => $newStatus
                ? __('تم تفعيل ظهور الإعلان بنجاح.')
                : __('تم إيقاف تفعيل الإعلان.'),
        ]);
    }
}
