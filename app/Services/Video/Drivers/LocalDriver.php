<?php

declare(strict_types=1);

namespace App\Services\Video\Drivers;

use App\Services\Video\Contracts\VideoDriverInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalDriver implements VideoDriverInterface
{
    public function __construct(
        protected string $disk = 'public',
        protected string $path = 'videos/lessons'
    ) {}

    public function name(): string
    {
        return 'local';
    }

    public function title(): string
    {
        return 'سيرفر المنصة المحلي (Local Storage)';
    }

    public function isConfigured(): bool
    {
        // Local server storage is always available as long as disk exists
        return true;
    }

    public function handleUpload(UploadedFile $file): array
    {
        $extension = $file->getClientOriginalExtension() ?: 'mp4';
        $filename = 'lesson_'.date('Ymd_His').'_'.Str::random(10).'.'.$extension;

        $storedPath = $file->storeAs($this->path, $filename, $this->disk);
        $publicUrl = Storage::disk($this->disk)->url($storedPath);

        return [
            'video_source' => 'local',
            'video_url' => $publicUrl,
            'mux_playback_id' => null,
            'mux_asset_id' => null,
            'mux_upload_id' => null,
            'duration_seconds' => null,
            'status' => 'ready',
        ];
    }

    public function createDirectUploadUrl(string $corsOrigin = '*'): ?array
    {
        // Local uploads happen via the platform chunked upload endpoint
        return null;
    }

    public function parseInput(string $input): array
    {
        $trimmed = trim($input);

        // Check if YouTube
        if (preg_match('#(?:youtube\.com/(?:watch\?v=|embed/)|youtu\.be/)([a-zA-Z0-9_\-]+)#i', $trimmed, $matches)) {
            $youtubeId = $matches[1];

            return [
                'video_source' => 'youtube',
                'video_url' => "https://www.youtube.com/embed/{$youtubeId}",
                'mux_playback_id' => null,
            ];
        }

        // Check if Vimeo
        if (preg_match('#(?:vimeo\.com/(?:video/)?)([0-9]+)#i', $trimmed, $matches)) {
            $vimeoId = $matches[1];

            return [
                'video_source' => 'vimeo',
                'video_url' => "https://player.vimeo.com/video/{$vimeoId}",
                'mux_playback_id' => null,
            ];
        }

        // Check if direct HLS (.m3u8)
        if (str_ends_with(strtolower($trimmed), '.m3u8')) {
            return [
                'video_source' => 'hls',
                'video_url' => $trimmed,
                'mux_playback_id' => null,
            ];
        }

        // Check if local storage URL
        if (str_contains($trimmed, '/storage/')) {
            return [
                'video_source' => 'local',
                'video_url' => $trimmed,
                'mux_playback_id' => null,
            ];
        }

        // Default external link / MP4
        return [
            'video_source' => 'link',
            'video_url' => $trimmed,
            'mux_playback_id' => null,
        ];
    }
}
