<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminHasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$permissions  Comma or variadic separated permissions. Admin needs at least ONE.
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $admin = auth('admin')->user();

        if (! ($admin instanceof Admin)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized access. Administrator privileges required.',
                ], 401);
            }

            return redirect()->guest(route('admin.login'));
        }

        if ($admin->isSuperAdmin()) {
            return $next($request);
        }

        // Expand any comma-separated permissions like "courses.view,courses.manage"
        $expandedPermissions = [];
        foreach ($permissions as $perm) {
            foreach (explode(',', $perm) as $p) {
                $trimmed = trim($p);
                if ($trimmed !== '') {
                    $expandedPermissions[] = $trimmed;
                }
            }
        }

        if (! empty($expandedPermissions) && ! $admin->hasAnyPermission($expandedPermissions)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('messages.forbidden') !== 'messages.forbidden'
                        ? __('messages.forbidden')
                        : 'You do not have the required permission to perform this action.',
                    'required_permissions' => $expandedPermissions,
                ], 403);
            }

            abort(403, __('غير مصرح لك بالوصول إلى هذه الصفحة أو تنفيذ هذا الإجراء.'));
        }

        return $next($request);
    }
}
