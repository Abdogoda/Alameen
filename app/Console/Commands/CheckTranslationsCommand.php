<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CheckTranslationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:check
                            {--locale=en : The locale to check translations against (default: en)}
                            {--fix : Automatically extract and populate missing translation keys into lang/{locale}.json}
                            {--unwrapped : Report only hardcoded unwrapped Arabic text in Blade templates}
                            {--missing : Report only missing translation keys}
                            {--json : Output audit results as structured JSON}
                            {--strict : Return exit code 1 if any missing translations or unwrapped text found}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit the application for missing translations, mismatched placeholders, and hardcoded static text';

    /**
     * Arabic unicode range regex.
     */
    protected string $arabicRegex = '/[\x{0600}-\x{06FF}]/u';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $locale = (string) $this->option('locale') ?: 'en';
        $onlyUnwrapped = (bool) $this->option('unwrapped');
        $onlyMissing = (bool) $this->option('missing');
        $asJson = (bool) $this->option('json');
        $shouldFix = (bool) $this->option('fix');
        $strict = (bool) $this->option('strict');

        if (! $asJson) {
            $this->info("=== Scanning System Translations & Static Text (Locale: [{$locale}]) ===");
        }

        // 1. Scan codebase for translation function calls
        $discovered = $this->scanCodebaseForTranslations();

        // 2. Scan blade templates for unwrapped static Arabic text
        $unwrapped = $this->scanBladeForUnwrappedText();

        // 3. Load existing translations
        $translations = $this->loadTranslations($locale);

        // 4. Audit keys against loaded translations
        $auditResult = $this->auditKeys($discovered, $translations, $locale);

        // 5. Handle --fix flag if requested
        $fixedCount = 0;
        if ($shouldFix) {
            $fixedCount = $this->applyFix($auditResult['missing_keys'], $locale);
            if (! $asJson) {
                $this->info("✓ Successfully synced {$fixedCount} translation keys into lang/{$locale}.json.");
            }
            // Reload translations and re-audit after fix
            $translations = $this->loadTranslations($locale);
            $auditResult = $this->auditKeys($discovered, $translations, $locale);
        }

        // 6. Output as JSON if requested
        if ($asJson) {
            $output = [
                'locale' => $locale,
                'metrics' => [
                    'files_scanned' => $discovered['scanned_files_count'],
                    'total_keys_found' => count($discovered['keys']),
                    'translated_count' => count($discovered['keys']) - count($auditResult['missing_keys']),
                    'missing_count' => count($auditResult['missing_keys']),
                    'untranslated_identical' => count($auditResult['untranslated_identical']),
                    'mismatched_placeholders' => count($auditResult['mismatched_placeholders']),
                    'unwrapped_arabic_lines' => count($unwrapped),
                ],
                'missing_keys' => $auditResult['missing_keys'],
                'mismatched_placeholders' => $auditResult['mismatched_placeholders'],
                'unwrapped_arabic_text' => $unwrapped,
            ];
            $this->line((string) json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            return ($strict && (count($auditResult['missing_keys']) > 0 || count($unwrapped) > 0)) ? self::FAILURE : self::SUCCESS;
        }

        // 7. Render Console Summary & Tables
        $totalKeys = count($discovered['keys']);
        $missingCount = count($auditResult['missing_keys']);
        $translatedCount = $totalKeys - $missingCount;
        $coveragePct = $totalKeys > 0 ? round(($translatedCount / $totalKeys) * 100, 1) : 100;

        $this->table(
            ['Metric', 'Value', 'Status'],
            [
                ['Scanned Files', (string) $discovered['scanned_files_count'], 'INFO'],
                ['Total Translation Keys Discovered', (string) $totalKeys, 'INFO'],
                ['Translated Keys (in '.$locale.')', "{$translatedCount} ({$coveragePct}%)", $coveragePct >= 95 ? 'OK' : 'ATTENTION'],
                ['Missing Translations', (string) $missingCount, $missingCount === 0 ? 'OK' : 'FAIL'],
                ['Untranslated / Identical Values', (string) count($auditResult['untranslated_identical']), count($auditResult['untranslated_identical']) === 0 ? 'OK' : 'WARN'],
                ['Mismatched Placeholders', (string) count($auditResult['mismatched_placeholders']), count($auditResult['mismatched_placeholders']) === 0 ? 'OK' : 'WARN'],
                ['Hardcoded Unwrapped Arabic Lines', (string) count($unwrapped), count($unwrapped) === 0 ? 'OK' : 'WARN'],
            ]
        );

        // Display Missing Translations Table (if not only unwrapped)
        if (! $onlyUnwrapped && count($auditResult['missing_keys']) > 0) {
            $this->warn("\nMissing Translation Keys in [{$locale}] (Sample of first 25):");
            $rows = [];
            foreach (array_slice($auditResult['missing_keys'], 0, 25, true) as $k => $locs) {
                $rows[] = [
                    mb_substr($k, 0, 50).(mb_strlen($k) > 50 ? '...' : ''),
                    implode(', ', array_slice($locs, 0, 2)),
                    count($locs),
                ];
            }
            $this->table(['Key (Arabic / Code)', 'Occurrences Sample', 'Count'], $rows);

            if ($missingCount > 25) {
                $this->line('... and '.($missingCount - 25).' more missing keys.');
            }

            if (! $shouldFix) {
                $this->comment('Tip: Run "php artisan translations:check --fix" to automatically generate missing translations in lang/'.$locale.'.json.');
            }
        }

        // Display Mismatched Placeholders
        if (! empty($auditResult['mismatched_placeholders'])) {
            $this->error("\nMismatched Placeholders in [{$locale}]:");
            $pRows = [];
            foreach ($auditResult['mismatched_placeholders'] as $item) {
                $pRows[] = [
                    mb_substr($item['key'], 0, 40),
                    implode(', ', $item['expected_params']),
                    mb_substr($item['translation'], 0, 40),
                ];
            }
            $this->table(['Original Key', 'Expected Params', 'Current Translation'], $pRows);
        }

        // Display Unwrapped Static Text in Blade
        if (! $onlyMissing && count($unwrapped) > 0) {
            $this->warn("\nHardcoded / Unwrapped Arabic Text in Blade Templates (Sample of first 25):");
            $uRows = [];
            foreach (array_slice($unwrapped, 0, 25) as $u) {
                $uRows[] = [
                    $u['file'],
                    $u['line'],
                    $u['text'],
                ];
            }
            $this->table(['Template File', 'Line', 'Unwrapped Text Snippet'], $uRows);

            if (count($unwrapped) > 25) {
                $this->line('... and '.(count($unwrapped) - 25).' more unwrapped lines.');
            }
        }

        if ($missingCount === 0 && count($unwrapped) === 0) {
            $this->info("\n✓ System localization audit passed with 100% translation coverage and no unwrapped static text!");

            return self::SUCCESS;
        }

        if ($strict && ($missingCount > 0 || count($unwrapped) > 0)) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * Scan codebase for all translation calls.
     *
     * @return array{keys: array<string, list<string>>, scanned_files_count: int}
     */
    protected function scanCodebaseForTranslations(): array
    {
        $base = base_path();
        $directories = [
            $base.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views',
            $base.DIRECTORY_SEPARATOR.'app',
            $base.DIRECTORY_SEPARATOR.'routes',
        ];

        $files = [];
        foreach ($directories as $dir) {
            if (! File::isDirectory($dir)) {
                continue;
            }
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
            foreach ($iterator as $f) {
                if ($f->isDir()) {
                    continue;
                }
                $ext = $f->getExtension();
                if ($ext === 'php') {
                    $files[] = $f->getPathname();
                }
            }
        }

        $keys = [];
        $pattern = "/(?:__|trans|@lang|trans_choice)\s*\(\s*(['\"])(.*?)(?<!\\\\)\\1/su";

        foreach ($files as $filePath) {
            $content = File::get($filePath);
            $relPath = str_replace($base.DIRECTORY_SEPARATOR, '', $filePath);

            if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $rawKey = $match[2];
                    $cleanedKey = str_replace(["\\'", '\\"'], ["'", '"'], $rawKey);
                    $cleanedKey = trim($cleanedKey);

                    if ($cleanedKey !== '' && ! str_contains($cleanedKey, '$') && ! str_ends_with($cleanedKey, '.')) {
                        $keys[$cleanedKey][] = $relPath;
                    }
                }
            }
        }

        // Deduplicate occurrences per key
        foreach ($keys as $k => $locs) {
            $keys[$k] = array_values(array_unique($locs));
        }

        return [
            'keys' => $keys,
            'scanned_files_count' => count($files),
        ];
    }

    /**
     * Scan Blade templates for hardcoded Arabic text outside translation helpers.
     *
     * @return list<array{file: string, line: int, text: string}>
     */
    protected function scanBladeForUnwrappedText(): array
    {
        $base = base_path('resources'.DIRECTORY_SEPARATOR.'views');
        if (! File::isDirectory($base)) {
            return [];
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
        $unwrapped = [];

        foreach ($iterator as $f) {
            if ($f->isDir() || ! str_ends_with($f->getFilename(), '.blade.php')) {
                continue;
            }

            $content = File::get($f->getPathname());
            $rel = str_replace(base_path().DIRECTORY_SEPARATOR, '', $f->getPathname());

            // 1. Strip Blade and HTML comments
            $clean = preg_replace('/\{\{--.*?--\}\}/s', '', $content) ?? $content;
            $clean = preg_replace('/<!--.*?-->/s', '', $clean) ?? $clean;

            // 2. Strip scripts and styles
            $clean = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $clean) ?? $clean;
            $clean = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $clean) ?? $clean;

            // 3. Strip wrapped translation calls: __('...'), trans('...'), @lang('...')
            $clean = preg_replace("/(?:__|trans|@lang|trans_choice)\s*\(\s*(['\"]).*?(?<!\\\\)\\1/su", '', $clean) ?? $clean;

            $lines = explode("\n", $clean);
            foreach ($lines as $lineIndex => $line) {
                if (preg_match($this->arabicRegex, $line)) {
                    $trimmed = trim($line);
                    // Filter out comments and SVG paths or non-visual text
                    if ($trimmed !== '' && ! str_starts_with($trimmed, '//') && ! str_starts_with($trimmed, '/*') && ! str_starts_with($trimmed, '*')) {
                        $unwrapped[] = [
                            'file' => $rel,
                            'line' => $lineIndex + 1,
                            'text' => mb_substr($trimmed, 0, 80),
                        ];
                    }
                }
            }
        }

        return $unwrapped;
    }

    /**
     * Load all JSON and PHP translations for the given locale.
     *
     * @return array{json: array<string, string>, groups: array<string, array<string, mixed>>}
     */
    protected function loadTranslations(string $locale): array
    {
        $jsonFile = base_path("lang/{$locale}.json");
        $jsonTrans = [];
        if (File::exists($jsonFile)) {
            $decoded = json_decode(File::get($jsonFile), true);
            if (is_array($decoded)) {
                $jsonTrans = $decoded;
            }
        }

        $groupTrans = [];
        $langDir = base_path("lang/{$locale}");
        if (File::isDirectory($langDir)) {
            foreach (File::files($langDir) as $file) {
                if ($file->getExtension() === 'php') {
                    $group = $file->getBasename('.php');
                    try {
                        $data = require $file->getPathname();
                        if (is_array($data)) {
                            $groupTrans[$group] = $data;
                        }
                    } catch (\Throwable) {
                        // ignore broken lang file
                    }
                }
            }
        }

        return [
            'json' => $jsonTrans,
            'groups' => $groupTrans,
        ];
    }

    /**
     * Audit discovered keys against loaded translations.
     *
     * @param  array{keys: array<string, list<string>>, scanned_files_count: int}  $discovered
     * @param  array{json: array<string, string>, groups: array<string, array<string, mixed>>}  $translations
     * @return array{
     *     missing_keys: array<string, list<string>>,
     *     untranslated_identical: list<string>,
     *     mismatched_placeholders: list<array{key: string, expected_params: list<string>, translation: string}>
     * }
     */
    protected function auditKeys(array $discovered, array $translations, string $locale): array
    {
        $missingKeys = [];
        $untranslatedIdentical = [];
        $mismatchedPlaceholders = [];

        $json = $translations['json'];
        $groups = $translations['groups'];

        foreach ($discovered['keys'] as $key => $locations) {
            $translatedValue = null;

            // Check if key is a group-based key: e.g. "ui.something" or "courses.title"
            if (str_contains($key, '.') && ! preg_match($this->arabicRegex, $key)) {
                $parts = explode('.', $key, 2);
                $group = $parts[0];
                $item = $parts[1];

                if (isset($groups[$group])) {
                    $val = data_get($groups[$group], $item);
                    if (is_string($val)) {
                        $translatedValue = $val;
                    }
                }
            }

            // Check JSON translations
            if ($translatedValue === null && isset($json[$key])) {
                $translatedValue = $json[$key];
            }

            // Check if missing
            if ($translatedValue === null || trim((string) $translatedValue) === '') {
                // If checking English and the key itself is already written in English without Arabic:
                if ($locale === 'en' && ! preg_match($this->arabicRegex, $key) && ! str_contains($key, '.')) {
                    $translatedValue = $key;
                }
                // In Arabic locale, the key itself might be Arabic text
                elseif ($locale === 'ar' && preg_match($this->arabicRegex, $key)) {
                    // Valid native Arabic key
                    $translatedValue = $key;
                } else {
                    $missingKeys[$key] = $locations;

                    continue;
                }
            }

            // Check if identical to Arabic key when checking English
            if ($locale === 'en' && preg_match($this->arabicRegex, $key) && $translatedValue === $key) {
                $untranslatedIdentical[] = $key;
            }

            // Check placeholders
            preg_match_all('/:([a-zA-Z_]+)/', $key, $keyParams);
            preg_match_all('/:([a-zA-Z_]+)/', $translatedValue, $transParams);

            $expected = $keyParams[1];
            $actual = $transParams[1];

            $missingParams = array_diff($expected, $actual);
            if (! empty($missingParams)) {
                $mismatchedPlaceholders[] = [
                    'key' => $key,
                    'expected_params' => array_values($missingParams),
                    'translation' => $translatedValue,
                ];
            }
        }

        return [
            'missing_keys' => $missingKeys,
            'untranslated_identical' => $untranslatedIdentical,
            'mismatched_placeholders' => $mismatchedPlaceholders,
        ];
    }

    /**
     * Auto-fix missing translations by syncing into lang/{locale}.json.
     *
     * @param  array<string, list<string>>  $missingKeys
     */
    protected function applyFix(array $missingKeys, string $locale): int
    {
        $jsonPath = base_path("lang/{$locale}.json");
        $existing = File::exists($jsonPath) ? (json_decode(File::get($jsonPath), true) ?: []) : [];

        $addedCount = 0;
        foreach (array_keys($missingKeys) as $key) {
            if (! isset($existing[$key])) {
                $existing[$key] = $this->generateTranslation($key, $locale);
                $addedCount++;
            }
        }

        ksort($existing);
        $encoded = json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        File::put($jsonPath, $encoded !== false ? $encoded : '{}');

        return $addedCount;
    }

    /**
     * Generate fallback translation for a key.
     */
    protected function generateTranslation(string $key, string $locale): string
    {
        if ($locale === 'ar') {
            return $key;
        }

        // Clean up formatting
        return trim($key);
    }
}
