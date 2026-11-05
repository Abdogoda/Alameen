<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentIsApproved
{
    /**
     * Ensure the authenticated student account is approved and active.
     * Pending and suspended accounts cannot access any student-authenticated pages.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user() ?? $request->user('web');

        if ($user && method_exists($user, 'isActive') && ! $user->isActive()) {
            $isPending = $user->status === 'pending';

            if ($request->hasSession()) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            if ($request->is('api/*') && method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
                $user->currentAccessToken()->delete();
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'status' => $user->status,
                    'message' => $isPending
                        ? __('حسابك قيد المراجعة وبانتظار موافقة إدارة المنصة.')
                        : __('تم إيقاف هذا الحساب. يرجى التواصل مع إدارة المنصة.'),
                ], 403);
            }

            return redirect()->route('login')->withErrors([
                'email' => $isPending
                    ? __('حسابك قيد المراجعة وبانتظار موافقة إدارة المنصة. ستتمكن من تسجيل الدخول فور اعتماد الحساب.')
                    : __('تم إيقاف هذا الحساب. يرجى التواصل مع إدارة المنصة.'),
            ]);
        }

        return $next($request);
    }
}
