<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AnnouncementResource;
use App\Models\Announcement;
use App\Models\User;
use App\Services\Announcement\AnnouncementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementApiController extends Controller
{
    public function __construct(
        protected AnnouncementService $announcementService
    ) {}

    /**
     * GET /api/v1/announcements
     * Retrieve eligible announcements and educational content for the requesting client.
     */
    public function index(Request $request): JsonResponse
    {
        $platform = $request->query('platform', 'mobile');
        if (! in_array($platform, ['mobile', 'web', 'admin'], true)) {
            $platform = 'mobile';
        }

        $displayMode = $request->query('display_mode');
        $contentType = $request->query('content_type');

        /** @var User|null $user */
        $user = auth('sanctum')->user();
        $deviceFingerprint = $request->header('X-Device-Fingerprint') ?? $request->query('device_fingerprint');

        $announcements = $this->announcementService->getEligibleAnnouncements(
            platform: $platform,
            user: $user,
            admin: null,
            deviceFingerprint: is_string($deviceFingerprint) ? $deviceFingerprint : null,
            displayMode: is_string($displayMode) ? $displayMode : null,
            contentType: is_string($contentType) ? $contentType : null,
        );

        return response()->json([
            'success' => true,
            'data' => AnnouncementResource::collection($announcements),
            'meta' => [
                'platform' => $platform,
                'count' => $announcements->count(),
                'has_forced' => $announcements->contains(fn ($a) => $a->display_mode === 'force'),
            ],
        ]);
    }

    /**
     * GET /api/v1/announcements/{id}
     * Get details of a single active announcement.
     */
    public function show(int $id): JsonResponse
    {
        /** @var Announcement|null $announcement */
        $announcement = Announcement::query()->active()->find($id);

        if (! $announcement) {
            return response()->json([
                'success' => false,
                'message' => __('messages.not_found') !== 'messages.not_found'
                    ? __('messages.not_found')
                    : 'Announcement not found or expired.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new AnnouncementResource($announcement),
        ]);
    }

    /**
     * POST /api/v1/announcements/{id}/interact
     * Record interaction (viewed, dismissed, acknowledged) from client app.
     */
    public function interact(Request $request, int $id): JsonResponse
    {
        /** @var Announcement|null $announcement */
        $announcement = Announcement::find($id);

        if (! $announcement) {
            return response()->json([
                'success' => false,
                'message' => 'Announcement not found.',
            ], 404);
        }

        $validated = $request->validate([
            'action' => ['required', 'string', 'in:viewed,dismissed,acknowledged'],
            'platform' => ['nullable', 'string', 'in:mobile,web,admin'],
            'device_fingerprint' => ['nullable', 'string', 'max:255'],
            'metadata' => ['nullable', 'array'],
        ]);

        /** @var User|null $user */
        $user = auth('sanctum')->user();
        $deviceFingerprint = $request->header('X-Device-Fingerprint')
            ?? ($validated['device_fingerprint'] ?? null);

        $interaction = $this->announcementService->recordInteraction(
            announcement: $announcement,
            action: $validated['action'],
            user: $user,
            admin: null,
            deviceFingerprint: is_string($deviceFingerprint) ? $deviceFingerprint : null,
            platform: $validated['platform'] ?? 'mobile',
            metadata: $validated['metadata'] ?? []
        );

        return response()->json([
            'success' => true,
            'message' => __('messages.action_successful') !== 'messages.action_successful'
                ? __('messages.action_successful')
                : 'Interaction recorded successfully.',
            'data' => [
                'id' => $interaction->id,
                'announcement_id' => $announcement->id,
                'action' => $interaction->action,
                'recorded_at' => now()->toIso8601String(),
            ],
        ]);
    }
}
