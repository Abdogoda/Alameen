@props([
    'active' => 'home',
])

@php
    $cmsSettings = app(\App\Services\Cms\SettingsService::class);
    $headerMenuItems = app(\App\Services\Cms\MenuService::class)->getMenu('header');
    $announcementEnabled = $cmsSettings->get('announcement_enabled', '0') === '1';
    $announcementText = $cmsSettings->get('announcement_text');
    $announcementUrl = $cmsSettings->get('announcement_url');
    $siteName = $cmsSettings->getSiteName();
    $siteLogo = $cmsSettings->getLogoUrl();
    $siteLogoWhite = $cmsSettings->getLogoWhiteUrl();
@endphp

@if($announcementEnabled && !empty($announcementText))
    <div class="w-full bg-gradient-to-r from-[#012383] via-[#0B2A8F] to-[#10B77F] text-white text-xs py-2 px-4 text-center font-bold flex items-center justify-center gap-3 relative z-50 shadow-xs">
        <span class="inline-block size-2 rounded-full bg-amber-400 animate-pulse"></span>
        <span>{{ __($announcementText) }}</span>
        @if(!empty($announcementUrl))
            <a href="{{ $announcementUrl }}" class="underline hover:text-amber-300 font-extrabold inline-flex items-center gap-1 transition-colors">
                <span>{{ __('المزيد') }}</span>
                <span class="rtl:rotate-180">→</span>
            </a>
        @endif
    </div>
@endif

<!-- UNIFIED MASTER HEADER (Sticky at Top) -->
<header
    class="w-full bg-[#FAFCFF]/95 dark:bg-slate-900/95 border-b border-[#D7E3F5] dark:border-slate-800 flex items-center justify-between px-3.5 sm:px-6 lg:px-12 xl:px-20 2xl:px-28 py-2.5 sm:py-3.5 shrink-0 sticky top-0 z-40 shadow-xs transition-colors relative"
    id="main-header"
    x-data="{ mobileNavOpen: false }"
    @keydown.window.escape="mobileNavOpen = false"
    @resize.window.debounce.100ms="if (window.innerWidth >= 1024) mobileNavOpen = false"
    x-init="$watch('mobileNavOpen', value => document.body.classList.toggle('overflow-hidden', value))"
