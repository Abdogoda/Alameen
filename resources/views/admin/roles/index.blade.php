<x-admin-layout::admin :title="__('إدارة الأدوار والصلاحيات')">

    <x-slot:actions>
        <a
            href="{{ route('admin.admins.index') }}"
            class="h-10 px-4 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 text-[#012383] dark:text-blue-300 font-bold text-xs border border-[#DEE8F7] dark:border-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-xs"
        >
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span>{{ __('قائمة المشرفين') }}</span>
        </a>

        <a
            href="{{ route('admin.roles.create') }}"
            class="h-10 px-4 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2 cursor-pointer"
        >
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>{{ __('إضافة دور جديد') }}</span>
        </a>
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

        <!-- Roles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach ($roles as $role)
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 shadow-xs flex flex-col justify-between gap-5 relative overflow-hidden group hover:shadow-card hover:border-[#0B2A8F]/30 transition-all">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 inline-block mb-1">
                                    {{ $role->slug }}
                                </span>
                                <h3 class="font-bold text-base text-[#012383] dark:text-white">
                                    {{ $role->name }}
                                </h3>
                                @if (!empty($role->name_en) && $role->name_en !== $role->name)
                                    <p class="text-xs text-slate-400 font-medium">{{ $role->name_en }}</p>
                                @endif
                            </div>

                            @if ($role->is_system)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800/50 shrink-0">
                                    {{ __('دور نظام أساسي') }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 border border-blue-200/60 dark:border-slate-700 shrink-0">
                                    {{ __('دور مخصص') }}
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed min-h-[36px]">
                            {{ $role->description ?: __('لا يوجد وصف متاح لهذا الدور.') }}
                        </p>
                    </div>

                    <!-- Role Specs / Metrics -->
                    <div class="space-y-4 pt-3 border-t border-[#DEE8F7] dark:border-slate-800">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-bold">{{ __('المشرفون المسند إليهم') }}:</span>
                            <span class="font-black text-[#012383] dark:text-blue-400">
                                {{ $role->admins_count }} {{ __('مشرف') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-bold">{{ __('عدد الصلاحيات الممنوحة') }}:</span>
                            <span class="font-black {{ in_array('*', $role->permissions ?? [], true) ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                {{ in_array('*', $role->permissions ?? [], true) ? __('كاملة (Wildcard *)') : count($role->permissions ?? []).' '.__('صلاحية') }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between gap-2 pt-2">
                            <a
                                href="{{ route('admin.roles.edit', $role) }}"
                                class="flex-1 py-2 px-3 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-[#EAF1FB] text-[#012383] dark:text-blue-300 text-xs font-bold text-center border border-slate-200/70 dark:border-slate-700 transition-colors flex items-center justify-center gap-1.5 cursor-pointer"
                            >
                                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>{{ __('تعديل الصلاحيات') }}</span>
                            </a>

                            @if (!$role->is_system && $role->slug !== 'super_admin' && $role->admins_count === 0)
                                <form 
                                    method="POST" 
                                    action="{{ route('admin.roles.destroy', $role) }}" 
                                    data-confirm="{{ __('هل أنت متأكد من حذف الدور :name؟', ['name' => $role->name]) }}"
                                    data-confirm-title="{{ __('حذف الدور الإداري') }}"
                                    data-confirm-btn="{{ __('نعم، احذف الدور') }}"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 border border-transparent hover:border-rose-100 transition-colors cursor-pointer"
                                        title="{{ __('حذف الدور') }}"
                                    >
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

</x-admin-layout::admin>
