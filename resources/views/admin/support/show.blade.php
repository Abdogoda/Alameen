<x-admin-layout::admin
    :title="__('مساحة محادثة التذكرة: :number', ['number' => $ticket->ticket_number])"
    :description="__('محادثة حية ومباشرة مع الطالب :name مع إمكانية الملاحظات الداخلية وإدارة التذكرة', ['name' => $ticket->user->name])"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-5 w-full min-w-0"
>
    <div
        class="flex flex-col gap-4 w-full"
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
        <!-- Top Toolbar & Navigation -->
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.support.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-[#DEE8F7] dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:text-[#012383] text-xs font-bold transition-colors">
                    <svg class="size-4 transform rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>{{ __('العودة لقائمة التذاكر') }}</span>
                </a>

                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-black text-[#012383] dark:text-blue-400 bg-[#EAF1FB] dark:bg-slate-800 px-3 py-1 rounded-xl border border-blue-200 dark:border-slate-700">
                        #{{ $ticket->ticket_number }}
                    </span>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg border {{ $ticket->getStatusBadgeClass() }}" x-text="statusLabel">
                        {{ $ticket->getStatusLabel() }}
                    </span>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg border {{ $ticket->getPriorityBadgeClass() }}" x-text="priorityLabel">
                        {{ $ticket->getPriorityLabel() }}
                    </span>
                </div>
            </div>

            <!-- Quick Close / Reopen Buttons -->
            <div class="flex items-center gap-2">
                @if(! $ticket->isClosed())
                    <form action="{{ route('admin.support.close', $ticket->id) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من إغلاق هذه التذكرة؟') }}');">
                        @csrf
                        <button type="submit" class="px-3.5 py-1.5 rounded-xl border border-rose-300 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 text-xs font-bold hover:bg-rose-100 transition-colors">
                            {{ __('إغلاق التذكرة') }}
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.support.reopen', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors shadow-xs">
                            {{ __('إعادة فتح التذكرة') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2">
                <svg class="size-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Main Workspace: Split 2-Column Responsive Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            
            <!-- LEFT COLUMN: Ticket Controls & Student Meta (lg:col-span-4) -->
            <div class="lg:col-span-4 flex flex-col gap-4 order-2 lg:order-1">
                
                <!-- Student Card -->
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-5 shadow-xs flex flex-col gap-4 text-start">
                    <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('معلومات الطالب') }}</span>
                        <a href="{{ route('admin.students.show', $ticket->user->id) }}" target="_blank" class="text-xs font-bold text-[#012383] dark:text-blue-400 hover:underline flex items-center gap-1">
                            <span>{{ __('الملف الأكاديمي') }}</span>
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="size-12 rounded-2xl bg-[#EAF1FB] text-[#012383] font-black text-lg flex items-center justify-center shrink-0 border border-blue-200">
                            {{ mb_substr($ticket->user->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-bold text-slate-900 dark:text-white truncate">{{ $ticket->user->name }}</span>
                            <span class="text-xs text-slate-400 truncate">{{ $ticket->user->email }}</span>
                            @if($ticket->user->phone)
                                <span class="text-xs text-slate-500 font-mono mt-0.5">{{ $ticket->user->phone }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-[#DEE8F7] dark:border-slate-800 text-xs">
                        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 flex flex-col">
                            <span class="text-slate-400 text-[10px]">{{ __('الكورسات المشترك بها') }}</span>
                            <span class="font-bold text-slate-800 dark:text-white tabular-nums">{{ $ticket->user->courses->count() }} {{ __('كورس') }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 flex flex-col">
                            <span class="text-slate-400 text-[10px]">{{ __('تاريخ التسجيل') }}</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ $ticket->user->created_at?->format('Y-m-d') }}</span>
                        </div>
                    </div>

                    @if($ticket->course)
                        <div class="p-3 rounded-xl bg-blue-50/70 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900 flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-[#0B2A8F] dark:text-blue-300">{{ __('الكورس موضوع التذكرة:') }}</span>
                            <span class="font-bold text-xs text-[#012383] dark:text-blue-200">{{ $ticket->course->title_ar ?? $ticket->course->title }}</span>
                        </div>
                    @endif
                </div>

                <!-- Ticket Management & Quick Actions Card -->
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-5 shadow-xs flex flex-col gap-4 text-start">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('إدارة ومعالجة التذكرة') }}</span>

                    <!-- Status Changer -->
                    <form action="{{ route('admin.support.status.update', $ticket->id) }}" method="POST" class="flex flex-col gap-1.5">
                        @csrf
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('حالة التذكرة:') }}</label>
                        <div class="flex items-center gap-2">
                            <select name="status" class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-semibold bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                                <option value="open" @selected($ticket->status === 'open')>{{ __('مفتوحة وجديدة') }}</option>
                                <option value="in_progress" @selected($ticket->status === 'in_progress')>{{ __('قيد المعالجة') }}</option>
                                <option value="waiting_student" @selected($ticket->status === 'waiting_student')>{{ __('بانتظار رد الطالب') }}</option>
                                <option value="waiting_admin" @selected($ticket->status === 'waiting_admin')>{{ __('بانتظار رد المشرف') }}</option>
                                <option value="resolved" @selected($ticket->status === 'resolved')>{{ __('تم الحل بنجاح') }}</option>
                                <option value="closed" @selected($ticket->status === 'closed')>{{ __('مغلقة') }}</option>
                            </select>
                            <button type="submit" class="px-3 h-10 rounded-xl bg-[#012383] text-white text-xs font-bold hover:bg-[#0B2A8F] transition-colors shrink-0">
                                {{ __('حفظ') }}
                            </button>
                        </div>
                    </form>

                    <!-- Priority Changer -->
                    <form action="{{ route('admin.support.priority.update', $ticket->id) }}" method="POST" class="flex flex-col gap-1.5 pt-2 border-t border-[#DEE8F7] dark:border-slate-800">
                        @csrf
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('درجة الأولوية:') }}</label>
                        <div class="flex items-center gap-2">
                            <select name="priority" class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-semibold bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                                <option value="low" @selected($ticket->priority === 'low')>{{ __('منخفضة') }}</option>
                                <option value="medium" @selected($ticket->priority === 'medium')>{{ __('متوسطة') }}</option>
                                <option value="high" @selected($ticket->priority === 'high')>{{ __('مرتفعة') }}</option>
                                <option value="urgent" @selected($ticket->priority === 'urgent')>{{ __('عاجلة جداً') }}</option>
                            </select>
                            <button type="submit" class="px-3 h-10 rounded-xl bg-[#012383] text-white text-xs font-bold hover:bg-[#0B2A8F] transition-colors shrink-0">
                                {{ __('حفظ') }}
                            </button>
                        </div>
                    </form>

                    <!-- Assign Admin -->
                    <form action="{{ route('admin.support.assign', $ticket->id) }}" method="POST" class="flex flex-col gap-1.5 pt-2 border-t border-[#DEE8F7] dark:border-slate-800">
                        @csrf
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('إسناد التذكرة لمشرف:') }}</label>
                            @if($ticket->assigned_admin_id !== auth('admin')->id())
                                <button
                                    type="button"
                                    @click="$refs.assignAdminSelect.value = '{{ auth('admin')->id() }}'; $refs.assignForm.submit()"
                                    class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline"
                                >
                                    {{ __('إسناد لي فوراً') }}
                                </button>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <select
                                name="admin_id"
                                x-ref="assignAdminSelect"
                                class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-semibold bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]"
                            >
                                <option value="">{{ __('--- غير مسندة لأحد ---') }}</option>
                                @foreach($admins as $adm)
                                    <option value="{{ $adm->id }}" @selected($ticket->assigned_admin_id === $adm->id)>
                                        {{ $adm->name }} ({{ $adm->role_name }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-3 h-10 rounded-xl bg-[#012383] text-white text-xs font-bold hover:bg-[#0B2A8F] transition-colors shrink-0">
                                {{ __('إسناد') }}
                            </button>
                        </div>
                    </form>

                </div>

            </div>

            <!-- RIGHT COLUMN: Interactive Chat Console (lg:col-span-8) -->
            <div class="lg:col-span-8 flex flex-col bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden h-[650px] sm:h-[720px] order-1 lg:order-2">
                
                <!-- Chat Header Bar -->
                <div class="p-4 border-b border-[#DEE8F7] dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="size-3 rounded-full bg-emerald-500 animate-pulse"></div>
                        <h2 class="font-bold text-sm sm:text-base text-slate-900 dark:text-white truncate">
                            {{ $ticket->subject }}
                        </h2>
                    </div>

                    <div class="flex items-center gap-2 text-xs text-slate-400 shrink-0">
                        <span>{{ __('تحديث تلقائي حي') }}</span>
                        <span class="size-1.5 rounded-full bg-blue-500 animate-ping"></span>
                    </div>
                </div>

                <!-- Messages Stream Scrollable Area -->
                <div
                    id="admin-messages-scroll-area"
                    class="flex-1 overflow-y-auto p-4 sm:p-6 flex flex-col gap-4 bg-slate-50/40 dark:bg-slate-950/20"
                    x-ref="messagesContainer"
                >
                    @foreach($ticket->messages as $msg)
                        @if($msg->isSystemEvent())
                            <!-- System Event Pill -->
                            <div class="flex items-center justify-center my-1.5" data-message-id="{{ $msg->id }}">
                                <div class="px-3.5 py-1 bg-slate-200/80 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[11px] font-semibold text-center max-w-lg shadow-2xs">
                                    ⚙️ {{ $msg->message }} • {{ $msg->created_at?->diffForHumans() }}
                                </div>
                            </div>
                        @elseif($msg->isInternalNote())
                            <!-- Private Internal Admin Note (Distinct Golden / Amber Styling) -->
                            <div class="flex flex-col items-center my-2 max-w-2xl mx-auto w-full" data-message-id="{{ $msg->id }}">
                                <div class="w-full bg-amber-50 dark:bg-amber-950/30 border-2 border-amber-300 dark:border-amber-800/80 rounded-2xl p-4 shadow-xs">
                                    <div class="flex items-center justify-between text-amber-800 dark:text-amber-300 border-b border-amber-200 dark:border-amber-900/60 pb-2 mb-2 text-xs">
                                        <div class="flex items-center gap-1.5 font-bold">
                                            <span>🔒</span>
                                            <span>{{ __('ملاحظة داخلية خاصة بالمشرفين (مخفية عن الطالب)') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-[11px]">
                                            <span class="font-bold">{{ $msg->getSenderName() }}</span>
                                            <span>•</span>
                                            <span>{{ $msg->created_at?->format('H:i') }}</span>
                                        </div>
                                    </div>

                                    <div class="text-xs sm:text-sm text-amber-950 dark:text-amber-100 font-medium whitespace-pre-wrap leading-relaxed">
                                        {{ $msg->message }}
                                    </div>

                                    @if(!empty($msg->attachments))
                                        <div class="mt-2.5 pt-2 border-t border-amber-200 dark:border-amber-900/60 flex flex-col gap-1.5">
                                            @foreach($msg->attachments as $file)
                                                <a href="{{ $file['url'] }}" target="_blank" class="text-xs text-amber-800 dark:text-amber-300 hover:underline flex items-center gap-1">
                                                    <span>📎 {{ $file['name'] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($msg->isFromStudent())
                            <!-- Student Message (Left in LTR, Right in RTL) -->
                            <div class="flex items-start gap-3 self-start max-w-[85%] sm:max-w-[75%]" data-message-id="{{ $msg->id }}">
                                <div class="size-8 sm:size-9 rounded-full bg-[#EAF1FB] text-[#012383] font-bold text-xs flex items-center justify-center shrink-0 border border-blue-200 mt-1">
                                    {{ mb_substr($ticket->user->name, 0, 1) }}
                                </div>

                                <div class="flex flex-col gap-1 min-w-0 text-start">
                                    <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                        <span class="font-bold text-slate-900 dark:text-white">{{ $msg->getSenderName() }}</span>
                                        <span class="text-slate-400">({{ __('الطالب') }})</span>
                                        <span>•</span>
                                        <span>{{ $msg->created_at?->format('H:i') }}</span>
                                    </div>

                                    <div class="bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-slate-800 dark:text-slate-200 p-3.5 sm:p-4 rounded-2xl rounded-tl-xs shadow-xs text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap word-break">
                                        {{ $msg->message }}

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
                            <div class="flex flex-col items-end gap-1.5 self-end max-w-[85%] sm:max-w-[75%]" data-message-id="{{ $msg->id }}">
                                <div class="flex items-center gap-1.5 text-[11px] text-slate-400 pe-1">
                                    <span class="font-bold text-[#012383] dark:text-blue-400">{{ $msg->getSenderName() }}</span>
                                    <span>•</span>
                                    <span>{{ $msg->created_at?->format('H:i') }}</span>
                                </div>

                                <div class="bg-gradient-to-r from-[#012383] to-[#0B2A8F] text-white p-3.5 sm:p-4 rounded-2xl rounded-tr-xs shadow-sm text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap word-break">
                                    {{ $msg->message }}

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
                <div class="p-3 sm:p-4 bg-white dark:bg-slate-900 border-t border-[#DEE8F7] dark:border-slate-800">
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
                            <div class="flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-bold">
                                <button
                                    type="button"
                                    @click="isInternal = false"
                                    :class="!isInternal ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900'"
                                    class="px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5"
                                >
                                    <span>💬 {{ __('رد مباشر على الطالب') }}</span>
                                </button>
                                <button
                                    type="button"
                                    @click="isInternal = true"
                                    :class="isInternal ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900'"
                                    class="px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5"
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
                                    class="px-3 py-1.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 flex items-center gap-1.5"
                                >
                                    <span>⚡ {{ __('قوالب ردود جاهزة') }}</span>
                                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div
                                    x-show="openCanned"
                                    x-cloak
                                    @click.away="openCanned = false"
                                    class="absolute end-0 bottom-full mb-2 w-72 sm:w-80 bg-white dark:bg-slate-900 rounded-2xl border border-[#DEE8F7] dark:border-slate-800 shadow-xl p-2 z-30 flex flex-col gap-1 text-start"
                                >
                                    <span class="text-[11px] font-bold text-slate-400 px-2 py-1">{{ __('اختر رداً للإدراج الفوري:') }}</span>
                                    @foreach($cannedResponses as $canned)
                                        <button
                                            type="button"
                                            @click="insertCannedText(`{{ addslashes($canned['text']) }}`); openCanned = false;"
                                            class="p-2 rounded-xl text-start hover:bg-blue-50 dark:hover:bg-slate-800 transition-colors flex flex-col gap-0.5"
                                        >
                                            <span class="text-xs font-bold text-[#012383] dark:text-blue-400">{{ $canned['title'] }}</span>
                                            <span class="text-[11px] text-slate-500 truncate max-w-full">{{ $canned['text'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Selected Files Tag Bar -->
                        <div x-show="selectedFiles.length > 0" class="flex items-center gap-2 flex-wrap pb-1">
                            <template x-for="(f, i) in selectedFiles" :key="i">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#012383] dark:text-blue-300 text-[11px] font-bold border border-blue-200 dark:border-blue-900">
                                    <span class="truncate max-w-[150px]" x-text="f.name"></span>
                                    <button type="button" @click="removeSelectedFile(i)" class="text-rose-500 hover:text-rose-700">✕</button>
                                </span>
                            </template>
                        </div>

                        <!-- Input Controls Row -->
                        <div class="flex items-end gap-2">
                            <!-- Attach button -->
                            <div class="relative shrink-0">
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
                                    class="size-11 rounded-xl border border-[#DEE8F7] dark:border-slate-700 hover:border-[#012383] text-slate-500 bg-slate-50 dark:bg-slate-800 flex items-center justify-center transition-colors"
                                    title="{{ __('إرفاق ملف أو تسجيل') }}"
                                >
                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                </button>
                            </div>

                            <!-- Textarea -->
                            <div class="flex-1 relative">
                                <textarea
                                    name="message"
                                    x-model="replyText"
                                    x-ref="replyTextArea"
                                    @keydown.ctrl.enter="$refs.adminReplyForm.requestSubmit()"
                                    rows="2"
                                    :placeholder="isInternal ? '{{ __('اكتب ملاحظة داخلية خاصة بالمشرفين فقط (لن يراها الطالب)...') }}' : '{{ __('اكتب ردك الأكاديمي أو التوجيهي للطالب هنا...') }}'"
                                    :class="isInternal ? 'border-amber-400 bg-amber-50/30' : 'border-[#DEE8F7] dark:border-slate-700'"
                                    class="w-full min-h-[44px] max-h-36 py-2.5 px-4 rounded-xl border text-xs sm:text-sm focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all outline-hidden font-medium resize-none dark:bg-slate-800 dark:text-white"
                                ></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="sending || (replyText.trim() === '' && selectedFiles.length === 0)"
                                :class="isInternal ? 'bg-amber-600 hover:bg-amber-700' : 'bg-[#012383] hover:bg-[#0B2A8F]'"
                                class="h-11 px-6 rounded-xl disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2 shadow-xs transition-all shrink-0"
                            >
                                <span x-show="!sending" x-text="isInternal ? '{{ __('حفظ الملاحظة') }}' : '{{ __('إرسال الرد') }}'"></span>
                                <span x-show="sending">{{ __('جاري الإرسال...') }}</span>
                                <svg x-show="!sending" class="size-4 transform rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
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
                        div.className = 'flex items-center justify-center my-1.5';
                        div.innerHTML = `<div class="px-3.5 py-1 bg-slate-200/80 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[11px] font-semibold text-center max-w-lg shadow-2xs">⚙️ ${this.escapeHtml(msg.message)} • ${msg.time_formatted}</div>`;
                    } else if (msg.is_internal) {
                        div.className = 'flex flex-col items-center my-2 max-w-2xl mx-auto w-full';
                        div.innerHTML = `
                            <div class="w-full bg-amber-50 dark:bg-amber-950/30 border-2 border-amber-300 dark:border-amber-800/80 rounded-2xl p-4 shadow-xs">
                                <div class="flex items-center justify-between text-amber-800 dark:text-amber-300 border-b border-amber-200 dark:border-amber-900/60 pb-2 mb-2 text-xs">
                                    <div class="flex items-center gap-1.5 font-bold">
                                        <span>🔒</span>
                                        <span>{{ __('ملاحظة داخلية خاصة بالمشرفين') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-[11px]">
                                        <span class="font-bold">${this.escapeHtml(msg.sender_name)}</span> • <span>${msg.exact_time}</span>
                                    </div>
                                </div>
                                <div class="text-xs sm:text-sm text-amber-950 dark:text-amber-100 font-medium whitespace-pre-wrap leading-relaxed">
                                    ${this.escapeHtml(msg.message)}
                                </div>
                            </div>
                        `;
                    } else if (msg.is_student) {
                        div.className = 'flex items-start gap-3 self-start max-w-[85%] sm:max-w-[75%]';
                        div.innerHTML = `
                            <div class="size-8 sm:size-9 rounded-full bg-[#EAF1FB] text-[#012383] font-bold text-xs flex items-center justify-center shrink-0 border border-blue-200 mt-1">
                                ${this.escapeHtml(msg.sender_name.charAt(0))}
                            </div>
                            <div class="flex flex-col gap-1 min-w-0 text-start">
                                <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                    <span class="font-bold text-slate-900 dark:text-white">${this.escapeHtml(msg.sender_name)}</span>
                                    <span class="text-slate-400">({{ __('الطالب') }})</span> • <span>${msg.exact_time}</span>
                                </div>
                                <div class="bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-slate-800 dark:text-slate-200 p-3.5 sm:p-4 rounded-2xl rounded-tl-xs shadow-xs text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap word-break">
                                    ${this.escapeHtml(msg.message)}
                                </div>
                            </div>
                        `;
                    } else {
                        div.className = 'flex flex-col items-end gap-1.5 self-end max-w-[85%] sm:max-w-[75%]';
                        div.innerHTML = `
                            <div class="flex items-center gap-1.5 text-[11px] text-slate-400 pe-1">
                                <span class="font-bold text-[#012383] dark:text-blue-400">${this.escapeHtml(msg.sender_name)}</span> • <span>${msg.exact_time}</span>
                            </div>
                            <div class="bg-gradient-to-r from-[#012383] to-[#0B2A8F] text-white p-3.5 sm:p-4 rounded-2xl rounded-tr-xs shadow-sm text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap word-break">
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
