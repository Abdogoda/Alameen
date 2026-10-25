<?php

namespace App\Services\Localization;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LocalizationService
{
    /**
     * Get all supported locales.
     *
     * @return array<string, array{name: string, native: string, dir: string, flag: string, code: string, regional: string}>
     */
    public function getSupportedLocales(): array
    {
        /** @var array<string, array{name: string, native: string, dir: string, flag: string, code: string, regional: string}> $locales */
        $locales = config('localization.locales', [
            'en' => ['name' => 'English', 'native' => 'English', 'dir' => 'ltr', 'flag' => '🇺🇸', 'code' => 'en', 'regional' => 'en_US'],
            'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'dir' => 'rtl', 'flag' => '🇸🇦', 'code' => 'ar', 'regional' => 'ar_SA'],
        ]);

        return $locales;
    }

    /**
     * Check if a given locale is supported.
     */
    public function isSupported(string $locale): bool
    {
        return array_key_exists(strtolower($locale), $this->getSupportedLocales());
    }

    /**
     * Get the current active locale.
     */
    public function getCurrentLocale(): string
    {
        return App::getLocale();
    }

    /**
     * Get text direction for a locale ('rtl' or 'ltr').
     */
    public function getDirection(?string $locale = null): string
    {
        $locale = $locale ? strtolower($locale) : $this->getCurrentLocale();
        $locales = $this->getSupportedLocales();

        return $locales[$locale]['dir'] ?? 'ltr';
    }

    /**
     * Check if the current (or given) locale is RTL.
     */
    public function isRtl(?string $locale = null): bool
    {
        return $this->getDirection($locale) === 'rtl';
    }

    /**
     * Set the application locale and persist it across session, cookie, and user profile.
     */
    public function setLocale(string $locale, mixed $user = null): string
    {
        $normalized = strtolower($locale);

        if (! $this->isSupported($normalized)) {
            $normalized = (string) config('localization.fallback', 'en');
        }

        App::setLocale($normalized);
        Carbon::setLocale($normalized);

        if (Session::isStarted()) {
            Session::put((string) config('localization.session_key', 'locale'), $normalized);
        }

        Cookie::queue(
            (string) config('localization.cookie_name', 'locale'),
            $normalized,
            (int) config('localization.cookie_lifetime', 525600)
        );

        if ($user !== null && method_exists($user, 'update')) {
            $user->update(['locale' => $normalized]);
        }

        return $normalized;
    }

    /**
     * Detect the appropriate locale from an incoming request.
     * In the API, language is determined exclusively via the Accept-Language header (not in URL/query).
     */
    public function detectLocale(Request $request): string
    {
        // API Requests: Strictly determined by Accept-Language header
        if ($request->is('api/*') || $request->expectsJson()) {
            $acceptLanguage = $request->header('Accept-Language');

            if (is_string($acceptLanguage) && ! empty($acceptLanguage)) {
                $preferred = $request->getPreferredLanguage(array_keys($this->getSupportedLocales()));

                if (is_string($preferred) && $this->isSupported($preferred)) {
                    return strtolower($preferred);
                }
            }

            // Fallback to authenticated user's saved preference
            /** @var mixed $user */
            $user = $request->user('admin') ?? $request->user();
            if ($user !== null && ! empty($user->locale) && $this->isSupported($user->locale)) {
                return strtolower($user->locale);
            }

            return (string) config('localization.default', 'en');
        }

        // Web Requests:
        // 1. Explicit query parameter (e.g. ?lang=ar or ?locale=ar)
        $queryLocale = $request->query('lang') ?? $request->query('locale');
        if (is_string($queryLocale) && $this->isSupported($queryLocale)) {
            return strtolower($queryLocale);
        }

        // 2. Custom header
        $headerLocale = $request->header('X-Locale') ?? $request->header('X-Language');
        if (is_string($headerLocale) && $this->isSupported($headerLocale)) {
            return strtolower($headerLocale);
        }

        // 3. Authenticated user preference
        /** @var mixed $user */
        $user = $request->user('admin') ?? $request->user();
        if ($user !== null && ! empty($user->locale) && $this->isSupported($user->locale)) {
            return strtolower($user->locale);
        }

        // 4. Session
        if ($request->hasSession()) {
            $sessionLocale = $request->session()->get((string) config('localization.session_key', 'locale'));
            if (is_string($sessionLocale) && $this->isSupported($sessionLocale)) {
                return strtolower($sessionLocale);
            }
        }

        // 5. Cookie
        $cookieLocale = $request->cookie((string) config('localization.cookie_name', 'locale'));
        if (is_string($cookieLocale) && $this->isSupported($cookieLocale)) {
            return strtolower($cookieLocale);
        }

        // 6. Browser Accept-Language header
        $preferred = $request->getPreferredLanguage(array_keys($this->getSupportedLocales()));
        if (is_string($preferred) && $this->isSupported($preferred)) {
            return strtolower($preferred);
        }

        // 7. Default configured locale
        return (string) config('localization.default', 'en');
    }
}
