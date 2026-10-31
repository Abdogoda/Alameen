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
     * Create a direct upload URL on MUX.
     *
     * @return array{id: string, url: string, status: string}|null
     */
    public function createDirectUploadUrl(string $corsOrigin = '*'): ?array
    {
        return $this->muxDriver->createDirectUploadUrl($corsOrigin);
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
