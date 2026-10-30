@props([
    'variant' => 'dropdown', // 'dropdown', 'pill', 'segmented'
    'align' => 'end', // 'start', 'end'
    'size' => 'md', // 'sm', 'md'
])

@php
    $id = 'theme-switcher-' . uniqid();
@endphp

<div
    x-data="{
        open: false,
        theme: (window.AlameenTheme ? window.AlameenTheme.getTheme() : (localStorage.getItem('alameen_theme') || 'light')),
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
    @if ($variant === 'pill')
        {{-- Segmented Pill Style (Matches Language Switcher Pill) --}}
        <div class="inline-flex items-center gap-1 rounded-full border border-[#D7E3F5] bg-[#EAF1FB] dark:border-slate-700 dark:bg-slate-800/80 p-1 text-xs shadow-xs transition-colors">
            <!-- Light Button -->
            <button
                type="button"
                @click="setTheme('light')"
                :class="theme === 'light' ? 'bg-white text-amber-500 shadow-sm dark:bg-slate-700 dark:text-amber-400' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white'"
                class="inline-flex items-center justify-center size-7 sm:size-8 rounded-full transition-all cursor-pointer"
                title="{{ __('theme.light') }}"
                aria-label="{{ __('theme.light') }}"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </button>

            <!-- Dark Button -->
            <button
                type="button"
                @click="setTheme('dark')"
                :class="theme === 'dark' ? 'bg-white text-indigo-500 shadow-sm dark:bg-slate-700 dark:text-indigo-400' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white'"
                class="inline-flex items-center justify-center size-7 sm:size-8 rounded-full transition-all cursor-pointer"
                title="{{ __('theme.dark') }}"
                aria-label="{{ __('theme.dark') }}"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            <!-- Device / System Button -->
            <button
                type="button"
                @click="setTheme('system')"
                :class="theme === 'system' ? 'bg-white text-[#0B2A8F] shadow-sm dark:bg-slate-700 dark:text-blue-400' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white'"
                class="inline-flex items-center justify-center size-7 sm:size-8 rounded-full transition-all cursor-pointer"
                title="{{ __('theme.system') }}"
                aria-label="{{ __('theme.system') }}"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </button>
        </div>

    @elseif ($variant === 'segmented')
        {{-- Full Segmented Switcher for Settings / Forms --}}
        <div class="grid grid-cols-3 gap-3 p-1.5 rounded-2xl bg-slate-100 dark:bg-slate-800/80 border border-[#DEE8F7] dark:border-slate-700">
            <!-- Light -->
            <button
                type="button"
                @click="setTheme('light')"
                :class="theme === 'light' ? 'bg-white dark:bg-slate-700 text-[#012383] dark:text-white shadow-sm border border-[#DEE8F7] dark:border-slate-600 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'"
                class="flex flex-col items-center justify-center gap-2 py-3.5 px-3 rounded-xl transition-all cursor-pointer"
            >
                <div class="size-9 rounded-xl flex items-center justify-center bg-amber-50 dark:bg-amber-950/40 text-amber-500 dark:text-amber-400">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="text-xs">{{ __('theme.light') }}</span>
                <span x-show="theme === 'light'" class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1">
                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                    {{ __('theme.active') }}
                </span>
            </button>

            <!-- Dark -->
            <button
                type="button"
                @click="setTheme('dark')"
                :class="theme === 'dark' ? 'bg-white dark:bg-slate-700 text-[#012383] dark:text-white shadow-sm border border-[#DEE8F7] dark:border-slate-600 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'"
                class="flex flex-col items-center justify-center gap-2 py-3.5 px-3 rounded-xl transition-all cursor-pointer"
            >
                <div class="size-9 rounded-xl flex items-center justify-center bg-indigo-50 dark:bg-indigo-950/40 text-indigo-500 dark:text-indigo-400">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </div>
                <span class="text-xs">{{ __('theme.dark') }}</span>
                <span x-show="theme === 'dark'" class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1">
                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                    {{ __('theme.active') }}
                </span>
            </button>

            <!-- System -->
            <button
                type="button"
                @click="setTheme('system')"
                :class="theme === 'system' ? 'bg-white dark:bg-slate-700 text-[#012383] dark:text-white shadow-sm border border-[#DEE8F7] dark:border-slate-600 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'"
                class="flex flex-col items-center justify-center gap-2 py-3.5 px-3 rounded-xl transition-all cursor-pointer"
            >
                <div class="size-9 rounded-xl flex items-center justify-center bg-blue-50 dark:bg-blue-950/40 text-[#0B2A8F] dark:text-blue-400">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-xs">{{ __('theme.system') }}</span>
                <span x-show="theme === 'system'" class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1">
                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                    {{ __('theme.active') }}
                </span>
            </button>
        </div>

    @else
        {{-- Default: Elegant Dropdown Button --}}
        <button
            type="button"
            @click="open = !open"
            class="h-8 sm:h-10 px-2 sm:px-3 rounded-full bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:bg-[#EAF1FB] dark:hover:bg-slate-700/80 text-[#012383] dark:text-slate-200 transition-all cursor-pointer shadow-xs flex items-center gap-1 sm:gap-1.5 group font-['Cairo'] shrink-0"
            :title="'{{ __('theme.theme') }}: ' + (theme === 'light' ? '{{ __('theme.light') }}' : (theme === 'dark' ? '{{ __('theme.dark') }}' : '{{ __('theme.device') }}'))"
            aria-label="{{ __('theme.theme') }}"
        >
            <!-- Sun Icon (Light) -->
            <svg
                x-show="theme === 'light'"
                class="size-4 text-amber-500 transition-transform group-hover:rotate-45 duration-300"
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
                class="size-4 text-indigo-400 transition-transform group-hover:-rotate-12 duration-300"
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
                class="size-4 text-[#0B2A8F] dark:text-blue-400 transition-transform group-hover:scale-110 duration-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>

            <svg class="size-3 text-slate-400 group-hover:text-[#012383] dark:group-hover:text-white transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div
            x-show="open"
            @click.outside="open = false"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute {{ $align === 'end' ? 'ltr:right-0 rtl:left-0' : 'ltr:left-0 rtl:right-0' }} mt-2 w-48 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xl p-1.5 z-50 flex flex-col gap-1"
            style="display: none;"
        >
            <div class="px-3 py-1.5 border-b border-[#DEE8F7] dark:border-slate-800 mb-1">
                <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('theme.theme') }}</p>
            </div>

            <!-- Light Option -->
            <button
                type="button"
                @click="setTheme('light')"
                class="flex items-center justify-between w-full px-3 py-2 rounded-xl text-xs font-semibold transition-colors cursor-pointer"
                :class="theme === 'light' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60'"
            >
                <div class="flex items-center gap-2.5">
                    <svg class="size-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>{{ __('theme.light') }}</span>
                </div>
                <span x-show="theme === 'light'" class="size-2 rounded-full bg-[#012383] dark:bg-blue-400"></span>
            </button>

            <!-- Dark Option -->
            <button
                type="button"
                @click="setTheme('dark')"
                class="flex items-center justify-between w-full px-3 py-2 rounded-xl text-xs font-semibold transition-colors cursor-pointer"
                :class="theme === 'dark' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60'"
            >
                <div class="flex items-center gap-2.5">
                    <svg class="size-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span>{{ __('theme.dark') }}</span>
                </div>
                <span x-show="theme === 'dark'" class="size-2 rounded-full bg-[#012383] dark:bg-blue-400"></span>
            </button>

            <!-- Device / System Option -->
            <button
                type="button"
                @click="setTheme('system')"
                class="flex items-center justify-between w-full px-3 py-2 rounded-xl text-xs font-semibold transition-colors cursor-pointer"
                :class="theme === 'system' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60'"
            >
                <div class="flex items-center gap-2.5">
                    <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>{{ __('theme.device') }}</span>
                </div>
                <span x-show="theme === 'system'" class="size-2 rounded-full bg-[#012383] dark:bg-blue-400"></span>
            </button>
        </div>
    @endif
</div>
