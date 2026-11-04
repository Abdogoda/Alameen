<?php

declare(strict_types=1);

namespace App\Services\Video\Drivers;

use App\Services\Cms\SettingsService;
use App\Services\Video\Contracts\VideoDriverInterface;
use App\Services\Video\MuxTokenService;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MuxDriver implements VideoDriverInterface
{
    protected ?SettingsService $settingsService = null;

    protected ?MuxTokenService $tokenService = null;

    /**
     * @param  array<int, string>  $playbackPolicy
     */
    public function __construct(
        protected ?string $tokenId = null,
        protected ?string $tokenSecret = null,
        protected string $baseUrl = 'https://api.mux.com/video/v1',
        protected array $playbackPolicy = ['signed'],
        protected string $corsOrigin = '*'
    ) {
        $this->settingsService = app(SettingsService::class);
        $this->tokenService = app(MuxTokenService::class);
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

    /**
     * Retrieve Mux Token ID (Dashboard settings first, fallback to config/env).
     */
    public function getTokenId(): ?string
    {
        $id = $this->settingsService->get('mux_token_id');
        if (! empty($id)) {
            return trim($id);
        }

        return $this->tokenId ?: (config('video.drivers.mux.token_id') ? (string) config('video.drivers.mux.token_id') : null);
    }

    /**
     * Retrieve Mux Token Secret (Dashboard settings first, fallback to config/env).
     */
    public function getTokenSecret(): ?string
    {
        $secret = $this->settingsService->get('mux_token_secret');
        if (! empty($secret)) {
            return trim($secret);
        }

        return $this->tokenSecret ?: (config('video.drivers.mux.token_secret') ? (string) config('video.drivers.mux.token_secret') : null);
    }

    /**
     * Retrieve active playback policy ('signed' recommended for maximum protection).
     *
     * @return array<int, string>
     */
    public function getPlaybackPolicy(): array
    {
        $policy = $this->settingsService->get('mux_playback_policy');
        if (! empty($policy)) {
            $cleaned = trim($policy);

            return $cleaned === 'public' ? ['public'] : ['signed'];
        }

        $configPolicy = config('video.drivers.mux.playback_policy', ['signed']);

        return is_array($configPolicy) ? $configPolicy : [(string) $configPolicy];
    }

    public function isConfigured(): bool
    {
        $id = $this->getTokenId();
        $secret = $this->getTokenSecret();

        return ! empty($id) && ! empty($secret);
    }

    /**
     * Helper to access MuxTokenService for JWT generation.
     */
    public function tokens(): MuxTokenService
    {
        return $this->tokenService;
    }

    /**
     * Test MUX credentials, API connection, and cryptographic signing keys.
     *
     * @return array{
     *     success: bool,
     *     message: string,
     *     api_connected: bool,
     *     signing_ready: bool,
     *     playback_policy: string,
     *     recent_assets_count: int
     * }
     */
    public function testConnection(): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'message' => __('بيانات اتصال MUX API غير مكتملة. يرجى إدخال MUX Token ID و Secret أولاً.'),
                'api_connected' => false,
                'signing_ready' => false,
                'playback_policy' => implode(',', $this->getPlaybackPolicy()),
                'recent_assets_count' => 0,
            ];
        }

        try {
            $response = Http::withBasicAuth($this->getTokenId(), $this->getTokenSecret())
                ->acceptJson()
                ->timeout(10)
                ->get("{$this->baseUrl}/assets", [
                    'limit' => 5,
                ]);

            if (! $response->successful()) {
                $status = $response->status();
                $err = $response->json('error.messages.0') ?? $response->body();

                return [
                    'success' => false,
                    'message' => __('فشل الاتصال بـ MUX (كود :code): :err', ['code' => $status, 'err' => $err]),
                    'api_connected' => false,
                    'signing_ready' => false,
                    'playback_policy' => implode(',', $this->getPlaybackPolicy()),
                    'recent_assets_count' => 0,
                ];
            }

            $assets = $response->json('data') ?? [];
            $signingReady = $this->tokenService->isConfigured();

            return [
                'success' => true,
                'message' => $signingReady
                    ? __('تم الاتصال بسحابة MUX بنجاح! الاتصال يعمل ومفاتيح التوقيع الرقمي (RS256) نشطة ومحمية.')
                    : __('تم الاتصال بـ MUX بنجاح، ولكن مفاتيح التشفير (Signing Keys) غير مهيأة. يرجى إضافتها لتأمين الفيديوهات ومنع تسريبها.'),
                'api_connected' => true,
                'signing_ready' => $signingReady,
                'playback_policy' => implode(',', $this->getPlaybackPolicy()),
                'recent_assets_count' => count($assets),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => __('تعذر الوصول إلى سيرفرات MUX: :err', ['err' => $e->getMessage()]),
                'api_connected' => false,
                'signing_ready' => false,
                'playback_policy' => implode(',', $this->getPlaybackPolicy()),
                'recent_assets_count' => 0,
            ];
        }
    }

    /**
     * Create a Mux Direct Upload URL with signed playback policy and MP4 downloads disabled.
     */
    public function createDirectUploadUrl(string $corsOrigin = '*'): ?array
    {
        if (! $this->isConfigured()) {
            Log::warning('[MUX_DRIVER] createDirectUploadUrl called while MuxDriver is not configured.', [
                'has_token_id' => ! empty($this->getTokenId()),
                'has_token_secret' => ! empty($this->getTokenSecret()),
            ]);

            return null;
        }

        try {
            $policy = $this->getPlaybackPolicy();
            $effectiveCors = $corsOrigin ?: $this->corsOrigin;

            Log::info('[MUX_DRIVER] Requesting direct upload from MUX API', [
                'url' => "{$this->baseUrl}/uploads",
                'cors_origin' => $effectiveCors,
                'playback_policy' => $policy,
            ]);

            $response = Http::withBasicAuth($this->getTokenId(), $this->getTokenSecret())
                ->acceptJson()
                ->post("{$this->baseUrl}/uploads", [
                    'cors_origin' => $effectiveCors,
                    'new_asset_settings' => [
                        'playback_policy' => $policy,
                        'video_quality' => 'basic',
                        'mp4_support' => 'none', // Disables direct downloadable MP4 files
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json('data');
                Log::info('[MUX_DRIVER] Direct upload URL created successfully from Mux API', [
                    'upload_id' => $data['id'] ?? null,
                    'status' => $data['status'] ?? null,
                ]);

                return $data;
            }

            Log::error('[MUX_DRIVER] Direct Upload creation failed from Mux API', [
                'http_status' => $response->status(),
                'response_body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('[MUX_DRIVER] MUX API request exception: '.$e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getUpload(string $uploadId): ?array
    {
        if (! $this->isConfigured()) {
            Log::warning('[MUX_DRIVER] getUpload called while MuxDriver is not configured.', ['upload_id' => $uploadId]);
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->getTokenId(), $this->getTokenSecret())
                ->acceptJson()
                ->get("{$this->baseUrl}/uploads/{$uploadId}");

            if ($response->successful()) {
                return $response->json('data');
            }

            Log::warning('[MUX_DRIVER] getUpload returned unsuccessful status: HTTP '.$response->status().' - '.$response->body(), [
                'upload_id' => $uploadId,
            ]);
        } catch (\Throwable $e) {
            Log::warning('[MUX_DRIVER] getUpload exception: '.$e->getMessage(), ['upload_id' => $uploadId]);
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAsset(string $assetId): ?array
    {
        if (! $this->isConfigured()) {
            Log::warning('[MUX_DRIVER] getAsset called while MuxDriver is not configured.', ['asset_id' => $assetId]);
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->getTokenId(), $this->getTokenSecret())
                ->acceptJson()
                ->get("{$this->baseUrl}/assets/{$assetId}");

            if ($response->successful()) {
                return $response->json('data');
            }

            Log::warning('[MUX_DRIVER] getAsset returned unsuccessful status: HTTP '.$response->status().' - '.$response->body(), [
                'asset_id' => $assetId,
            ]);
        } catch (\Throwable $e) {
            Log::warning('[MUX_DRIVER] getAsset exception: '.$e->getMessage(), ['asset_id' => $assetId]);
        }

        return null;
    }
            Log::warning('MUX getAsset exception: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Delete an Asset from MUX cloud to free up storage and avoid billing.
     */
    public function deleteAsset(string $assetId): bool
    {
        if (! $this->isConfigured() || empty($assetId)) {
            return false;
        }

        try {
            $response = Http::withBasicAuth($this->getTokenId(), $this->getTokenSecret())
                ->acceptJson()
                ->delete("{$this->baseUrl}/assets/{$assetId}");

            if ($response->successful() || $response->status() === 404) {
                Log::info("MUX Asset {$assetId} deleted successfully from cloud.");

                return true;
            }

            Log::warning("Failed to delete MUX Asset {$assetId}: HTTP {$response->status()} - {$response->body()}");
        } catch (\Throwable $e) {
            Log::error("Exception deleting MUX Asset {$assetId}: ".$e->getMessage());
        }

        return false;
    }

    /**
     * Create a new signed playback ID for an existing asset.
     */
    public function createSignedPlaybackId(string $assetId): ?string
    {
        if (! $this->isConfigured() || empty($assetId)) {
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->getTokenId(), $this->getTokenSecret())
                ->acceptJson()
                ->post("{$this->baseUrl}/assets/{$assetId}/playback-ids", [
                    'policy' => 'signed',
                ]);

            if ($response->successful()) {
                return $response->json('data.id');
            }
        } catch (\Throwable $e) {
            Log::error("Failed to create signed playback ID for asset {$assetId}: ".$e->getMessage());
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
     */
    protected function uploadFileToMuxEndpoint(string $filePath, string $uploadUrl, string $mimeType): void
    {
        $fileSize = (int) filesize($filePath);
        if ($fileSize <= 0) {
            throw new \RuntimeException('File size is 0 bytes; cannot upload to MUX.');
        }

        $chunkSize = 5 * 1024 * 1024;

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
                $bytesToRead = max(1, min($chunkSize, $fileSize - $offset));
                $chunkData = fread($handle, $bytesToRead);

                if ($chunkData === false || strlen($chunkData) === 0) {
                    throw new \RuntimeException("Failed to read chunk data at byte offset {$offset}.");
                }

                $chunkLength = strlen($chunkData);
                $end = $offset + $chunkLength - 1;
                $isLastChunk = ($end + 1) >= $fileSize;

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

                    usleep(500000);
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
