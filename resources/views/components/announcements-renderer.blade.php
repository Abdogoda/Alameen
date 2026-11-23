@props([
    'platform' => 'web',
])

@php
    $announcementService = app(\App\Services\Announcement\AnnouncementService::class);
    $currentUser = auth()->user();
    $currentAdmin = auth('admin')->user();
    
    // Fetch active eligible announcements for this platform and user
    $eligibleAnnouncements = $announcementService->getEligibleAnnouncements(
        platform: $platform,
        user: $currentUser,
        admin: $currentAdmin
    );

    // Highest priority forced announcement (if any)
    $forcedItem = $eligibleAnnouncements->firstWhere('display_mode', 'force');
    
    // Standard popup announcement (if any, only if no active forced item)
    $popupItem = $forcedItem ? null : $eligibleAnnouncements->firstWhere('display_mode', 'popup');

    // Active top banners
    $bannerItems = $eligibleAnnouncements->where('display_mode', 'banner');
@endphp

<div
    id="alameen-announcements-root"
    x-data="{
        forcedOpen: {{ $forcedItem ? 'true' : 'false' }},
        popupOpen: {{ $popupItem ? 'true' : 'false' }},
        activeBannerId: {{ $bannerItems->first()?->id ?? 'null' }},
        isSubmitting: false,
        deviceFingerprint: localStorage.getItem('alameen_device_id') || ('dev_' + Math.random().toString(36).substring(2, 15)),
        
        init() {
            if (!localStorage.getItem('alameen_device_id')) {
                localStorage.setItem('alameen_device_id', this.deviceFingerprint);
            }
            
            // Check client-side storage for dismissal in case of guest cache
            @if($forcedItem)
                if (localStorage.getItem('alameen_ack_{{ $forcedItem->id }}')) {
                    this.forcedOpen = false;
                }
            @endif

            @if($popupItem)
                if (localStorage.getItem('alameen_dismiss_{{ $popupItem->id }}')) {
                    this.popupOpen = false;
                }
            @endif
        },

        async sendInteraction(id, action) {
            try {
                const token = document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content');
                await fetch(`/announcements/${id}/interact`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token || ''
                    },
                    body: JSON.stringify({
                        action: action,
                        device_fingerprint: this.deviceFingerprint,
                        platform: '{{ $platform }}'
                    })
                });
            } catch (err) {
                console.error('Failed to record announcement interaction:', err);
            }
        },

        acknowledgeForced(id) {
            this.isSubmitting = true;
            localStorage.setItem('alameen_ack_' + id, Date.now());
            this.sendInteraction(id, 'acknowledged').finally(() => {
                this.forcedOpen = false;
                this.isSubmitting = false;
            });
        },

        dismissPopup(id) {
            localStorage.setItem('alameen_dismiss_' + id, Date.now());
            this.sendInteraction(id, 'dismissed');
            this.popupOpen = false;
        },

        dismissBanner(id) {
            localStorage.setItem('alameen_banner_dismiss_' + id, Date.now());
            this.sendInteraction(id, 'dismissed');
            if (this.activeBannerId === id) {
                this.activeBannerId = null;
            }
        }
    }"
    class="relative"
