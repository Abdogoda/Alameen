<?php

declare(strict_types=1);

namespace App\Services\Video\Drivers;

use App\Services\Video\Contracts\VideoDriverInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MuxDriver implements VideoDriverInterface
{
    public function __construct(
        protected ?string $tokenId = null,
        protected ?string $tokenSecret = null,
        protected string $baseUrl = 'https://api.mux.com/video/v1',
        protected array $playbackPolicy = ['public'],
        protected string $corsOrigin = '*'
    ) {
        $this->tokenId = $this->tokenId ?: config('video.drivers.mux.token_id', env('MUX_TOKEN_ID'));
        $this->tokenSecret = $this->tokenSecret ?: config('video.drivers.mux.token_secret', env('MUX_TOKEN_SECRET'));
        $this->baseUrl = config('video.drivers.mux.base_url', 'https://api.mux.com/video/v1');
    }

    public function name(): string
    {
        return 'mux';
    }

    public function title(): string
    {
        return 'MUX Video Cloud Streaming';
    }

    public function isConfigured(): bool
    {
        return ! empty($this->tokenId) && ! empty($this->tokenSecret);
    }

    public function createDirectUploadUrl(string $corsOrigin = '*'): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->tokenId, $this->tokenSecret)
                ->acceptJson()
                ->post("{$this->baseUrl}/uploads", [
                    'cors_origin' => $corsOrigin ?: $this->corsOrigin,
                    'new_asset_settings' => [
                        'playback_policy' => $this->playbackPolicy,
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

    public function handleUpload(UploadedFile $file): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('MUX API is not configured.');
        }

        $uploadData = $this->createDirectUploadUrl();
        if (! $uploadData || empty($uploadData['url'])) {
            throw new \RuntimeException('Failed to create MUX Direct Upload URL.');
        }

        $stream = fopen($file->getRealPath(), 'r');
        $uploadResponse = Http::withBody(
            $stream,
            $file->getMimeType() ?: 'video/mp4'
        )->put($uploadData['url']);

        if (is_resource($stream)) {
            fclose($stream);
        }

        if (! $uploadResponse->successful()) {
            throw new \RuntimeException('Failed to stream video to MUX: '.$uploadResponse->body());
        }

        // Poll briefly for the created asset
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
            'duration_seconds' => null,
            'status' => 'ready',
        ];
    }

    public function parseInput(string $input): array
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

        // Check if raw Mux Playback ID (alphanumeric string between 16 and 64 characters)
        if (preg_match('/^[a-zA-Z0-9]{16,64}$/', $trimmed)) {
            return [
                'video_source' => 'mux',
                'video_url' => "https://stream.mux.com/{$trimmed}.m3u8",
                'mux_playback_id' => $trimmed,
            ];
        }

        return [
            'video_source' => 'link',
            'video_url' => $trimmed,
            'mux_playback_id' => null,
        ];
    }
}
