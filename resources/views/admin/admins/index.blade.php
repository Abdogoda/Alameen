<x-admin-layout::admin :title="__('إدارة المشرفين وفريق العمل')">

    <x-slot:actions>
        @if(auth('admin')->user()?->hasPermission('roles.manage'))
        <a
            href="{{ route('admin.roles.index') }}"
            class="h-10 px-4 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 text-[#012383] dark:text-blue-300 font-bold text-xs border border-[#DEE8F7] dark:border-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-xs"
        >
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>{{ __('الأدوار والصلاحيات') }} ({{ $totalRoles }})</span>
        </a>
        @endif

        @if(auth('admin')->user()?->hasPermission('admins.manage'))
        <a
            href="{{ route('admin.admins.create') }}"
            class="h-10 px-4 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2 cursor-pointer"
        >
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>{{ __('إضافة مشرف جديد') }}</span>
        </a>
        @endif
    </x-slot:actions>

    <div class="space-y-6">

        <!-- Status Alerts -->
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm flex items-center gap-2.5 font-bold shadow-xs">
                <svg class="size-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-sm shadow-xs">
                <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-600 dark:text-rose-400 font-semibold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Quick Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase">{{ __('إجمالي المشرفين') }}</span>
                    <h3 class="text-2xl font-black text-[#012383] dark:text-blue-400 mt-1">{{ $totalAdmins }}</h3>
                </div>
                <div class="size-11 rounded-xl bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 flex items-center justify-center">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase">{{ __('الأدوار المعتمدة') }}</span>
                    <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1">{{ $totalRoles }}</h3>
                </div>
                <div class="size-11 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase">{{ __('مفاتيح Passkey المفعلة') }}</span>
                    <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                        {{ \App\Models\AdminPasskey::count() }}
                    </h3>
                </div>
                <div class="size-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11a7.96 7.96 0 001.272 4.316m5.748 4.288A19.92 19.92 0 0112 21"/></svg>
                </div>
            </div>
        </div>

        <!-- Admins Table Card -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-[#DEE8F7] dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base text-[#012383] dark:text-white">{{ __('قائمة أعضاء فريق الإدارة') }}</h3>
                    <p class="text-xs text-slate-400 mt-0.5">{{ __('استعراض المشرفين، الأدوار الوظيفية، ومفاتيح المرور البيومترية.') }}</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300">
                    {{ $admins->total() }} {{ __('مشرف') }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs sm:text-sm">
                    <thead class="bg-[#F8FAFC] dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold border-b border-[#DEE8F7] dark:border-slate-800 text-start">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6 text-start">{{ __('المشرف') }}</th>
                            <th class="py-3.5 px-4 text-start">{{ __('الدور الوظيفي') }}</th>
                            <th class="py-3.5 px-4 text-start">{{ __('مفاتيح Passkey') }}</th>
                            <th class="py-3.5 px-4 text-start">{{ __('تاريخ الإنشاء') }}</th>
                            <th class="py-3.5 px-4 sm:px-6 text-end">{{ __('الإجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#DEE8F7] dark:divide-slate-800">
                        @foreach ($admins as $item)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/70 transition-colors">
                                <!-- Admin info -->
                                <td class="py-4 px-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="size-10 rounded-xl bg-gradient-to-br from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                            {{ $item->initials() }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-800 dark:text-slate-100 truncate">{{ $item->name }}</span>
                                                @if ($item->id === auth('admin')->id())
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 dark:bg-blue-900/40 text-[#012383] dark:text-blue-300">{{ __('أنت') }}</span>
                                                @endif
                                            </div>
                                            <span class="text-xs text-slate-400 font-mono truncate block">{{ $item->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Role -->
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $item->isSuperAdmin() ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800/50' : 'bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 border border-blue-200/60 dark:border-slate-700' }}">
                                        <span class="size-1.5 rounded-full {{ $item->isSuperAdmin() ? 'bg-amber-500' : 'bg-blue-500' }}"></span>
                                        {{ $item->role_name }}
                                    </span>
                                </td>

                                <!-- Passkey count -->
                                <td class="py-4 px-4">
                                    @if ($item->passkeys->count() > 0)
                                        <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold text-xs">
                                            <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                            <span>{{ $item->passkeys->count() }} {{ __('مفتاح') }}</span>
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">{{ __('غير مسجل') }}</span>
                                    @endif
                                </td>

                                <!-- Created at -->
                                <td class="py-4 px-4 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $item->created_at?->translatedFormat('d M Y') ?? '—' }}
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-4 sm:px-6 text-end">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if(auth('admin')->user()?->hasPermission('admins.manage'))
                                        <a
                                            href="{{ route('admin.admins.edit', $item) }}"
                                            class="p-2 rounded-xl text-slate-500 hover:text-[#012383] hover:bg-[#EAF1FB] dark:hover:bg-slate-800 transition-colors cursor-pointer"
                                            title="{{ __('تعديل الحساب والصلاحيات') }}"
                                        >
                                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        @if ($item->id !== auth('admin')->id() && $item->id !== 1)
                                            <form 
                                                method="POST" 
                                                action="{{ route('admin.admins.destroy', $item) }}" 
                                                data-confirm="{{ __('هل أنت متأكد من رغبتك في حذف حساب المشرف :name نهائياً؟', ['name' => $item->name]) }}"
                                                data-confirm-title="{{ __('حذف حساب مشرف') }}"
                                                data-confirm-btn="{{ __('نعم، احذف الحساب') }}"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer"
                                                    title="{{ __('حذف المشرف') }}"
                                                >
                                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                        @else
                                            <span class="text-xs text-slate-400 font-medium">{{ __('معاينة فقط') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($admins->hasPages())
                <div class="p-5 border-t border-[#DEE8F7] dark:border-slate-800">
                    {{ $admins->links() }}
                </div>
            @endif
        </div>

    </div>

</x-admin-layout::admin>