>
    <!-- Background Frosted Blur Layer (Separated so header is never a containing block for fixed overlays) -->
    <div class="absolute inset-0 -z-10 backdrop-blur-md pointer-events-none"></div>

    <!-- Brand Identity -->
    <a href="{{ route('home') }}" class="flex gap-2 sm:gap-2.5 items-center shrink-0 cursor-pointer">
        <div class="size-8 sm:size-9 lg:size-10 shrink-0 relative flex items-center justify-center transition-all">
            <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="size-full object-contain pointer-events-none dark:hidden logo-theme-light" />
            <img src="{{ $siteLogoWhite ?? '/assets/images/logo-white.png' }}" alt="{{ $siteName }}" class="size-full object-contain pointer-events-none hidden dark:block logo-theme-dark" />
        </div>
        <span class="font-['Baloo_Bhaijaan_2'] font-bold text-base sm:text-lg lg:text-[21px] leading-tight text-[#012383] dark:text-white whitespace-nowrap tracking-tight">
            {{ $siteName }}
        </span>
    </a>

    <!-- Main Navigation Links (Desktop) -->
    <nav class="hidden lg:flex gap-[4px] xl:gap-[8px] items-center shrink-0">
        @if($headerMenuItems->isNotEmpty())
            @foreach($headerMenuItems as $item)
                <a
                    href="{{ $item->resolved_url }}"
                    target="{{ $item->target }}"
                    class="px-3 py-1.5 text-[14px] xl:text-[15px] font-semibold font-['Cairo'] rounded-[12px] whitespace-nowrap transition-colors text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 dark:hover:bg-slate-800/60"
                >
                    {{ $item->title }}
                </a>
            @endforeach
        @else
            <!-- Fallback Static Nav Links -->
            <a href="{{ route('home') }}#home" class="px-3 py-1.5 text-[14px] xl:text-[15px] font-semibold font-['Cairo'] rounded-[12px] text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F]">
                {{ __('landing.navHome') }}
            </a>
            <a href="{{ route('courses.index') }}" class="px-3 py-1.5 text-[14px] xl:text-[15px] font-semibold font-['Cairo'] rounded-[12px] text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F]">
                {{ __('landing.navCourses') }}
            </a>
            <a href="{{ route('home') }}#about" class="px-3 py-1.5 text-[14px] xl:text-[15px] font-semibold font-['Cairo'] rounded-[12px] text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F]">
                {{ __('landing.navAbout') }}
            </a>
            <a href="{{ route('reviews.index') }}" class="px-3 py-1.5 text-[14px] xl:text-[15px] font-semibold font-['Cairo'] rounded-[12px] text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F]">
                {{ __('landing.navTestimonials') }}
            </a>
            <a href="{{ route('contact') }}" class="px-3 py-1.5 text-[14px] xl:text-[15px] font-semibold font-['Cairo'] rounded-[12px] text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F]">
                {{ __('landing.navContact') }}
            </a>
        @endif
    </nav>

    <!-- Right Group (Theme Switcher, Language Switcher & Auth) -->
    <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
        
        <!-- Multi-Theme Switcher (Desktop & Tablet) -->
        <div class="hidden sm:inline-block">
            <x-theme-switcher variant="dropdown" />
        </div>

        <!-- Language Switcher Dropdown (Desktop & Tablet) -->
        <div class="hidden sm:inline-block">
            <x-language-switcher variant="dropdown" />
        </div>

        @if (auth('admin')->check())
            <a
                href="{{ route('admin.dashboard') }}"
                class="bg-gradient-to-r from-[#F2AE2E] to-amber-500 hover:from-amber-400 hover:to-[#F2AE2E] flex items-center justify-center px-3 sm:px-5 h-[34px] sm:h-[40px] rounded-full text-slate-950 text-xs sm:text-sm font-bold font-['Cairo'] transition-all shadow-sm hover:shadow-md cursor-pointer whitespace-nowrap"
            >
                {{ __('لوحة الإدارة') }}
            </a>
        @elseif (auth()->check())
            <a
                href="{{ $cmsSettings->isPageEnabled('student_dashboard') ? route('dashboard') : route('courses.index') }}"
                class="bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center px-3 sm:px-5 h-[34px] sm:h-[40px] rounded-full !text-white text-xs sm:text-sm font-bold font-['Cairo'] transition-all shadow-sm hover:shadow-md cursor-pointer whitespace-nowrap"
                style="color: #ffffff !important;"
            >
                <span class="text-white" style="color: #ffffff !important;">{{ __('بوابة الطالب') }}</span>
            </a>
        @else
            <div class="flex items-center gap-1.5 sm:gap-2">
                <a
                    href="{{ route('login') }}"
                    class="bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center px-3 sm:px-5 h-[34px] sm:h-[40px] rounded-full !text-white text-xs sm:text-sm font-bold font-['Cairo'] transition-all shadow-sm hover:shadow-md cursor-pointer whitespace-nowrap"
                    style="color: #ffffff !important;"
                >
                    <span class="text-white" style="color: #ffffff !important;">{{ __('landing.loginBtn') }}</span>
                </a>
                @if (Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="hidden md:flex border border-[#012383] dark:border-blue-400 text-[#012383] dark:text-blue-300 hover:bg-[#EAF1FB] dark:hover:bg-slate-800 items-center justify-center px-3.5 sm:px-5 h-[36px] sm:h-[40px] rounded-full text-xs sm:text-sm font-bold font-['Cairo'] transition-all cursor-pointer whitespace-nowrap"
                    >
                        {{ __('landing.createAccount') }}
                    </a>
                @endif
            </div>
        @endif

        <!-- Mobile Hamburger Toggle Button -->
        <button
            type="button"
            id="mobile-menu-toggle"
            @click.stop="mobileNavOpen = !mobileNavOpen"
            aria-label="{{ __('القائمة الرئيسية') }}"
            :aria-expanded="mobileNavOpen.toString()"
            class="lg:hidden flex items-center justify-center size-[36px] sm:size-[38px] rounded-xl bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-300 hover:bg-[#DEE8F7] dark:hover:bg-slate-700 transition-colors cursor-pointer select-none"
        >
            <svg x-show="!mobileNavOpen" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="mobileNavOpen" x-cloak class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

    </div>

    <!-- Mobile Navigation Drawer (Sidebar) & Backdrop (Unified Direct Component) -->
    <div class="marketing-drawer-container">
        <!-- 1. Backdrop Overlay -->
        <div
            id="mobile-nav-overlay"
            x-show="mobileNavOpen"
            x-cloak
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileNavOpen = false"
            @touchmove.prevent
            class="marketing-drawer-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[90] lg:hidden touch-manipulation"
            style="display: none;"
        ></div>

        <!-- 2. Slide-Over Sidebar Drawer -->
        <div
            id="mobile-nav-menu"
            x-show="mobileNavOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="ltr:-translate-x-full rtl:translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="ltr:-translate-x-full rtl:translate-x-full"
            class="marketing-sidebar-drawer fixed inset-y-0 start-0 z-[100] w-[310px] sm:w-[350px] max-w-[85vw] h-screen h-[100dvh] max-h-screen max-h-[100dvh] bg-[#FAFCFF] dark:bg-slate-900 border-e border-[#D7E3F5] dark:border-slate-800 shadow-2xl flex flex-col justify-between p-5 overflow-hidden lg:hidden select-none"
            style="display: none;"
        >
            <div class="flex flex-col flex-1 min-h-0">
                <!-- Sidebar Header: Brand + Close Button -->
                <div class="flex items-center justify-between pb-4 border-b border-[#D7E3F5] dark:border-slate-800 shrink-0">
                    <a href="{{ route('home') }}" @click="mobileNavOpen = false" class="flex items-center gap-2.5 cursor-pointer min-w-0">
                        <div class="size-8 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 flex items-center justify-center p-1 shrink-0 border border-[#DEE8F7] dark:border-slate-700 shadow-xs">
                            <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="size-full object-contain pointer-events-none dark:hidden logo-theme-light" />
                            <img src="{{ $siteLogoWhite ?? '/assets/images/logo-white.png' }}" alt="{{ $siteName }}" class="size-full object-contain pointer-events-none hidden dark:block logo-theme-dark" />
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-['Baloo_Bhaijaan_2'] font-bold text-base leading-tight text-[#012383] dark:text-white truncate">
                                {{ __($siteName) }}
                            </span>
                            <span class="text-[10px] font-semibold text-[#0B2A8F] dark:text-blue-300 truncate">
                                {{ __('منصة التخاطب والتدريب') }}
                            </span>
                        </div>
                    </a>
                    <button
                        type="button"
                        id="close-mobile-nav-btn"
                        @click="mobileNavOpen = false"
                        class="size-8 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition-colors cursor-pointer shrink-0"
                        aria-label="{{ __('إغلاق القائمة') }}"
                    >
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                    <!-- Authenticated User Card if Logged In -->
                    @if (auth()->check())
                        <div class="shrink-0 p-3 rounded-2xl bg-[#EAF1FB]/70 dark:bg-slate-800/70 border border-[#D7E3F5] dark:border-slate-700/60 flex items-center gap-3 my-3">
                            <div class="size-9 rounded-xl bg-[#012383] text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                                {{ mb_substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="flex flex-col min-w-0 flex-1">
                                <span class="text-xs font-bold text-[#012383] dark:text-white truncate">
                                    {{ auth()->user()->name }}
                                </span>
                                <span class="text-[10.5px] text-slate-500 dark:text-slate-400 truncate">
                                    {{ auth()->user()->email }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Dynamic or Fallback Navigation Links (Scrollable if needed) -->
                    <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain py-3.5 flex flex-col gap-1.5 alameen-sidebar-scroll">
                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 px-3 uppercase tracking-wider mb-1">
                            {{ __('أقسام المنصة') }}
                        </p>

                        @if($headerMenuItems->isNotEmpty())
                            @foreach($headerMenuItems as $item)
                                <a
                                    href="{{ $item->resolved_url }}"
                                    target="{{ $item->target }}"
                                    @click="mobileNavOpen = false"
                                    class="mobile-nav-link flex items-center justify-between px-3.5 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] dark:text-slate-300 hover:bg-[#EAF1FB] dark:hover:bg-slate-800 transition-colors"
                                >
                                    <span>{{ $item->title }}</span>
                                    <span class="text-xs opacity-50 rtl:rotate-180">→</span>
                                </a>
                            @endforeach
                        @else
                            <a
                                href="{{ route('home') }}#home"
                                @click="mobileNavOpen = false"
                                class="mobile-nav-link flex items-center justify-between px-3.5 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm transition-all {{ $active === 'home' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 shadow-xs' : 'text-[#012383] dark:text-slate-300 hover:bg-[#EAF1FB]/50' }}"
                            >
                                <span class="flex items-center gap-2.5">
                                    <svg class="size-4 shrink-0 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span>{{ __('landing.navHome') }}</span>
                                </span>
                                <span class="text-xs opacity-50 rtl:rotate-180">←</span>
                            </a>

                            <a
                                href="{{ route('courses.index') }}"
                                @click="mobileNavOpen = false"
                                class="mobile-nav-link flex items-center justify-between px-3.5 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm transition-all {{ $active === 'courses' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 shadow-xs' : 'text-[#012383] dark:text-slate-300 hover:bg-[#EAF1FB]/50' }}"
                            >
                                <span class="flex items-center gap-2.5">
                                    <svg class="size-4 shrink-0 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <span>{{ __('landing.navCourses') }}</span>
                                </span>
                                <span class="text-xs opacity-50 rtl:rotate-180">←</span>
                            </a>

                            <a
                                href="{{ route('home') }}#about"
                                @click="mobileNavOpen = false"
                                class="mobile-nav-link flex items-center justify-between px-3.5 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm transition-all {{ $active === 'about' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 shadow-xs' : 'text-[#012383] dark:text-slate-300 hover:bg-[#EAF1FB]/50' }}"
                            >
                                <span class="flex items-center gap-2.5">
                                    <svg class="size-4 shrink-0 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ __('landing.navAbout') }}</span>
                                </span>
                                <span class="text-xs opacity-50 rtl:rotate-180">←</span>
                            </a>

                            <a
                                href="{{ route('reviews.index') }}"
                                @click="mobileNavOpen = false"
                                class="mobile-nav-link flex items-center justify-between px-3.5 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm transition-all {{ $active === 'reviews' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 shadow-xs' : 'text-[#012383] dark:text-slate-300 hover:bg-[#EAF1FB]/50' }}"
                            >
                                <span class="flex items-center gap-2.5">
                                    <svg class="size-4 shrink-0 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    <span>{{ __('landing.navTestimonials') }}</span>
                                </span>
                                <span class="text-xs opacity-50 rtl:rotate-180">←</span>
                            </a>

                            <a
                                href="{{ route('contact') }}"
                                @click="mobileNavOpen = false"
                                class="mobile-nav-link flex items-center justify-between px-3.5 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm transition-all {{ $active === 'contact' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 shadow-xs' : 'text-[#012383] dark:text-slate-300 hover:bg-[#EAF1FB]/50' }}"
                            >
                                <span class="flex items-center gap-2.5">
                                    <svg class="size-4 shrink-0 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span>{{ __('landing.navContact') }}</span>
                                </span>
                                <span class="text-xs opacity-50 rtl:rotate-180">←</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Bottom Actions (Settings, Language, Theme & Auth) -->
                <div class="flex flex-col gap-3.5 pt-4 border-t border-[#D7E3F5] dark:border-slate-800 shrink-0 mt-auto">
                    <div class="flex items-center justify-between gap-2 px-1">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('الإعدادات واللغة') }}</span>
                        <div class="flex items-center gap-2">
                            <x-theme-switcher variant="dropdown" />
                            <x-language-switcher variant="dropdown" />
                        </div>
                    </div>

                    @if (auth('admin')->check())
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="w-full text-center bg-gradient-to-r from-[#F2AE2E] to-amber-500 hover:from-amber-400 hover:to-[#F2AE2E] text-slate-950 font-bold py-2.5 rounded-xl text-xs font-['Cairo'] shadow-sm transition-all"
                        >
                            {{ __('لوحة الإدارة') }}
                        </a>
                    @elseif (auth()->check())
                        <div class="flex flex-col gap-2">
                            <a
                                href="{{ $cmsSettings->isPageEnabled('student_dashboard') ? route('dashboard') : route('courses.index') }}"
                                class="w-full text-center bg-[#012383] hover:bg-[#0B2A8F] !text-white font-bold py-2.5 rounded-xl text-xs font-['Cairo'] shadow-sm transition-all"
                                style="color: #ffffff !important;"
                            >
                                <span class="text-white" style="color: #ffffff !important;">{{ __('بوابة الطالب الأكاديمية') }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/40 transition-colors cursor-pointer">
                                    <svg class="size-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>{{ __('تسجيل الخروج') }}</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex flex-col gap-2">
                            <a
                                href="{{ route('login') }}"
                                class="w-full text-center bg-[#012383] hover:bg-[#0B2A8F] !text-white font-bold py-2.5 rounded-xl text-xs font-['Cairo'] shadow-sm transition-all"
                                style="color: #ffffff !important;"
                            >
                                <span class="text-white" style="color: #ffffff !important;">{{ __('landing.loginBtn') }}</span>
                            </a>
                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="w-full text-center border border-[#012383] dark:border-blue-400 text-[#012383] dark:text-blue-300 hover:bg-[#EAF1FB] dark:hover:bg-slate-800 py-2.5 rounded-xl text-xs font-bold font-['Cairo'] transition-all"
                                >
                                    {{ __('landing.createAccount') }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Fallback & Instant DOM Controller for Mobile Menu Drawer (Guarantees Zero Flash & Smooth Closing) -->
    <script>
        (function() {
            function syncMobileNav() {
                var menu = document.getElementById('mobile-nav-menu');
                var overlay = document.getElementById('mobile-nav-overlay');
                var header = document.getElementById('main-header');

                // Strictly unlock body on page load / refresh
                document.body.classList.remove('overflow-hidden');

                function forceClose() {
                    try {
                        if (header && window.Alpine && typeof Alpine.$data === 'function') {
                            var data = Alpine.$data(header);
                            if (data && typeof data.mobileNavOpen !== 'undefined') {
                                data.mobileNavOpen = false;
                            }
                        }
                    } catch (e) {}

                    if (menu) menu.style.display = 'none';
                    if (overlay) overlay.style.display = 'none';
                    document.body.classList.remove('overflow-hidden');
                }

                var closeBtn = document.getElementById('close-mobile-nav-btn');
                if (closeBtn && !closeBtn.__boundClose) {
                    closeBtn.__boundClose = true;
                    closeBtn.addEventListener('click', forceClose);
                }

                if (overlay && !overlay.__boundClose) {
                    overlay.__boundClose = true;
                    overlay.addEventListener('click', forceClose);
                }

                document.querySelectorAll('.mobile-nav-link').forEach(function(l) {
                    if (!l.__boundClose) {
                        l.__boundClose = true;
                        l.addEventListener('click', forceClose);
                    }
                });

                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1024) {
                        forceClose();
                    }
                });

                window.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        forceClose();
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', syncMobileNav);
            } else {
                syncMobileNav();
            }
            document.addEventListener('livewire:navigated', syncMobileNav);
        })();
    </script>
</header>
