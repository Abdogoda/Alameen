<x-layouts::app :title="$ticket->subject . ' - ' . __('محادثة الدعم')">
    <div
        class="flex flex-col gap-3.5 sm:gap-4 w-full text-start max-w-5xl mx-auto flex-1 min-h-[520px]"
        x-data="studentChat({
            ticketId: {{ $ticket->id }},
            lastId: {{ $ticket->publicMessages->max('id') ?? 0 }},
            pollUrl: '{{ route('support.poll', $ticket->id) }}',
            isClosed: {{ $ticket->isClosed() ? 'true' : 'false' }}
        })"
        x-init="initChat()"
    >
        <!-- Top App Navigation & Quick Action Bar -->
        <div class="flex items-center justify-between gap-2.5 flex-wrap">
            <div class="flex items-center gap-2">
                <a
                    href="{{ route('support.index') }}"
                    class="size-9 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-[#EAF1FB] dark:hover:bg-slate-800 text-[#012383] dark:text-blue-400 flex items-center justify-center transition-colors shadow-2xs group"
                    title="{{ __('العودة لكافة التذاكر') }}"
                >
                    <svg class="size-4.5 transform rtl:rotate-180 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <div class="flex items-center gap-1.5 flex-wrap">
                    <!-- Ticket Number Capsule with Copy feature -->
                    <button
                        type="button"
                        @click="copyTicketNumber()"
                        class="inline-flex items-center gap-1.5 font-mono text-xs font-black text-[#012383] dark:text-blue-300 bg-[#EAF1FB] dark:bg-slate-800/90 hover:bg-blue-100 dark:hover:bg-slate-800 px-2.5 py-1.5 rounded-xl border border-blue-200/70 dark:border-slate-700 shadow-2xs transition-all cursor-pointer"
                        title="{{ __('انقر لنسخ رقم التذكرة') }}"
                    >
                        <span>#{{ $ticket->ticket_number }}</span>
                        <svg x-show="!copied" class="size-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span x-show="copied" x-cloak class="text-emerald-600 dark:text-emerald-400 font-sans text-[10px] font-bold">✓ {{ __('تم النسخ') }}</span>
                    </button>

                    <!-- Status Badge -->
                    <span
                        class="text-xs font-bold px-2.5 py-1.5 rounded-xl border shadow-2xs transition-colors {{ $ticket->getStatusBadgeClass() }}"
                        x-text="statusLabel"
                    >
                        {{ $ticket->getStatusLabel() }}
                    </span>

                    <!-- Priority Badge (Hidden on smallest screens if crowded) -->
                    <span class="hidden xs:inline-flex text-xs font-bold px-2.5 py-1.5 rounded-xl border shadow-2xs {{ $ticket->getPriorityBadgeClass() }}">
                        {{ $ticket->getPriorityLabel() }}
                    </span>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-2 ms-auto">
                <!-- Details toggle button on mobile -->
                <button
                    type="button"
                    @click="showDetails = !showDetails"
                    class="sm:hidden px-2.5 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 text-xs font-bold flex items-center gap-1 shadow-2xs transition-colors"
                >
                    <svg class="size-3.5 text-[#012383] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('التفاصيل') }}</span>
                </button>

                @if(! $ticket->isClosed())
                    <button
                        type="button"
                        @click="showCloseModal = true"
                        class="px-3 py-1.5 rounded-xl border border-rose-200 dark:border-rose-900/60 bg-rose-50/80 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 text-xs font-bold transition-all shadow-2xs active:scale-95"
                    >
                        {{ __('إغلاق التذكرة') }}
                    </button>
                @else
                    <form action="{{ route('support.reopen', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-xs active:scale-95">
                            {{ __('إعادة فتح التذكرة') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs sm:text-sm font-semibold flex items-center gap-2.5 shadow-2xs">
                <svg class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs sm:text-sm font-semibold flex items-center gap-2.5 shadow-2xs">
                <svg class="size-4 shrink-0 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Ticket Summary & Context Card (Compact & Responsive) -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-3.5 sm:p-4.5 shadow-xs transition-all">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex-1 min-w-0 flex flex-col gap-1.5">
                    <!-- Title -->
                    <h1 class="font-bold text-base sm:text-lg lg:text-xl text-[#012383] dark:text-white leading-tight break-words">
                        {{ $ticket->subject }}
                    </h1>

                    <!-- Badges & Metadata Row -->
                    <div class="flex items-center gap-2 flex-wrap text-xs">
                        <span class="inline-flex items-center gap-1 font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 rounded-lg border border-slate-200/60 dark:border-slate-700">
                            <span>🏷️</span>
                            <span>{{ $ticket->getCategoryLabel() }}</span>
                        </span>

                        @if($ticket->course)
                            <span class="inline-flex items-center gap-1.5 font-semibold text-[#0B2A8F] dark:text-blue-300 bg-blue-50 dark:bg-blue-950/40 px-2.5 py-0.5 rounded-lg border border-blue-200/60 dark:border-blue-900 truncate max-w-full sm:max-w-md">
                                <span>📚</span>
                                <span class="truncate">{{ $ticket->course->title_ar ?? $ticket->course->title }}</span>
                            </span>
                        @endif

                        <span class="text-slate-400 dark:text-slate-500 text-[11px] hidden sm:inline-flex items-center gap-1">
                            <span>🕒</span>
                            <span>{{ __('أنشئت:') }} {{ $ticket->created_at?->diffForHumans() }}</span>
                        </span>
                    </div>
                </div>

                <!-- Assigned Support Pill / Box -->
                <div class="flex items-center gap-2.5 bg-slate-50 dark:bg-slate-800/70 border border-slate-200/70 dark:border-slate-700/80 p-2 sm:p-2.5 rounded-xl shrink-0 self-start md:self-center">
                    <div class="size-8 sm:size-9 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-300 flex items-center justify-center font-bold text-xs sm:text-sm shrink-0 border border-blue-200/80 dark:border-slate-700 shadow-2xs">
                        @if($ticket->assignedAdmin)
                            {{ mb_substr($ticket->assignedAdmin->name, 0, 1) }}
                        @else
                            🎧
                        @endif
                    </div>
                    <div class="flex flex-col text-start min-w-0">
                        <span class="text-[10px] text-slate-400 dark:text-slate-400 font-medium leading-none">{{ __('فريق المتابعة') }}</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate max-w-[160px] sm:max-w-[200px] mt-0.5">
                            {{ $ticket->assignedAdmin->name ?? __('فريق الدعم السريري والتقني') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Optional Collapsible Details on Mobile Screen -->
            <div
                x-show="showDetails"
                x-cloak
                x-collapse
                class="pt-3 mt-3 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-2 text-xs text-slate-600 dark:text-slate-300"
            >
                <div class="grid grid-cols-2 gap-2 text-start">
                    <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-[10px] text-slate-400 block">{{ __('تاريخ الإنشاء:') }}</span>
                        <span class="font-bold">{{ $ticket->created_at?->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-[10px] text-slate-400 block">{{ __('آخر تحديث:') }}</span>
                        <span class="font-bold">{{ $ticket->last_message_at?->diffForHumans() ?? __('الآن') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Console & Messaging Stream Window -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl shadow-sm flex flex-col flex-1 overflow-hidden h-[calc(100dvh-185px)] min-h-[450px] max-h-[760px] sm:h-[590px] lg:h-[660px] transition-all relative">

            <!-- Chat Stream Scrollable Area -->
            <div
                id="messages-scroll-area"
                class="flex-1 overflow-y-auto p-3.5 sm:p-5 flex flex-col gap-3.5 bg-slate-50/60 dark:bg-slate-950/30 scroll-smooth"
                x-ref="messagesContainer"
            >
                @foreach($ticket->publicMessages as $msg)
                    @if($msg->isSystemEvent())
                        <!-- System Timeline Pill -->
                        <div class="flex items-center justify-center my-1.5" data-message-id="{{ $msg->id }}">
                            <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-200/70 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 border border-slate-300/40 dark:border-slate-700/50 rounded-full text-[11px] font-semibold text-center max-w-md shadow-2xs backdrop-blur-xs">
                                <svg class="size-3.5 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $msg->message }}</span>
                                <span class="text-slate-400 dark:text-slate-500">•</span>
                                <span class="text-slate-400 dark:text-slate-500">{{ $msg->created_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    @elseif($msg->isFromStudent())
                        <!-- Student Message Bubble (Outgoing) -->
                        <div class="flex flex-col items-end gap-1 self-end max-w-[88%] sm:max-w-[75%]" data-message-id="{{ $msg->id }}">
                            <div class="flex items-center gap-1.5 text-[10.5px] text-slate-400 dark:text-slate-500 pe-1">
                                <span class="font-bold text-[#012383] dark:text-blue-400">{{ __('أنت') }}</span>
                                <span>•</span>
                                <span dir="ltr">{{ $msg->created_at?->format('H:i') }}</span>
                            </div>

                            <div class="w-fit text-start bg-gradient-to-br from-[#012383] via-[#082b9e] to-[#0B2A8F] text-white px-4 py-3 rounded-2xl rounded-tr-sm rtl:rounded-tr-2xl rtl:rounded-tl-sm shadow-sm text-xs sm:text-sm font-normal leading-relaxed whitespace-pre-wrap break-words">
                                {{ trim($msg->message) }}

                                <!-- Attachments -->
                                @if(!empty($msg->attachments))
                                    <div class="mt-2.5 flex flex-col gap-2 pt-2 border-t border-white/20">
                                        @foreach($msg->attachments as $file)
                                            @if(!empty($file['is_image']))
                                                <div class="rounded-xl overflow-hidden border border-white/25 max-w-[260px] sm:max-w-[320px] shadow-2xs group relative">
                                                    <a href="{{ $file['url'] }}" target="_blank" class="block overflow-hidden">
                                                        <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-auto max-h-52 object-cover group-hover:scale-105 transition-transform duration-300" />
                                                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1">
                                                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                                            <span>{{ __('عرض بالكامل') }}</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            @elseif(!empty($file['is_audio']))
                                                <div class="p-2.5 rounded-xl bg-white/15 border border-white/20 max-w-[280px]">
                                                    <div class="flex items-center gap-1.5 text-[11px] mb-1.5 truncate text-white/90 font-medium">
                                                        <span>🎙️</span>
                                                        <span class="truncate">{{ $file['name'] }}</span>
                                                    </div>
                                                    <audio controls class="w-full h-8 rounded-lg">
                                                        <source src="{{ $file['url'] }}" type="{{ $file['mime'] }}">
                                                    </audio>
                                                </div>
                                            @else
                                                <a href="{{ $file['url'] }}" target="_blank" download class="flex items-center justify-between gap-3 p-2.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 transition-colors text-xs text-white group">
                                                    <div class="flex items-center gap-2 truncate">
                                                        <span class="p-1 rounded-lg bg-white/20 text-white shrink-0 font-mono text-[10px] font-bold uppercase">
                                                            {{ pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'FILE' }}
                                                        </span>
                                                        <span class="truncate font-medium">{{ $file['name'] }}</span>
                                                    </div>
                                                    <svg class="size-4 text-white/80 group-hover:translate-y-0.5 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Read Receipts Status -->
                            <div class="flex items-center gap-1 text-[10px] text-slate-400 dark:text-slate-500 pe-1">
                                @if($msg->read_at)
                                    <span class="text-blue-600 dark:text-blue-400 font-bold flex items-center gap-0.5">
                                        <svg class="size-3.5 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M18 7l-8.5 8.5-4-4-1.5 1.5 5.5 5.5 10-10L18 7zm3 0l-10 10-1.5-1.5 10-10L21 7z"/></svg>
                                        <span>{{ __('تمت القراءة') }}</span>
                                    </span>
                                @else
                                    <span class="flex items-center gap-0.5 text-slate-400">
                                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>{{ __('تم الإرسال') }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Support Agent Message Bubble (Incoming) -->
                        <div class="flex items-start gap-2.5 sm:gap-3 self-start max-w-[88%] sm:max-w-[75%]" data-message-id="{{ $msg->id }}">
                            <div class="relative shrink-0 mt-1">
                                <img src="{{ $msg->getSenderAvatar() }}" alt="{{ $msg->getSenderName() }}" class="size-8 sm:size-9 rounded-full object-cover border border-[#DEE8F7] dark:border-slate-700 shadow-2xs" />
                                <span class="absolute -bottom-0.5 -end-0.5 size-2.5 bg-emerald-500 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
                            </div>

                            <div class="flex flex-col gap-1 min-w-0 text-start">
                                <div class="flex items-center gap-1.5 flex-wrap text-[11px] text-slate-500 dark:text-slate-400">
                                    <span class="font-bold text-slate-900 dark:text-slate-100">{{ $msg->getSenderName() }}</span>
                                    <span class="px-1.5 py-0.5 bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-300 border border-blue-200/60 dark:border-slate-700 rounded-md font-bold text-[10px]">{{ __('فريق المنصة') }}</span>
                                    <span>•</span>
                                    <span dir="ltr">{{ $msg->created_at?->format('H:i') }}</span>
                                </div>

                                <div class="w-fit text-start bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700/80 text-slate-800 dark:text-slate-100 px-4 py-3 rounded-2xl rounded-tl-sm rtl:rounded-tl-2xl rtl:rounded-tr-sm shadow-xs text-xs sm:text-sm font-normal leading-relaxed whitespace-pre-wrap break-words">
                                    {{ trim($msg->message) }}

                                    <!-- Attachments -->
                                    @if(!empty($msg->attachments))
                                        <div class="mt-2.5 flex flex-col gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                                            @foreach($msg->attachments as $file)
                                                @if(!empty($file['is_image']))
                                                    <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 max-w-[260px] sm:max-w-[320px] shadow-2xs group relative">
                                                        <a href="{{ $file['url'] }}" target="_blank" class="block overflow-hidden">
                                                            <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-auto max-h-52 object-cover group-hover:scale-105 transition-transform duration-300" />
                                                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1">
                                                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                                                <span>{{ __('عرض بالكامل') }}</span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @elseif(!empty($file['is_audio']))
                                                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 max-w-[280px]">
                                                        <p class="text-[11px] mb-1.5 truncate text-slate-700 dark:text-slate-300 font-medium">🎙️ {{ $file['name'] }}</p>
                                                        <audio controls class="w-full h-8 rounded-lg">
                                                            <source src="{{ $file['url'] }}" type="{{ $file['mime'] }}">
                                                        </audio>
                                                    </div>
                                                @else
                                                    <a href="{{ $file['url'] }}" target="_blank" download class="flex items-center justify-between gap-3 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-750 border border-slate-200 dark:border-slate-700 transition-colors text-xs text-slate-700 dark:text-slate-200 group">
                                                        <div class="flex items-center gap-2 truncate">
                                                            <span class="p-1 rounded-lg bg-blue-100 dark:bg-blue-950 text-[#012383] dark:text-blue-300 shrink-0 font-mono text-[10px] font-bold uppercase">
                                                                {{ pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'FILE' }}
                                                            </span>
                                                            <span class="truncate font-medium">{{ $file['name'] }}</span>
                                                        </div>
                                                        <svg class="size-4 text-slate-400 group-hover:translate-y-0.5 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
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

                <!-- Polling Dynamic Message Slot -->
                <div x-ref="newMessagesSlot" class="contents"></div>
            </div>

            <!-- Composer / Reply Box -->
            @if(! $ticket->isClosed())
                <div class="p-2.5 sm:p-3.5 bg-white dark:bg-slate-900 border-t border-[#DEE8F7] dark:border-slate-800 shrink-0">
                    <form
                        action="{{ route('support.reply', $ticket->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        @submit="handleReplySubmit($event)"
                        x-ref="replyForm"
                        class="flex flex-col gap-2"
                    >
                        @csrf

                        <!-- Selected Files Chips Bar -->
                        <div x-show="selectedFiles.length > 0" x-cloak class="flex items-center gap-2 flex-wrap pb-1 px-1">
                            <template x-for="(f, i) in selectedFiles" :key="i">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-[#012383] dark:text-blue-300 text-[11px] font-bold border border-blue-200/80 dark:border-blue-900 shadow-2xs">
                                    <span class="truncate max-w-[140px]" x-text="f.name"></span>
                                    <span class="text-[10px] text-slate-400" x-text="'(' + (f.size / 1024).toFixed(0) + 'KB)'"></span>
                                    <button type="button" @click="removeSelectedFile(i)" class="text-rose-500 hover:text-rose-700 cursor-pointer">✕</button>
                                </span>
                            </template>
                        </div>

                        <!-- Main Composer Action Row -->
                        <div class="flex items-end gap-1.5 sm:gap-2">
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
                                    class="size-10 sm:size-11 rounded-xl border border-[#DEE8F7] dark:border-slate-700/80 hover:border-[#012383] dark:hover:border-blue-400 text-slate-500 dark:text-slate-400 hover:text-[#012383] dark:hover:text-white bg-slate-50 dark:bg-slate-800 flex items-center justify-center transition-colors cursor-pointer shadow-2xs active:scale-95"
                                    title="{{ __('إرفاق ملف، صورة أو تسجيل صوتي') }}"
                                >
                                    <svg class="size-4.5 sm:size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                </button>
                            </div>

                            <!-- Text input textarea (Auto-expanding) -->
                            <div class="flex-1 relative">
                                <textarea
                                    name="message"
                                    x-ref="replyInput"
                                    x-model="replyText"
                                    @input="autoGrow($event.target)"
                                    @paste="handlePaste($event)"
                                    @keydown.ctrl.enter="$refs.replyForm.requestSubmit()"
                                    rows="1"
                                    placeholder="{{ __('اكتب رسالتك أو استفسارك هنا...') }}"
                                    class="w-full min-h-[40px] sm:min-h-[44px] max-h-32 py-2 px-3 sm:py-2.5 sm:px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/80 focus:bg-white dark:focus:bg-slate-800 text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:border-[#0B2A8F] dark:focus:border-blue-400 focus:ring-1 focus:ring-[#0B2A8F] dark:focus:ring-blue-400 transition-all outline-hidden font-medium resize-none leading-relaxed"
                                ></textarea>
                            </div>

                            <!-- Submit button (Responsive: icon-only on mobile, text+icon on sm+) -->
                            <button
                                type="submit"
                                :disabled="sending || (replyText.trim() === '' && selectedFiles.length === 0)"
                                class="h-10 px-3.5 sm:h-11 sm:px-5 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-1.5 shadow-xs transition-all shrink-0 cursor-pointer active:scale-95"
                                title="{{ __('إرسال الرسالة') }}"
                            >
                                <span x-show="!sending" class="hidden sm:inline">{{ __('إرسال') }}</span>
                                <span x-show="sending">{{ __('جاري الإرسال...') }}</span>
                                <svg x-show="!sending" class="size-4 transform rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Closed Ticket Banner -->
                <div class="p-3.5 sm:p-4 bg-slate-100/90 dark:bg-slate-800/90 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-start shrink-0">
                    <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300">
                        <svg class="size-4.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>{{ __('هذه التذكرة مغلقة حالياً. إذا كنت بحاجة للمزيد من المساعدة يمكنك إعادة فتحها.') }}</span>
                    </div>

                    <form action="{{ route('support.reopen', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold transition-all shadow-xs cursor-pointer active:scale-95">
                            {{ __('إعادة فتح التذكرة الآن') }}
                        </button>
                    </form>
                </div>
            @endif

        </div>

        <!-- Close Ticket Confirmation Modal -->
        <div
            x-show="showCloseModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4"
        >
            <div
                @click.outside="showCloseModal = false"
                class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full p-5 sm:p-6 shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col gap-4 text-start animate-fade-in"
            >
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="size-8 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold">
                            ⚠️
                        </div>
                        <h3 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white">{{ __('إغلاق تذكرة الدعم') }}</h3>
                    </div>
                    <button type="button" @click="showCloseModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">✕</button>
                </div>

                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ __('هل أنت متأكد من رغبتك في إغلاق هذه التذكرة؟ يمكنك دائماً إعادة فتحها إذا واجهتك أي صعوبة أخرى مستقبلاً.') }}
                </p>

                <form action="{{ route('support.close', $ticket->id) }}" method="POST" class="flex flex-col gap-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('ملاحظة أو سبب الإغلاق (اختياري)') }}</label>
                        <input
                            type="text"
                            name="reason"
                            placeholder="{{ __('مثال: تم حل المشكلة شكراً لكم') }}"
                            class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 dark:bg-slate-800 dark:text-white text-xs focus:border-[#012383] dark:focus:border-blue-400 outline-hidden font-medium"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="showCloseModal = false" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs cursor-pointer">
                            {{ __('تأكيد إغلاق التذكرة') }}
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
                showDetails: false,
                copied: false,
                replyText: '',
                selectedFiles: [],
                sending: false,
                pollingTimer: null,

                initChat() {
                    this.scrollToBottom();
                    this.startPolling();
                },

                copyTicketNumber() {
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText('{{ $ticket->ticket_number }}');
                    }
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2000);
                },

                autoGrow(textarea) {
                    if (!textarea) return;
                    textarea.style.height = 'auto';
                    textarea.style.height = Math.min(textarea.scrollHeight, 128) + 'px';
                },

                handlePaste(e) {
                    if (e.clipboardData && e.clipboardData.files.length > 0) {
                        const files = Array.from(e.clipboardData.files);
                        const imageFiles = files.filter(f => f.type.startsWith('image/'));
                        if (imageFiles.length > 0) {
                            e.preventDefault();
                            this.selectedFiles = [...this.selectedFiles, ...imageFiles];
                            this.syncFilesToInput();
                        }
                    }
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
                        div.className = 'flex items-center justify-center my-1.5';
                        div.setAttribute('data-message-id', msg.id);
                        div.innerHTML = `
                            <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-200/70 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 border border-slate-300/40 dark:border-slate-700/50 rounded-full text-[11px] font-semibold text-center max-w-md shadow-2xs backdrop-blur-xs">
                                <span>ℹ️</span>
                                <span>${this.escapeHtml(msg.message)}</span>
                                <span class="text-slate-400">•</span>
                                <span class="text-slate-400">${this.escapeHtml(msg.time_formatted)}</span>
                            </div>
                        `;
                    } else if (msg.is_student) {
                        div.className = 'flex flex-col items-end gap-1 self-end max-w-[88%] sm:max-w-[75%]';
                        div.setAttribute('data-message-id', msg.id);
                        const attachmentsHtml = this.renderAttachmentsHtml(msg.attachments, true);
                        div.innerHTML = `
                            <div class="flex items-center gap-1.5 text-[10.5px] text-slate-400 dark:text-slate-500 pe-1">
                                <span class="font-bold text-[#012383] dark:text-blue-400">{{ __('أنت') }}</span>
                                <span>•</span>
                                <span dir="ltr">${this.escapeHtml(msg.exact_time)}</span>
                            </div>
                            <div class="w-fit text-start bg-gradient-to-br from-[#012383] via-[#082b9e] to-[#0B2A8F] text-white px-4 py-3 rounded-2xl rounded-tr-sm rtl:rounded-tr-2xl rtl:rounded-tl-sm shadow-sm text-xs sm:text-sm font-normal leading-relaxed whitespace-pre-wrap break-words">
                                ${this.escapeHtml(msg.message)}
                                ${attachmentsHtml}
                            </div>
                            <div class="flex items-center gap-1 text-[10px] text-slate-400 pe-1">
                                <span>✓ {{ __('تم الإرسال') }}</span>
                            </div>
                        `;
                    } else {
                        div.className = 'flex items-start gap-2.5 sm:gap-3 self-start max-w-[88%] sm:max-w-[75%]';
                        div.setAttribute('data-message-id', msg.id);
                        const attachmentsHtml = this.renderAttachmentsHtml(msg.attachments, false);
                        div.innerHTML = `
                            <div class="relative shrink-0 mt-1">
                                <img src="${msg.sender_avatar}" alt="${this.escapeHtml(msg.sender_name)}" class="size-8 sm:size-9 rounded-full object-cover border border-[#DEE8F7] dark:border-slate-700 shadow-2xs" />
                                <span class="absolute -bottom-0.5 -end-0.5 size-2.5 bg-emerald-500 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
                            </div>
                            <div class="flex flex-col gap-1 min-w-0 text-start">
                                <div class="flex items-center gap-1.5 flex-wrap text-[11px] text-slate-500 dark:text-slate-400">
                                    <span class="font-bold text-slate-900 dark:text-slate-100">${this.escapeHtml(msg.sender_name)}</span>
                                    <span class="px-1.5 py-0.5 bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-300 border border-blue-200/60 dark:border-slate-700 rounded-md font-bold text-[10px]">{{ __('فريق المنصة') }}</span>
                                    <span>•</span>
                                    <span dir="ltr">${this.escapeHtml(msg.exact_time)}</span>
                                </div>
                                <div class="w-fit text-start bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700/80 text-slate-800 dark:text-slate-100 px-4 py-3 rounded-2xl rounded-tl-sm rtl:rounded-tl-2xl rtl:rounded-tr-sm shadow-xs text-xs sm:text-sm font-normal leading-relaxed whitespace-pre-wrap break-words">
                                    ${this.escapeHtml(msg.message)}
                                    ${attachmentsHtml}
                                </div>
                            </div>
                        `;
                    }
                    slot.appendChild(div);
                },

                renderAttachmentsHtml(attachments, isStudent) {
                    if (!attachments || !attachments.length) return '';
                    let html = `<div class="mt-2.5 flex flex-col gap-2 pt-2 border-t ${isStudent ? 'border-white/20' : 'border-slate-100 dark:border-slate-700'}">`;
                    attachments.forEach(file => {
                        if (file.is_image) {
                            html += `
                                <div class="rounded-xl overflow-hidden border ${isStudent ? 'border-white/25' : 'border-slate-200 dark:border-slate-700'} max-w-[260px] sm:max-w-[320px] shadow-2xs">
                                    <a href="${file.url}" target="_blank" class="block">
                                        <img src="${file.url}" alt="${this.escapeHtml(file.name)}" class="w-full h-auto max-h-52 object-cover" />
                                    </a>
                                </div>
                            `;
                        } else if (file.is_audio) {
                            html += `
                                <div class="p-2.5 rounded-xl ${isStudent ? 'bg-white/15 border border-white/20' : 'bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700'} max-w-[280px]">
                                    <p class="text-[11px] mb-1.5 truncate ${isStudent ? 'text-white/90' : 'text-slate-700 dark:text-slate-300'} font-medium">🎙️ ${this.escapeHtml(file.name)}</p>
                                    <audio controls class="w-full h-8 rounded-lg"><source src="${file.url}" type="${file.mime}"></audio>
                                </div>
                            `;
                        } else {
                            html += `
                                <a href="${file.url}" target="_blank" download class="flex items-center justify-between gap-3 p-2.5 rounded-xl ${isStudent ? 'bg-white/15 hover:bg-white/25 border border-white/20 text-white' : 'bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200'} transition-colors text-xs">
                                    <span class="truncate font-medium">${this.escapeHtml(file.name)}</span>
                                    <span class="text-xs">⬇</span>
                                </a>
                            `;
                        }
                    });
                    html += `</div>`;
                    return html;
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
                    this.selectedFiles.splice(index, 1);
                    this.syncFilesToInput();
                },

                syncFilesToInput() {
                    const dt = new DataTransfer();
                    const input = this.$refs.replyFileInput;
                    this.selectedFiles.forEach(f => dt.items.add(f));
                    if (input) input.files = dt.files;
                },

                async handleReplySubmit(e) {
                    if (this.replyText.trim() === '' && this.selectedFiles.length === 0) {
                        e.preventDefault();
                        return;
                    }
                    this.sending = true;
                }
            };
        }
    </script>
</x-layouts::app>