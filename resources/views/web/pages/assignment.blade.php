<x-layouts::app :title="__('تسليم المهمة السريرية والتدريب الصوتي')">
    <div class="px-4 sm:px-6 lg:px-[40px] pt-[24px] lg:pt-[30px] pb-[40px] flex flex-col gap-[24px] w-full">
        
        <!-- Page Introduction -->
        <div class="flex flex-col gap-[4px] items-start text-start w-full">
            <p class="font-['Cairo'] font-semibold text-[12px] leading-[1.8] text-[#0b2a8f] text-start w-full">
                {{ __('مساحة التعلّم / المهام السريرية والتدريب الصوتي') }}
            </p>
            <h1 class="font-['Cairo'] font-bold text-[28px] sm:text-[32px] leading-[1.3] text-[#012383] text-start w-full">
                {{ $task?->title ?? __('تطبيق التمييز السمعي ومخارج الأصوات') }}
            </h1>
            <p class="font-['Cairo'] font-medium text-[13px] sm:text-[14px] leading-[1.7] text-[#475569] text-start w-full">
                {{ $task?->course?->title ?? __('أساسيات التخاطب وعلاج النطق') }}
            </p>
        </div>

        @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center gap-3">
            <svg class="size-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-bold flex items-center gap-3">
            <svg class="size-5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <!-- Submission Context Banner -->
        <div class="bg-gradient-to-l from-[#EAF1FB] via-[#F4F8FD] to-white border border-[#DEE8F7] rounded-[16px] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full shadow-xs">
            <div class="flex items-center gap-[14px]">
                <div class="size-[32px] rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center shrink-0">
                    <svg class="size-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="flex flex-col gap-[2px] items-start text-start">
                    <p class="font-['Cairo'] font-bold text-[15px] leading-[1.6] text-[#012383]">
                        {{ __('التدريب السريري التفاعلي') }}
                    </p>
                    <p class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569]">
                        {{ __('سجّل صوتك مباشرة عبر المايكروفون أو ارفع ملفك الصوتي لتقييم مخارج الحروف سريرياً') }}
                    </p>
                </div>
            </div>

            <a
                href="{{ route('lessons.show') }}"
                class="bg-white border border-[#DEE8F7] hover:border-[#0b2a8f]/40 hover:bg-[#EAF1FB] rounded-[10px] h-[44px] px-[18px] flex items-center justify-center font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] transition-all shrink-0 shadow-2xs"
            >
                {{ __('العودة إلى الدرس والفيديوهات') }}
            </a>
        </div>

        @if($submission)
        <!-- Previous Submission Evaluation Card -->
        <div class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 sm:p-7 shadow-sm flex flex-col gap-5">
            <div class="flex items-center justify-between border-b border-[#DEE8F7]/70 pb-4">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-2xl {{ $submission->status === 'graded' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center font-bold">
                        @if($submission->status === 'graded')
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-[#012383]">
                            {{ $submission->status === 'graded' ? __('نتيجة وتقييم المهمة السريرية') : __('تم تسليم المهمة • بانتظار التقييم السريري') }}
                        </h3>
                        <p class="text-xs text-slate-400">
                            {{ __('تاريخ التسليم:') }} {{ $submission->created_at?->format('d/m/Y h:i A') }}
                        </p>
                    </div>
                </div>

                @if($submission->status === 'graded')
                    <div class="text-center px-4 py-2 rounded-2xl bg-emerald-50 border border-emerald-200">
                        <span class="text-xs font-bold text-emerald-600 block">{{ __('الدرجة السريرية') }}</span>
                        <span class="text-2xl font-black text-emerald-700">{{ $submission->score }} / 100</span>
                    </div>
                @else
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        {{ __('قيد المراجعة بواسطة أخصائي التخاطب') }}
                    </span>
                @endif
            </div>

            <!-- Audio Recording Review -->
            @if($submission->audio_recording_path)
            <div class="bg-slate-50 border border-[#DEE8F7] rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-700">🎙️ {{ __('التسجيل الصوتي المرفق:') }}</span>
                    <audio controls class="h-8 max-w-[280px]">
                        <source src="{{ asset('storage/' . $submission->audio_recording_path) }}">
                    </audio>
                </div>
                @if($submission->student_notes)
                    <div class="text-xs text-slate-500 max-w-sm">
                        <span class="font-bold text-slate-700">{{ __('ملاحظتك:') }}</span> {{ $submission->student_notes }}
                    </div>
                @endif
            </div>
            @endif

            <!-- Supervisor Feedback -->
            @if($submission->clinical_feedback)
            <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200 flex flex-col gap-1.5">
                <span class="text-xs font-bold text-[#0B2A8F]">📝 {{ __('ملاحظات وتوجيهات المشرف السريري:') }}</span>
                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-medium">
                    {{ $submission->clinical_feedback }}
                </p>
                @if($submission->graded_at)
                    <span class="text-[10px] text-slate-400 mt-1">{{ __('تم التقييم بتاريخ:') }} {{ $submission->graded_at->format('d/m/Y') }}</span>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- Assignment Form & Recorder Grid -->
        <div class="flex flex-col lg:flex-row gap-[24px] items-start w-full">
            
            <!-- Main Interactive Recorder & Upload Form -->
            <div class="flex-1 min-w-0 flex flex-col gap-[20px] w-full">
                
                <!-- Task Instructions Card -->
                <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-6 sm:p-7 flex flex-col gap-[16px] text-start w-full shadow-xs">
                    <div class="flex items-center justify-between border-b border-[#DEE8F7]/60 pb-3">
                        <h2 class="font-['Cairo'] font-bold text-[20px] leading-[1.4] text-[#012383]">
                            {{ __('المطلوب في هذه المهمة السريرية') }}
                        </h2>
                        <span class="text-xs font-bold text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-full font-['Cairo']">
                            {{ __('تسجيل صوتي مباشر') }}
                        </span>
                    </div>
                    
                    <p class="font-['Cairo'] font-medium text-[14px] leading-[1.8] text-[#475569]">
                        {{ $task?->instructions ?? __('قم بتسجيل مقطع صوتي واضح لمدة دقيقة واحدة تنطق فيه الكلمات التدريبية وتطبيق قواعد التمييز السمعي لنطق أصوات الحروف اللثوية والصفيرية.') }}
                    </p>
                </div>

                @if($task)
                <!-- Interactive Voice Studio Form -->
                <form method="POST" action="{{ route('assignments.submit', $task->id) }}" enctype="multipart/form-data" class="bg-white border border-[#DEE8F7] rounded-[16px] p-6 sm:p-7 flex flex-col gap-[20px] text-start w-full shadow-xs">
                    @csrf
                    
                    <div class="flex items-center justify-between w-full border-b border-[#DEE8F7]/60 pb-3">
                        <h3 class="font-['Cairo'] font-bold text-[18px] sm:text-[19px] leading-[1.6] text-[#012383]">
                            {{ __('ستوديو التسجيل الصوتي المباشر') }}
                        </h3>
                        <div class="bg-[#EEF7F3] border border-[#D1EADB] rounded-full px-3 py-1 flex items-center gap-1.5">
                            <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="font-['Cairo'] font-bold text-[12px] text-emerald-800 whitespace-nowrap">
                                {{ __('جاهز للتسجيل') }}
                            </span>
                        </div>
                    </div>

                    <!-- Web Audio API / MediaRecorder Box -->
                    <div class="p-6 rounded-2xl bg-gradient-to-br from-[#FAFCFF] to-[#F1F6FC] border-2 border-dashed border-[#CFDBEA] flex flex-col items-center justify-center gap-4 text-center">
                        <div id="recorder-icon-container" class="size-16 rounded-full bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center transition-all shadow-xs">
                            <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        </div>

                        <div class="flex flex-col items-center gap-1">
                            <h4 id="recorder-status-text" class="font-bold text-base text-[#012383]">
                                {{ __('انقر على زر البدء لتسجيل صوتك الآن') }}
                            </h4>
                            <p id="recorder-timer" class="text-xs font-mono font-bold text-slate-500 hidden">
                                00:00
                            </p>
                        </div>

                        <!-- Recording Controls -->
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                id="btn-start-record"
                                onclick="startRecording()"
                                class="px-5 h-11 rounded-xl bg-[#012383] text-white hover:bg-[#0B2A8F] font-bold text-xs flex items-center gap-2 shadow-sm cursor-pointer transition-all"
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
                            <span class="text-xs font-bold text-emerald-700">✓ {{ __('تم التسجيل! يمكنك الاستماع لتسجيلك قبل الإرسال:') }}</span>
                            <audio id="audio-preview-player" controls class="w-full h-10"></audio>
                        </div>

                        <!-- Hidden Base64 Recording Payload -->
                        <input type="hidden" name="audio_data" id="audio-data-payload">
                    </div>

                    <!-- Alternative: File Upload -->
                    <div class="flex flex-col gap-2">
                        <label class="font-['Cairo'] font-bold text-[14px] text-[#012383]">
                            {{ __('أو يمكنك رفع ملف صوتي جاهز من جهازك (MP3, WAV, M4A, WEBM):') }}
                        </label>
                        <input
                            type="file"
                            name="audio_file"
                            id="audio-file-input"
                            accept="audio/*,.mp3,.wav,.m4a,.webm,.ogg"
                            class="block w-full text-xs text-slate-500 file:me-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#EAF1FB] file:text-[#0B2A8F] hover:file:bg-[#DEE8F7] cursor-pointer"
                        />
                    </div>

                    <!-- Student Notes -->
                    <div class="flex flex-col gap-2">
                        <label class="font-['Cairo'] font-bold text-[14px] text-[#012383]">
                            {{ __('ملاحظتك للمشرف السريري (اختياري):') }}
                        </label>
                        <textarea
                            name="student_notes"
                            rows="3"
                            placeholder="{{ __('اذكر أي ملاحظات أو صعوبات واجهتها أثناء نطق التمارين...') }}"
                            class="bg-white border border-[#DEE8F7] rounded-[10px] p-3.5 w-full font-['Cairo'] font-medium text-[13px] text-[#475569] outline-none focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all resize-none"
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end pt-2 border-t border-[#DEE8F7]/60">
                        <button
                            type="submit"
                            class="bg-[#0B2A8F] hover:bg-[#012383] text-white rounded-[10px] h-[48px] px-8 flex items-center justify-center font-['Cairo'] font-bold text-[15px] shadow-sm hover:shadow transition-all cursor-pointer"
                        >
                            {{ __('إرسال التسجيل السريري للمراجعة') }}
                        </button>
                    </div>
                </form>
                @endif

            </div>

            <!-- Sidebar Info & Clinical Tips -->
            <div class="w-full lg:w-[320px] shrink-0 flex flex-col gap-[16px]">
                <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-5 sm:p-6 flex flex-col gap-4 w-full shadow-xs">
                    <h3 class="font-['Cairo'] font-bold text-[17px] leading-[1.4] text-[#012383]">
                        {{ __('إرشادات التسجيل الصوتي') }}
                    </h3>

                    <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                        <div class="flex items-start gap-2.5">
                            <span class="text-[#0B2A8F] font-bold">١.</span>
                            <span>{{ __('اختر مكاناً هادئاً بعيداً عن الصدى والضوضاء الخارجية.') }}</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="text-[#0B2A8F] font-bold">٢.</span>
                            <span>{{ __('تحدث بنبرة طبيعية وبمسافة ١٥ سم من المايكروفون.') }}</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="text-[#0B2A8F] font-bold">٣.</span>
                            <span>{{ __('احرص على وضوح الحروف ومخارج الأصوات المطلوب تدريبها.') }}</span>
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
