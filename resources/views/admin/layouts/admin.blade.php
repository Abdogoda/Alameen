@props([
    'title' => null,
    'description' => null,
    'actions' => null,
    'contentClass' => 'flex-1 p-3.5 sm:p-6 lg:p-8 w-full min-w-0',
    'modals' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="alameen-dashboard-layout">
    <head>
        @include('partials.head')
        <link rel="stylesheet" href="/assets/css/admin.css" />
        <title>{{ filled($title ?? null) ? $title.' - '.($siteName ?? config('app.name', 'منصة الأمين')) : ($siteName ?? config('app.name', 'منصة الأمين')) }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="bg-[#F8FAFC] dark:bg-[#0B1120] text-[#334155] dark:text-slate-200 min-h-screen flex flex-col font-['Cairo'] antialiased selection:bg-[#EAF1FB] selection:text-[#0B2A8F] w-full max-w-full relative alameen-dashboard-layout"
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
                        try { localStorage.setItem('admin_sidebar_collapsed', 'true'); } catch (e) {}
                    } else {
                        document.documentElement.classList.remove('sidebar-collapsed');
                        try { localStorage.removeItem('admin_sidebar_collapsed'); } catch (e) {}
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

        @php
            $adminUser = auth('admin')->user();
            $pendingReceiptsCount = isset($pendingReceiptsCount) ? (int)$pendingReceiptsCount : (\Illuminate\Support\Facades\Schema::hasTable('payment_receipts') ? \App\Models\PaymentReceipt::where('status', 'pending')->count() : 0);
            $pendingTasksCount = isset($pendingTasksCount) ? (int)$pendingTasksCount : (\Illuminate\Support\Facades\Schema::hasTable('task_submissions') ? \App\Models\TaskSubmission::where('status', 'pending')->count() : 0);
            $openSupportTicketsCount = isset($openSupportTicketsCount) ? (int)$openSupportTicketsCount : (\Illuminate\Support\Facades\Schema::hasTable('support_tickets') ? \App\Models\SupportTicket::whereIn('status', ['open', 'waiting_admin'])->count() : 0);
        @endphp
        
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
            <!-- 1. ADMIN SIDEBAR NAVIGATION                    -->
            <!-- ============================================== -->
            <aside
                :class="{
                    'is-open': mobileSidebarOpen,
                    'is-collapsed': !desktopSidebarOpen && window.innerWidth >= 1024
                }"
                class="admin-sidebar-drawer z-[70] bg-white dark:bg-slate-900 border-e border-[#DEE8F7] dark:border-slate-800 flex flex-col shrink-0 shadow-2xl lg:shadow-none select-none overflow-hidden"
            >
                <!-- Platform Brand & Role Badge (Matches top header height: h-14 sm:h-16) -->
                <div class="h-14 sm:h-16 px-4 sm:px-5 border-b border-[#DEE8F7] dark:border-slate-800 flex items-center justify-between shrink-0 bg-white dark:bg-slate-900 sidebar-header">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 sm:gap-3 cursor-pointer min-w-0 group sidebar-brand-link">
                        <div class="size-9 sm:size-10 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 flex items-center justify-center p-1.5 shrink-0 border border-[#DEE8F7] dark:border-slate-700 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            <img src="{{ filled($siteLogo ?? null) ? $siteLogo : platform_logo() }}" alt="{{ filled($siteName ?? null) ? $siteName : platform_name() }}" class="size-full object-contain pointer-events-none dark:hidden logo-theme-light" />
                            <img src="{{ $siteLogoWhite ?? platform_logo_white() }}" alt="{{ filled($siteName ?? null) ? $siteName : platform_name() }}" class="size-full object-contain pointer-events-none hidden dark:block logo-theme-dark" />
                        </div>
                        <div class="min-w-0 sidebar-brand-text flex flex-col justify-center">
                            <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-sm sm:text-base leading-tight text-[#012383] dark:text-white block truncate">{{ filled($siteName ?? null) ? $siteName : platform_name() }}</span>
                            <span class="text-[9.5px] sm:text-[10px] font-bold text-[#0B2A8F] dark:text-blue-300 bg-[#EAF1FB] dark:bg-slate-800 px-2 py-0.5 rounded-full inline-block leading-none mt-0.5 truncate max-w-full">{{ __('لوحة الإدارة المركزية') }}</span>
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
                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 px-3 uppercase tracking-wider mb-1 sidebar-section-title">{{ __('التعليم وشؤون الطلاب') }}</p>
                        
                        <!-- الرئيسية (Active state) -->
                        <a
                            href="{{ route('admin.dashboard') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm shadow-xs transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#012383] text-white' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('لوحة الإحصائيات') }}' : ''"
                        >
                            <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('لوحة الإحصائيات') }}</span>
                        </a>

                        <!-- المستويات والفيديوهات -->
                        <a
                            href="{{ route('admin.courses.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.courses.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('المستويات والدروس') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.courses.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('المستويات والدروس') }}</span>
                        </a>

                        <!-- شؤون الطلاب والمسجلين -->
                        <a
                            href="{{ route('admin.students.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.students.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('شؤون الطلاب') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.students.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('شؤون الطلاب') }}</span>
                        </a>

                        <!-- تصحيح المهام والتاسكات -->
                        <a
                            href="{{ route('admin.tasks.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.tasks.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('تصحيح المهام') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.tasks.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('تصحيح المهام') }}</span>
                            @if(($pendingTasksCount ?? 0) > 0)
                                <span class="sidebar-label min-w-5 h-5 px-1.5 flex items-center justify-center text-[11px] font-black rounded-full shrink-0 transition-colors {{ request()->routeIs('admin.tasks.*') ? 'bg-white/20 text-white border border-white/30' : 'bg-blue-100 text-[#0B2A8F]' }}">{{ $pendingTasksCount }}</span>
                            @endif
                        </a>


                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 px-3 uppercase tracking-wider mt-3 mb-1 sidebar-section-title">{{ __('المالية والدعم الفني') }}</p>

                        <!-- إيصالات الدفع والتحويلات -->
                        <a
                            href="{{ route('admin.receipts.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.receipts.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('إيصالات الدفع') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.receipts.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('إيصالات الدفع') }}</span>
                            @if(($pendingReceiptsCount ?? 0) > 0)
                                <span class="sidebar-label min-w-5 h-5 px-1.5 flex items-center justify-center text-[11px] font-black rounded-full animate-pulse shrink-0 transition-colors {{ request()->routeIs('admin.receipts.*') ? 'bg-[#FEF3C7] shadow-xs text-amber-900' : 'bg-amber-100 text-amber-900' }}">{{ $pendingReceiptsCount }}</span>
                            @endif
                        </a>

                        <!-- تذاكر الدعم والرسائل المباشرة -->
                        <a
                            href="{{ route('admin.support.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.support.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('تذاكر الدعم والرسائل') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.support.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('تذاكر الدعم والرسائل') }}</span>
                            @if(($openSupportTicketsCount ?? 0) > 0)
                                <span class="sidebar-label min-w-5 h-5 px-1.5 flex items-center justify-center text-[11px] font-black rounded-full bg-[#F2AE2E] text-slate-900 animate-pulse shrink-0">
                                    {{ $openSupportTicketsCount }}
                                </span>
                            @endif
                        </a>

                        <!-- إدارة الإشعارات المركزية -->
                        <a
                            href="{{ route('admin.notifications.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.notifications.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('إدارة الإشعارات') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.notifications.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('إدارة الإشعارات') }}</span>
                        </a>

                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 px-3 uppercase tracking-wider mt-3 mb-1 sidebar-section-title">{{ __('فريق العمل والصلاحيات') }}</p>

                        <!-- إدارة المشرفين -->
                        <a
                            href="{{ route('admin.admins.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.admins.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('إدارة المشرفين') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.admins.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('إدارة المشرفين') }}</span>
                        </a>

                        <!-- الأدوار والصلاحيات -->
                        <a
                            href="{{ route('admin.roles.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('الأدوار والصلاحيات') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.roles.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('الأدوار والصلاحيات') }}</span>
                        </a>

                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 px-3 uppercase tracking-wider mt-3 mb-1 sidebar-section-title">{{ __('إعدادات المنصة والسجلات') }}</p>

                        <!-- إعدادات النظام -->
                        <a
                            href="{{ route('admin.settings.index') }}"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('إعدادات المنصة') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('إعدادات المنصة') }}</span>
                        </a>

                        <!-- سجلات النظام والـ Logs -->
                        <a
                            href="{{ route('log-viewer.index') }}"
                            target="_blank"
                            @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                            class="sidebar-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ request()->routeIs('log-viewer.*') ? 'bg-[#012383] text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white' }}"
                            :title="!desktopSidebarOpen && window.innerWidth >= 1024 ? '{{ __('سجلات النظام (Logs)') }}' : ''"
                        >
                            <svg class="size-5 shrink-0 {{ request()->routeIs('log-viewer.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="flex-1 text-start sidebar-label">{{ __('سجلات النظام (Logs)') }}</span>
                        </a>
                    </nav>
                </div>

                <!-- Bottom User Profile Card with Dropdown -->
                <div class="sidebar-bottom-section mt-auto shrink-0 border-t border-[#DEE8F7] dark:border-slate-800 p-3 sm:p-4 bg-white dark:bg-slate-900 relative" x-data="{ sidebarUserMenuOpen: false }" @click.outside="sidebarUserMenuOpen = false" @keydown.escape.window="sidebarUserMenuOpen = false">
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
                        class="absolute bottom-full inset-x-3 mb-2 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-2xl p-1.5 z-50 flex flex-col gap-1 font-['Cairo']"
                        style="display: none;"
                    >
                        <!-- Header with Name, Email & Role -->
                        <div class="px-3 py-2.5 border-b border-[#DEE8F7] dark:border-slate-800 mb-1 flex items-center gap-2.5">
                            <div class="size-9 rounded-xl bg-gradient-to-br from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden shadow-xs">
                                @if($adminUser?->avatarUrl())
                                    <img src="{{ $adminUser->avatarUrl() }}" alt="{{ $adminUser->name }}" class="size-full object-cover" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');" />
                                    <span class="hidden">{{ $adminUser?->initials() ?? 'أد' }}</span>
                                @else
                                    <span>{{ $adminUser?->initials() ?? 'أد' }}</span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1 text-start">
                                <p class="font-bold text-xs text-[#012383] dark:text-white truncate">{{ $adminUser?->name ?? __('المسؤول العام') }}</p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-400 truncate dir-ltr text-start">{{ $adminUser?->email ?? '' }}</p>
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 mt-0.5">
                                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $adminUser?->role_name ?? __('مشرف') }}
                                </span>
                            </div>
                        </div>

                        <!-- Menu Options -->
                        <a
                            href="{{ route('admin.profile.edit') }}"
                            @click="sidebarUserMenuOpen = false"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-blue-300 transition-colors {{ request()->routeIs('admin.profile.*') ? 'bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-300' : '' }}"
                        >
                            <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="flex-1 text-start">{{ __('الملف الشخصي والأمان') }}</span>
                            <span class="text-[10px] text-slate-400 font-normal">⚙</span>
                        </a>

                        <a
                            href="{{ route('admin.settings.index') }}"
                            @click="sidebarUserMenuOpen = false"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-blue-300 transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-300' : '' }}"
                        >
                            <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="flex-1 text-start">{{ __('إعدادات المنصة') }}</span>
                        </a>

                        <a
                            href="{{ route('dashboard') }}"
                            target="_blank"
                            @click="sidebarUserMenuOpen = false"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-blue-300 transition-colors"
                        >
                            <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span class="flex-1 text-start">{{ __('معاينة بوابة الطالب') }}</span>
                            <span class="text-[10px] text-slate-400 font-normal">↗</span>
                        </a>

                        <div class="border-t border-[#DEE8F7] dark:border-slate-800 my-1"></div>

                        <!-- Sign Out Form -->
                        <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                            @csrf
                            <button
                                type="submit"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-950/40 dark:hover:text-rose-300 transition-colors cursor-pointer text-start group"
                            >
                                <span class="flex items-center gap-2">
                                    <svg class="size-4 rtl:rotate-180 text-rose-500 group-hover:text-rose-600 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>{{ __('تسجيل خروج الإدارة') }}</span>
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
                        aria-label="{{ __('بيانات المشرف والقائمة') }}"
                        :aria-expanded="sidebarUserMenuOpen"
                    >
                        <!-- Avatar / Image -->
                        <div class="size-10 rounded-xl bg-gradient-to-br from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-xs shrink-0 overflow-hidden ring-1 ring-[#DEE8F7] dark:ring-slate-700 group-hover:scale-102 transition-transform">
                            @if($adminUser?->avatarUrl())
                                <img src="{{ $adminUser->avatarUrl() }}" alt="{{ $adminUser->name }}" class="size-full object-cover" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');" />
                                <span class="hidden">{{ $adminUser?->initials() ?? 'أد' }}</span>
                            @else
                                <span>{{ $adminUser?->initials() ?? 'أد' }}</span>
                            @endif
                        </div>

                        <!-- Name & Email -->
                        <div class="sidebar-user-text min-w-0 flex-1 text-start">
                            <span class="font-bold text-xs text-[#012383] dark:text-slate-100 truncate block leading-tight group-hover:text-[#0B2A8F] dark:group-hover:text-blue-300 transition-colors">
                                {{ $adminUser?->name ?? __('المسؤول العام') }}
                            </span>
                            <span class="text-[11px] text-slate-400 dark:text-slate-400 truncate block dir-ltr text-start mt-0.5">
                                {{ $adminUser?->email ?? '' }}
                            </span>
                        </div>

                        <!-- Dropdown Chevron -->
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
            <!-- 2. MAIN ADMIN CONTENT WORKSPACE                -->
            <!-- ============================================== -->
            <main class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC] dark:bg-[#0B1120]">
                
                <!-- Top Admin Header (Clean Sticky Topbar: No Page Titles or Action Buttons) -->
                <header class="admin-sticky-header h-14 sm:h-16 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-[#DEE8F7] dark:border-slate-800 px-3 sm:px-6 lg:px-8 flex items-center justify-between gap-3 sticky top-0 z-40 shadow-2xs shrink-0">
                    
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

                    <!-- Left: Admin Status & Profile Badge (No action buttons or page titles) -->
                    <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                        <!-- Theme Switcher for Admin (Light / Dark / Device) -->
                        <x-admin::theme-switcher />

                        <!-- Language Switcher for Admin -->
                        <x-admin::language-switcher />

                        <!-- Notifications Quick Access -->
                        <a href="{{ route('admin.notifications.index') }}" class="size-9 sm:size-10 rounded-xl bg-slate-50 dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F]/40 dark:hover:border-blue-400/40 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 hover:-translate-y-0.5 hover:shadow-xs active:scale-95 flex items-center justify-center text-[#012383] dark:text-slate-200 transition-all duration-200 relative cursor-pointer" title="{{ __('إدارة الإشعارات') }}">
                            <svg class="size-4 sm:size-5 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="absolute top-1.5 end-1.5 sm:top-2 sm:end-2 size-2 rounded-full bg-[#F2AE2E] animate-pulse"></span>
                        </a>

                        <!-- Admin User Info Badge & Dropdown -->
                        <div class="relative ps-1.5 sm:ps-2 border-s border-[#DEE8F7] dark:border-slate-700" x-data="{ adminHeaderMenuOpen: false }" @click.outside="adminHeaderMenuOpen = false" @keydown.escape.window="adminHeaderMenuOpen = false">
                            <button
                                type="button"
                                @click="adminHeaderMenuOpen = !adminHeaderMenuOpen"
                                class="flex items-center gap-2 sm:gap-2.5 p-1 sm:pe-3 sm:ps-1.5 rounded-2xl border transition-all duration-200 cursor-pointer group shrink-0 select-none !transform-none"
                                :class="adminHeaderMenuOpen
                                    ? 'bg-[#EAF1FB] dark:bg-slate-800 border-[#C5D7F2] dark:border-slate-700 shadow-xs ring-2 ring-[#012383]/10 dark:ring-blue-500/20'
                                    : 'border-transparent hover:border-[#DEE8F7] dark:hover:border-slate-700 hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800/60'"
                                aria-label="{{ __('حساب المشرف') }}"
                                :aria-expanded="adminHeaderMenuOpen"
                            >
                                <div class="size-8 sm:size-9 rounded-xl bg-gradient-to-br from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-xs shrink-0 overflow-hidden ring-1 ring-[#DEE8F7] dark:ring-slate-700 group-hover:scale-105 transition-transform">
                                    @if($adminUser?->avatarUrl())
                                        <img src="{{ $adminUser->avatarUrl() }}" alt="{{ $adminUser->name }}" class="size-full object-cover" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');" />
                                        <span class="hidden">{{ $adminUser?->initials() ?? 'أد' }}</span>
                                    @else
                                        <span>{{ $adminUser?->initials() ?? 'أد' }}</span>
                                    @endif
                                </div>
                                <div class="hidden md:flex flex-col text-start min-w-0 max-w-[150px] lg:max-w-[200px]">
                                    <span class="font-bold text-xs text-[#012383] dark:text-slate-100 leading-tight group-hover:text-[#0B2A8F] dark:group-hover:text-blue-300 transition-colors truncate">
                                        {{ $adminUser?->name ?? __('المسؤول العام') }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 dark:text-slate-400 truncate dir-ltr text-start">
                                        {{ $adminUser?->email ?? '' }}
                                    </span>
                                </div>
                                <svg
                                    class="size-3 text-slate-400 transition-transform duration-200 hidden sm:block shrink-0"
                                    :class="adminHeaderMenuOpen ? 'rotate-180 text-[#012383] dark:text-blue-300' : 'group-hover:text-[#012383] dark:group-hover:text-white'"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Admin Header Dropdown Menu -->
                            <div
                                x-show="adminHeaderMenuOpen"
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
                                    <div class="size-9 rounded-xl bg-gradient-to-br from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden shadow-xs">
                                        @if($adminUser?->avatarUrl())
                                            <img src="{{ $adminUser->avatarUrl() }}" alt="{{ $adminUser->name }}" class="size-full object-cover" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');" />
                                            <span class="hidden">{{ $adminUser?->initials() ?? 'أد' }}</span>
                                        @else
                                            <span>{{ $adminUser?->initials() ?? 'أد' }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1 text-start">
                                        <p class="font-bold text-xs text-[#012383] dark:text-white truncate">{{ $adminUser?->name ?? __('المسؤول العام') }}</p>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-400 truncate dir-ltr text-start">{{ $adminUser?->email ?? '' }}</p>
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 mt-0.5">
                                            <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                            {{ $adminUser?->role_name ?? __('مشرف') }}
                                        </span>
                                    </div>
                                </div>

                                <a
                                    href="{{ route('admin.profile.edit') }}"
                                    @click="adminHeaderMenuOpen = false"
                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-blue-300 transition-colors {{ request()->routeIs('admin.profile.*') ? 'bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-300' : '' }}"
                                >
                                    <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="flex-1 text-start">{{ __('الملف الشخصي والأمان') }}</span>
                                    <span class="text-[10px] text-slate-400 font-normal">⚙</span>
                                </a>

                                <a
                                    href="{{ route('admin.settings.index') }}"
                                    @click="adminHeaderMenuOpen = false"
                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-blue-300 transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-300' : '' }}"
                                >
                                    <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="flex-1 text-start">{{ __('إعدادات المنصة') }}</span>
                                </a>

                                <a
                                    href="{{ route('dashboard') }}"
                                    target="_blank"
                                    @click="adminHeaderMenuOpen = false"
                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-[#EAF1FB] hover:text-[#012383] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-blue-300 transition-colors"
                                >
                                    <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    <span class="flex-1 text-start">{{ __('معاينة بوابة الطالب') }}</span>
                                    <span class="text-[10px] text-slate-400 font-normal">↗</span>
                                </a>

                                <div class="border-t border-[#DEE8F7] dark:border-slate-800 my-1"></div>

                                <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-950/40 dark:hover:text-rose-300 transition-colors cursor-pointer text-start group"
                                    >
                                        <span class="flex items-center gap-2">
                                            <svg class="size-4 rtl:rotate-180 text-rose-500 group-hover:text-rose-600 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            <span>{{ __('تسجيل خروج الإدارة') }}</span>
                                        </span>
                                        <span class="text-[10px] text-rose-400 group-hover:text-rose-600 transition-colors font-semibold">↵</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </header>

                <!-- Page Content Area with Dedicated Responsive Page Header -->
                <div class="{{ $contentClass }}">
                    @if(isset($title) || isset($actions))
                        <div class="flex flex-col gap-3.5 sm:gap-4 pb-2 min-w-0 {{ isset($actions) ? '2xl:flex-row 2xl:items-center 2xl:justify-between' : '' }}">
                            @if(isset($title))
                                <div class="min-w-0 max-w-full">
                                    <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl lg:text-[26px] text-[#012383] dark:text-white leading-tight">
                                        {{ $title }}
                                    </h1>
                                    @if(isset($description))
                                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-3xl leading-relaxed">
                                            {{ $description }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            @if(isset($actions))
                                <div class="flex items-center gap-2 flex-wrap w-full 2xl:w-auto 2xl:justify-end min-w-0 shrink-0">
                                    {{ $actions }}
                                </div>
                            @endif
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>

        </div>

        {{-- Global Modals Container (Rendered directly under body to guarantee absolute viewport centering) --}}
        {{ $modals ?? '' }}
        <x-admin::confirm-modal />

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
        <script type="module" src="/assets/js/admin.js"></script>
    </body>
</html>

