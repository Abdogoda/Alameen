<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCrossOriginHeaders
{
    /**
     * Handle an incoming request and set headers for cross-origin popup compatibility (e.g. Google Firebase Auth).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Allow OAuth popups (Firebase/Google Auth) to maintain opener communication
        // without triggering "Cross-Origin-Opener-Policy policy would block the window.closed call"
        if (! $response->headers->has('Cross-Origin-Opener-Policy')) {
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
        }

        return $response;
    }
}
