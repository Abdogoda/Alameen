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
     * Update or create a dynamic setting and bust the cache.
     */
    public function set(string $key, ?string $valueAr, ?string $valueEn = null, string $group = 'general', bool $isSecret = false): Setting
    {
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
