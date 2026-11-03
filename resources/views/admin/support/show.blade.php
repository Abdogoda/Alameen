<x-admin-layout::admin
    :title="null"
    contentClass="p-3 sm:p-5 lg:p-6 flex flex-col gap-3 sm:gap-4 w-full min-w-0 max-w-[1600px] mx-auto"
>
    <div
        class="flex flex-col gap-3 sm:gap-4 w-full"
        x-data="adminChat({
            ticketId: {{ $ticket->id }},
            lastId: {{ $ticket->messages->max('id') ?? 0 }},
            pollUrl: '{{ route('admin.support.poll', $ticket->id) }}',
            status: '{{ $ticket->status }}',
            priority: '{{ $ticket->priority }}',
            assignedAdminId: {{ $ticket->assigned_admin_id ?? 'null' }},
            isClosed: {{ $ticket->isClosed() ? 'true' : 'false' }}
        })"
        x-init="initChat()"
    >
        <!-- ============================================== -->
        <!-- 1. TOP CONTEXT & COMMAND TOOLBAR               -->
        <!-- ============================================== -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-3 sm:p-4 shadow-xs flex flex-wrap items-center justify-between gap-3">
            
            <!-- Left: Back Button, Ticket Number & Status Badges -->
            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                <a
                    href="{{ route('admin.support.index') }}"
                    class="size-9 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 hover:text-[#012383] dark:hover:text-white flex items-center justify-center transition-all cursor-pointer shadow-2xs group"
                    title="{{ __('العودة لقائمة التذاكر') }}"
                >
                    <svg class="size-4.5 rtl:rotate-180 group-hover:-translate-x-0.5 rtl:group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <!-- Ticket Number Capsule -->
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50/70 dark:from-blue-950/40 dark:to-indigo-950/30 border border-blue-200/70 dark:border-blue-900/60 shadow-2xs">
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-400">#</span>
                    <span class="font-mono text-xs font-black text-[#012383] dark:text-blue-300">{{ $ticket->ticket_number }}</span>
                </div>

                <!-- Status Badge -->
                <span
                    class="text-xs font-bold px-3 py-1 rounded-xl border shadow-2xs flex items-center gap-1.5 {{ $ticket->getStatusBadgeClass() }}"
                >
                    <span class="size-2 rounded-full {{ $ticket->status === 'open' ? 'bg-emerald-500 animate-pulse' : ($ticket->status === 'in_progress' ? 'bg-amber-500 animate-pulse' : 'bg-slate-400') }}"></span>
                    <span x-text="statusLabel">{{ $ticket->getStatusLabel() }}</span>
                </span>

                <!-- Priority Badge -->
                <span
                    class="text-xs font-bold px-3 py-1 rounded-xl border shadow-2xs flex items-center gap-1 {{ $ticket->getPriorityBadgeClass() }}"
                >
                    @if($ticket->priority === 'urgent')
                        <span>🔥</span>
                    @elseif($ticket->priority === 'high')
                        <span>⚡</span>
                    @endif
                    <span x-text="priorityLabel">{{ $ticket->getPriorityLabel() }}</span>
                </span>

                <!-- Category Pill -->
                <span class="hidden md:inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700">
                    <span>🏷️</span>
                    <span>{{ $ticket->getCategoryLabel() }}</span>
                </span>
            </div>

            <!-- Right: Quick Close / Reopen & Mobile Switcher -->
            <div class="flex items-center gap-2">
                @if(! $ticket->isClosed())
                    <form action="{{ route('admin.support.close', $ticket->id) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من إغلاق هذه التذكرة؟') }}');">
                        @csrf
                        <button
                            type="submit"
                            class="h-9 px-3.5 rounded-xl border border-rose-300 dark:border-rose-900/60 bg-rose-50/70 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 text-xs font-bold transition-all shadow-2xs flex items-center gap-1.5 cursor-pointer"
                        >
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>{{ __('إغلاق التذكرة') }}</span>
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.support.reopen', $ticket->id) }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="h-9 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer"
                        >
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>{{ __('إعادة فتح التذكرة') }}</span>
                        </button>
                    </form>
                @endif
            </div>

        </div>

        @if(session('success'))
            <div class="p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm font-semibold flex items-center gap-2.5 animate-fade-in shadow-xs">
                <div class="size-6 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- 2. MOBILE RESPONSIVE TAB SWITCHER (< 1024px)   -->
        <!-- ============================================== -->
        <div class="lg:hidden flex items-center p-1 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-2xs">
            <button
                type="button"
                @click="mobileTab = 'chat'"
                :class="mobileTab === 'chat' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 font-semibold'"
                class="flex-1 py-2 text-xs rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>{{ __('محادثة التذكرة الحية') }}</span>
            </button>
            <button
                type="button"
                @click="mobileTab = 'info'"
                :class="mobileTab === 'info' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 font-semibold'"
                class="flex-1 py-2 text-xs rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>{{ __('معلومات الطالب والتحكم') }}</span>
            </button>
        </div>

        <!-- ============================================== -->
        <!-- 3. MAIN WORKSPACE: 2-COLUMN DESKTOP / TABBED   -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-5 items-start">
            
            <!-- ========================================== -->
            <!-- SIDE COLUMN: Student Profile & Controls    -->
            <!-- ========================================== -->
            <div
                class="lg:col-span-4 flex flex-col gap-4 order-2 lg:order-1"
                :class="{ 'hidden lg:flex': mobileTab !== 'info' }"
            >
                <!-- 1. Student Profile Card -->
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 shadow-xs flex flex-col gap-4 text-start">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="size-2 rounded-full bg-[#0B2A8F] dark:bg-blue-400"></span>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('معلومات الطالب') }}</span>
                        </div>
                        <a
                            href="{{ route('admin.students.show', $ticket->user->id) }}"
                            target="_blank"
                            class="text-xs font-bold text-[#012383] dark:text-blue-400 hover:text-[#0B2A8F] hover:underline flex items-center gap-1 transition-colors"
                        >
                            <span>{{ __('الملف الأكاديمي') }}</span>
                            <svg class="size-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>

                    <!-- Student Info Row -->
                    <div class="flex items-center gap-3.5">
                        <div class="size-13 rounded-2xl bg-gradient-to-br from-[#012383] to-[#0B2A8F] text-white font-black text-lg flex items-center justify-center shrink-0 shadow-md shadow-blue-900/10 ring-4 ring-blue-500/10">
                            {{ mb_substr($ticket->user->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-extrabold text-sm sm:text-base text-slate-900 dark:text-white truncate">{{ $ticket->user->name }}</span>
                            <span class="text-xs text-slate-400 truncate dir-ltr text-start mt-0.5">{{ $ticket->user->email }}</span>
                            @if($ticket->user->phone)
                                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                    <a href="tel:{{ $ticket->user->phone }}" class="text-xs font-mono font-bold text-slate-600 dark:text-slate-300 hover:text-[#012383] bg-slate-50 dark:bg-slate-800 px-2 py-0.5 rounded-md border border-slate-200/80 dark:border-slate-700">
                                        📞 {{ $ticket->user->phone }}
                                    </a>
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $ticket->user->phone);
                                    @endphp
                                    <a
                                        href="https://wa.me/{{ $cleanPhone }}"
                                        target="_blank"
                                        class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 px-2 py-0.5 rounded-md border border-emerald-200/70 transition-colors"
                                        title="{{ __('مراسلة واتساب مباشرة') }}"
                                    >
                                        💬 {{ __('واتساب') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Academic Stats Pills -->
                    <div class="grid grid-cols-2 gap-2.5 pt-2 border-t border-slate-100 dark:border-slate-800 text-xs">
                        <div class="p-3 rounded-2xl bg-slate-50/80 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex flex-col">
                            <span class="text-slate-400 dark:text-slate-400 text-[10px] font-bold">🎓 {{ __('الكورسات المشترك بها') }}</span>
                            <span class="font-black text-sm text-slate-800 dark:text-white mt-1 tabular-nums">{{ $ticket->user->courses->count() }} {{ __('كورس') }}</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50/80 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex flex-col">
                            <span class="text-slate-400 dark:text-slate-400 text-[10px] font-bold">📅 {{ __('تاريخ التسجيل') }}</span>
                            <span class="font-bold text-xs text-slate-800 dark:text-white mt-1.5">{{ $ticket->user->created_at?->format('Y-m-d') }}</span>
                        </div>
                    </div>

                    @if($ticket->course)
                        <div class="p-3 rounded-2xl bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200/80 dark:border-blue-900/60 flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-[#0B2A8F] dark:text-blue-300">📚 {{ __('الكورس موضوع التذكرة:') }}</span>
                            <span class="font-bold text-xs text-[#012383] dark:text-blue-200 leading-snug">{{ $ticket->course->title_ar ?? $ticket->course->title }}</span>
                        </div>
                    @endif
                </div>

                <!-- 2. Ticket Management & Fast Controls Card -->
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 shadow-xs flex flex-col gap-4 text-start">
                    <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                        <span class="size-2 rounded-full bg-amber-500"></span>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('إدارة ومعالجة التذكرة') }}</span>
                    </div>

                    <!-- Status Changer -->
                    <form action="{{ route('admin.support.status.update', $ticket->id) }}" method="POST" class="flex flex-col gap-1.5">
                        @csrf
                        <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300">
                            <span>📌 {{ __('حالة التذكرة:') }}</span>
                            <span class="text-[10px] font-normal text-slate-400">{{ __('حفظ فوري') }}</span>
                        </div>
                        <div class="relative">
                            <select
                                name="status"
                                @change="$el.form.submit()"
                                class="w-full h-11 ps-3.5 pe-8 rounded-xl border border-slate-200 dark:border-slate-700/80 text-xs font-bold bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 outline-none focus:border-[#012383] focus:ring-2 focus:ring-[#012383]/10 transition-all cursor-pointer"
                            >
                                <option value="open" @selected($ticket->status === 'open')>🟢 {{ __('مفتوحة وجديدة') }}</option>
                                <option value="in_progress" @selected($ticket->status === 'in_progress')>🟡 {{ __('قيد المعالجة والمتابعة') }}</option>
                                <option value="waiting_student" @selected($ticket->status === 'waiting_student')>⏳ {{ __('بانتظار رد الطالب') }}</option>
                                <option value="waiting_admin" @selected($ticket->status === 'waiting_admin')>👤 {{ __('بانتظار رد المشرف') }}</option>
                                <option value="resolved" @selected($ticket->status === 'resolved')>✅ {{ __('تم الحل بنجاح') }}</option>
                                <option value="closed" @selected($ticket->status === 'closed')>🔒 {{ __('مغلقة ومؤرشفة') }}</option>
                            </select>
                        </div>
                    </form>

                    <!-- Priority Changer -->
                    <form action="{{ route('admin.support.priority.update', $ticket->id) }}" method="POST" class="flex flex-col gap-1.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                        @csrf
                        <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300">
                            <span>⚡ {{ __('درجة الأولوية:') }}</span>
                            <span class="text-[10px] font-normal text-slate-400">{{ __('حفظ فوري') }}</span>
                        </div>
                        <div class="relative">
                            <select
                                name="priority"
                                @change="$el.form.submit()"
                                class="w-full h-11 ps-3.5 pe-8 rounded-xl border border-slate-200 dark:border-slate-700/80 text-xs font-bold bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 outline-none focus:border-[#012383] focus:ring-2 focus:ring-[#012383]/10 transition-all cursor-pointer"
                            >
                                <option value="low" @selected($ticket->priority === 'low')>🟢 {{ __('منخفضة') }}</option>
                                <option value="medium" @selected($ticket->priority === 'medium')>🔵 {{ __('متوسطة (عادية)') }}</option>
                                <option value="high" @selected($ticket->priority === 'high')>🟠 {{ __('مرتفعة (مهمة)') }}</option>
                                <option value="urgent" @selected($ticket->priority === 'urgent')>🔴 {{ __('عاجلة جداً (فورية)') }}</option>
                            </select>
                        </div>
                    </form>

                    <!-- Assign Admin -->
                    <form
                        action="{{ route('admin.support.assign', $ticket->id) }}"
                        method="POST"
                        x-ref="assignForm"
                        class="flex flex-col gap-1.5 pt-3 border-t border-slate-100 dark:border-slate-800"
                    >
                        @csrf
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                                <span>👨‍⚕️</span>
                                <span>{{ __('إسناد التذكرة لمشرف:') }}</span>
                            </label>
                            @if($ticket->assigned_admin_id !== auth('admin')->id())
                                <button
                                    type="button"
                                    @click="$refs.assignAdminSelect.value = '{{ auth('admin')->id() }}'; $refs.assignForm.submit()"
                                    class="text-[11px] font-bold text-[#0B2A8F] dark:text-blue-300 hover:text-[#012383] bg-blue-50 dark:bg-blue-950/50 hover:bg-blue-100 px-2.5 py-0.5 rounded-lg border border-blue-200/70 dark:border-blue-900 transition-colors flex items-center gap-1 cursor-pointer"
                                >
                                    <span>⚡</span>
                                    <span>{{ __('إسناد لي فوراً') }}</span>
                                </button>
                            @endif
                        </div>
                        <div class="relative">
                            <select
                                name="admin_id"
                                x-ref="assignAdminSelect"
                                @change="$el.form.submit()"
                                class="w-full h-11 ps-3.5 pe-8 rounded-xl border border-slate-200 dark:border-slate-700/80 text-xs font-bold bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 outline-none focus:border-[#012383] focus:ring-2 focus:ring-[#012383]/10 transition-all cursor-pointer"
                            >
                                <option value="">{{ __('--- غير مسندة لأحد ---') }}</option>
                                @foreach($admins as $adm)
                                    <option value="{{ $adm->id }}" @selected($ticket->assigned_admin_id === $adm->id)>
                                        {{ $adm->name }} ({{ $adm->role_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                </div>

            </div>

            <!-- ========================================== -->
            <!-- MAIN CHAT CONSOLE (8 Cols on Desktop)      -->
            <!-- ========================================== -->
            <div
                class="lg:col-span-8 flex flex-col bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden h-[620px] sm:h-[680px] lg:h-[calc(100vh-210px)] min-h-[560px] order-1 lg:order-2"
                :class="{ 'hidden lg:flex': mobileTab !== 'chat' }"
            >
                
                <!-- Chat Header Bar -->
                <div class="px-4 py-3 sm:px-6 sm:py-3.5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/40 backdrop-blur-md flex items-center justify-between gap-3 shrink-0">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="size-2.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></div>
                        <h2 class="font-bold text-sm sm:text-base text-slate-900 dark:text-white truncate">
                            {{ $ticket->subject }}
                        </h2>
                    </div>

                    <div class="flex items-center gap-2 text-xs shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/40 text-[11px] font-bold">
                            <span class="size-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            <span>{{ __('مزامنة حية ومباشرة') }}</span>
                        </span>
                    </div>
                </div>

                <!-- Messages Stream Scrollable Area -->
                <div
                    id="admin-messages-scroll-area"
                    class="flex-1 overflow-y-auto p-4 sm:p-6 flex flex-col gap-4 bg-[#F8FAFC]/50 dark:bg-[#070C18]/60 alameen-chat-scroll"
                    x-ref="messagesContainer"
                >
                    @foreach($ticket->messages as $msg)
                        @if($msg->isSystemEvent())
                            <!-- System Event Pill -->
                            <div class="flex items-center justify-center my-1" data-message-id="{{ $msg->id }}">
                                <div class="px-3.5 py-1 bg-slate-200/70 dark:bg-slate-800/90 text-slate-600 dark:text-slate-400 rounded-full text-[11px] font-semibold text-center max-w-lg shadow-2xs border border-slate-300/40 dark:border-slate-700/60">
                                    ⚙️ {{ $msg->message }} • {{ $msg->created_at?->diffForHumans() }}
                                </div>
                            </div>
                        @elseif($msg->isInternalNote())
                            <!-- Private Internal Admin Note (Distinct Golden / Amber Card) -->
                            <div class="flex flex-col items-center my-2 max-w-2xl mx-auto w-full" data-message-id="{{ $msg->id }}">
                                <div class="w-full bg-gradient-to-r from-amber-50 to-orange-50/60 dark:from-amber-950/40 dark:to-orange-950/20 border-2 border-amber-300 dark:border-amber-700/80 rounded-2xl p-4 shadow-xs">
                                    <div class="flex items-center justify-between text-amber-800 dark:text-amber-300 border-b border-amber-200 dark:border-amber-900/60 pb-2 mb-2 text-xs">
                                        <div class="flex items-center gap-1.5 font-extrabold">
                                            <span>🔒</span>
                                            <span>{{ __('ملاحظة داخلية خاصة بالمشرفين (مخفية تماماً عن الطالب)') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-[11px]">
                                            <span class="font-bold">{{ $msg->getSenderName() }}</span>
                                            <span>•</span>
                                            <span class="dir-ltr">{{ $msg->created_at?->format('H:i') }}</span>
                                        </div>
                                    </div>

                                    <div class="text-xs sm:text-sm text-amber-950 dark:text-amber-100 font-medium whitespace-pre-wrap leading-relaxed">
                                        {{ $msg->message }}
                                    </div>

                                    @if(!empty($msg->attachments))
                                        <div class="mt-2.5 pt-2 border-t border-amber-200 dark:border-amber-900/60 flex flex-col gap-1.5">
                                            @foreach($msg->attachments as $file)
                                                <a href="{{ $file['url'] }}" target="_blank" class="text-xs text-amber-800 dark:text-amber-300 hover:underline flex items-center gap-1 font-semibold">
                                                    <span>📎 {{ $file['name'] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($msg->isFromStudent())
                            <!-- Student Message (Left in LTR, Right in RTL) -->
                            <div class="flex items-start gap-2.5 sm:gap-3 self-start max-w-[90%] sm:max-w-[75%]" data-message-id="{{ $msg->id }}">
                                <div class="size-8 sm:size-9 rounded-full bg-[#EAF1FB] text-[#012383] font-bold text-xs flex items-center justify-center shrink-0 border border-blue-200 mt-1 shadow-2xs">
                                    {{ mb_substr($ticket->user->name, 0, 1) }}
                                </div>

                                <div class="flex flex-col gap-1 min-w-0 text-start">
                                    <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                        <span class="font-bold text-slate-900 dark:text-white">{{ $msg->getSenderName() }}</span>
                                        <span class="text-slate-400">({{ __('الطالب') }})</span>
                                        <span>•</span>
                                        <span class="dir-ltr">{{ $msg->created_at?->format('H:i') }}</span>
                                    </div>

                                    <div class="w-fit text-start bg-white dark:bg-slate-800 border border-slate-200/90 dark:border-slate-700 text-slate-800 dark:text-slate-100 p-3 sm:p-4 rounded-2xl rounded-tl-xs rtl:rounded-tl-2xl rtl:rounded-tr-xs shadow-xs text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap break-words">
                                        {{ trim($msg->message) }}

                                        @if(!empty($msg->attachments))
                                            <div class="mt-3 flex flex-col gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                                                @foreach($msg->attachments as $file)
                                                    @if(!empty($file['is_image']))
                                                        <div class="rounded-xl overflow-hidden border border-slate-200 max-w-[280px]">
                                                            <a href="{{ $file['url'] }}" target="_blank">
                                                                <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-auto object-cover hover:scale-105 transition-transform" />
                                                            </a>
                                                        </div>
                                                    @elseif(!empty($file['is_audio']))
                                                        <div class="bg-slate-50 dark:bg-slate-900 p-2 rounded-xl border border-slate-200 dark:border-slate-700">
                                                            <p class="text-[11px] mb-1 truncate text-slate-700 dark:text-slate-300">🎙️ {{ $file['name'] }}</p>
                                                            <audio controls class="w-full h-8">
                                                                <source src="{{ $file['url'] }}" type="{{ $file['mime'] }}">
                                                            </audio>
                                                        </div>
                                                    @else
                                                        <a href="{{ $file['url'] }}" target="_blank" download class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 border border-slate-200 dark:border-slate-700 transition-colors text-xs text-slate-700 dark:text-slate-200">
                                                            <svg class="size-4 shrink-0 text-[#012383]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                            <span class="truncate">{{ $file['name'] }}</span>
                                                            <span class="text-[10px] text-slate-400">⬇</span>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Admin Message Bubble (Outgoing) -->
                            <div class="flex flex-col items-end gap-1.5 self-end max-w-[90%] sm:max-w-[75%]" data-message-id="{{ $msg->id }}">
                                <div class="flex items-center gap-1.5 text-[11px] text-slate-400 pe-1">
                                    <span class="font-bold text-[#012383] dark:text-blue-300">{{ $msg->getSenderName() }}</span>
                                    <span>•</span>
                                    <span class="dir-ltr">{{ $msg->created_at?->format('H:i') }}</span>
                                </div>

                                <div class="w-fit text-start bg-gradient-to-r from-[#012383] via-[#0B2A8F] to-[#1E3A8A] text-white p-3 sm:p-4 rounded-2xl rounded-tr-xs rtl:rounded-tr-2xl rtl:rounded-tl-xs shadow-md shadow-blue-900/10 text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap break-words">
                                    {{ trim($msg->message) }}

                                    @if(!empty($msg->attachments))
                                        <div class="mt-3 flex flex-col gap-2 pt-2 border-t border-white/20">
                                            @foreach($msg->attachments as $file)
                                                @if(!empty($file['is_image']))
                                                    <div class="rounded-xl overflow-hidden border border-white/20 max-w-[280px]">
                                                        <a href="{{ $file['url'] }}" target="_blank">
                                                            <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-auto object-cover hover:scale-105 transition-transform" />
                                                        </a>
                                                    </div>
                                                @elseif(!empty($file['is_audio']))
                                                    <div class="bg-white/10 p-2 rounded-xl">
                                                        <p class="text-[11px] mb-1 truncate text-white/90">🎙️ {{ $file['name'] }}</p>
                                                        <audio controls class="w-full h-8">
                                                            <source src="{{ $file['url'] }}" type="{{ $file['mime'] }}">
                                                        </audio>
                                                    </div>
                                                @else
                                                    <a href="{{ $file['url'] }}" target="_blank" download class="flex items-center gap-2 p-2 rounded-xl bg-white/15 hover:bg-white/25 transition-colors text-xs text-white">
                                                        <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        <span class="truncate">{{ $file['name'] }}</span>
                                                        <span class="text-[10px] text-white/70">⬇</span>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="text-[10px] text-slate-400 pe-1">
                                    @if($msg->read_at)
                                        <span class="text-blue-500 font-bold">✓✓ {{ __('قرأه الطالب في :time', ['time' => $msg->read_at->format('H:i')]) }}</span>
                                    @else
                                        <span>✓ {{ __('تم الإرسال') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- Polling insertion slot -->
                    <div x-ref="newMessagesSlot" class="contents"></div>
                </div>

                <!-- Admin Reply & Note Composer -->
                <div class="p-3 sm:p-4 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 shrink-0">
                    <form
                        action="{{ route('admin.support.reply', $ticket->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        x-ref="adminReplyForm"
                        @submit="handleSubmit($event)"
                        class="flex flex-col gap-2.5"
                    >
                        @csrf

                        <!-- Mode Switcher Tabs + Canned Responses Selector -->
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <!-- Toggle Tabs: Direct Reply vs Internal Note -->
                            <div class="flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-bold shadow-2xs">
                                <button
                                    type="button"
                                    @click="isInternal = false"
                                    :class="!isInternal ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                                    class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer"
                                >
                                    <span>💬 {{ __('رد مباشر على الطالب') }}</span>
                                </button>
                                <button
                                    type="button"
                                    @click="isInternal = true"
                                    :class="isInternal ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                                    class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer"
                                >
                                    <span>🔒 {{ __('ملاحظة داخلية خاصة') }}</span>
                                </button>
                            </div>
                            <input type="hidden" name="is_internal_note" :value="isInternal ? '1' : '0'">

                            <!-- Canned responses dropdown -->
                            <div class="relative" x-data="{ openCanned: false }">
                                <button
                                    type="button"
                                    @click="openCanned = !openCanned"
                                    class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 flex items-center gap-1.5 transition-colors cursor-pointer shadow-2xs"
                                >
                                    <span>⚡ {{ __('قوالب ردود جاهزة') }}</span>
                                    <svg class="size-3.5 transition-transform" :class="{ 'rotate-180': openCanned }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div
                                    x-show="openCanned"
                                    x-cloak
                                    @click.away="openCanned = false"
                                    class="absolute end-0 bottom-full mb-2 w-72 sm:w-80 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl p-2 z-30 flex flex-col gap-1 text-start"
                                >
                                    <span class="text-[11px] font-bold text-slate-400 px-2 py-1">{{ __('اختر رداً للإدراج الفوري:') }}</span>
                                    @foreach($cannedResponses as $canned)
                                        <button
                                            type="button"
                                            @click="insertCannedText(`{{ addslashes($canned['text']) }}`); openCanned = false;"
                                            class="p-2.5 rounded-xl text-start hover:bg-blue-50 dark:hover:bg-slate-800 transition-colors flex flex-col gap-0.5 cursor-pointer"
                                        >
                                            <span class="text-xs font-bold text-[#012383] dark:text-blue-400">{{ $canned['title'] }}</span>
                                            <span class="text-[11px] text-slate-500 dark:text-slate-400 truncate max-w-full">{{ $canned['text'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Selected Files Tag Bar -->
                        <div x-show="selectedFiles.length > 0" class="flex items-center gap-2 flex-wrap pb-1">
                            <template x-for="(f, i) in selectedFiles" :key="i">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#012383] dark:text-blue-300 text-[11px] font-bold border border-blue-200 dark:border-blue-900 shadow-2xs">
                                    <span class="truncate max-w-[150px]" x-text="f.name"></span>
                                    <button type="button" @click="removeSelectedFile(i)" class="text-rose-500 hover:text-rose-700 cursor-pointer">✕</button>
                                </span>
                            </template>
                        </div>

                        <!-- Textarea & Actions Box -->
                        <div
                            class="rounded-2xl border transition-all p-2 flex flex-col gap-2"
                            :class="isInternal
                                ? 'border-amber-400 bg-amber-50/30 dark:bg-amber-950/20'
                                : 'border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/50 focus-within:bg-white dark:focus-within:bg-slate-800 focus-within:border-[#012383] focus-within:ring-2 focus-within:ring-[#012383]/10'"
                        >
                            <textarea
                                name="message"
                                x-model="replyText"
                                x-ref="replyTextArea"
                                @keydown.ctrl.enter="$refs.adminReplyForm.requestSubmit()"
                                rows="2"
                                :placeholder="isInternal ? '{{ __('اكتب ملاحظة داخلية خاصة بالمشرفين فقط (لن يراها الطالب)...') }}' : '{{ __('اكتب ردك الأكاديمي أو التوجيهي للطالب هنا...') }}'"
                                class="w-full bg-transparent border-0 text-xs sm:text-sm font-medium outline-none resize-none p-1.5 text-slate-800 dark:text-white placeholder:text-slate-400 min-h-[48px] max-h-36"
                            ></textarea>

                            <div class="flex items-center justify-between pt-1 border-t border-slate-200/60 dark:border-slate-700/60">
                                <!-- Attachment Trigger -->
                                <div class="relative">
                                    <input
                                        type="file"
                                        id="adminAttachments"
                                        name="attachments[]"
                                        multiple
                                        x-ref="adminFileInput"
                                        @change="onFilesChange($event)"
                                        accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.zip,.mp3,.wav,.ogg,.m4a"
                                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                                    />
                                    <button
                                        type="button"
                                        class="size-9 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-[#012383] text-slate-500 dark:text-slate-300 hover:text-[#012383] bg-white dark:bg-slate-800 flex items-center justify-center transition-all cursor-pointer shadow-2xs group"
                                        title="{{ __('إرفاق ملف أو تسجيل صوتي') }}"
                                    >
                                        <svg class="size-4.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    </button>
                                </div>

                                <!-- Keyboard Shortcut Tip + Send Button -->
                                <div class="flex items-center gap-2.5">
                                    <span class="hidden sm:inline text-[11px] text-slate-400 font-semibold">{{ __('Ctrl + Enter للإرسال') }}</span>

                                    <button
                                        type="submit"
                                        :disabled="sending || (replyText.trim() === '' && selectedFiles.length === 0)"
                                        :class="isInternal
                                            ? 'bg-amber-600 hover:bg-amber-700 shadow-amber-600/20'
                                            : 'bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#1E3A8A] shadow-blue-900/20'"
                                        class="h-9 sm:h-10 px-5 rounded-xl disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2 shadow-md hover:shadow-lg active:scale-95 transition-all cursor-pointer shrink-0"
                                        style="color: #ffffff !important;"
                                    >
                                        <span x-show="!sending" x-text="isInternal ? '{{ __('حفظ الملاحظة') }}' : '{{ __('إرسال الرد') }}'"></span>
                                        <span x-show="sending">{{ __('جاري الإرسال...') }}</span>
                                        <svg x-show="!sending" class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>

    </div>

    <!-- Live Admin Chat Polling Script -->
    <script>
        function adminChat(config) {
            return {
                ticketId: config.ticketId,
                lastId: config.lastId,
                pollUrl: config.pollUrl,
                status: config.status,
                priority: config.priority,
                assignedAdminId: config.assignedAdminId,
                isClosed: config.isClosed,
                statusLabel: '{{ $ticket->getStatusLabel() }}',
                priorityLabel: '{{ $ticket->getPriorityLabel() }}',
                mobileTab: 'chat', // 'chat' or 'info'
                replyText: '',
                isInternal: false,
                selectedFiles: [],
                sending: false,
                pollingTimer: null,

                initChat() {
                    this.scrollToBottom();
                    this.startPolling();
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.messagesContainer;
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                },

                startPolling() {
                    this.pollingTimer = setInterval(() => {
                        this.pollNewMessages();
                    }, 4000);
                },

                async pollNewMessages() {
                    try {
                        const res = await fetch(`${this.pollUrl}?last_id=${this.lastId}`);
                        if (!res.ok) return;
                        const data = await res.json();
                        
                        this.statusLabel = data.status_label;
                        this.priorityLabel = data.priority_label;
                        this.isClosed = data.is_closed;

                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(msg => {
                                if (msg.id > this.lastId) {
                                    this.lastId = msg.id;
                                    this.appendMessage(msg);
                                }
                            });
                            this.scrollToBottom();
                        }
                    } catch (e) {
                        console.error('Admin support polling error:', e);
                    }
                },

                appendMessage(msg) {
                    const slot = this.$refs.newMessagesSlot;
                    if (!slot) return;

                    const div = document.createElement('div');
                    if (msg.is_system) {
                        div.className = 'flex items-center justify-center my-1';
                        div.innerHTML = `<div class="px-3.5 py-1 bg-slate-200/70 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[11px] font-semibold text-center max-w-lg shadow-2xs border border-slate-300/40 dark:border-slate-700/60">⚙️ ${this.escapeHtml(msg.message)} • ${msg.time_formatted}</div>`;
                    } else if (msg.is_internal) {
                        div.className = 'flex flex-col items-center my-2 max-w-2xl mx-auto w-full';
                        div.innerHTML = `
                            <div class="w-full bg-gradient-to-r from-amber-50 to-orange-50/60 dark:from-amber-950/40 dark:to-orange-950/20 border-2 border-amber-300 dark:border-amber-700/80 rounded-2xl p-4 shadow-xs">
                                <div class="flex items-center justify-between text-amber-800 dark:text-amber-300 border-b border-amber-200 dark:border-amber-900/60 pb-2 mb-2 text-xs">
                                    <div class="flex items-center gap-1.5 font-extrabold">
                                        <span>🔒</span>
                                        <span>{{ __('ملاحظة داخلية خاصة بالمشرفين') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-[11px]">
                                        <span class="font-bold">${this.escapeHtml(msg.sender_name)}</span> • <span class="dir-ltr">${msg.exact_time}</span>
                                    </div>
                                </div>
                                <div class="text-xs sm:text-sm text-amber-950 dark:text-amber-100 font-medium whitespace-pre-wrap leading-relaxed">
                                    ${this.escapeHtml(msg.message)}
                                </div>
                            </div>
                        `;
                    } else if (msg.is_student) {
                        div.className = 'flex items-start gap-2.5 sm:gap-3 self-start max-w-[90%] sm:max-w-[75%]';
                        div.innerHTML = `
                            <div class="size-8 sm:size-9 rounded-full bg-[#EAF1FB] text-[#012383] font-bold text-xs flex items-center justify-center shrink-0 border border-blue-200 mt-1 shadow-2xs">
                                ${this.escapeHtml(msg.sender_name.charAt(0))}
                            </div>
                            <div class="flex flex-col gap-1 min-w-0 text-start">
                                <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                    <span class="font-bold text-slate-900 dark:text-white">${this.escapeHtml(msg.sender_name)}</span>
                                    <span class="text-slate-400">({{ __('الطالب') }})</span> • <span class="dir-ltr">${msg.exact_time}</span>
                                </div>
                                <div class="w-fit text-start bg-white dark:bg-slate-800 border border-slate-200/90 dark:border-slate-700 text-slate-800 dark:text-slate-100 p-3 sm:p-4 rounded-2xl rounded-tl-xs rtl:rounded-tl-2xl rtl:rounded-tr-xs shadow-xs text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap break-words">
                                    ${this.escapeHtml(msg.message)}
                                </div>
                            </div>
                        `;
                    } else {
                        div.className = 'flex flex-col items-end gap-1.5 self-end max-w-[90%] sm:max-w-[75%]';
                        div.innerHTML = `
                            <div class="flex items-center gap-1.5 text-[11px] text-slate-400 pe-1">
                                <span class="font-bold text-[#012383] dark:text-blue-300">${this.escapeHtml(msg.sender_name)}</span> • <span class="dir-ltr">${msg.exact_time}</span>
                            </div>
                            <div class="w-fit text-start bg-gradient-to-r from-[#012383] via-[#0B2A8F] to-[#1E3A8A] text-white p-3 sm:p-4 rounded-2xl rounded-tr-xs rtl:rounded-tr-2xl rtl:rounded-tl-xs shadow-md shadow-blue-900/10 text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap break-words">
                                ${this.escapeHtml(msg.message)}
                            </div>
                        `;
                    }
                    slot.appendChild(div);
                },

                escapeHtml(text) {
                    if (!text) return '';
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                },

                insertCannedText(text) {
                    this.replyText = (this.replyText ? this.replyText + "\n" : '') + text;
                    this.$nextTick(() => {
                        this.$refs.replyTextArea.focus();
                    });
                },

                onFilesChange(e) {
                    this.selectedFiles = Array.from(e.target.files);
                },

                removeSelectedFile(index) {
                    const dt = new DataTransfer();
                    const input = this.$refs.adminFileInput;
                    this.selectedFiles.splice(index, 1);
                    this.selectedFiles.forEach(f => dt.items.add(f));
                    input.files = dt.files;
                },

                handleSubmit(e) {
                    if (this.replyText.trim() === '' && this.selectedFiles.length === 0) {
                        e.preventDefault();
                        return;
                    }
                    this.sending = true;
                }
            };
        }
    </script>
</x-admin-layout::admin>