<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AdminPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteAdminRequest;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of administrators and supervisors.
     */
    public function index(): View
    {
        $admins = Admin::with(['role', 'passkeys'])
            ->latest('id')
            ->paginate(15);

        return view('admin.admins.index', [
            'admins' => $admins,
            'totalAdmins' => Admin::count(),
            'totalRoles' => Role::count(),
        ]);
    }

    /**
     * Show the form for creating a new administrator.
     */
    public function create(): View
    {
        $roles = Role::orderBy('id')->get();
        $permissionGroups = AdminPermission::grouped(app()->getLocale());

        return view('admin.admins.create', [
            'roles' => $roles,
            'permissionGroups' => $permissionGroups,
        ]);
    }

    /**
     * Store a newly created administrator in the database.
     */
    public function store(StoreAdminRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'permissions' => ! empty($validated['permissions']) ? array_values($validated['permissions']) : null,
            'locale' => 'ar',
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', __('تم إنشاء حساب المشرف (:name) بنجاح.', ['name' => $admin->name]));
    }

    /**
     * Show the form for editing the specified administrator.
     */
    public function edit(Admin $admin): View
    {
        $admin->load('role');
        $roles = Role::orderBy('id')->get();
        $permissionGroups = AdminPermission::grouped(app()->getLocale());

        return view('admin.admins.edit', [
            'admin' => $admin,
            'roles' => $roles,
            'permissionGroups' => $permissionGroups,
        ]);
    }

    /**
     * Update the specified administrator in the database.
     */
    public function update(UpdateAdminRequest $request, Admin $admin): RedirectResponse
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'permissions' => ! empty($validated['permissions']) ? array_values($validated['permissions']) : null,
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $admin->update($data);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', __('تم تحديث بيانات المشرف (:name) بنجاح.', ['name' => $admin->name]));
    }

    /**
     * Remove the specified administrator from storage.
     */
    public function destroy(DeleteAdminRequest $request, Admin $admin): RedirectResponse
    {
        $request->validated();

        if ($admin->id === auth('admin')->id()) {
            return back()->withErrors(['admin' => __('لا يمكنك حذف حسابك الشخصي.')]);
        }

        if ($admin->id === 1) {
            return back()->withErrors(['admin' => __('لا يمكن حذف حساب المسؤول العام الرئيسي.')]);
        }

        $name = $admin->name;
        $admin->delete();

        return redirect()
            ->route('admin.admins.index')
            ->with('success', __('تم حذف حساب المشرف (:name) بنجاح.', ['name' => $name]));
    }
}
