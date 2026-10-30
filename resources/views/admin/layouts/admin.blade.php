@props([
    'title' => null,
    'description' => null,
    'actions' => null,
    'contentClass' => 'flex-1 p-3.5 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto min-w-0',
    'modals' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        @include('partials.head')
        <link rel="stylesheet" href="/assets/css/admin.css" />
        <title>{{ filled($title ?? null) ? $title.' - '.config('app.name', 'منصة الأمين') : config('app.name', 'منصة الأمين') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F8FAFC] dark:bg-[#0B1120] text-[#334155] dark:text-slate-200 min-h-screen flex flex-col font-['Cairo'] antialiased selection:bg-[#EAF1FB] selection:text-[#0B2A8F]" x-data="{ adminSidebarOpen: false }" @keydown.window.escape="adminSidebarOpen = false">
        @php
            $pendingReceiptsCount = isset($pendingReceiptsCount) ? (int)$pendingReceiptsCount : (\Illuminate\Support\Facades\Schema::hasTable('payment_receipts') ? \App\Models\PaymentReceipt::where('status', 'pending')->count() : 0);
            $pendingTasksCount = isset($pendingTasksCount) ? (int)$pendingTasksCount : (\Illuminate\Support\Facades\Schema::hasTable('task_submissions') ? \App\Models\TaskSubmission::where('status', 'pending')->count() : 0);
        @endphp
        
        <!-- Mobile Sidebar Overlay -->
        <div
            x-show="adminSidebarOpen"
            x-cloak
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="adminSidebarOpen = false"
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 lg:hidden"
            style="display: none;"
        ></div>

        <div class="flex-1 flex flex-row min-h-screen w-full relative">
            
            <!-- ============================================== -->
            <!-- 1. ADMIN SIDEBAR NAVIGATION                    -->
            <!-- ============================================== -->
            <aside
                :class="{ 'is-open': adminSidebarOpen }"
                class="admin-sidebar-drawer bg-white dark:bg-slate-900 border-e border-[#DEE8F7] dark:border-slate-800 flex flex-col justify-between p-4 sm:p-5 shrink-0 shadow-xl lg:shadow-none overflow-y-auto"
            >
                <div class="flex flex-col gap-5 sm:gap-6">
                    <!-- Platform Brand & Role Badge -->
                    <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 cursor-pointer">
                            <div class="size-11 rounded-xl bg-[#EAF1FB] dark:bg-white flex items-center justify-center p-2 shrink-0 border border-[#DEE8F7] dark:border-transparent shadow-xs">
                                <img src="/assets/images/logo.png" alt="منصة الأمين" class="size-full object-contain pointer-events-none" />
                            </div>
                            <div>
                                <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-xl leading-tight text-[#012383] dark:text-white block">منصة الأمين</span>
                                <span class="text-[11px] font-bold text-[#0B2A8F] dark:text-blue-300 bg-[#EAF1FB] dark:bg-slate-800 px-2 py-0.5 rounded-full inline-block mt-0.5">لوحة الإدارة المركزية</span>
                            </div>
                        </a>
                        <button type="button" @click="adminSidebarOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer" aria-label="{{ __('إغلاق القائمة') }}">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Navigation Links Group -->
                    <nav class="flex flex-col gap-1.5">
                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 px-3 uppercase tracking-wider mb-1">{{ __('الرئيسية والتحليلات') }}</p>
                        
                        <!-- الرئيسية (Active state) -->
                        <a
                            href="{{ route('admin.dashboard') }}"
                            @click="if (window.innerWidth < 1024) adminSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm shadow-xs transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#012383] text-white' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                        >
                            <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="flex-1 text-start">{{ __('لوحة الإحصائيات') }}</span>
                        </a>

                        <!-- المستويات والفيديوهات -->
                        <a
                            href="{{ route('admin.courses.index') }}"
                            @click="if (window.innerWidth < 1024) adminSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.courses.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.courses.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span class="flex-1 text-start">{{ __('المستويات والدروس') }}</span>
                        </a>

                        <!-- إيصالات الدفع والتحويلات -->
                        <a
                            href="{{ route('admin.receipts.index') }}"
                            @click="if (window.innerWidth < 1024) adminSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.receipts.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.receipts.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="flex-1 text-start">{{ __('إيصالات الدفع') }}</span>
                            @if(($pendingReceiptsCount ?? 0) > 0)
                                <span class="min-w-5 h-5 px-1.5 flex items-center justify-center text-[11px] font-black rounded-full animate-pulse shrink-0 transition-colors {{ request()->routeIs('admin.receipts.*') ? 'bg-[#FEF3C7] shadow-xs text-amber-900' : 'bg-amber-100 text-amber-900' }}">{{ $pendingReceiptsCount }}</span>
                            @endif
                        </a>

                        <!-- تصحيح المهام والتاسكات -->
                        <a
                            href="{{ route('admin.tasks.index') }}"
                            @click="if (window.innerWidth < 1024) adminSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.tasks.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.tasks.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span class="flex-1 text-start">{{ __('تصحيح المهام') }}</span>
                            @if(($pendingTasksCount ?? 0) > 0)
                                <span class="min-w-5 h-5 px-1.5 flex items-center justify-center text-[11px] font-black rounded-full shrink-0 transition-colors {{ request()->routeIs('admin.tasks.*') ? 'bg-white/20 text-white border border-white/30' : 'bg-blue-100 text-[#0B2A8F]' }}">{{ $pendingTasksCount }}</span>
                            @endif
                        </a>

                        <!-- إدارة الإشعارات المركزية -->
                        <a
                            href="{{ route('admin.notifications.index') }}"
                            @click="if (window.innerWidth < 1024) adminSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.notifications.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.notifications.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="flex-1 text-start">{{ __('إدارة الإشعارات') }}</span>
                        </a>

                        <!-- الشهادات ومعيار الـ 95% -->
                        <a
                            href="{{ route('admin.certificates.index') }}"
                            @click="if (window.innerWidth < 1024) adminSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.certificates.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.certificates.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span class="flex-1 text-start">{{ __('الشهادات المعتمدة') }}</span>
                        </a>

                        <!-- شؤون الطلاب والمسجلين -->
                        <a
                            href="{{ route('admin.students.index') }}"
                            @click="if (window.innerWidth < 1024) adminSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.students.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.students.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span class="flex-1 text-start">{{ __('شؤون الطلاب') }}</span>
                        </a>

                        <!-- إعدادات النظام -->
                        <a
                            href="{{ route('admin.settings.index') }}"
                            @click="if (window.innerWidth < 1024) adminSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="flex-1 text-start">{{ __('إعدادات المنصة') }}</span>
                        </a>

                        <!-- سجلات النظام والـ Logs -->
                        <a
                            href="{{ route('log-viewer.index') }}"
                            target="_blank"
                            @click="if (window.innerWidth < 1024) adminSidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('log-viewer.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('log-viewer.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="flex-1 text-start">{{ __('سجلات النظام (Logs)') }}</span>
                        </a>
                    </nav>
                </div>

                <!-- Bottom Actions (Preview Student LMS / Sign Out) -->
                <div class="border-t border-[#DEE8F7] dark:border-slate-800 pt-4 flex flex-col gap-2">
                    <a href="{{ route('dashboard') }}" target="_blank" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-[#EAF1FB] text-[#012383] text-xs font-bold transition-all duration-200 border border-slate-100 hover:border-[#DEE8F7] dark:bg-slate-800/80 dark:hover:bg-slate-700 dark:text-blue-300 dark:border-slate-700 group">
                        <span class="flex items-center gap-2">
                            <svg class="size-4 text-[#0B2A8F] dark:text-blue-400 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span>{{ __('معاينة بوابة الطالب') }}</span>
                        </span>
                        <span class="text-[10px] text-slate-400 group-hover:text-[#0B2A8F] bg-white group-hover:bg-[#DEE8F7]/60 dark:bg-slate-700/80 dark:text-slate-300 px-1.5 py-0.5 rounded-md border border-slate-200/60 group-hover:border-[#C5D7F2] dark:border-slate-600 transition-colors font-medium">{{ __('فتح ↗') }}</span>
                    </a>

                    <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between p-2.5 rounded-xl bg-rose-50/70 hover:bg-rose-100/90 text-rose-600 hover:text-rose-700 border border-rose-100/90 hover:border-rose-200 text-xs font-bold transition-all duration-200 cursor-pointer group shadow-2xs dark:bg-rose-950/25 dark:hover:bg-rose-950/50 dark:text-rose-400 dark:hover:text-rose-300 dark:border-rose-900/40 dark:hover:border-rose-800/60">
                            <span class="flex items-center gap-2">
                                <svg class="size-4 text-rose-500 group-hover:text-rose-600 rtl:rotate-180 transition-transform group-hover:-translate-x-0.5 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span>{{ __('تسجيل خروج الإدارة') }}</span>
                            </span>
                            <span class="text-[10px] text-rose-500 group-hover:text-rose-700 bg-white group-hover:bg-rose-200/70 dark:bg-rose-950/60 dark:text-rose-300 px-1.5 py-0.5 rounded-md border border-rose-200/70 group-hover:border-rose-300 dark:border-rose-900/60 transition-colors font-semibold">{{ __('خروج') }} ↵</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- ============================================== -->
            <!-- 2. MAIN ADMIN CONTENT WORKSPACE                -->
            <!-- ============================================== -->
            <main class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC] dark:bg-[#0B1120]">
                
                <!-- Top Admin Header (Always Sticky at Top) -->
                <header class="admin-sticky-header min-h-16 sm:min-h-20 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-[#DEE8F7] dark:border-slate-800 px-3 sm:px-6 lg:px-8 flex items-center justify-between gap-2 sm:gap-4 sticky top-0 z-40 shadow-xs">
                    
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <button
                            type="button"
                            @click="adminSidebarOpen = true"
                            class="lg:hidden p-2 text-slate-500 hover:text-[#012383] rounded-xl hover:bg-slate-50 border border-slate-200 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800 cursor-pointer shrink-0"
                            aria-label="{{ __('فتح القائمة الجانبية') }}"
                        >
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        
                        <div class="min-w-0">
                            <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-sm sm:text-base md:text-xl text-[#012383] dark:text-white leading-tight truncate">
                                {{ $title ?? __('لوحة التحكم والإشراف الأكاديمي') }}
                            </h2>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 hidden md:block truncate">
                                {{ $description ?? __('إدارة شاملة لبرنامج التخاطب، المشتركين، والموافقات المالية') }}
                            </p>
                        </div>
                    </div>

                    <!-- Left: Admin Status & Profile Badge -->
                    <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                        
                        @if (isset($actions))
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                {{ $actions }}
                            </div>
                        @endif

                        <!-- Theme Switcher for Admin (Light / Dark / Device) -->
                        <x-admin::theme-switcher />

                        <!-- Language Switcher for Admin -->
                        <x-admin::language-switcher />

                        <!-- Notifications Quick Access -->
                        <a href="{{ route('admin.notifications.index') }}" class="size-9 sm:size-10 rounded-xl bg-slate-50 dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 flex items-center justify-center text-[#012383] dark:text-slate-200 transition-colors relative cursor-pointer" title="{{ __('إدارة الإشعارات') }}">
                            <svg class="size-4 sm:size-5 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="absolute top-1.5 end-1.5 sm:top-2 sm:end-2 size-2 rounded-full bg-[#F2AE2E] animate-pulse"></span>
                        </a>

                        <!-- Admin User Info Badge -->
                        @php
                            $adminUser = auth('admin')->user();
                        @endphp
                        <div class="flex items-center gap-2 ps-1.5 sm:ps-2 border-s border-[#DEE8F7] dark:border-slate-700">
                            <div class="size-9 sm:size-10 rounded-xl bg-gradient-to-br from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-xs shrink-0">
                                {{ $adminUser?->initials() ?? 'أد' }}
                            </div>
                            <div class="hidden md:flex flex-col text-start">
                                <span class="font-bold text-xs text-[#012383] dark:text-slate-100 leading-tight">
                                    {{ $adminUser?->name ?? __('المسؤول العام') }}
                                </span>
                                <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $adminUser?->role_name ?? __('مشرف') }}
                                </span>
                            </div>
                        </div>

                    </div>
                </header>

                <!-- Page Content Area -->
                <div class="{{ $contentClass }}">
                    {{ $slot }}
                </div>

                {{ $modals ?? '' }}
            </main>

        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
        <script type="module" src="/assets/js/admin.js"></script>
    </body>
</html>

