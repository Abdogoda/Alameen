@props([
    'embedUrl' => '',
    'video' => null,
    'student' => null,
    'aspectRatio' => '16/9',
    'playbackToken' => '',
    'storyboardToken' => '',
    'thumbnailToken' => '',
])

@php
    $currentUser = $student ?? auth()->user();

    $extractField = function($u, array $keys) {
        if (is_array($u)) {
            foreach ($keys as $k) {
                if (isset($u[$k]) && $u[$k] !== '' && $u[$k] !== null) {
                    return (string) $u[$k];
                }
            }
        } elseif (is_object($u)) {
            foreach ($keys as $k) {
                if (isset($u->{$k}) && $u->{$k} !== '' && $u->{$k} !== null) {
                    return (string) $u->{$k};
                }
            }
        }
        return null;
    };

    $studentData = [
        'name' => $extractField($currentUser, ['name']),
        'email' => $extractField($currentUser, ['email']),
        'phone' => $extractField($currentUser, ['phone']),
        'id' => $extractField($currentUser, ['id', 'user_id']),
    ];

    $muxPlaybackId = $video?->mux_playback_id;
    $videoSource = $video?->video_source ?? (!empty($embedUrl) ? 'bunny' : null);
    $videoUrl = $video?->video_url ?? $embedUrl;
    $videoId = $video?->id ?? null;
@endphp

<script>
    (function() {
        try {
            localStorage.removeItem('alameen_video_locked_until');
            sessionStorage.removeItem('alameen_video_locked_until');
            document.documentElement.classList.remove('alameen-video-locked-mode');
        } catch(e) {}
    })();
</script>

<div 
    x-data="videoWatermarkPlayer(@js($studentData), {{ $videoId ? (int) $videoId : 'null' }})" 
    x-init="initPlayer()"
    class="w-full flex flex-col gap-4 select-none"
    style="-webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; -webkit-touch-callout: none;"
    oncontextmenu="return false;"
    ondragstart="return false;"
