<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class AdminLogViewerSecurityController extends Controller
{
    /**
     * Show the password confirmation form for accessing the Laravel Log Viewer.
     */
    public function showConfirmForm(Request $request): View|RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        if (! $admin instanceof Admin) {
            return redirect()->guest(route('admin.login'));
        }

        $confirmedAt = $request->session()->get('admin_log_viewer_confirmed_at');
        $timeout = (int) config('log-viewer.password_timeout', 1800);

        // If already unlocked and within timeout, proceed directly
        if (is_numeric($confirmedAt) && (time() - (int) $confirmedAt) < $timeout) {
            $intended = (string) $request->session()->pull('url.intended', route('log-viewer.index'));

            return redirect()->to($intended);
        }

        return view('admin.auth.log-viewer-confirm', [
            'admin' => $admin,
        ]);
    }

    /**
     * Confirm administrator password and unlock access to Laravel Log Viewer.
     */
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $admin = Auth::guard('admin')->user();

        if (! $admin instanceof Admin) {
            return redirect()->guest(route('admin.login'));
        }

        if (! Hash::check((string) $request->input('password'), $admin->password)) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // Grant access by stamping session time
        $request->session()->put('admin_log_viewer_confirmed_at', time());

        // Log sensitive authorization unlock into audit trail
        try {
            AdminActivityLog::create([
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
                'role_name' => $admin->role instanceof \App\Models\Role ? $admin->role->name : 'Admin',
                'module' => 'logs',
                'action' => 'confirm_password',
                'description' => 'قام المسؤول بتأكيد كلمة المرور بنجاح لفتح سجلات النظام المتقدمة (Log Viewer).',
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'route_name' => 'admin.log-viewer.confirm.store',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
            ]);
        } catch (Throwable) {
            // Non-critical audit failure
        }

        $intended = (string) $request->session()->pull('url.intended', route('log-viewer.index'));

        return redirect()->to($intended)->with('success', __('تم تأكيد كلمة المرور بنجاح، تم فتح سجلات النظام.'));
    }

    /**
     * Explicitly lock Log Viewer access by clearing confirmation timestamp.
     */
    public function lock(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_log_viewer_confirmed_at');

        return redirect()->route('admin.dashboard')->with('info', __('تم قفل الوصول إلى سجلات النظام بنجاح.'));
    }
}
