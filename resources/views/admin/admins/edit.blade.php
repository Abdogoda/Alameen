<x-admin-layout::admin :title="__('تعديل بيانات المشرف: :name', ['name' => $admin->name])">

    <x-slot:actions>
        <a
            href="{{ route('admin.admins.index') }}"
            class="h-10 px-4 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 text-[#012383] dark:text-blue-300 font-bold text-xs border border-[#DEE8F7] dark:border-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-xs"
        >
            <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>{{ __('العودة لقائمة المشرفين') }}</span>
        </a>
    </x-slot:actions>

    <div class="max-w-4xl mx-auto space-y-6">

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-sm shadow-xs">
                <div class="flex items-center gap-2 font-bold mb-1.5">
                    <svg class="size-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ __('يرجى تصحيح الأخطاء التالية:') }}</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-600 dark:text-rose-400 font-semibold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.admins.update', $admin) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Info Card -->
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
                <h3 class="text-base font-bold text-[#012383] dark:text-white pb-3 border-b border-[#DEE8F7] dark:border-slate-800">
                    {{ __('بيانات الحساب الأساسية') }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('اسم المشرف') }} <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $admin->name) }}"
                            required
                            class="w-full px-4 py-3 rounded-xl bg-[#FAFCFF] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F]"
                        />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('البريد الإلكتروني') }} <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $admin->email) }}"
                            required
                            class="w-full px-4 py-3 rounded-xl bg-[#FAFCFF] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F]"
                        />
                    </div>

                    <!-- Password (Optional on update) -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('كلمة المرور الجديدة') }}
                            <span class="text-slate-400 font-normal">({{ __('اتركها فارغة للإبقاء على الحالية') }})</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-xl bg-[#FAFCFF] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F]"
                        />
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('الدور الوظيفي الرئيسي') }} <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="role_id"
                            name="role_id"
                            required
                            class="w-full px-4 py-3 rounded-xl bg-[#FAFCFF] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F]"
                        >
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $admin->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }} ({{ $role->slug }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Custom Overrides Permissions Card -->
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
                <div>
                    <h3 class="text-base font-bold text-[#012383] dark:text-white">{{ __('الصلاحيات المخصصة الإضافية (اختياري)') }}</h3>
                    <p class="text-xs text-slate-400 mt-1">{{ __('يرث المشرف كافة صلاحيات دوره الأساسي تلقائياً. يمكنك هنا منحه صلاحيات إضافية مستقلة.') }}</p>
                </div>

                @php
                    $assignedCustomPerms = old('permissions', $admin->permissions ?? []);
                @endphp

                <div class="space-y-6 pt-2">
                    @foreach ($permissionGroups as $groupName => $permissions)
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 space-y-3">
                            <h4 class="text-xs font-bold text-[#012383] dark:text-blue-300 flex items-center gap-2">
                                <span class="size-2 rounded-full bg-[#0B2A8F] dark:bg-blue-400"></span>
                                <span>{{ $groupName }}</span>
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                @foreach ($permissions as $perm)
                                    <label class="flex items-start gap-2.5 p-2 rounded-xl hover:bg-white dark:hover:bg-slate-800 cursor-pointer select-none transition-colors">
                                        <input
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $perm['value'] }}"
                                            {{ in_array($perm['value'], $assignedCustomPerms, true) ? 'checked' : '' }}
                                            class="rounded border-[#CFDBEA] text-[#0B2A8F] focus:ring-[#0B2A8F] size-4 mt-0.5"
                                        />
                                        <div class="text-xs">
                                            <span class="font-bold text-slate-700 dark:text-slate-200 block">{{ $perm['label'] }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono">{{ $perm['value'] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a
                    href="{{ route('admin.admins.index') }}"
                    class="px-5 py-3 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
                >
                    {{ __('إلغاء') }}
                </a>
                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2 cursor-pointer"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ __('حفظ التعديلات') }}</span>
                </button>
            </div>
        </form>

    </div>

</x-admin-layout::admin>
