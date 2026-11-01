<x-layouts::app :title="$ticket->subject . ' - ' . __('محادثة الدعم')">
    <div
        class="flex flex-col gap-4 w-full text-start max-w-5xl mx-auto min-h-[calc(100vh-140px)]"
        x-data="studentChat({
            ticketId: {{ $ticket->id }},
            lastId: {{ $ticket->publicMessages->max('id') ?? 0 }},
            pollUrl: '{{ route('support.poll', $ticket->id) }}',
            isClosed: {{ $ticket->isClosed() ? 'true' : 'false' }}
        })"
        x-init="initChat()"
    >
        <!-- Top Navigation / Breadcrumb -->
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <a href="{{ route('support.index') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-[#0B2A8F] hover:text-[#012383] transition-colors">
                <svg class="size-4 transform rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>{{ __('العودة لكافة التذاكر') }}</span>
            </a>

            <!-- Close or Reopen Quick Actions -->
            <div class="flex items-center gap-2">
                @if(! $ticket->isClosed())
                    <button
                        type="button"
                        @click="showCloseModal = true"
                        class="px-3.5 py-1.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-colors"
                    >
                        {{ __('إغلاق التذكرة') }}
                    </button>
                @else
                    <form action="{{ route('support.reopen', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors shadow-xs">
                            {{ __('إعادة فتح التذكرة') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-3">
                <svg class="size-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold flex items-center gap-3">
                <svg class="size-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Ticket Summary Card Header -->
        <div class="bg-white border border-[#DEE8F7] rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex-1 min-w-0 flex flex-col gap-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-mono text-xs font-black text-[#012383] bg-[#EAF1FB] px-2.5 py-1 rounded-lg border border-[#DEE8F7]">
                        #{{ $ticket->ticket_number }}
                    </span>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg border {{ $ticket->getStatusBadgeClass() }}" x-text="statusLabel">
                        {{ $ticket->getStatusLabel() }}
                    </span>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg border {{ $ticket->getPriorityBadgeClass() }}">
                        {{ $ticket->getPriorityLabel() }}
                    </span>
                    <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                        {{ $ticket->getCategoryLabel() }}
                    </span>
                </div>

                <h1 class="font-bold text-lg sm:text-xl text-[#012383] truncate">
                    {{ $ticket->subject }}
                </h1>

                @if($ticket->course)
                    <div class="text-xs font-semibold text-[#0B2A8F] flex items-center gap-1.5">
                        <span>📚 {{ __('الكورس المرتبط:') }}</span>
                        <span class="underline">{{ $ticket->course->title_ar ?? $ticket->course->title }}</span>
                    </div>
                @endif
            </div>

            <!-- Assigned Admin Info Box -->
            <div class="flex items-center gap-3 bg-slate-50 border border-slate-200/70 p-2.5 rounded-xl shrink-0">
                <div class="size-10 rounded-full bg-[#EAF1FB] text-[#012383] flex items-center justify-center font-bold text-sm shrink-0 border border-blue-200">
                    @if($ticket->assignedAdmin)
                        {{ mb_substr($ticket->assignedAdmin->name, 0, 1) }}
                    @else
                        🎧
                    @endif
                </div>
                <div class="flex flex-col text-start">
                    <span class="text-[11px] text-slate-400 font-medium">{{ __('فريق المتابعة') }}</span>
                    <span class="text-xs font-bold text-slate-800">
                        {{ $ticket->assignedAdmin->name ?? __('فريق الدعم السريري والتقني') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Chat Container Window -->
        <div class="bg-white border border-[#DEE8F7] rounded-2xl shadow-sm flex flex-col flex-1 overflow-hidden h-[580px] sm:h-[620px]">
            
            <!-- Messages Stream Area -->
            <div
                id="messages-scroll-area"
                class="flex-1 overflow-y-auto p-4 sm:p-6 flex flex-col gap-4 bg-slate-50/50"
                x-ref="messagesContainer"
            >
                @foreach($ticket->publicMessages as $msg)
                    @if($msg->isSystemEvent())
                        <!-- System Timeline Event -->
                        <div class="flex items-center justify-center my-2" data-message-id="{{ $msg->id }}">
                            <div class="px-3.5 py-1.5 bg-slate-200/70 text-slate-600 rounded-full text-[11px] font-semibold text-center max-w-md shadow-2xs">
                                ℹ️ {{ $msg->message }} • {{ $msg->created_at?->diffForHumans() }}
                            </div>
                        </div>
                    @elseif($msg->isFromStudent())
                        <!-- Student Message Bubble (Outgoing) -->
                        <div class="flex flex-col items-end gap-1.5 self-end max-w-[85%] sm:max-w-[70%]" data-message-id="{{ $msg->id }}">
                            <div class="flex items-center gap-1.5 text-[11px] text-slate-400 pe-1">
                                <span class="font-bold text-[#012383]">{{ __('أنت') }}</span>
                                <span>•</span>
                                <span>{{ $msg->created_at?->format('H:i') }}</span>
                            </div>
                            
                            <div class="bg-gradient-to-r from-[#012383] to-[#0B2A8F] text-white p-3.5 sm:p-4 rounded-2xl rounded-tr-xs shadow-sm text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap word-break">
                                {{ $msg->message }}

                                <!-- Attachments -->
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

                            <div class="text-[10px] text-slate-400 pe-1 flex items-center gap-1">
                                @if($msg->read_at)
                                    <span class="text-blue-600 font-bold">✓✓ {{ __('تمت القراءة') }}</span>
                                @else
                                    <span>✓ {{ __('تم الإرسال') }}</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Admin Message Bubble (Incoming) -->
                        <div class="flex items-start gap-3 self-start max-w-[85%] sm:max-w-[75%]" data-message-id="{{ $msg->id }}">
                            <img src="{{ $msg->getSenderAvatar() }}" alt="{{ $msg->getSenderName() }}" class="size-8 sm:size-9 rounded-full object-cover shrink-0 border border-slate-200 mt-1" />
                            
                            <div class="flex flex-col gap-1 min-w-0">
                                <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                    <span class="font-bold text-slate-900">{{ $msg->getSenderName() }}</span>
                                    <span class="px-1.5 py-0.2 bg-[#F2AE2E]/20 text-[#012383] rounded-md font-bold text-[10px]">{{ __('فريق المنصة') }}</span>
                                    <span>•</span>
                                    <span>{{ $msg->created_at?->format('H:i') }}</span>
                                </div>

                                <div class="bg-white border border-[#DEE8F7] text-slate-800 p-3.5 sm:p-4 rounded-2xl rounded-tl-xs shadow-xs text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap word-break">
                                    {{ $msg->message }}

                                    <!-- Attachments -->
                                    @if(!empty($msg->attachments))
                                        <div class="mt-3 flex flex-col gap-2 pt-2 border-t border-slate-100">
                                            @foreach($msg->attachments as $file)
                                                @if(!empty($file['is_image']))
                                                    <div class="rounded-xl overflow-hidden border border-slate-200 max-w-[280px]">
                                                        <a href="{{ $file['url'] }}" target="_blank">
                                                            <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-auto object-cover hover:scale-105 transition-transform" />
                                                        </a>
                                                    </div>
                                                @elseif(!empty($file['is_audio']))
                                                    <div class="bg-slate-50 p-2 rounded-xl border border-slate-200">
                                                        <p class="text-[11px] mb-1 truncate text-slate-700">🎙️ {{ $file['name'] }}</p>
                                                        <audio controls class="w-full h-8">
                                                            <source src="{{ $file['url'] }}" type="{{ $file['mime'] }}">
                                                        </audio>
                                                    </div>
                                                @else
                                                    <a href="{{ $file['url'] }}" target="_blank" download class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-colors text-xs text-slate-700">
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
                    @endif
                @endforeach

                <!-- Dynamic container for newly polled messages -->
                <div x-ref="newMessagesSlot" class="contents"></div>
            </div>

            <!-- Composer / Reply Box -->
            @if(! $ticket->isClosed())
                <div class="p-3 sm:p-4 bg-white border-t border-[#DEE8F7]">
                    <form
                        action="{{ route('support.reply', $ticket->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        @submit="handleReplySubmit($event)"
                        x-ref="replyForm"
                        class="flex flex-col gap-2"
                    >
                        @csrf

                        <!-- Selected Files Tag Bar -->
                        <div x-show="selectedFiles.length > 0" class="flex items-center gap-2 flex-wrap pb-1">
                            <template x-for="(f, i) in selectedFiles" :key="i">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-[#012383] text-[11px] font-bold border border-blue-200">
                                    <span class="truncate max-w-[150px]" x-text="f.name"></span>
                                    <button type="button" @click="removeSelectedFile(i)" class="text-rose-500 hover:text-rose-700">✕</button>
                                </span>
                            </template>
                        </div>

                        <div class="flex items-end gap-2">
                            <!-- Attach file button -->
                            <div class="relative shrink-0">
                                <input
                                    type="file"
                                    id="replyAttachments"
                                    name="attachments[]"
                                    multiple
                                    x-ref="replyFileInput"
                                    @change="onReplyFilesChange($event)"
                                    accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.zip,.mp3,.wav,.ogg,.m4a"
                                    class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                                />
                                <button
                                    type="button"
                                    class="size-11 rounded-xl border border-[#DEE8F7] hover:border-[#012383] text-slate-500 hover:text-[#012383] bg-slate-50 flex items-center justify-center transition-colors"
                                    title="{{ __('إرفاق ملف أو صورة') }}"
                                >
                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                </button>
                            </div>

                            <!-- Text input textarea -->
                            <div class="flex-1 relative">
                                <textarea
                                    name="message"
                                    x-model="replyText"
                                    @keydown.ctrl.enter="$refs.replyForm.requestSubmit()"
                                    rows="1"
                                    placeholder="{{ __('اكتب رسالتك أو استفسارك هنا...') }}"
                                    class="w-full min-h-[44px] max-h-32 py-2.5 px-4 rounded-xl border border-[#DEE8F7] text-xs sm:text-sm focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all outline-hidden font-medium resize-none"
                                ></textarea>
                            </div>

                            <!-- Submit button -->
                            <button
                                type="submit"
                                :disabled="sending || (replyText.trim() === '' && selectedFiles.length === 0)"
                                class="h-11 px-5 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2 shadow-xs transition-all shrink-0"
                            >
                                <span x-show="!sending">{{ __('إرسال') }}</span>
                                <span x-show="sending">{{ __('جاري الإرسال...') }}</span>
                                <svg x-show="!sending" class="size-4 transform rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Closed Ticket Banner -->
                <div class="p-4 bg-slate-100 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-start">
                    <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-600">
                        <svg class="size-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>{{ __('هذه التذكرة مغلقة حالياً. إذا كنت بحاجة للمزيد من المساعدة يمكنك إعادة فتحها.') }}</span>
                    </div>

                    <form action="{{ route('support.reopen', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-xl bg-[#012383] text-white text-xs font-bold hover:bg-[#0B2A8F] transition-colors shadow-xs">
                            {{ __('إعادة فتح التذكرة') }}
                        </button>
                    </form>
                </div>
            @endif

        </div>

        <!-- Close Ticket Confirmation Modal -->
        <div
            x-show="showCloseModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4"
        >
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl flex flex-col gap-4 text-start">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-lg text-slate-900">{{ __('إغلاق تذكرة الدعم') }}</h3>
                    <button type="button" @click="showCloseModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <p class="text-xs sm:text-sm text-slate-600">
                    {{ __('هل أنت متأكد من رغبتك في إغلاق هذه التذكرة؟ يمكنك دائماً إعادة فتحها إذا واجهتك أي صعوبة أخرى.') }}
                </p>

                <form action="{{ route('support.close', $ticket->id) }}" method="POST" class="flex flex-col gap-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('ملاحظة أو سبب الإغلاق (اختياري)') }}</label>
                        <input type="text" name="reason" placeholder="{{ __('مثال: تم حل المشكلة شكراً لكم') }}" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs focus:border-[#012383] outline-hidden">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="showCloseModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-bold">
                            {{ __('تأكيد الإغلاق') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Live Student Chat Script -->
    <script>
        function studentChat(config) {
            return {
                ticketId: config.ticketId,
                lastId: config.lastId,
                pollUrl: config.pollUrl,
                isClosed: config.isClosed,
                statusLabel: '{{ $ticket->getStatusLabel() }}',
                showCloseModal: false,
                replyText: '',
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
                    if (this.isClosed) return;
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
                        console.error('Support polling error:', e);
                    }
                },

                appendMessage(msg) {
                    const slot = this.$refs.newMessagesSlot;
                    if (!slot) return;

                    const div = document.createElement('div');
                    if (msg.is_system) {
                        div.className = 'flex items-center justify-center my-2';
                        div.innerHTML = `<div class="px-3.5 py-1.5 bg-slate-200/70 text-slate-600 rounded-full text-[11px] font-semibold text-center max-w-md shadow-2xs">ℹ️ ${this.escapeHtml(msg.message)} • ${msg.time_formatted}</div>`;
                    } else if (msg.is_student) {
                        div.className = 'flex flex-col items-end gap-1.5 self-end max-w-[85%] sm:max-w-[70%]';
                        div.innerHTML = `
                            <div class="flex items-center gap-1.5 text-[11px] text-slate-400 pe-1">
                                <span class="font-bold text-[#012383]">{{ __('أنت') }}</span> • <span>${msg.exact_time}</span>
                            </div>
                            <div class="bg-gradient-to-r from-[#012383] to-[#0B2A8F] text-white p-3.5 sm:p-4 rounded-2xl rounded-tr-xs shadow-sm text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap word-break">
                                ${this.escapeHtml(msg.message)}
                            </div>
                        `;
                    } else {
                        div.className = 'flex items-start gap-3 self-start max-w-[85%] sm:max-w-[75%]';
                        div.innerHTML = `
                            <img src="${msg.sender_avatar}" alt="${this.escapeHtml(msg.sender_name)}" class="size-8 sm:size-9 rounded-full object-cover shrink-0 border border-slate-200 mt-1" />
                            <div class="flex flex-col gap-1 min-w-0">
                                <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                    <span class="font-bold text-slate-900">${this.escapeHtml(msg.sender_name)}</span>
                                    <span class="px-1.5 py-0.2 bg-[#F2AE2E]/20 text-[#012383] rounded-md font-bold text-[10px]">{{ __('فريق المنصة') }}</span>
                                    <span>•</span>
                                    <span>${msg.exact_time}</span>
                                </div>
                                <div class="bg-white border border-[#DEE8F7] text-slate-800 p-3.5 sm:p-4 rounded-2xl rounded-tl-xs shadow-xs text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-wrap word-break">
                                    ${this.escapeHtml(msg.message)}
                                </div>
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

                onReplyFilesChange(e) {
                    this.selectedFiles = Array.from(e.target.files);
                },

                removeSelectedFile(index) {
                    const dt = new DataTransfer();
                    const input = this.$refs.replyFileInput;
                    this.selectedFiles.splice(index, 1);
                    this.selectedFiles.forEach(f => dt.items.add(f));
                    input.files = dt.files;
                },

                async handleReplySubmit(e) {
                    if (this.replyText.trim() === '' && this.selectedFiles.length === 0) {
                        e.preventDefault();
                        return;
                    }
                    this.sending = true;
                    // Standard form submission to support multipart file upload reliably
                }
            };
        }
    </script>
</x-layouts::app>
