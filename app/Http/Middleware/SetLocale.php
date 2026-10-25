<?php

namespace App\Http\Middleware;

use App\Services\Localization\LocalizationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function __construct(
        protected LocalizationService $localizationService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->localizationService->detectLocale($request);

        App::setLocale($locale);
        Carbon::setLocale($locale);

        $direction = $this->localizationService->getDirection($locale);
        $isRtl = $this->localizationService->isRtl($locale);
        $supportedLocales = $this->localizationService->getSupportedLocales();

        View::share('currentLocale', $locale);
        View::share('currentDirection', $direction);
        View::share('isRtl', $isRtl);
        View::share('supportedLocales', $supportedLocales);

        $response = $next($request);

        $response->headers->set('Content-Language', $locale);

        return $response;
    }
}
