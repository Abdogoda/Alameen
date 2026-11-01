<x-admin-layout::admin
    :title="__('إدارة تذاكر الدعم والرسائل المباشرة')"
    :description="__('متابعة استفسارات ومشاكل الطلاب، التوجيه السريري والأكاديمي، والدعم الفني المباشر')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-5 sm:gap-6 max-w-[1500px] w-full mx-auto min-w-0"
>
    <x-slot:actions>
        <span class="text-xs font-bold text-blue-800 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 px-3.5 py-2 rounded-xl flex items-center gap-2 shrink-0">
            <span class="size-2 rounded-full bg-blue-600 animate-ping"></span>
            <span>{{ __(':count تذكرة مفتوحة وبانتظار المتابعة', ['count' => $counts['open'] ?? 0]) }}</span>
        </span>
    </x-slot:actions>

    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center gap-2">
            <svg class="size-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        <!-- Total -->
        <a href="{{ route('admin.support.index', ['status' => 'all']) }}" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 flex flex-col gap-1 shadow-xs hover:border-[#012383] transition-colors {{ $status === 'all' ? 'ring-2 ring-[#012383]' : '' }}">
            <span class="text-[11px] font-bold text-slate-400">{{ __('إجمالي التذاكر') }}</span>
            <span class="text-2xl font-black text-slate-800 dark:text-white tabular-nums">{{ $counts['all'] }}</span>
        </a>

        <!-- Open / Needs Reply -->
        <a href="{{ route('admin.support.index', ['status' => 'open']) }}" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 flex flex-col gap-1 shadow-xs hover:border-blue-500 transition-colors {{ $status === 'open' ? 'ring-2 ring-blue-500' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-blue-600">{{ __('جديدة ومفتوحة') }}</span>
                <span class="size-2 rounded-full bg-blue-500 animate-pulse"></span>
            </div>
            <span class="text-2xl font-black text-blue-700 dark:text-blue-400 tabular-nums">{{ $counts['open'] }}</span>
        </a>

        <!-- Urgent -->
        <a href="{{ route('admin.support.index', ['priority' => 'urgent']) }}" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 flex flex-col gap-1 shadow-xs hover:border-rose-500 transition-colors {{ $priority === 'urgent' ? 'ring-2 ring-rose-500' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-rose-600">{{ __('عاجلة جداً') }}</span>
                @if($counts['urgent'] > 0)
                    <span class="size-2 rounded-full bg-rose-500 animate-ping"></span>
                @endif
            </div>
            <span class="text-2xl font-black text-rose-600 dark:text-rose-400 tabular-nums">{{ $counts['urgent'] }}</span>
        </a>

        <!-- Unassigned -->
        <a href="{{ route('admin.support.index', ['assigned' => 'unassigned']) }}" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 flex flex-col gap-1 shadow-xs hover:border-amber-500 transition-colors {{ $assigned === 'unassigned' ? 'ring-2 ring-amber-500' : '' }}">
            <span class="text-[11px] font-bold text-amber-600">{{ __('غير مسندة') }}</span>
            <span class="text-2xl font-black text-amber-700 dark:text-amber-400 tabular-nums">{{ $counts['unassigned'] }}</span>
        </a>

        <!-- Assigned to Me -->
        <a href="{{ route('admin.support.index', ['assigned' => 'mine']) }}" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 flex flex-col gap-1 shadow-xs hover:border-purple-500 transition-colors {{ $assigned === 'mine' ? 'ring-2 ring-purple-500' : '' }}">
            <span class="text-[11px] font-bold text-purple-600">{{ __('تذاكري الموكلة لي') }}</span>
            <span class="text-2xl font-black text-purple-700 dark:text-purple-400 tabular-nums">{{ $counts['mine'] }}</span>
        </a>

        <!-- Resolved -->
        <a href="{{ route('admin.support.index', ['status' => 'resolved']) }}" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 flex flex-col gap-1 shadow-xs hover:border-teal-500 transition-colors {{ $status === 'resolved' ? 'ring-2 ring-teal-500' : '' }}">
            <span class="text-[11px] font-bold text-teal-600">{{ __('تم حلها') }}</span>
            <span class="text-2xl font-black text-teal-700 dark:text-teal-400 tabular-nums">{{ $counts['resolved'] }}</span>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex flex-col gap-4">
        
        <!-- Search & Dropdowns form -->
        <form method="GET" action="{{ route('admin.support.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div class="relative sm:col-span-2">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="{{ __('ابحث برقم التذكرة، اسم الطالب، البريد، أو الموضوع...') }}"
                    class="w-full h-11 ps-10 pe-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#012383] bg-slate-50/50 dark:bg-slate-800/50"
                />
                <svg class="size-4 text-slate-400 absolute start-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" class="w-full h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-50/50 dark:bg-slate-800/50 outline-none focus:border-[#012383]">
                    <option value="all">{{ __('جميع الحالات') }}</option>
                    <option value="open" @selected($status === 'open')>{{ __('مفتوحة وجديدة') }}</option>
                    <option value="in_progress" @selected($status === 'in_progress')>{{ __('قيد المعالجة') }}</option>
                    <option value="waiting_student" @selected($status === 'waiting_student')>{{ __('بانتظار رد الطالب') }}</option>
                    <option value="waiting_admin" @selected($status === 'waiting_admin')>{{ __('بانتظار رد المشرف') }}</option>
                    <option value="resolved" @selected($status === 'resolved')>{{ __('تم الحل') }}</option>
                    <option value="closed" @selected($status === 'closed')>{{ __('مغلقة') }}</option>
                </select>
            </div>

            <!-- Priority Filter -->
            <div>
                <select name="priority" class="w-full h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-50/50 dark:bg-slate-800/50 outline-none focus:border-[#012383]">
                    <option value="all">{{ __('جميع الأولويات') }}</option>
                    <option value="urgent" @selected($priority === 'urgent')>{{ __('عاجل جداً') }}</option>
                    <option value="high" @selected($priority === 'high')>{{ __('مرتفعة') }}</option>
                    <option value="medium" @selected($priority === 'medium')>{{ __('متوسطة') }}</option>
                    <option value="low" @selected($priority === 'low')>{{ __('منخفضة') }}</option>
                </select>
            </div>

            <!-- Category / Action -->
            <div class="flex items-center gap-2">
                <select name="category" class="w-full h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-50/50 dark:bg-slate-800/50 outline-none focus:border-[#012383]">
                    <option value="all">{{ __('جميع التصنيفات') }}</option>
                    <option value="academic" @selected($category === 'academic')>{{ __('سريري وأكاديمي') }}</option>
                    <option value="technical" @selected($category === 'technical')>{{ __('دعم فني وتقني') }}</option>
                    <option value="billing" @selected($category === 'billing')>{{ __('فواتير ومدفوعات') }}</option>
                    <option value="courses" @selected($category === 'courses')>{{ __('محتوى الكورسات') }}</option>
                    <option value="certificates" @selected($category === 'certificates')>{{ __('شهادات') }}</option>
                    <option value="general" @selected($category === 'general')>{{ __('عام') }}</option>
                </select>
                <button type="submit" class="px-4 h-11 rounded-xl bg-[#012383] text-white text-xs font-bold shrink-0 hover:bg-[#0B2A8F] transition-colors">
                    {{ __('تصفية') }}
                </button>
            </div>
        </form>

    </div>

    <!-- Tickets Table View -->
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl shadow-xs overflow-hidden">
        @if($tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-start border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[#DEE8F7] dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-bold">
                            <th class="py-3.5 px-4 text-start">{{ __('التذكرة') }}</th>
                            <th class="py-3.5 px-4 text-start">{{ __('الطالب صاحب التذكرة') }}</th>
                            <th class="py-3.5 px-4 text-start">{{ __('الموضوع والتصنيف') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('الأولوية') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('الحالة') }}</th>
                            <th class="py-3.5 px-4 text-start">{{ __('المشرف المسؤول') }}</th>
                            <th class="py-3.5 px-4 text-start">{{ __('آخر رسالة') }}</th>
                            <th class="py-3.5 px-4 text-end">{{ __('الإجراء') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#DEE8F7] dark:divide-slate-800">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors {{ $ticket->unread_user_count > 0 ? 'bg-amber-50/40 dark:bg-amber-950/20' : '' }}">
                                
                                <!-- Ticket Number -->
                                <td class="py-4 px-4 whitespace-nowrap font-mono font-bold text-[#012383] dark:text-blue-400">
                                    <div class="flex items-center gap-1.5">
                                        @if($ticket->unread_user_count > 0)
                                            <span class="size-2 rounded-full bg-[#F2AE2E] animate-pulse"></span>
                                        @endif
                                        <span>#{{ $ticket->ticket_number }}</span>
                                    </div>
                                </td>

                                <!-- Student Info -->
                                <td class="py-4 px-4 min-w-[180px]">
                                    <div class="flex items-center gap-2.5">
                                        <div class="size-8 rounded-full bg-[#EAF1FB] text-[#012383] font-bold flex items-center justify-center shrink-0 border border-blue-200">
                                            {{ mb_substr($ticket->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="font-bold text-slate-900 dark:text-white truncate">{{ $ticket->user->name }}</span>
                                            <span class="text-[11px] text-slate-400 truncate">{{ $ticket->user->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Subject & Category -->
                                <td class="py-4 px-4 max-w-xs">
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ route('admin.support.show', $ticket->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-[#012383] transition-colors truncate">
                                            {{ $ticket->subject }}
                                        </a>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="px-2 py-0.5 rounded-md text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold">
                                                {{ $ticket->getCategoryLabel() }}
                                            </span>
                                            @if($ticket->course)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] bg-blue-50 text-[#0B2A8F] font-semibold truncate max-w-[150px]">
                                                    📚 {{ $ticket->course->title_ar ?? $ticket->course->title }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Priority -->
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $ticket->getPriorityBadgeClass() }}">
                                        {{ $ticket->getPriorityLabel() }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $ticket->getStatusBadgeClass() }}">
                                        {{ $ticket->getStatusLabel() }}
                                    </span>
                                </td>

                                <!-- Assigned Admin -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($ticket->assignedAdmin)
                                        <div class="flex items-center gap-1.5">
                                            <div class="size-6 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold flex items-center justify-center shrink-0">
                                                {{ mb_substr($ticket->assignedAdmin->name, 0, 1) }}
                                            </div>
                                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $ticket->assignedAdmin->name }}</span>
                                        </div>
                                    @else
                                        <form action="{{ route('admin.support.assign', $ticket->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="admin_id" value="{{ auth('admin')->id() }}">
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#012383] text-[11px] font-bold transition-colors border border-blue-200">
                                                + {{ __('تعيين لي') }}
                                            </button>
                                        </form>
                                    @endif
                                </td>

                                <!-- Last Message Time -->
                                <td class="py-4 px-4 whitespace-nowrap text-slate-500">
                                    <div class="flex flex-col">
                                        <span>{{ $ticket->last_message_at?->diffForHumans() }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $ticket->last_message_at?->format('Y-m-d H:i') }}</span>
                                    </div>
                                </td>

                                <!-- Action -->
                                <td class="py-4 px-4 text-end whitespace-nowrap">
                                    <a
                                        href="{{ route('admin.support.show', $ticket->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold transition-all shadow-xs"
                                    >
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        <span>{{ __('فتح المحادثة') }}</span>
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#DEE8F7] dark:border-slate-800">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="p-12 text-center flex flex-col items-center justify-center gap-3">
                <div class="size-14 rounded-2xl bg-[#EAF1FB] flex items-center justify-center text-[#012383]">
                    <svg class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h4 class="font-bold text-base text-slate-800 dark:text-white">{{ __('لا توجد تذاكر دعم تطابق معايير البحث') }}</h4>
                <p class="text-xs text-slate-400">{{ __('جرّب تغيير فلاتر البحث أو اختيار حالة أخرى.') }}</p>
            </div>
        @endif
    </div>

</x-admin-layout::admin>
