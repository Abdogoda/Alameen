<?php

declare(strict_types=1);

namespace App\Services\Video;

use App\Models\User;
use App\Models\Video;

class BunnyStreamService
{
    protected string $pullZone;

    protected string $libraryId;

    protected string $securityTokenKey;

    public function __construct()
    {
        $this->pullZone = (string) config('services.bunny.pull_zone', env('BUNNY_STREAM_PULL_ZONE', 'iframe.mediadelivery.net'));
        $this->libraryId = (string) config('services.bunny.library_id', env('BUNNY_STREAM_LIBRARY_ID', ''));
        $this->securityTokenKey = (string) config('services.bunny.security_token_key', env('BUNNY_SECURITY_TOKEN_KEY', ''));
    }

    /**
     * Generate secure Bunny.net iframe embed URL with signed token.
     */
    public function getIframeEmbedUrl(Video $video, ?int $expiresInSeconds = 7200): string
    {
        $libraryId = $video->bunny_library_id ?: $this->libraryId;
        $videoId = $video->bunny_video_id;

        if (empty($videoId)) {
            return '';
        }

        $expires = time() + ($expiresInSeconds ?? 7200);

        if (! empty($this->securityTokenKey)) {
            $token = hash('sha256', $this->securityTokenKey.$videoId.(string) $expires);

            return "https://{$this->pullZone}/embed/{$libraryId}/{$videoId}?token={$token}&expires={$expires}&autoplay=false&preload=true";
        }

        return "https://{$this->pullZone}/embed/{$libraryId}/{$videoId}?autoplay=false&preload=true";
    }

    /**
     * Generate secure HLS playlist URL (.m3u8).
     */
    public function getHlsPlaylistUrl(Video $video, ?int $expiresInSeconds = 7200): string
    {
        $videoId = $video->bunny_video_id;

        if (empty($videoId)) {
            return '';
        }

        $pullDomain = env('BUNNY_HLS_PULL_ZONE', 'vz-video.b-cdn.net');
        $expires = time() + ($expiresInSeconds ?? 7200);

        if (! empty($this->securityTokenKey)) {
            $token = hash('sha256', $this->securityTokenKey.$videoId.(string) $expires);

            return "https://{$pullDomain}/{$videoId}/playlist.m3u8?token={$token}&expires={$expires}";
        }

        return "https://{$pullDomain}/{$videoId}/playlist.m3u8";
    }

    /**
     * Prepare student watermark payload for the dynamic canvas overlay.
     *
     * @return array{
     *     name: string,
     *     phone: string,
     *     user_id: int,
     *     ip: string,
     *     timestamp: string
     * }
     */
    public function getWatermarkPayload(User $user, ?string $clientIp = null): array
    {
        return [
            'name' => $user->name,
            'phone' => $user->phone ?? __('غير مسجل'),
            'user_id' => $user->id,
            'ip' => $clientIp ?? request()->ip() ?? '127.0.0.1',
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
