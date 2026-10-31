<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AdminPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteRoleRequest;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleManagementController extends Controller
{
    /**
     * Display a listing of system and custom roles.
     */
    public function index(): View
    {
        $roles = Role::withCount('admins')
            ->orderBy('id')
            ->get();

        return view('admin.roles.index', [
            'roles' => $roles,
            'totalRoles' => $roles->count(),
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        $permissionGroups = AdminPermission::grouped(app()->getLocale());

        return view('admin.roles.create', [
            'permissionGroups' => $permissionGroups,
        ]);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $role = Role::create([
            'name' => $validated['name'],
            'name_ar' => $validated['name_ar'] ?: $validated['name'],
            'name_en' => $validated['name_en'] ?: null,
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'description_ar' => $validated['description_ar'] ?? ($validated['description'] ?? null),
            'description_en' => $validated['description_en'] ?? null,
            'permissions' => array_values($validated['permissions']),
            'is_system' => false,
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('تم إنشاء الدور الجديد (:name) بنجاح.', ['name' => $role->name]));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): View
    {
        $permissionGroups = AdminPermission::grouped(app()->getLocale());

        return view('admin.roles.edit', [
            'role' => $role,
            'permissionGroups' => $permissionGroups,
        ]);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $validated = $request->validated();

        $role->update([
            'name' => $validated['name'],
            'name_ar' => $validated['name_ar'] ?: $validated['name'],
            'name_en' => $validated['name_en'] ?: null,
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'description_ar' => $validated['description_ar'] ?? ($validated['description'] ?? null),
            'description_en' => $validated['description_en'] ?? null,
            'permissions' => array_values($validated['permissions']),
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('تم تحديث بيانات الدور (:name) بنجاح.', ['name' => $role->name]));
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(DeleteRoleRequest $request, Role $role): RedirectResponse
    {
        $request->validated();

        if ($role->is_system || $role->slug === 'super_admin') {
            return back()->withErrors(['role' => __('لا يمكن حذف أدوار النظام الأساسية.')]);
        }

        if ($role->admins()->count() > 0) {
            return back()->withErrors(['role' => __('لا يمكن حذف هذا الدور لوجود مشرفين مسندين إليه حالياً.')]);
        }

        $name = $role->name;
        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('تم حذف الدور (:name) بنجاح.', ['name' => $name]));
    }
}
