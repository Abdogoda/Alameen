@props([
    'embedUrl' => '',
    'video' => null,
    'student' => null,
    'aspectRatio' => '16/9',
])

@php
    $studentData = [
        'name' => $student?->name ?? auth()->user()?->name ?? 'طالب الأمين',
        'phone' => $student?->phone ?? auth()->user()?->phone ?? '01000000000',
        'id' => $student?->id ?? auth()->id() ?? 'STD-001',
        'ip' => request()->ip() ?? '127.0.0.1',
    ];

    $muxPlaybackId = $video?->mux_playback_id;
    $videoSource = $video?->video_source ?? (!empty($embedUrl) ? 'bunny' : null);
    $videoUrl = $video?->video_url ?? $embedUrl;
@endphp

<div 
    x-data="videoWatermarkPlayer(@js($studentData))" 
    x-init="initPlayer()"
    class="relative w-full overflow-hidden rounded-2xl bg-black shadow-2xl border border-slate-800"
    style="aspect-ratio: {{ $aspectRatio }};"
>
    {{-- 1. MUX Stream Player --}}
    @if(!empty($muxPlaybackId))
        <mux-player 
            playback-id="{{ $muxPlaybackId }}" 
            stream-type="on-demand" 
            controls 
            autoplay="false"
            preload="auto"
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

    {{-- 5. Direct MP4 / HLS Video --}}
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
            preload="metadata"
            class="w-full h-full object-contain absolute inset-0 z-10"
        ></video>

    @else
        <div class="flex items-center justify-center h-full text-slate-500 text-sm">
            {{ __('يرجى تحديد رابط الفيديو الآمن') }}
        </div>
    @endif

    {{-- Dynamic Floating Canvas Overlay (Anti Screen Recording) --}}
    <canvas 
        x-ref="watermarkCanvas" 
        class="absolute inset-0 w-full h-full pointer-events-none z-30 opacity-75"
    ></canvas>
</div>

<script>
    function videoWatermarkPlayer(student) {
        return {
            student: student,
            canvas: null,
            ctx: null,
            posX: 50,
            posY: 50,
            vx: 1.2,
            vy: 0.9,
            animId: null,

            initPlayer() {
                this.$nextTick(() => {
                    this.canvas = this.$refs.watermarkCanvas;
                    if (!this.canvas) return;
                    this.ctx = this.canvas.getContext('2d');
                    this.resize();
                    window.addEventListener('resize', () => this.resize());
                    this.startLoop();
                });
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
                    const textHeight = 60;

                    if (this.posX <= 10 || this.posX + textWidth >= this.canvas.width - 10) {
                        this.vx = -this.vx;
                    }
                    if (this.posY <= 20 || this.posY + textHeight >= this.canvas.height - 10) {
                        this.vy = -this.vy;
                    }

                    // Format live timestamp
                    const now = new Date();
                    const timeStr = now.toLocaleDateString('ar-EG') + ' ' + now.toLocaleTimeString('ar-EG');

                    // Draw semi-transparent watermark badge
                    this.ctx.save();
                    this.ctx.font = '500 12px "Noto Sans Arabic", Inter, sans-serif';
                    this.ctx.fillStyle = 'rgba(255, 255, 255, 0.40)';
                    this.ctx.fillText(`${this.student.name} • ${this.student.phone}`, this.posX, this.posY);
                    this.ctx.font = '400 10px monospace';
                    this.ctx.fillStyle = 'rgba(255, 255, 255, 0.28)';
                    this.ctx.fillText(`IP: ${this.student.ip} • ID: ${this.student.id} • ${timeStr}`, this.posX, this.posY + 16);
                    this.ctx.restore();

                    this.animId = requestAnimationFrame(render);
                };

                this.animId = requestAnimationFrame(render);
            }
        };
    }
</script>
