<?php

declare(strict_types=1);

namespace App\Services\Video;

use App\Services\Video\Contracts\VideoDriverInterface;
use App\Services\Video\Drivers\LocalDriver;
use App\Services\Video\Drivers\MuxDriver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Manager;

class VideoServiceManager extends Manager
{
    /**
     * Get the default video driver name.
     */
    public function getDefaultDriver(): string
    {
        return config('video.default') ?: 'mux';
    }

    /**
     * Create an instance of the MUX driver.
     */
    protected function createMuxDriver(): VideoDriverInterface
    {
        return new MuxDriver(
            config('video.drivers.mux.token_id'),
            config('video.drivers.mux.token_secret'),
            config('video.drivers.mux.base_url', 'https://api.mux.com/video/v1'),
            config('video.drivers.mux.playback_policy', ['public']),
            config('video.drivers.mux.cors_origin', '*')
        );
    }

    /**
     * Create an instance of the Local server storage driver.
     */
    protected function createLocalDriver(): VideoDriverInterface
    {
        return new LocalDriver(
            config('video.drivers.local.disk', 'public'),
            config('video.drivers.local.path', 'videos/lessons')
        );
    }

    /**
     * Get the currently active driver (falling back to 'local' if default is not configured).
     */
    public function getActiveDriver(): VideoDriverInterface
    {
        $defaultName = $this->getDefaultDriver();
        $driver = $this->driver($defaultName);

        if ($driver->isConfigured()) {
            return $driver;
        }

        // Automatic fallback to local server driver if default driver (e.g. MUX) is not configured
        return $this->driver('local');
    }

    /**
     * Check if Mux driver is currently configured and operational.
     */
    public function isMuxConfigured(): bool
    {
        return $this->driver('mux')->isConfigured();
    }

    /**
     * Create direct upload URL using the active or requested driver.
     *
     * @return array{id: string, url: string, status: string}|null
     */
    public function createDirectUploadUrl(string $corsOrigin = '*', ?string $driverName = null): ?array
    {
        $driver = $driverName ? $this->driver($driverName) : $this->getActiveDriver();

        return $driver->createDirectUploadUrl($corsOrigin);
    }

    /**
     * Handle video upload with automatic resilient fallback to local server.
     *
     * @return array{
     *     video_source: string,
     *     video_url: string|null,
     *     mux_playback_id: string|null,
     *     mux_asset_id: string|null,
     *     mux_upload_id: string|null,
     *     duration_seconds?: int|null,
     *     status: string,
     *     driver_used: string
     * }
     */
    public function handleVideoUpload(UploadedFile $file, ?string $preferredDriver = null): array
    {
        $driverName = $preferredDriver ?: $this->getDefaultDriver();
        $targetDriver = $this->driver($driverName);

        // 1. Try preferred driver if configured
        if ($targetDriver->isConfigured()) {
            try {
                $result = $targetDriver->handleUpload($file);
                if (! empty($result['video_url']) || ! empty($result['mux_playback_id'])) {
                    $result['driver_used'] = $targetDriver->name();

                    return $result;
                }
            } catch (\Throwable $e) {
                Log::warning("Video driver [{$targetDriver->name()}] upload failed, executing fallback to local server storage: {$e->getMessage()}");
            }
        }

        // 2. Guaranteed fallback to local server storage
        $localDriver = $this->driver('local');
        $result = $localDriver->handleUpload($file);
        $result['driver_used'] = 'local';

        return $result;
    }

    /**
     * Parse any user video input string (MUX Playback ID, stream.mux.com, YouTube, Vimeo, HLS, MP4)
     * into a normalized source structure.
     *
     * @return array{
     *     video_source: string,
     *     video_url: string,
     *     mux_playback_id: string|null
     * }
     */
    public function parseVideoInput(string $input): array
    {
        // Try MUX parser first
        $muxDriver = $this->driver('mux');
        $muxParsed = $muxDriver->parseInput($input);
        if ($muxParsed['video_source'] === 'mux') {
            return $muxParsed;
        }

        // Fall back to general local/external parser
        return $this->driver('local')->parseInput($input);
    }
}
