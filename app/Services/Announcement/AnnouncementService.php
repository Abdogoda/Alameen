<?php

declare(strict_types=1);

namespace App\Services\Announcement;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\AnnouncementInteraction;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementService
{
    /**
     * Get eligible active announcements for a given platform, audience, and actor.
     *
     * @return Collection<int, Announcement>
     */
    public function getEligibleAnnouncements(
        string $platform = 'web',
        ?User $user = null,
        ?Admin $admin = null,
        ?string $deviceFingerprint = null,
        ?string $displayMode = null,
        ?string $contentType = null
    ): Collection {
        $query = Announcement::query()
            ->active()
            ->forPlatform($platform);

        // Filter by audience
        if ($admin !== null) {
            $query->forAudience('admins');
        } elseif ($user !== null) {
            $query->forAudience('students');
        } else {
            $query->forAudience('guests');
        }

        if (! empty($displayMode)) {
            $query->where('display_mode', $displayMode);
        }

        if (! empty($contentType)) {
            $query->where('content_type', $contentType);
        }

        $announcements = $query->ordered()->get();

        if ($announcements->isEmpty()) {
            return $announcements;
        }

        // Fetch past interactions for this viewer
        $announcementIds = $announcements->pluck('id')->all();

        $interactionsQuery = AnnouncementInteraction::query()
            ->whereIn('announcement_id', $announcementIds);

        if ($admin !== null) {
            $interactionsQuery->where('admin_id', $admin->id);
        } elseif ($user !== null) {
            $interactionsQuery->where('user_id', $user->id);
        } elseif (! empty($deviceFingerprint)) {
            $interactionsQuery->where('device_fingerprint', $deviceFingerprint);
        } else {
            // Unauthenticated guest without fingerprint - return all eligible announcements
            return $announcements;
        }

        $interactions = $interactionsQuery->get()->groupBy('announcement_id');

        // Filter out announcements already dismissed/acknowledged based on display_mode and frequency
        return $announcements->filter(function (Announcement $announcement) use ($interactions): bool {
            $announcementInteractions = $interactions->get($announcement->id, collect());

            $isAcknowledged = $announcementInteractions->contains(fn ($i) => ! empty($i->acknowledged_at) || $i->action === 'acknowledged');
            $isDismissed = $announcementInteractions->contains(fn ($i) => ! empty($i->dismissed_at) || $i->action === 'dismissed');
            $isViewed = $announcementInteractions->contains(fn ($i) => ! empty($i->viewed_at) || $i->action === 'viewed');

            // Force mode MUST remain visible until explicitly acknowledged
            if ($announcement->display_mode === 'force') {
                return ! $isAcknowledged;
            }

            // Always frequency is always shown
            if ($announcement->frequency === 'always') {
                return true;
            }

            // Once frequency: hide once viewed, dismissed, or acknowledged
            if ($announcement->frequency === 'once') {
                return ! ($isViewed || $isDismissed || $isAcknowledged);
            }

            // Until dismissed frequency: hide once dismissed or acknowledged
            if ($announcement->frequency === 'until_dismissed') {
                return ! ($isDismissed || $isAcknowledged);
            }

            return true;
        })->values();
    }

    /**
     * Record an interaction (viewed, dismissed, acknowledged) for an announcement.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function recordInteraction(
        Announcement $announcement,
        string $action,
        ?User $user = null,
        ?Admin $admin = null,
        ?string $deviceFingerprint = null,
        string $platform = 'web',
        array $metadata = []
    ): AnnouncementInteraction {
        $now = now();

        $attributes = [
            'announcement_id' => $announcement->id,
            'platform' => $platform,
        ];

        if ($admin !== null) {
            $attributes['admin_id'] = $admin->id;
        } elseif ($user !== null) {
            $attributes['user_id'] = $user->id;
        } elseif (! empty($deviceFingerprint)) {
            $attributes['device_fingerprint'] = $deviceFingerprint;
        }

        /** @var AnnouncementInteraction $interaction */
        $interaction = AnnouncementInteraction::query()->firstOrNew($attributes);

