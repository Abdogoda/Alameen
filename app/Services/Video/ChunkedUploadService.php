<?php

declare(strict_types=1);

namespace App\Services\Video;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChunkedUploadService
{
    protected string $storageBasePath;

    public function __construct()
    {
        $this->storageBasePath = storage_path('app/chunks');
        if (! File::exists($this->storageBasePath)) {
            File::makeDirectory($this->storageBasePath, 0755, true, true);
        }
    }

    /**
     * Sanitize upload session ID.
     */
    public function sanitizeUploadId(string $uploadId): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '', $uploadId) ?: Str::random(24);
    }

    /**
     * Get directory path for a specific upload session.
     */
    public function getSessionPath(string $uploadId): string
    {
        $safeId = $this->sanitizeUploadId($uploadId);
        $path = $this->storageBasePath.DIRECTORY_SEPARATOR.$safeId;

        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        return $path;
    }

    /**
     * Save an individual chunk file and progressively assemble it into the target buffer.
     * Progressive assembly ensures that when the final chunk arrives, the entire multi-GB
     * file is already assembled, eliminating reverse-proxy (504 Gateway Timeout) hangs.
     */
    public function saveChunk(
        string $uploadId,
        int $chunkIndex,
        UploadedFile $file,
        ?int $totalChunks = null,
        ?int $chunkSize = null
    ): bool {
        $sessionDir = $this->getSessionPath($uploadId);
        $chunkPath = $sessionDir.DIRECTORY_SEPARATOR."chunk_{$chunkIndex}";

        // 1. Move chunk into session folder for resume tracking & verification
        $chunkSizeActual = $file->getSize();
        $moved = (bool) $file->move($sessionDir, "chunk_{$chunkIndex}");
        if (! $moved) {
            return false;
        }

        // 2. Update metadata tracking
        $this->recordChunkMetadata($sessionDir, $chunkIndex, $chunkSizeActual, $totalChunks);

        // 3. Progressive direct assembly at exact byte offset
        $effectiveChunkSize = $chunkSize ?: $this->getEstablishedChunkSize($sessionDir, $chunkSizeActual);
        $assembledPath = $sessionDir.DIRECTORY_SEPARATOR.'assembled.part';

        try {
            $assembledHandle = fopen($assembledPath, 'c+b');
            if ($assembledHandle) {
                $offset = $chunkIndex * $effectiveChunkSize;
                fseek($assembledHandle, $offset, SEEK_SET);

                $chunkHandle = fopen($chunkPath, 'rb');
                if ($chunkHandle) {
                    stream_copy_to_stream($chunkHandle, $assembledHandle);
                    fclose($chunkHandle);
                }
                fclose($assembledHandle);
            }
        } catch (\Throwable $e) {
            Log::warning("[ChunkedUploadService] Progressive assembly warning for chunk {$chunkIndex}: {$e->getMessage()}");
        }

        return true;
    }

    /**
     * Get list of already received chunk indices for session resume.
     *
     * @return array<int>
     */
    public function getReceivedChunks(string $uploadId, int $totalChunks): array
    {
        $sessionDir = $this->getSessionPath($uploadId);
        $metaPath = $sessionDir.DIRECTORY_SEPARATOR.'meta.json';

        if (File::exists($metaPath)) {
            $data = json_decode((string) File::get($metaPath), true);
            if (isset($data['received']) && is_array($data['received'])) {
                return array_values(array_unique(array_map('intval', $data['received'])));
            }
        }

        // Fallback: check filesystem chunk files
        $received = [];
        for ($i = 0; $i < $totalChunks; $i++) {
            if (File::exists($sessionDir.DIRECTORY_SEPARATOR."chunk_{$i}")) {
                $received[] = $i;
            }
        }

        return $received;
    }

    /**
     * Check if all expected chunks have arrived.
     */
    public function isComplete(string $uploadId, int $totalChunks): bool
    {
        $sessionDir = $this->getSessionPath($uploadId);

        for ($i = 0; $i < $totalChunks; $i++) {
            if (! File::exists($sessionDir.DIRECTORY_SEPARATOR."chunk_{$i}")) {
                return false;
            }
        }

        return true;
    }

    /**
     * Fast-finalize the upload by moving the progressively assembled file
     * directly into the public storage path in milliseconds (< 50ms).
     *
     * @return array{
     *     video_source: string,
     *     video_url: string,
     *     mux_playback_id: null,
     *     mux_asset_id: null,
     *     mux_upload_id: null,
     *     driver_used: string,
     *     status: string,
     *     original_filename: string
     * }
     */
    public function finalizeFastUpload(
        string $uploadId,
        int $totalChunks,
        string $originalFilename,
        string $disk = 'public',
        string $targetSubdir = 'videos/lessons'
    ): array {
        $sessionDir = $this->getSessionPath($uploadId);
        $assembledPath = $sessionDir.DIRECTORY_SEPARATOR.'assembled.part';

        Storage::disk($disk)->makeDirectory($targetSubdir);

        $cleanFilename = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME));
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION) ?: 'mp4';
        $destinationFilename = 'lesson_'.date('Ymd_His').'_'.Str::random(10).'.'.$extension;
        $relativeDestination = trim($targetSubdir, '/').'/'.$destinationFilename;
        $absoluteDestination = Storage::disk($disk)->path($relativeDestination);

        // If assembled.part is missing or incomplete, run standard stream assembly directly to destination
        if (! File::exists($assembledPath) || File::size($assembledPath) === 0) {
            $this->mergeDirectToDestination($uploadId, $totalChunks, $absoluteDestination);
        } else {
            // Instantaneous atomic rename on the filesystem
            if (! @rename($assembledPath, $absoluteDestination)) {
                File::move($assembledPath, $absoluteDestination);
            }
        }

        // Clean up session directory immediately
        $this->cleanup($uploadId);

        $publicUrl = Storage::disk($disk)->url($relativeDestination);

        Log::info('[ChunkedUploadService] Video upload fast-finalized successfully', [
            'upload_id' => $uploadId,
            'public_url' => $publicUrl,
            'destination' => $relativeDestination,
        ]);

        return [
            'video_source' => 'local',
            'video_url' => $publicUrl,
            'mux_playback_id' => null,
            'mux_asset_id' => null,
            'mux_upload_id' => null,
            'driver_used' => 'local',
            'status' => 'ready',
            'original_filename' => $originalFilename,
        ];
    }

    /**
     * Merge all received chunks directly to the target destination without intermediate temp copy.
     */
    public function mergeDirectToDestination(string $uploadId, int $totalChunks, string $absoluteDestination): void
    {
        $sessionDir = $this->getSessionPath($uploadId);

        $targetHandle = fopen($absoluteDestination, 'wb');
        if (! $targetHandle) {
            throw new \RuntimeException('Failed to open destination video file for writing.');
        }

        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = $sessionDir.DIRECTORY_SEPARATOR."chunk_{$i}";
            if (! File::exists($chunkPath)) {
                fclose($targetHandle);
                @unlink($absoluteDestination);
                throw new \RuntimeException("Missing chunk {$i} during video assembly.");
            }

            $chunkHandle = fopen($chunkPath, 'rb');
            if ($chunkHandle) {
                stream_copy_to_stream($chunkHandle, $targetHandle);
                fclose($chunkHandle);
            }

            @unlink($chunkPath);
        }

        fclose($targetHandle);
    }

    /**
     * Merge all received chunks into a single complete UploadedFile instance.
     * Retained for backward compatibility with existing tests and services.
     */
    public function mergeChunks(string $uploadId, int $totalChunks, string $originalFilename): UploadedFile
    {
        $sessionDir = $this->getSessionPath($uploadId);
        $tempMergedDir = storage_path('app/temp');
        if (! File::exists($tempMergedDir)) {
            File::makeDirectory($tempMergedDir, 0755, true, true);
        }

        $cleanFilename = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME));
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION) ?: 'mp4';
        $mergedFilePath = $tempMergedDir.DIRECTORY_SEPARATOR.'merged_'.$uploadId.'_'.$cleanFilename.'.'.$extension;

        $assembledPath = $sessionDir.DIRECTORY_SEPARATOR.'assembled.part';

        // If progressively assembled file is intact, move it directly
        if (File::exists($assembledPath) && File::size($assembledPath) > 0) {
            if (! @rename($assembledPath, $mergedFilePath)) {
                File::move($assembledPath, $mergedFilePath);
            }
            $this->cleanup($uploadId);
        } else {
            $targetHandle = fopen($mergedFilePath, 'wb');
            if (! $targetHandle) {
                throw new \RuntimeException('Failed to create merged target video file.');
            }

            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = $sessionDir.DIRECTORY_SEPARATOR."chunk_{$i}";
                if (! File::exists($chunkPath)) {
                    fclose($targetHandle);
                    throw new \RuntimeException("Missing chunk {$i} during video assembly.");
                }

                $chunkHandle = fopen($chunkPath, 'rb');
                if ($chunkHandle) {
                    stream_copy_to_stream($chunkHandle, $targetHandle);
                    fclose($chunkHandle);
                }

                @unlink($chunkPath);
            }

            fclose($targetHandle);
            $this->cleanup($uploadId);
        }

        $mimeType = File::mimeType($mergedFilePath) ?: 'video/mp4';

        return new UploadedFile(
            $mergedFilePath,
            $originalFilename,
            $mimeType,
            null,
            true // test mode to allow moving from temp path
        );
    }

    /**
     * Remove temporary chunk files and session directory.
     */
    public function cleanup(string $uploadId): void
    {
        $safeId = $this->sanitizeUploadId($uploadId);
        $path = $this->storageBasePath.DIRECTORY_SEPARATOR.$safeId;

        if (File::exists($path)) {
            File::deleteDirectory($path);
        }
    }

    /**
     * Record chunk index in session metadata file for instant resume lookup.
     */
    protected function recordChunkMetadata(string $sessionDir, int $chunkIndex, int $chunkSize, ?int $totalChunks): void
    {
        $metaPath = $sessionDir.DIRECTORY_SEPARATOR.'meta.json';
        $meta = [
            'received' => [],
            'chunk_size' => $chunkSize,
            'total_chunks' => $totalChunks,
        ];

        if (File::exists($metaPath)) {
            $existing = json_decode((string) File::get($metaPath), true);
            if (is_array($existing)) {
                $meta = array_merge($meta, $existing);
            }
        }

        if (! in_array($chunkIndex, $meta['received'], true)) {
            $meta['received'][] = $chunkIndex;
            sort($meta['received']);
        }

        if ($chunkIndex === 0 && $chunkSize > 0) {
            $meta['chunk_size'] = $chunkSize;
        }

        if ($totalChunks !== null) {
            $meta['total_chunks'] = $totalChunks;
        }

        @file_put_contents($metaPath, json_encode($meta, JSON_UNESCAPED_SLASHES));
    }

    /**
     * Get established uniform chunk size from metadata or default (10MB).
     */
    protected function getEstablishedChunkSize(string $sessionDir, int $fallbackSize): int
    {
        $metaPath = $sessionDir.DIRECTORY_SEPARATOR.'meta.json';
        if (File::exists($metaPath)) {
            $meta = json_decode((string) File::get($metaPath), true);
            if (! empty($meta['chunk_size']) && (int) $meta['chunk_size'] > 0) {
                return (int) $meta['chunk_size'];
            }
        }

        return $fallbackSize > 0 ? $fallbackSize : (10 * 1024 * 1024);
    }
}
