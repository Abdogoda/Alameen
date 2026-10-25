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
     * Load messages for string keys exclusively from PHP translation files.
     *
     * @param  string  $locale
     * @return array<string, string|array<mixed>>
     */
    protected function loadJsonPaths($locale)
    {
        /** @var array<string, string> $output */
        $output = [];

        // 1. Load standard lang/{locale}/*.php files
        foreach ($this->paths as $path) {
            $dir = "{$path}/{$locale}";

            if (! $this->files->isDirectory($dir)) {
                continue;
            }

            foreach ($this->files->files($dir) as $file) {
                if ($file->getExtension() === 'php') {
                    $prefix = $file->getBasename('.php');
                    $lines = $this->files->getRequire($file->getRealPath());

                    if (is_array($lines)) {
                        // Flatten with file prefix so __('landing.heroTitle') works
                        $output = array_replace($output, $this->flatten($lines, $prefix));
                        // Also register direct root keys so __('Save') or __('الرئيسية') works
                        $output = array_replace($output, $this->flatten($lines));
                    }
                }
            }
        }

        // 2. If English locale, cross-map Arabic string keys to English translations from PHP files
        if ($locale === 'en') {
            foreach ($this->paths as $path) {
                $arDir = "{$path}/ar";
                $enDir = "{$path}/en";

                if (! $this->files->isDirectory($arDir) || ! $this->files->isDirectory($enDir)) {
                    continue;
                }

                foreach ($this->files->files($arDir) as $arFile) {
                    $base = $arFile->getBasename();
                    $enFilePath = "{$enDir}/{$base}";

                    if ($this->files->exists($enFilePath)) {
                        $arLines = $this->flatten($this->files->getRequire($arFile->getRealPath()));
                        $enLines = $this->flatten($this->files->getRequire($enFilePath));

                        foreach ($arLines as $k => $arText) {
                            if (is_string($arText) && $arText !== '' && isset($enLines[$k]) && is_string($enLines[$k])) {
                                $output[$arText] = $enLines[$k];
                            }
                        }
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Flatten multi-dimensional array into dot-notated string keys.
     *
     * @param  array<string, mixed>  $array
     * @return array<string, string>
     */
    protected function flatten(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $fullKey = $prefix === '' ? (string) $key : "{$prefix}.{$key}";
            if (is_array($value)) {
                $result = array_replace($result, $this->flatten($value, $fullKey));
            } elseif (is_string($value)) {
                $result[$fullKey] = $value;
            }
        }

        return $result;
    }
}
