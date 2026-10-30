<?php

declare(strict_types=1);
use Composer\Semver\Semver;

/**
 * Pre-commit validation script to prevent locking dependencies incompatible
 * with the target PHP platform (e.g. CI running PHP 8.3 while local machine runs PHP 8.4+).
 */
$root = dirname(__DIR__);
$composerJsonPath = $root.DIRECTORY_SEPARATOR.'composer.json';
$composerLockPath = $root.DIRECTORY_SEPARATOR.'composer.lock';

if (! file_exists($composerJsonPath)) {
    fwrite(STDERR, "Error: composer.json not found at {$composerJsonPath}\n");
    exit(1);
}

$composerJson = json_decode(file_get_contents($composerJsonPath), true);
if (! is_array($composerJson)) {
    fwrite(STDERR, "Error: Failed to parse composer.json\n");
    exit(1);
}

// 1. Verify config.platform.php is explicitly configured
$platformPhp = $composerJson['config']['platform']['php'] ?? null;

if (empty($platformPhp)) {
    fwrite(STDERR, "\n[ERROR] Missing 'config.platform.php' in composer.json!\n");
    fwrite(STDERR, "Without this, Composer will resolve dependencies using your local PHP runtime,\n");
    fwrite(STDERR, "which can pull in packages requiring newer PHP versions that break CI.\n\n");
    fwrite(STDERR, "Add this to composer.json under \"config\":\n");
    fwrite(STDERR, "    \"platform\": {\n");
    fwrite(STDERR, "        \"php\": \"8.3.0\"\n");
    fwrite(STDERR, "    }\n\n");
    exit(1);
}

// Normalize e.g. "8.3" to "8.3.0" for semver comparison
$normalizedTarget = preg_match('/^\d+\.\d+$/', $platformPhp) ? "{$platformPhp}.0" : $platformPhp;

echo "Checking dependencies against target PHP {$platformPhp}...\n";

// 2. If composer.lock exists, verify every locked package satisfies target PHP
if (! file_exists($composerLockPath)) {
    echo "Warning: composer.lock not found. Skipping lockfile check.\n";
    exit(0);
}

$composerLock = json_decode(file_get_contents($composerLockPath), true);
if (! is_array($composerLock)) {
    fwrite(STDERR, "Error: Failed to parse composer.lock\n");
    exit(1);
}

// Load Semver if available
if (file_exists($root.'/vendor/autoload.php')) {
    require_once $root.'/vendor/autoload.php';
}

$packages = array_merge(
    $composerLock['packages'] ?? [],
    $composerLock['packages-dev'] ?? []
);

$incompatible = [];

foreach ($packages as $pkg) {
    $name = $pkg['name'] ?? 'unknown';
    $version = $pkg['version'] ?? 'unknown';
    $phpReq = $pkg['require']['php'] ?? null;

    if ($phpReq === null) {
        continue;
    }

    $compatible = true;
    if (class_exists(Semver::class)) {
        try {
            $compatible = Semver::satisfies($normalizedTarget, $phpReq);
        } catch (Throwable $e) {
            // If constraint parsing fails, fallback
            $compatible = false;
        }
    } else {
        // Fallback simple check for >=X.Y or ^X.Y
        if (preg_match('/(?:>=|\^)\s*(\d+\.\d+(?:\.\d+)?)/', $phpReq, $m)) {
            $minReq = $m[1];
            if (version_compare($normalizedTarget, $minReq, '<')) {
                $compatible = false;
            }
        }
    }

    if (! $compatible) {
        $incompatible[] = [
            'name' => $name,
            'version' => $version,
            'php' => $phpReq,
        ];
    }
}

if (! empty($incompatible)) {
    fwrite(STDERR, "\n============================================================\n");
    fwrite(STDERR, "[ERROR] Incompatible packages detected in composer.lock!\n");
    fwrite(STDERR, "Target platform PHP is: {$platformPhp}\n");
    fwrite(STDERR, "The following packages require a newer PHP version than {$platformPhp}:\n\n");

    foreach ($incompatible as $bad) {
        fwrite(STDERR, sprintf("  - %s (%s) requires PHP '%s'\n", $bad['name'], $bad['version'], $bad['php']));
    }

    fwrite(STDERR, "\nThis will cause GitHub Actions CI and production to FAIL on composer install.\n");
    fwrite(STDERR, "To fix this:\n");
    fwrite(STDERR, "  1. Make sure composer.json has \"config\": { \"platform\": { \"php\": \"{$platformPhp}\" } }\n");
    fwrite(STDERR, "  2. Run: composer update\n");
    fwrite(STDERR, "============================================================\n\n");
    exit(1);
}

echo "✓ All packages in composer.lock satisfy target PHP {$platformPhp}.\n";

// 3. Verify composer validate
$validateOutput = [];
$validateCode = 0;
exec('composer validate --strict --no-check-all 2>&1', $validateOutput, $validateCode);

if ($validateCode !== 0) {
    fwrite(STDERR, "\n[ERROR] 'composer validate --strict' failed:\n");
    fwrite(STDERR, implode("\n", $validateOutput)."\n\n");
    exit(1);
}

echo "✓ composer.json and composer.lock are valid and in sync.\n";
exit(0);
