<?php

declare(strict_types=1);

namespace App\Services\Video;

use App\Services\Video\Drivers\MuxDriver;
use Illuminate\Http\UploadedFile;

/**
 * Service adapter for MUX video operations.
 * Delegates to the modern VideoServiceManager / MuxDriver architecture
 * while ensuring complete backward compatibility.
 */
class MuxVideoService
{
    protected MuxDriver $muxDriver;

    public function __construct(
        protected VideoServiceManager $manager
    ) {
        /** @var MuxDriver $driver */
        $driver = $this->manager->driver('mux');
        $this->muxDriver = $driver;
    }

    /**
     * Check if MUX credentials are set in environment or config.
     */
    public function isConfigured(): bool
    {
        return $this->muxDriver->isConfigured();
    }

    /**
     * Get underlying driver instance.
     */
    public function getDriver(): MuxDriver
    {
        return $this->muxDriver;
    }

    /**
     * Access token signing service.
     */
    public function tokens(): MuxTokenService
    {
        return $this->muxDriver->tokens();
    }

    /**
     * Test connection and credentials.
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
        return $this->muxDriver->testConnection();
    }

    /**
     * Delete an asset from MUX cloud.
     */
    public function deleteAsset(string $assetId): bool
    {
        return $this->muxDriver->deleteAsset($assetId);
    }

    /**
     * Create a direct upload URL on MUX.
     *
     * @return array{id: string, url: string, status: string}|null
     */
    public function createDirectUploadUrl(string $corsOrigin = '*'): ?array
    {
        return $this->muxDriver->createDirectUploadUrl($corsOrigin);
    }

    /**
     * Create a signed playback ID for an existing asset.
     */
    public function createSignedPlaybackId(string $assetId): ?string
    {
        return $this->muxDriver->createSignedPlaybackId($assetId);
    }

    /**
     * Get details of a direct upload from MUX.
     *
     * @return array<string, mixed>|null
     */
    public function getUpload(string $uploadId): ?array
    {
        return $this->muxDriver->getUpload($uploadId);
    }

    /**
     * Get details of an asset from MUX.
     *
     * @return array<string, mixed>|null
     */
    public function getAsset(string $assetId): ?array
    {
        return $this->muxDriver->getAsset($assetId);
    }

    /**
     * Handle video file upload with automatic fallback to local server.
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
        return $this->manager->handleVideoUpload($file);
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
        return $this->manager->parseVideoInput($input);
    }
}
