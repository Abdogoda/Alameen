<?php

declare(strict_types=1);

use App\Services\Cms\SettingsService;

if (! function_exists('platform_setting')) {
    /**
     * Retrieve a dynamic platform setting by key.
     */
    function platform_setting(string $key, ?string $default = null): ?string
    {
        try {
            return app(SettingsService::class)->get($key, $default);
        } catch (Throwable) {
            return $default;
        }
    }
}

if (! function_exists('platform_name')) {
    /**
     * Retrieve the dynamic official platform brand name.
     */
    function platform_name(?string $default = null): string
    {
        try {
            return app(SettingsService::class)->getSiteName() ?: ($default ?? config('app.name', 'منصة الأمين'));
        } catch (Throwable) {
            return $default ?? config('app.name', 'منصة الأمين');
        }
    }
}

if (! function_exists('platform_logo')) {
    /**
     * Retrieve the sanitized, normalized platform logo URL.
     */
    function platform_logo(): string
    {
        try {
            return app(SettingsService::class)->getLogoUrl();
        } catch (Throwable) {
            return '/assets/images/logo.png';
        }
    }
}

if (! function_exists('platform_logo_white')) {
    /**
     * Retrieve the white version of platform logo for dark theme & dark backgrounds.
     */
    function platform_logo_white(): string
    {
        try {
            return app(SettingsService::class)->getLogoWhiteUrl();
        } catch (Throwable) {
            return '/assets/images/logo-white.png';
        }
    }
}

if (! function_exists('platform_footer_logo')) {
    /**
     * Retrieve the footer logo URL (defaults to white logo on dark background).
     */
    function platform_footer_logo(): string
    {
        try {
            return app(SettingsService::class)->getFooterLogoUrl();
        } catch (Throwable) {
            return '/assets/images/logo-white.png';
        }
    }
}

if (! function_exists('platform_auth_login_image')) {
    /**
     * Retrieve the auth artwork image URL (for login, register, reset password).
     */
    function platform_auth_login_image(?string $default = null): string
    {
        try {
            return app(SettingsService::class)->getAuthLoginImageUrl() ?: ($default ?? '/assets/images/alameen-images/13.jpeg');
        } catch (Throwable) {
            return $default ?? '/assets/images/alameen-images/13.jpeg';
        }
    }
}

if (! function_exists('platform_tagline')) {
    /**
     * Retrieve the official platform tagline / description.
     */
    function platform_tagline(?string $default = null): string
    {
        try {
            return app(SettingsService::class)->getSiteTagline() ?: ($default ?? 'المنصة الأكاديمية والسريرية الأولى لتدريب وتأهيل أخصائي النطق والتخاطب في الوطن العربي');
        } catch (Throwable) {
            return $default ?? 'المنصة الأكاديمية والسريرية الأولى لتدريب وتأهيل أخصائي النطق والتخاطب في الوطن العربي';
        }
    }
}

if (! function_exists('platform_currency')) {
    /**
     * Retrieve the active currency symbol (e.g. ج.م).
     */
    function platform_currency(?string $default = null): string
    {
        try {
            return app(SettingsService::class)->getCurrencySymbol() ?: ($default ?? 'ج.م');
        } catch (Throwable) {
            return $default ?? 'ج.م';
        }
    }
}

if (! function_exists('platform_currency_name')) {
    /**
     * Retrieve the active currency name (e.g. جنيه مصري).
     */
    function platform_currency_name(?string $default = null): string
    {
        try {
            return app(SettingsService::class)->getCurrencyName() ?: ($default ?? 'جنيه مصري');
        } catch (Throwable) {
            return $default ?? 'جنيه مصري';
        }
    }
}
