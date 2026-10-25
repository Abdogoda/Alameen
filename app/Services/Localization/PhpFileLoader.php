<?php

declare(strict_types=1);

namespace App\Services\Localization;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Translation\FileLoader;

class PhpFileLoader extends FileLoader
{
    /**
     * Create a new file loader instance.
     *
     * @param  array<int, string>|string  $path
     */
    public function __construct(Filesystem $files, array|string $path)
    {
        parent::__construct($files, $path);
    }

    /**
     * Load messages for string keys from PHP translation files instead of JSON.
     *
     * @param  string  $locale
     * @return array<string, string|array<mixed>>
     */
    protected function loadJsonPaths($locale)
    {
        return (new Collection($this->paths))
            ->reduce(function (array $output, string $path) use ($locale): array {
                $dir = "{$path}/{$locale}";

                if (! $this->files->isDirectory($dir)) {
                    return $output;
                }

                foreach ($this->files->files($dir) as $file) {
                    if ($file->getExtension() === 'php') {
                        $lines = $this->files->getRequire($file->getRealPath());

                        if (is_array($lines)) {
                            $output = array_replace($output, $lines);
                        }
                    }
                }

                return $output;
            }, []);
    }
}
