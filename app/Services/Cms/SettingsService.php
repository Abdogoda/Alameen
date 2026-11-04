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
        return $this->get('site_name', 'مركز الأمين') ?: 'مركز الأمين';
    }

    /**
     * Retrieve the localized platform tagline.
     */
    public function getSiteTagline(): string
    {
        return $this->get('site_tagline', 'منصة مركز الامين , التخاطب الشامل مع هنا المكاوي , دورات تخاطب شاملة , فيديوهات وتطبيقات عملية')
            ?: 'منصة مركز الامين , التخاطب الشامل مع هنا المكاوي , دورات تخاطب شاملة , فيديوهات وتطبيقات عملية';
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
            $logo = '/'.$logo;
        }

        return $logo;
    }

    /**
     * Retrieve the white version of platform logo for dark theme and dark backgrounds.
     */
    public function getLogoWhiteUrl(): string
    {
        $logo = $this->get('site_logo_white', '/assets/images/logo-white.png')
            ?: $this->get('site_logo_dark', '/assets/images/logo-white.png');

        if (empty($logo)) {
            return '/assets/images/logo-white.png';
        }

        $logo = rtrim(trim($logo), '/');

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://') || str_starts_with($logo, 'data:')) {
            return $logo;
        }

        if (! str_starts_with($logo, '/')) {
            $logo = '/'.$logo;
        }

        return $logo;
    }

    /**
     * Retrieve the footer logo URL (defaults to white logo for dark footer background).
     */
    public function getFooterLogoUrl(): string
    {
        $logo = $this->get('footer_logo', '/assets/images/logo-white.png');
        if (empty($logo)) {
            return $this->getLogoWhiteUrl();
        }

        $logo = rtrim(trim($logo), '/');

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://') || str_starts_with($logo, 'data:')) {
            return $logo;
        }

        if (! str_starts_with($logo, '/')) {
            $logo = '/'.$logo;
        }

        return $logo;
    }

    /**
     * Retrieve the auth artwork image URL (for login, register, reset password pages).
     */
    public function getAuthLoginImageUrl(): string
    {
        $img = $this->get('auth_login_image', '/assets/images/alameen-images/13.jpeg');
        if (empty($img)) {
            return '/assets/images/alameen-images/13.jpeg';
        }

        $img = rtrim(trim($img), '/');

        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://') || str_starts_with($img, 'data:')) {
            return $img;
        }

        if (! str_starts_with($img, '/')) {
            $img = '/'.$img;
        }

        return $img;
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
     * Theme & Palette Colors
     */
    public function getThemePrimaryColor(): string
    {
        return $this->get('theme_primary_color', '#012383') ?: '#012383';
    }

    public function getThemeSecondaryColor(): string
    {
        return $this->get('theme_secondary_color', '#0B2A8F') ?: '#0B2A8F';
    }

    public function getThemeAccentColor(): string
    {
        return $this->get('theme_accent_color', '#10B77F') ?: '#10B77F';
    }

    public function getThemeGoldColor(): string
    {
        return $this->get('theme_gold_color', '#C59B27') ?: '#C59B27';
    }

    public function getThemeFooterBg(): string
    {
        return $this->get('theme_footer_bg', '#031338') ?: '#031338';
    }

    /**
     * Contact & Location Helpers
     */
    public function getContactEmail(): string
    {
        return $this->get('contact_email', 'hm2991479@gmail.com') ?: 'hm2991479@gmail.com';
    }

    public function getContactPhone(): string
    {
        return $this->get('contact_phone', '01005123784') ?: '01005123784';
    }

    public function getWhatsAppUrl(): string
    {
        $raw = $this->get('social_whatsapp', 'https://wa.me/201005123784') ?: 'https://wa.me/201005123784';
        if (! str_starts_with($raw, 'http')) {
            $digits = preg_replace('/\D+/', '', $raw);
            return 'https://wa.me/'.(str_starts_with($digits, '2') ? $digits : '2'.$digits);
        }
        return $raw;
    }

    public function getPersonalFacebook(): ?string
    {
        return $this->get('social_facebook_personal', 'https://www.facebook.com/share/1BY6bGSNeR/?mibextid=wwXIfr');
    }

    public function getCenterFacebook(): ?string
    {
        return $this->get('social_facebook_center', 'https://www.facebook.com/share/1CChF5RHCT/?mibextid=wwXIfr')
            ?: $this->get('social_facebook');
    }

    public function getPersonalTikTok(): ?string
    {
        return $this->get('social_tiktok_personal', 'https://www.tiktok.com/@slt.hanamekawy?_r=1&_t=ZS-97eMA66BpIa');
    }

    public function getCenterTikTok(): ?string
    {
        return $this->get('social_tiktok_center', 'https://www.tiktok.com/@alameen_center?_r=1&_t=ZS-9AyFeNMOY5N')
            ?: $this->get('social_tiktok');
    }

    public function getWorkingHours(): string
    {
        return $this->get('working_hours', 'السبت - الخميس من 12 - 9 م') ?: 'السبت - الخميس من 12 - 9 م';
    }

    public function getHeadquartersAddress(): string
    {
        return $this->get('headquarters_address', 'الزقازيق - شارع الغشام') ?: 'الزقازيق - شارع الغشام';
    }

    public function getFooterBadge(): string
    {
        return $this->get('footer_badge', 'مركز الامين للتدريب والتاهيل التخاطبي') ?: 'مركز الامين للتدريب والتاهيل التخاطبي';
    }

    public function getCertificateBg(): string
    {
        return $this->get('certificate_custom_bg', '/assets/images/alameen-images/certificate.png') ?: '/assets/images/alameen-images/certificate.png';
    }

    /**
     * Check whether a specific student portal page is enabled by admin.
     * Default state enables ONLY 'courses' (and 'settings' for account management)
     * while keeping dashboard, tasks, grades, and notifications/support hidden as requested.
     */
    public function isPageEnabled(string $pageKey): bool
    {
        $normalizedKey = str_starts_with($pageKey, 'page_') ? $pageKey : 'page_'.$pageKey.'_enabled';

        $default = match ($pageKey) {
            'courses', 'page_courses_enabled' => '1',
            'settings', 'page_settings_enabled' => '1',
            default => '0',
        };

        $val = $this->get($normalizedKey, $default);

        return $val === '1' || $val === 'true' || $val === true;
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
                $cleanLogo = '/'.$cleanLogo;
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
