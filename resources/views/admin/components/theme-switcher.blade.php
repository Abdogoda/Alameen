@props([
    'variant' => 'dropdown', // 'dropdown', 'pill', 'segmented'
    'align' => 'end', // 'start', 'end'
])

<div
    x-data="{
        open: false,
        theme: (window.AlameenTheme ? window.AlameenTheme.getTheme() : (localStorage.getItem('alameen_theme') || 'system')),
        isDark: (window.AlameenTheme ? window.AlameenTheme.isDark() : document.documentElement.classList.contains('dark')),
        setTheme(val) {
            this.theme = val;
            if (window.AlameenTheme) {
                window.AlameenTheme.setTheme(val);
                this.isDark = window.AlameenTheme.isDark();
            } else {
                localStorage.setItem('alameen_theme', val);
                const dark = val === 'dark' || (val === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', dark);
                this.isDark = dark;
            }
            this.open = false;
        },
        init() {
            window.addEventListener('alameen-theme-changed', (e) => {
                this.theme = e.detail.theme;
                this.isDark = e.detail.isDark;
            });
        }
    }"
    class="relative inline-block text-start"
    {{ $attributes }}
>
    @if ($variant === 'segmented')
        {{-- Segmented Cards for Admin Settings Page --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
            <!-- Light Theme Card -->
            <button
                type="button"
                @click="setTheme('light')"
                :class="theme === 'light' ? 'ring-2 ring-[#0B2A8F] bg-white dark:bg-slate-800 shadow-md border-transparent' : 'bg-slate-50 dark:bg-slate-900 border-[#DEE8F7] dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700'"
                class="flex flex-col items-center justify-between p-5 rounded-2xl border transition-all text-center cursor-pointer group"
            >
                <div class="size-12 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-[#012383] dark:text-slate-100">{{ __('theme.light') }}</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">{{ __('مظهر نهاري ناصع ومريح في الإضاءة العالية') }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 w-full flex items-center justify-center">
                    <span x-show="theme === 'light'" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                        <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ __('theme.active') }}
                    </span>
                    <span x-show="theme !== 'light'" class="text-xs text-slate-400 font-medium">
                        {{ __('تفعيل') }}
                    </span>
                </div>
            </button>

            <!-- Dark Theme Card -->
            <button
                type="button"
                @click="setTheme('dark')"
                :class="theme === 'dark' ? 'ring-2 ring-[#0B2A8F] bg-white dark:bg-slate-800 shadow-md border-transparent' : 'bg-slate-50 dark:bg-slate-900 border-[#DEE8F7] dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700'"
                class="flex flex-col items-center justify-between p-5 rounded-2xl border transition-all text-center cursor-pointer group"
            >
                <div class="size-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-[#012383] dark:text-slate-100">{{ __('theme.dark') }}</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">{{ __('مظهر ليلي مريح للعين أثناء جلسات الإشراف الطويلة') }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 w-full flex items-center justify-center">
                    <span x-show="theme === 'dark'" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                        <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ __('theme.active') }}
                    </span>
                    <span x-show="theme !== 'dark'" class="text-xs text-slate-400 font-medium">
                        {{ __('تفعيل') }}
                    </span>
                </div>
            </button>

            <!-- Device / System Card -->
            <button
                type="button"
                @click="setTheme('system')"
                :class="theme === 'system' ? 'ring-2 ring-[#0B2A8F] bg-white dark:bg-slate-800 shadow-md border-transparent' : 'bg-slate-50 dark:bg-slate-900 border-[#DEE8F7] dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700'"
                class="flex flex-col items-center justify-between p-5 rounded-2xl border transition-all text-center cursor-pointer group"
            >
                <div class="size-12 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-[#0B2A8F] dark:text-blue-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-[#012383] dark:text-slate-100">{{ __('theme.system') }}</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">{{ __('مزامنة تلقائية حسب إعدادات نظام التشغيل والجهاز') }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 w-full flex items-center justify-center">
                    <span x-show="theme === 'system'" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                        <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ __('theme.active') }}
                    </span>
                    <span x-show="theme !== 'system'" class="text-xs text-slate-400 font-medium">
                        {{ __('تفعيل') }}
                    </span>
                </div>
            </button>
        </div>

    @else
        {{-- Default Admin Topbar Dropdown --}}
        <button
            type="button"
            @click="open = !open"
            class="size-9 sm:size-10 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-[#DEE8F7] dark:border-slate-700/80 hover:bg-[#EAF1FB] dark:hover:bg-slate-700/80 flex items-center justify-center text-[#012383] dark:text-slate-200 transition-colors relative cursor-pointer shadow-xs group shrink-0"
            :title="'{{ __('theme.theme') }}: ' + (theme === 'light' ? '{{ __('theme.light') }}' : (theme === 'dark' ? '{{ __('theme.dark') }}' : '{{ __('theme.device') }}'))"
            aria-label="{{ __('theme.theme') }}"
        >
            <!-- Sun Icon (Light) -->
            <svg
                x-show="theme === 'light'"
                class="size-4 sm:size-5 text-amber-500 transition-transform group-hover:rotate-45 duration-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>

            <!-- Moon Icon (Dark) -->
            <svg
                x-show="theme === 'dark'"
                x-cloak
                class="size-4 sm:size-5 text-indigo-400 transition-transform group-hover:-rotate-12 duration-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>

            <!-- Device / System Icon -->
            <svg
                x-show="theme === 'system'"
                x-cloak
                class="size-4 sm:size-5 text-[#0B2A8F] dark:text-blue-400 transition-transform group-hover:scale-110 duration-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div
            x-show="open"
            x-cloak
            @click.outside="open = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute end-0 z-50 mt-2 w-44 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-white dark:bg-slate-900 p-1.5 shadow-xl ring-1 ring-black/5 dark:ring-black/50"
            style="display: none;"
        >
            <div class="px-2.5 py-1 border-b border-[#DEE8F7] dark:border-slate-800 mb-1">
                <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-wider">{{ __('theme.theme') }}</span>
            </div>

            <!-- Light Option -->
            <button
                type="button"
                @click="setTheme('light')"
                class="flex items-center justify-between w-full px-2.5 py-1.5 rounded-lg text-xs font-medium transition-all cursor-pointer"
                :class="theme === 'light' ? 'bg-[#0B2A8F] text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white'"
            >
                <span class="flex items-center gap-2">
                    <svg class="size-4 text-amber-500" :class="theme === 'light' ? 'text-amber-200' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>{{ __('theme.light') }}</span>
                </span>
                <span x-show="theme === 'light'" class="text-[10px] text-white/90">✓</span>
            </button>

            <!-- Dark Option -->
            <button
                type="button"
                @click="setTheme('dark')"
                class="flex items-center justify-between w-full px-2.5 py-1.5 rounded-lg text-xs font-medium transition-all cursor-pointer"
                :class="theme === 'dark' ? 'bg-[#0B2A8F] text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white'"
            >
                <span class="flex items-center gap-2">
                    <svg class="size-4 text-indigo-400" :class="theme === 'dark' ? 'text-indigo-200' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span>{{ __('theme.dark') }}</span>
                </span>
                <span x-show="theme === 'dark'" class="text-[10px] text-white/90">✓</span>
            </button>

            <!-- System Option -->
            <button
                type="button"
                @click="setTheme('system')"
                class="flex items-center justify-between w-full px-2.5 py-1.5 rounded-lg text-xs font-medium transition-all cursor-pointer"
                :class="theme === 'system' ? 'bg-[#0B2A8F] text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white'"
            >
                <span class="flex items-center gap-2">
                    <svg class="size-4 text-blue-500" :class="theme === 'system' ? 'text-blue-200' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>{{ __('theme.device') }}</span>
                </span>
                <span x-show="theme === 'system'" class="text-[10px] text-white/90">✓</span>
            </button>
        </div>
    @endif
</div>
