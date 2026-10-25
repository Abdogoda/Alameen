<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user === null || ! $user->isAdmin()) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiResponse::error(
                    __('messages.unauthorized') !== 'messages.unauthorized'
                        ? __('messages.unauthorized')
                        : 'Unauthorized access. Administrator privileges required.',
                    403,
                );
            }

            abort(403, 'Unauthorized access. Administrator privileges required.');
        }

        return $next($request);
    }
}
