@props([
    'active' => 'home',
])

<!-- UNIFIED MASTER HEADER (Sticky at Top) -->
<header class="w-full bg-[#FAFCFF]/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-[#D7E3F5] dark:border-slate-800 flex items-center justify-between px-3.5 sm:px-6 lg:px-12 xl:px-20 2xl:px-28 py-2.5 sm:py-3.5 shrink-0 sticky top-0 z-50 shadow-xs transition-colors" id="main-header" x-data="{ mobileNavOpen: false }">
    
    <!-- Brand Identity -->
    <a href="{{ route('home') }}" class="flex gap-2 sm:gap-3 items-center shrink-0 cursor-pointer">
        <div class="size-8 sm:size-10 lg:size-12 shrink-0 relative flex items-center justify-center transition-all">
            <img src="/assets/images/logo.png" alt="{{ config('app.name', 'منصة مركز الأمين') }}" class="size-full object-contain pointer-events-none" />
        </div>
        <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-lg sm:text-2xl lg:text-3xl leading-tight text-[#012383] dark:text-white whitespace-nowrap tracking-tight">
            {{ __('brand.name') }}
        </span>
    </a>

@php
    $headerMenuItems = app(\App\Services\Cms\MenuService::class)->getMenu('header');
@endphp

    <!-- Main Navigation Links (Desktop) -->
    <nav class="hidden lg:flex gap-[6px] xl:gap-[12px] items-center shrink-0">
        @if($headerMenuItems->isNotEmpty())
            @foreach($headerMenuItems as $item)
                <a
                    href="{{ $item->resolved_url }}"
                    target="{{ $item->target }}"
                    class="px-3.5 py-2 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 dark:hover:bg-slate-800/60"
                >
                    {{ $item->title }}
                </a>
            @endforeach
        @else
            <!-- Fallback Static Nav Links -->
            <a href="{{ route('home') }}#home" class="px-4 py-2 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F]">
                {{ __('landing.navHome') }}
            </a>
            <a href="{{ route('courses.index') }}" class="px-3.5 py-2 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F]">
                {{ __('landing.navCourses') }}
            </a>
            <a href="{{ route('home') }}#about" class="px-3.5 py-2 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F]">
                {{ __('landing.navAbout') }}
            </a>
            <a href="{{ route('reviews.index') }}" class="px-3.5 py-2 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F]">
                {{ __('landing.navTestimonials') }}
            </a>
            <a href="{{ route('contact') }}" class="px-3.5 py-2 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] text-[#012383] dark:text-slate-300 hover:text-[#0B2A8F]">
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
                {{ __('ui.لوحة الإدارة') }}
            </a>
        @elseif (auth()->check())
            <a
                href="{{ route('dashboard') }}"
                class="bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center px-3 sm:px-5 h-[34px] sm:h-[40px] rounded-full !text-white text-xs sm:text-sm font-bold font-['Cairo'] transition-all shadow-sm hover:shadow-md cursor-pointer whitespace-nowrap"
                style="color: #ffffff !important;"
            >
                <span class="text-white" style="color: #ffffff !important;">{{ __('ui.بوابة الطالب') }}</span>
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
            class="lg:hidden flex items-center justify-center size-[34px] sm:size-[38px] rounded-xl bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-300 hover:bg-[#DEE8F7] dark:hover:bg-slate-700 transition-colors cursor-pointer"
        >
            <svg x-show="!mobileNavOpen" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="mobileNavOpen" x-cloak class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

    </div>

    <!-- Mobile Full-Height Navigation Menu -->
    <div
        id="mobile-nav-menu"
        x-show="mobileNavOpen"
        x-cloak
        @click.outside="mobileNavOpen = false"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 -translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-3"
        class="lg:hidden w-full h-[calc(100dvh-100%)] min-h-[calc(100dvh-100%)] bg-[#FAFCFF] dark:bg-slate-900 border-b border-[#D7E3F5] dark:border-slate-800 px-5 pt-4 pb-8 flex flex-col justify-between absolute top-full start-0 z-50 shadow-2xl overflow-y-auto"
    >
        <!-- Nav Links -->
        <div class="flex flex-col gap-1.5">
            <a
                href="{{ route('home') }}#home"
                @click="mobileNavOpen = false"
                class="mobile-nav-link flex items-center justify-between px-4 py-3 rounded-2xl font-['Cairo'] font-bold text-base transition-all {{ $active === 'home' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 shadow-xs' : 'text-[#012383] dark:text-slate-300 hover:bg-[#EAF1FB]/50' }}"
            >
                <span>{{ __('landing.navHome') }}</span>
                <span class="text-xs opacity-50 rtl:rotate-180">→</span>
            </a>

            <a
                href="{{ route('courses.index') }}"
                @click="mobileNavOpen = false"
                class="mobile-nav-link flex items-center justify-between px-4 py-3 rounded-2xl font-['Cairo'] font-bold text-base transition-all {{ $active === 'courses' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 shadow-xs' : 'text-[#012383] dark:text-slate-300 hover:bg-[#EAF1FB]/50' }}"
            >
                <span>{{ __('landing.navCourses') }}</span>
                <span class="text-xs opacity-50 rtl:rotate-180">→</span>
            </a>

            <a
                href="{{ route('home') }}#about"
                @click="mobileNavOpen = false"
                class="mobile-nav-link flex items-center justify-between px-4 py-3 rounded-2xl font-['Cairo'] font-bold text-base transition-all {{ $active === 'about' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 shadow-xs' : 'text-[#012383] dark:text-slate-300 hover:bg-[#EAF1FB]/50' }}"
            >
                <span>{{ __('landing.navAbout') }}</span>
                <span class="text-xs opacity-50 rtl:rotate-180">→</span>
            </a>

            <a
                href="{{ route('reviews.index') }}"
                @click="mobileNavOpen = false"
                class="mobile-nav-link flex items-center justify-between px-4 py-3 rounded-2xl font-['Cairo'] font-bold text-base transition-all {{ $active === 'reviews' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 shadow-xs' : 'text-[#012383] dark:text-slate-300 hover:bg-[#EAF1FB]/50' }}"
            >
                <span>{{ __('landing.navTestimonials') }}</span>
                <span class="text-xs opacity-50 rtl:rotate-180">→</span>
            </a>

            <a
                href="{{ route('contact') }}"
                @click="mobileNavOpen = false"
                class="mobile-nav-link flex items-center justify-between px-4 py-3 rounded-2xl font-['Cairo'] font-bold text-base transition-all {{ $active === 'contact' ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 shadow-xs' : 'text-[#012383] dark:text-slate-300 hover:bg-[#EAF1FB]/50' }}"
            >
                <span>{{ __('landing.navContact') }}</span>
                <span class="text-xs opacity-50 rtl:rotate-180">→</span>
            </a>
        </div>

        <!-- Bottom Actions (Settings & Auth) -->
        <div class="flex flex-col gap-4 pt-4 border-t border-[#D7E3F5] dark:border-slate-800 mt-auto">
            <!-- Mobile Switchers Row -->
            <div class="flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('ui.الإعدادات') }}</span>
                <div class="flex items-center gap-2">
                    <x-theme-switcher variant="dropdown" />
                    <x-language-switcher variant="dropdown" />
                </div>
            </div>

            <!-- Auth Action -->
            @guest
                @if (Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="w-full text-center border-2 border-[#012383] dark:border-blue-400 text-[#012383] dark:text-blue-300 hover:bg-[#EAF1FB] dark:hover:bg-slate-800 py-3 rounded-2xl text-sm font-bold font-['Cairo'] transition-all shadow-xs"
                    >
                        {{ __('landing.createAccount') }}
                    </a>
                @endif
            @endguest
        </div>
    </div>

</header>

