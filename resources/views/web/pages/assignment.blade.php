<x-layouts::app :title="__('تسليم المهمة السريرية والتدريب الصوتي')">
    <div class="flex flex-col gap-6 w-full text-start">
        
        <!-- Page Introduction & Breadcrumbs -->
        <div class="flex flex-col gap-1 items-start text-start w-full">
            <nav class="flex items-center gap-2 text-xs font-semibold text-[#0B2A8F] dark:text-blue-400" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}" class="hover:underline flex items-center gap-1" wire:navigate>
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('لوحة الطالب') }}</span>
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <a href="{{ route('assignments.index') }}" class="hover:underline" wire:navigate>{{ __('المهام السريرية') }}</a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-[#012383] dark:text-white font-bold">{{ $task?->title ?? __('التدريب الصوتي') }}</span>
            </nav>
            <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-[#012383] dark:text-white leading-tight">
                {{ $task?->title ?? __('تطبيق التمييز السمعي ومخارج الأصوات') }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-3xl">
                {{ $task?->course?->title ?? __('أساسيات التخاطب وعلاج النطق') }}
            </p>
        </div>

        @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-sm font-bold flex items-center gap-3 shadow-xs">
            <svg class="size-5 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-800 dark:text-rose-300 text-sm font-bold flex items-center gap-3 shadow-xs">
            <svg class="size-5 shrink-0 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <!-- Submission Context Banner (Enhanced Modern Background with Subtle Ambient Glow) -->
        <div class="relative overflow-hidden rounded-2xl border border-[#DEE8F7] dark:border-slate-800/90 bg-gradient-to-l from-white via-[#F4F8FD] to-[#EAF1FB] dark:from-slate-900 dark:via-slate-900/95 dark:to-[#0B1B44] p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <!-- Subtle Ambient Light Effects -->
            <div class="absolute -top-12 -left-12 size-36 bg-[#0B2A8F]/10 dark:bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -right-10 size-32 bg-[#F2AE2E]/10 dark:bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex items-center gap-3.5 sm:gap-4">
                <div class="size-11 rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 border border-emerald-500/20 dark:border-emerald-500/30 flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="size-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="flex flex-col gap-1 items-start text-start">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white leading-tight">
                            {{ __('التدريب السريري التفاعلي') }}
                        </h3>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 shadow-2xs">
                            <span class="size-1.5 rounded-full bg-emerald-500"></span>
                            {{ __('متاح للتسليم') }}
                        </span>
                    </div>
                    <p class="font-medium text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl">
                        {{ __('سجّل صوتك مباشرة عبر المايكروفون أو ارفع ملفك الصوتي لتقييم مخارج الحروف سريرياً.') }}
                    </p>
                </div>
            </div>

            <div class="relative z-10 flex items-center gap-2 shrink-0">
                <a
                    href="{{ route('lessons.show') }}"
                    class="h-10 px-4 rounded-xl text-xs sm:text-sm font-bold bg-white dark:bg-slate-800 text-[#012383] dark:text-blue-300 border border-[#DEE8F7] dark:border-slate-700 hover:bg-[#EAF1FB] dark:hover:bg-slate-700/80 shadow-2xs transition-all flex items-center gap-2 cursor-pointer active:scale-95"
                    wire:navigate
                >
                    <svg class="size-4 rtl:rotate-180 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>{{ __('العودة إلى الدرس والفيديوهات') }}</span>
                </a>
            </div>
        </div>

        @if($submission)
        <!-- Previous Submission Evaluation Card -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-6 sm:p-7 shadow-xs flex flex-col gap-5">
            <div class="flex items-center justify-between border-b border-[#DEE8F7]/70 dark:border-slate-800 pb-4">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-2xl {{ $submission->status === 'graded' ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400' : 'bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400' }} flex items-center justify-center font-bold">
                        @if($submission->status === 'graded')
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-[#012383] dark:text-white">
                            {{ $submission->status === 'graded' ? __('نتيجة وتقييم المهمة السريرية') : __('تم تسليم المهمة • بانتظار التقييم السريري') }}
                        </h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500">
                            {{ __('تاريخ التسليم:') }} {{ $submission->created_at?->format('d/m/Y h:i A') }}
                        </p>
                    </div>
                </div>

                @if($submission->status === 'graded')
                    <div class="text-center px-4 py-2 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60">
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 block">{{ __('الدرجة السريرية') }}</span>
                        <span class="text-2xl font-black text-emerald-700 dark:text-emerald-300">{{ $submission->score }} / 100</span>
                    </div>
                @else
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                        {{ __('قيد المراجعة بواسطة أخصائي التخاطب') }}
                    </span>
                @endif
            </div>

            <!-- Audio Recording Review -->
            @if($submission->audio_recording_path)
            <div class="bg-slate-50 dark:bg-slate-800/50 border border-[#DEE8F7] dark:border-slate-800 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">🎙️ {{ __('التسجيل الصوتي المرفق:') }}</span>
                    <audio controls class="h-8 max-w-[280px]">
                        <source src="{{ asset('storage/' . $submission->audio_recording_path) }}">
                    </audio>
                </div>
                @if($submission->student_notes)
                    <div class="text-xs text-slate-500 dark:text-slate-400 max-w-sm">
                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ __('ملاحظتك:') }}</span> {{ $submission->student_notes }}
                    </div>
                @endif
            </div>
            @endif

            <!-- Supervisor Feedback -->
            @if($submission->clinical_feedback)
            <div class="p-4 rounded-xl bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/60 flex flex-col gap-1.5">
                <span class="text-xs font-bold text-[#0B2A8F] dark:text-blue-300">📝 {{ __('ملاحظات وتوجيهات المشرف السريري:') }}</span>
                <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                    {{ $submission->clinical_feedback }}
                </p>
                @if($submission->graded_at)
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">{{ __('تم التقييم بتاريخ:') }} {{ $submission->graded_at->format('d/m/Y') }}</span>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- Assignment Form & Recorder Grid -->
        <div class="flex flex-col lg:flex-row gap-6 items-start w-full">
            
            <!-- Main Interactive Recorder & Upload Form -->
            <div class="flex-1 min-w-0 flex flex-col gap-5 w-full">
                
                <!-- Task Instructions Card -->
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-6 sm:p-7 flex flex-col gap-4 text-start w-full shadow-xs">
                    <div class="flex items-center justify-between border-b border-[#DEE8F7]/60 dark:border-slate-800 pb-3">
                        <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383] dark:text-white">
                            {{ __('المطلوب في هذه المهمة السريرية') }}
                        </h2>
                        <span class="text-xs font-bold text-[#0B2A8F] dark:text-blue-300 bg-[#EAF1FB] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 px-3 py-1 rounded-full">
                            {{ __('تسجيل صوتي مباشر') }}
                        </span>
                    </div>
                    
                    <p class="font-medium text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                        {{ $task?->instructions ?? __('قم بتسجيل مقطع صوتي واضح لمدة دقيقة واحدة تنطق فيه الكلمات التدريبية وتطبيق قواعد التمييز السمعي لنطق أصوات الحروف اللثوية والصفيرية.') }}
                    </p>
                </div>

                @if($task)
                <!-- Interactive Voice Studio Form -->
                <form method="POST" action="{{ route('assignments.submit', $task->id) }}" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-6 sm:p-7 flex flex-col gap-5 text-start w-full shadow-xs">
                    @csrf
                    
                    <div class="flex items-center justify-between w-full border-b border-[#DEE8F7]/60 dark:border-slate-800 pb-3">
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white">
                            {{ __('ستوديو التسجيل الصوتي المباشر') }}
                        </h3>
                        <div class="bg-[#EEF7F3] dark:bg-emerald-950/40 border border-[#D1EADB] dark:border-emerald-800/60 rounded-full px-3 py-1 flex items-center gap-1.5">
                            <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="font-bold text-xs text-emerald-800 dark:text-emerald-300 whitespace-nowrap">
                                {{ __('جاهز للتسجيل') }}
                            </span>
                        </div>
                    </div>

                    <!-- Web Audio API / MediaRecorder Box -->
                    <div class="p-6 rounded-2xl bg-gradient-to-br from-[#FAFCFF] to-[#F1F6FC] dark:from-slate-950/60 dark:to-slate-900/60 border-2 border-dashed border-[#CFDBEA] dark:border-slate-700/80 flex flex-col items-center justify-center gap-4 text-center">
                        <div id="recorder-icon-container" class="size-16 rounded-2xl bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400 flex items-center justify-center transition-all shadow-xs">
                            <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        </div>

                        <div class="flex flex-col items-center gap-1">
                            <h4 id="recorder-status-text" class="font-bold text-base text-[#012383] dark:text-white">
                                {{ __('انقر على زر البدء لتسجيل صوتك الآن') }}
                            </h4>
                            <p id="recorder-timer" class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400 hidden">
                                00:00
                            </p>
                        </div>

                        <!-- Recording Controls -->
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                id="btn-start-record"
                                onclick="startRecording()"
                                class="px-5 h-11 rounded-xl bg-[#012383] dark:bg-blue-600 text-white hover:bg-[#0B2A8F] dark:hover:bg-blue-700 font-bold text-xs flex items-center gap-2 shadow-sm cursor-pointer transition-all"
                            >
                                <span class="size-2.5 rounded-full bg-rose-500 animate-ping"></span>
                                <span>{{ __('بدء التسجيل المباشر') }}</span>
                            </button>

                            <button
                                type="button"
                                id="btn-stop-record"
                                onclick="stopRecording()"
                                class="px-5 h-11 rounded-xl bg-rose-600 text-white hover:bg-rose-700 font-bold text-xs flex items-center gap-2 shadow-sm cursor-pointer hidden transition-all"
                            >
                                <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"/></svg>
                                <span>{{ __('إيقاف وحفظ التسجيل') }}</span>
                            </button>
                        </div>

                        <!-- Audio Preview Player -->
                        <div id="audio-preview-box" class="w-full max-w-md hidden flex flex-col gap-2 pt-2">
                            <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400">✓ {{ __('تم التسجيل! يمكنك الاستماع لتسجيلك قبل الإرسال:') }}</span>
                            <audio id="audio-preview-player" controls class="w-full h-10"></audio>
                        </div>

                        <!-- Hidden Base64 Recording Payload -->
                        <input type="hidden" name="audio_data" id="audio-data-payload">
                    </div>

                    <!-- Alternative: File Upload -->
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm text-[#012383] dark:text-slate-200">
                            {{ __('أو يمكنك رفع ملف صوتي جاهز من جهازك (MP3, WAV, M4A, WEBM):') }}
                        </label>
                        <input
                            type="file"
                            name="audio_file"
                            id="audio-file-input"
                            accept="audio/*,.mp3,.wav,.m4a,.webm,.ogg"
                            class="block w-full text-xs text-slate-500 dark:text-slate-400 file:me-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#EAF1FB] dark:file:bg-slate-800 file:text-[#0B2A8F] dark:file:text-blue-300 hover:file:bg-[#DEE8F7] dark:hover:file:bg-slate-700 cursor-pointer"
                        />
                    </div>

                    <!-- Student Notes -->
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm text-[#012383] dark:text-slate-200">
                            {{ __('ملاحظتك للمشرف السريري (اختياري):') }}
                        </label>
                        <textarea
                            name="student_notes"
                            rows="3"
                            placeholder="{{ __('اذكر أي ملاحظات أو صعوبات واجهتها أثناء نطق التمارين...') }}"
                            class="bg-white dark:bg-slate-800/80 border border-[#DEE8F7] dark:border-slate-700 rounded-xl p-3.5 w-full font-medium text-xs sm:text-sm text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] dark:focus:border-blue-400 focus:ring-1 focus:ring-[#0B2A8F] dark:focus:ring-blue-400 transition-all resize-none placeholder:text-slate-400 dark:placeholder:text-slate-500"
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end pt-2 border-t border-[#DEE8F7]/60 dark:border-slate-800">
                        <button
                            type="submit"
                            class="bg-[#012383] hover:bg-[#0B2A8F] dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-xl h-11 px-8 flex items-center justify-center font-bold text-sm shadow-xs transition-all cursor-pointer active:scale-95"
                        >
                            {{ __('إرسال التسجيل السريري للمراجعة') }}
                        </button>
                    </div>
                </form>
                @endif

            </div>

            <!-- Sidebar Info & Clinical Tips -->
            <div class="w-full lg:w-[320px] shrink-0 flex flex-col gap-4">
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-5 sm:p-6 flex flex-col gap-4 w-full shadow-xs">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-[#012383] dark:text-white">
                        {{ __('إرشادات التسجيل الصوتي') }}
                    </h3>

                    <div class="space-y-3.5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        <div class="flex items-start gap-2.5">
                            <span class="size-6 rounded-lg bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400 font-bold flex items-center justify-center shrink-0 text-xs">١</span>
                            <span class="mt-0.5">{{ __('اختر مكاناً هادئاً بعيداً عن الصدى والضوضاء الخارجية.') }}</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="size-6 rounded-lg bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400 font-bold flex items-center justify-center shrink-0 text-xs">٢</span>
                            <span class="mt-0.5">{{ __('تحدث بنبرة طبيعية وبمسافة ١٥ سم من المايكروفون.') }}</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="size-6 rounded-lg bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400 font-bold flex items-center justify-center shrink-0 text-xs">٣</span>
                            <span class="mt-0.5">{{ __('احرص على وضوح الحروف ومخارج الأصوات المطلوب تدريبها.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Web Audio API MediaRecorder Script -->
    <script>
        let mediaRecorder = null;
        let audioChunks = [];
        let timerInterval = null;
        let secondsElapsed = 0;

        async function startRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = event => {
                    if (event.data.size > 0) {
                        audioChunks.push(event.data);
                    }
                };

                mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    const audioUrl = URL.createObjectURL(audioBlob);

                    const previewPlayer = document.getElementById('audio-preview-player');
                    previewPlayer.src = audioUrl;
                    document.getElementById('audio-preview-box').classList.remove('hidden');

                    // Convert blob to base64
                    const reader = new FileReader();
                    reader.readAsDataURL(audioBlob);
                    reader.onloadend = () => {
                        document.getElementById('audio-data-payload').value = reader.result;
                    };

                    // Stop all microphone tracks
                    stream.getTracks().forEach(track => track.stop());
                };

                mediaRecorder.start();

                // UI adjustments
                document.getElementById('btn-start-record').classList.add('hidden');
                document.getElementById('btn-stop-record').classList.remove('hidden');
                document.getElementById('recorder-status-text').textContent = '{{ __('جاري التسجيل الصوتي الآن...') }}';
                
                const iconContainer = document.getElementById('recorder-icon-container');
                iconContainer.classList.add('animate-pulse', 'bg-rose-100', 'text-rose-600');

                // Timer
                secondsElapsed = 0;
                const timerEl = document.getElementById('recorder-timer');
                timerEl.classList.remove('hidden');
                timerInterval = setInterval(() => {
                    secondsElapsed++;
                    const mins = String(Math.floor(secondsElapsed / 60)).padStart(2, '0');
                    const secs = String(secondsElapsed % 60).padStart(2, '0');
                    timerEl.textContent = `${mins}:${secs}`;
                }, 1000);

            } catch (err) {
                alert('{{ __('تعذر الوصول إلى المايكروفون. يرجى السماح بالوصول في إعدادات المتصفح أو رفع ملف صوتي جاهز.') }}');
            }
        }

        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
                clearInterval(timerInterval);

                document.getElementById('btn-start-record').classList.remove('hidden');
                document.getElementById('btn-stop-record').classList.add('hidden');
                document.getElementById('recorder-status-text').textContent = '{{ __('تم الانتهاء من التسجيل الصوتي بنجاح!') }}';

                const iconContainer = document.getElementById('recorder-icon-container');
                iconContainer.classList.remove('animate-pulse', 'bg-rose-100', 'text-rose-600');
            }
        }
    </script>
</x-layouts::app>
