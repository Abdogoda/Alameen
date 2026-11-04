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

    {{-- Anti-Screen Recording & Privacy Shield Overlay (Triggered on screen recording, blur, or screenshot attempts) --}}
    <div 
        x-show="shieldActive" 
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 z-40 bg-black/95 backdrop-blur-md flex flex-col items-center justify-center text-center p-6 text-white select-none pointer-events-auto"
        style="display: none;"
    >
        <div class="size-16 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center border border-rose-500/30 mb-4 animate-pulse">
            <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h4 class="font-['Cairo'] font-bold text-lg text-rose-300" x-text="shieldTitle"></h4>
        <p class="font-['Cairo'] text-xs text-slate-300 mt-2 max-w-md leading-relaxed" x-text="shieldMessage"></p>
        <button 
            type="button" 
            @click="dismissShield()"
            class="mt-5 px-6 py-2.5 rounded-xl bg-[#0B2A8F] hover:bg-[#012383] text-white font-['Cairo'] font-bold text-xs shadow-md transition-all cursor-pointer"
        >
            {{ __('العودة لمتابعة المحاضرة') }}
        </button>
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
            gridShiftX: 0,
            gridShiftY: 0,
            badgeX: 60,
            badgeY: 60,
            badgeVx: 0.9,
            badgeVy: 0.7,
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

            pauseVideo() {
                try {
                    const mux = document.getElementById('alameen-mux-player');
                    if (mux && typeof mux.pause === 'function') {
                        mux.pause();
                    }
                    const nativeVid = this.$el.querySelector('video');
                    if (nativeVid && typeof nativeVid.pause === 'function') {
                        nativeVid.pause();
                    }
                } catch (e) {}
            },

            triggerShield(title, message) {
                this.pauseVideo();
                this.shieldTitle = title;
                this.shieldMessage = message;
                this.shieldActive = true;
            },

            dismissShield() {
                this.shieldActive = false;
            },

            setupAntiScreenRecording() {
                // 1. Intercept Screen Capture API (navigator.mediaDevices.getDisplayMedia)
                if (navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia) {
                    navigator.mediaDevices.getDisplayMedia = () => {
                        this.triggerShield(
                            '{{ __('تم رصد محاولة تسجيل أو مشاركة الشاشة') }}',
                            '{{ __('تسجيل المحاضرات محمي بحقوق الملكية الفكرية ومحظور تماماً. يرجى إغلاق برنامج التسجيل للمتابعة.') }}'
                        );
                        return Promise.reject(new DOMException('Screen recording is strictly prohibited.', 'NotAllowedError'));
                    };
                }

                // 2. Window Blur & Visibility Change (Pause & Shield when switching windows or launching screen recorder)
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        this.triggerShield(
                            '{{ __('تم إيقاف العرض مؤقتاً لحماية المحتوى') }}',
                            '{{ __('تم رصد مغادرة نافذة المحاضرة أو تشغيل نافذة أخرى. يرجى البقاء داخل الصفحة للمتابعة.') }}'
                        );
                    }
                });

                window.addEventListener('blur', () => {
                    this.triggerShield(
                        '{{ __('تم إيقاف العرض مؤقتاً لحماية المحتوى') }}',
                        '{{ __('تم رصد مغادرة نافذة المحاضرة أو تشغيل نافذة أخرى. يرجى البقاء داخل الصفحة للمتابعة.') }}'
                    );
                });

                // 3. Screenshot Keys & Shortcuts Interception
                const blockScreenshot = (e) => {
                    // PrintScreen
                    if (e.key === 'PrintScreen' || e.code === 'PrintScreen' || e.keyCode === 44) {
                        e.preventDefault();
                        this.nullifyClipboardAndAlert();
                        return;
                    }

                    // Windows Snipping Tool (Win + Shift + S) or Mac Screenshots (Cmd + Shift + 3/4/5)
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

                    // Print (Ctrl + P)
                    if ((e.ctrlKey || e.metaKey) && (e.key === 'p' || e.key === 'P')) {
                        e.preventDefault();
                        this.nullifyClipboardAndAlert();
                        return;
                    }

                    // Save Page (Ctrl + S)
                    if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S')) {
                        e.preventDefault();
                        return;
                    }

                    // View Source (Ctrl + U)
                    if ((e.ctrlKey || e.metaKey) && (e.key === 'u' || e.key === 'U')) {
                        e.preventDefault();
                        return;
                    }

                    // DevTools (F12 or Ctrl+Shift+I/J/C)
                    if (e.key === 'F12' || ((e.ctrlKey || e.metaKey) && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase()))) {
                        e.preventDefault();
                        this.triggerShield(
                            '{{ __('أدوات المطورين محظورة') }}',
                            '{{ __('تم رصد محاولة فحص الصفحة أو استخراج عناصر الفيديو.') }}'
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

                // 4. Disable Picture-in-Picture on players
                const nativeVid = this.$el.querySelector('video');
                if (nativeVid) {
                    nativeVid.disablePictureInPicture = true;
                    nativeVid.addEventListener('enterpictureinpicture', () => {
                        if (document.exitPictureInPicture) document.exitPictureInPicture();
                    });
                }
            },

            nullifyClipboardAndAlert() {
                this.pauseVideo();
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText('⚠️ تصوير الشاشة أو تسجيل المحاضرات ممنوع قانونياً ومحمي بحقوق الملكية الفكرية - أكاديمية الأمين.');
                }
                this.triggerShield(
                    '{{ __('تم رصد محاولة تصوير أو تسجيل الشاشة') }}',
                    '{{ __('تصوير أو تسجيل المحاضرات محمي بحقوق الملكية الفكرية. تم تعطيل الالتقاط وتوثيق المحاولة برقم الهوية.') }}'
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

            startLoop() {
                const render = () => {
                    if (!this.ctx || !this.canvas) return;
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

                    // 1. Shift the full-frame watermark pattern continuously across (X, Y)
                    this.gridShiftX += 0.55;
                    this.gridShiftY += 0.35;

                    const offsetX = this.gridShiftX % this.colSpacing;
                    const offsetY = this.gridShiftY % this.rowSpacing;

                    // Formatted live timestamp
                    const now = new Date();
                    const dateStr = now.toLocaleDateString('{{ app()->getLocale() === 'en' ? 'en-US' : 'ar-EG' }}');
                    const timeStr = now.toLocaleTimeString('{{ app()->getLocale() === 'en' ? 'en-US' : 'ar-EG' }}');

                    const line1 = `${this.student.name} • ${this.student.phone}`;
                    const line2 = `ID: ${this.student.id} • IP: ${this.student.ip} • ${dateStr} ${timeStr}`;

                    // 2. STRICT OPACITY 0.1 ON ALL VIDEO FRAME AS REQUESTED
                    this.ctx.save();
                    this.ctx.globalAlpha = 0.10; // Exactly 0.1 opacity
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
                            this.ctx.shadowBlur = 3;
                            this.ctx.fillText(line1, x, y);
                            this.ctx.font = '500 10.5px monospace';
                            this.ctx.fillText(line2, x, y + 15);
                            this.ctx.font = '700 12.5px "Noto Sans Arabic", "Cairo", Inter, system-ui, sans-serif';
                        }
                    }

                    this.ctx.restore();

                    // 3. Dynamic drifting secondary watermark badge (also at strictly 0.1 opacity)
                    this.badgeX += this.badgeVx;
                    this.badgeY += this.badgeVy;

                    if (this.badgeX <= 25 || this.badgeX + 240 >= this.canvas.width - 25) {
                        this.badgeVx = -this.badgeVx;
                    }
                    if (this.badgeY <= 35 || this.badgeY + 45 >= this.canvas.height - 35) {
                        this.badgeVy = -this.badgeVy;
                    }

                    this.ctx.save();
                    this.ctx.globalAlpha = 0.10; // Exactly 0.1 opacity
                    this.ctx.font = '800 13px "Cairo", system-ui, sans-serif';
                    this.ctx.fillStyle = '#FFFFFF';
                    this.ctx.shadowColor = '#000000';
                    this.ctx.shadowBlur = 4;
                    this.ctx.fillText(`🔒 ${this.student.name} • ${this.student.phone}`, this.badgeX, this.badgeY);
                    this.ctx.font = '600 11px monospace';
                    this.ctx.fillText(`ID: ${this.student.id} • ${this.student.ip}`, this.badgeX, this.badgeY + 16);
                    this.ctx.restore();

                    this.animId = requestAnimationFrame(render);
                };

                this.animId = requestAnimationFrame(render);
            }
        };
    }
</script>
