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

        return redirect()->back();
    }
}
