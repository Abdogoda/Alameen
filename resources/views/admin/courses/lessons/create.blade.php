<x-admin-layout::admin
    :title="__('إضافة درس جديد: :course', ['course' => $course->title_ar])"
    :description="__('رفع ومعالجة فيديو الدرس بنظام التجزئة المتقدم، وربطه بالمستوى التدريبي والمهمة السريرية')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-6 max-w-[1100px] w-full mx-auto min-w-0"
>
    <x-slot:actions>
        <div class="flex items-center gap-2">
            <a 
                href="{{ route('admin.courses.show', $course) }}" 
                class="h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs"
            >
                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>{{ __('العودة لمنهج الكورس') }}</span>
            </a>
        </div>
    </x-slot:actions>

    {{-- Session Flash Alerts --}}
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-bold flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="size-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 cursor-pointer">✕</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-bold space-y-1 shadow-xs">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form 
        id="form-create-lesson" 
        action="{{ route('admin.courses.videos.store', $course) }}" 
        method="POST" 
        enctype="multipart/form-data" 
        class="flex flex-col gap-6"
    >
        @csrf
        
        {{-- Hidden fields populated by Chunked Video Upload --}}
        <input type="hidden" name="video_source" id="field-video-source" value="{{ old('video_source', 'link') }}" />
        <input type="hidden" name="mux_playback_id" id="field-mux-playback-id" value="{{ old('mux_playback_id') }}" />
        <input type="hidden" name="mux_asset_id" id="field-mux-asset-id" value="{{ old('mux_asset_id') }}" />

        {{-- ===================================================== --}}
        {{-- CARD 1: Lesson Core Details                           --}}
        {{-- ===================================================== --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-5">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3 flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2.5">
                    <span class="size-8 rounded-xl bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-400 font-bold flex items-center justify-center text-sm shadow-2xs">
                        ١
                    </span>
                    <div>
                        <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white leading-tight">
                            {{ __('بيانات الدرس والمستوى الأكاديمي') }}
                        </h3>
                        <p class="text-xs text-slate-400">
                            {{ __('تحديد المستوى المستهدف، عنوان الدرس، ونسبة إكمال المشاهدة المطلوبة') }}
                        </p>
                    </div>
                </div>
                
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                    {{ $course->title_ar }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Target Level Selector --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                        {{ __('المستوى المستهدف:') }} <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="level_id" 
                        required 
                        class="w-full h-11 px-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50 cursor-pointer"
                    >
                        <option value="">{{ __('-- اختر المستوى التدريبي التابع له --') }}</option>
                        @foreach($course->levels as $level)
                            <option 
                                value="{{ $level->id }}" 
                                {{ (old('level_id', $selectedLevelId) == $level->id) ? 'selected' : '' }}
                            >
                                {{ $level->sort_order ?: '#' }}. {{ $level->title_ar }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Lesson Title Arabic --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                        {{ __('عنوان الدرس بالعربية:') }} <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title_ar" 
                        value="{{ old('title_ar') }}" 
                        required 
                        placeholder="مثال: الدرس ٤: تمارين تقوية عضلات النطق وجلسات التدريب" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Duration in Minutes --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                        {{ __('مدة الدرس التقديرية (بالدقائق):') }} <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="duration_minutes" 
                        id="input-duration-minutes" 
                        min="1" 
                        max="600" 
                        value="{{ old('duration_minutes', 20) }}" 
                        required 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>

                {{-- Min Completion Percentage --}}
                <div class="sm:col-span-2">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">
                            {{ __('نسبة إكمال المشاهدة المطلوبة (%):') }}
                        </label>
                        <span class="text-[11px] font-bold text-slate-400">{{ __('تفتح المهمة بعد تحقيق هذه النسبة') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input 
                            type="number" 
                            name="min_completion_percentage" 
                            id="input-min-completion" 
                            min="1" 
                            max="100" 
                            value="{{ old('min_completion_percentage', 80) }}" 
                            class="w-28 h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-black text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                        />
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <button type="button" onclick="document.getElementById('input-min-completion').value = 75" class="px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:border-[#0B2A8F] cursor-pointer">75%</button>
                            <button type="button" onclick="document.getElementById('input-min-completion').value = 80" class="px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:border-[#0B2A8F] cursor-pointer">80%</button>
                            <button type="button" onclick="document.getElementById('input-min-completion').value = 90" class="px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:border-[#0B2A8F] cursor-pointer">90%</button>
                            <button type="button" onclick="document.getElementById('input-min-completion').value = 100" class="px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:border-[#0B2A8F] cursor-pointer">100%</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Free Preview Toggle --}}
            <div class="pt-2">
                <label class="flex items-center gap-3 p-3 rounded-xl bg-blue-50/50 dark:bg-slate-800/40 border border-blue-100 dark:border-slate-700/60 cursor-pointer hover:border-[#0B2A8F]/40 transition-all">
                    <input 
                        type="checkbox" 
                        name="is_free_preview" 
                        value="1" 
                        {{ old('is_free_preview') ? 'checked' : '' }} 
                        class="size-4.5 rounded border-slate-300 text-[#012383] focus:ring-[#012383] cursor-pointer" 
                    />
                    <div>
                        <span class="text-xs font-bold text-[#012383] dark:text-blue-300 block">{{ __('إتاحة هذا الدرس كمعاينة مجانية (Free Preview)') }}</span>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('يستطيع الزوار والطلاب المسجلون حضور هذا الدرس قبل دفع رسوم الاشتراك') }}</span>
                    </div>
                </label>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- CARD 2: Advanced Video Uploader & Streaming Service   --}}
        {{-- ===================================================== --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-5" x-data="{ uploadTab: 'chunked' }">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="size-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 font-bold flex items-center justify-center text-sm shadow-2xs">
                        ٢
                    </span>
                    <div>
                        <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white leading-tight">
                            {{ __('مصدر ورفع فيديو الدرس (Video Streaming Engine)') }}
                        </h3>
                        <p class="text-xs text-slate-400">
                            {{ __('رفع بالتجزئة (Chunks)، شريط تقدم لحظي، ومعالجة تلقائية للسيرفر أو MUX') }}
                        </p>
                    </div>
                </div>

                {{-- Active Service Driver Badge --}}
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold text-slate-400">{{ __('محرك الخدمة:') }}</span>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold border flex items-center gap-1.5 {{ $isMuxConfigured ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-300 border-blue-200 dark:border-slate-700' }}">
                        <span class="size-2 rounded-full {{ $isMuxConfigured ? 'bg-emerald-500 animate-pulse' : 'bg-blue-500' }}"></span>
                        <span>{{ $activeDriverTitle }}</span>
                    </span>
                </div>
            </div>

            {{-- Mode Switcher Pills --}}
            <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-slate-100 dark:bg-slate-800/80 w-fit border border-slate-200/80 dark:border-slate-700">
                <button 
                    type="button" 
                    @click="uploadTab = 'chunked'" 
                    :class="uploadTab === 'chunked' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383]'" 
                    class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span>{{ __('رفع فيديو بالتجزئة المباشرة (Chunks Upload)') }}</span>
                </button>

                <button 
                    type="button" 
                    @click="uploadTab = 'link'" 
                    :class="uploadTab === 'link' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383]'" 
                    class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <span>{{ __('رابط خارجي أو معرّف Mux / YouTube') }}</span>
                </button>
            </div>

            {{-- ===================================================== --}}
            {{-- TAB 1: Chunked Video Uploader with Live Progress      --}}
            {{-- ===================================================== --}}
            <div x-show="uploadTab === 'chunked'" class="space-y-4">
                
                {{-- Dropzone / File Picker --}}
                <div 
                    id="chunked-dropzone" 
                    class="border-2 border-dashed border-[#DEE8F7] dark:border-slate-700 hover:border-[#012383] dark:hover:border-blue-400 rounded-2xl p-6 sm:p-8 text-center transition-all bg-slate-50/50 dark:bg-slate-800/30 flex flex-col items-center justify-center gap-3 cursor-pointer relative"
                    onclick="document.getElementById('file-video-input').click()"
                    ondragover="event.preventDefault(); this.classList.add('border-[#012383]', 'bg-blue-50/40')"
                    ondragleave="this.classList.remove('border-[#012383]', 'bg-blue-50/40')"
                    ondrop="handleFileDrop(event)"
                >
                    <div class="size-14 rounded-2xl bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-400 flex items-center justify-center shadow-xs">
                        <svg class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </div>

                    <div>
                        <h4 class="font-bold text-sm sm:text-base text-[#012383] dark:text-white">
                            {{ __('اضغط لاختيار ملف الفيديو أو اسحبه وأفلته هنا') }}
                        </h4>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ __('الصيغ المدعومة: MP4, MOV, WebM, MKV (يتم تجزئة الملف تلقائياً لضمان استقرار وسرعة الرفع حتى 2GB)') }}
                        </p>
                    </div>

                    <button 
                        type="button" 
                        class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-[#012383] dark:text-blue-300 font-bold text-xs shadow-2xs hover:bg-[#EAF1FB] transition-all cursor-pointer"
                    >
                        {{ __('تصفح الملفات من جهازك') }}
                    </button>

                    <input 
                        type="file" 
                        id="file-video-input" 
                        accept="video/mp4,video/quicktime,video/webm,video/x-matroska" 
                        class="hidden" 
                        onchange="handleFileSelect(this.files)" 
                    />
                </div>

                {{-- Live Progress & Processing Box (Hidden until upload begins) --}}
                <div id="upload-progress-container" class="hidden p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700 space-y-4">
                    
                    {{-- File Info Header --}}
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="size-10 rounded-xl bg-[#012383] text-white flex items-center justify-center shrink-0">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <span id="progress-file-name" class="font-bold text-xs sm:text-sm text-[#012383] dark:text-white truncate block"></span>
                                <span id="progress-file-size" class="text-[11px] text-slate-400 block"></span>
                            </div>
                        </div>

                        <span id="progress-status-badge" class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-[#012383] dark:bg-blue-950/60 dark:text-blue-300">
                            {{ __('جاري الرفع...') }}
                        </span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span id="progress-percentage-label" class="text-[#012383] dark:text-blue-300">0%</span>
                            <span id="progress-chunks-label" class="text-slate-400 font-mono text-[11px]">جاري التجهيز...</span>
                        </div>
                        <div class="w-full h-3 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden relative">
                            <div id="progress-bar-fill" class="h-full bg-gradient-to-r from-[#012383] via-blue-600 to-emerald-500 rounded-full transition-all duration-200 w-0"></div>
                        </div>
                    </div>

                    {{-- Metrics Grid (Speed, ETA, Uploaded Size) --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-1 text-center">
                        <div class="p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7]/80 dark:border-slate-700">
                            <span class="text-[10px] text-slate-400 block font-bold">{{ __('السرعة اللحظية') }}</span>
                            <span id="metric-speed" class="font-black text-xs sm:text-sm text-slate-700 dark:text-slate-200 mt-0.5 block">-</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7]/80 dark:border-slate-700">
                            <span class="text-[10px] text-slate-400 block font-bold">{{ __('الوقت المتبقي (ETA)') }}</span>
                            <span id="metric-eta" class="font-black text-xs sm:text-sm text-slate-700 dark:text-slate-200 mt-0.5 block">-</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7]/80 dark:border-slate-700">
                            <span class="text-[10px] text-slate-400 block font-bold">{{ __('الأجزاء المرفوعة') }}</span>
                            <span id="metric-chunks" class="font-black text-xs sm:text-sm text-[#012383] dark:text-blue-300 mt-0.5 block font-mono">0 / 0</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7]/80 dark:border-slate-700">
                            <span class="text-[10px] text-slate-400 block font-bold">{{ __('الحجم المرفوع') }}</span>
                            <span id="metric-uploaded" class="font-black text-xs sm:text-sm text-emerald-600 dark:text-emerald-400 mt-0.5 block">-</span>
                        </div>
                    </div>

                    {{-- Processing State Banner --}}
                    <div id="processing-banner" class="hidden p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 flex items-center gap-3">
                        <svg class="size-5 text-amber-600 animate-spin shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <div class="text-xs">
                            <span class="font-bold text-amber-900 dark:text-amber-200 block">{{ __('اكتمل رفع الأجزاء (100%): جاري معالجة وتهيئة بث الفيديو...') }}</span>
                            <span class="text-amber-700 dark:text-amber-300 text-[11px]">{{ __('يقوم السيرفر بتجميع الأجزاء وضغط الفيديو أو رفعه لمنصة البث.') }}</span>
                        </div>
                    </div>

                    {{-- Success & Ready Banner with Live Player Preview --}}
                    <div id="success-banner" class="hidden p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-emerald-800 dark:text-emerald-300 text-xs font-bold">
                                <svg class="size-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span id="success-message-text">{{ __('تم تجهيز ومعالجة الفيديو بنجاح وهو جاهز للنشر!') }}</span>
                            </div>
                            <span id="success-driver-badge" class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-200 text-emerald-900"></span>
                        </div>

                        {{-- Preview Player --}}
                        <div class="rounded-xl overflow-hidden bg-black aspect-video max-w-lg mx-auto shadow-inner relative">
                            <video id="preview-player" controls class="size-full"></video>
                        </div>
                    </div>

                </div>

            </div>

            {{-- ===================================================== --}}
            {{-- TAB 2: External Link / MUX ID / YouTube / Vimeo       --}}
            {{-- ===================================================== --}}
            <div x-show="uploadTab === 'link'" style="display: none;" class="space-y-3">
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block">
                    {{ __('رابط الفيديو أو معرّف بث MUX Playback ID:') }}
                </label>
                <input 
                    type="text" 
                    name="video_url" 
                    id="input-external-url" 
                    value="{{ old('video_url') }}" 
                    placeholder="https://stream.mux.com/XXXXX.m3u8 أو معرّف MUX أو رابط YouTube / Vimeo / HLS" 
                    class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                />
                <p class="text-[11px] text-slate-400">
                    {{ __('يدعم النظام التعرف التلقائي على روابط MUX، ملفات .m3u8 المباشرة، يوتيوب، وفيميو.') }}
                </p>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- CARD 3: Attached Clinical Task                        --}}
        {{-- ===================================================== --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-4">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3 flex items-center gap-2.5">
                <span class="size-8 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 font-bold flex items-center justify-center text-sm shadow-2xs">
                    ٣
                </span>
                <div>
                    <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white leading-tight">
                        {{ __('المهمة السريرية الملحقة بالدرس (اختياري)') }}
                    </h3>
                    <p class="text-xs text-slate-400">
                        {{ __('تفتح المهمة للطالب تلقائياً بعد استيفاء نسبة مشاهدة الفيديو المحددة أعلاه') }}
                    </p>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                    {{ __('عنوان المهمة السريرية:') }}
                </label>
                <input 
                    type="text" 
                    name="task_title_ar" 
                    value="{{ old('task_title_ar') }}" 
                    placeholder="مثال: تسجيل مقطع صوتي يوضح مخارج أصوات الحروف اللثوية" 
                    class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                />
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                    {{ __('تعليمات المهمة والمطلوب من المتدرب:') }}
                </label>
                <textarea 
                    name="task_instructions_ar" 
                    rows="2" 
                    placeholder="المعايير السريرية المطلوبة، نموذج النطق، ومدة التسجيل الصوتي الموصى بها..." 
                    class="w-full p-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] resize-none bg-slate-50/50 dark:bg-slate-800/50"
                >{{ old('task_instructions_ar') }}</textarea>
            </div>
        </div>

        {{-- Bottom Action Buttons --}}
        <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-3 pt-2">
            <a 
                href="{{ route('admin.courses.show', $course) }}" 
                class="w-full sm:w-auto px-6 h-11 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center hover:bg-slate-50 transition-all cursor-pointer"
            >
                {{ __('إلغاء والعودة للمنهج') }}
            </a>

            <button 
                type="submit" 
                id="btn-submit-lesson" 
                class="w-full sm:w-auto px-8 h-12 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-extrabold text-sm shadow-md hover:shadow-lg transition-all duration-200 active:scale-98 flex items-center justify-center gap-2 cursor-pointer"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ __('حفظ ونشر الدرس في المنهج') }}</span>
            </button>
        </div>

    </form>

    {{-- ===================================================== --}}
    {{-- Client-Side Chunked Upload Engine JavaScript          --}}
    {{-- ===================================================== --}}
    <script>
        const CHUNK_SIZE = 2 * 1024 * 1024; // 2MB chunk slice
        const UPLOAD_URL = "{{ route('admin.courses.videos.chunk.upload', $course) }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";

        let currentFile = null;
        let isUploading = false;
        let uploadStartTime = 0;
        let uploadedBytesTotal = 0;

        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function handleFileDrop(e) {
            e.preventDefault();
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFileSelect(files);
        }

        function handleFileSelect(files) {
            if (!files || files.length === 0) return;
            currentFile = files[0];
            startChunkedUpload(currentFile);
        }

        async function startChunkedUpload(file) {
            if (isUploading) return;
            isUploading = true;

            const uploadId = 'up_' + Date.now() + '_' + Math.random().toString(36).substring(2, 10);
            const totalSize = file.size;
            const totalChunks = Math.ceil(totalSize / CHUNK_SIZE);

            // Show progress box
            const progressContainer = document.getElementById('upload-progress-container');
            const dropzone = document.getElementById('chunked-dropzone');
            progressContainer.classList.remove('hidden');
            dropzone.classList.add('opacity-50', 'pointer-events-none');

            document.getElementById('progress-file-name').textContent = file.name;
            document.getElementById('progress-file-size').textContent = formatBytes(totalSize);
            document.getElementById('progress-status-badge').textContent = '{{ __("جاري الرفع...") }}';
            document.getElementById('progress-status-badge').className = 'px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-[#012383] dark:bg-blue-950/60 dark:text-blue-300';
            document.getElementById('processing-banner').classList.add('hidden');
            document.getElementById('success-banner').classList.add('hidden');

            uploadStartTime = Date.now();
            uploadedBytesTotal = 0;

            for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
                const start = chunkIndex * CHUNK_SIZE;
                const end = Math.min(start + CHUNK_SIZE, totalSize);
                const chunkBlob = file.slice(start, end);

                const formData = new FormData();
                formData.append('_token', CSRF_TOKEN);
                formData.append('upload_id', uploadId);
                formData.append('chunk_index', chunkIndex);
                formData.append('total_chunks', totalChunks);
                formData.append('original_filename', file.name);
                formData.append('file_chunk', chunkBlob, file.name);

                // If last chunk, show processing state banner right before or during
                if (chunkIndex === totalChunks - 1) {
                    document.getElementById('processing-banner').classList.remove('hidden');
                    document.getElementById('progress-status-badge').textContent = '{{ __("جاري المعالجة...") }}';
                    document.getElementById('progress-status-badge').className = 'px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 dark:bg-amber-950/60 dark:text-amber-200 animate-pulse';
                }

                try {
                    const response = await fetch(UPLOAD_URL, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.message || 'فشل في رفع الجزء ' + (chunkIndex + 1));
                    }

                    const result = await response.json();

                    // Update Metrics
                    uploadedBytesTotal += (end - start);
                    const percent = Math.min(100, Math.round((uploadedBytesTotal / totalSize) * 100));

                    document.getElementById('progress-bar-fill').style.width = percent + '%';
                    document.getElementById('progress-percentage-label').textContent = percent + '%';
                    document.getElementById('progress-chunks-label').textContent = `{{ __("الجزء") }} ${chunkIndex + 1} / ${totalChunks}`;
                    document.getElementById('metric-chunks').textContent = `${chunkIndex + 1} / ${totalChunks}`;
                    document.getElementById('metric-uploaded').textContent = formatBytes(uploadedBytesTotal);

                    // Speed & ETA
                    const elapsedSeconds = (Date.now() - uploadStartTime) / 1000;
                    if (elapsedSeconds > 0) {
                        const bytesPerSecond = uploadedBytesTotal / elapsedSeconds;
                        document.getElementById('metric-speed').textContent = formatBytes(bytesPerSecond) + '/s';

                        const remainingBytes = totalSize - uploadedBytesTotal;
                        const remainingSeconds = Math.round(remainingBytes / bytesPerSecond);
                        document.getElementById('metric-eta').textContent = remainingSeconds > 0 ? remainingSeconds + ' ثانية' : 'لحظات...';
                    }

                    // Check if complete
                    if (result.status === 'completed') {
                        onUploadCompleted(result);
                        return;
                    }
                } catch (err) {
                    isUploading = false;
                    alert('خطأ أثناء الرفع: ' + err.message);
                    dropzone.classList.remove('opacity-50', 'pointer-events-none');
                    document.getElementById('progress-status-badge').textContent = 'فشل الرفع';
                    document.getElementById('progress-status-badge').className = 'px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800';
                    document.getElementById('processing-banner').classList.add('hidden');
                    return;
                }
            }
        }

        function onUploadCompleted(result) {
            isUploading = false;
            document.getElementById('processing-banner').classList.add('hidden');
            document.getElementById('progress-status-badge').textContent = '{{ __("تمت المعالجة بنجاح") }}';
            document.getElementById('progress-status-badge').className = 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300';
            
            const successBanner = document.getElementById('success-banner');
            successBanner.classList.remove('hidden');

            document.getElementById('success-driver-badge').textContent = result.driver_used === 'mux' ? 'MUX Cloud Stream' : '{{ __("سيرفر المنصة المحلي") }}';
            if (result.message) {
                document.getElementById('success-message-text').textContent = result.message;
            }

            // Populate form fields
            document.getElementById('field-video-source').value = result.video_source || 'local';
            if (result.video_url) {
                document.getElementById('input-external-url').value = result.video_url;
            }
            if (result.mux_playback_id) {
                document.getElementById('field-mux-playback-id').value = result.mux_playback_id;
            }
            if (result.mux_asset_id) {
                document.getElementById('field-mux-asset-id').value = result.mux_asset_id;
            }

            // Play video preview
            const player = document.getElementById('preview-player');
            if (result.mux_playback_id) {
                const streamUrl = `https://stream.mux.com/${result.mux_playback_id}.m3u8`;
                if (window.Hls && Hls.isSupported()) {
                    const hls = new Hls();
                    hls.loadSource(streamUrl);
                    hls.attachMedia(player);
                } else {
                    player.src = streamUrl;
                }
            } else if (result.video_url) {
                player.src = result.video_url;
            }
        }
    </script>
</x-admin-layout::admin>
