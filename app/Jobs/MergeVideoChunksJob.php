<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Course;
use App\Models\Video;
use App\Services\Video\ChunkedUploadService;
use App\Services\Video\VideoServiceManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MergeVideoChunksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 1800;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 2;

    public function __construct(
        public string $uploadId,
        public int $totalChunks,
        public string $originalFilename,
        public int $courseId,
        public ?int $videoId,
        public string $targetType,
    ) {}

    /**
     * Execute the job: merge chunks, store to local storage, update DB record.
     */
    public function handle(): void
    {
        @ini_set('max_execution_time', '0');
        @set_time_limit(0);
        @ini_set('memory_limit', '1024M');
        if (function_exists('ignore_user_abort')) {
            @ignore_user_abort(true);
        }

        $cacheKey = "chunk_merge:{$this->uploadId}";

        try {
            // Phase 1: Merging chunks
            Cache::put($cacheKey, [
                'status' => 'merging',
                'upload_id' => $this->uploadId,
                'target_type' => $this->targetType,
            ], 3600);

            Log::info('[MergeVideoChunksJob] Starting chunk merge', [
                'upload_id' => $this->uploadId,
                'total_chunks' => $this->totalChunks,
                'course_id' => $this->courseId,
                'video_id' => $this->videoId,
                'target_type' => $this->targetType,
            ]);

            /** @var ChunkedUploadService $chunkedUploadService */
            $chunkedUploadService = app(ChunkedUploadService::class);

            /** @var VideoServiceManager $videoManager */
            $videoManager = app(VideoServiceManager::class);

            $mergedFile = $chunkedUploadService->mergeChunks(
                $this->uploadId,
                $this->totalChunks,
                $this->originalFilename
            );

            // Phase 2: Storing to local storage
            Cache::put($cacheKey, [
                'status' => 'storing',
                'upload_id' => $this->uploadId,
                'target_type' => $this->targetType,
            ], 3600);

            $uploadResult = $videoManager->handleVideoUpload($mergedFile, 'local');

            Log::info('[MergeVideoChunksJob] Video stored successfully', [
                'upload_id' => $this->uploadId,
                'video_source' => $uploadResult['video_source'],
                'video_url' => $uploadResult['video_url'],
            ]);

            // Clean up temporary merged file
            @unlink($mergedFile->getRealPath());

            // Phase 3: Update DB record
            if ($this->targetType === 'lesson' && $this->videoId) {
                $targetVideo = Video::where('course_id', $this->courseId)
                    ->where('id', $this->videoId)
                    ->first();

                if ($targetVideo) {
                    $targetVideo->update([
                        'video_source' => $uploadResult['video_source'],
                        'video_url' => $uploadResult['video_url'],
                        'mux_playback_id' => $uploadResult['mux_playback_id'] ?? null,
                        'mux_asset_id' => $uploadResult['mux_asset_id'] ?? null,
                        'bunny_video_id' => null,
                    ]);
                }
            } elseif ($this->targetType === 'promo') {
                $course = Course::find($this->courseId);
                if ($course) {
                    $course->update([
                        'promo_video_source' => $uploadResult['video_source'],
                        'promo_video_url' => $uploadResult['video_url'],
                        'mux_playback_id' => $uploadResult['mux_playback_id'] ?? null,
                        'mux_asset_id' => $uploadResult['mux_asset_id'] ?? null,
                    ]);
                }
            }

            // Phase 4: Mark completed
            Cache::put($cacheKey, [
                'status' => 'completed',
                'upload_id' => $this->uploadId,
                'target_type' => $this->targetType,
                'video_source' => $uploadResult['video_source'],
                'video_url' => $uploadResult['video_url'],
                'mux_playback_id' => $uploadResult['mux_playback_id'] ?? null,
                'mux_asset_id' => $uploadResult['mux_asset_id'] ?? null,
                'driver_used' => $uploadResult['driver_used'] ?? 'local',
                'original_filename' => $this->originalFilename,
            ], 3600);

            Log::info('[MergeVideoChunksJob] Job completed successfully', [
                'upload_id' => $this->uploadId,
                'target_type' => $this->targetType,
            ]);

        } catch (\Throwable $e) {
            Log::error('[MergeVideoChunksJob] Job failed', [
                'upload_id' => $this->uploadId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Clean up chunks on failure
            try {
                app(ChunkedUploadService::class)->cleanup($this->uploadId);
            } catch (\Throwable) {
                // Ignore cleanup errors
            }

            Cache::put($cacheKey, [
                'status' => 'failed',
                'upload_id' => $this->uploadId,
                'target_type' => $this->targetType,
                'error' => $e->getMessage(),
            ], 3600);

            throw $e;
        }
    }
}
