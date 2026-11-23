<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\User;
use App\Services\Announcement\AnnouncementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementWebController extends Controller
{
    public function __construct(
        protected AnnouncementService $announcementService
    ) {}

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
