<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        @include('partials.head')
        <link rel="stylesheet" href="/assets/css/admin.css" />
        <title>{{ $title ?? __('لوحة الإدارة المركزية | منصة الأمين') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F8FAFC] text-[#334155] min-h-screen flex flex-col font-['Cairo'] antialiased selection:bg-[#EAF1FB] selection:text-[#0B2A8F]" x-data="{ adminSidebarOpen: false }">
        
        <!-- Mobile Sidebar Overlay -->
        <div
            x-show="adminSidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="adminSidebarOpen = false"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 lg:hidden"
            style="display: none;"
        ></div>

        <div class="flex-1 flex flex-row min-h-screen w-full relative">
            
            <!-- ============================================== -->
            <!-- 1. ADMIN SIDEBAR NAVIGATION                    -->
            <!-- ============================================== -->
            <aside
                :class="{ 'translate-x-0': adminSidebarOpen, '-translate-x-full lg:translate-x-0 rtl:translate-x-full lg:rtl:translate-x-0': !adminSidebarOpen }"
                class="fixed lg:sticky top-0 start-0 z-50 w-[270px] h-screen bg-white border-e border-[#DEE8F7] flex flex-col justify-between p-5 shrink-0 transition-transform duration-300 shadow-sm lg:shadow-none overflow-y-auto"
            >
                <div class="flex flex-col gap-6">
                    <!-- Platform Brand & Role Badge -->
                    <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 cursor-pointer">
                            <div class="size-11 rounded-xl bg-[#EAF1FB] flex items-center justify-center p-2 shrink-0 border border-[#DEE8F7]">
                                <img src="/assets/images/logo.png" alt="منصة الأمين" class="size-full object-contain pointer-events-none" />
                            </div>
                            <div>
                                <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-xl leading-tight text-[#012383] block">منصة الأمين</span>
                                <span class="text-[11px] font-bold text-[#0B2A8F] bg-[#EAF1FB] px-2 py-0.5 rounded-full inline-block mt-0.5">لوحة الإدارة المركزية</span>
                            </div>
                        </a>
                        <button type="button" @click="adminSidebarOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Navigation Links Group -->
                    <nav class="flex flex-col gap-1.5">
                        <p class="text-[11px] font-bold text-slate-400 px-3 uppercase tracking-wider mb-1">{{ __('الرئيسية والتحليلات') }}</p>
                        
                        <!-- الرئيسية (Active state) -->
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm shadow-xs transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#012383] text-white' : 'text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383]' }}"
                        >
                            <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="flex-1 text-start">{{ __('لوحة الإحصائيات') }}</span>
                        </a>

                        <!-- المستويات والفيديوهات -->
                        <a href="{{ route('admin.courses.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.courses.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383]' }}">
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.courses.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span class="flex-1 text-start">{{ __('المستويات والدروس') }}</span>
                            <span class="px-2 py-0.5 text-[11px] font-bold bg-blue-50 text-[#0B2A8F] rounded-md">{{ __('ديناميك') }}</span>
                        </a>

                        <!-- إيصالات الدفع والتحويلات -->
                        <a href="{{ route('admin.receipts.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.receipts.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383]' }}">
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.receipts.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="flex-1 text-start">{{ __('إيصالات الدفع') }}</span>
                            <span class="px-2 py-0.5 text-[11px] font-bold bg-amber-100 text-amber-800 rounded-full animate-pulse">{{ __('١٢ معلق') }}</span>
                        </a>

                        <!-- تصحيح المهام والتاسكات -->
                        <a href="{{ route('admin.tasks.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.tasks.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383]' }}">
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.tasks.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span class="flex-1 text-start">{{ __('تصحيح المهام') }}</span>
                            <span class="px-2 py-0.5 text-[11px] font-bold bg-blue-100 text-[#0B2A8F] rounded-full">{{ __('٢٨ مهمة') }}</span>
                        </a>

                        <!-- إدارة الإشعارات المركزية -->
                        <a
                            href="{{ route('admin.notifications.index') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.notifications.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383]' }}"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.notifications.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="flex-1 text-start">{{ __('إدارة الإشعارات') }}</span>
                        </a>

                        <!-- الشهادات ومعيار الـ 95% -->
                        <a href="{{ route('admin.certificates.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.certificates.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383]' }}">
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.certificates.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span class="flex-1 text-start">{{ __('الشهادات المعتمدة') }}</span>
                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-800 rounded">٩٥٪</span>
                        </a>

                        <!-- شؤون الطلاب والمسجلين -->
                        <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.students.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383]' }}">
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.students.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span class="flex-1 text-start">{{ __('شؤون الطلاب') }}</span>
                        </a>

                        <!-- إعدادات النظام -->
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383]' }}">
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="flex-1 text-start">{{ __('إعدادات المنصة') }}</span>
                        </a>
                    </nav>
                </div>

                <!-- Bottom Actions (Preview Student LMS / Sign Out) -->
                <div class="border-t border-[#DEE8F7] pt-4 flex flex-col gap-2">
                    <a href="{{ route('dashboard') }}" target="_blank" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-[#EAF1FB] text-[#012383] text-xs font-bold transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="size-4 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            {{ __('معاينة بوابة الطالب') }}
                        </span>
                        <span class="text-[10px] text-slate-400">{{ __('فتح ↗') }}</span>
                    </a>

                    <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            {{ __('تسجيل خروج الإدارة') }}
                        </button>
                    </form>
                </div>
            </aside>

            <!-- ============================================== -->
            <!-- 2. MAIN ADMIN CONTENT WORKSPACE                -->
            <!-- ============================================== -->
            <main class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">
                
                <!-- Top Admin Header -->
                <header class="h-20 bg-white border-b border-[#DEE8F7] px-4 sm:px-8 flex items-center justify-between gap-4 sticky top-0 z-30">
                    
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            @click="adminSidebarOpen = true"
                            class="lg:hidden p-2 text-slate-500 hover:text-[#012383] rounded-xl hover:bg-slate-50 border border-slate-200 cursor-pointer"
                        >
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        
                        <div>
                            <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] leading-tight">
                                {{ $title ?? __('لوحة التحكم والإشراف الأكاديمي') }}
                            </h2>
                            <p class="text-[11px] text-slate-500 hidden sm:block">
                                {{ __('إدارة شاملة لبرنامج التخاطب، المشتركين، والموافقات المالية') }}
                            </p>
                        </div>
                    </div>

                    <!-- Left: Admin Status & Profile Badge -->
                    <div class="flex items-center gap-3">
                        
                        <!-- Language Switcher for Admin -->
                        <x-admin::language-switcher />

                        <!-- Notifications Quick Access -->
                        <a href="{{ route('admin.notifications.index') }}" class="size-10 rounded-xl bg-slate-50 border border-[#DEE8F7] hover:bg-[#EAF1FB] flex items-center justify-center text-[#012383] transition-colors relative cursor-pointer" title="{{ __('إدارة الإشعارات') }}">
                            <svg class="size-5 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="absolute top-2 end-2 size-2 rounded-full bg-[#F2AE2E] animate-pulse"></span>
                        </a>

                        <!-- Admin User Info Badge -->
                        @php
                            $adminUser = auth('admin')->user();
                        @endphp
                        <div class="flex items-center gap-2.5 ps-2 border-s border-[#DEE8F7]">
                            <div class="size-10 rounded-xl bg-gradient-to-br from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-sm shadow-xs">
                                {{ $adminUser?->initials() ?? 'أد' }}
                            </div>
                            <div class="hidden md:flex flex-col text-start">
                                <span class="font-bold text-xs text-[#012383] leading-tight">
                                    {{ $adminUser?->name ?? __('المسؤول العام') }}
                                </span>
                                <span class="text-[10px] font-semibold text-emerald-600 flex items-center gap-1">
                                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $adminUser?->role_name ?? __('مشرف') }}
                                </span>
                            </div>
                        </div>

                    </div>
                </header>

                <!-- Page Content Area -->
                <div class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                    {{ $slot }}
                </div>

                <!-- Admin Footer -->
                <footer class="h-14 border-t border-[#DEE8F7] bg-white px-4 sm:px-8 flex items-center justify-between text-xs text-slate-500">
                    <span>{{ __('منصة الأمين التعليمية • لوحة التحكم المركزية v2.0') }}</span>
                    <span class="hidden sm:inline">{{ __('نظام الفيديوهات المتزامنة والتقييم السريري') }}</span>
                </footer>
            </main>

        </div>

        <script type="module" src="/assets/js/admin.js"></script>
    </body>
</html>
