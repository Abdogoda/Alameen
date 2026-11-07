<x-admin-layout::admin
    :title="__('تعديل الكورس التدريبي: :title', ['title' => $course->title_ar])"
    :description="__('تعديل تفاصيل الكورس، السعر، الصورة البارزة، وإعدادات الفيديو التعريفي عبر MUX أو رابط خارجي')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-6 max-w-[1200px] w-full mx-auto min-w-0"
>
    <x-slot:actions>
        <div class="flex items-center gap-2 flex-wrap">
            <a 
                href="{{ route('admin.courses.index') }}" 
                class="h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs"
            >
                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>{{ __('دليل الكورسات') }}</span>
            </a>

            <a 
                href="{{ route('admin.courses.assign-users', $course) }}" 
                class="h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800/80 hover:bg-purple-100 text-purple-700 dark:text-purple-300 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>{{ __('تعيين الطلاب (:count)', ['count' => $course->users_count]) }}</span>
            </a>

            <a 
                href="{{ route('admin.courses.show', $course) }}" 
                class="h-9 sm:h-11 px-3.5 sm:px-5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs flex items-center gap-1.5 transition-all shadow-xs hover:shadow-md"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>{{ __('عرض المنهج والمستويات') }}</span>
            </a>
        </div>
    </x-slot:actions>

    {{-- Session Flash Alerts --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-bold flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="size-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 cursor-pointer">✕</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-bold space-y-1 shadow-xs">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
        @csrf
        @method('PUT')

        {{-- Card 1: Main Course Metadata --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-5">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white">{{ __('البيانات الأساسية للكورس') }}</h3>
                    <p class="text-xs text-slate-400">{{ __('العناوين، الاسم المعرّف (Slug)، وخيارات الإتاحة للطلاب') }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ __('حالة النشر:') }}</label>
                        <select name="is_published" class="h-9 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-slate-50 dark:bg-slate-800 text-[#012383] dark:text-white outline-none">
                            <option value="1" {{ $course->is_published ? 'selected' : '' }}>{{ __('منشور ونشط للطلاب') }}</option>
                            <option value="0" {{ ! $course->is_published ? 'selected' : '' }}>{{ __('مسودة غير منشورة') }}</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ __('الإتاحة التلقائية للطلاب:') }}</label>
                        <select name="auto_enroll_students" class="h-9 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold {{ $course->auto_enroll_students ? 'bg-blue-50 dark:bg-blue-950/60 text-[#012383] dark:text-blue-300 border-blue-300' : 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200' }} outline-none">
                            <option value="1" {{ old('auto_enroll_students', $course->auto_enroll_students) ? 'selected' : '' }}>{{ __('مفعّل تلقائياً لجميع الطلاب') }}</option>
                            <option value="0" {{ ! old('auto_enroll_students', $course->auto_enroll_students) ? 'selected' : '' }}>{{ __('غير مفعّل (يتطلب اشتراك وإيصال)') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                    {{ __('عنوان الكورس بالعربية:') }} <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title_ar" 
                    required 
                    value="{{ old('title_ar', $course->title_ar) }}" 
                    class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                />
                <input type="hidden" name="title_en" value="{{ old('title_en', $course->title_en) }}" />
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                    {{ __('المعرّف الرابطي (Slug):') }} <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="slug" 
                    required 
                    value="{{ old('slug', $course->slug) }}" 
                    class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                />
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('نبذة ووصف الكورس بالعربية:') }}</label>
                <textarea name="description_ar" rows="3" class="w-full p-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] resize-none bg-slate-50/50 dark:bg-slate-800/50">{{ old('description_ar', $course->description_ar) }}</textarea>
                <input type="hidden" name="description_en" value="{{ old('description_en', $course->description_en) }}" />
            </div>
        </div>

        {{-- Card 2: Pricing & Access Duration --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-5">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3">
                <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white">{{ __('التسعير وصلاحية الوصول') }}</h3>
                <p class="text-xs text-slate-400">{{ __('السعر الفعلي المطلوب وسعر الشطب قبل الخصم، ومدة اشتراك الطالب') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                        {{ __('السعر الفعلي (ج.م):') }} <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="price" 
                        required 
                        min="0" 
                        value="{{ old('price', (int)$course->price) }}" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-blue-400 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                        {{ __('السعر الأصلي قبل الخصم (ج.م):') }}
                    </label>
                    <input 
                        type="number" 
                        name="original_price" 
                        min="0" 
                        value="{{ old('original_price', (int)$course->original_price) }}" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-600 dark:text-slate-300 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                        {{ __('مدة الصلاحية (أيام):') }} <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="access_duration_days" 
                        required 
                        min="1" 
                        value="{{ old('access_duration_days', $course->access_duration_days) }}" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>
            </div>

            {{-- Auto Enroll Info Banner --}}
            <div class="p-4 rounded-2xl {{ $course->auto_enroll_students ? 'bg-blue-50/70 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900/50' : 'bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700' }} flex items-start gap-3">
                <div class="size-9 rounded-xl {{ $course->auto_enroll_students ? 'bg-blue-100 dark:bg-blue-900/50 text-[#012383] dark:text-blue-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' }} flex items-center justify-center shrink-0">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h4 class="text-xs font-bold {{ $course->auto_enroll_students ? 'text-[#012383] dark:text-blue-300' : 'text-slate-700 dark:text-slate-300' }}">
                            {{ $course->auto_enroll_students ? __('هذا الكورس متاح ومفعّل تلقائياً لجميع الطلاب المعتمدين') : __('هذا الكورس يتطلب اشتراكاً من الطالب ورفع إيصال سداد واعتماده') }}
                        </h4>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $course->auto_enroll_students ? 'bg-blue-200/70 text-[#012383] dark:bg-blue-900 dark:text-blue-200' : 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                            {{ $course->auto_enroll_students ? __('وصول تلقائي مفتوح') : __('سداد مسبق') }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('عند تفعيل خيار الإتاحة التلقائية (من أعلى الصفحة أو من قائمة الكورسات)، يظهر الكورس فوراً في مكتبة دورات أي طالب يتم تفعيل حسابه، ويتاح له فتح المحاضرات والمهام التدريبية مباشرة بدون رفع إيصال دفع.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 3: Promo / Preview Video (Resilient Chunked Upload & MUX / Link + Live Player Preview) --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-5" x-data="{ videoOption: 'upload' }">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white flex items-center gap-2">
                        <svg class="size-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>{{ __('الفيديو التعريفي للكورس (MUX Platform / Link)') }}</span>
                    </h3>
                    <p class="text-xs text-slate-400">{{ __('رفع الفيديو التعريفي بالتجزئة وشريط التقدم المباشر مع استقرار تام (Zero Timeout)، أو تضمينه عبر رابط خارجي مع المعاينة الفورية') }}</p>
                </div>

                {{-- Source Switcher Tabs --}}
                <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
                    <button 
                        type="button" 
                        @click="videoOption = 'upload'" 
                        :class="videoOption === 'upload' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383]'" 
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer flex items-center gap-1.5"
                    >
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <span>{{ __('رفع ملف فيديو إلى MUX') }}</span>
                    </button>
                    <button 
                        type="button" 
                        @click="videoOption = 'mux_link'" 
                        :class="videoOption === 'mux_link' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383]'" 
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer flex items-center gap-1.5"
                    >
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <span>{{ __('رابط خارجي أو Mux ID') }}</span>
                    </button>
                </div>
            </div>

            {{-- Hidden inputs to pass uploaded promo video metadata to the course update form --}}
            <input type="hidden" name="promo_video_source" id="field-promo-video-source" value="{{ old('promo_video_source', $course->promo_video_source) }}" />
            <input type="hidden" name="mux_playback_id" id="field-mux-playback-id" value="{{ old('mux_playback_id', $course->mux_playback_id) }}" />
            <input type="hidden" name="mux_asset_id" id="field-mux-asset-id" value="{{ old('mux_asset_id', $course->mux_asset_id) }}" />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                <div class="space-y-4">
                    {{-- Option 1: File Upload in Chunks --}}
                    <div x-show="videoOption === 'upload'" class="space-y-3">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block">
                            {{ __('رفع ملف فيديو تعريفي جديد:') }}
                        </label>

                        {{-- Dropzone / File Picker --}}
                        <div 
                            id="promo-chunked-dropzone" 
                            class="border-2 border-dashed border-[#DEE8F7] dark:border-slate-700 hover:border-[#012383] dark:hover:border-blue-400 rounded-2xl p-5 text-center transition-all bg-slate-50/50 dark:bg-slate-800/30 flex flex-col items-center justify-center gap-2.5 cursor-pointer relative"
                            onclick="document.getElementById('file-promo-video-input').click()"
                            ondragover="event.preventDefault(); this.classList.add('border-[#012383]', 'bg-blue-50/40')"
                            ondragleave="this.classList.remove('border-[#012383]', 'bg-blue-50/40')"
                            ondrop="handlePromoFileDrop(event)"
                        >
                            <div class="size-11 rounded-xl bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-400 flex items-center justify-center shadow-xs">
                                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>

                            <div>
                                <h4 class="font-bold text-xs sm:text-sm text-[#012383] dark:text-white">
                                    {{ __('اضغط لاختيار ملف الفيديو التعريفي أو اسحبه هنا') }}
                                </h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    {{ __('يتم الرفع بنظام التجزئة وشريط التقدم اللحظي لتجنب انقطاع الاتصال (Zero Timeout)') }}
                                </p>
                            </div>

                            <button 
                                type="button" 
                                class="px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-[#012383] dark:text-blue-300 font-bold text-xs shadow-2xs hover:bg-[#EAF1FB] transition-all cursor-pointer"
                            >
                                {{ __('تصفح الفيديو من جهازك') }}
                            </button>

                            <input 
                                type="file" 
                                id="file-promo-video-input" 
                                accept="video/mp4,video/quicktime,video/webm,video/x-matroska" 
                                class="hidden" 
                                onchange="handlePromoFileSelect(this.files)" 
                            />
                        </div>

                        {{-- Live Progress Container --}}
                        <div id="promo-upload-progress-container" class="hidden p-4 sm:p-5 rounded-2xl bg-slate-50/90 dark:bg-slate-800/80 border border-[#DEE8F7] dark:border-slate-700/80 shadow-xs space-y-4 transition-all">
                            
                            {{-- File Info Header --}}
                            <div class="flex items-center justify-between flex-wrap gap-2 pb-2.5 border-b border-slate-200/70 dark:border-slate-700/60">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="size-9 rounded-xl bg-gradient-to-tr from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center shrink-0 shadow-xs ring-2 ring-blue-500/10">
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span id="promo-progress-file-name" class="font-bold text-xs text-slate-800 dark:text-white truncate block max-w-xs"></span>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span id="promo-progress-file-size" dir="ltr" class="text-[10px] font-mono font-medium text-slate-400"></span>
                                            <span class="size-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ __('رفع مجزأ ذكي (Smart Chunks)') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Dynamic Status Badge --}}
                                <div id="promo-progress-status-badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100/90 text-[#012383] dark:bg-blue-950/70 dark:text-blue-300 border border-blue-200 dark:border-blue-900 transition-all shadow-2xs">
                                    <span class="relative flex size-2 shrink-0">
                                        <span class="animate-ping absolute inline-flex size-full rounded-full bg-blue-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full size-2 bg-blue-600"></span>
                                    </span>
                                    <span id="promo-progress-status-text">{{ __('جاري الرفع...') }}</span>
                                </div>
                            </div>

                            {{-- Progress Bar & Percentage --}}
                            <div class="space-y-2">
                                <div class="flex items-end justify-between gap-2">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 text-[11px] font-bold text-slate-700 dark:text-slate-200">
                                        <svg class="size-3 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        <span id="promo-progress-chunks-label">{{ __('جاري التجهيز...') }}</span>
                                    </div>

                                    <span id="promo-progress-percentage-label" class="text-xl sm:text-2xl font-black text-[#012383] dark:text-blue-400 font-mono tracking-tight leading-none">0%</span>
                                </div>

                                <div class="w-full h-3 rounded-full bg-slate-200/80 dark:bg-slate-700/80 p-0.5 overflow-hidden shadow-inner relative">
                                    <div id="promo-progress-bar-fill" class="h-full rounded-full bg-gradient-to-r from-[#012383] via-blue-600 to-teal-400 dark:from-blue-600 dark:via-indigo-500 dark:to-cyan-400 transition-all duration-300 ease-out relative overflow-hidden flex items-center justify-end w-0 shadow-xs">
                                        <div class="absolute inset-0 upload-shimmer-stripes opacity-30"></div>
                                        <div class="relative size-1.5 rounded-full bg-white shadow-xs mx-1 shrink-0 opacity-80"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Metrics Grid --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 text-center">
                                <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700/80">
                                    <span class="text-[10px] text-slate-400 block mb-0.5">{{ __('السرعة') }}</span>
                                    <span id="promo-metric-speed" dir="ltr" class="font-black text-xs text-slate-700 dark:text-slate-200 block font-mono">-</span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700/80">
                                    <span class="text-[10px] text-slate-400 block mb-0.5">{{ __('المتبقي') }}</span>
                                    <span id="promo-metric-eta" class="font-black text-xs text-slate-700 dark:text-slate-200 block font-mono">-</span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700/80">
                                    <span class="text-[10px] text-slate-400 block mb-0.5">{{ __('الأجزاء') }}</span>
                                    <span id="promo-metric-chunks" dir="ltr" class="font-black text-xs text-[#012383] dark:text-blue-300 font-mono">0 / 0</span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700/80">
                                    <span class="text-[10px] text-slate-400 block mb-0.5">{{ __('المرفوع') }}</span>
                                    <span id="promo-metric-uploaded" dir="ltr" class="font-black text-xs text-emerald-600 dark:text-emerald-400 block font-mono">-</span>
                                </div>
                            </div>

                            {{-- Processing State Banner --}}
                            <div id="promo-processing-banner" class="hidden p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 flex items-center gap-2.5">
                                <svg class="size-4.5 text-amber-600 animate-spin shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <div class="text-xs">
                                    <span class="font-bold text-amber-900 dark:text-amber-200 block">{{ __('اكتمل رفع الأجزاء (100%): جاري المعالجة والحفظ...') }}</span>
                                    <span class="text-amber-700 dark:text-amber-300 text-[11px]">{{ __('يقوم السيرفر بتجميع الأجزاء وتجهيز الفيديو التعريفي فوراً.') }}</span>
                                </div>
                            </div>

                            {{-- Success & Ready Banner --}}
                            <div id="promo-success-banner" class="hidden p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 space-y-2">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div class="flex items-center gap-2 text-emerald-800 dark:text-emerald-300 text-xs font-bold">
                                        <svg class="size-4.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span id="promo-success-message-text">{{ __('تم رفع وتجهيز الفيديو التعريفي بنجاح وهو معروض بالمعاينة!') }}</span>
                                    </div>
                                    <span id="promo-success-driver-badge" class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-200 text-emerald-900"></span>
                                </div>
                                <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium">
                                    {{ __('يمكنك الآن متابعة تعديل تفاصيل الكورس (السعر، الصلاحية، إلخ) وحفظ التعديلات بشكل طبيعي.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Option 2: Link / Mux Playback ID --}}
                    <div x-show="videoOption === 'mux_link'" class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block">
                            {{ __('رابط الفيديو أو معرّف بث MUX:') }}
                        </label>
                        <input 
                            type="text" 
                            id="promo-url-input"
                            name="promo_video_url" 
                            value="{{ old('promo_video_url', $course->mux_playback_id ?: $course->promo_video_url) }}" 
                            placeholder="{{ __('https://stream.mux.com/XXXXX.m3u8 أو معرّف MUX أو رابط YouTube / Vimeo') }}" 
                            class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                        />
                        <p class="text-[11px] text-slate-400">
                            {{ __('يدعم: روابط بث MUX (.m3u8)، معرّفات Mux Playback ID، روابط YouTube، روابط Vimeo، وملفات MP4 المباشرة.') }}
                        </p>
                    </div>

                    {{-- Current Video Source Badge --}}
                    <div id="promo-current-badge-container">
                        @if(!empty($course->mux_playback_id))
                            <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-800 dark:text-emerald-300 space-y-1">
                                <span class="font-bold flex items-center gap-1.5">
                                    <span class="size-2 rounded-full bg-emerald-500"></span>
                                    {{ __('مرتبط حالياً بمنصة MUX') }}
                                </span>
                                <p class="font-mono text-[11px] truncate">Playback ID: {{ $course->mux_playback_id }}</p>
                            </div>
                        @elseif($course->promo_video_source === 'local' && !empty($course->promo_video_url))
                            <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-xs text-[#012383] dark:text-blue-300 space-y-1">
                                <span class="font-bold flex items-center gap-1.5">
                                    <span class="size-2 rounded-full bg-blue-500"></span>
                                    {{ __('مخزن حالياً على سيرفر المنصة المحلي') }}
                                </span>
                                <p class="font-mono text-[11px] truncate">{{ $course->promo_video_url }}</p>
                            </div>
                        @elseif(!empty($course->promo_video_url))
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300 space-y-1">
                                <span class="font-bold flex items-center gap-1.5">
                                    <span class="size-2 rounded-full bg-slate-500"></span>
                                    {{ __('مرتبط برابط خارجي') }}
                                </span>
                                <p class="font-mono text-[11px] truncate">{{ $course->promo_video_url }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Live Promo Video Preview Box --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-[#012383] dark:text-blue-400 block">
                            {{ __('معاينة الفيديو مباشرة (Live Player Preview):') }}
                        </label>
                        <button 
                            type="button" 
                            onclick="reloadPromoPreview()" 
                            class="text-xs font-bold text-[#0B2A8F] hover:underline cursor-pointer"
                        >
                            {{ __('تحديث المعاينة 🔄') }}
                        </button>
                    </div>

                    <div id="promo-preview-player" class="w-full bg-slate-950 rounded-2xl overflow-hidden aspect-video border border-slate-800 flex items-center justify-center relative shadow-md">
                        @if(!empty($course->mux_playback_id))
                            <mux-player 
                                playback-id="{{ $course->mux_playback_id }}" 
                                stream-type="on-demand" 
                                controls 
                                class="w-full h-full"
                            ></mux-player>
                        @elseif(!empty($course->effective_promo_video_url))
                            <video src="{{ $course->effective_promo_video_url }}" controls class="w-full h-full"></video>
                        @else
                            <div class="text-center p-6 text-slate-500 text-xs">
                                <svg class="size-8 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>{{ __('أدخل رابط الفيديو أو ارفع ملف لمعاينته هنا.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Thumbnail --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-4">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3">
                <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white">{{ __('صورة الغلاف (Thumbnail)') }}</h3>
                <p class="text-xs text-slate-400">{{ __('صورة العرض في دليل الدورات وصفحة تفاصيل الكورس') }}</p>
            </div>

            <div class="flex items-center gap-5 flex-wrap sm:flex-nowrap">
                <div class="size-24 sm:size-28 rounded-2xl bg-[#012383] overflow-hidden border border-[#DEE8F7] dark:border-slate-700 shrink-0 shadow-2xs">
                    <img id="thumbnail-preview" src="{{ $course->thumbnail ?: '/assets/images/course-topic-basics.png' }}" alt="{{ $course->title_ar }}" class="size-full object-cover" />
                </div>

                <div class="space-y-2 flex-1">
                    <input 
                        type="file" 
                        name="thumbnail_file" 
                        accept="image/*" 
                        onchange="previewThumbnailFile(this)"
                        class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#EAF1FB] file:text-[#012383] hover:file:bg-[#DEE8F7] cursor-pointer" 
                    />
                    <div class="text-xs text-slate-400">
                        {{ __('أو حدد مسار الصورة المباشر:') }}
                    </div>
                    <input 
                        type="text" 
                        name="thumbnail" 
                        value="{{ old('thumbnail', $course->thumbnail) }}" 
                        placeholder="/assets/images/..." 
                        class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>
            </div>
        </div>

        {{-- Card 4: Certificate Template & Custom Background --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-5">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white">{{ __('تصميم وقالب شهادة هذا الكورس') }}</h3>
                    <p class="text-xs text-slate-400">{{ __('تخصيص القالب أو رفع خلفية شهادة مستقلة لهذا الكورس التدريبي تحديداً') }}</p>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/40 text-[#012383] dark:text-blue-300 font-bold border border-blue-200 dark:border-blue-800">
                    {{ __('شهادة إتمام ٩٥٪') }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                        {{ __('قالب الشهادة المعتمد للكورس:') }}
                    </label>
                    <select name="certificate_template" class="w-full h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-slate-50/50 dark:bg-slate-800/50 text-[#012383] dark:text-white outline-none">
                        <option value="default" {{ ($course->certificate_template === 'default' || empty($course->certificate_template)) ? 'selected' : '' }}>
                            {{ __('استخدام القالب الافتراضي للمنصة') }}
                        </option>
                        <option value="royal_gold" {{ $course->certificate_template === 'royal_gold' ? 'selected' : '' }}>
                            👑 {{ __('الملكي الذهبي (Royal Gold)') }}
                        </option>
                        <option value="clinical_emerald" {{ $course->certificate_template === 'clinical_emerald' ? 'selected' : '' }}>
                            🩺 {{ __('السريري الزمردي (Clinical Emerald)') }}
                        </option>
                        <option value="modern_navy" {{ $course->certificate_template === 'modern_navy' ? 'selected' : '' }}>
                            🎓 {{ __('الأكاديمي العصري (Modern Academic)') }}
                        </option>
                        <option value="custom" {{ $course->certificate_template === 'custom' ? 'selected' : '' }}>
                            🎨 {{ __('قالب مخصص مرفوع للكورس') }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                        {{ __('رفع خلفية شهادة خاصة بالكورس (A4):') }}
                    </label>
                    <input type="file" name="certificate_custom_bg_file" accept="image/png,image/jpeg,image/webp" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#012383] file:text-white hover:file:bg-[#0B2A8F]" />
                    @if($course->certificate_custom_bg)
                        <div class="mt-2 text-[11px] text-emerald-600 font-bold flex items-center gap-1">
                            <span>✓</span>
                            <a href="{{ $course->certificate_custom_bg }}" target="_blank" class="underline">{{ __('معاينة الخلفية المرفوعة حالياً للكورس') }}</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                        {{ __('اسم المعتمد / المحاضر على الشهادة (اختياري):') }}
                    </label>
                    <input 
                        type="text" 
                        name="certificate_signatory_name" 
                        value="{{ old('certificate_signatory_name', $course->certificate_signatory_name) }}" 
                        placeholder="{{ __('اتركه فارغاً لاستخدام المعتمد الافتراضي للمنصة') }}" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-slate-50/50 dark:bg-slate-800/50 text-[#012383] dark:text-white outline-none focus:border-[#012383]" 
                    />
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                        {{ __('صفة المعتمد (اختياري):') }}
                    </label>
                    <input 
                        type="text" 
                        name="certificate_signatory_title" 
                        value="{{ old('certificate_signatory_title', $course->certificate_signatory_title) }}" 
                        placeholder="{{ __('اتركه فارغاً لاستخدام المسمى الافتراضي') }}" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-slate-50/50 dark:bg-slate-800/50 text-[#012383] dark:text-white outline-none focus:border-[#012383]" 
                    />
                </div>
            </div>
        </div>

        {{-- Form Action Buttons --}}
        <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 pt-2">
            {{-- Delete Course Form Button --}}
            <div>
                <button 
                    type="button" 
                    onclick="window.AlameenConfirm({
                        title: '{{ __('حذف الكورس التدريبي') }}',
                        message: '{{ __('هل أنت متأكد من رغبتك في حذف هذا الكورس نهائياً؟') }}',
                        confirmText: '{{ __('نعم، احذف الكورس') }}',
                        onConfirm: () => {
                            if(window.AlameenButtonLoader) window.AlameenButtonLoader.setButtonLoading(this, true, '{{ __('جاري حذف الكورس...') }}');
                            document.getElementById('delete-course-form').submit();
                        }
                    })" 
                    class="px-4 py-2.5 rounded-xl border border-rose-300 dark:border-rose-900 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-xs font-bold transition-all cursor-pointer"
                >
                    {{ __('حذف الكورس التدريبي نهائياً') }}
                </button>
            </div>

            {{-- Save & Cancel Buttons --}}
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a 
                    href="{{ route('admin.courses.show', $course) }}" 
                    class="w-full sm:w-auto px-5 h-11 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center cursor-pointer"
                >
                    {{ __('إلغاء') }}
                </a>

                <button 
                    type="submit" 
                    id="btn-submit-course"
                    class="w-full sm:w-auto px-8 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs sm:text-sm shadow-md hover:shadow-lg hover:shadow-blue-900/30 active:scale-95 transition-all cursor-pointer flex items-center justify-center gap-2"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span id="btn-submit-course-text">{{ __('حفظ التعديلات ونشر الكورس') }}</span>
                </button>
            </div>
        </div>
    </form>

    {{-- Hidden Delete Course Form --}}
    <form id="delete-course-form" action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    {{-- Mux Player & UpChunk --}}
    <script src="https://cdn.jsdelivr.net/npm/@mux/mux-player"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mux/upchunk@3/dist/upchunk.min.js"></script>

    <script>
        const IS_MUX_CONFIGURED = {{ !empty($isMuxConfigured) ? 'true' : 'false' }};
        const PROMO_CHUNK_UPLOAD_URL = "{{ route('admin.courses.promo.chunk.upload', $course) }}";
        const PROMO_MUX_DIRECT_URL = "{{ route('admin.courses.promo.mux.direct_upload', $course) }}";
        const PROMO_MUX_STATUS_TEMPLATE = "{{ route('admin.courses.promo.mux.upload_status', [$course, ':uploadId']) }}";
        const PROMO_MUX_UPDATE_URL = "{{ route('admin.courses.promo.mux.update', $course) }}";
        const CHUNK_SIZE = 10 * 1024 * 1024; // 10MB binary slices for local upload
        const CSRF_TOKEN = "{{ csrf_token() }}";

        let promoCurrentFile = null;
        let isPromoUploading = false;
        let promoUploadStartTime = null;

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        async function fetchWithRetry(url, options, maxRetries = 3) {
            let lastError = null;
            for (let attempt = 1; attempt <= maxRetries; attempt++) {
                try {
                    const response = await fetch(url, options);
                    if (response.ok) {
                        return response;
                    }
                    const err = await response.json().catch(() => ({ message: `HTTP ${response.status}` }));
                    lastError = new Error(err.message || 'فشل رفع جزء من الملف');
                } catch (err) {
                    lastError = err;
                }

                if (attempt < maxRetries) {
                    await sleep(2000 * attempt);
                }
            }
            throw lastError;
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function formatSeconds(sec) {
            if (sec <= 0 || !isFinite(sec)) return '{{ __("لحظات...") }}';
            if (sec < 60) return Math.round(sec) + ' {{ __("ث") }}';
            const m = Math.floor(sec / 60);
            const s = Math.round(sec % 60);
            return `${m} {{ __("د") }} ${s} {{ __("ث") }}`;
        }

        function previewThumbnailFile(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('thumbnail-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function handlePromoFileDrop(e) {
            e.preventDefault();
            const dropzone = document.getElementById('promo-chunked-dropzone');
            if (dropzone) dropzone.classList.remove('border-[#012383]', 'bg-blue-50/40');
            if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                handlePromoFileSelect(e.dataTransfer.files);
            }
        }

        function handlePromoFileSelect(files) {
            if (!files || files.length === 0) return;
            promoCurrentFile = files[0];
            startPromoVideoUpload(promoCurrentFile);
        }

        function startPromoVideoUpload(file) {
            if (isPromoUploading) return;

            if (IS_MUX_CONFIGURED && typeof UpChunk !== 'undefined') {
                return startPromoMuxDirectUpload(file);
            }

            return startPromoLocalChunkedUpload(file);
        }

        /**
         * Direct Browser-to-MUX Upload using @mux/upchunk.
         */
        async function startPromoMuxDirectUpload(file) {
            isPromoUploading = true;
            promoUploadStartTime = Date.now();

            const progressContainer = document.getElementById('promo-upload-progress-container');
            const fileNameEl = document.getElementById('promo-progress-file-name');
            const fileSizeEl = document.getElementById('promo-progress-file-size');
            const statusBadge = document.getElementById('promo-progress-status-badge');
            const statusText = document.getElementById('promo-progress-status-text');
            const percentLabel = document.getElementById('promo-progress-percentage-label');
            const chunksLabel = document.getElementById('promo-progress-chunks-label');
            const barFill = document.getElementById('promo-progress-bar-fill');
            const speedEl = document.getElementById('promo-metric-speed');
            const etaEl = document.getElementById('promo-metric-eta');
            const chunksEl = document.getElementById('promo-metric-chunks');
            const uploadedEl = document.getElementById('promo-metric-uploaded');
            const processingBanner = document.getElementById('promo-processing-banner');
            const successBanner = document.getElementById('promo-success-banner');
            const submitBtn = document.getElementById('btn-submit-course');
            const submitText = document.getElementById('btn-submit-course-text');

            progressContainer.classList.remove('hidden');
            processingBanner.classList.add('hidden');
            successBanner.classList.remove('hidden');

            fileNameEl.textContent = file.name;
            fileNameEl.title = file.name;
            fileSizeEl.textContent = formatBytes(file.size);

            if (statusBadge) {
                statusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100/90 text-[#012383] dark:bg-blue-950/70 dark:text-blue-300 border border-blue-200 dark:border-blue-900 transition-all shadow-2xs';
            }
            if (statusText) statusText.textContent = '{{ __("جاري الاتصال بسحابة MUX...") }}';
            if (chunksLabel) chunksLabel.textContent = '{{ __("رفع سحابي مباشر (Zero Timeout)") }}';
            if (submitBtn) {
                submitBtn.disabled = true;
                if (submitText) submitText.textContent = '{{ __("جاري رفع الفيديو التعريفي...") }}';
            }

            try {
                const res = await fetch(PROMO_MUX_DIRECT_URL, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    throw new Error(err.message || 'فشل في إنشاء رابط الرفع لمنصة MUX.');
                }

                const data = await res.json();
                if (!data.success || !data.upload_url) {
                    throw new Error(data.message || 'رابط رفع MUX غير متوفر.');
                }

                const uploadUrl = data.upload_url;
                const uploadId = data.upload_id;

                if (statusText) statusText.textContent = '{{ __("جاري الرفع المباشر إلى MUX...") }}';

                const upload = UpChunk.createUpload({
                    endpoint: uploadUrl,
                    file: file,
                    chunkSize: 5120, // 5MB chunks
                    attempts: 5,
                    delayBeforeAttempt: 1,
                });

                upload.on('attempt', (e) => {
                    console.log(`[PROMO_UPLOAD MUX] UpChunk Attempting chunk #${e.detail?.chunkNumber ?? '?'}`);
                });

                upload.on('attemptFailure', (e) => {
                    console.warn('[PROMO_UPLOAD MUX] UpChunk Chunk attempt failed:', e.detail);
                    if (statusText) {
                        statusText.textContent = `جاري إعادة محاولة إرسال الجزء (${e.detail?.chunkNumber ?? ''})... المحاولات المتبقية: ${e.detail?.attemptsLeft ?? ''}`;
                    }
                });

                upload.on('chunkSuccess', (e) => {
                    console.log(`[PROMO_UPLOAD MUX] UpChunk Chunk #${e.detail?.chunkNumber ?? '?'} succeeded!`);
                    if (statusText) {
                        statusText.textContent = '{{ __("جاري الرفع المباشر إلى MUX...") }}';
                    }
                });

                upload.on('offline', () => {
                    console.warn('[PROMO_UPLOAD MUX] Network offline');
                    if (statusText) statusText.textContent = 'انقطع الاتصال بالإنترنت، بانتظار عودة الشبكة...';
                });

                upload.on('online', () => {
                    console.log('[PROMO_UPLOAD MUX] Network restored');
                    if (statusText) statusText.textContent = 'تم استعادة الاتصال، جاري استكمال الرفع...';
                });

                upload.on('progress', (e) => {
                    const percent = Math.min(100, Math.round(e.detail));
                    barFill.style.width = percent + '%';
                    percentLabel.textContent = percent + '%';

                    const uploadedBytes = (percent / 100) * file.size;
                    uploadedEl.textContent = `${formatBytes(uploadedBytes)} / ${formatBytes(file.size)}`;

                    const elapsedSec = (Date.now() - promoUploadStartTime) / 1000;
                    if (elapsedSec > 0) {
                        const bytesPerSec = uploadedBytes / elapsedSec;
                        speedEl.textContent = formatBytes(bytesPerSec) + '/s';
                        const remainingBytes = Math.max(0, file.size - uploadedBytes);
                        const etaSec = bytesPerSec > 0 ? (remainingBytes / bytesPerSec) : 0;
                        etaEl.textContent = formatSeconds(etaSec);
                    }

                    if (chunksEl) chunksEl.textContent = `${percent}%`;
                });

                upload.on('success', () => {
                    processingBanner.classList.remove('hidden');
                    if (statusBadge) {
                        statusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 dark:bg-amber-950/70 dark:text-amber-200 border border-amber-200 dark:border-amber-900 transition-all animate-pulse';
                    }
                    if (statusText) statusText.textContent = '{{ __("اكتمل الرفع (100%): جاري معالجة الفيديو على MUX...") }}';

                    const statusUrl = PROMO_MUX_STATUS_TEMPLATE.replace(':uploadId', uploadId);
                    let pollCount = 0;

                    const poller = setInterval(async () => {
                        pollCount++;
                        try {
                            const pollRes = await fetch(statusUrl, {
                                headers: { 'Accept': 'application/json' }
                            });
                            if (pollRes.ok) {
                                const pollData = await pollRes.json();
                                if (pollData.asset_id && pollData.playback_id) {
                                    clearInterval(poller);

                                    // Save MUX details to course on backend
                                    await fetch(PROMO_MUX_UPDATE_URL, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': CSRF_TOKEN,
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            mux_playback_id: pollData.playback_id,
                                            mux_asset_id: pollData.asset_id
                                        })
                                    }).catch(() => {});

                                    // Populate hidden form inputs
                                    document.getElementById('field-promo-video-source').value = 'mux';
                                    document.getElementById('field-mux-playback-id').value = pollData.playback_id;
                                    document.getElementById('field-mux-asset-id').value = pollData.asset_id;
                                    const promoInput = document.getElementById('promo-url-input');
                                    if (promoInput) promoInput.value = `https://stream.mux.com/${pollData.playback_id}.m3u8`;

                                    // Success UI
                                    processingBanner.classList.add('hidden');
                                    successBanner.classList.remove('hidden');
                                    const driverBadge = document.getElementById('promo-success-driver-badge');
                                    if (driverBadge) driverBadge.textContent = 'MUX Cloud Stream';

                                    if (statusBadge) {
                                        statusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900 transition-all';
                                    }
                                    if (statusText) statusText.textContent = '{{ __("تمت معالجة الفيديو بنجاح على MUX وهو جاهز للنشر!") }}';

                                    // Update preview player
                                    updatePromoPlayerWithMux(pollData.playback_id);

                                    if (submitBtn) {
                                        submitBtn.disabled = false;
                                        if (submitText) submitText.textContent = '{{ __("حفظ التعديلات ونشر الكورس") }}';
                                    }
                                    isPromoUploading = false;
                                    return;
                                }
                            }
                        } catch (err) {
                            console.warn('Poll error:', err);
                        }

                        if (pollCount > 45) {
                            clearInterval(poller);
                            document.getElementById('field-promo-video-source').value = 'mux';
                            processingBanner.classList.add('hidden');
                            successBanner.classList.remove('hidden');
                            if (statusText) statusText.textContent = '{{ __("تم رفع الفيديو بنجاح إلى MUX (جاري إكمال المعالجة في السحابة)") }}';
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                if (submitText) submitText.textContent = '{{ __("حفظ التعديلات ونشر الكورس") }}';
                            }
                            isPromoUploading = false;
                        }
                    }, 2000);
                });

                upload.on('error', (err) => {
                    console.error('[PROMO_UPLOAD MUX] UpChunk upload error:', err);
                    console.warn('[PROMO_UPLOAD MUX] Triggering automatic fallback to local chunked upload...');
                    if (statusText) {
                        statusText.textContent = 'تعذر استكمال الرفع المباشر إلى MUX (' + (err.detail?.message || 'انقطاع الاتصال') + ')، جاري التحويل التلقائي للرفع عبر خادم المنصة...';
                    }
                    if (statusBadge) {
                        statusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200';
                    }
                    isPromoUploading = false;
                    return startLocalPromoChunkedUpload(file);
                });
                        submitBtn.disabled = false;
                        if (submitText) submitText.textContent = '{{ __("حفظ التعديلات ونشر الكورس") }}';
                    }
                });

            } catch (err) {
                console.warn('MUX direct upload fallback to local chunked upload:', err);
                return startPromoLocalChunkedUpload(file);
            }
        }

        /**
         * Local server chunked upload
         */
        async function startPromoLocalChunkedUpload(file) {
            isPromoUploading = true;

            const uploadId = 'promo_' + Date.now() + '_' + Math.random().toString(36).substring(2, 9);
            const totalChunks = Math.ceil(file.size / CHUNK_SIZE);

            const progressContainer = document.getElementById('promo-upload-progress-container');
            const fileNameEl = document.getElementById('promo-progress-file-name');
            const fileSizeEl = document.getElementById('promo-progress-file-size');
            const statusBadge = document.getElementById('promo-progress-status-badge');
            const statusText = document.getElementById('promo-progress-status-text');
            const percentLabel = document.getElementById('promo-progress-percentage-label');
            const chunksLabel = document.getElementById('promo-progress-chunks-label');
            const barFill = document.getElementById('promo-progress-bar-fill');
            const speedEl = document.getElementById('promo-metric-speed');
            const etaEl = document.getElementById('promo-metric-eta');
            const chunksEl = document.getElementById('promo-metric-chunks');
            const uploadedEl = document.getElementById('promo-metric-uploaded');
            const processingBanner = document.getElementById('promo-processing-banner');
            const successBanner = document.getElementById('promo-success-banner');
            const submitBtn = document.getElementById('btn-submit-course');
            const submitText = document.getElementById('btn-submit-course-text');

            progressContainer.classList.remove('hidden');
            processingBanner.classList.add('hidden');
            successBanner.classList.add('hidden');

            fileNameEl.textContent = file.name;
            fileNameEl.title = file.name;
            fileSizeEl.textContent = formatBytes(file.size);
            
            if (statusBadge) {
                statusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100/90 text-[#012383] dark:bg-blue-950/70 dark:text-blue-300 border border-blue-200 dark:border-blue-900 transition-all shadow-2xs';
            }
            if (statusText) statusText.textContent = '{{ __("جاري الرفع...") }}';

            if (submitBtn) {
                submitBtn.disabled = true;
                if (submitText) submitText.textContent = '{{ __("جاري رفع الفيديو التعريفي...") }}';
            }
            promoUploadStartTime = Date.now();

            let uploadedBytes = 0;

            for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
                const start = chunkIndex * CHUNK_SIZE;
                const end = Math.min(start + CHUNK_SIZE, file.size);
                const chunkBlob = file.slice(start, end);

                const formData = new FormData();
                formData.append('upload_id', uploadId);
                formData.append('chunk_index', chunkIndex);
                formData.append('total_chunks', totalChunks);
                formData.append('original_filename', file.name);
                formData.append('total_size', file.size);
                formData.append('file_chunk', chunkBlob);

                if (chunkIndex === totalChunks - 1) {
                    processingBanner.classList.remove('hidden');
                    if (statusBadge) {
                        statusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 dark:bg-amber-950/70 dark:text-amber-200 border border-amber-200 dark:border-amber-900 transition-all animate-pulse';
                    }
                    if (statusText) statusText.textContent = '{{ __("جاري تجميع وحفظ الفيديو على السيرفر...") }}';
                }

                try {
                    const response = await fetchWithRetry(PROMO_CHUNK_UPLOAD_URL, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        },
                        body: formData
                    }, 3);

                    if (!response.ok) {
                        const err = await response.json().catch(() => ({ message: 'Server error' }));
                        throw new Error(err.message || 'فشل رفع جزء من الملف');
                    }

                    const result = await response.json();

                    uploadedBytes += (end - start);
                    const percent = Math.min(100, Math.round((uploadedBytes / file.size) * 100));
                    const partsPercent = Math.min(100, Math.round(((chunkIndex + 1) / totalChunks) * 100));

                    percentLabel.textContent = percent + '%';
                    barFill.style.width = percent + '%';
                    chunksLabel.textContent = `{{ __("الجزء") }} ${chunkIndex + 1} {{ __("من") }} ${totalChunks} (${partsPercent}%)`;
                    chunksEl.textContent = `${chunkIndex + 1} / ${totalChunks}`;
                    uploadedEl.textContent = `${formatBytes(uploadedBytes)} / ${formatBytes(file.size)}`;

                    const elapsedSec = (Date.now() - promoUploadStartTime) / 1000;
                    if (elapsedSec > 0) {
                        const bytesPerSec = uploadedBytes / elapsedSec;
                        speedEl.textContent = formatBytes(bytesPerSec) + '/s';
                        const remainingBytes = Math.max(0, file.size - uploadedBytes);
                        const etaSec = bytesPerSec > 0 ? (remainingBytes / bytesPerSec) : 0;
                        etaEl.textContent = formatSeconds(etaSec);
                    }

                    if (chunkIndex === totalChunks - 1 && result.status === 'completed') {
                        if (statusBadge) {
                            statusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900 transition-all';
                        }
                        if (statusText) statusText.textContent = '{{ __("اكتمل الرفع والمعالجة بنجاح") }}';
                        processingBanner.classList.add('hidden');
                        successBanner.classList.remove('hidden');

                        let playUrl = result.promo_video_url || '';
                        if (playUrl.includes('/storage/')) {
                            playUrl = '/storage/' + playUrl.split('/storage/')[1];
                        }

                        // Populate hidden form fields so saving the course details updates normally
                        document.getElementById('field-promo-video-source').value = result.promo_video_source || 'local';
                        if (playUrl) {
                            const promoInput = document.getElementById('promo-url-input');
                            if (promoInput) promoInput.value = playUrl;
                        }
                        document.getElementById('field-mux-playback-id').value = result.mux_playback_id || '';
                        document.getElementById('field-mux-asset-id').value = result.mux_asset_id || '';

                        const driverBadge = document.getElementById('promo-success-driver-badge');
                        if (driverBadge) {
                            driverBadge.textContent = result.driver_used === 'mux' ? 'منصة MUX' : '{{ __("سيرفر المنصة المحلي") }}';
                        }

                        // Update preview player
                        if (result.mux_playback_id) {
                            updatePromoPlayerWithMux(result.mux_playback_id, result.playback_token);
                        } else if (playUrl) {
                            const playerContainer = document.getElementById('promo-preview-player');
                            playerContainer.innerHTML = '';
                            const video = document.createElement('video');
                            video.controls = true;
                            video.className = 'w-full h-full';
                            video.src = playUrl;
                            playerContainer.appendChild(video);
                        }

                        if (submitBtn) {
                            submitBtn.disabled = false;
                            if (submitText) submitText.textContent = '{{ __("حفظ التعديلات ونشر الكورس") }}';
                        }
                        isPromoUploading = false;
                        return;
                    }
                } catch (err) {
                    console.error(err);
                    if (statusBadge) {
                        statusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/70 dark:text-rose-300 border border-rose-200 transition-all';
                    }
                    if (statusText) statusText.textContent = '{{ __("خطأ في الرفع") }}';
                    alert('حدث خطأ أثناء رفع ملف الفيديو: ' + err.message);
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        if (submitText) submitText.textContent = '{{ __("حفظ التعديلات ونشر الكورس") }}';
                    }
                    isPromoUploading = false;
                    return;
                }
            }
        }

        function updatePromoPlayerWithMux(playbackId, token = null) {
            const playerContainer = document.getElementById('promo-preview-player');
            playerContainer.innerHTML = '';

            if (window.customElements && customElements.get('mux-player')) {
                const muxPlayer = document.createElement('mux-player');
                muxPlayer.setAttribute('playback-id', playbackId);
                muxPlayer.setAttribute('stream-type', 'on-demand');
                muxPlayer.setAttribute('controls', 'true');
                if (token) muxPlayer.setAttribute('playback-token', token);
                muxPlayer.className = 'w-full h-full';
                playerContainer.appendChild(muxPlayer);
            } else {
                const video = document.createElement('video');
                video.controls = true;
                video.className = 'w-full h-full';
                const tokenQuery = token ? `?token=${token}` : '';
                const hlsSrc = `https://stream.mux.com/${playbackId}.m3u8${tokenQuery}`;
                if (window.Hls && Hls.isSupported()) {
                    const hls = new Hls();
                    hls.loadSource(hlsSrc);
                    hls.attachMedia(video);
                } else {
                    video.src = hlsSrc;
                }
                playerContainer.appendChild(video);
            }
        }

        function reloadPromoPreview() {
            const urlInput = document.getElementById('promo-url-input').value.trim();
            const playerContainer = document.getElementById('promo-preview-player');

            if (!urlInput) {
                playerContainer.innerHTML = '<div class="text-center p-6 text-slate-500 text-xs">يرجى كتابة رابط الفيديو للمعاينة.</div>';
                return;
            }

            playerContainer.innerHTML = '';

            // 1. Mux
            if (urlInput.includes('stream.mux.com') || /^[a-zA-Z0-9]{16,64}$/.test(urlInput)) {
                let playbackId = urlInput;
                const match = urlInput.match(/stream\.mux\.com\/([a-zA-Z0-9_\-]+)/);
                if (match) playbackId = match[1];

                updatePromoPlayerWithMux(playbackId);
            }
            // 2. YouTube
            else if (urlInput.includes('youtube.com') || urlInput.includes('youtu.be')) {
                let ytId = '';
                const m = urlInput.match(/(?:embed\/|watch\?v=|youtu\.be\/)([a-zA-Z0-9_\-]+)/);
                if (m) ytId = m[1];
                playerContainer.innerHTML = `<iframe src="https://www.youtube.com/embed/${ytId}" class="w-full h-full border-0" allowfullscreen></iframe>`;
            }
            // 3. Vimeo
            else if (urlInput.includes('vimeo.com')) {
                let vmId = '';
                const m = urlInput.match(/vimeo\.com\/(?:video\/)?([0-9]+)/);
                if (m) vmId = m[1];
                playerContainer.innerHTML = `<iframe src="https://player.vimeo.com/video/${vmId}" class="w-full h-full border-0" allowfullscreen></iframe>`;
            }
            // 4. MP4 / Direct link
            else {
                const video = document.createElement('video');
                video.controls = true;
                video.className = 'w-full h-full';
                video.src = urlInput;
                playerContainer.appendChild(video);
            }
        }
    </script>
</x-admin-layout::admin>
