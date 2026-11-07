<?php

namespace App\Services\Localization;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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
            'en' => ['name' => 'English', 'native' => 'English', 'dir' => 'ltr', 'flag' => '🇺🇸', 'flag_svg' => '/assets/images/flags/us.svg', 'code' => 'en', 'regional' => 'en_US'],
            'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'dir' => 'rtl', 'flag' => '🇪🇬', 'flag_svg' => '/assets/images/flags/eg.svg', 'code' => 'ar', 'regional' => 'ar_EG'],
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
            $normalized = (string) config('localization.fallback', 'ar');
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

        if ($user === null) {
            $user = Auth::guard('admin')->user()
                ?? request()?->user('admin')
                ?? Auth::guard('web')->user()
                ?? request()?->user();
        }

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
        // 1. Explicit query parameter (e.g. ?lang=ar or ?locale=ar)
        $queryLocale = $request->query('lang') ?? $request->query('locale');
        if (is_string($queryLocale) && $this->isSupported($queryLocale)) {
            $normalized = strtolower($queryLocale);
            if ($request->hasSession()) {
                $request->session()->put((string) config('localization.session_key', 'locale'), $normalized);
            }

            return $normalized;
        }

        // 2. Custom header (X-Locale or X-Language)
        $headerLocale = $request->header('X-Locale') ?? $request->header('X-Language');
        if (is_string($headerLocale) && $this->isSupported($headerLocale)) {
            return strtolower($headerLocale);
        }

        // 3. Pure API requests (strictly under api/* without session)
        if ($request->is('api/*')) {
            $acceptLanguage = $request->header('Accept-Language');

            if (is_string($acceptLanguage) && ! empty($acceptLanguage)) {
                $preferred = $request->getPreferredLanguage(array_keys($this->getSupportedLocales()));

                if (is_string($preferred) && $this->isSupported($preferred)) {
                    return strtolower($preferred);
                }
            }

            /** @var mixed $user */
            $user = Auth::guard('admin')->user() ?? $request->user('admin') ?? Auth::guard('web')->user() ?? $request->user();
            if ($user !== null && ! empty($user->locale) && $this->isSupported($user->locale)) {
                return strtolower($user->locale);
            }

            return (string) config('localization.default', 'ar');
        }

        // Web Requests (including AJAX/Fetch requests to web auth routes):
        // 4. Active Session (Explicit language chosen by user in active session)
        if ($request->hasSession()) {
            $sessionLocale = $request->session()->get((string) config('localization.session_key', 'locale'));
            if (is_string($sessionLocale) && $this->isSupported($sessionLocale)) {
                $normalized = strtolower($sessionLocale);

                // Auto-sync active session preference to authenticated user if different
                /** @var mixed $user */
                $user = Auth::guard('admin')->user() ?? $request->user('admin') ?? Auth::guard('web')->user() ?? $request->user();
                if ($user !== null && method_exists($user, 'update') && $user->locale !== $normalized) {
                    $user->update(['locale' => $normalized]);
                }

                return $normalized;
            }
        }

        // 5. Authenticated user preference (when no session preference is set)
        /** @var mixed $user */
        $user = Auth::guard('admin')->user() ?? $request->user('admin') ?? Auth::guard('web')->user() ?? $request->user();
        if ($user !== null && ! empty($user->locale) && $this->isSupported($user->locale)) {
            $userLocale = strtolower($user->locale);
            if ($request->hasSession()) {
                $request->session()->put((string) config('localization.session_key', 'locale'), $userLocale);
            }

            return $userLocale;
        }

        // 6. Remembered Cookie
        $cookieLocale = $request->cookie((string) config('localization.cookie_name', 'locale'));
        if (is_string($cookieLocale) && $this->isSupported($cookieLocale)) {
            $normalized = strtolower($cookieLocale);
            if ($request->hasSession()) {
                $request->session()->put((string) config('localization.session_key', 'locale'), $normalized);
            }

            return $normalized;
        }

        // 7. Default configured locale (Primary default: Arabic)
        return (string) config('localization.default', 'ar');
    }
}
