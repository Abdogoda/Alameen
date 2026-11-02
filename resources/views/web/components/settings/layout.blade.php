<div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-8 shadow-xs flex flex-col md:flex-row items-start gap-6 lg:gap-8 w-full min-w-0">
    
    <!-- Settings Navigation Sidebar (Tabs) -->
    <div class="settings-nav-col w-full md:w-64 lg:w-72 shrink-0 border-b md:border-b-0 md:border-e border-[#DEE8F7] dark:border-slate-800 pb-5 md:pb-0 md:pe-6">
        <div class="flex flex-col gap-2 w-full" role="tablist">
            @php
                $settingsTabs = [
                    [
                        'name' => 'profile',
                        'title' => __('الملف الشخصي'),
                        'subtitle' => __('البيانات الأساسية والحساب'),
                        'route' => 'profile.edit',
                        'is_active' => request()->routeIs('profile.*'),
                        'icon' => '<svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
                    ],
                    [
                        'name' => 'security',
                        'title' => __('الأمان وكلمة المرور'),
                        'subtitle' => __('تأمين الدخول وكلمة المرور'),
                        'route' => 'security.edit',
                        'is_active' => request()->routeIs('security.*'),
                        'icon' => '<svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>',
                    ],
                    [
                        'name' => 'appearance',
                        'title' => __('المظهر والتخصيص'),
                        'subtitle' => __('الوضع الليلي والخطوط وتجربة التعلم'),
                        'route' => 'appearance.edit',
                        'is_active' => request()->routeIs('appearance.*'),
                        'icon' => '<svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 5 5 0 015-5c.78 0 1.5.2 2.13.56.24.14.47.3.69.48l1.37-1.37a5 5 0 017.07 0 5 5 0 010 7.07l-1.37 1.37c.18.22.34.45.48.69.36.63.56 1.35.56 2.13a5 5 0 01-5 5H7z"/></svg>',
                    ],
                    [
                        'name' => 'certificates',
                        'title' => __('الشهادات المعتمدة'),
                        'subtitle' => __('الشهادات المرفوعة والموثقة لك'),
                        'route' => 'settings.certificates',
                        'is_active' => request()->routeIs('settings.certificates*'),
                        'icon' => '<svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
                    ],
                ];
            @endphp

            @foreach($settingsTabs as $tab)
                @if($tab['is_active'])
                    <a
                        href="{{ route($tab['route']) }}"
                        wire:navigate
                        class="flex items-center gap-3.5 px-4 py-3 rounded-2xl bg-[#012383] dark:bg-blue-600 text-white font-bold text-sm shadow-md transition-all duration-200 relative overflow-hidden group select-none"
                    >
                        <span class="size-9 rounded-xl bg-white/20 text-white flex items-center justify-center shrink-0 shadow-xs">
                            {!! $tab['icon'] !!}
                        </span>
                        <div class="flex flex-col text-start min-w-0 flex-1">
                            <span class="font-bold text-sm text-white leading-tight">{{ $tab['title'] }}</span>
                            <span class="text-[11px] text-white/80 font-normal truncate mt-0.5">{{ $tab['subtitle'] }}</span>
                        </div>
                        <span class="size-2 rounded-full bg-emerald-400 shadow-sm animate-pulse shrink-0"></span>
                    </a>
                @else
                    <a
                        href="{{ route($tab['route']) }}"
                        wire:navigate
                        class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-slate-600 dark:text-slate-300 hover:text-[#012383] dark:hover:text-white hover:bg-[#EAF1FB]/80 dark:hover:bg-slate-800/80 font-semibold text-sm transition-all duration-200 group select-none"
                    >
                        <span class="size-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-400 group-hover:text-[#012383] dark:group-hover:text-white group-hover:bg-[#EAF1FB] dark:group-hover:bg-slate-700 transition-colors flex items-center justify-center shrink-0">
                            {!! $tab['icon'] !!}
                        </span>
                        <div class="flex flex-col text-start min-w-0 flex-1">
                            <span class="font-semibold text-sm group-hover:text-[#012383] dark:group-hover:text-white transition-colors leading-tight">{{ $tab['title'] }}</span>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-normal truncate mt-0.5">{{ $tab['subtitle'] }}</span>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Settings Content Column -->
    <div class="settings-content-col flex-1 self-stretch min-w-0 w-full text-start">
        @if (isset($heading))
            <div class="flex flex-col gap-1 pb-4 mb-6 border-b border-[#DEE8F7] dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="size-2 rounded-full bg-[#012383] dark:bg-blue-400"></span>
                    <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-[#012383] dark:text-white leading-tight">
                        {{ $heading }}
                    </h2>
                </div>
                @if (isset($subheading))
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 ps-4">{{ $subheading }}</p>
                @endif
            </div>
        @endif

        <div class="w-full">
            {{ $slot }}
        </div>
    </div>

</div>
