@props([
    'embedUrl' => '',
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
@endphp

<div 
    x-data="videoWatermarkPlayer(@js($studentData))" 
    x-init="initPlayer()"
    class="relative w-full overflow-hidden rounded-2xl bg-black shadow-2xl border border-slate-800"
    style="aspect-ratio: {{ $aspectRatio }};"
>
    {{-- Bunny.net Stream Video Iframe --}}
    @if(!empty($embedUrl))
        <iframe 
            src="{{ $embedUrl }}" 
            loading="lazy" 
            class="w-full h-full border-0 absolute inset-0 z-10" 
            allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" 
            allowfullscreen="true"
        ></iframe>
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
                    this.ctx.shadowColor = 'rgba(0, 0, 0, 0.6)';
                    this.ctx.shadowBlur = 4;

                    this.ctx.fillText(
                        `${this.student.name} • ID: #${this.student.id}`,
                        this.posX,
                        this.posY
                    );
                    this.ctx.fillText(
                        `هاتف: ${this.student.phone} | IP: ${this.student.ip}`,
                        this.posX,
                        this.posY + 16
                    );
                    this.ctx.fillText(
                        `ترخيص منصة الأمين: ${timeStr}`,
                        this.posX,
                        this.posY + 32
                    );

                    this.ctx.restore();

                    this.animId = requestAnimationFrame(render);
                };

                this.animId = requestAnimationFrame(render);
            }
        };
    }
</script>
