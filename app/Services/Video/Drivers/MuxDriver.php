<?php

declare(strict_types=1);

namespace App\Services\Video\Drivers;

use App\Services\Video\Contracts\VideoDriverInterface;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MuxDriver implements VideoDriverInterface
{
    /**
     * @param  array<int, string>  $playbackPolicy
     */
    public function __construct(
        protected ?string $tokenId = null,
        protected ?string $tokenSecret = null,
        protected string $baseUrl = 'https://api.mux.com/video/v1',
        protected array $playbackPolicy = ['public'],
        protected string $corsOrigin = '*'
    ) {
        $this->tokenId = $this->tokenId ?: (config('video.drivers.mux.token_id') ? (string) config('video.drivers.mux.token_id') : null);
        $this->tokenSecret = $this->tokenSecret ?: (config('video.drivers.mux.token_secret') ? (string) config('video.drivers.mux.token_secret') : null);
        $this->baseUrl = (string) config('video.drivers.mux.base_url', 'https://api.mux.com/video/v1');
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

    /**
     * @return array<string, mixed>|null
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
     * @return array<string, mixed>|null
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

    public function handleUpload(UploadedFile $file): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('MUX API is not configured.');
        }

        $uploadData = $this->createDirectUploadUrl();
        if (! $uploadData || empty($uploadData['url'])) {
            throw new \RuntimeException('Failed to create MUX Direct Upload URL.');
        }

        $realPath = $file->getRealPath();
        $mimeType = $file->getMimeType() ?: 'video/mp4';

        $this->uploadFileToMuxEndpoint($realPath, $uploadData['url'], $mimeType);

        // Poll briefly for the created asset
        $assetId = null;
        $playbackId = null;

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $uploadStatus = $this->getUpload($uploadData['id']);
            $assetId = $uploadStatus['asset_id'] ?? null;
            if ($assetId) {
                break;
            }
            if ($attempt < 4) {
                sleep(1);
            }
        }

        if ($assetId) {
            for ($attempt = 0; $attempt < 3; $attempt++) {
                $assetData = $this->getAsset($assetId);
                $playbackId = $assetData['playback_ids'][0]['id'] ?? null;
                if ($playbackId) {
                    break;
                }
                if ($attempt < 2) {
                    sleep(1);
                }
            }
        }

        return [
            'video_source' => 'mux',
            'video_url' => $playbackId ? "https://stream.mux.com/{$playbackId}.m3u8" : null,
            'mux_playback_id' => $playbackId,
            'mux_asset_id' => $assetId,
            'mux_upload_id' => $uploadData['id'],
            'duration_seconds' => null,
            'status' => $playbackId ? 'ready' : 'preparing',
        ];
    }

    /**
     * Upload file to MUX direct upload endpoint using resilient chunked streaming.
     * MUX direct upload endpoints support standard Google Cloud resumable chunk uploads.
     * Chunks must be multiples of 256 KiB (262,144 bytes).
     */
    protected function uploadFileToMuxEndpoint(string $filePath, string $uploadUrl, string $mimeType): void
    {
        $fileSize = (int) filesize($filePath);
        if ($fileSize <= 0) {
            throw new \RuntimeException('File size is 0 bytes; cannot upload to MUX.');
        }

        // MUX requires chunk sizes to be a multiple of 256 KiB (262,144 bytes)
        // 5 MiB = 5 * 1024 * 1024 = 5,242,880 bytes (20 * 256 KiB)
        $chunkSize = 5 * 1024 * 1024;

        // If file is smaller than or equal to one chunk, perform single PUT request
        if ($fileSize <= $chunkSize) {
            $handle = fopen($filePath, 'rb');
            if (! $handle) {
                throw new \RuntimeException('Failed to open file stream for upload.');
            }

            try {
                $timeout = (int) config('video.drivers.mux.timeout', 3600);
                $response = Http::timeout($timeout)
                    ->connectTimeout(30)
                    ->withHeaders([
                        'Content-Type' => $mimeType,
                        'Content-Length' => (string) $fileSize,
                    ])
                    ->withBody(Utils::streamFor($handle), $mimeType)
                    ->put($uploadUrl);

                if (! $response->successful()) {
                    throw new \RuntimeException('Failed to stream video to MUX: '.$response->body());
                }
            } finally {
                if (is_resource($handle)) {
                    fclose($handle);
                }
            }

            return;
        }

        // Resumable Chunked Upload for larger files
        $handle = fopen($filePath, 'rb');
        if (! $handle) {
            throw new \RuntimeException('Failed to open file stream for upload.');
        }

        try {
            $offset = 0;

            while ($offset < $fileSize) {
                $bytesToRead = min($chunkSize, $fileSize - $offset);
                $chunkData = fread($handle, $bytesToRead);

                if ($chunkData === false || strlen($chunkData) === 0) {
                    throw new \RuntimeException("Failed to read chunk data at byte offset {$offset}.");
                }

                $chunkLength = strlen($chunkData);
                $end = $offset + $chunkLength - 1;
                $isLastChunk = ($end + 1) >= $fileSize;

                // Retry logic for individual chunk
                $uploaded = false;
                $lastError = null;

                for ($retry = 0; $retry < 3; $retry++) {
                    try {
                        $response = Http::timeout(180)
                            ->connectTimeout(30)
                            ->withOptions([
                                'http_errors' => false,
                                'allow_redirects' => false,
                            ])
                            ->withHeaders([
                                'Content-Type' => $mimeType,
                                'Content-Length' => (string) $chunkLength,
                                'Content-Range' => "bytes {$offset}-{$end}/{$fileSize}",
                            ])
                            ->withBody($chunkData, $mimeType)
                            ->put($uploadUrl);

                        $status = $response->status();

                        // 308 Resume Incomplete is expected for intermediate chunks; 200/201 for final chunk
                        if ($isLastChunk) {
                            if ($status >= 200 && $status < 300) {
                                $uploaded = true;
                                break;
                            }
                        } else {
                            if ($status === 308 || ($status >= 200 && $status < 300)) {
                                $uploaded = true;
                                break;
                            }
                        }

                        $lastError = "HTTP {$status}: ".$response->body();
                    } catch (\Throwable $e) {
                        $lastError = $e->getMessage();
                    }

                    usleep(500000); // 500ms delay before retry
                }

                if (! $uploaded) {
                    throw new \RuntimeException("MUX chunk upload failed at bytes {$offset}-{$end}/{$fileSize}: {$lastError}");
                }

                $offset += $chunkLength;
            }
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }
        }
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
