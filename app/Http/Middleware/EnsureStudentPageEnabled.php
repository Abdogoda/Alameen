<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Cms\SettingsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentPageEnabled
{
    public function __construct(
        protected SettingsService $settingsService,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $page): Response
    {
        if (! $this->settingsService->isPageEnabled($page)) {
            return redirect()->route('courses.index');
        }

        return $next($request);
    }
}

