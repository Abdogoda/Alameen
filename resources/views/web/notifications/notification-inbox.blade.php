<div class="flex flex-col gap-6 w-full text-start">

    <!-- ============================================== -->
    <!-- 1. BREADCRUMBS & PAGE HEADER                   -->
    <!-- ============================================== -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
        <div class="flex flex-col gap-1 items-start text-start">
            <nav class="flex items-center gap-2 text-xs font-semibold text-[#0B2A8F] dark:text-blue-400" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}" class="hover:underline flex items-center gap-1" wire:navigate>
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('لوحة الطالب') }}</span>
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-slate-500 dark:text-slate-400">{{ __('الإشعارات والدعم') }}</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-[#012383] dark:text-white font-bold">{{ __('صندوق الإشعارات') }}</span>
            </nav>

            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-[#012383] dark:text-white leading-tight">
                    {{ __('مركز الإشعارات والتواصل') }}
                </h1>
                @if ($unreadCount > 0)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-900/60 shadow-2xs">
                        <span class="size-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <span>{{ $unreadCount }} {{ __('تنبيه غير مقروء') }}</span>
                    </span>
                @endif
            </div>

            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-3xl">
                {{ __('تابع أحدث الإشعارات الأكاديمية والسريرية، مستجدات المحاضرات والشهادات، وتواصل مباشرة مع الدعم والمشرفين.') }}
            </p>
        </div>

        @if ($unreadCount > 0)
            <div class="flex items-center gap-2.5 shrink-0">
                <button
                    type="button"
                    wire:click="markAllAsRead"
                    class="h-10 px-4 rounded-xl text-xs font-bold bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#0B2A8F] dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-blue-300 border border-[#C5D7F2] dark:border-slate-700 transition-all flex items-center gap-2 shadow-2xs cursor-pointer active:scale-95"
                >
                    <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ __('تحديد الكل كمقروء') }}</span>
                </button>
            </div>
        @endif
    </div>

    <!-- ============================================== -->
    <!-- UNIFIED HUB TABS (Internal Navigation Switcher)-->
    <!-- ============================================== -->
    <div class="w-full">
        <x-hub-tabs active="notifications" :unread-notifications="$unreadCount" :unread-support="$unreadSupportCount" />
    </div>

    <!-- ============================================== -->
    <!-- 2. STATS OVERVIEW CARDS                        -->
    <!-- ============================================== -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4 w-full">
        
        <!-- Card 1: All Notifications -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between gap-3 relative overflow-hidden group hover:border-[#0B2A8F]/30 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('إجمالي التنبيهات') }}</span>
                <div class="size-9 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0 border border-[#DEE8F7] dark:border-slate-700">
                    <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-[#012383] dark:text-white leading-none">
                    {{ $totalCount }}
                </span>
                <span class="text-xs font-semibold text-slate-400">{{ __('إشعار مسجل') }}</span>
            </div>
        </div>

        <!-- Card 2: Unread -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between gap-3 relative overflow-hidden group hover:border-rose-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('تنبيهات غير مقروءة') }}</span>
                <div class="size-9 rounded-xl {{ $unreadCount > 0 ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400 border-rose-200 dark:border-rose-900' : 'bg-slate-50 text-slate-500 dark:bg-slate-800 dark:text-slate-400 border-slate-200 dark:border-slate-700' }} flex items-center justify-center shrink-0 border">
                    <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl {{ $unreadCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-400 dark:text-slate-500' }} leading-none">
                    {{ $unreadCount }}
                </span>
                <span class="text-xs font-semibold text-slate-400">{{ __('بحاجة للمعاينة') }}</span>
            </div>
        </div>

        <!-- Card 3: Academic / Clinical -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between gap-3 relative overflow-hidden group hover:border-indigo-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('إشعارات أكاديمية وتدريب') }}</span>
                <div class="size-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-100 dark:border-indigo-900">
                    <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-indigo-600 dark:text-indigo-400 leading-none">
                    {{ $academicCount }}
                </span>
                <span class="text-xs font-semibold text-slate-400">{{ __('دورات ومهام') }}</span>
            </div>
        </div>

        <!-- Card 4: Security & System -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between gap-3 relative overflow-hidden group hover:border-emerald-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('أمان الحساب والنظام') }}</span>
                <div class="size-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900">
                    <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-emerald-600 dark:text-emerald-400 leading-none">
                    {{ $securityCount + $systemCount }}
                </span>
                <span class="text-xs font-semibold text-slate-400">{{ __('تنبيه أمان ونظام') }}</span>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 3. FILTER TABS & LIVE SEARCH BAR               -->
    <!-- ============================================== -->
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-3 sm:p-4 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 w-full">
        
        <!-- Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 text-xs font-bold scrollbar-none touch-pan-x">
            @php
                $tabs = [
                    'all' => ['label' => __('كافة الإشعارات'), 'count' => $counts['all'] ?? $totalCount],
                    'unread' => ['label' => __('غير مقروءة'), 'count' => $counts['unread'] ?? $unreadCount],
                    'info' => ['label' => __('معلومات وأكاديمي'), 'count' => $counts['info'] ?? 0],
                    'security' => ['label' => __('الأمان'), 'count' => $counts['security'] ?? 0],
                    'system' => ['label' => __('النظام'), 'count' => $counts['system'] ?? 0],
                    'warning' => ['label' => __('تحذيرات'), 'count' => $counts['warning'] ?? 0],
                ];
            @endphp

            @foreach ($tabs as $key => $tab)
                <button
                    type="button"
                    wire:click="setFilter('{{ $key }}')"
                    class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap cursor-pointer flex items-center gap-1.5 {{ $filter === $key ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-[#EAF1FB] dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white bg-slate-50 dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700/80' }}"
                >
                    <span>{{ $tab['label'] }}</span>
                    <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $filter === $key ? 'bg-white/20 text-white font-extrabold' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                        {{ $tab['count'] }}
                    </span>
                </button>
            @endforeach
        </div>

        <!-- Livewire Search Input -->
        <div class="relative w-full md:w-80 shrink-0">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="{{ __('ابحث في عنوان أو نص الإشعار...') }}"
                class="w-full h-10 ps-10 pe-9 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs sm:text-sm bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:bg-white dark:focus:bg-slate-900 focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all outline-hidden font-medium"
            />
            <svg class="size-4 text-slate-400 absolute start-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            @if ($search !== '')
                <button
                    type="button"
                    wire:click="$set('search', '')"
                    class="absolute end-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer"
                    title="{{ __('مسح البحث') }}"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            @endif
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 4. NOTIFICATIONS LIST CONTAINER                -->
    <!-- ============================================== -->
    <div class="divide-y divide-[#DEE8F7] dark:divide-slate-800 rounded-2xl sm:rounded-3xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xs overflow-hidden w-full">
        @forelse ($notifications as $notification)
            @php
                $isUnread = $notification->read_at === null;
                $type = $notification->data['type'] ?? 'info';
                $title = $notification->data['title'] ?? '';
                $body = $notification->data['body'] ?? '';
                $actionUrl = $notification->data['action_url'] ?? null;
                $actionText = $notification->data['action_text'] ?? __('عرض التفاصيل');

                $typeBadge = match($type) {
                    'success' => ['bg' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800', 'label' => __('إنجاز')],
                    'warning' => ['bg' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border-amber-200 dark:border-amber-800', 'label' => __('تنبيه')],
                    'error' => ['bg' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border-rose-200 dark:border-rose-800', 'label' => __('تنبيه عاجل')],
                    'security' => ['bg' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800', 'label' => __('أمان الحساب')],
                    'system' => ['bg' => 'bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400 border-purple-200 dark:border-purple-800', 'label' => __('تحديث نظام')],
                    default => ['bg' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 border-blue-200 dark:border-blue-800', 'label' => __('إشعار')],
                };

                $iconColor = match($type) {
                    'success' => 'bg-emerald-100/70 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800',
                    'warning' => 'bg-amber-100/70 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800',
                    'error' => 'bg-rose-100/70 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800',
                    'security' => 'bg-indigo-100/70 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800',
                    'system' => 'bg-purple-100/70 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400 border border-purple-200 dark:border-purple-800',
                    default => 'bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-400 border border-[#DEE8F7] dark:border-slate-700',
                };
            @endphp

            <div class="p-4 sm:p-5 flex flex-col md:flex-row md:items-start justify-between gap-4 transition-all hover:bg-slate-50/70 dark:hover:bg-slate-800/40 {{ $isUnread ? 'bg-gradient-to-r from-blue-50/30 via-white to-white dark:from-blue-950/20 dark:via-slate-900 dark:to-slate-900 border-s-4 border-s-[#0B2A8F]' : '' }}">
                
                <div class="flex items-start gap-3.5 sm:gap-4 flex-1 min-w-0">
                    
                    <!-- Icon Capsule -->
                    <div class="size-11 sm:size-12 rounded-2xl {{ $iconColor }} flex items-center justify-center shrink-0 shadow-2xs">
                        @if ($type === 'security')
                            <svg class="size-5 sm:size-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        @elseif ($type === 'success')
                            <svg class="size-5 sm:size-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif ($type === 'warning')
                            <svg class="size-5 sm:size-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        @elseif ($type === 'error')
                            <svg class="size-5 sm:size-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif ($type === 'system')
                            <svg class="size-5 sm:size-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        @else
                            <svg class="size-5 sm:size-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @endif
                    </div>

                    <!-- Notification Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold border {{ $typeBadge['bg'] }}">
                                {{ $typeBadge['label'] }}
                            </span>
                            
                            @if ($isUnread)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-900">
                                    <span class="size-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    <span>{{ __('جديد') }}</span>
                                </span>
                            @endif

                            <span class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1 ms-auto sm:ms-0">
                                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </span>
                        </div>

                        <h2 class="text-sm sm:text-base font-bold text-slate-800 dark:text-slate-100 leading-snug break-words">
                            {{ $title }}
                        </h2>

                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mt-1.5 leading-relaxed break-words">
                            {{ $body }}
                        </p>

                        @if ($actionUrl)
                            <div class="mt-3">
                                <a
                                    href="{{ $actionUrl }}"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-[#012383] hover:text-[#0B2A8F] dark:text-blue-400 dark:hover:text-blue-300 hover:underline"
                                    wire:navigate
                                >
                                    <span>{{ $actionText }}</span>
                                    <span class="rtl:rotate-180">→</span>
                                </a>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Action Controls -->
                <div class="flex items-center md:flex-col gap-2 shrink-0 justify-end md:justify-start pt-2 md:pt-0 border-t md:border-t-0 border-[#DEE8F7] dark:border-slate-800">
                    @if ($isUnread)
                        <button
                            type="button"
                            wire:click="markAsRead('{{ $notification->id }}')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#0B2A8F] dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-blue-300 border border-[#C5D7F2] dark:border-slate-700 transition cursor-pointer active:scale-95 shadow-2xs"
                            title="{{ __('تحديد كمقروء') }}"
                        >
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('تحديد كمقروء') }}</span>
                        </button>
                    @endif

                    <button
                        type="button"
                        wire:click="delete('{{ $notification->id }}')"
                        wire:confirm="{{ __('هل أنت متأكد من رغبتك في حذف هذا الإشعار؟') }}"
                        class="p-2 text-xs rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 border border-transparent hover:border-rose-200 dark:hover:border-rose-900 transition cursor-pointer"
                        title="{{ __('حذف الإشعار') }}"
                    >
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>

            </div>

        @empty
            <div class="py-16 sm:py-20 text-center px-4 w-full flex flex-col items-center justify-center">
                <div class="size-16 sm:size-20 rounded-3xl bg-[#EAF1FB] dark:bg-slate-800/80 border border-[#DEE8F7] dark:border-slate-700 flex items-center justify-center text-[#012383] dark:text-blue-400 mb-4 shadow-sm">
                    <svg class="size-8 sm:size-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                
                <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100 font-['Baloo_Bhaijaan_2']">
                    @if ($search !== '')
                        {{ __('لا توجد نتائج مطابقة لبحثك') }}
                    @elseif ($filter === 'unread')
                        {{ __('رائع! لا توجد إشعارات غير مقروءة حالياً') }}
                    @else
                        {{ __('صندوق الإشعارات فارغ حالياً') }}
                    @endif
                </h3>
                
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-md mx-auto leading-relaxed">
                    @if ($search !== '')
                        {{ __('لم نتمكن من العثور على أي إشعار يحتوي على كلمة البحث، جرّب كلمة أخرى أو امسح البحث.') }}
                    @else
                        {{ __('ستصلك هنا كافة التنبيهات المتعلقة ببرامجك التدريبية والشهادات والتواصل مع المشرفين فور صدورها.') }}
                    @endif
                </p>

                @if ($search !== '' || $filter !== 'all')
                    <button
                        type="button"
                        wire:click="setFilter('all'); $set('search', '');"
                        class="mt-4 px-4 py-2 rounded-xl text-xs font-bold bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#012383] dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-blue-300 border border-[#DEE8F7] dark:border-slate-700 transition cursor-pointer"
                    >
                        {{ __('إعادة ضبط التصفية وعرض الكل') }}
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    <!-- ============================================== -->
    <!-- 5. PAGINATION                                  -->
    <!-- ============================================== -->
    @if ($notifications->hasPages())
        <div class="mt-2 w-full">
            {{ $notifications->links() }}
        </div>
    @endif

</div>
