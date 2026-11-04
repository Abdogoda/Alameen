@props([
    'title' => null,
    'modals' => null,
    'scripts' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="alameen-dashboard-layout">
    <head>
        @include('partials.head')
        <title>{{ filled($title ?? null) ? $title . ' | ' . ($siteName ?? __(config('app.name', 'منصة الأمين'))) : ($siteName ?? __(config('app.name', 'منصة الأمين'))) }}</title>
    </head>
    <body
        class="bg-[#F8FAFC] text-[#334155] min-h-screen flex flex-col font-['Cairo'] antialiased selection:bg-[#EAF1FB] selection:text-[#0B2A8F] w-full max-w-full relative alameen-dashboard-layout"
        x-data="{
            mobileSidebarOpen: false,
            desktopSidebarOpen: !document.documentElement.classList.contains('sidebar-collapsed'),
            toggleSidebar() {
                if (window.innerWidth < 1024) {
                    this.mobileSidebarOpen = !this.mobileSidebarOpen;
                } else {
                    this.desktopSidebarOpen = !this.desktopSidebarOpen;
                    if (!this.desktopSidebarOpen) {
                        document.documentElement.classList.add('sidebar-collapsed');
                        try { localStorage.setItem('student_sidebar_collapsed', 'true'); } catch (e) {}
                    } else {
                        document.documentElement.classList.remove('sidebar-collapsed');
                        try { localStorage.removeItem('student_sidebar_collapsed'); } catch (e) {}
                    }
                }
            }
        }"
        @keydown.window.escape="mobileSidebarOpen = false"
        @resize.window.debounce.100ms="if (window.innerWidth >= 1024) mobileSidebarOpen = false"
        :class="{ 'overflow-hidden': mobileSidebarOpen }"
    >
        
        <!-- Top-Level Pre-Rendered Themed Loader (At the top of everything from 0ms) -->
        @persist('alameen-loader')
        <x-alameen-loader />
        @endpersist

        <!-- Top Scroll Progress Indicator Bar -->
        <div id="alameen-scroll-progress" class="fixed top-0 inset-x-0 h-1 bg-gradient-to-r from-[#012383] via-blue-500 to-emerald-400 z-[99999] transition-all duration-150 pointer-events-none" style="width: 0%; display: none;"></div>

        <div class="flex-1 flex flex-row min-h-screen w-full max-w-full overflow-x-clip relative alameen-content-wrap">
            
            <!-- Mobile Sidebar Overlay (z-[60] so it stays above sticky header and page, but beneath drawer z-[70]) -->
            <div
                x-show="mobileSidebarOpen"
                x-cloak
                x-transition:enter="transition-opacity ease-out duration-250"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click.stop="mobileSidebarOpen = false"
                @touchmove.prevent
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-[60] lg:hidden touch-manipulation"
                style="display: none;"
            ></div>

            <!-- ============================================== -->
            <!-- STUDENT SIDEBAR NAVIGATION                     -->
            <!-- ============================================== -->
            <aside
                :class="{
                    'is-open': mobileSidebarOpen,
                    'is-collapsed': !desktopSidebarOpen && window.innerWidth >= 1024
                }"
                class="student-sidebar-drawer z-[70] bg-white dark:bg-slate-900 border-e border-[#DEE8F7] dark:border-slate-800 flex flex-col shrink-0 shadow-2xl lg:shadow-none select-none overflow-hidden"
            >
                <!-- Platform Brand & Role Badge (Matches top header height: h-14 sm:h-16) -->
                <div class="h-14 sm:h-16 px-4 sm:px-5 border-b border-[#DEE8F7] dark:border-slate-800 flex items-center justify-between shrink-0 bg-white dark:bg-slate-900 sidebar-header">
                    <a href="{{ $cmsSettings->isPageEnabled('student_dashboard') ? route('dashboard') : route('courses.index') }}" class="flex items-center gap-2.5 sm:gap-3 cursor-pointer min-w-0 group sidebar-brand-link" wire:navigate>
                        <div class="size-9 sm:size-10 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 flex items-center justify-center p-1.5 shrink-0 border border-[#DEE8F7] dark:border-slate-700 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            <img src="{{ filled($siteLogo ?? null) ? $siteLogo : platform_logo() }}" alt="{{ filled($siteName ?? null) ? $siteName : platform_name() }}" class="size-full object-contain pointer-events-none dark:hidden logo-theme-light" />
                            <img src="{{ $siteLogoWhite ?? platform_logo_white() }}" alt="{{ filled($siteName ?? null) ? $siteName : platform_name() }}" class="size-full object-contain pointer-events-none hidden dark:block logo-theme-dark" />
                        </div>
                        <div class="min-w-0 sidebar-brand-text flex flex-col justify-center">
                            <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-sm sm:text-base leading-tight text-[#012383] dark:text-white block truncate">
                                {{ filled($siteName ?? null) ? $siteName : platform_name() }}
                            </span>
                            <span class="text-[9.5px] sm:text-[10px] font-bold text-[#0B2A8F] dark:text-blue-300 bg-[#EAF1FB] dark:bg-slate-800 px-2 py-0.5 rounded-full inline-block leading-none mt-0.5 truncate max-w-full">
                                {{ __('بوابة الطالب الأكاديمية') }}
                            </span>
                        </div>
                    </a>
                    <button type="button" @click.stop.prevent="mobileSidebarOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer shrink-0 touch-manipulation" aria-label="{{ __('إغلاق القائمة') }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Navigation Links Scroll Area (Enhanced modern subtle scrollbar) -->
                <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain px-3 sm:px-4 py-4 flex flex-col gap-5 sm:gap-6 alameen-sidebar-scroll">
                    <!-- Navigation Links Group -->
                    <nav class="flex flex-col gap-1.5">
                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 px-3 uppercase tracking-wider mb-1 sidebar-section-title">
                            {{ __('التعلّم الخاص بك') }}
                        </p>
                        
                        @if($cmsSettings->isPageEnabled('student_dashboard'))
                        <!-- Dashboard Link -->
                        <a
                            href="{{ route('dashboard') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm shadow-xs transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#012383] text-white' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('لوحة الطالب') }}' : ''"
                        >
                            <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="flex-1 text-start sidebar-label">
                                {{ __('لوحة الطالب') }}
                            </span>
                        </a>
                        @endif

                        @if($cmsSettings->isPageEnabled('courses'))
                        <!-- دوراتي -->
                        <a
                            href="{{ route('courses.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('courses.*', 'lessons.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('دوراتي ومحاضراتي') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('courses.*', 'lessons.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('دوراتي ومحاضراتي') }}</span>
                        </a>
                        @endif

                        @if($cmsSettings->isPageEnabled('tasks'))
                        <!-- المهام -->
                        <a
                            href="{{ route('assignments.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('assignments.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('المهام') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('assignments.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('المهام') }}</span>
                        </a>
                        @endif

                        @if($cmsSettings->isPageEnabled('grades'))
                        <!-- درجاتي وتقييماتي -->
                        <a
                            href="{{ route('grades.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('grades.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('درجاتي وتقييماتي') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('grades.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('درجاتي وتقييماتي') }}</span>
                        </a>
                        @endif

                        @if($cmsSettings->isPageEnabled('notifications'))
                        @php
                            $studentUnreadSupportCount = auth()->check() ? \App\Models\SupportMessage::whereHas('ticket', fn($q) => $q->where('user_id', auth()->id()))->where('sender_type', \App\Models\Admin::class)->where('type', 'message')->whereNull('read_at')->count() : 0;
                            $studentUnreadNotificationsCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
                            $totalUnreadHubCount = $studentUnreadSupportCount + $studentUnreadNotificationsCount;
                        @endphp
                        <!-- الإشعارات والدعم (مركز التواصل والتنبيهات الموحد) -->
                        <a
                            href="{{ route('notifications.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('notifications.*', 'support.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('الإشعارات والدعم') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('notifications.*', 'support.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('الإشعارات والدعم') }}</span>
                            @if($totalUnreadHubCount > 0)
                                <span class="min-w-5 h-5 px-1.5 flex items-center justify-center text-[11px] font-black rounded-full {{ $studentUnreadSupportCount > 0 ? 'bg-[#F2AE2E] text-slate-900' : 'bg-rose-500 text-white' }} animate-pulse shrink-0">
                                    {{ $totalUnreadHubCount }}
                                </span>
                            @endif
                        </a>
                        @endif

                        @if($cmsSettings->isPageEnabled('settings'))
                        <!-- إعدادات الحساب -->
                        <a
                            href="{{ route('profile.edit') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('profile.*', 'security.*', 'appearance.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            wire:navigate
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('الإعدادات والأمان') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('الإعدادات والأمان') }}</span>
                        </a>
                        @endif
                    </nav>
                </div>

                <!-- Bottom User Profile Card with Dropdown -->
                @php
                    $studentUser = auth()->user();
                @endphp
                <div class="mt-auto shrink-0 border-t border-[#DEE8F7] dark:border-slate-800 p-3 sm:p-4 bg-white dark:bg-slate-900 relative sidebar-bottom-section" x-data="{ sidebarUserMenuOpen: false }" @click.outside="sidebarUserMenuOpen = false" @keydown.escape.window="sidebarUserMenuOpen = false">
                    <!-- Popup Dropdown Menu (Opens upwards above the user trigger) -->
                    <div
                        x-show="sidebarUserMenuOpen"
                        x-cloak
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                        class="absolute bottom-full inset-x-3 mb-2 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-2xl p-1.5 z-50 flex flex-col gap-1 font-['Cairo'] min-w-[240px]"
                        style="display: none;"
                    >
                        <!-- Header with Name, Email & Role -->
                        <div class="px-3 py-2.5 border-b border-[#DEE8F7] dark:border-slate-800 mb-1 flex items-center gap-2.5">
                            <div class="size-9 rounded-xl bg-gradient-to-tr from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden shadow-xs">
                                @if($studentUser?->avatarUrl())
                                    <img src="{{ $studentUser->avatarUrl() }}" alt="{{ $studentUser->name }}" class="size-full object-cover" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');" />
                                    <span class="hidden">{{ $studentUser?->initials() ?? 'ط' }}</span>
                                @else
                                    <span>{{ $studentUser?->initials() ?? 'ط' }}</span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1 text-start">
                                <p class="font-bold text-xs text-[#012383] dark:text-white truncate">{{ $studentUser?->name ?? __('طالب متدرب') }}</p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-400 truncate dir-ltr text-start">{{ $studentUser?->email ?? '' }}</p>
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 mt-0.5">
                                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                    {{ __('طالب مسجل') }}
                                </span>
                            </div>
                        </div>

                        <!-- Menu Options -->
                        <a
                            href="{{ route('profile.edit') }}"
                            @click="sidebarUserMenuOpen = false; if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-blue-300 transition-colors {{ request()->routeIs('profile.*') ? 'bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-300' : '' }}"
                            wire:navigate
                        >
                            <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="flex-1 text-start">{{ __('الملف الشخصي والإعدادات') }}</span>
                        </a>

                        <a
                            href="{{ route('appearance.edit') }}"
                            @click="sidebarUserMenuOpen = false; if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-blue-300 transition-colors {{ request()->routeIs('appearance.*') ? 'bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-300' : '' }}"
                            wire:navigate
                        >
                            <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            <span class="flex-1 text-start">{{ __('المظهر والموضوع') }}</span>
                        </a>

                        <a
                            href="{{ route('security.edit') }}"
                            @click="sidebarUserMenuOpen = false; if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-blue-300 transition-colors {{ request()->routeIs('security.*') ? 'bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-300' : '' }}"
                            wire:navigate
                        >
                            <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span class="flex-1 text-start">{{ __('الأمان وكلمة المرور') }}</span>
                        </a>

                        <a
                            href="{{ route('home') }}"
                            target="_blank"
                            @click="sidebarUserMenuOpen = false"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-blue-300 transition-colors"
                        >
                            <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span class="flex-1 text-start">{{ __('الصفحة الرئيسية') }}</span>
                            <span class="text-[10px] text-slate-400 font-normal">↗</span>
                        </a>

                        <div class="border-t border-[#DEE8F7] dark:border-slate-800 my-1"></div>

                        <!-- Sign Out Form -->
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button
                                type="submit"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-950/40 dark:hover:text-rose-300 transition-colors cursor-pointer text-start group"
                            >
                                <span class="flex items-center gap-2">
                                    <svg class="size-4 rtl:rotate-180 text-rose-500 group-hover:text-rose-600 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>{{ __('تسجيل الخروج') }}</span>
                                </span>
                                <span class="text-[10px] text-rose-400 group-hover:text-rose-600 transition-colors font-semibold">↵</span>
                            </button>
                        </form>
                    </div>

                    <!-- Sidebar User Trigger Button (Name, Email, Image) -->
                    <button
                        type="button"
                        @click="sidebarUserMenuOpen = !sidebarUserMenuOpen"
                        class="sidebar-user-btn w-full flex items-center gap-2.5 p-2 rounded-xl border transition-all duration-200 cursor-pointer group select-none text-start"
                        :class="sidebarUserMenuOpen
                            ? 'bg-[#EAF1FB] dark:bg-slate-800 border-[#C5D7F2] dark:border-slate-700 shadow-xs ring-2 ring-[#012383]/10 dark:ring-blue-500/20'
                            : 'bg-slate-50/80 hover:bg-[#EAF1FB] border-slate-200/70 hover:border-[#DEE8F7] dark:bg-slate-800/80 dark:hover:bg-slate-700/80 dark:border-slate-700'"
                        aria-label="{{ __('بيانات الطالب والقائمة') }}"
                        :aria-expanded="sidebarUserMenuOpen"
                    >
                        <!-- Avatar / Image -->
                        <div class="size-10 rounded-xl bg-gradient-to-tr from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-xs shrink-0 overflow-hidden ring-1 ring-[#DEE8F7] dark:ring-slate-700 group-hover:scale-102 transition-transform">
                            @if($studentUser?->avatarUrl())
                                <img src="{{ $studentUser->avatarUrl() }}" alt="{{ $studentUser->name }}" class="size-full object-cover" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');" />
                                <span class="hidden">{{ $studentUser?->initials() ?? 'ط' }}</span>
                            @else
                                <span>{{ $studentUser?->initials() ?? 'ط' }}</span>
                            @endif
                        </div>

                        <!-- Name & Email (hidden when collapsed) -->
                        <div class="sidebar-user-text min-w-0 flex-1 text-start">
                            <span class="font-bold text-xs text-[#012383] dark:text-slate-100 truncate block leading-tight group-hover:text-[#0B2A8F] dark:group-hover:text-blue-300 transition-colors">
                                {{ $studentUser?->name ?? __('طالب متدرب') }}
                            </span>
                            <span class="text-[11px] text-slate-400 dark:text-slate-400 truncate block dir-ltr text-start mt-0.5">
                                {{ $studentUser?->email ?? '' }}
                            </span>
                        </div>

                        <!-- Dropdown Chevron (hidden when collapsed) -->
                        <svg
                            class="sidebar-user-chevron size-4 text-slate-400 transition-transform duration-200 shrink-0"
                            :class="sidebarUserMenuOpen ? 'rotate-180 text-[#012383] dark:text-blue-300' : 'group-hover:text-[#012383] dark:group-hover:text-white'"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </aside>

            <!-- ============================================== -->
            <!-- MAIN CONTENT WORKSPACE                         -->
            <!-- ============================================== -->
            <main class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC] dark:bg-[#0B1120]">
                
                <!-- Top Admin/User Header -->
                <header class="student-sticky-header h-14 sm:h-16 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-[#DEE8F7] dark:border-slate-800 px-3 sm:px-6 lg:px-8 flex items-center justify-between gap-2 sm:gap-4 sticky top-0 z-30 w-full max-w-full shadow-2xs shrink-0">
                    
                    <!-- Sidebar Toggle Button (Mobile + Desktop) -->
                    <div class="flex items-center gap-2 shrink-0">
                        <button
                            type="button"
                            @click.stop.prevent="toggleSidebar()"
                            class="size-8 sm:size-10 rounded-xl border border-[#DEE8F7] bg-white hover:bg-[#EAF1FB] dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white flex items-center justify-center text-slate-600 hover:text-[#012383] transition-all duration-200 cursor-pointer shadow-xs shrink-0 group touch-manipulation"
                            :title="desktopSidebarOpen ? '{{ __('طي / إخفاء القائمة الجانبية') }}' : '{{ __('إظهار القائمة الجانبية') }}'"
                            aria-label="{{ __('تبديل القائمة الجانبية') }}"
                        >
                            <svg class="size-4 sm:size-5 text-slate-600 group-hover:text-[#012383] dark:text-slate-300 dark:group-hover:text-white transition-colors pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Left: Quick Actions, Language, Theme Switcher, Notification Bell, Profile -->
                    <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                        
                        <!-- Language Switcher Dropdown -->
                        <x-language-switcher />

                        <!-- Theme Switcher (Light / Dark / Device) -->
                        <x-theme-switcher />

                        @if($cmsSettings->isPageEnabled('notifications'))
                        <!-- Notifications Bell (Livewire Component) -->
                        <livewire:notifications.notification-bell />
                        @endif

                        <!-- Vertical Divider -->
                        <div class="h-6 sm:h-7 w-px bg-[#DEE8F7] dark:bg-slate-700 mx-0.5 sm:mx-1 shrink-0" aria-hidden="true"></div>

                        <!-- User Profile Summary & Dropdown (Avatar, Name, Email) -->
                        <div class="relative" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false" @keydown.escape.window="userMenuOpen = false">
                            <button
                                type="button"
                                @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center gap-2 sm:gap-2.5 p-1 sm:pe-3 sm:ps-1.5 rounded-2xl border transition-all duration-200 cursor-pointer group shrink-0 select-none !transform-none"
                                :class="userMenuOpen 
                                    ? 'bg-[#EAF1FB] dark:bg-slate-800 border-[#C5D7F2] dark:border-slate-700 shadow-xs ring-2 ring-[#012383]/10 dark:ring-blue-500/20' 
                                    : 'border-transparent hover:border-[#DEE8F7] dark:hover:border-slate-700 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800/60'"
                                aria-label="{{ __('حسابي') }}"
                                :aria-expanded="userMenuOpen"
                            >
                                <div 
                                    class="size-8 sm:size-9 rounded-xl bg-gradient-to-tr from-[#012383] to-[#0B2A8F] flex items-center justify-center text-white font-bold text-xs sm:text-sm shadow-xs transition-transform duration-200 shrink-0 overflow-hidden ring-1 ring-[#DEE8F7] dark:ring-slate-700"
                                    :class="userMenuOpen ? 'scale-105 ring-2 ring-white dark:ring-slate-900 shadow-sm' : 'group-hover:scale-105'"
                                >
                                    @if(auth()->user()?->avatarUrl())
                                        <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" class="size-full object-cover" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');" />
                                        <span class="hidden">{{ auth()->user()?->initials() ?? 'ط' }}</span>
                                    @else
                                        <span>{{ auth()->user()?->initials() ?? 'ط' }}</span>
                                    @endif
                                </div>
                                <div class="text-start hidden sm:flex sm:flex-col min-w-0 max-w-[150px] lg:max-w-[200px]">
                                    <p 
                                        class="font-bold text-xs leading-tight transition-colors duration-200 truncate"
                                        :class="userMenuOpen ? 'text-[#012383] dark:text-blue-300 font-extrabold' : 'text-[#012383] dark:text-slate-200'"
                                    >
                                        {{ auth()->user()?->name ?? __('طالب متدرب') }}
                                    </p>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-400 truncate dir-ltr text-start">
                                        {{ auth()->user()?->email ?? '' }}
                                    </p>
                                </div>
                                <svg 
                                    class="size-3 text-slate-400 transition-transform duration-200 hidden sm:block shrink-0" 
                                    :class="userMenuOpen ? 'rotate-180 text-[#012383] dark:text-blue-300' : 'group-hover:text-[#012383] dark:group-hover:text-white'"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div
                                x-show="userMenuOpen"
                                x-cloak
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                class="absolute end-0 mt-2 w-60 sm:w-64 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xl p-1.5 z-50 flex flex-col gap-1 font-['Cairo']"
                                style="display: none;"
                            >
                                <div class="px-3 py-2.5 border-b border-[#DEE8F7] dark:border-slate-800 mb-1 flex items-center gap-2.5">
                                    <div class="size-9 rounded-xl bg-gradient-to-tr from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden shadow-xs">
                                        @if(auth()->user()?->avatarUrl())
                                            <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" class="size-full object-cover" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');" />
                                            <span class="hidden">{{ auth()->user()?->initials() ?? 'ط' }}</span>
                                        @else
                                            <span>{{ auth()->user()?->initials() ?? 'ط' }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1 text-start">
                                        <p class="font-bold text-xs text-[#012383] dark:text-white truncate">{{ auth()->user()?->name ?? __('طالب متدرب') }}</p>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-400 truncate dir-ltr text-start">{{ auth()->user()?->email ?? '' }}</p>
                                    </div>
                                </div>

                                <a href="{{ route('profile.edit') }}" @click="userMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-colors {{ request()->routeIs('profile.*') ? 'bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-300' : '' }}" wire:navigate>
                                    <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>{{ __('الملف الشخصي والإعدادات') }}</span>
                                </a>

                                <a href="{{ route('appearance.edit') }}" @click="userMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-colors {{ request()->routeIs('appearance.*') ? 'bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-300' : '' }}" wire:navigate>
                                    <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                    <span>{{ __('المظهر والموضوع') }}</span>
                                </a>

                                <a href="{{ route('security.edit') }}" @click="userMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-colors {{ request()->routeIs('security.*') ? 'bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-300' : '' }}" wire:navigate>
                                    <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span>{{ __('الأمان وكلمة المرور') }}</span>
                                </a>

                                <a href="{{ route('home') }}" target="_blank" @click="userMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-colors">
                                    <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    <span>{{ __('الصفحة الرئيسية') }}</span>
                                    <span class="ms-auto text-[10px] text-slate-400">↗</span>
                                </a>

                                <div class="border-t border-[#DEE8F7] dark:border-slate-800 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-950/40 dark:hover:text-rose-300 transition-colors cursor-pointer text-start group">
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
                <div class="px-3.5 sm:px-6 lg:px-8 pt-4 sm:pt-6 pb-8 flex flex-col gap-6 max-w-[1600px] w-full mx-auto">
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

        @if(app()->getLocale() === 'en')
        <script>
        window.__I18N_DICTIONARY__ = {
            dashboard: @json(__('dashboard')),
            courses: @json(__('courses')),
            lesson: @json(__('lesson')),
            grades: @json(__('grades')),
            result: @json(__('result')),
            profile: @json(__('profile')),
            catalog: @json(__('catalog')),
            reviews: @json(__('reviews')),
            contact: @json(__('contact')),
            landing: @json(__('landing')),
            notifications: @json(__('notifications')),
        };
        window.__EN_STRINGS__ = @json(json_decode(file_get_contents(base_path('lang/en.json')), true) ?: []);

        function stripTashkeel(s) {
            return s ? s.replace(/[\u064B-\u065F\u0670\u06D6-\u06ED]/g, '').trim() : '';
        }

        const rawTrans = window.__EN_STRINGS__ || {};
        const normTrans = {};
        for (const [k, v] of Object.entries(rawTrans)) {
            const tk = k.trim();
            normTrans[tk] = v;
            const stripped = stripTashkeel(tk);
            if (stripped && !normTrans[stripped]) {
                normTrans[stripped] = v;
            }
        }

        function applyI18nTranslations(root = document) {
            if (!root) return;
            const dict = window.__I18N_DICTIONARY__;
            if (dict) {
                root.querySelectorAll('[data-i18n]').forEach(el => {
                    const keyPath = el.getAttribute('data-i18n');
                    if (!keyPath) return;
                    const [group, key] = keyPath.split('.');
                    if (dict[group] && dict[group][key]) {
                        el.textContent = dict[group][key];
                    }
                });
                root.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                    const keyPath = el.getAttribute('data-i18n-placeholder');
                    if (!keyPath) return;
                    const [group, key] = keyPath.split('.');
                    if (dict[group] && dict[group][key]) {
                        el.setAttribute('placeholder', dict[group][key]);
                    }
                });
                root.querySelectorAll('[data-i18n-title]').forEach(el => {
                    const keyPath = el.getAttribute('data-i18n-title');
                    if (!keyPath) return;
                    const [group, key] = keyPath.split('.');
                    if (dict[group] && dict[group][key]) {
                        el.setAttribute('title', dict[group][key]);
                    }
                });
            }

            root.querySelectorAll('[placeholder], [title], [aria-label]').forEach(el => {
                const ph = el.getAttribute('placeholder');
                if (ph) {
                    const t = ph.trim();
                    if (normTrans[t]) el.setAttribute('placeholder', normTrans[t]);
                    else if (normTrans[stripTashkeel(t)]) el.setAttribute('placeholder', normTrans[stripTashkeel(t)]);
                }
                const tt = el.getAttribute('title');
                if (tt) {
                    const t = tt.trim();
                    if (normTrans[t]) el.setAttribute('title', normTrans[t]);
                    else if (normTrans[stripTashkeel(t)]) el.setAttribute('title', normTrans[stripTashkeel(t)]);
                }
                const al = el.getAttribute('aria-label');
                if (al) {
                    const t = al.trim();
                    if (normTrans[t]) el.setAttribute('aria-label', normTrans[t]);
                    else if (normTrans[stripTashkeel(t)]) el.setAttribute('aria-label', normTrans[stripTashkeel(t)]);
                }
            });

            const targetRoot = root.body || root;
            const walker = document.createTreeWalker(targetRoot, NodeFilter.SHOW_TEXT, {
                acceptNode(node) {
                    if (!node.nodeValue || !node.nodeValue.trim()) return NodeFilter.FILTER_REJECT;
                    const parent = node.parentElement;
                    if (!parent) return NodeFilter.FILTER_REJECT;
                    const tag = parent.tagName.toLowerCase();
                    if (tag === 'script' || tag === 'style' || tag === 'code' || tag === 'pre') return NodeFilter.FILTER_REJECT;
                    return NodeFilter.FILTER_ACCEPT;
                }
            }, false);

            let node;
            while ((node = walker.nextNode())) {
                const original = node.nodeValue;
                const trimmed = original.trim();
                if (!trimmed) continue;

                if (normTrans[trimmed]) {
                    node.nodeValue = original.replace(trimmed, normTrans[trimmed]);
                    continue;
                }
                const stripped = stripTashkeel(trimmed);
                if (stripped && normTrans[stripped]) {
                    node.nodeValue = original.replace(trimmed, normTrans[stripped]);
                    continue;
                }
                for (const arKey in normTrans) {
                    if (arKey.length >= 4 && original.includes(arKey)) {
                        node.nodeValue = node.nodeValue.replaceAll(arKey, normTrans[arKey]);
                    }
                }
            }
        }
        document.addEventListener('DOMContentLoaded', () => applyI18nTranslations());
        document.addEventListener('livewire:navigated', () => applyI18nTranslations());
        if (document.readyState !== 'loading') { applyI18nTranslations(); }
        </script>
        @endif
    </body>
</html>
