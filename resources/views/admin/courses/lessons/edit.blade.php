<x-admin-layout::admin
    :title="__('تعديل الدرس: :title', ['title' => $video->title_ar])"
    :description="__('تحديث بيانات الدرس، نسبة المشاهدة المطلوبة، واستبدال ملف الفيديو عبر التجزئة المباشرة أو MUX')"
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
        id="form-edit-lesson" 
        action="{{ route('admin.courses.videos.update', [$course, $video]) }}" 
        method="POST" 
        enctype="multipart/form-data" 
        class="flex flex-col gap-6"
        x-data="{ uploadTab: '{{ old('upload_tab', 'keep') }}' }"
    >
        @csrf
        @method('PUT')
        
        {{-- Hidden fields for Chunked Video Replacement --}}
        <input type="hidden" name="upload_tab" id="field-upload-tab" :value="uploadTab" />
        <input type="hidden" name="video_source" id="field-video-source" value="{{ old('video_source', $video->video_source) }}" />
        <input type="hidden" name="mux_playback_id" id="field-mux-playback-id" value="{{ old('mux_playback_id', $video->mux_playback_id) }}" />
        <input type="hidden" name="mux_asset_id" id="field-mux-asset-id" value="{{ old('mux_asset_id', $video->mux_asset_id) }}" />

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
                            {{ __('بيانات الدرس والمستوى التدريبي') }}
                        </h3>
                        <p class="text-xs text-slate-400">
                            {{ __('تعديل المستوى التابع له، عنوان الدرس بالعربية، ومدة ونسبة الإكمال') }}
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
                        {{ __('المستوى التدريبي التابع له:') }} <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="level_id" 
                        required 
                        class="w-full h-11 px-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50 cursor-pointer"
                    >
                        @foreach($course->levels as $level)
                            <option 
                                value="{{ $level->id }}" 
                                {{ (old('level_id', $video->level_id) == $level->id) ? 'selected' : '' }}
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
                        value="{{ old('title_ar', $video->title_ar) }}" 
                        required 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Duration in Minutes --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                        {{ __('المدة التقديرية (بالدقائق):') }} <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="duration_minutes" 
                        id="input-duration-minutes" 
                        min="1" 
                        max="600" 
                        value="{{ old('duration_minutes', $video->duration_minutes) }}" 
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
                            value="{{ old('min_completion_percentage', $video->min_completion_percentage) }}" 
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
                        {{ old('is_free_preview', $video->is_free_preview) ? 'checked' : '' }} 
                        class="size-4.5 rounded border-slate-300 text-[#012383] focus:ring-[#012383] cursor-pointer" 
                    />
                    <div>
                        <span class="text-xs font-bold text-[#012383] dark:text-blue-300 block">{{ __('إتاحة هذا الدرس كمعاينة مجانية للزوار') }}</span>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('يستطيع الطلاب والزوار مشاهدة هذا الدرس قبل دفع رسوم الاشتراك') }}</span>
                    </div>
                </label>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- CARD 2: Current Video & Replacement Engine            --}}
        {{-- ===================================================== --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-5">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="size-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 font-bold flex items-center justify-center text-sm shadow-2xs">
                        ٢
                    </span>
                    <div>
                        <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white leading-tight">
                            {{ __('فيديو الدرس واستبدال المحتوى') }}
                        </h3>
                        <p class="text-xs text-slate-400">
                            {{ __('معاينة الفيديو الحالي أو رفع فيديو بديل بنظام التجزئة وشريط التقدم المباشر') }}
                        </p>
                    </div>
                </div>

                {{-- Current Video Source Badge --}}
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold text-slate-400">{{ __('المصدر الحالي:') }}</span>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold border flex items-center gap-1.5 {{ $video->video_source === 'mux' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : ($video->video_source === 'bunny' ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800' : 'bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-300 border-blue-200 dark:border-slate-700') }}">
                        <span class="size-2 rounded-full {{ $video->video_source === 'mux' ? 'bg-emerald-500' : ($video->video_source === 'bunny' ? 'bg-amber-500' : 'bg-blue-500') }}"></span>
                        <span>{{ $video->video_source === 'mux' ? __('بث MUX السحابي') : ($video->video_source === 'bunny' ? __('سحابة Bunny Stream') : ($video->video_source === 'local' ? __('سيرفر المنصة المحلي') : __('رابط مباشر'))) }}</span>
                    </span>
                </div>
            </div>

            {{-- Mode Switcher Pills --}}
            <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-slate-100 dark:bg-slate-800/80 w-fit border border-slate-200/80 dark:border-slate-700 flex-wrap">
                <button 
                    type="button" 
                    @click="uploadTab = 'keep'" 
                    :class="uploadTab === 'keep' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383]'" 
                    class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>{{ __('الإبقاء على الفيديو الحالي ومعاينته') }}</span>
                </button>

                <button 
                    type="button" 
                    @click="uploadTab = 'chunked'" 
                    :class="uploadTab === 'chunked' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383]'" 
                    class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span>{{ __('استبدال الفيديو برفع ملف جديد بالتجزئة') }}</span>
                </button>

                <button 
                    type="button" 
                    @click="uploadTab = 'link'" 
                    :class="uploadTab === 'link' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383]'" 
                    class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <span>{{ __('تعديل الرابط أو معرّف MUX') }}</span>
                </button>
            </div>

            {{-- TAB 1: Current Video Preview --}}
            <div x-show="uploadTab === 'keep'" class="space-y-4">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-[#DEE8F7] dark:border-slate-700 flex flex-col md:flex-row items-center gap-4">
                    <div class="w-full md:w-80 aspect-video rounded-xl overflow-hidden bg-black shadow-inner shrink-0 relative">
                        @if($video->video_source === 'mux' && $video->mux_playback_id)
                            <mux-player
                                playback-id="{{ $video->mux_playback_id }}"
                                metadata-video-title="{{ $video->title_ar }}"
                                stream-type="on-demand"
                                class="size-full"
                            ></mux-player>
                        @elseif($video->video_source === 'bunny' || !empty($video->bunny_video_id))
                            <iframe
                                src="{{ $video->effective_playback_url }}"
                                loading="lazy"
                                class="size-full border-0"
                                allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                allowfullscreen="true"
                            ></iframe>
                        @elseif($video->effective_playback_url)
                            <video src="{{ $video->effective_playback_url }}" controls class="size-full"></video>
                        @else
                            <div class="size-full flex items-center justify-center text-slate-500 text-xs font-bold">
                                {{ __('لا يوجد ملف فيديو مرفوع حالياً') }}
                            </div>
                        @endif
                    </div>

                    <div class="space-y-2 flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700 dark:text-slate-200">
                            {{ __('بيانات البث الحالية:') }}
                        </div>
                        @if($video->video_source === 'mux' && $video->mux_playback_id)
                            <p class="text-xs font-mono text-slate-500 dark:text-slate-400 break-all">
                                <span class="font-bold text-[#012383] dark:text-blue-300">MUX Playback ID:</span> {{ $video->mux_playback_id }}
                            </p>
                        @endif
                        @if($video->video_source === 'bunny' && !empty($video->bunny_video_id))
                            <p class="text-xs font-mono text-slate-500 dark:text-slate-400 break-all">
                                <span class="font-bold text-[#012383] dark:text-blue-300">Bunny Video ID:</span> {{ $video->bunny_video_id }}
                            </p>
                        @endif
                        @if($video->effective_playback_url)
                            <p class="text-xs font-mono text-slate-500 dark:text-slate-400 break-all truncate">
                                <span class="font-bold text-[#012383] dark:text-blue-300">URL:</span> {{ $video->effective_playback_url }}
                            </p>
                        @endif
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ __('الفيديو يعمل وجاهز للعرض. إذا كنت ترغب في استبداله، اختر تبويب "استبدال الفيديو برفع ملف جديد" بالأعلى.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- TAB 2: Chunked Video Uploader --}}
            <div x-show="uploadTab === 'chunked'" class="space-y-4" style="display: none;">
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
                            {{ __('اضغط لاختيار ملف الفيديو الجديد أو اسحبه هنا') }}
                        </h4>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ __('سيتم رفع الفيديو تلقائياً بنظام التجزئة وشريط التقدم، ثم استبدال الفيديو القديم فور الحفظ') }}
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

                {{-- Live Progress Container --}}
                <div id="upload-progress-container" class="hidden p-5 sm:p-6 rounded-2xl sm:rounded-3xl bg-slate-50/90 dark:bg-slate-800/80 border border-[#DEE8F7] dark:border-slate-700/80 shadow-xs space-y-5 transition-all">
                    
                    {{-- File Info Header --}}
                    <div class="flex items-center justify-between flex-wrap gap-3 pb-3 border-b border-slate-200/70 dark:border-slate-700/60">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="size-11 rounded-2xl bg-gradient-to-tr from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center shrink-0 shadow-xs ring-4 ring-blue-500/10">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <span id="progress-file-name" class="font-bold text-xs sm:text-sm text-slate-800 dark:text-white truncate block max-w-xs sm:max-w-md"></span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span id="progress-file-size" dir="ltr" class="text-[11px] font-mono font-medium text-slate-400"></span>
                                    <span class="size-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ __('رفع مجزأ ذكي (Smart Chunks)') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Dynamic Status Badge --}}
                        <div id="progress-status-badge" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-100/90 text-[#012383] dark:bg-blue-950/70 dark:text-blue-300 border border-blue-200 dark:border-blue-900 transition-all shadow-2xs">
                            <span class="relative flex size-2 shrink-0">
                                <span class="animate-ping absolute inline-flex size-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full size-2 bg-blue-600"></span>
                            </span>
                            <span id="progress-status-text">{{ __('جاري الرفع...') }}</span>
                        </div>
                    </div>

                    {{-- Progress Bar & Parts Percentage Section --}}
                    <div class="space-y-2.5">
                        <div class="flex items-end justify-between gap-3">
                            {{-- Parts Counter Pill & Detail --}}
                            <div class="flex items-center gap-2">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 shadow-2xs">
                                    <svg class="size-3.5 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <span id="progress-chunks-label">{{ __('جاري التجهيز...') }}</span>
                                </div>
                            </div>

                            {{-- Large High-Tech Percentage --}}
                            <div class="flex items-baseline gap-1 text-left">
                                <span id="progress-percentage-label" class="text-2xl sm:text-3xl font-black text-[#012383] dark:text-blue-400 font-mono tracking-tight leading-none">0%</span>
                            </div>
                        </div>

                        {{-- The Enhanced Progress Bar Track & Shimmering Fill --}}
                        <div class="w-full h-3.5 sm:h-4 rounded-full bg-slate-200/80 dark:bg-slate-700/80 p-0.5 overflow-hidden shadow-inner relative">
                            <div id="progress-bar-fill" class="h-full rounded-full bg-gradient-to-r from-[#012383] via-blue-600 to-teal-400 dark:from-blue-600 dark:via-indigo-500 dark:to-cyan-400 transition-all duration-300 ease-out relative overflow-hidden flex items-center justify-end w-0 shadow-xs">
                                {{-- Shimmer effect overlay --}}
                                <div class="absolute inset-0 upload-shimmer-stripes opacity-30"></div>
                                {{-- Leading glow edge --}}
                                <div class="relative size-2 rounded-full bg-white shadow-xs mx-1 shrink-0 opacity-80"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Metrics Grid (Speed, ETA, Uploaded Chunks & Parts Percentage, Uploaded Size) --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1 text-center">
                        {{-- Metric 1: Instant Speed --}}
                        <div class="p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700/80 shadow-2xs">
                            <div class="flex items-center justify-center gap-1.5 text-slate-400 mb-1">
                                <svg class="size-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span class="text-[11px] font-bold">{{ __('السرعة اللحظية') }}</span>
                            </div>
                            <span id="metric-speed" dir="ltr" class="font-black text-xs sm:text-sm text-slate-700 dark:text-slate-200 block font-mono">-</span>
                        </div>

                        {{-- Metric 2: Estimated Time Remaining (ETA) --}}
                        <div class="p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700/80 shadow-2xs">
                            <div class="flex items-center justify-center gap-1.5 text-slate-400 mb-1">
                                <svg class="size-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-[11px] font-bold">{{ __('الوقت المتبقي') }}</span>
                            </div>
                            <span id="metric-eta" class="font-black text-xs sm:text-sm text-slate-700 dark:text-slate-200 block font-mono">-</span>
                        </div>

                        {{-- Metric 3: Uploaded Parts & Parts Percentage --}}
                        <div class="p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700/80 shadow-2xs">
                            <div class="flex items-center justify-center gap-1.5 text-slate-400 mb-1">
                                <svg class="size-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <span class="text-[11px] font-bold">{{ __('الأجزاء المرفوعة') }}</span>
                            </div>
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                <span id="metric-chunks" dir="ltr" class="font-black text-xs sm:text-sm text-[#012383] dark:text-blue-300 font-mono">0 / 0</span>
                                <span id="metric-parts-pct" class="text-[10px] font-black px-1.5 py-0.5 rounded-md bg-blue-50 text-[#012383] dark:bg-blue-950/70 dark:text-blue-300 font-mono border border-blue-200/60 dark:border-blue-800/60">0%</span>
                            </div>
                        </div>

                        {{-- Metric 4: Uploaded Size --}}
                        <div class="p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700/80 shadow-2xs">
                            <div class="flex items-center justify-center gap-1.5 text-slate-400 mb-1">
                                <svg class="size-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7z"/></svg>
                                <span class="text-[11px] font-bold">{{ __('الحجم المرفوع') }}</span>
                            </div>
                            <span id="metric-uploaded" dir="ltr" class="font-black text-xs sm:text-sm text-emerald-600 dark:text-emerald-400 block font-mono">-</span>
                        </div>
                    </div>

                    {{-- Processing State Banner --}}
                    <div id="processing-banner" class="hidden p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 flex items-center gap-3">
                        <svg class="size-5 text-amber-600 animate-spin shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <div class="text-xs">
                            <span class="font-bold text-amber-900 dark:text-amber-200 block">{{ __('اكتمل رفع الأجزاء (100%): جاري معالجة الفيديو الجديد...') }}</span>
                            <span class="text-amber-700 dark:text-amber-300 text-[11px]">{{ __('يقوم السيرفر بتجميع الأجزاء وضغط الفيديو أو رفعه لمنصة البث.') }}</span>
                        </div>
                    </div>

                    {{-- Success & Ready Banner --}}
                    <div id="success-banner" class="hidden p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 space-y-3">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2 text-emerald-800 dark:text-emerald-300 text-xs font-bold">
                                <svg class="size-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span id="success-message-text">{{ __('تم رفع وتجهيز الفيديو الجديد بنجاح على السيرفر وهو جاهز للعرض!') }}</span>
                            </div>
                            <span id="success-driver-badge" class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-200 text-emerald-900"></span>
                        </div>

                        <div class="rounded-xl overflow-hidden bg-black aspect-video max-w-lg mx-auto shadow-inner relative">
                            <video id="preview-player" controls class="size-full"></video>
                        </div>

                        <div class="p-3 bg-emerald-100/70 dark:bg-emerald-900/40 rounded-xl border border-emerald-300 dark:border-emerald-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="text-xs text-emerald-900 dark:text-emerald-200">
                                <p class="font-bold">{{ __('تم تحديث وحفظ الفيديو الجديد على الدرس في السيرفر بنجاح.') }}</p>
                                <p class="text-[11px] text-emerald-800/80 dark:text-emerald-300/80">{{ __('اضغط على الزر لتأكيد واعتماد التعديلات وحفظ باقي بيانات الدرس فوراً.') }}</p>
                            </div>
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs flex items-center gap-1.5 shadow-md cursor-pointer transition-all shrink-0"
                            >
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ __('حفظ وتأكيد تعديلات الدرس 💾') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 3: Direct Link or Mux Playback ID --}}
            <div x-show="uploadTab === 'link'" class="space-y-4" style="display: none;">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                        {{ __('رابط الفيديو المباشر أو معرّف MUX:') }}
                    </label>
                    <input 
                        type="text" 
                        name="video_url" 
                        id="input-direct-video-url" 
                        value="{{ old('video_url', $video->effective_playback_url) }}" 
                        placeholder="{{ __('https://stream.mux.com/xxxxx.m3u8 أو https://youtu.be/xxxxx') }}" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                    <p class="text-[11px] text-slate-400 mt-1">
                        {{ __('يدعم النظام التعرف التلقائي على روابط MUX و YouTube و Vimeo والروابط المباشرة (MP4/HLS).') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- CARD 3: Attached Clinical Task Details                --}}
        {{-- ===================================================== --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-4">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="size-8 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 font-bold flex items-center justify-center text-sm shadow-2xs">
                        ٣
                    </span>
                    <div>
                        <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white leading-tight">
                            {{ __('المهمة السريرية الملحقة بالدرس') }}
                        </h3>
                        <p class="text-xs text-slate-400">
                            {{ __('تفتح المهمة تلقائياً للمتدرب بعد تحقيق نسبة إكمال المشاهدة المطلوبة') }}
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                    {{ __('عنوان المهمة السريرية بالعربية:') }}
                </label>
                <input 
                    type="text" 
                    name="task_title_ar" 
                    value="{{ old('task_title_ar', $video->clinicalTask?->title_ar) }}" 
                    placeholder="{{ __('مثال: تسجيل صوتي عملي لتقييم مخارج الحروف اللثوية') }}" 
                    class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                />
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                    {{ __('تعليمات وإرشادات المهمة للطلاب:') }}
                </label>
                <textarea 
                    name="task_instructions_ar" 
                    rows="3" 
                    class="w-full p-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] resize-none bg-slate-50/50 dark:bg-slate-800/50"
                >{{ old('task_instructions_ar', $video->clinicalTask?->instructions_ar) }}</textarea>
            </div>
        </div>

        {{-- Form Actions Bar --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a 
                href="{{ route('admin.courses.show', $course) }}" 
                class="px-5 h-11 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center transition-all cursor-pointer"
            >
                {{ __('إلغاء والعودة') }}
            </a>

            <button 
                type="submit" 
                id="btn-submit-lesson" 
                class="px-7 h-11 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-black text-xs sm:text-sm shadow-md hover:shadow-lg active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ __('حفظ وتحديث بيانات الدرس') }}</span>
            </button>
        </div>
    </form>

    {{-- Official MUX Player Web Component --}}
    <script src="https://cdn.jsdelivr.net/npm/@mux/mux-player"></script>

    {{-- Chunked Upload & Processing Client Engine --}}
    <script>
        const CHUNK_SIZE = 10 * 1024 * 1024; // 10MB binary slices for high network throughput
        const CHUNK_UPLOAD_URL = "{{ route('admin.courses.videos.chunk.upload', $course) }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";

        let currentFile = null;
        let isUploading = false;
        let uploadStartTime = null;

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

        function handleFileDrop(e) {
            e.preventDefault();
            const dropzone = document.getElementById('chunked-dropzone');
            dropzone.classList.remove('border-[#012383]', 'bg-blue-50/40');
            if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                handleFileSelect(e.dataTransfer.files);
            }
        }

        function handleFileSelect(files) {
            if (!files || files.length === 0) return;
            currentFile = files[0];
            startChunkedUpload(currentFile);
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

        async function startChunkedUpload(file) {
            if (isUploading) return;
            isUploading = true;

            const uploadId = 'up_' + Date.now() + '_' + Math.random().toString(36).substring(2, 9);
            const totalChunks = Math.ceil(file.size / CHUNK_SIZE);

            // UI Elements
            const progressContainer = document.getElementById('upload-progress-container');
            const dropzone = document.getElementById('chunked-dropzone');
            const fileNameEl = document.getElementById('progress-file-name');
            const fileSizeEl = document.getElementById('progress-file-size');
            const statusBadge = document.getElementById('progress-status-badge');
            const statusText = document.getElementById('progress-status-text');
            const percentLabel = document.getElementById('progress-percentage-label');
            const chunksLabel = document.getElementById('progress-chunks-label');
            const barFill = document.getElementById('progress-bar-fill');
            const speedEl = document.getElementById('metric-speed');
            const etaEl = document.getElementById('metric-eta');
            const chunksEl = document.getElementById('metric-chunks');
            const partsPctEl = document.getElementById('metric-parts-pct');
            const uploadedEl = document.getElementById('metric-uploaded');
            const processingBanner = document.getElementById('processing-banner');
            const successBanner = document.getElementById('success-banner');
            const submitBtn = document.getElementById('btn-submit-lesson');

            progressContainer.classList.remove('hidden');
            processingBanner.classList.add('hidden');
            successBanner.classList.add('hidden');

            fileNameEl.textContent = file.name;
            fileNameEl.title = file.name;
            fileSizeEl.textContent = formatBytes(file.size);
            
            if (statusBadge) {
                statusBadge.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-100/90 text-[#012383] dark:bg-blue-950/70 dark:text-blue-300 border border-blue-200 dark:border-blue-900 transition-all shadow-2xs';
            }
            if (statusText) statusText.textContent = '{{ __("جاري الرفع...") }}';

            if (submitBtn) submitBtn.disabled = true;
            uploadStartTime = Date.now();

            let uploadedBytes = 0;

            for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
                const start = chunkIndex * CHUNK_SIZE;
                const end = Math.min(start + CHUNK_SIZE, file.size);
                const chunkBlob = file.slice(start, end);

                const formData = new FormData();
                formData.append('upload_id', uploadId);
                formData.append('video_id', '{{ $video->id }}');
                formData.append('chunk_index', chunkIndex);
                formData.append('total_chunks', totalChunks);
                formData.append('original_filename', file.name);
                formData.append('total_size', file.size);
                formData.append('file_chunk', chunkBlob);

                // If last chunk, show processing state banner right before sending
                if (chunkIndex === totalChunks - 1) {
                    processingBanner.classList.remove('hidden');
                    if (statusBadge) {
                        statusBadge.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 dark:bg-amber-950/70 dark:text-amber-200 border border-amber-200 dark:border-amber-900 transition-all animate-pulse';
                    }
                    if (statusText) statusText.textContent = '{{ __("جاري تجميع ومعالجة الفيديو...") }}';
                }

                try {
                    const response = await fetchWithRetry(CHUNK_UPLOAD_URL, {
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

                    // Metrics calculation
                    const elapsedSec = (Date.now() - uploadStartTime) / 1000;
                    const bytesPerSec = elapsedSec > 0 ? (uploadedBytes / elapsedSec) : 0;
                    const remainingBytes = Math.max(0, file.size - uploadedBytes);
                    const etaSec = bytesPerSec > 0 ? (remainingBytes / bytesPerSec) : 0;

                    // Update UI
                    percentLabel.textContent = percent + '%';
                    barFill.style.width = percent + '%';
                    chunksLabel.textContent = `{{ __("الجزء") }} ${chunkIndex + 1} {{ __("من") }} ${totalChunks} (${partsPercent}%)`;
                    chunksEl.textContent = `${chunkIndex + 1} / ${totalChunks}`;
                    if (partsPctEl) partsPctEl.textContent = partsPercent + '%';
                    uploadedEl.textContent = `${formatBytes(uploadedBytes)} / ${formatBytes(file.size)}`;
                    speedEl.textContent = formatBytes(bytesPerSec) + '/s';
                    etaEl.textContent = formatSeconds(etaSec);

                    // Final chunk finished -> Processing state
                    if (chunkIndex === totalChunks - 1 && result.status === 'completed') {
                        if (statusBadge) {
                            statusBadge.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900 transition-all';
                        }
                        if (statusText) statusText.textContent = '{{ __("اكتمل الرفع والمعالجة بنجاح") }}';
                        processingBanner.classList.add('hidden');
                        successBanner.classList.remove('hidden');

                        // Normalize relative storage URL
                        let playUrl = result.video_url || '';
                        if (playUrl.includes('/storage/')) {
                            playUrl = '/storage/' + playUrl.split('/storage/')[1];
                        }

                        // Populate hidden form inputs
                        const uploadTabField = document.getElementById('field-upload-tab');
                        if (uploadTabField) uploadTabField.value = 'chunked';
                        document.getElementById('field-video-source').value = result.video_source || 'local';
                        if (playUrl) {
                            document.getElementById('input-direct-video-url').value = playUrl;
                        }
                        document.getElementById('field-mux-playback-id').value = result.mux_playback_id || '';
                        document.getElementById('field-mux-asset-id').value = result.mux_asset_id || '';

                        // Update driver badge
                        const driverBadge = document.getElementById('success-driver-badge');
                        driverBadge.textContent = result.driver_used === 'mux' ? 'منصة MUX' : '{{ __("سيرفر المنصة المحلي") }}';

                        // Set preview player
                        const player = document.getElementById('preview-player');
                        if (playUrl) {
                            player.src = playUrl;
                            player.load();
                        }

                        if (submitBtn) submitBtn.disabled = false;
                        isUploading = false;
                        return;
                    } else if (chunkIndex === totalChunks - 1) {
                        // Merging on server
                        processingBanner.classList.remove('hidden');
                        if (statusBadge) {
                            statusBadge.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 dark:bg-amber-950/70 dark:text-amber-200 border border-amber-200 dark:border-amber-900 transition-all animate-pulse';
                        }
                        if (statusText) statusText.textContent = '{{ __("جاري تجميع ومعالجة الفيديو...") }}';
                    }

                } catch (err) {
                    console.error(err);
                    if (statusBadge) {
                        statusBadge.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/70 dark:text-rose-300 border border-rose-200 transition-all';
                    }
                    if (statusText) statusText.textContent = '{{ __("خطأ في الرفع") }}';
                    alert('حدث خطأ أثناء رفع ملف الفيديو: ' + err.message);
                    if (submitBtn) submitBtn.disabled = false;
                    isUploading = false;
                    return;
                }
            }
        }
    </script>
</x-admin-layout::admin>
