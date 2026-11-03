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
    class="relative w-full overflow-hidden rounded-2xl bg-black shadow-2xl border border-slate-800 select-none"
    style="aspect-ratio: {{ $aspectRatio }}; -webkit-user-select: none; user-select: none;"
    oncontextmenu="return false;"
>
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
            forward-seek-offset="10"
            backward-seek-offset="10"
            class="w-full h-full absolute inset-0 z-10"
        ></mux-player>

    {{-- 2. YouTube Embed --}}
    @elseif(!empty($videoUrl) && (str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be')))
        @php
            preg_match('#(?:youtube\.com/(?:watch\?v=|embed/)|youtu\.be/)([a-zA-Z0-9_\-]+)#i', $videoUrl, $ytMatches);
            $ytId = $ytMatches[1] ?? '';
        @endphp
        <iframe 
            src="https://www.youtube.com/embed/{{ $ytId }}" 
            loading="lazy" 
            class="w-full h-full border-0 absolute inset-0 z-10" 
            allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" 
            allowfullscreen="true"
        ></iframe>

    {{-- 3. Vimeo Embed --}}
    @elseif(!empty($videoUrl) && str_contains($videoUrl, 'vimeo.com'))
        @php
            preg_match('#vimeo\.com/(?:video/)?([0-9]+)#i', $videoUrl, $vmMatches);
            $vmId = $vmMatches[1] ?? '';
        @endphp
        <iframe 
            src="https://player.vimeo.com/video/{{ $vmId }}" 
            loading="lazy" 
            class="w-full h-full border-0 absolute inset-0 z-10" 
            allow="autoplay; fullscreen; picture-in-picture" 
            allowfullscreen="true"
        ></iframe>

    {{-- 4. Bunny.net / Generic Embed URL --}}
    @elseif(!empty($embedUrl) && str_contains($embedUrl, 'iframe'))
        <iframe 
            src="{{ $embedUrl }}" 
            loading="lazy" 
            class="w-full h-full border-0 absolute inset-0 z-10" 
            allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" 
            allowfullscreen="true"
        ></iframe>

    {{-- 5. Direct Protected Video --}}
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
            controlsList="nodownload"
            oncontextmenu="return false;"
            preload="metadata"
            class="w-full h-full object-contain absolute inset-0 z-10"
        ></video>

    @else
        <div class="flex items-center justify-center h-full text-slate-500 text-sm">
            {{ __('يرجى تحديد رابط الفيديو الآمن') }}
        </div>
    @endif

    @if(app(\App\Services\Cms\SettingsService::class)->get('dynamic_video_watermark', '1') === '1')
    {{-- Dynamic Floating Canvas Overlay (Anti Screen Recording & Forensic Identification) --}}
    <canvas 
        x-ref="watermarkCanvas" 
        class="absolute inset-0 w-full h-full pointer-events-none z-30 opacity-80"
    ></canvas>
    @endif
</div>

<script>
    function videoWatermarkPlayer(student, videoId) {
        return {
            student: student,
            videoId: videoId,
            canvas: null,
            ctx: null,
            posX: 50,
            posY: 50,
            vx: 1.1,
            vy: 0.8,
            animId: null,
            tokenRefreshTimer: null,

            initPlayer() {
                this.$nextTick(() => {
                    this.canvas = this.$refs.watermarkCanvas;
                    if (this.canvas) {
                        this.ctx = this.canvas.getContext('2d');
                        this.resize();
                        window.addEventListener('resize', () => this.resize());
                        this.startLoop();
                    }

                    // Auto refresh signed playback token before expiration (every 45 minutes)
                    if (this.videoId) {
                        this.tokenRefreshTimer = setInterval(() => {
                            this.refreshToken();
                        }, 45 * 60 * 1000);
                    }
                });
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
                } catch (e) {
                    console.warn('Failed to refresh MUX token:', e);
                }
            },

            resize() {
                if (!this.canvas) return;
                const rect = this.canvas.parentElement.getBoundingClientRect();
                this.canvas.width = rect.width;
                this.canvas.height = rect.height;
            },

            startLoop() {
                const render = () => {
                    if (!this.ctx || !this.canvas) return;
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

                    // Update bouncing positions
                    this.posX += this.vx;
                    this.posY += this.vy;

                    const textWidth = 260;
                    const textHeight = 45;

                    if (this.posX <= 15 || this.posX + textWidth >= this.canvas.width - 15) {
                        this.vx = -this.vx;
                    }
                    if (this.posY <= 25 || this.posY + textHeight >= this.canvas.height - 25) {
                        this.vy = -this.vy;
                    }

                    // Format live timestamp
                    const now = new Date();
                    const timeStr = now.toLocaleDateString('{{ app()->getLocale() === 'en' ? 'en-US' : 'ar-EG' }}') + ' ' + now.toLocaleTimeString('{{ app()->getLocale() === 'en' ? 'en-US' : 'ar-EG' }}');

                    // Draw semi-transparent watermark badge with subtle shadow for visibility over light/dark frames
                    this.ctx.save();
                    this.ctx.shadowColor = 'rgba(0, 0, 0, 0.85)';
                    this.ctx.shadowBlur = 4;
                    this.ctx.font = '600 13px "Noto Sans Arabic", Inter, system-ui, sans-serif';
                    this.ctx.fillStyle = 'rgba(255, 255, 255, 0.45)';
                    this.ctx.fillText(`${this.student.name} • ${this.student.phone}`, this.posX, this.posY);

                    this.ctx.font = '500 11px monospace';
                    this.ctx.fillStyle = 'rgba(255, 255, 255, 0.32)';
                    this.ctx.fillText(`ID: ${this.student.id} • IP: ${this.student.ip} • ${timeStr}`, this.posX, this.posY + 16);
                    this.ctx.restore();

                    this.animId = requestAnimationFrame(render);
                };

                this.animId = requestAnimationFrame(render);
            }
        };
    }
</script>
