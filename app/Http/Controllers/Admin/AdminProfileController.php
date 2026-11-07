<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdminPasswordRequest;
use App\Http\Requests\Admin\UpdateAdminProfileRequest;
use App\Models\Admin;
use App\Services\Auth\AdminPasskeyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function __construct(
        protected AdminPasskeyService $passkeyService
    ) {}

    /**
     * Show the admin profile and security management page.
     */
    public function edit(): View
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();
        $admin->load('role', 'passkeys');

        $passkeys = $this->passkeyService->listPasskeys($admin);

        return view('admin.profile.edit', [
            'admin' => $admin,
            'passkeys' => $passkeys,
        ]);
    }

    /**
     * Update the administrator's profile information.
     */
    public function update(UpdateAdminProfileRequest $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();
        $validated = $request->validated();

        $newLocale = $validated['locale'] ?? $admin->locale;

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'locale' => $newLocale,
        ]);

        if (! empty($validated['locale'])) {
            app(\App\Services\Localization\LocalizationService::class)->setLocale($newLocale, $admin);
        }

        return redirect()
            ->route('admin.profile.edit')
            ->with('status', 'profile-updated')
            ->with('success', __('تم تحديث بيانات الملف الشخصي بنجاح.'));
    }

    /**
     * Update the administrator's account password.
     */
    public function updatePassword(UpdateAdminPasswordRequest $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();
        $validated = $request->validated();

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.profile.edit')
            ->with('status', 'password-updated')
            ->with('success', __('تم تغيير كلمة المرور بنجاح.'));
    }
}
