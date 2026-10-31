<x-admin-layout::admin
    :title="__('إدارة محتوى الكورس والمستويات')"
    :description="__('تحكم كامل في المستويات، رفع الدروس، ربط المهام، وتعديل السعر ديناميكياً')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-5 sm:gap-6 max-w-[1400px] w-full mx-auto min-w-0"
>
    <x-slot:actions>
        <div class="flex items-center gap-2 flex-wrap">
            <a 
                href="{{ route('admin.courses.index') }}" 
                class="h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs"
            >
                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>{{ __('كافة الكورسات') }}</span>
            </a>

            <a 
                href="{{ route('admin.courses.edit', $course) }}" 
                class="h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-blue-50 dark:bg-slate-800 border border-blue-200 dark:border-slate-700 hover:bg-[#EAF1FB] text-[#0B2A8F] dark:text-blue-300 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>{{ __('تعديل بيانات الكورس') }}</span>
            </a>

            {{-- Course-level lesson settings --}}
            <button 
                type="button" 
                onclick="openCourseVideoSettingsModal({{ $course->videos->count() }})" 
                class="h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] hover:bg-[#EAF1FB] text-[#012383] dark:text-blue-300 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs cursor-pointer"
                title="{{ __('تحكم شامل في إعدادات جميع دروس الكورس') }}"
            >
                <svg class="size-4 text-[#012383] dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                <span>{{ __('إعدادات دروس الكورس') }}</span>
            </button>

            {{-- Dedicated Page: Add Lesson Button --}}
            <a 
                href="{{ route('admin.courses.videos.create', $course) }}" 
                class="group h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-bold text-xs flex items-center gap-1.5 transition-all shadow-sm hover:shadow-md cursor-pointer shrink-0"
            >
                <svg class="size-4 group-hover:rotate-90 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>{{ __('إضافة درس جديد') }}</span>
            </a>

            <button 
                type="button" 
                onclick="openModal('modal-add-level')" 
                class="group relative h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-slate-700 dark:text-slate-200 font-bold text-xs shadow-2xs hover:shadow-xs active:scale-95 transition-all duration-200 flex items-center gap-1.5 cursor-pointer shrink-0"
            >
                <svg class="size-4 group-hover:rotate-90 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span class="hidden xs:inline">{{ __('إضافة مستوى جديد') }}</span>
                <span class="xs:hidden">{{ __('مستوى جديد') }}</span>
            </button>
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

    <!-- Course Card Info Bar with Dynamic Pricing Controller -->
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-4 sm:p-7 shadow-xs flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-5 sm:gap-6 min-w-0">
        <div class="flex items-start sm:items-center gap-3.5 sm:gap-4 min-w-0">
            <div class="size-16 sm:size-20 rounded-2xl bg-[#012383] overflow-hidden shrink-0 border border-[#DEE8F7] dark:border-slate-700 shadow-2xs relative">
                <img src="{{ $course->thumbnail ?: '/assets/images/course-topic-basics.png' }}" alt="{{ $course->title_ar }}" class="size-full object-cover" />
            </div>
            <div class="space-y-1 text-right min-w-0">
                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap text-xs">
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 text-xs font-bold font-['Cairo']">
                        {{ $course->is_published ? __('الكورس الأساسي النشط') : __('مسودة غير منشورة') }}
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs text-slate-600 dark:text-slate-300 font-medium">{{ $course->levels->count() }} مستويات معتمدة</span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs text-slate-600 dark:text-slate-300 font-medium">{{ $course->videos->count() }} درس و{{ $course->clinicalTasks()->count() }} مهمة</span>
                    @if(!empty($course->effective_promo_video_url) || !empty($course->mux_playback_id))
                        <span class="text-xs text-slate-400">•</span>
                        <button 
                            type="button" 
                            onclick="previewVideo('{{ addslashes($course->title_ar) }} (فيديو تعريفي)', '{{ $course->promo_video_source ?: 'mux' }}', '{{ $course->effective_promo_video_url }}', '{{ $course->mux_playback_id }}')" 
                            class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 cursor-pointer"
                        >
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ __('معاينة برومو الكورس عبر MUX') }}</span>
                        </button>
                    @endif
                </div>
                <h2 class="font-['Cairo'] font-extrabold text-lg sm:text-2xl text-[#012383] dark:text-white leading-snug">
                    {{ $course->title_ar }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xl leading-relaxed">
                    {{ $course->description_ar ?: __('البرنامج التدريبي المتكامل لتأهيل أخصائيي التخاطب وأولياء الأمور مع نظام المهام بعد كل درس وشهادة إتمام عند ٩٥٪.') }}
                </p>
            </div>
        </div>

        <!-- Dynamic Price Badge & Editor Button -->
        <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/60 p-3.5 sm:p-4 rounded-2xl border border-[#DEE8F7]/80 dark:border-slate-700 w-full lg:w-auto justify-between lg:justify-start shrink-0">
            <div class="text-right">
                <span class="text-[11px] text-slate-400 font-bold block">{{ __('سعر الكورس المعتمد') }}</span>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <span class="text-xl sm:text-2xl font-black text-[#012383] dark:text-blue-400 dynamic-course-price">
                        {{ number_format($course->price) }} ج.م
                    </span>
                    @if($course->original_price && $course->original_price > $course->price)
                        <span class="text-xs text-slate-400 line-through dynamic-course-old-price">
                            {{ number_format($course->original_price) }} ج.م
                        </span>
                    @endif
                </div>
            </div>
            <button 
                type="button" 
                onclick="openModal('modal-edit-price')" 
                class="group h-10 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] dark:hover:border-blue-400 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 text-[#0B2A8F] dark:text-blue-300 font-bold text-xs transition-all duration-200 shadow-2xs hover:shadow-md active:scale-95 cursor-pointer flex items-center gap-1.5 shrink-0"
            >
                <svg class="size-3.5 group-hover:-rotate-14 group-hover:scale-110 transition-transform duration-250" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>{{ __('تعديل السعر') }}</span>
            </button>
        </div>
    </div>

    <!-- Dynamic Levels Curriculum Container -->
    <div class="flex flex-col gap-4 sm:gap-5 min-w-0">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-blue-400">
                    {{ __('هيكل المستويات والدروس') }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('لكل مستوى: قائمة بالدروس المندرجة تحته مع المهمة السريرية الملحقة به (رفع عبر MUX أو رابط خارجي)') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 px-3 py-1 rounded-full w-fit">
                    {{ __('إجمالي :count مستويات', ['count' => $course->levels->count()]) }}
                </span>
                <a 
                    href="{{ route('courses.index') }}" 
                    target="_blank" 
                    class="text-xs font-bold text-[#0B2A8F] dark:text-blue-300 bg-blue-50 dark:bg-slate-800 hover:bg-[#EAF1FB] border border-blue-200 dark:border-slate-700 px-3 py-1 rounded-full flex items-center gap-1 transition-all"
                >
                    <span>{{ __('معاينة بوابة الطالب') }}</span>
                    <span class="text-xs">↗</span>
                </a>
            </div>
        </div>

        <!-- Levels Container -->
        <div id="levels-curriculum-container" class="space-y-4">
            @forelse($course->levels as $index => $level)
                <div id="level-card-{{ $level->id }}" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs transition-all">
                    
                    {{-- Level Header Bar --}}
                    <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-50 via-white to-white dark:from-slate-800/50 dark:via-slate-800/30 dark:to-slate-800/30 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 border-b border-[#DEE8F7]/60 dark:border-slate-800">
                        <div class="flex items-start sm:items-center gap-3 min-w-0">
                            @if($level->videos->count() > 0)
                                <label class="flex items-center cursor-pointer shrink-0 mt-1 sm:mt-0" title="{{ __('تحديد جميع دروس هذا المستوى') }}">
                                    <input 
                                        type="checkbox" 
                                        class="level-select-all-checkbox size-4 rounded border-slate-300 text-[#012383] focus:ring-[#012383] cursor-pointer" 
                                        data-level-id="{{ $level->id }}" 
                                        onchange="toggleSelectLevelVideos({{ $level->id }}, this.checked)" 
                                    />
                                </label>
                            @endif
                            <span class="size-9 rounded-xl bg-[#012383] text-white font-bold text-sm flex items-center justify-center font-['Cairo'] shadow-2xs shrink-0">
                                {{ $level->sort_order ?: ($index + 1) }}
                            </span>
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                                    <h4 class="font-bold text-sm sm:text-base text-[#012383] dark:text-blue-400">
                                        {{ $level->title_ar }}
                                    </h4>
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold">
                                        {{ $level->videos->count() }} دروس
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 text-[11px] font-bold">
                                        {{ $level->videos->filter(fn($v) => !is_null($v->clinicalTask))->count() }} مهام
                                    </span>
                                </div>
                                @if($level->description_ar)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        {{ $level->description_ar }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Level Action Buttons --}}
                        <div class="flex items-center gap-2 self-end sm:self-auto shrink-0 flex-wrap">
                            @if($level->videos->count() > 0)
                                <button 
                                    type="button" 
                                    onclick="openLevelVideoSettingsModal({{ $level->id }}, '{{ addslashes($level->title_ar) }}', {{ $level->videos->count() }})" 
                                    class="px-2.5 sm:px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-[#012383] dark:text-blue-300 text-xs font-bold transition-all shadow-2xs hover:shadow-xs flex items-center gap-1 cursor-pointer"
                                    title="{{ __('إعدادات دروس هذا المستوى') }}"
                                >
                                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span class="hidden md:inline">{{ __('إعدادات الدروس') }}</span>
                                </button>
                            @endif

                            <a 
                                href="{{ route('admin.courses.videos.create', ['course' => $course, 'level_id' => $level->id]) }}" 
                                class="group px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-[#0B2A8F] to-[#012383] hover:from-[#012383] hover:to-[#0B2A8F] text-white text-xs font-bold transition-all duration-200 shadow-xs hover:shadow-md hover:shadow-blue-900/25 active:scale-95 flex items-center gap-1.5 cursor-pointer"
                            >
                                <svg class="size-3.5 group-hover:rotate-90 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <span>{{ __('إضافة درس') }}</span>
                            </a>

                            <button 
                                type="button" 
                                onclick="openEditLevelModal({{ $level->id }}, '{{ addslashes($level->title_ar) }}', '{{ addslashes($level->description_ar ?? '') }}', {{ $level->sort_order }})" 
                                class="p-2 text-slate-400 hover:text-[#012383] dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer" 
                                title="{{ __('تعديل المستوى') }}"
                            >
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>

                            <form 
                                action="{{ route('admin.courses.levels.destroy', [$course, $level]) }}" 
                                method="POST" 
                                onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا المستوى والدروس التابعة له؟') }}')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer" title="{{ __('حذف المستوى') }}">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Lessons List -->
                    <div class="lessons-list p-3 sm:p-5 flex flex-col gap-2.5 bg-slate-50/30 dark:bg-slate-900/40">
                        @forelse($level->videos as $video)
                            <div class="p-3 sm:p-3.5 bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 shadow-2xs hover:border-[#0B2A8F]/40 transition-colors">
                                <div class="flex items-start sm:items-center gap-3 min-w-0">
                                    {{-- Video Selection Checkbox --}}
                                    <label class="flex items-center cursor-pointer shrink-0 mt-1 sm:mt-0" title="{{ __('تحديد هذا الدرس للعمليات الجماعية') }}">
                                        <input 
                                            type="checkbox" 
                                            class="video-select-checkbox size-4 rounded border-slate-300 text-[#012383] focus:ring-[#012383] cursor-pointer" 
                                            value="{{ $video->id }}" 
                                            data-level-id="{{ $level->id }}" 
                                            onchange="handleVideoSelectionChange()" 
                                        />
                                    </label>

                                    <div class="size-8 rounded-lg bg-[#EAF1FB] dark:bg-slate-700 flex items-center justify-center shrink-0 text-[#0B2A8F] dark:text-blue-400 mt-0.5 sm:mt-0">
                                        <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                                    </div>
                                    <div class="text-right min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h5 class="font-bold text-xs sm:text-sm text-[#012383] dark:text-slate-100">
                                                {{ $video->title_ar }}
                                            </h5>
                                            
                                            {{-- Source Badge (MUX / Link / etc.) --}}
                                            @if($video->video_source === 'mux' || !empty($video->mux_playback_id))
                                                <span class="px-2 py-0.2 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold border border-emerald-200 dark:border-emerald-800 flex items-center gap-1">
                                                    <span class="size-1.5 rounded-full bg-emerald-500"></span> MUX Video
                                                </span>
                                            @elseif($video->video_source === 'youtube')
                                                <span class="px-2 py-0.2 rounded-md bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-200">YouTube</span>
                                            @elseif($video->video_source === 'vimeo')
                                                <span class="px-2 py-0.2 rounded-md bg-sky-50 text-sky-700 text-[10px] font-bold border border-sky-200">Vimeo</span>
                                            @elseif($video->video_source === 'bunny' || !empty($video->bunny_video_id))
                                                <span class="px-2 py-0.2 rounded-md bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">Bunny Stream</span>
                                            @else
                                                <span class="px-2 py-0.2 rounded-md bg-blue-50 text-blue-700 text-[10px] font-bold border border-blue-200">رابط مباشر</span>
                                            @endif

                                            {{-- Required Completion Percentage Badge --}}
                                            <span class="px-2 py-0.2 rounded-md bg-blue-50/80 dark:bg-slate-700 text-[#012383] dark:text-blue-300 text-[10px] font-bold border border-blue-200/60 dark:border-slate-600" title="{{ __('نسبة إكمال المشاهدة المطلوبة لاجتياز الدرس') }}">
                                                {{ __('مشاهدة مطلوبة: :pct%', ['pct' => $video->min_completion_percentage]) }}
                                            </span>

                                            @if($video->is_free_preview)
                                                <span class="px-1.5 py-0.2 rounded-md bg-amber-100 dark:bg-amber-950/50 text-amber-800 dark:text-amber-300 text-[10px] font-bold">معاينة مجانية</span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex-wrap">
                                            <span>{{ $video->duration_minutes }} دقيقة</span>
                                            @if($video->clinicalTask)
                                                <span>•</span>
                                                <span class="text-purple-700 dark:text-purple-400 font-medium">
                                                    {{ $video->clinicalTask->title_ar }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Lesson Action Buttons --}}
                                <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                                    {{-- Universal Preview Button (MUX or any source) --}}
                                    <button 
                                        type="button" 
                                        onclick="previewVideo('{{ addslashes($video->title_ar) }}', '{{ $video->video_source }}', '{{ $video->effective_playback_url }}', '{{ $video->mux_playback_id }}')" 
                                        class="group px-3 py-1.5 text-xs font-extrabold !text-[#012383] hover:!text-white dark:!text-blue-300 dark:hover:!text-white bg-blue-50 hover:bg-[#012383] dark:bg-slate-800 dark:hover:bg-blue-600 rounded-xl border border-blue-200 hover:border-[#012383] dark:border-slate-700 shadow-2xs hover:shadow-xs active:scale-95 transition-all duration-150 flex items-center gap-1.5 cursor-pointer"
                                        title="{{ __('تشغيل ومعاينة الدرس') }}"
                                    >
                                        <svg class="size-3 text-[#012383] group-hover:text-white dark:text-blue-300 dark:group-hover:text-white transition-colors fill-current shrink-0" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        <span class="!text-[#012383] group-hover:!text-white dark:!text-blue-300 dark:group-hover:!text-white font-extrabold transition-colors">{{ __('تشغيل ↗') }}</span>
                                    </button>

                                    {{-- Quick Single Video Settings Button --}}
                                    <button 
                                        type="button" 
                                        onclick="openSingleVideoSettingsModal({{ $video->id }}, '{{ addslashes($video->title_ar) }}', {{ $video->min_completion_percentage }}, {{ $video->is_free_preview ? 1 : 0 }}, {{ $video->level_id }})" 
                                        class="p-1.5 text-slate-400 hover:text-[#012383] dark:hover:text-blue-400 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors cursor-pointer" 
                                        title="{{ __('إعدادات هذا الدرس') }}"
                                    >
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>

                                    {{-- Edit Lesson Button --}}
                                    <button 
                                        type="button" 
                                        onclick="openEditLessonModal({{ $video->id }}, '{{ addslashes($video->title_ar) }}', {{ $video->duration_minutes }}, '{{ addslashes($video->effective_playback_url) }}', '{{ $video->mux_playback_id }}', '{{ addslashes($video->clinicalTask?->title_ar ?? '') }}', {{ $video->level_id }}, {{ $video->min_completion_percentage }}, {{ $video->is_free_preview ? 1 : 0 }})" 
                                        class="p-1.5 text-slate-400 hover:text-[#012383] rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors cursor-pointer" 
                                        title="{{ __('تعديل الدرس') }}"
                                    >
                                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>

                                    {{-- Delete Lesson Form --}}
                                    <form 
                                        action="{{ route('admin.courses.videos.destroy', [$course, $video]) }}" 
                                        method="POST" 
                                        onsubmit="return confirm('{{ __('هل أنت متأكد من رغبتك في حذف هذا الدرس نهائياً؟') }}')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer" title="{{ __('حذف الدرس') }}">
                                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-xs text-slate-400 bg-white/60 dark:bg-slate-800/40 rounded-xl border border-dashed border-[#DEE8F7] dark:border-slate-700 flex flex-col items-center justify-center gap-2">
                                <span>{{ __('لا توجد دروس مضافة لهذا المستوى حتى الآن.') }}</span>
                                <a 
                                    href="{{ route('admin.courses.videos.create', ['course' => $course, 'level_id' => $level->id]) }}" 
                                    class="px-3.5 py-1.5 rounded-lg bg-[#012383] text-white font-bold text-xs hover:bg-[#0B2A8F] transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer"
                                >
                                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    <span>{{ __('إضافة الدرس الأول لهذا المستوى') }}</span>
                                </a>
                            </div>
                        @endforelse
                    </div>

                </div>
            @empty
                <div class="p-10 bg-white dark:bg-slate-900 border border-dashed border-[#DEE8F7] dark:border-slate-800 rounded-3xl text-center space-y-3">
                    <p class="font-bold text-slate-600 dark:text-slate-300 text-sm">{{ __('لم يتم تعريف أي مستويات تدريبية لهذا الكورس بعد.') }}</p>
                    <button type="button" onclick="openModal('modal-add-level')" class="px-4 py-2 bg-[#012383] text-white font-bold text-xs rounded-xl cursor-pointer">
                        {{ __('إضافة المستوى الأول الآن') }}
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Floating Bulk Actions Bar --}}
    <div id="bulk-actions-bar" class="fixed bottom-5 inset-x-3 sm:inset-x-auto sm:left-1/2 sm:-translate-x-1/2 z-40 bg-[#012383] text-white p-3 sm:px-6 sm:py-3.5 rounded-2xl shadow-2xl border border-blue-400/30 flex items-center justify-between sm:justify-center gap-3 sm:gap-4 transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none max-w-2xl w-full">
        <div class="flex items-center gap-2">
            <span class="size-7 rounded-lg bg-white/20 flex items-center justify-center font-black text-xs" id="bulk-selected-count">0</span>
            <span class="text-xs font-bold">{{ __('دروس محددة') }}</span>
        </div>

        <div class="h-5 w-px bg-white/20 hidden sm:block"></div>

        <div class="flex items-center gap-2 flex-wrap">
            {{-- Bulk Settings Button --}}
            <button 
                type="button" 
                onclick="openBulkVideoSettingsModal()" 
                class="px-3 py-1.5 rounded-xl bg-white text-[#012383] hover:bg-blue-50 font-extrabold text-xs flex items-center gap-1.5 shadow-xs transition-all cursor-pointer"
            >
                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                <span>{{ __('إعدادات المحدد') }}</span>
            </button>

            {{-- Bulk Move Level Button --}}
            <button 
                type="button" 
                onclick="openBulkMoveModal()" 
                class="px-3 py-1.5 rounded-xl bg-blue-900/60 hover:bg-blue-800 text-white font-bold text-xs flex items-center gap-1.5 border border-white/20 transition-all cursor-pointer"
            >
                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <span class="hidden sm:inline">{{ __('نقل لمستوى') }}</span>
            </button>

            {{-- Bulk Delete Button --}}
            <button 
                type="button" 
                onclick="submitBulkDelete()" 
                class="px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs flex items-center gap-1.5 transition-all cursor-pointer"
                title="{{ __('حذف الدروس المحددة') }}"
            >
                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span class="hidden sm:inline">{{ __('حذف') }}</span>
            </button>

            {{-- Clear Selection --}}
            <button 
                type="button" 
                onclick="clearAllVideoSelection()" 
                class="p-1.5 rounded-lg text-blue-200 hover:text-white hover:bg-white/10 transition-colors cursor-pointer" 
                title="{{ __('إلغاء التحديد') }}"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <x-slot:modals>
        <!-- Modal 2: Edit Lesson -->
        <div id="modal-edit-lesson" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-xl w-full p-5 sm:p-7 flex flex-col gap-4 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-3.5">
                    <div>
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-[#012383] dark:text-white">{{ __('تعديل بيانات الدرس') }}</h3>
                    </div>
                    <button type="button" onclick="closeModal('modal-edit-lesson')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="form-edit-lesson" action="" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('عنوان الدرس:') }}</label>
                        <input type="text" name="title_ar" id="edit-lesson-title" required class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('المدة (بالدقائق):') }}</label>
                            <input type="number" name="duration_minutes" id="edit-lesson-duration" required min="1" max="300" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('نسبة إكمال المشاهدة (%):') }}</label>
                            <input type="number" name="min_completion_percentage" id="edit-lesson-min-completion" min="1" max="100" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('المستوى التابع له:') }}</label>
                            <select name="level_id" id="edit-lesson-level" class="w-full h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50">
                                @foreach($course->levels as $lvl)
                                    <option value="{{ $lvl->id }}">{{ $lvl->title_ar }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('رابط فيديو الدرس أو معرّف بث MUX:') }}</label>
                        <input type="text" name="video_url" id="edit-lesson-url" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('أو استبدال فيديو الدرس برفع ملف جديد إلى MUX:') }}</label>
                        <input type="file" name="video_file" accept="video/mp4,video/quicktime,video/webm" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#012383] file:text-white hover:file:bg-[#0B2A8F] cursor-pointer" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-[#012383] dark:text-blue-400 block mb-1">{{ __('المهمة السريرية الملحقة:') }}</label>
                        <input type="text" name="task_title_ar" id="edit-lesson-task" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_free_preview" id="edit-lesson-preview" value="1" class="size-4 rounded border-slate-300 text-[#012383] focus:ring-[#012383] cursor-pointer" />
                        <label for="edit-lesson-preview" class="text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer">
                            {{ __('إتاحة هذا الدرس كمعاينة مجانية للزوار قبل الاشتراك') }}
                        </label>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-2 border-t border-[#DEE8F7] dark:border-slate-800">
                        <button type="button" onclick="closeModal('modal-edit-lesson')" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm cursor-pointer">
                            {{ __('تحديث الدرس') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal 3: Add New Level -->
        <div id="modal-add-level" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-md w-full p-5 sm:p-8 flex flex-col gap-4 sm:gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                    <div class="flex items-center gap-2">
                        <div class="size-8 rounded-lg bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400 flex items-center justify-center font-bold">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white">
                            {{ __('إضافة مستوى ديناميكي جديد') }}
                        </h3>
                    </div>
                    <button type="button" onclick="closeModal('modal-add-level')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('admin.courses.levels.store', $course) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('عنوان المستوى الجديد:') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="title_ar" required placeholder="مثال: المستوى الخامس: استراتيجيات التدخل المبكر" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('وصف موجز للمستوى:') }}</label>
                        <textarea name="description_ar" rows="2" placeholder="أهداف المستوى والمحاور السريرية المندرجة تحته..." class="w-full p-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] resize-none bg-slate-50/50 dark:bg-slate-800/50"></textarea>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-2">
                        <button type="button" onclick="closeModal('modal-add-level')" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm cursor-pointer">
                            {{ __('إنشاء المستوى') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal 4: Edit Level -->
        <div id="modal-edit-level" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-md w-full p-5 sm:p-8 flex flex-col gap-4 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-[#012383] dark:text-white">{{ __('تعديل المستوى التدريبي') }}</h3>
                    <button type="button" onclick="closeModal('modal-edit-level')" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="form-edit-level" action="" method="POST" class="flex flex-col gap-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('عنوان المستوى:') }}</label>
                        <input type="text" name="title_ar" id="edit-level-title" required class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('وصف المستوى:') }}</label>
                        <textarea name="description_ar" id="edit-level-desc" rows="2" class="w-full p-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] resize-none bg-slate-50/50 dark:bg-slate-800/50"></textarea>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-2">
                        <button type="button" onclick="closeModal('modal-edit-level')" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs cursor-pointer">{{ __('إلغاء') }}</button>
                        <button type="submit" class="w-full sm:w-auto px-6 h-10 rounded-xl bg-[#012383] text-white font-bold text-xs cursor-pointer">{{ __('تحديث المستوى') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal 5: Edit Price -->
        <div id="modal-edit-price" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-md w-full p-5 sm:p-8 flex flex-col gap-4 sm:gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                    <div class="flex items-center gap-2">
                        <div class="size-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white">
                            {{ __('تعديل سعر الكورس') }}
                        </h3>
                    </div>
                    <button type="button" onclick="closeModal('modal-edit-price')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('admin.courses.price.update', $course) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('السعر الأصلي (قبل الخصم):') }}</label>
                        <input type="number" name="originalPrice" value="{{ (int)$course->original_price ?: 1800 }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-600 dark:text-slate-300 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('السعر الفعلي المطلوب دفعه:') }} <span class="text-rose-500">*</span></label>
                        <input type="number" name="currentPrice" required value="{{ (int)$course->price ?: 1200 }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-blue-400 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-2">
                        <button type="button" onclick="closeModal('modal-edit-price')" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">{{ __('إلغاء') }}</button>
                        <button type="submit" class="w-full sm:w-auto px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm cursor-pointer">{{ __('حفظ ونشر السعر') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal 6: Universal Video Preview Modal (MUX Player or Multi-Source) -->
        <div id="modal-video-preview" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-content bg-slate-950 text-white rounded-2xl sm:rounded-3xl max-w-3xl w-full p-4 sm:p-6 flex flex-col gap-4 shadow-2xl border border-slate-800">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="size-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                            <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                        </div>
                        <div>
                            <h4 id="preview-video-title" class="font-bold text-sm sm:text-base text-white">معاينة الفيديو</h4>
                            <span id="preview-video-source" class="text-[11px] font-mono text-emerald-400">MUX Stream</span>
                        </div>
                    </div>
                    <button type="button" onclick="closeVideoPreview()" class="p-1.5 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Player Dynamic Container --}}
                <div id="video-player-container" class="w-full bg-black rounded-xl overflow-hidden shadow-inner aspect-video flex items-center justify-center relative">
                    <!-- Populated dynamically via previewVideo() -->
                </div>

                <div class="flex items-center justify-between text-xs text-slate-400 pt-1">
                    <span id="preview-video-url-label" class="truncate max-w-md font-mono text-[11px]"></span>
                    <button type="button" onclick="closeVideoPreview()" class="px-4 py-1.5 rounded-lg bg-slate-800 text-slate-200 hover:bg-slate-700 font-bold text-xs cursor-pointer">
                        {{ __('إغلاق المشغل') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal 7: Full Video Settings Control (Course, Level, Bulk, or Specific Video) -->
        <div id="modal-video-settings" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-xl w-full p-5 sm:p-7 flex flex-col gap-4 sm:gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="size-9 rounded-xl bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-300 flex items-center justify-center shrink-0">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                        </div>
                        <div>
                            <h3 id="video-settings-modal-title" class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white leading-tight">
                                {{ __('إعدادات الدروس') }}
                            </h3>
                            <p id="video-settings-modal-subtitle" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                {{ __('تعديل نسب المشاهدة وسياسات المعاينة والنقل') }}
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal('modal-video-settings')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="form-video-settings" action="{{ route('admin.courses.videos.settings.apply', $course) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="scope" id="video-settings-scope" value="course" />
                    <input type="hidden" name="level_id" id="video-settings-level-id" value="" />
                    <input type="hidden" name="bulk_action" id="video-settings-bulk-action" value="update_settings" />
                    
                    {{-- Dynamic container for selected video IDs --}}
                    <div id="video-settings-ids-container"></div>

                    {{-- Scope Info Banner --}}
                    <div id="video-settings-scope-banner" class="p-3 rounded-xl bg-blue-50/80 dark:bg-slate-800/80 border border-blue-200/80 dark:border-slate-700 text-xs font-bold text-[#012383] dark:text-blue-300 flex items-center gap-2">
                        <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span id="video-settings-scope-text">{{ __('تطبيق الإعدادات على جميع دروس الكورس.') }}</span>
                    </div>

                    {{-- 1. Min Completion Watch Percentage --}}
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-[#DEE8F7] dark:border-slate-700 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-[#012383] dark:text-blue-300 flex items-center gap-1.5">
                                <svg class="size-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ __('نسبة إكمال المشاهدة المطلوبة (%):') }}</span>
                            </label>
                            <span class="text-[11px] font-bold text-slate-400">{{ __('الافتراضي: 80%') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <input 
                                type="number" 
                                name="min_completion_percentage" 
                                id="video-settings-min-completion" 
                                min="1" 
                                max="100" 
                                value="80" 
                                class="w-32 h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-black text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-white dark:bg-slate-800"
                            />
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <button type="button" onclick="document.getElementById('video-settings-min-completion').value = 75" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-700 border border-[#DEE8F7] dark:border-slate-600 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-[#0B2A8F] cursor-pointer">75%</button>
                                <button type="button" onclick="document.getElementById('video-settings-min-completion').value = 80" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-700 border border-[#DEE8F7] dark:border-slate-600 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-[#0B2A8F] cursor-pointer">80%</button>
                                <button type="button" onclick="document.getElementById('video-settings-min-completion').value = 90" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-700 border border-[#DEE8F7] dark:border-slate-600 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-[#0B2A8F] cursor-pointer">90%</button>
                                <button type="button" onclick="document.getElementById('video-settings-min-completion').value = 100" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-700 border border-[#DEE8F7] dark:border-slate-600 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-[#0B2A8F] cursor-pointer">100%</button>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                            {{ __('الحد الأدنى لمشاهدة المتدرب للدرس قبل اعتباره مكتملاً وإتاحة الدرس التالي أو المهمة السريرية.') }}
                        </p>
                    </div>

                    {{-- 2. Free Preview Policy --}}
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-[#DEE8F7] dark:border-slate-700 space-y-2.5">
                        <label class="text-xs font-bold text-[#012383] dark:text-blue-300 block flex items-center gap-1.5">
                            <svg class="size-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>{{ __('سياسة المعاينة المجانية:') }}</span>
                        </label>

                        <div class="space-y-2">
                            <label class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 cursor-pointer hover:border-[#0B2A8F] transition-all">
                                <input type="radio" name="free_preview_policy" value="keep" checked class="size-4 text-[#012383] focus:ring-[#012383] cursor-pointer" />
                                <div>
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200 block">{{ __('الاحتفاظ بالحالة الحالية دون تغيير') }}</span>
                                    <span class="text-[11px] text-slate-400">{{ __('لا يتم تغيير وضع المعاينة المجانية للدروس المستهدفة') }}</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 cursor-pointer hover:border-[#0B2A8F] transition-all">
                                <input type="radio" name="free_preview_policy" value="enable_all" class="size-4 text-[#012383] focus:ring-[#012383] cursor-pointer" />
                                <div>
                                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 block">{{ __('تفعيل المعاينة المجانية للكل') }}</span>
                                    <span class="text-[11px] text-slate-400">{{ __('تتاح الدروس مجاناً للزوار والمسجلين قبل الاشتراك') }}</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 cursor-pointer hover:border-[#0B2A8F] transition-all">
                                <input type="radio" name="free_preview_policy" value="disable_all" class="size-4 text-[#012383] focus:ring-[#012383] cursor-pointer" />
                                <div>
                                    <span class="text-xs font-bold text-rose-700 dark:text-rose-400 block">{{ __('إغلاق المعاينة المجانية (مدفوعة بالكامل)') }}</span>
                                    <span class="text-[11px] text-slate-400">{{ __('تتطلب اشتراكاً مدفوعاً لدراستها') }}</span>
                                </div>
                            </label>

                            <label id="option-first-only" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 cursor-pointer hover:border-[#0B2A8F] transition-all">
                                <input type="radio" name="free_preview_policy" value="first_only" class="size-4 text-[#012383] focus:ring-[#012383] cursor-pointer" />
                                <div>
                                    <span class="text-xs font-bold text-[#012383] dark:text-blue-300 block">{{ __('معاينة مجانية لأول درس فقط') }}</span>
                                    <span class="text-[11px] text-slate-400">{{ __('يفتح أول درس فقط كمعاينة وتغلق بقية الدروس لاشتراك الطالب') }}</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 3. Optional Move to Level --}}
                    <div id="video-settings-level-selector" class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-[#DEE8F7] dark:border-slate-700 space-y-2">
                        <label class="text-xs font-bold text-[#012383] dark:text-blue-300 block flex items-center gap-1.5">
                            <svg class="size-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span>{{ __('نقل إلى مستوى آخر (اختياري):') }}</span>
                        </label>
                        <select name="target_level_id" id="video-settings-target-level" class="w-full h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-white dark:bg-slate-800">
                            <option value="">{{ __('عدم النقل (الإبقاء في نفس المستوى الحالي)') }}</option>
                            @foreach($course->levels as $lvl)
                                <option value="{{ $lvl->id }}">{{ $lvl->title_ar }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-2 border-t border-[#DEE8F7] dark:border-slate-800">
                        <button type="button" onclick="closeModal('modal-video-settings')" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-6 h-10 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-bold text-xs shadow-sm cursor-pointer">
                            {{ __('حفظ وتطبيق الإعدادات') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal 8: Bulk Move Lessons to Level -->
        <div id="modal-bulk-move" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-md w-full p-5 sm:p-7 flex flex-col gap-4 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-3">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-[#012383] dark:text-white">
                        {{ __('نقل الدروس المحددة إلى مستوى آخر') }}
                    </h3>
                    <button type="button" onclick="closeModal('modal-bulk-move')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="form-bulk-move" action="{{ route('admin.courses.videos.settings.apply', $course) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="scope" value="bulk" />
                    <input type="hidden" name="bulk_action" value="move_level" />
                    <div id="bulk-move-ids-container"></div>

                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('سيتم نقل الدروس المحددة إلى المستوى التدريبي المختار أدناه:') }}
                    </p>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                            {{ __('المستوى المستهدف:') }} <span class="text-rose-500">*</span>
                        </label>
                        <select name="target_level_id" required class="w-full h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50 dark:bg-slate-800">
                            @foreach($course->levels as $lvl)
                                <option value="{{ $lvl->id }}">{{ $lvl->title_ar }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-2 border-t border-[#DEE8F7] dark:border-slate-800">
                        <button type="button" onclick="closeModal('modal-bulk-move')" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm cursor-pointer">
                            {{ __('تأكيد النقل') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Hidden form for bulk delete --}}
        <form id="form-bulk-delete" action="{{ route('admin.courses.videos.settings.apply', $course) }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="scope" value="bulk" />
            <input type="hidden" name="bulk_action" value="delete" />
            <div id="bulk-delete-ids-container"></div>
        </form>
    </x-slot:modals>

    <script>
        function openModal(id) {
            const m = document.getElementById(id);
            if (m) {
                m.classList.remove('modal-hidden');
                document.documentElement.classList.add('modal-open');
                document.body.classList.add('modal-open');
                document.body.style.overflow = 'hidden';
                document.documentElement.style.overflow = 'hidden';
                const content = m.querySelector('.modal-content') || m;
                if (content && typeof content.scrollTop !== 'undefined') {
                    content.scrollTop = 0;
                }
            }
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if (m) {
                m.classList.add('modal-hidden');
                const anyOpen = document.querySelector('.modal-backdrop:not(.modal-hidden)');
                if (!anyOpen) {
                    document.documentElement.classList.remove('modal-open');
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.documentElement.style.overflow = '';
                }
            }
        }

        function openEditLevelModal(levelId, title, desc, sortOrder) {
            const form = document.getElementById('form-edit-level');
            form.action = `/admin/courses/{{ $course->id }}/levels/${levelId}`;
            document.getElementById('edit-level-title').value = title;
            document.getElementById('edit-level-desc').value = desc;
            openModal('modal-edit-level');
        }

        function openEditLessonModal(videoId, title, durationMinutes, url, muxId, taskTitle, levelId, minCompletion, isPreview) {
            const form = document.getElementById('form-edit-lesson');
            form.action = `/admin/courses/{{ $course->id }}/videos/${videoId}`;
            document.getElementById('edit-lesson-title').value = title;
            document.getElementById('edit-lesson-duration').value = durationMinutes;
            document.getElementById('edit-lesson-min-completion').value = minCompletion || 80;
            document.getElementById('edit-lesson-url').value = muxId || url;
            document.getElementById('edit-lesson-task').value = taskTitle;
            document.getElementById('edit-lesson-preview').checked = !!isPreview;
            if (document.getElementById('edit-lesson-level')) {
                document.getElementById('edit-lesson-level').value = levelId;
            }
            openModal('modal-edit-lesson');
        }

        // Multi-Selection and Bulk Video Settings
        function getSelectedVideoIds() {
            const checkboxes = document.querySelectorAll('.video-select-checkbox:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        function handleVideoSelectionChange() {
            const selectedIds = getSelectedVideoIds();
            const count = selectedIds.length;
            const bar = document.getElementById('bulk-actions-bar');
            const countEl = document.getElementById('bulk-selected-count');
            
            if (countEl) countEl.textContent = count;
            
            if (count > 0) {
                bar.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
                bar.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
            } else {
                bar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
                bar.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
            }

            // Sync level checkboxes
            document.querySelectorAll('.level-select-all-checkbox').forEach(lvlCb => {
                const lvlId = lvlCb.getAttribute('data-level-id');
                const levelVideos = document.querySelectorAll(`.video-select-checkbox[data-level-id="${lvlId}"]`);
                const checkedLevelVideos = document.querySelectorAll(`.video-select-checkbox[data-level-id="${lvlId}"]:checked`);
                lvlCb.checked = (levelVideos.length > 0 && levelVideos.length === checkedLevelVideos.length);
            });
        }

        function toggleSelectLevelVideos(levelId, isChecked) {
            document.querySelectorAll(`.video-select-checkbox[data-level-id="${levelId}"]`).forEach(cb => {
                cb.checked = isChecked;
            });
            handleVideoSelectionChange();
        }

        function clearAllVideoSelection() {
            document.querySelectorAll('.video-select-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.level-select-all-checkbox').forEach(cb => cb.checked = false);
            handleVideoSelectionChange();
        }

        // Scope 1: Course-wide Settings Modal
        function openCourseVideoSettingsModal(videoCount) {
            document.getElementById('video-settings-scope').value = 'course';
            document.getElementById('video-settings-level-id').value = '';
            document.getElementById('video-settings-ids-container').innerHTML = '';
            document.getElementById('video-settings-modal-title').textContent = '{{ __("إعدادات دروس الكورس بالكامل") }}';
            document.getElementById('video-settings-modal-subtitle').textContent = `{{ __("تحكم موحد في جميع دروس المنهج") }} (${videoCount} {{ __("درس") }})`;
            document.getElementById('video-settings-scope-text').textContent = `{{ __("سيتم تطبيق الإعدادات المختارة على كافة مستويات ودروس الكورس") }} (${videoCount} {{ __("درس") }}).`;
            
            const firstOnlyOption = document.getElementById('option-first-only');
            if (firstOnlyOption) firstOnlyOption.style.display = 'flex';

            const moveSelector = document.getElementById('video-settings-level-selector');
            if (moveSelector) moveSelector.style.display = 'none';

            openModal('modal-video-settings');
        }

        // Scope 2: Level-wide Settings Modal
        function openLevelVideoSettingsModal(levelId, levelTitle, videoCount) {
            document.getElementById('video-settings-scope').value = 'level';
            document.getElementById('video-settings-level-id').value = levelId;
            document.getElementById('video-settings-ids-container').innerHTML = '';
            document.getElementById('video-settings-modal-title').textContent = `{{ __("إعدادات دروس:") }} ${levelTitle}`;
            document.getElementById('video-settings-modal-subtitle').textContent = `{{ __("تطبيق على جميع دروس هذا المستوى") }} (${videoCount} {{ __("درس") }})`;
            document.getElementById('video-settings-scope-text').textContent = `{{ __("سيتم تطبيق الإعدادات على جميع دروس") }} "${levelTitle}" (${videoCount} {{ __("درس") }}).`;

            const firstOnlyOption = document.getElementById('option-first-only');
            if (firstOnlyOption) firstOnlyOption.style.display = 'flex';

            const moveSelector = document.getElementById('video-settings-level-selector');
            if (moveSelector) moveSelector.style.display = 'block';

            openModal('modal-video-settings');
        }

        // Scope 3: Bulk Selection Settings Modal
        function openBulkVideoSettingsModal() {
            const selectedIds = getSelectedVideoIds();
            if (selectedIds.length === 0) {
                alert('{{ __("يرجى تحديد درس واحد على الأقل أولاً.") }}');
                return;
            }

            document.getElementById('video-settings-scope').value = 'bulk';
            document.getElementById('video-settings-level-id').value = '';
            
            const container = document.getElementById('video-settings-ids-container');
            container.innerHTML = '';
            selectedIds.forEach(id => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'video_ids[]';
                inp.value = id;
                container.appendChild(inp);
            });

            document.getElementById('video-settings-modal-title').textContent = '{{ __("إعدادات الدروس المحددة") }}';
            document.getElementById('video-settings-modal-subtitle').textContent = `{{ __("تطبيق على") }} ${selectedIds.length} {{ __("دروس تم اختيارها") }}`;
            document.getElementById('video-settings-scope-text').textContent = `{{ __("سيتم تطبيق الإعدادات المختارة على") }} ${selectedIds.length} {{ __("درس محدد") }}.`;

            const firstOnlyOption = document.getElementById('option-first-only');
            if (firstOnlyOption) firstOnlyOption.style.display = 'none';

            const moveSelector = document.getElementById('video-settings-level-selector');
            if (moveSelector) moveSelector.style.display = 'block';

            openModal('modal-video-settings');
        }

        // Scope 4: Specific Single Video Settings Modal
        function openSingleVideoSettingsModal(videoId, title, minCompletion, isPreview, levelId) {
            document.getElementById('video-settings-scope').value = 'specific';
            document.getElementById('video-settings-level-id').value = '';
            
            const container = document.getElementById('video-settings-ids-container');
            container.innerHTML = '';
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'video_ids[]';
            inp.value = videoId;
            container.appendChild(inp);

            document.getElementById('video-settings-min-completion').value = minCompletion || 80;
            
            const policyRadios = document.querySelectorAll('input[name="free_preview_policy"]');
            policyRadios.forEach(r => {
                if (isPreview && r.value === 'enable_all') r.checked = true;
                else if (!isPreview && r.value === 'disable_all') r.checked = true;
                else if (r.value === 'keep') r.checked = true;
            });

            const targetLevel = document.getElementById('video-settings-target-level');
            if (targetLevel) targetLevel.value = levelId || '';

            document.getElementById('video-settings-modal-title').textContent = `{{ __("إعدادات الدرس:") }} ${title}`;
            document.getElementById('video-settings-modal-subtitle').textContent = '{{ __("تحكم كامل في نسبة المشاهدة والمعاينة والمستوى") }}';
            document.getElementById('video-settings-scope-text').textContent = `{{ __("تعديل إعدادات هذا الدرس الفردي المحدد.") }}`;

            const firstOnlyOption = document.getElementById('option-first-only');
            if (firstOnlyOption) firstOnlyOption.style.display = 'none';

            const moveSelector = document.getElementById('video-settings-level-selector');
            if (moveSelector) moveSelector.style.display = 'block';

            openModal('modal-video-settings');
        }

        // Bulk Move Modal
        function openBulkMoveModal() {
            const selectedIds = getSelectedVideoIds();
            if (selectedIds.length === 0) {
                alert('{{ __("يرجى تحديد درس واحد على الأقل أولاً.") }}');
                return;
            }

            const container = document.getElementById('bulk-move-ids-container');
            container.innerHTML = '';
            selectedIds.forEach(id => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'video_ids[]';
                inp.value = id;
                container.appendChild(inp);
            });

            openModal('modal-bulk-move');
        }

        // Bulk Delete Confirmation & Submission
        function submitBulkDelete() {
            const selectedIds = getSelectedVideoIds();
            if (selectedIds.length === 0) {
                alert('{{ __("يرجى تحديد درس واحد على الأقل أولاً.") }}');
                return;
            }

            if (!confirm(`{{ __("هل أنت متأكد من رغبتك في حذف") }} ${selectedIds.length} {{ __("درس محدد نهائياً من المنهج؟") }}`)) {
                return;
            }

            const container = document.getElementById('bulk-delete-ids-container');
            container.innerHTML = '';
            selectedIds.forEach(id => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'video_ids[]';
                inp.value = id;
                container.appendChild(inp);
            });

            document.getElementById('form-bulk-delete').submit();
        }

        // Universal Video Preview Player (MUX / YouTube / Vimeo / HLS / MP4)
        function previewVideo(title, source, playbackUrl, muxPlaybackId) {
            document.getElementById('preview-video-title').textContent = title;
            const container = document.getElementById('video-player-container');
            const sourceLabel = document.getElementById('preview-video-source');
            const urlLabel = document.getElementById('preview-video-url-label');
            
            container.innerHTML = '';
            urlLabel.textContent = playbackUrl || muxPlaybackId || '';

            // 1. MUX Stream (via <mux-player> or HLS)
            if (muxPlaybackId || (playbackUrl && playbackUrl.includes('stream.mux.com'))) {
                const finalPlaybackId = muxPlaybackId || (playbackUrl.match(/stream\.mux\.com\/([a-zA-Z0-9_\-]+)/) ? playbackUrl.match(/stream\.mux\.com\/([a-zA-Z0-9_\-]+)/)[1] : '');
                sourceLabel.textContent = 'MUX Stream Player';

                if (window.customElements && customElements.get('mux-player')) {
                    const muxPlayer = document.createElement('mux-player');
                    muxPlayer.setAttribute('playback-id', finalPlaybackId);
                    muxPlayer.setAttribute('stream-type', 'on-demand');
                    muxPlayer.setAttribute('autoplay', 'true');
                    muxPlayer.setAttribute('controls', 'true');
                    muxPlayer.className = 'w-full h-full';
                    container.appendChild(muxPlayer);
                } else {
                    // Fallback to standard video element with Hls.js
                    const video = document.createElement('video');
                    video.controls = true;
                    video.autoplay = true;
                    video.className = 'w-full h-full';
                    const hlsSrc = `https://stream.mux.com/${finalPlaybackId}.m3u8`;
                    
                    if (window.Hls && Hls.isSupported()) {
                        const hls = new Hls();
                        hls.loadSource(hlsSrc);
                        hls.attachMedia(video);
                    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                        video.src = hlsSrc;
                    }
                    container.appendChild(video);
                }
            }
            // 2. YouTube
            else if (playbackUrl && (playbackUrl.includes('youtube.com') || playbackUrl.includes('youtu.be'))) {
                sourceLabel.textContent = 'YouTube Player';
                let ytId = '';
                const m = playbackUrl.match(/(?:embed\/|watch\?v=|youtu\.be\/)([a-zA-Z0-9_\-]+)/);
                if (m) ytId = m[1];
                container.innerHTML = `<iframe src="https://www.youtube.com/embed/${ytId}?autoplay=1" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
            }
            // 3. Vimeo
            else if (playbackUrl && playbackUrl.includes('vimeo.com')) {
                sourceLabel.textContent = 'Vimeo Player';
                let vmId = '';
                const m = playbackUrl.match(/vimeo\.com\/(?:video\/)?([0-9]+)/);
                if (m) vmId = m[1];
                container.innerHTML = `<iframe src="https://player.vimeo.com/video/${vmId}?autoplay=1" class="w-full h-full border-0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
            }
            // 4. Direct HLS or MP4 HTML5 Video
            else if (playbackUrl) {
                sourceLabel.textContent = 'HTML5 Video Player';
                const video = document.createElement('video');
                video.controls = true;
                video.autoplay = true;
                video.className = 'w-full h-full';
                if (playbackUrl.endsWith('.m3u8') && window.Hls && Hls.isSupported()) {
                    const hls = new Hls();
                    hls.loadSource(playbackUrl);
                    hls.attachMedia(video);
                } else {
                    video.src = playbackUrl;
                }
                container.appendChild(video);
            } else {
                container.innerHTML = '<div class="p-6 text-slate-400 text-xs">لا يوجد رابط فيديو محدد للمعاينة.</div>';
            }

            openModal('modal-video-preview');
        }

        function closeVideoPreview() {
            const container = document.getElementById('video-player-container');
            container.innerHTML = '';
            closeModal('modal-video-preview');
        }
    </script>
</x-admin-layout::admin>
