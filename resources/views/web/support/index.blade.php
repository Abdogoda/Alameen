<x-layouts::app :title="__('مركز الدعم والمحادثات')">
    <div class="flex flex-col gap-5 sm:gap-6 w-full text-start">
        
        <!-- Header & Breadcrumbs -->
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
                    <span class="text-[#012383] dark:text-blue-300 font-bold">{{ __('الدعم والرسائل') }}</span>
                </nav>

                <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-[#012383] dark:text-white leading-tight">
                    {{ __('مركز الدعم والمحادثات المباشرة') }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-3xl leading-relaxed">
                    {{ __('تواصل مباشرة مع المشرفين السريريين والإدارة، وتابع حلول استفساراتك باحترافية.') }}
                </p>
            </div>

            <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                <a href="{{ route('support.create') }}" class="h-10 px-5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs sm:text-sm font-bold shadow-xs transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>{{ __('فتح تذكرة دعم جديدة') }}</span>
                </a>
            </div>
        </div>

        <!-- UNIFIED HUB TABS (Internal Navigation Switcher) -->
        <div class="w-full">
            <x-hub-tabs active="support" :unread-notifications="$unreadNotificationsCount ?? 0" :unread-support="$unreadSupportCount ?? 0" />
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-2xs">
                <svg class="size-5 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter Tabs & Search Bar -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-3 sm:p-4 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 sm:gap-4">
            <!-- Tabs (Horizontal swipeable on mobile) -->
            <div class="filter-tabs flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 md:pb-0 text-xs sm:text-sm font-bold w-full md:w-auto">
                <a href="{{ route('support.index', ['status' => 'all', 'search' => $search]) }}" class="filter-tab shrink-0 min-w-max px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $status === 'all' ? 'bg-[#012383] text-white shadow-xs font-bold border border-transparent' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 bg-slate-50 dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700/80' }}">
                    <span>{{ __('الكل') }}</span>
                    <span class="ms-1.5 px-2 py-0.5 text-[11px] rounded-full {{ $status === 'all' ? 'bg-white/20 text-white font-extrabold' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">{{ $counts['all'] }}</span>
                </a>
                <a href="{{ route('support.index', ['status' => 'open', 'search' => $search]) }}" class="filter-tab shrink-0 min-w-max px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $status === 'open' ? 'bg-[#012383] text-white shadow-xs font-bold border border-transparent' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 bg-slate-50 dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700/80' }}">
                    <span>{{ __('قيد المتابعة') }}</span>
                    <span class="ms-1.5 px-2 py-0.5 text-[11px] rounded-full {{ $status === 'open' ? 'bg-white/20 text-white font-extrabold' : 'bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300' }}">{{ $counts['open'] }}</span>
                </a>
                <a href="{{ route('support.index', ['status' => 'waiting_student', 'search' => $search]) }}" class="filter-tab shrink-0 min-w-max px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $status === 'waiting_student' ? 'bg-[#012383] text-white shadow-xs font-bold border border-transparent' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 bg-slate-50 dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700/80' }}">
                    <span>{{ __('بانتظار ردك') }}</span>
                    @if($counts['waiting_student'] > 0)
                        <span class="ms-1.5 px-2 py-0.5 text-[11px] rounded-full bg-[#F2AE2E] text-slate-900 font-extrabold animate-pulse">{{ $counts['waiting_student'] }}</span>
                    @else
                        <span class="ms-1.5 px-2 py-0.5 text-[11px] rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">0</span>
                    @endif
                </a>
                <a href="{{ route('support.index', ['status' => 'resolved', 'search' => $search]) }}" class="filter-tab shrink-0 min-w-max px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $status === 'resolved' ? 'bg-[#012383] text-white shadow-xs font-bold border border-transparent' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 bg-slate-50 dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700/80' }}">
                    <span>{{ __('تم الحل') }}</span>
                    <span class="ms-1.5 px-2 py-0.5 text-[11px] rounded-full {{ $status === 'resolved' ? 'bg-white/20 text-white font-extrabold' : 'bg-teal-100 dark:bg-teal-950 text-teal-800 dark:text-teal-300' }}">{{ $counts['resolved'] }}</span>
                </a>
                <a href="{{ route('support.index', ['status' => 'closed', 'search' => $search]) }}" class="filter-tab shrink-0 min-w-max px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $status === 'closed' ? 'bg-[#012383] text-white shadow-xs font-bold border border-transparent' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 bg-slate-50 dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700/80' }}">
                    <span>{{ __('مغلقة') }}</span>
                    <span class="ms-1.5 px-2 py-0.5 text-[11px] rounded-full {{ $status === 'closed' ? 'bg-white/20 text-white font-extrabold' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">{{ $counts['closed'] }}</span>
                </a>
            </div>

            <!-- Search input -->
            <form method="GET" action="{{ route('support.index') }}" class="relative w-full md:w-72 shrink-0">
                <input type="hidden" name="status" value="{{ $status }}">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="{{ __('ابحث بالموضوع أو رقم التذكرة...') }}"
                    class="w-full h-10 ps-10 pe-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs sm:text-sm bg-slate-50 dark:bg-slate-800 dark:text-white focus:bg-white dark:focus:bg-slate-850 focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all outline-hidden font-medium"
                />
                <svg class="size-4 text-slate-400 absolute start-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </form>
        </div>

        <!-- Ticket Cards List -->
        @if($tickets->count() > 0)
            <div class="grid grid-cols-1 gap-3.5 sm:gap-4">
                @foreach($tickets as $ticket)
                    <a
                        href="{{ route('support.show', $ticket->id) }}"
                        class="bg-white dark:bg-slate-900 hover:bg-slate-50/70 dark:hover:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-800 hover:border-[#0B2A8F]/40 dark:hover:border-blue-500/40 rounded-2xl p-4 sm:p-5 transition-all shadow-xs hover:shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-3 sm:gap-4 text-start group relative overflow-hidden"
                    >
                        @if($ticket->unread_count > 0)
                            <div class="absolute top-0 start-0 w-1.5 h-full bg-[#F2AE2E]"></div>
                        @endif

                        <div class="flex-1 min-w-0 flex flex-col gap-2">
                            <!-- Badges row -->
                            <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                                <span class="font-mono text-xs font-black text-[#012383] dark:text-blue-300 bg-[#EAF1FB] dark:bg-slate-800 px-2.5 py-1 rounded-lg border border-[#DEE8F7] dark:border-slate-700">
                                    #{{ $ticket->ticket_number }}
                                </span>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-lg border {{ $ticket->getStatusBadgeClass() }}">
                                    {{ $ticket->getStatusLabel() }}
                                </span>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-lg border {{ $ticket->getPriorityBadgeClass() }}">
                                    {{ $ticket->getPriorityLabel() }}
                                </span>
                                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md">
                                    {{ $ticket->getCategoryLabel() }}
                                </span>
                                @if($ticket->course)
                                    <span class="text-xs font-semibold text-[#0B2A8F] dark:text-blue-300 bg-blue-50 dark:bg-blue-950/40 px-2 py-0.5 rounded-md truncate max-w-[220px]">
                                        📚 {{ $ticket->course->title_ar ?? $ticket->course->title }}
                                    </span>
                                @endif
                            </div>

                            <!-- Subject -->
                            <h2 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white group-hover:text-[#012383] dark:group-hover:text-blue-400 transition-colors truncate">
                                {{ $ticket->subject }}
                            </h2>

                            <!-- Metadata & assigned admin -->
                            <div class="flex items-center gap-3 sm:gap-4 text-xs text-slate-500 dark:text-slate-400 flex-wrap">
                                <span class="flex items-center gap-1.5">
                                    <svg class="size-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ __('آخر تحديث:') }} {{ $ticket->last_message_at?->diffForHumans() }}</span>
                                </span>

                                @if($ticket->assignedAdmin)
                                    <span class="flex items-center gap-1.5">
                                        <svg class="size-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ __('المشرف المسؤول:') }} {{ $ticket->assignedAdmin->name }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500">{{ __('بانتظار تخصيص المشرف') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Right / Action Column -->
                        <div class="flex items-center gap-3 self-end md:self-center shrink-0">
                            @if($ticket->unread_count > 0)
                                <span class="px-2.5 py-1 text-xs font-extrabold bg-[#F2AE2E] text-slate-900 rounded-full shadow-xs animate-bounce flex items-center gap-1">
                                    <span>{{ $ticket->unread_count }}</span>
                                    <span>{{ __('رد جديد') }}</span>
                                </span>
                            @endif

                            <div class="size-9 sm:size-10 rounded-xl bg-slate-100 dark:bg-slate-800 group-hover:bg-[#012383] text-slate-500 dark:text-slate-400 group-hover:text-white flex items-center justify-center transition-colors">
                                <svg class="size-4.5 sm:size-5 transform rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $tickets->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-8 sm:p-12 text-center flex flex-col items-center justify-center gap-4 shadow-xs">
                <div class="size-16 rounded-2xl bg-[#EAF1FB] dark:bg-slate-800 flex items-center justify-center text-[#012383] dark:text-blue-400">
                    <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div class="flex flex-col gap-1 max-w-md">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ __('لا توجد تذاكر دعم مطابقة') }}</h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('إذا واجهتك أي مشكلة أو كان لديك استفسار سريري أو تقني، لا تتردد في فتح تذكرة جديدة وسيقوم فريقنا بمساعدتك فوراً.') }}
                    </p>
                </div>
                <a href="{{ route('support.create') }}" class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#012383] text-white text-xs sm:text-sm font-bold hover:bg-[#0B2A8F] transition-all shadow-sm">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>{{ __('فتح تذكرة دعم الآن') }}</span>
                </a>
            </div>
        @endif

    </div>
</x-layouts::app>