>
    {{-- Full-Page Ambient Security Watermark Canvas (Covers entire viewport on video pages in all modes) --}}
    <canvas 
        x-ref="pageWatermarkCanvas"
        id="alameen-page-watermark-canvas"
        class="fixed inset-0 pointer-events-none"
        style="position: fixed; inset: 0; width: 100vw; height: 100vh; z-index: 99999; pointer-events: none !important; opacity: 1;"
    ></canvas>

    {{-- Video Frame Container --}}
    <div 
        id="alameen-video-frame"
        x-ref="videoFrameContainer"
        class="relative w-full overflow-hidden rounded-2xl bg-black shadow-2xl border border-slate-800 group"
        style="aspect-ratio: {{ $aspectRatio }};"
    >
    <style>
        @media print {
            body, #alameen-mux-player, video, iframe, canvas, .video-player-container {
                display: none !important;
                visibility: hidden !important;
            }
        }
        /* Hide Cast, AirPlay, and PiP buttons in MUX Player shadow DOM to prevent external detachment */
        mux-player::part(pip-button),
        mux-player::part(cast-button),
        mux-player::part(airplay-button) {
            display: none !important;
            pointer-events: none !important;
        }
        .alameen-blackout-active {
            visibility: hidden !important;
            opacity: 0 !important;
            filter: brightness(0) !important;
            pointer-events: none !important;
        }

        /* Fullscreen styles for video container & canvas */
        #alameen-video-frame:fullscreen,
        #alameen-video-frame:-webkit-full-screen {
            width: 100vw !important;
            height: 100vh !important;
            max-width: 100vw !important;
            max-height: 100vh !important;
            aspect-ratio: auto !important;
            border-radius: 0 !important;
            border: none !important;
            background: #000000 !important;
        }

        #alameen-video-frame:fullscreen mux-player,
        #alameen-video-frame:-webkit-full-screen mux-player,
        #alameen-video-frame:fullscreen video,
        #alameen-video-frame:-webkit-full-screen video,
        #alameen-video-frame:fullscreen iframe,
        #alameen-video-frame:-webkit-full-screen iframe {
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 100% !important;
        }

        #alameen-video-frame:fullscreen canvas,
        #alameen-video-frame:-webkit-full-screen canvas {
            position: absolute !important;
            inset: 0 !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 2147483647 !important;
            pointer-events: none !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    </style>

    {{-- 1. MUX Stream Player (Protected with cryptographic signed tokens) --}}
    @if(($videoSource === 'mux' || empty($videoSource)) && !empty($muxPlaybackId))
        @once
            <script src="https://cdn.jsdelivr.net/npm/@mux/mux-player"></script>
        @endonce
        <mux-player 
            id="alameen-mux-player"
            playback-id="{{ $muxPlaybackId }}" 
            @if(!empty($playbackToken))
                playback-token="{{ $playbackToken }}" 
            @endif
            @if(!empty($storyboardToken))
                storyboard-token="{{ $storyboardToken }}" 
            @endif
            @if(!empty($thumbnailToken))
                thumbnail-token="{{ $thumbnailToken }}" 
            @endif
            stream-type="on-demand" 
            controls 
            autoplay="false"
            preload="auto"
            playsinline
            webkit-playsinline
            fullscreen-element="alameen-video-frame"
            disable-picture-in-picture="true"
            disablepictureinpicture="true"
            controlslist="nodownload noplaybackrate"
            forward-seek-offset="10"
            backward-seek-offset="10"
            class="w-full h-full absolute inset-0 z-10"
            oncontextmenu="return false;"
        ></mux-player>

    {{-- 2. YouTube Embed (Security Flags, Links Hidden) --}}
    @elseif(!empty($videoUrl) && (str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be')))
        @php
            preg_match('#(?:youtube\.com/(?:watch\?v=|embed/)|youtu\.be/)([a-zA-Z0-9_\-]+)#i', $videoUrl, $ytMatches);
            $ytId = $ytMatches[1] ?? '';
        @endphp
        <iframe 
            src="https://www.youtube.com/embed/{{ $ytId }}?modestbranding=1&rel=0&showinfo=0&controls=1&disablekb=1&fs=0" 
            loading="lazy" 
            class="w-full h-full border-0 absolute inset-0 z-10" 
            allow="accelerometer;gyroscope;autoplay;encrypted-media;" 
            allowfullscreen="false"
            oncontextmenu="return false;"
        ></iframe>

    {{-- 3. Vimeo Embed (Security Flags, Links Hidden) --}}
    @elseif(!empty($videoUrl) && str_contains($videoUrl, 'vimeo.com'))
        @php
            preg_match('#vimeo\.com/(?:video/)?([0-9]+)#i', $videoUrl, $vmMatches);
            $vmId = $vmMatches[1] ?? '';
        @endphp
        <iframe 
            src="https://player.vimeo.com/video/{{ $vmId }}?title=0&byline=0&portrait=0" 
            loading="lazy" 
            class="w-full h-full border-0 absolute inset-0 z-10" 
            allow="autoplay; encrypted-media;" 
            allowfullscreen="false"
            oncontextmenu="return false;"
        ></iframe>

    {{-- 4. Bunny.net / Protected Embed (Links Hidden) --}}
    @elseif(!empty($embedUrl) && str_contains($embedUrl, 'iframe'))
        <iframe 
            src="{{ $embedUrl }}" 
            loading="lazy" 
            class="w-full h-full border-0 absolute inset-0 z-10" 
            allow="accelerometer;gyroscope;autoplay;encrypted-media;" 
            allowfullscreen="false"
            oncontextmenu="return false;"
        ></iframe>

    {{-- 5. Direct Protected Stream (No Download, Inline Only) --}}
    @elseif(!empty($videoUrl))
        @php
            $playbackSrc = $videoUrl;
            if (str_contains($playbackSrc, '/storage/')) {
                $playbackSrc = '/storage/' . \Illuminate\Support\Str::after($playbackSrc, '/storage/');
            }
        @endphp
        <video 
            src="{{ $playbackSrc }}" 
            controls 
            controlsList="nodownload noplaybackrate"
            disablePictureInPicture="true"
            oncontextmenu="return false;"
            preload="metadata"
            playsinline
            webkit-playsinline
            class="w-full h-full object-contain absolute inset-0 z-10"
        ></video>

    @else
        <div class="flex items-center justify-center h-full text-slate-400 text-sm font-['Cairo']">
            {{ __('المحتوى المرئي قيد المعالجة الآمنة') }}
        </div>
    @endif

    {{-- Fullscreen / In-Player Video Watermark Canvas --}}
    <canvas 
        x-ref="watermarkCanvas" 
        id="alameen-video-watermark-canvas"
        class="absolute inset-0 w-full h-full pointer-events-none z-30"
        style="opacity: 1;"
    ></canvas>

    {{-- Smart Tab-Leave Pause Overlay (Polite & Secure: Pauses and blanks video when leaving tab, without account suspension) --}}
    <div 
        x-show="tabPausedActive" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute inset-0 z-40 bg-slate-950/95 backdrop-blur-md flex flex-col items-center justify-center text-center p-6 text-white select-none pointer-events-auto"
        style="display: none;"
    >
        <div class="size-16 rounded-2xl bg-blue-500/20 text-blue-400 flex items-center justify-center border border-blue-500/30 mb-4 shadow-lg shadow-blue-500/10">
            <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h4 class="font-['Cairo'] font-bold text-lg text-white">
            {{ __('المحاضرة متوقفة مؤقتاً') }}
        </h4>
        <p class="font-['Cairo'] text-xs text-slate-300 mt-2 max-w-sm leading-relaxed">
            {{ __('حرصاً على متابعة المحتوى وعدم تفويت الشرح، يتوقف الفيديو تلقائياً عند مغادرة صفحة الدرس.') }}
        </p>
        
        <button
            type="button"
            @click="resumeFromTabPause()"
            class="mt-5 inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-['Cairo'] text-xs font-bold shadow-lg hover:shadow-xl transition-all cursor-pointer group"
        >
            <svg class="size-4 rtl:rotate-180 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
            <span>{{ __('متابعة الاستماع للمحاضرة') }}</span>
        </button>
    </div>

    {{-- Anti-Screen Recording & Zero-Latency Privacy Shield Overlay (Instant solid black screen with no bypass buttons) --}}
    <div 
        x-show="shieldActive" 
        x-cloak
        class="absolute inset-0 z-50 bg-black flex flex-col items-center justify-center text-center p-6 text-white select-none pointer-events-auto"
        style="display: none;"
    >
        <div class="size-16 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center border border-rose-500/30 mb-4 animate-pulse">
            <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h4 class="font-['Cairo'] font-bold text-lg text-rose-300" x-text="shieldTitle"></h4>
        <p class="font-['Cairo'] text-xs text-slate-300 mt-2 max-w-md leading-relaxed" x-text="shieldMessage"></p>
        
        <div class="mt-5 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-950/60 border border-rose-500/40 text-rose-300 font-['Cairo'] text-xs font-semibold">
            <svg class="size-4 animate-spin shrink-0 text-rose-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span>{{ __('حظر أمني نشط: يرجى إيقاف برنامج أو أداة تسجيل الشاشة تماماً.') }}</span>
        </div>

    </div>

    {{-- End of Video Frame Container --}}
    </div>

    {{-- UNDER THE VIDEO: Lesson Completed Success Banner --}}
    <div 
        x-show="completionBannerActive" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="w-full p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border border-emerald-500/40 dark:border-emerald-500/50 text-slate-800 dark:text-white shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4"
        style="display: none;"
    >
        <div class="flex items-center gap-3.5 min-w-0">
            <div class="size-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-200 dark:border-emerald-800">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="min-w-0 text-start">
                <h4 class="font-['Cairo'] font-bold text-xs sm:text-sm text-emerald-700 dark:text-emerald-300 truncate">
                    {{ __('أحسنت! تم إكمال هذا الدرس بنجاح وتم فتح المحاضرة التالية.') }}
                </h4>
                <p class="font-['Cairo'] text-xs text-slate-600 dark:text-slate-300 mt-0.5 truncate" x-text="completionMessage"></p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <template x-if="nextVideoUrl">
                <a 
                    :href="nextVideoUrl" 
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-['Cairo'] font-bold text-xs sm:text-sm shadow-sm hover:shadow transition-all flex items-center justify-center gap-2 w-full sm:w-auto"
                >
                    <span>{{ __('الانتقال للمحاضرة التالية') }}</span>
                    <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </template>
        </div>
    </div>
</div>

<script>
    function videoWatermarkPlayer(student, videoId) {
        return {
            student: student,
            videoId: videoId,
            videoCanvas: null,
            videoCtx: null,
            pageCanvas: null,
            pageCtx: null,
            animId: null,
            tokenRefreshTimer: null,
            shieldActive: false,
            shieldTitle: '',
            shieldMessage: '',
            tabPausedActive: false,
            completionBannerActive: false,
            completionMessage: '',
            nextVideoUrl: null,
            gridShiftX: 0,
            gridShiftY: 0,
            colSpacing: 340,
            rowSpacing: 130,
            isFullscreen: false,
            watermarkLines: [],

            dpr: 1,

            prepareWatermarkLines() {
                const name = (this.student?.name && String(this.student.name).trim()) ? String(this.student.name).trim() : null;
                const email = (this.student?.email && String(this.student.email).trim()) ? String(this.student.email).trim() : null;
                const phone = (this.student?.phone && String(this.student.phone).trim()) ? String(this.student.phone).trim() : null;
                const id = (this.student?.id !== undefined && this.student?.id !== null && String(this.student.id).trim() !== '') 
                    ? `ID: ${String(this.student.id).trim()}` 
                    : null;

                const line1Parts = [];
                if (name) line1Parts.push(name);
                if (phone) line1Parts.push(phone);

                const line2Parts = [];
                if (email) line2Parts.push(email);
                if (id) line2Parts.push(id);

                const lines = [];
                if (line1Parts.length > 0 && line2Parts.length > 0) {
                    lines.push(line1Parts.join(' • '));
                    lines.push(line2Parts.join(' • '));
                } else if (line1Parts.length > 0) {
                    lines.push(line1Parts.join(' • '));
                } else if (line2Parts.length > 0) {
                    if (line2Parts.length === 2 && !name && !phone) {
                        lines.push(line2Parts[0]);
                        lines.push(line2Parts[1]);
                    } else {
                        lines.push(line2Parts.join(' • '));
                    }
                }
                this.watermarkLines = lines;
            },

            initPlayer() {
                this.$nextTick(() => {
                    this.prepareWatermarkLines();
                    this.videoCanvas = this.$refs.watermarkCanvas;
                    if (this.videoCanvas) {
                        this.videoCtx = this.videoCanvas.getContext('2d');
                    }

                    this.pageCanvas = this.$refs.pageWatermarkCanvas || document.getElementById('alameen-page-watermark-canvas');
                    if (this.pageCanvas) {
                        this.pageCtx = this.pageCanvas.getContext('2d');
                    }

                    this.resize();
                    window.addEventListener('resize', () => this.resize());

                    // Listen to all browser fullscreen events
                    const onFsChange = () => {
                        this.isFullscreen = Boolean(
                            document.fullscreenElement || 
                            document.webkitFullscreenElement || 
                            document.mozFullScreenElement || 
                            document.msFullscreenElement
                        );
                        this.resize();
                        setTimeout(() => this.resize(), 60);
                        setTimeout(() => this.resize(), 160);
                    };

                    ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange', 'MSFullscreenChange'].forEach(ev => {
                        document.addEventListener(ev, onFsChange);
                    });

                    this.setupFullscreenBinding();
                    this.startLoop();

                    this.setupTabVisibilityProtection();
                    this.setupAntiScreenRecording();
                    this.setupProgressTracking();

                    // Auto refresh signed MUX playback token before expiration (every 45 minutes)
                    if (this.videoId) {
                        this.tokenRefreshTimer = setInterval(() => {
                            this.refreshToken();
                        }, 45 * 60 * 1000);
                    }
                });
            },

            setupFullscreenBinding() {
                const container = document.getElementById('alameen-video-frame');
                const playerEl = this.getPlayerElement();

                if (!container || !playerEl) return;

                if (playerEl.tagName && playerEl.tagName.toLowerCase() === 'mux-player') {
                    playerEl.setAttribute('fullscreen-element', 'alameen-video-frame');
                }

                const origRequestFs = playerEl.requestFullscreen || playerEl.webkitRequestFullscreen;
                const requestContainerFs = (options) => {
                    if (container.requestFullscreen) {
                        return container.requestFullscreen(options);
                    } else if (container.webkitRequestFullscreen) {
                        return container.webkitRequestFullscreen(options);
                    }
                    if (origRequestFs) return origRequestFs.call(playerEl, options);
                };

                playerEl.requestFullscreen = requestContainerFs;
                if ('webkitRequestFullscreen' in playerEl) {
                    playerEl.webkitRequestFullscreen = requestContainerFs;
                }

                if (typeof HTMLVideoElement !== 'undefined' && playerEl instanceof HTMLVideoElement) {
                    playerEl.setAttribute('playsinline', '');
                    playerEl.setAttribute('webkit-playsinline', '');
                    playerEl.addEventListener('webkitbeginfullscreen', () => {
                        this.isFullscreen = true;
                        this.resize();
                    });
                    playerEl.addEventListener('webkitendfullscreen', () => {
                        this.isFullscreen = false;
                        this.resize();
                    });
                }
            },

            resize() {
                const dpr = Math.max(1, Math.min(window.devicePixelRatio || 1, 3));
                this.dpr = dpr;

                // 1. Page canvas covers full viewport
                if (this.pageCanvas) {
                    const width = window.innerWidth || document.documentElement.clientWidth;
                    const height = window.innerHeight || document.documentElement.clientHeight;
                    this.pageCanvas.width = Math.round(width * dpr);
                    this.pageCanvas.height = Math.round(height * dpr);
                    this.pageCanvas.style.width = width + 'px';
                    this.pageCanvas.style.height = height + 'px';
                }

                // 2. Video canvas covers the video container or fullscreen dimensions
                if (this.videoCanvas) {
                    const container = this.videoCanvas.parentElement || document.getElementById('alameen-video-frame');
                    if (container) {
                        const rect = container.getBoundingClientRect();
                        const width = (rect.width > 0 ? rect.width : window.innerWidth);
                        const height = (rect.height > 0 ? rect.height : window.innerHeight);
                        this.videoCanvas.width = Math.round(width * dpr);
                        this.videoCanvas.height = Math.round(height * dpr);
                        this.videoCanvas.style.width = width + 'px';
                        this.videoCanvas.style.height = height + 'px';
                    }
                }
            },

            getPlayerElement() {
                return document.getElementById('alameen-mux-player') 
                    || this.$el.querySelector('video') 
                    || this.$el.querySelector('iframe');
            },

            resumeFromTabPause() {
                this.tabPausedActive = false;
                const player = this.getPlayerElement();
                if (player && typeof player.play === 'function') {
                    player.play().catch(() => {});
                }
            },

            setupTabVisibilityProtection() {
                // When switching tabs or minimizing browser: immediately pause video and blank overlay (Safe: No suspension)
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden || document.visibilityState !== 'visible') {
                        const player = this.getPlayerElement();
                        if (player && typeof player.pause === 'function') {
                            try { player.pause(); } catch (e) {}
                        }
                        this.tabPausedActive = true;
                    }
                });

                // When user clicks to another application/window, pause video smoothly to prevent background recording
                window.addEventListener('blur', () => {
                    const player = this.getPlayerElement();
                    if (player && !player.paused) {
                        try { player.pause(); } catch (e) {}
                        this.tabPausedActive = true;
                    }
                });
            },

            instantBlackout(title, message, incidentType = 'screen_recording_attempt') {
                // 1. Immediately pause, mute, and blank out player DOM element
                const player = this.getPlayerElement();
                if (player) {
                    try {
                        if (typeof player.pause === 'function') player.pause();
                        if ('muted' in player) player.muted = true;
                        if ('volume' in player) player.volume = 0;
                    } catch (e) {}
                    player.classList.add('alameen-blackout-active');
                    player.style.setProperty('visibility', 'hidden', 'important');
                    player.style.setProperty('opacity', '0', 'important');
                    player.style.setProperty('filter', 'brightness(0)', 'important');
                }

                // 2. Immediately fill watermark canvas with solid pitch black
                if (this.videoCtx && this.videoCanvas) {
                    this.videoCtx.save();
                    this.videoCtx.fillStyle = '#000000';
                    this.videoCtx.fillRect(0, 0, this.videoCanvas.width, this.videoCanvas.height);
                    this.videoCtx.restore();
                }
                if (this.pageCtx && this.pageCanvas) {
                    this.pageCtx.save();
                    this.pageCtx.fillStyle = '#000000';
                    this.pageCtx.fillRect(0, 0, this.pageCanvas.width, this.pageCanvas.height);
                    this.pageCtx.restore();
                }

                // 3. Trigger solid black shield overlay immediately with zero delay
                this.shieldTitle = title;
                this.shieldMessage = message;
                this.shieldActive = true;

                // 4. Deactivate student account immediately, alert admins with full metadata, and reload to force logout
                this.reportViolationAndReload(incidentType);
            },

            reportViolationAndReload(incidentType) {
                if (this._isReportingViolation) return;
                this._isReportingViolation = true;

                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const csrf = tokenMeta ? tokenMeta.getAttribute('content') : '{{ csrf_token() }}';

                const url = this.videoId 
                    ? `/videos/${this.videoId}/report-violation` 
                    : `/security/report-violation`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        video_id: this.videoId,
                        incident_type: incidentType,
                        timestamp: new Date().toISOString()
                    })
                })
                .catch(() => {})
                .finally(() => {
                    setTimeout(() => {
                        window.location.reload();
                    }, 400);
                });
            },

            unlockPlayer() {
                try {
                    localStorage.removeItem('alameen_video_locked_until');
                    sessionStorage.removeItem('alameen_video_locked_until');
                    document.documentElement.classList.remove('alameen-video-locked-mode');
                } catch (e) {}

                this.shieldActive = false;
                this.tabPausedActive = false;

                const player = this.getPlayerElement();
                if (player) {
                    player.classList.remove('alameen-blackout-active');
                    player.style.removeProperty('visibility');
                    player.style.removeProperty('opacity');
                    player.style.removeProperty('filter');
                    if ('muted' in player) player.muted = false;
                    if ('volume' in player) player.volume = 1;
                }
            },

            setupAntiScreenRecording() {
                // 1. Intercept Screen Capture API (getDisplayMedia)
                if (navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia) {
                    navigator.mediaDevices.getDisplayMedia = () => {
                        this.instantBlackout(
                            '{{ __('تم رصد محاولة تسجيل أو مشاركة الشاشة') }}',
                            '{{ __('تسجيل المحاضرات محمي بحقوق الملكية الفكرية ومحظور تماماً.') }}',
                            'get_display_media_intercepted'
                        );
                        return Promise.reject(new DOMException('Screen recording is strictly prohibited.', 'NotAllowedError'));
                    };
                }

                // 2. Intercept Direct Canvas / Media Stream Siphoning (captureStream)
                try {
                    if (typeof HTMLMediaElement !== 'undefined' && HTMLMediaElement.prototype.captureStream) {
                        HTMLMediaElement.prototype.captureStream = function() {
                            throw new DOMException('Media capture is disabled.', 'SecurityError');
                        };
                    }
                    if (typeof HTMLCanvasElement !== 'undefined' && HTMLCanvasElement.prototype.captureStream) {
                        HTMLCanvasElement.prototype.captureStream = function() {
                            throw new DOMException('Canvas capture is disabled.', 'SecurityError');
                        };
                    }
                } catch (e) {}

                // 3. Screenshot & Screen Recording Keys Interception
                const blockScreenshot = (e) => {
                    if (e.key === 'PrintScreen' || e.code === 'PrintScreen' || e.keyCode === 44) {
                        e.preventDefault();
                        this.nullifyClipboardAndAlert();
                        return;
                    }
                    // Win + G (Windows Game Bar Screen Recorder)
                    if ((e.metaKey || e.ctrlKey) && (e.key === 'g' || e.key === 'G')) {
                        e.preventDefault();
                        return;
                    }
                    // Win + Shift + S / Cmd + Shift + S
                    if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'S' || e.key === 's')) {
                        e.preventDefault();
                        this.nullifyClipboardAndAlert();
                        return;
                    }
                    // Mac Screen Capture / Screen Recording Shortcuts (Cmd + Shift + 3, 4, 5)
                    if (e.metaKey && e.shiftKey && ['3', '4', '5'].includes(e.key)) {
                        e.preventDefault();
                        this.nullifyClipboardAndAlert();
                        return;
                    }
                    if ((e.ctrlKey || e.metaKey) && (e.key === 'p' || e.key === 'P')) {
                        e.preventDefault();
                        return;
                    }
                    if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S')) {
                        e.preventDefault();
                        return;
                    }
                    if ((e.ctrlKey || e.metaKey) && (e.key === 'u' || e.key === 'U')) {
                        e.preventDefault();
                        return;
                    }
                    if (e.key === 'F12' || ((e.ctrlKey || e.metaKey) && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase()))) {
                        e.preventDefault();
                        this.instantBlackout(
                            '{{ __('أدوات المطورين محظورة') }}',
                            '{{ __('تم رصد محاولة فحص الصفحة أو استخراج عناصر الفيديو.') }}',
                            'devtools_opened'
                        );
                        return;
                    }
                };

                window.addEventListener('keydown', blockScreenshot, true);
                window.addEventListener('keyup', (e) => {
                    if (e.key === 'PrintScreen' || e.code === 'PrintScreen' || e.keyCode === 44) {
                        this.nullifyClipboardAndAlert();
                    }
                }, true);

                // 4. Disable PiP and Cast
                const playerEl = this.getPlayerElement();
                if (playerEl && 'disablePictureInPicture' in playerEl) {
                    playerEl.disablePictureInPicture = true;
                    playerEl.addEventListener('enterpictureinpicture', () => {
                        if (document.exitPictureInPicture) document.exitPictureInPicture();
                    });
                }

                // 5. Anti-Tampering Integrity Monitor for Watermark Canvas
                this.setupCanvasIntegrity();
            },

            setupCanvasIntegrity() {
                const monitorCanvas = (el) => {
                    if (!el) return;
                    try {
                        const observer = new MutationObserver(() => {
                            if (!el) return;
                            const style = window.getComputedStyle(el);
                            if (style.display === 'none' || style.visibility === 'hidden' || parseFloat(style.opacity || '1') < 0.5) {
                                el.style.setProperty('display', 'block', 'important');
                                el.style.setProperty('visibility', 'visible', 'important');
                                el.style.setProperty('opacity', '1', 'important');
                                const player = this.getPlayerElement();
                                if (player && typeof player.pause === 'function') player.pause();
                            }
                        });
                        observer.observe(el, { attributes: true, attributeFilter: ['style', 'class'] });
                    } catch (e) {}
                };

                monitorCanvas(this.videoCanvas);
                monitorCanvas(this.pageCanvas);
            },

            nullifyClipboardAndAlert() {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText('⚠️ تصوير الشاشة أو تسجيل المحاضرات ممنوع قانونياً ومحمي بحقوق الملكية الفكرية - أكاديمية الأمين.');
                }
                this.instantBlackout(
                    '{{ __('تم رصد محاولة تصوير أو تسجيل الشاشة') }}',
                    '{{ __('تصوير أو تسجيل المحاضرات محمي بحقوق الملكية الفكرية. تم تعطيل الالتقاط وتوثيق المحاولة برقم الهوية.') }}',
                    'screenshot_attempt'
                );
            },

            async refreshToken() {
                if (!this.videoId) return;
                try {
                    const res = await fetch(`/videos/${this.videoId}/playback-token`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        const player = document.getElementById('alameen-mux-player');
                        if (player && data.playback_token) {
                            player.playbackToken = data.playback_token;
                            if (data.storyboard_token) {
                                player.storyboardToken = data.storyboard_token;
                            }
                        }
                    }
                } catch (e) {}
            },

            setupProgressTracking() {
                if (!this.videoId) return;

                const getPlayer = () => document.getElementById('alameen-mux-player') || this.$el.querySelector('video');

                let lastSentSeconds = -1;
                let lastSendTime = 0;
                let isSending = false;
                let hasTriggeredCompletion = false;

                window.playerResume = (sec) => {
                    const p = getPlayer();
                    if (p && !isNaN(sec) && sec > 0) {
                        p.currentTime = sec;
                        if (typeof p.play === 'function') {
                            p.play().catch(() => {});
                        }
                    }
                };

                const sendProgress = async (seconds, isEnded = false, duration = null) => {
                    if (isSending) return;
                    if (!isEnded && Math.abs(seconds - lastSentSeconds) < 3) return;

                    isSending = true;
                    lastSentSeconds = seconds;
                    lastSendTime = Date.now();

                    try {
                        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                        const csrf = tokenMeta ? tokenMeta.getAttribute('content') : '{{ csrf_token() }}';

                        const res = await fetch(`/videos/${this.videoId}/progress`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                watched_seconds: Math.max(0, Math.floor(seconds)),
                                is_ended: isEnded ? 1 : 0,
                                duration: duration && duration > 0 ? Math.floor(duration) : null
                            })
                        });

                        if (res.ok) {
                            const data = await res.json();
                            if (data.is_completed && !hasTriggeredCompletion) {
                                hasTriggeredCompletion = true;
                                this.onLessonCompleted(data);
                            }
                        }
                    } catch (e) {
                        console.warn('[Video Progress] Sync heartbeat error:', e);
                    } finally {
                        isSending = false;
                    }
                };

                const attachEvents = (player) => {
                    if (!player || player._progressEventsAttached) return;
                    player._progressEventsAttached = true;

                    // Periodic playback progress (every 6-8 seconds)
                    player.addEventListener('timeupdate', () => {
                        const current = player.currentTime || 0;
                        const dur = player.duration || null;
                        const now = Date.now();

                        if (now - lastSendTime >= 7000 && current > 0) {
                            sendProgress(current, false, dur);
                        }
                    });

                    // Pause event: save immediate bookmark
                    player.addEventListener('pause', () => {
                        if (player.currentTime > 0) {
                            sendProgress(player.currentTime, false, player.duration || null);
                        }
                    });

                    // Ended event: mark 100% completed immediately
                    player.addEventListener('ended', () => {
                        const dur = player.duration || player.currentTime || 0;
                        sendProgress(dur, true, dur);
                    });
                };

                // Attach listener as soon as player DOM element is available
                let attempts = 0;
                const attachTimer = setInterval(() => {
                    attempts++;
                    const p = getPlayer();
                    if (p) {
                        attachEvents(p);
                        clearInterval(attachTimer);
                    } else if (attempts >= 20) {
                        clearInterval(attachTimer);
                    }
                }, 400);
            },

            onLessonCompleted(data) {
                this.completionBannerActive = true;
                if (data.next_video) {
                    this.nextVideoUrl = data.next_video.url;
                    this.completionMessage = '{{ __('المحاضرة التالية:') }} ' + data.next_video.title;
                } else {
                    this.nextVideoUrl = null;
                    this.completionMessage = '{{ __('تهانينا! لقد أتممت جميع محاضرات هذا الكورس.') }}';
                }

                // If user reached end of video, gently prompt / redirect after 3.5 seconds
                const player = document.getElementById('alameen-mux-player') || this.$el.querySelector('video');
                if (player && (player.ended || (player.duration && player.currentTime >= player.duration - 1))) {
                    if (this.nextVideoUrl) {
                        setTimeout(() => {
                            if (this.nextVideoUrl) {
                                window.location.href = this.nextVideoUrl;
                            }
                        }, 3500);
                    } else {
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    }
                }
            },

            startLoop() {
                let lastTime = 0;

                const drawWatermarkGrid = (ctx, w, h, lines, offsetX, offsetY) => {
                    if (!ctx || w <= 0 || h <= 0) return;

                    ctx.clearRect(0, 0, w, h);
                    if (!lines || lines.length === 0) return;

                    const dpr = this.dpr || 1;
                    const logicalW = w / dpr;
                    const logicalH = h / dpr;

                    ctx.save();
                    // High-DPI crisp vector scale to eliminate blurriness completely
                    ctx.scale(dpr, dpr);

                    // 10% opacity as requested
                    ctx.globalAlpha = 0.10;

                    // Angled watermark across the entire frame (-22 degrees)
                    const angle = -22 * Math.PI / 180;
                    ctx.rotate(angle);

                    // Cover full diagonal boundaries with generous margins
                    const diag = Math.sqrt(logicalW * logicalW + logicalH * logicalH);
                    const startX = -diag;
                    const endX = diag * 2;
                    const startY = -diag;
                    const endY = diag * 2;

                    // Adaptive contrast: Crisp dark text with subtle white halo on light page, crisp white on dark video
                    const isVideoCtx = (ctx === this.videoCtx);
                    const isDark = isVideoCtx || document.documentElement.classList.contains('dark');
                    const textColor = isDark ? '#FFFFFF' : '#0F172A';
                    const shadowColor = isDark ? 'rgba(0, 0, 0, 0.90)' : 'rgba(255, 255, 255, 0.95)';

                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = textColor;
                    ctx.shadowColor = shadowColor;
                    ctx.shadowBlur = 1.5;

                    for (let x = startX + offsetX; x < endX; x += this.colSpacing) {
                        for (let y = startY + offsetY; y < endY; y += this.rowSpacing) {
                            if (lines.length === 1) {
                                ctx.font = '700 13px "Noto Sans Arabic", "Cairo", Inter, system-ui, sans-serif';
                                ctx.fillText(lines[0], x, y);
                            } else {
                                ctx.font = '700 13px "Noto Sans Arabic", "Cairo", Inter, system-ui, sans-serif';
                                ctx.fillText(lines[0], x, y);
                                ctx.font = '600 12px Inter, Roboto, monospace, sans-serif';
                                ctx.fillText(lines[1], x, y + 18);
                            }
                        }
                    }

                    ctx.restore();
                };

                const render = (time) => {
                    // If shield is active, enforce solid pitch black every single frame
                    if (this.shieldActive) {
                        if (this.videoCtx && this.videoCanvas) {
                            this.videoCtx.fillStyle = '#000000';
                            this.videoCtx.fillRect(0, 0, this.videoCanvas.width, this.videoCanvas.height);
                        }
                        if (this.pageCtx && this.pageCanvas) {
                            this.pageCtx.fillStyle = '#000000';
                            this.pageCtx.fillRect(0, 0, this.pageCanvas.width, this.pageCanvas.height);
                        }
                        this.animId = requestAnimationFrame(render);
                        return;
                    }

                    if (!lastTime) lastTime = time;
                    const elapsed = Math.min((time - lastTime) / 1000, 0.1);
                    lastTime = time;

                    // Factor normalized to 60 FPS baseline (elapsed * 60)
                    const speedFactor = elapsed * 60;

                    const lines = this.watermarkLines || [];

                    // Slower, calm, seamless sub-pixel continuous float
                    this.gridShiftX += 0.06 * speedFactor;
                    this.gridShiftY += 0.03 * speedFactor;

                    const offsetX = this.gridShiftX % this.colSpacing;
                    const offsetY = this.gridShiftY % this.rowSpacing;

                    const isFs = Boolean(
                        document.fullscreenElement || 
                        document.webkitFullscreenElement || 
                        document.mozFullScreenElement || 
                        document.msFullscreenElement ||
                        this.isFullscreen
                    );

                    // 1. Draw Page Watermark (covers the entire page in normal mode)
                    if (this.pageCtx && this.pageCanvas) {
                        drawWatermarkGrid(this.pageCtx, this.pageCanvas.width, this.pageCanvas.height, lines, offsetX, offsetY);
                    }

                    // 2. Draw Video Watermark (covers fullscreen video, or in-player if page watermark absent)
                    if (this.videoCtx && this.videoCanvas) {
                        if (isFs || !this.pageCanvas) {
                            drawWatermarkGrid(this.videoCtx, this.videoCanvas.width, this.videoCanvas.height, lines, offsetX, offsetY);
                        } else {
                            // In normal mode, pageCanvas already covers the video cleanly
                            this.videoCtx.clearRect(0, 0, this.videoCanvas.width, this.videoCanvas.height);
                        }
                    }

                    this.animId = requestAnimationFrame(render);
                };

                this.animId = requestAnimationFrame(render);
            }
        };
    }
</script>
