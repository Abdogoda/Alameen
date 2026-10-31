<?php

declare(strict_types=1);

namespace App\Services\Video;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
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
     * Save an individual chunk file.
     */
    public function saveChunk(string $uploadId, int $chunkIndex, UploadedFile $file): bool
    {
        $sessionDir = $this->getSessionPath($uploadId);
        $chunkPath = $sessionDir.DIRECTORY_SEPARATOR."chunk_{$chunkIndex}";

        return (bool) $file->move($sessionDir, "chunk_{$chunkIndex}");
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
     * Merge all received chunks into a single complete UploadedFile instance.
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

            // Remove chunk immediately to free disk space
            @unlink($chunkPath);
        }

        fclose($targetHandle);

        // Clean session directory
        $this->cleanup($uploadId);

        $mimeType = File::mimeType($mergedFilePath) ?: 'video/mp4';
        $fileSize = File::size($mergedFilePath);

        return new UploadedFile(
            $mergedFilePath,
            $originalFilename,
            $mimeType,
            null,
            true // test mode to allow moving from temp path
        );
    }

    /**
     * Remove temporary chunk files for an upload session.
     */
    public function cleanup(string $uploadId): void
    {
        $safeId = $this->sanitizeUploadId($uploadId);
        $path = $this->storageBasePath.DIRECTORY_SEPARATOR.$safeId;

        if (File::exists($path)) {
            File::deleteDirectory($path);
        }
    }
}