        $interaction->action = $action;
        $interaction->metadata = array_merge($interaction->metadata ?? [], $metadata);

        if ($action === 'viewed') {
            $interaction->viewed_at = $now;
        } elseif ($action === 'dismissed') {
            $interaction->dismissed_at = $now;
        } elseif ($action === 'acknowledged') {
            $interaction->acknowledged_at = $now;
        }

        $interaction->save();

        return $interaction;
    }

    /**
     * Create a new announcement from admin input.
     *
     * @param  array<string, mixed>  $data
     */
    public function createAnnouncement(array $data, ?Admin $creator = null): Announcement
    {
        if ($creator !== null) {
            $data['created_by_admin_id'] = $creator->id;
        }

        return Announcement::create($data);
    }

    /**
     * Update an existing announcement.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateAnnouncement(Announcement $announcement, array $data): Announcement
    {
        $announcement->update($data);

        return $announcement;
    }

    /**
     * Delete an announcement and any uploaded files.
     */
    public function deleteAnnouncement(Announcement $announcement): bool
    {
        // Remove locally stored video/thumbnail if present
        if (! empty($announcement->video_url) && Str::startsWith($announcement->video_url, '/storage/announcements/')) {
            $path = str_replace('/storage/', '', $announcement->video_url);
            Storage::disk('public')->delete($path);
        }

        if (! empty($announcement->thumbnail_url) && Str::startsWith($announcement->thumbnail_url, '/storage/announcements/')) {
            $path = str_replace('/storage/', '', $announcement->thumbnail_url);
            Storage::disk('public')->delete($path);
        }

        return (bool) $announcement->delete();
    }

    /**
     * Get paginated catalog of announcements for user browsing (archive/learning feed).
     */
    public function getCatalog(
        string $platform = 'web',
        ?User $user = null,
        ?string $contentType = null,
        ?string $search = null,
        int $perPage = 12
    ) {
        $query = Announcement::query()
            ->active()
            ->forPlatform($platform);

        if ($user !== null) {
            $query->forAudience('students');
        } else {
            $query->forAudience('guests');
        }

        if (! empty($contentType) && $contentType !== 'all') {
            $query->where('content_type', $contentType);
        }

        if (! empty($search)) {
            $term = trim($search);
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('summary', 'like', "%{$term}%")
                    ->orWhere('body', 'like', "%{$term}%");
            });
        }

        return $query->ordered()->paginate($perPage)->withQueryString();
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(Announcement $announcement): bool
    {
        $announcement->is_active = ! $announcement->is_active;
        $announcement->save();

        return $announcement->is_active;
    }

    /**
     * Aggregate interaction analytics for an announcement.
     *
     * @return array<string, mixed>
     */
    public function getStats(Announcement $announcement): array
    {
        $totalInteractions = $announcement->interactions()->count();
        $viewsCount = $announcement->interactions()->whereNotNull('viewed_at')->count();
        $dismissalsCount = $announcement->interactions()->whereNotNull('dismissed_at')->count();
        $acknowledgmentsCount = $announcement->interactions()->whereNotNull('acknowledged_at')->count();

        $byPlatform = $announcement->interactions()
            ->selectRaw('platform, count(*) as count')
            ->groupBy('platform')
            ->pluck('count', 'platform')
            ->toArray();

        return [
            'total_interactions' => $totalInteractions,
            'views' => $viewsCount,
            'dismissals' => $dismissalsCount,
            'acknowledgments' => $acknowledgmentsCount,
            'by_platform' => [
                'web' => $byPlatform['web'] ?? 0,
                'mobile' => $byPlatform['mobile'] ?? 0,
                'admin' => $byPlatform['admin'] ?? 0,
            ],
        ];
    }
}
