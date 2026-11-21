<?php

namespace App\Http\Controllers;

use App\Services\Localization\LocalizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
    public function __construct(
        protected LocalizationService $localizationService
    ) {}

    /**
     * Switch application locale.
     */
    public function switch(Request $request, string $locale): RedirectResponse|JsonResponse
    {
        if (! $this->localizationService->isSupported($locale)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Unsupported language.'),
                    'errors' => ['supported_locales' => array_keys($this->localizationService->getSupportedLocales())],
                ], 422);
            }

            return redirect()->back()->with('error', __('Unsupported language.'));
        }

        /** @var mixed $user */
        $user = Auth::guard('admin')->user()
            ?? $request->user('admin')
            ?? Auth::guard('web')->user()
            ?? $request->user();

        $activeLocale = $this->localizationService->setLocale($locale, $user);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Language switched successfully.'),
                'data' => [
                    'locale' => $activeLocale,
                    'direction' => $this->localizationService->getDirection($activeLocale),
                    'is_rtl' => $this->localizationService->isRtl($activeLocale),
                ],
            ]);
        }

        // 1. Check for explicit redirect target query parameter
        $redirectUrl = $request->query('redirect') ?? $request->input('redirect');
        if (! empty($redirectUrl) && is_string($redirectUrl)) {
            $parsed = parse_url($redirectUrl);
            $appHost = strtolower($request->getHost());
            $targetHost = isset($parsed['host']) ? strtolower($parsed['host']) : null;

            // Safe internal redirect: relative path OR same host
            if ($targetHost === null || $targetHost === $appHost) {
                $targetPath = ($parsed['path'] ?? '/');
                if (isset($parsed['query'])) {
                    $targetPath .= '?'.$parsed['query'];
                }
                if (isset($parsed['fragment'])) {
                    $targetPath .= '#'.$parsed['fragment'];
                }

                return redirect()->to($targetPath)->header('X-Robots-Tag', 'noindex, nofollow');
            }
        }

        // 2. Safe referer redirect: ONLY redirect back if referer is from the exact same application host
        $referer = $request->headers->get('referer');
        if (! empty($referer) && is_string($referer)) {
            $refererHost = parse_url($referer, PHP_URL_HOST);
            if ($refererHost && strtolower($refererHost) === strtolower($request->getHost())) {
                return redirect()->back()->header('X-Robots-Tag', 'noindex, nofollow');
            }
        }

        // 3. Fallback: Always redirect to the platform root (never back to search engines or external referers)
        return redirect()->to('/')->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
