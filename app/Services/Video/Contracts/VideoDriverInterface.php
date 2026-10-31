<?php

declare(strict_types=1);

namespace App\Services\Video\Contracts;

use Illuminate\Http\UploadedFile;

interface VideoDriverInterface
{
    /**
     * Unique identifier for this video driver (e.g. 'mux', 'local', 'bunny').
     */
    public function name(): string;

    /**
     * Human-readable title for the driver (e.g. 'MUX Video Cloud', 'سيرفر المنصة المحلي').
     */
    public function title(): string;

    /**
     * Determine if driver credentials/setup are valid and operational.
     */
    public function isConfigured(): bool;

    /**
     * Process an uploaded video file (direct upload, transcoding, or storage).
     *
     * @return array{
     *     video_source: string,
     *     video_url: string|null,
     *     mux_playback_id: string|null,
     *     mux_asset_id: string|null,
     *     mux_upload_id: string|null,
     *     duration_seconds?: int|null,
     *     status: string
     * }
     */
    public function handleUpload(UploadedFile $file): array;

    /**
     * Create a pre-signed or direct upload URL for frontend async uploads if supported.
     *
     * @return array{id: string, url: string, status: string}|null
     */
    public function createDirectUploadUrl(string $corsOrigin = '*'): ?array;

    /**
     * Parse arbitrary user input (URL, embed code, playback ID) into normalized video source details.
     *
     * @return array{
     *     video_source: string,
     *     video_url: string,
     *     mux_playback_id: string|null
     * }
     */
    public function parseInput(string $input): array;
}