>

    {{-- ======================================================== --}}
    {{-- 1. TOP ANNOUNCEMENT BANNER                               --}}
    {{-- ======================================================== --}}
    @foreach($bannerItems as $banner)
        <div
            x-show="activeBannerId === {{ $banner->id }}"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-y-full opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="-translate-y-full opacity-0"
            class="relative z-[50] bg-gradient-to-r from-[#012383] via-[#0B2A8F] to-[#1E40AF] text-white px-4 py-2.5 shadow-md flex items-center justify-between gap-3 text-xs sm:text-sm"
        >
            <div class="flex items-center gap-2.5 flex-1 min-w-0">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-400 text-slate-950 font-black text-[11px] shrink-0 animate-pulse">
                    <span>📢</span>
                    <span>{{ __('تنويه هام') }}</span>
                </span>
                <div class="truncate font-semibold">
                    <span>{{ $banner->title }}</span>
                    @if($banner->summary)
                        <span class="hidden md:inline text-white/80 font-normal"> - {{ $banner->summary }}</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @if($banner->button_text && $banner->button_url)
                    <a
                        href="{{ $banner->button_url }}"
                        class="px-3 py-1 rounded-lg bg-white text-[#012383] hover:bg-amber-300 hover:text-slate-950 font-bold text-xs shadow-xs transition-colors"
                    >
                        {{ $banner->button_text }}
                    </a>
                @endif
                <button
                    type="button"
                    @click="dismissBanner({{ $banner->id }})"
                    class="p-1 rounded-md text-white/70 hover:text-white hover:bg-white/10 transition-colors"
                    aria-label="{{ __('إغلاق التنبيه') }}"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endforeach

    {{-- ======================================================== --}}
    {{-- 2. MANDATORY FORCED BLOCKING MODAL (Cannot close outside) --}}
    {{-- ======================================================== --}}
    @if($forcedItem)
        <div
            x-show="forcedOpen"
            x-cloak
            class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md overflow-y-auto overscroll-contain select-none"
            role="alertdialog"
            aria-modal="true"
        >
            <div
                @click.stop
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="bg-white dark:bg-slate-900 border-2 border-red-500/80 dark:border-red-500/60 rounded-3xl shadow-2xl max-w-2xl w-full p-6 sm:p-8 flex flex-col gap-6 relative overflow-hidden"
            >
                {{-- Glowing Top Caution Bar --}}
                <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-red-500 via-amber-500 to-red-500"></div>

                {{-- Header with Warning Badge --}}
                <div class="flex items-start gap-4">
                    <div class="size-12 rounded-2xl bg-red-100 dark:bg-red-950/50 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0 border border-red-200 dark:border-red-900 shadow-xs">
                        <svg class="size-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-100 dark:bg-red-950/80 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800">
                                {{ __('إشعار إلزامي واجب الاطلاع') }}
                            </span>
                            @if(in_array($forcedItem->content_type, ['video', 'mixed']))
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300">
                                    {{ __('فيديو تعليمي') }}
                                </span>
                            @endif
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white leading-tight">
                            {{ $forcedItem->title }}
                        </h2>
                    </div>
                </div>

                {{-- Video Player (if video or mixed) --}}
                @if($forcedItem->has_video)
                    <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-black shadow-inner border border-slate-200 dark:border-slate-800">
                        @if($forcedItem->video_type === 'youtube' || str_contains($forcedItem->video_embed_url ?? '', 'youtube'))
                            <iframe
                                src="{{ $forcedItem->video_embed_url }}"
                                class="w-full h-full border-0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        @elseif($forcedItem->video_type === 'mux' && $forcedItem->video_mux_playback_id)
                            <mux-player
                                playback-id="{{ $forcedItem->video_mux_playback_id }}"
                                metadata-video-title="{{ $forcedItem->title }}"
                                class="w-full h-full"
                                controls
                            ></mux-player>
                        @elseif($forcedItem->video_url)
                            <video
                                src="{{ $forcedItem->video_url }}"
                                poster="{{ $forcedItem->thumbnail_url }}"
                                controls
                                class="w-full h-full object-contain"
                            ></video>
                        @endif
                    </div>
                @endif

                {{-- Text Content / Summary / HTML Body --}}
                <div class="flex flex-col gap-3 text-slate-700 dark:text-slate-300 text-sm sm:text-base leading-relaxed max-h-60 overflow-y-auto pr-1">
                    @if($forcedItem->summary)
                        <p class="font-bold text-slate-900 dark:text-white text-base">
                            {{ $forcedItem->summary }}
                        </p>
                    @endif
                    @if($forcedItem->body)
                        <div class="prose dark:prose-invert max-w-none text-sm leading-relaxed">
                            {!! nl2br(e($forcedItem->body)) !!}
                        </div>
                    @endif
                </div>

                {{-- Compulsory Acknowledgment CTA Button --}}
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('يجب تأكيد القراءة والاطلاع للاستمرار في استخدام المنصة.') }}
                    </p>

                    <div class="flex items-center gap-2.5 w-full sm:w-auto">
                        @if($forcedItem->button_text && $forcedItem->button_url)
                            <a
                                href="{{ $forcedItem->button_url }}"
                                target="_blank"
                                class="h-11 px-5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-xs sm:text-sm flex items-center justify-center transition-colors"
                            >
                                {{ $forcedItem->button_text }} ↗
                            </a>
                        @endif

                        <button
                            type="button"
                            :disabled="isSubmitting"
                            @click="acknowledgeForced({{ $forcedItem->id }})"
                            class="h-11 px-6 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-bold text-xs sm:text-sm shadow-md hover:shadow-lg active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer w-full sm:w-auto"
                        >
                            <span x-show="!isSubmitting">✓ {{ __('أقر وأؤكد الاطلاع والموافقة') }}</span>
                            <span x-show="isSubmitting" class="flex items-center gap-2">
                                <svg class="animate-spin size-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>{{ __('جارٍ الحفظ...') }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ======================================================== --}}
    {{-- 3. STANDARD POPUP / EDUCATIONAL VIDEO MODAL (Dismissible) --}}
    {{-- ======================================================== --}}
    @if($popupItem)
        <div
            x-show="popupOpen"
            x-cloak
            @keydown.escape.window="dismissPopup({{ $popupItem->id }})"
            class="fixed inset-0 z-[90] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto overscroll-contain"
            role="dialog"
            aria-modal="true"
        >
            <div
                @click.stop
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl shadow-2xl max-w-xl w-full p-6 sm:p-7 flex flex-col gap-5 relative overflow-hidden my-auto"
            >
                {{-- Close Button --}}
                <button
                    type="button"
                    @click="dismissPopup({{ $popupItem->id }})"
                    class="absolute top-4 end-4 p-2 rounded-full text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors z-10 cursor-pointer"
                    aria-label="{{ __('إغلاق') }}"
                >
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                {{-- Header --}}
                <div class="flex items-start gap-3.5 pr-8">
                    <div class="size-11 rounded-2xl bg-[#EAF1FB] dark:bg-blue-950/60 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-900">
                        @if(in_array($popupItem->content_type, ['video', 'mixed']))
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-[#0B2A8F] dark:text-blue-300 bg-[#EAF1FB] dark:bg-slate-800 px-2.5 py-0.5 rounded-full inline-block mb-1">
                            {{ in_array($popupItem->content_type, ['video', 'mixed']) ? __('فيديو تعليمي / إرشادي') : __('إعلان المنصة') }}
                        </span>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white leading-snug">
                            {{ $popupItem->title }}
                        </h3>
                    </div>
                </div>

                {{-- Video Player --}}
                @if($popupItem->has_video)
                    <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-black shadow-inner border border-slate-200 dark:border-slate-800">
                        @if($popupItem->video_type === 'youtube' || str_contains($popupItem->video_embed_url ?? '', 'youtube'))
                            <iframe
                                src="{{ $popupItem->video_embed_url }}"
                                class="w-full h-full border-0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        @elseif($popupItem->video_type === 'mux' && $popupItem->video_mux_playback_id)
                            <mux-player
                                playback-id="{{ $popupItem->video_mux_playback_id }}"
                                metadata-video-title="{{ $popupItem->title }}"
                                class="w-full h-full"
                                controls
                            ></mux-player>
                        @elseif($popupItem->video_url)
                            <video
                                src="{{ $popupItem->video_url }}"
                                poster="{{ $popupItem->thumbnail_url }}"
                                controls
                                class="w-full h-full object-contain"
                            ></video>
                        @endif
                    </div>
                @endif

                {{-- Body content --}}
                <div class="flex flex-col gap-2.5 text-slate-600 dark:text-slate-300 text-sm leading-relaxed max-h-52 overflow-y-auto pr-1">
                    @if($popupItem->summary)
                        <p class="font-semibold text-slate-900 dark:text-white text-sm">
                            {{ $popupItem->summary }}
                        </p>
                    @endif
                    @if($popupItem->body)
                        <div class="prose dark:prose-invert max-w-none text-xs sm:text-sm">
                            {!! nl2br(e($popupItem->body)) !!}
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3">
                    <button
                        type="button"
                        @click="dismissPopup({{ $popupItem->id }})"
                        class="text-xs font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer"
                    >
                        {{ __('إغلاق وعدم الإظهار مجددًا') }}
                    </button>

                    <div class="flex items-center gap-2">
                        @if($popupItem->button_text && $popupItem->button_url)
                            <a
                                href="{{ $popupItem->button_url }}"
                                class="h-10 px-4.5 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] text-white font-bold text-xs shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-1.5"
                            >
                                <span>{{ $popupItem->button_text }}</span>
                                <span>→</span>
                            </a>
                        @endif
                        <button
                            type="button"
                            @click="dismissPopup({{ $popupItem->id }})"
                            class="h-10 px-4 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold text-xs transition-colors cursor-pointer"
                        >
                            {{ __('حسنًا، فهمت') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
