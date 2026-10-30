@props([
    'title' => null,
    'modals' => null,
    'scripts' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="overflow-x-clip">
    <head>
        @include('partials.head')
        <title>{{ filled($title ?? null) ? $title . ' | ' . config('app.name', 'منصة الأمين') : config('app.name', 'منصة الأمين') }}</title>
        <link rel="stylesheet" href="/assets/css/admin.css" />
    </head>
    <body class="bg-[#F8FAFC] text-[#334155] min-h-screen flex flex-col font-['Cairo'] antialiased selection:bg-[#EAF1FB] selection:text-[#0B2A8F] overflow-x-clip w-full max-w-full relative" x-data="{ mobileSidebarOpen: false }" @keydown.window.escape="mobileSidebarOpen = false" :class="{ 'overflow-hidden': mobileSidebarOpen }">
        
        <!-- Top-Level Pre-Rendered Themed Loader (At the top of everything from 0ms) -->
        <x-alameen-loader />

        <div class="flex-1 flex flex-row min-h-screen w-full max-w-full overflow-x-clip relative alameen-content-wrap">
            
            <!-- Mobile Sidebar Overlay (z-40 so it stays beneath the z-50 drawer) -->
            <div
                x-show="mobileSidebarOpen"
                x-cloak
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="mobileSidebarOpen = false"
                @touchmove.prevent
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 lg:hidden"
                style="display: none;"
            ></div>

            <!-- ============================================== -->
            <!-- STUDENT SIDEBAR NAVIGATION                     -->
            <!-- ============================================== -->
            <aside
                :class="{ 'is-open': mobileSidebarOpen }"
                class="student-sidebar-drawer z-50 bg-white dark:bg-slate-900 border-e border-[#DEE8F7] dark:border-slate-800 flex flex-col p-4 sm:p-5 shrink-0 shadow-xl lg:shadow-none overflow-y-auto overscroll-contain scrollbar-thin"
            >
                <div class="flex flex-col gap-5 sm:gap-6 shrink-0">
                    <!-- Platform Brand & Role Badge -->
                    <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 cursor-pointer" wire:navigate>
                            <div class="size-11 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 flex items-center justify-center p-2 shrink-0 border border-[#DEE8F7] dark:border-slate-700 shadow-xs">
                                <img src="/assets/images/logo.png" alt="{{ config('app.name', 'منصة الأمين') }}" class="size-full object-contain pointer-events-none" />
                            </div>
                            <div>
                                <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-xl leading-tight text-[#012383] dark:text-white block">
                                    {{ config('app.name', 'منصة الأمين') }}
                                </span>
                                <span class="text-[11px] font-bold text-[#0B2A8F] dark:text-blue-300 bg-[#EAF1FB] dark:bg-slate-800 px-2 py-0.5 rounded-full inline-block mt-0.5">
                                    {{ __('بوابة الطالب الأكاديمية') }}
                                </span>
                            </div>
                        </a>
                        <button type="button" @click="mobileSidebarOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer" aria-label="{{ __('إغلاق القائمة') }}">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Navigation Links Group -->
                    <nav class="flex flex-col gap-1.5">
                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 px-3 uppercase tracking-wider mb-1">
                            {{ __('التعلّم الخاص بك') }}
                        </p>
                        
                        <!-- Dashboard Link -->
                        <a
                            href="{{ route('dashboard') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm shadow-xs transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#012383] text-white' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                        >
                            <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="flex-1 text-start">
                                {{ __('لوحة الطالب') }}
                            </span>
                        </a>

                        <!-- دوراتي -->
                        <a
                            href="{{ route('courses.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('courses.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('courses.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span class="flex-1 text-start">{{ __('دوراتي ومحاضراتي') }}</span>
                        </a>

                        <!-- المهام السريرية -->
                        <a
                            href="{{ route('assignments.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('assignments.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('assignments.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span class="flex-1 text-start">{{ __('المهام السريرية') }}</span>
                        </a>

                        <!-- درجاتي وتقييماتي -->
                        <a
                            href="{{ route('grades.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('grades.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('grades.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                            <span class="flex-1 text-start">{{ __('درجاتي وتقييماتي') }}</span>
                        </a>

                        <!-- صندوق الإشعارات (Livewire Backend) -->
                        <a
                            href="{{ route('notifications.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('notifications.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('notifications.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="flex-1 text-start">{{ __('صندوق الإشعارات') }}</span>
                        </a>

                        <!-- شهاداتي المعتمدة -->
                        <a
                            href="{{ route('certificates.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('certificates.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('certificates.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span class="flex-1 text-start">{{ __('شهاداتي المعتمدة') }}</span>
                        </a>

                        <!-- إعدادات الحساب -->
                        <a
                            href="{{ route('profile.edit') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('profile.*', 'security.*', 'appearance.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                        >
                            <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="flex-1 text-start">{{ __('الإعدادات والأمان') }}</span>
                        </a>
                    </nav>
                </div>

                <!-- Bottom User Actions & Sign Out -->
                <div class="mt-auto shrink-0 pt-4 border-t border-[#DEE8F7] dark:border-slate-800 flex flex-col gap-2">
                    <a href="{{ route('home') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-[#EAF1FB] text-[#012383] text-xs font-bold transition-all duration-200 border border-slate-100 hover:border-[#DEE8F7] dark:bg-slate-800/80 dark:hover:bg-slate-700 dark:text-blue-300 dark:border-slate-700 group">
                        <span class="flex items-center gap-2">
                            <svg class="size-4 text-[#0B2A8F] dark:text-blue-400 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span>{{ __('الصفحة الرئيسية') }}</span>
                        </span>
                        <span class="text-[10px] text-slate-400 group-hover:text-[#0B2A8F] bg-white group-hover:bg-[#DEE8F7]/60 dark:bg-slate-700/80 dark:text-slate-300 px-1.5 py-0.5 rounded-md border border-slate-200/60 group-hover:border-[#C5D7F2] dark:border-slate-600 transition-colors font-medium">فتح ↗</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between p-2.5 rounded-xl bg-rose-50/70 hover:bg-rose-100/90 text-rose-600 hover:text-rose-700 border border-rose-100/90 hover:border-rose-200 text-xs font-bold transition-all duration-200 cursor-pointer group shadow-2xs dark:bg-rose-950/25 dark:hover:bg-rose-950/50 dark:text-rose-400 dark:hover:text-rose-300 dark:border-rose-900/40 dark:hover:border-rose-800/60">
                            <span class="flex items-center gap-2">
                                <svg class="size-4 text-rose-500 group-hover:text-rose-600 rtl:rotate-180 transition-transform group-hover:-translate-x-0.5 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span>{{ __('تسجيل الخروج') }}</span>
                            </span>
                            <span class="text-[10px] text-rose-500 group-hover:text-rose-700 bg-white group-hover:bg-rose-200/70 dark:bg-rose-950/60 dark:text-rose-300 px-1.5 py-0.5 rounded-md border border-rose-200/70 group-hover:border-rose-300 dark:border-rose-900/60 transition-colors font-semibold">خروج ↵</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- ============================================== -->
            <!-- MAIN CONTENT WORKSPACE                         -->
            <!-- ============================================== -->
            <main class="flex-1 flex flex-col min-w-0 w-full max-w-full overflow-x-clip bg-[#F8FAFC]">
                
                <!-- Top Admin/User Header -->
                <header class="h-16 sm:h-20 bg-white/95 backdrop-blur-md border-b border-[#DEE8F7] px-3 sm:px-8 flex items-center justify-between gap-2 sm:gap-4 sticky top-0 z-30 w-full max-w-full">
                    
                    <!-- Right: Mobile Menu Toggle -->
                    <div class="flex items-center gap-2">
                        <button type="button" @click="mobileSidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 cursor-pointer shrink-0" aria-label="{{ __('القائمة') }}">
                            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                    </div>

                    <!-- Left: Quick Actions, Notification Bell, Theme Switcher, Language, Profile -->
                    <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                        
                        <!-- Theme Switcher (Light / Dark / Device) -->
                        <x-theme-switcher />

                        <!-- Notifications Bell (Livewire Component) -->
                        <livewire:notifications.notification-bell />

                        <!-- Language Switcher Dropdown -->
                        <x-language-switcher />

                        <!-- User Profile Summary & Dropdown -->
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button
                                type="button"
                                @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center gap-1.5 sm:gap-3 pe-0.5 sm:pe-1 border-s border-[#DEE8F7] ps-2 sm:ps-3 cursor-pointer group"
                                aria-label="{{ __('حسابي') }}"
                            >
                                <div class="size-8 sm:size-10 rounded-xl bg-gradient-to-tr from-[#012383] to-[#0B2A8F] flex items-center justify-center text-white font-bold text-xs sm:text-sm shadow-xs group-hover:scale-105 transition-transform shrink-0">
                                    {{ auth()->user()?->initials() ?? 'ط' }}
                                </div>
                                <div class="text-start hidden xl:block">
                                    <p class="font-bold text-xs text-[#012383] leading-tight">{{ auth()->user()?->name ?? __('طالب متدرب') }}</p>
                                    <p class="text-[11px] text-slate-400 font-medium">
                                        {{ __('طالب مسجل') }}
                                    </p>
                                </div>
                                <svg class="size-3 text-slate-400 transition-transform group-hover:text-[#012383] hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div
                                x-show="userMenuOpen"
                                @click.outside="userMenuOpen = false"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute end-0 mt-2 w-52 sm:w-56 rounded-2xl bg-white border border-[#DEE8F7] shadow-xl p-1.5 z-50 flex flex-col gap-1"
                                style="display: none;"
                            >
                                <div class="px-3 py-2 border-b border-[#DEE8F7] mb-1">
                                    <p class="font-bold text-xs text-[#012383]">{{ auth()->user()?->name ?? __('طالب متدرب') }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()?->email ?? '' }}</p>
                                </div>

                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-[#EAF1FB] hover:text-[#012383] transition-colors" wire:navigate>
                                    <svg class="size-4 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>{{ __('الملف الشخصي والإعدادات') }}</span>
                                </a>

                                <a href="{{ route('appearance.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-[#EAF1FB] hover:text-[#012383] transition-colors" wire:navigate>
                                    <svg class="size-4 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                    <span>{{ __('المظهر والموضوع') }}</span>
                                </a>

                                <a href="{{ route('security.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-[#EAF1FB] hover:text-[#012383] transition-colors" wire:navigate>
                                    <svg class="size-4 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span>{{ __('الأمان وكلمة المرور') }}</span>
                                </a>

                                <div class="border-t border-[#DEE8F7] my-1"></div>

                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors cursor-pointer text-start group">
                                        <span class="flex items-center gap-2">
                                            <svg class="size-4 rtl:rotate-180 text-rose-500 group-hover:text-rose-600 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            <span>{{ __('تسجيل الخروج') }}</span>
                                        </span>
                                        <span class="text-[10px] text-rose-400 group-hover:text-rose-600 transition-colors font-semibold">↵</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>

                </header>

                <!-- Page Body Container -->
                <div class="p-3.5 sm:p-8 flex flex-col gap-6 sm:gap-8 max-w-[1600px] w-full mx-auto">
                    {{ $slot }}
                </div>

            </main>

        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        {{ $modals ?? '' }}
        {{ $scripts ?? '' }}

        @fluxScripts
    </body>
</html>
