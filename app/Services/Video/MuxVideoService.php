<?php

declare(strict_types=1);

namespace App\Services\Video;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MuxVideoService
{
    protected ?string $tokenId;

    protected ?string $tokenSecret;

    protected string $baseUrl = 'https://api.mux.com/video/v1';

    public function __construct()
    {
        $this->tokenId = config('services.mux.token_id') ?: env('MUX_TOKEN_ID');
        $this->tokenSecret = config('services.mux.token_secret') ?: env('MUX_TOKEN_SECRET');
    }

    /**
     * Check if MUX credentials are set in environment.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->tokenId) && ! empty($this->tokenSecret);
    }

    /**
     * Create a direct upload URL on MUX.
     *
     * @return array{id: string, url: string, status: string}|null
     */
    public function createDirectUploadUrl(string $corsOrigin = '*'): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->tokenId, $this->tokenSecret)
                ->acceptJson()
                ->post("{$this->baseUrl}/uploads", [
                    'cors_origin' => $corsOrigin,
                    'new_asset_settings' => [
                        'playback_policy' => ['public'],
                        'video_quality' => 'basic',
                    ],
                ]);

            if ($response->successful()) {
                return $response->json('data');
            }

            Log::error('MUX Direct Upload creation failed: '.$response->body());
        } catch (\Throwable $e) {
            Log::error('MUX API request exception: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Get details of a direct upload from MUX.
     */
    public function getUpload(string $uploadId): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->tokenId, $this->tokenSecret)
                ->acceptJson()
                ->get("{$this->baseUrl}/uploads/{$uploadId}");

            if ($response->successful()) {
                return $response->json('data');
            }
        } catch (\Throwable $e) {
            Log::warning('MUX getUpload exception: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Get details of an asset from MUX.
     */
    public function getAsset(string $assetId): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->tokenId, $this->tokenSecret)
                ->acceptJson()
                ->get("{$this->baseUrl}/assets/{$assetId}");

            if ($response->successful()) {
                return $response->json('data');
            }
        } catch (\Throwable $e) {
            Log::warning('MUX getAsset exception: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Create asset from public video URL on MUX.
     */
    public function createAssetFromUrl(string $url): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->tokenId, $this->tokenSecret)
                ->acceptJson()
                ->post("{$this->baseUrl}/assets", [
                    'input' => [
                        ['url' => $url],
                    ],
                    'playback_policy' => ['public'],
                ]);

            if ($response->successful()) {
                return $response->json('data');
            }

            Log::error('MUX createAssetFromUrl error: '.$response->body());
        } catch (\Throwable $e) {
            Log::error('MUX createAssetFromUrl exception: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Handle video file upload through the platform.
     * If MUX is configured, uploads directly to MUX.
     * Otherwise, stores video locally in public storage.
     *
     * @return array{
     *     video_source: string,
     *     video_url: string|null,
     *     mux_playback_id: string|null,
     *     mux_asset_id: string|null,
     *     mux_upload_id: string|null
     * }
     */
    public function handleVideoUpload(UploadedFile $file): array
    {
        // 1. Try MUX Direct Upload if configured
        if ($this->isConfigured()) {
            $uploadData = $this->createDirectUploadUrl();
            if ($uploadData && ! empty($uploadData['url'])) {
                try {
                    $uploadResponse = Http::withBody(
                        fopen($file->getRealPath(), 'r'),
                        $file->getMimeType() ?: 'video/mp4'
                    )->put($uploadData['url']);

                    if ($uploadResponse->successful()) {
                        // Polling briefly for the created asset
                        $uploadStatus = $this->getUpload($uploadData['id']);
                        $assetId = $uploadStatus['asset_id'] ?? null;
                        $playbackId = null;

                        if ($assetId) {
                            $assetData = $this->getAsset($assetId);
                            $playbackId = $assetData['playback_ids'][0]['id'] ?? null;
                        }

                        return [
                            'video_source' => 'mux',
                            'video_url' => $playbackId ? "https://stream.mux.com/{$playbackId}.m3u8" : null,
                            'mux_playback_id' => $playbackId,
                            'mux_asset_id' => $assetId,
                            'mux_upload_id' => $uploadData['id'],
                        ];
                    }
                } catch (\Throwable $e) {
                    Log::warning('MUX direct upload via PUT failed, falling back to local: '.$e->getMessage());
                }
            }
        }

        // 2. Fallback: Save to application public storage
        $filename = 'course_video_'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('videos', $filename, 'public');
        $publicUrl = Storage::url($path);

        return [
            'video_source' => 'upload',
            'video_url' => $publicUrl,
            'mux_playback_id' => null,
            'mux_asset_id' => null,
            'mux_upload_id' => null,
        ];
    }

    /**
     * Parse any user input (link, Mux ID, Youtube, Vimeo, HLS) into normalized source details.
     *
     * @return array{
     *     video_source: string,
     *     video_url: string,
     *     mux_playback_id: string|null
     * }
     */
    public function parseVideoInput(string $input): array
    {
        $trimmed = trim($input);

        // Check if Mux stream URL: https://stream.mux.com/{PLAYBACK_ID}.m3u8
        if (preg_match('#stream\.mux\.com/([a-zA-Z0-9_\-]+)(\.m3u8)?#i', $trimmed, $matches)) {
            $playbackId = $matches[1];

            return [
                'video_source' => 'mux',
                'video_url' => "https://stream.mux.com/{$playbackId}.m3u8",
                'mux_playback_id' => $playbackId,
            ];
        }

        // Check if raw Mux Playback ID (alphanumeric string between 15 and 64 characters without slash/protocol)
        if (preg_match('/^[a-zA-Z0-9]{16,64}$/', $trimmed)) {
            return [
                'video_source' => 'mux',
                'video_url' => "https://stream.mux.com/{$trimmed}.m3u8",
                'mux_playback_id' => $trimmed,
            ];
        }

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

        // Default external link / MP4
        return [
            'video_source' => 'link',
            'video_url' => $trimmed,
            'mux_playback_id' => null,
        ];
    }
}
