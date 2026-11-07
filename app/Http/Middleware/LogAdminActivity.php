<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Services\Audit\AdminActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LogAdminActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $request->attributes->set('_activity_start_time', $startTime);

        try {
            $response = $next($request);

            // In local/testing or if fast-terminating, ensure the request gets logged
            if (app()->runningUnitTests()) {
                $this->logActivityOnce($request, $response);
            }

            return $response;
        } catch (Throwable $e) {
            // Log catastrophic failures or uncaught 500 exceptions
            try {
                $failedResponse = response('Internal Server Error', 500);
                $this->logActivityOnce($request, $failedResponse);
            } catch (Throwable) {
                // Ignore secondary logging exception
            }

            throw $e;
        }
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        $this->logActivityOnce($request, $response);
    }

    /**
     * Record the activity log once per request lifecycle.
     */
    protected function logActivityOnce(Request $request, Response $response): void
    {
        if ($request->attributes->get('_admin_activity_logged')) {
            return;
        }

        $request->attributes->set('_admin_activity_logged', true);

        // Only log for requests on the admin portal
        if (! $this->shouldLogRequest($request)) {
            return;
        }

        $startTime = $request->attributes->get('_activity_start_time');
        AdminActivityLogger::logRequest($request, $response, null, $startTime);
    }

    /**
     * Determine if the request should be recorded.
     */
    protected function shouldLogRequest(Request $request): bool
    {
        // 1. Is it an admin route or /admin path?
        $isAdminRoute = $request->is('admin*')
            || str_starts_with((string) $request->route()?->getName(), 'admin.');

        if (! $isAdminRoute) {
            return false;
        }

        // 2. Ignore pure static asset paths
        if ($request->is('admin/assets/*', 'assets/*', 'build/*', 'flux/*')) {
            return false;
        }

        return true;
    }
}

