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
    $studentData = [
        'name' => $student?->name ?? auth()->user()?->name ?? __('طالب الأمين'),
        'phone' => $student?->phone ?? auth()->user()?->phone ?? '01000000000',
        'id' => $student?->id ?? auth()->id() ?? 'STD-001',
        'ip' => request()->ip() ?? '127.0.0.1',
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
    class="relative w-full overflow-hidden rounded-2xl bg-black shadow-2xl border border-slate-800 select-none group"
    style="aspect-ratio: {{ $aspectRatio }}; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; -webkit-touch-callout: none;"
    oncontextmenu="return false;"
    ondragstart="return false;"
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
            class="w-full h-full object-contain absolute inset-0 z-10"
        ></video>

    @else
        <div class="flex items-center justify-center h-full text-slate-400 text-sm font-['Cairo']">
            {{ __('المحتوى المرئي قيد المعالجة الآمنة') }}
        </div>
    @endif

    {{-- Full-Frame Dynamic Moving Watermark Overlay (Strictly Opacity 0.1 Covering All Video Frame) --}}
    <canvas 
        x-ref="watermarkCanvas" 
        class="absolute inset-0 w-full h-full pointer-events-none z-30"
        style="opacity: 1;"
    ></canvas>

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

    {{-- Lesson Completed Success Banner Overlay --}}
    <div 
        x-show="completionBannerActive" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute inset-x-3 bottom-3 z-40 p-4 rounded-2xl bg-slate-900/95 backdrop-blur-md border border-emerald-500/50 text-white shadow-2xl flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3"
        style="display: none;"
    >
        <div class="flex items-center gap-3 min-w-0">
            <div class="size-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/40">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="min-w-0">
                <h4 class="font-['Cairo'] font-bold text-xs sm:text-sm text-emerald-300 truncate">{{ __('أحسنت! تم إكمال هذا الدرس بنجاح وتم فتح المحاضرة التالية.') }}</h4>
                <p class="font-['Cairo'] text-[11px] text-slate-300 mt-0.5 truncate" x-text="completionMessage"></p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <template x-if="nextVideoUrl">
                <a 
                    :href="nextVideoUrl" 
                    class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-['Cairo'] font-bold text-xs shadow-md transition-all flex items-center justify-center gap-1.5 w-full sm:w-auto"
                >
                    <span>{{ __('الانتقال للمحاضرة التالية') }}</span>
                    <svg class="size-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
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
            canvas: null,
            ctx: null,
            animId: null,
            tokenRefreshTimer: null,
            shieldActive: false,
            shieldTitle: '',
            shieldMessage: '',
            completionBannerActive: false,
            completionMessage: '',
            nextVideoUrl: null,
            gridShiftX: 0,
            gridShiftY: 0,
            colSpacing: 290,
            rowSpacing: 120,

            initPlayer() {
                this.$nextTick(() => {
                    this.canvas = this.$refs.watermarkCanvas;
                    if (this.canvas) {
                        this.ctx = this.canvas.getContext('2d');
                        this.resize();
                        window.addEventListener('resize', () => this.resize());
                        this.startLoop();
                    }

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

            resize() {
                if (!this.canvas) return;
                const rect = this.canvas.parentElement.getBoundingClientRect();
                this.canvas.width = rect.width;
                this.canvas.height = rect.height;
            },

            getPlayerElement() {
                return document.getElementById('alameen-mux-player') 
                    || this.$el.querySelector('video') 
                    || this.$el.querySelector('iframe');
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
                if (this.ctx && this.canvas) {
                    this.ctx.save();
                    this.ctx.fillStyle = '#000000';
                    this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                    this.ctx.restore();
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

                // 2. Screenshot Keys & Shortcuts Interception
                const blockScreenshot = (e) => {
                    if (e.key === 'PrintScreen' || e.code === 'PrintScreen' || e.keyCode === 44) {
                        e.preventDefault();
                        this.nullifyClipboardAndAlert();
                        return;
                    }
                    if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'S' || e.key === 's')) {
                        e.preventDefault();
                        this.nullifyClipboardAndAlert();
                        return;
                    }
                    if (e.metaKey && e.shiftKey && ['3', '4', '5'].includes(e.key)) {
                        e.preventDefault();
                        this.nullifyClipboardAndAlert();
                        return;
                    }
                    if ((e.ctrlKey || e.metaKey) && (e.key === 'p' || e.key === 'P')) {
                        e.preventDefault();
                        this.nullifyClipboardAndAlert();
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

                // 3. Disable PiP and Cast
                const playerEl = this.getPlayerElement();
                if (playerEl && 'disablePictureInPicture' in playerEl) {
                    playerEl.disablePictureInPicture = true;
                    playerEl.addEventListener('enterpictureinpicture', () => {
                        if (document.exitPictureInPicture) document.exitPictureInPicture();
                    });
                }
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
                const render = (time) => {
                    if (!this.ctx || !this.canvas) return;

                    // If shield is active, enforce solid pitch black every single frame
                    if (this.shieldActive) {
                        this.ctx.fillStyle = '#000000';
                        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                        this.animId = requestAnimationFrame(render);
                        return;
                    }

                    if (!lastTime) lastTime = time;
                    const elapsed = Math.min((time - lastTime) / 1000, 0.1);
                    lastTime = time;

                    // Factor normalized to 60 FPS baseline (elapsed * 60)
                    const speedFactor = elapsed * 60;

                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

                    // 1. Shift the full-frame watermark pattern very slowly and smoothly across (X, Y)
                    this.gridShiftX += 0.10 * speedFactor;
                    this.gridShiftY += 0.06 * speedFactor;

                    const offsetX = this.gridShiftX % this.colSpacing;
                    const offsetY = this.gridShiftY % this.rowSpacing;

                    // Formatted live timestamp
                    const now = new Date();
                    const dateStr = now.toLocaleDateString('{{ app()->getLocale() === 'en' ? 'en-US' : 'ar-EG' }}');
                    const timeStr = now.toLocaleTimeString('{{ app()->getLocale() === 'en' ? 'en-US' : 'ar-EG' }}');

                    const line1 = `${this.student.name} • ${this.student.phone}`;
                    const line2 = `ID: ${this.student.id} • IP: ${this.student.ip} • ${dateStr} ${timeStr}`;

                    // 2. High-legibility security grid across the entire video frame
                    this.ctx.save();
                    this.ctx.globalAlpha = 0.15;
                    this.ctx.font = '700 12.5px "Noto Sans Arabic", "Cairo", Inter, system-ui, sans-serif';

                    // Angled watermark across the entire frame (-22 degrees)
                    const angle = -22 * Math.PI / 180;
                    this.ctx.rotate(angle);

                    // Cover full diagonal boundaries with generous margins
                    const diag = Math.sqrt(this.canvas.width * this.canvas.width + this.canvas.height * this.canvas.height);
                    const startX = -diag;
                    const endX = diag * 2;
                    const startY = -diag;
                    const endY = diag * 2;

                    for (let x = startX + offsetX; x < endX; x += this.colSpacing) {
                        for (let y = startY + offsetY; y < endY; y += this.rowSpacing) {
                            this.ctx.fillStyle = '#FFFFFF';
                            this.ctx.shadowColor = '#000000';
                            this.ctx.shadowBlur = 4;
                            this.ctx.fillText(line1, x, y);
                            this.ctx.font = '500 10.5px monospace';
                            this.ctx.fillText(line2, x, y + 15);
                            this.ctx.font = '700 12.5px "Noto Sans Arabic", "Cairo", Inter, system-ui, sans-serif';
                        }
                    }

                    this.ctx.restore();

                    this.animId = requestAnimationFrame(render);
                };

                this.animId = requestAnimationFrame(render);
            }
        };
    }
</script>
