<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class StorageLinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:link
                {--relative : Create the symbolic link using relative paths}
                {--force : Recreate existing symbolic links}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the symbolic links configured for the application (Safe for restricted hosting)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $relative = (bool) $this->option('relative');
        $force = (bool) $this->option('force');

        foreach ($this->links() as $link => $target) {
            if (file_exists($link) && ! $this->isRemovableSymlink($link, $force)) {
                $this->components->error("The [{$link}] link already exists.");

                continue;
            }

            if (is_link($link)) {
                @unlink($link);
            }

            $success = $this->createLink($target, $link, $relative);

            if ($success) {
                $this->components->info("The [{$link}] link has been connected to [{$target}].");
            } else {
                $this->components->warn("Unable to create symbolic link [{$link}] to [{$target}] via PHP (functions 'symlink' and 'exec' may be restricted by your host).");
                $this->line("💡 You can manually create it in SSH / Terminal: ln -sfn {$target} {$link}");
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Create the link safely checking available PHP functions.
     */
    protected function createLink(string $target, string $link, bool $relative): bool
    {
        if ($relative && class_exists(SymfonyFilesystem::class)) {
            $relativeTarget = (new SymfonyFilesystem)->makePathRelative($target, dirname($link));
            $target = is_file($target) ? rtrim($relativeTarget, '/') : $relativeTarget;
        }

        if (function_exists('symlink')) {
            try {
                if (@symlink($target, $link)) {
                    return true;
                }
            } catch (\Throwable) {
                // symlink may fail or be restricted
            }
        }

        if (function_exists('exec')) {
            try {
                if (DIRECTORY_SEPARATOR === '\\') {
                    $mode = is_dir($target) ? 'J' : 'H';
                    exec('cmd /c mklink /'.$mode.' '.escapeshellarg($link).' '.escapeshellarg($target));

                    return file_exists($link);
                }

                $output = [];
                $resultCode = 0;
                exec('ln -s '.escapeshellarg($target).' '.escapeshellarg($link), $output, $resultCode);

                return $resultCode === 0 && file_exists($link);
            } catch (\Throwable) {
                // exec may throw if disabled
            }
        }

        return false;
    }

    /**
     * Get the symbolic links configured for the application.
     *
     * @return array<string, string>
     */
    protected function links(): array
    {
        /** @var array<string, string> $links */
        $links = config('filesystems.links', [
            public_path('storage') => storage_path('app/public'),
        ]);

        return $links;
    }

    /**
     * Determine if the provided path is a symlink that can be removed.
     */
    protected function isRemovableSymlink(string $link, bool $force): bool
    {
        return is_link($link) && $force;
    }
}
