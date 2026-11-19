<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CleanUploadTempCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clean-temp
                            {--hours=24 : Age in hours after which chunks and temp files are considered stale and safe to delete}
                            {--dry-run : Report what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely clean up abandoned video upload chunks and temporary merged files older than the specified retention period';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hours = max(1, (int) $this->option('hours'));
        $dryRun = (bool) $this->option('dry-run');
        $cutoffTime = Carbon::now()->subHours($hours)->getTimestamp();

        $this->info("Scanning storage for chunks and temp files older than {$hours} hours (Cutoff: ".date('Y-m-d H:i:s', $cutoffTime).')...');

        $tempPath = storage_path('app/temp');
        $chunksPath = storage_path('app/chunks');

        $totalBytesFreed = 0;
        $tempFilesDeleted = 0;
        $chunkDirsDeleted = 0;

        // 1. Clean stale merged files in storage/app/temp
        if (File::exists($tempPath)) {
            $tempFiles = File::files($tempPath);

            foreach ($tempFiles as $file) {
                $filePath = $file->getRealPath();
                $lastModified = @filemtime($filePath);
                $fileSize = (int) ($file->getSize() ?: 0);

                if ($lastModified !== false && $lastModified < $cutoffTime) {
                    $this->line("Found stale temp file: {$file->getFilename()} (".self::formatBytes($fileSize).', modified '.date('Y-m-d H:i', $lastModified).')');

                    if (! $dryRun) {
                        if (@unlink($filePath)) {
                            $totalBytesFreed += $fileSize;
                            $tempFilesDeleted++;
                        } else {
                            $this->warn("Failed to delete temp file: {$filePath}");
                        }
                    } else {
                        $totalBytesFreed += $fileSize;
                        $tempFilesDeleted++;
                    }
                }
            }
        }

        // 2. Clean stale chunk session directories in storage/app/chunks
        if (File::exists($chunksPath)) {
            $chunkDirs = File::directories($chunksPath);

            foreach ($chunkDirs as $dirPath) {
                $dirModified = @filemtime($dirPath);
                $dirSize = $this->getDirectorySize($dirPath);

                // Check directory timestamp or files inside to determine if session is abandoned
                $isStale = false;
                if ($dirModified !== false && $dirModified < $cutoffTime) {
                    $isStale = true;
                } else {
                    // Check if latest modified file inside is also older than cutoff
                    $filesInDir = File::allFiles($dirPath);
                    if (empty($filesInDir)) {
                        $isStale = true;
                    } else {
                        $latestFileTime = 0;
                        foreach ($filesInDir as $f) {
                            $latestFileTime = max($latestFileTime, (int) @filemtime($f->getRealPath()));
                        }
                        if ($latestFileTime > 0 && $latestFileTime < $cutoffTime) {
                            $isStale = true;
                        }
                    }
                }

                if ($isStale) {
                    $dirName = basename($dirPath);
                    $this->line("Found stale chunk session: {$dirName} (".self::formatBytes($dirSize).')');

                    if (! $dryRun) {
                        if (File::deleteDirectory($dirPath)) {
                            $totalBytesFreed += $dirSize;
                            $chunkDirsDeleted++;
                        } else {
                            $this->warn("Failed to delete chunk directory: {$dirPath}");
                        }
                    } else {
                        $totalBytesFreed += $dirSize;
                        $chunkDirsDeleted++;
                    }
                }
            }
        }

        $formattedFreed = self::formatBytes($totalBytesFreed);

        if ($dryRun) {
            $this->info("[DRY RUN] Would delete {$tempFilesDeleted} temp file(s) and {$chunkDirsDeleted} chunk session(s).");
            $this->info("[DRY RUN] Potential disk space to be reclaimed: {$formattedFreed}");
        } else {
            $this->info("Cleanup completed! Successfully deleted {$tempFilesDeleted} temp file(s) and {$chunkDirsDeleted} chunk session(s).");
            $this->info("Total disk space reclaimed: {$formattedFreed}");

            Log::info('[CleanUploadTempCommand] Storage cleanup completed', [
                'hours_threshold' => $hours,
                'temp_files_deleted' => $tempFilesDeleted,
                'chunk_dirs_deleted' => $chunkDirsDeleted,
                'bytes_freed' => $totalBytesFreed,
                'formatted_freed' => $formattedFreed,
            ]);
        }

        return self::SUCCESS;
    }

    /**
     * Calculate recursive directory size in bytes.
     */
    protected function getDirectorySize(string $path): int
    {
        $size = 0;
        try {
            $files = File::allFiles($path);
            foreach ($files as $file) {
                $size += (int) ($file->getSize() ?: 0);
            }
        } catch (\Throwable) {
            // Ignore permissions or reading errors
        }

        return $size;
    }

    /**
     * Format bytes into human-readable string.
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min((int) $pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision).' '.$units[$pow];
    }
}
