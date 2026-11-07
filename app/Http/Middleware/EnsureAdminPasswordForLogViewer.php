<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPasswordForLogViewer
{
    /**
     * Handle an incoming request.
     *
     * Ensure the authenticated administrator has confirmed their password
     * before accessing the Laravel Log Viewer dashboard or its API endpoints.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth('admin')->user()
            ?? ($request->user('admin') instanceof Admin ? $request->user('admin') : null)
            ?? ($request->user() instanceof Admin ? $request->user() : null);

        if (! $admin instanceof Admin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('messages.unauthorized') !== 'messages.unauthorized'
                        ? __('messages.unauthorized')
                        : 'Unauthorized access. Administrator privileges required.',
                ], 403);
            }

            return redirect()->guest(route('admin.login'));
        }

        $confirmedAt = $request->hasSession()
            ? $request->session()->get('admin_log_viewer_confirmed_at')
            : null;

        $timeout = (int) config('log-viewer.password_timeout', 1800);

        $isConfirmed = is_numeric($confirmedAt) && (time() - (int) $confirmedAt) < $timeout;

        if (! $isConfirmed) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('يرجى تأكيد كلمة مرور المسؤول أولاً للوصول إلى سجلات النظام.'),
                    'locked' => true,
                ], 423);
            }

            // Save the attempted URL so we can redirect the admin back upon confirmation
            if ($request->hasSession() && ! $request->session()->has('url.intended')) {
                $request->session()->put('url.intended', $request->fullUrl());
            }

            return redirect()->guest(route('admin.log-viewer.confirm'));
        }

        return $next($request);
    }
}
