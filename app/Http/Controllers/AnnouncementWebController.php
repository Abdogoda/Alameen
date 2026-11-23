<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\User;
use App\Services\Announcement\AnnouncementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementWebController extends Controller
{
    public function __construct(
        protected AnnouncementService $announcementService
    ) {}

    /**
     * Browse educational announcements & media catalog in the student portal.
     */
    public function index(Request $request): View
    {
        /** @var User|null $user */
        $user = auth()->user();

        $type = $request->query('type');
        $search = $request->query('q');

        $announcements = $this->announcementService->getCatalog(
            platform: 'web',
            user: $user,
            contentType: ! empty($type) ? (string) $type : null,
            search: ! empty($search) ? (string) $search : null,
            perPage: 12
        );

        return view('web.pages.announcements.index', [
            'announcements' => $announcements,
            'currentType' => $type,
            'searchQuery' => $search,
        ]);
    }

    /**
     * View a single announcement / educational video in detail.
     */
    public function show(Request $request, Announcement $announcement): View
    {
        /** @var User|null $user */
        $user = auth()->user();

        // Check if active and accessible on web
        if (! $announcement->is_active || ! in_array('web', $announcement->platforms ?? [])) {
            abort(404);
        }

        // Record interaction as viewed
        $this->announcementService->recordInteraction(
            announcement: $announcement,
            action: 'viewed',
            user: $user,
            platform: 'web',
            metadata: [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        // Fetch other active recent announcements for the sidebar/suggestions
        $recentAnnouncements = Announcement::query()
            ->active()
            ->forPlatform('web')
            ->forAudience($user ? 'students' : 'guests')
            ->where('id', '!=', $announcement->id)
            ->ordered()
            ->take(4)
            ->get();

        return view('web.pages.announcements.show', [
            'announcement' => $announcement,
            'recentAnnouncements' => $recentAnnouncements,
        ]);
    }

    /**
     * Record an interaction (viewed, dismissed, acknowledged) from web views.
     */
    public function interact(Request $request, Announcement $announcement): JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:viewed,dismissed,acknowledged'],
            'device_fingerprint' => ['nullable', 'string', 'max:255'],
            'platform' => ['nullable', 'string', 'in:web,admin,mobile'],
        ]);

        /** @var User|null $user */
        $user = auth()->user();
        /** @var Admin|null $admin */
        $admin = auth('admin')->user();

        $deviceFingerprint = $validated['device_fingerprint']
            ?? $request->cookie('alameen_device_id')
            ?? $request->session()->getId();

        $platform = $validated['platform'] ?? ($admin ? 'admin' : 'web');

        $interaction = $this->announcementService->recordInteraction(
            announcement: $announcement,
            action: $validated['action'],
            user: $user,
            admin: $admin,
            deviceFingerprint: $deviceFingerprint,
            platform: $platform,
            metadata: [
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'referer' => $request->headers->get('referer'),
            ]
        );

        return response()->json([
            'success' => true,
            'action' => $interaction->action,
            'message' => __('تم تسجيل الإجراء بنجاح.'),
        ]);
    }
}
