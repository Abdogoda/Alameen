<?php

declare(strict_types=1);

namespace App\Services\Cms;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public const CACHE_KEY = 'alameen_global_settings';

    /**
     * Retrieve a localized setting value by key with cache.
     */
    public function get(string $key, ?string $default = null): ?string
    {
        $all = $this->allCached();

        if (! isset($all[$key])) {
            return $default;
        }

        $locale = app()->getLocale();
        $record = $all[$key];

        if ($locale === 'en' && ! empty($record['value_en'])) {
            return $record['value_en'];
        }

        return $record['value_ar'] ?? $default;
    }

    /**
     * Retrieve raw bilingual record for admin dashboard form fields.
     *
     * @return array{value_ar: string|null, value_en: string|null, group: string}|null
     */
    public function getRaw(string $key): ?array
    {
        $all = $this->allCached();

        return $all[$key] ?? null;
    }

    /**
     * Retrieve all settings in a specific group.
     *
     * @return array<string, string|null>
     */
    public function getGroup(string $group): array
    {
        $all = $this->allCached();
        $groupSettings = [];

        foreach ($all as $key => $item) {
            if ($item['group'] === $group) {
                $groupSettings[$key] = $this->get($key);
            }
        }

        return $groupSettings;
    }

    /**
     * Retrieve the localized official platform name.
     */
    public function getSiteName(): string
    {
        return $this->get('site_name', config('app.name', 'منصة الأمين')) ?: config('app.name', 'منصة الأمين');
    }

    /**
     * Retrieve the localized platform tagline.
     */
    public function getSiteTagline(): string
    {
        return $this->get('site_tagline', 'المنصة الأكاديمية والسريرية الأولى لتدريب وتأهيل أخصائي النطق والتخاطب في الوطن العربي')
            ?: 'المنصة الأكاديمية والسريرية الأولى لتدريب وتأهيل أخصائي النطق والتخاطب في الوطن العربي';
    }

    /**
     * Retrieve the sanitized, valid platform logo URL.
     */
    public function getLogoUrl(): string
    {
        $logo = $this->get('site_logo', '/assets/images/logo.png');
        if (empty($logo)) {
            return '/assets/images/logo.png';
        }

        $logo = trim($logo);
        // Strip trailing slash if present (e.g. "assets/images/logo.png/")
        $logo = rtrim($logo, '/');

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://') || str_starts_with($logo, 'data:')) {
            return $logo;
        }

        if (! str_starts_with($logo, '/')) {
            $logo = '/' . $logo;
        }

        return $logo;
    }

    /**
     * Retrieve the dynamic currency symbol (e.g. ج.م).
     */
    public function getCurrencySymbol(): string
    {
        return $this->get('currency_symbol', 'ج.م') ?: 'ج.م';
    }

    /**
     * Retrieve the dynamic currency name (e.g. جنيه مصري).
     */
    public function getCurrencyName(): string
    {
        return $this->get('currency_name', 'جنيه مصري') ?: 'جنيه مصري';
    }

    /**
     * Update or create a dynamic setting and bust the cache.
     */
    public function set(string $key, ?string $valueAr, ?string $valueEn = null, string $group = 'general', bool $isSecret = false): Setting
    {
        // Sanitize logo input if entered with trailing slash or without leading slash
        if ($key === 'site_logo' && ! empty($valueAr)) {
            $cleanLogo = rtrim(trim($valueAr), '/');
            if (! str_starts_with($cleanLogo, 'http://') && ! str_starts_with($cleanLogo, 'https://') && ! str_starts_with($cleanLogo, 'data:') && ! str_starts_with($cleanLogo, '/')) {
                $cleanLogo = '/' . $cleanLogo;
            }
            $valueAr = $cleanLogo;
            $valueEn = $cleanLogo;
        }

        $setting = Setting::updateOrCreate(
            ['key' => $key],
            [
                'value_ar' => $valueAr,
                'value_en' => $valueEn,
                'group' => $group,
                'is_secret' => $isSecret,
            ]
        );

        $this->flushCache();

        return $setting;
    }

    /**
     * Retrieve all settings cached.
     *
     * @return array<string, array{value_ar: string|null, value_en: string|null, group: string}>
     */
    public function allCached(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return Setting::all()->keyBy('key')->map(fn (Setting $s) => [
                'value_ar' => $s->value_ar,
                'value_en' => $s->value_en,
                'group' => $s->group,
            ])->toArray();
        });
    }

    /**
     * Flush settings cache.
     */
    public function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
