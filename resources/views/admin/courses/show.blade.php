<x-admin-layout::admin
    :title="__('إدارة محتوى الكورس والمستويات')"
    :description="__('تحكم كامل في المستويات، رفع الفيديوهات، ربط المهام، وتعديل السعر ديناميكياً')"
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
                <span>{{ __('تعديل بيانات الكورس (Edit Page)') }}</span>
            </a>

            <button 
                type="button" 
                onclick="openModal('modal-add-level')" 
                class="group relative h-9 sm:h-11 px-3.5 sm:px-5 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-bold text-xs shadow-sm hover:shadow-lg hover:shadow-blue-900/30 active:scale-95 transition-all duration-200 flex items-center gap-1.5 sm:gap-2 cursor-pointer shrink-0"
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
                    <span class="text-xs text-slate-600 dark:text-slate-300 font-medium">{{ $course->videos->count() }} فيديو و{{ $course->clinicalTasks()->count() }} مهمة</span>
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
                    {{ __('هيكل المستويات والدروس (Dynamic Levels Architecture)') }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('لكل مستوى: قائمة بالفيديوهات المندرجة تحته مع المهمة السريرية الملحقة به (رفع عبر MUX أو رابط خارجي)') }}
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
                            <span class="size-9 rounded-xl bg-[#012383] text-white font-bold text-sm flex items-center justify-center font-['Cairo'] shadow-2xs shrink-0">
                                {{ $level->sort_order ?: ($index + 1) }}
                            </span>
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                                    <h4 class="font-bold text-sm sm:text-base text-[#012383] dark:text-blue-400">
                                        {{ $level->title_ar }}
                                    </h4>
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold">
                                        {{ $level->videos->count() }} فيديوهات
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
                        <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                            <button 
                                type="button" 
                                onclick="openAddLessonModal({{ $level->id }}, '{{ addslashes($level->title_ar) }}')" 
                                class="group px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-[#0B2A8F] to-[#012383] hover:from-[#012383] hover:to-[#0B2A8F] text-white text-xs font-bold transition-all duration-200 shadow-xs hover:shadow-md hover:shadow-blue-900/25 active:scale-95 flex items-center gap-1.5 cursor-pointer"
                            >
                                <svg class="size-3.5 group-hover:rotate-90 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <span>{{ __('إضافة درس') }}</span>
                            </button>

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
                                        class="group px-3 py-1.5 text-xs font-bold text-[#0B2A8F] dark:text-blue-300 bg-blue-50/70 dark:bg-slate-800/80 hover:bg-[#0B2A8F] hover:text-white dark:hover:bg-blue-600 dark:hover:text-white rounded-xl border border-blue-100 dark:border-slate-700 hover:border-[#0B2A8F] shadow-2xs hover:shadow-md hover:shadow-blue-900/20 active:scale-95 transition-all duration-200 flex items-center gap-1 cursor-pointer"
                                    >
                                        <span>{{ __('تشغيل ↗') }}</span>
                                    </button>

                                    {{-- Edit Lesson Button --}}
                                    <button 
                                        type="button" 
                                        onclick="openEditLessonModal({{ $video->id }}, '{{ addslashes($video->title_ar) }}', {{ $video->duration_minutes }}, '{{ addslashes($video->effective_playback_url) }}', '{{ $video->mux_playback_id }}', '{{ addslashes($video->clinicalTask?->title_ar ?? '') }}', {{ $video->level_id }})" 
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
                            <div class="p-4 text-center text-xs text-slate-400 bg-white/60 dark:bg-slate-800/40 rounded-xl border border-dashed border-[#DEE8F7] dark:border-slate-700">
                                {{ __('لا توجد دروس مضافة لهذا المستوى حتى الآن. اضغط على "+ إضافة درس" للبدء في رفع الفيديوهات.') }}
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

    <x-slot:modals>
        <!-- Modal 1: Add New Lesson / Video (MUX or Link) -->
        <div id="modal-add-lesson" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-xl w-full p-5 sm:p-7 flex flex-col gap-4 sm:gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-3.5">
                    <div>
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white">{{ __('إضافة فيديو ودرس جديد') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ __('المستوى المستهدف:') }} <span id="add-lesson-level-label" class="font-bold text-[#0B2A8F] dark:text-blue-400"></span>
                        </p>
                    </div>
                    <button type="button" onclick="closeModal('modal-add-lesson')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('admin.courses.videos.store', $course) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="level_id" id="add-lesson-level-id" value="" />

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('عنوان الفيديو / الدرس:') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="title_ar" required placeholder="مثال: الدرس ٤: تمارين تقوية عضلات النطق" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('مدة الفيديو (بالدقائق):') }} <span class="text-rose-500">*</span></label>
                            <input type="number" name="duration_minutes" required min="1" max="300" value="20" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('نسبة إكمال المشاهدة المطلوبة (%):') }}</label>
                            <input type="number" name="min_completion_percentage" min="1" max="100" value="80" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                        </div>
                    </div>

                    {{-- Video Source Tabs (Upload to MUX vs Direct Link) --}}
                    <div class="p-3.5 rounded-2xl bg-blue-50/60 dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700 space-y-3" x-data="{ sourceMode: 'mux_upload' }">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-[#012383] dark:text-blue-400 block flex items-center gap-1.5">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>{{ __('مصدر الفيديو (رفع عبر MUX أو عبر رابط):') }}</span>
                            </label>
                            
                            {{-- Toggle pill --}}
                            <div class="flex items-center gap-1 bg-white dark:bg-slate-900 p-1 rounded-xl border border-[#DEE8F7] dark:border-slate-700">
                                <button 
                                    type="button" 
                                    @click="sourceMode = 'mux_upload'" 
                                    :class="sourceMode === 'mux_upload' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383]'" 
                                    class="px-2.5 py-1 text-[11px] font-bold rounded-lg transition-all cursor-pointer"
                                >
                                    {{ __('رفع إلى MUX') }}
                                </button>
                                <button 
                                    type="button" 
                                    @click="sourceMode = 'link'" 
                                    :class="sourceMode === 'link' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383]'" 
                                    class="px-2.5 py-1 text-[11px] font-bold rounded-lg transition-all cursor-pointer"
                                >
                                    {{ __('عبر رابط / Mux ID') }}
                                </button>
                            </div>
                        </div>

                        {{-- Mode 1: File upload to MUX --}}
                        <div x-show="sourceMode === 'mux_upload'" class="space-y-1.5">
                            <span class="text-[11px] text-slate-500 font-medium block">
                                {{ __('اختر ملف الفيديو من جهازك ليتم رفعه مباشرة عبر المنصة ومعالجته على منصة MUX:') }}
                            </span>
                            <input 
                                type="file" 
                                name="video_file" 
                                accept="video/mp4,video/quicktime,video/webm" 
                                class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#012383] file:text-white hover:file:bg-[#0B2A8F] cursor-pointer" 
                            />
                        </div>

                        {{-- Mode 2: Link or Mux Playback ID --}}
                        <div x-show="sourceMode === 'link'" class="space-y-1.5" style="display: none;">
                            <span class="text-[11px] text-slate-500 font-medium block">
                                {{ __('أدخل معرّف بث MUX (Playback ID) أو رابط Mux .m3u8 أو YouTube أو Vimeo:') }}
                            </span>
                            <input 
                                type="text" 
                                name="video_url" 
                                placeholder="مثال: https://stream.mux.com/XXXXX.m3u8 أو معرّف MUX أو رابط YouTube" 
                                class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-white dark:bg-slate-800" 
                            />
                        </div>
                    </div>

                    {{-- Attached Clinical Task --}}
                    <div class="border-t border-[#DEE8F7] dark:border-slate-800 pt-3">
                        <label class="text-xs font-bold text-[#012383] dark:text-blue-400 block mb-1">
                            {{ __('المهمة السريرية الملحقة بالفيديو (تفتح بعد إتمام المشاهدة):') }}
                        </label>
                        <input 
                            type="text" 
                            name="task_title_ar" 
                            placeholder="عنوان المهمة (مثال: إعداد خطة تمارين عملية وتقييم مخارج الحروف)" 
                            class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                        />
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_free_preview" id="new-lesson-preview" value="1" class="size-4 rounded border-slate-300 text-[#012383] focus:ring-[#012383] cursor-pointer" />
                        <label for="new-lesson-preview" class="text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer">
                            {{ __('إتاحة هذا الدرس كمعاينة مجانية للزوار قبل الاشتراك') }}
                        </label>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-2 border-t border-[#DEE8F7] dark:border-slate-800">
                        <button type="button" onclick="closeModal('modal-add-lesson')" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm cursor-pointer">
                            {{ __('حفظ وإضافة الدرس') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal 2: Edit Lesson -->
        <div id="modal-edit-lesson" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-xl w-full p-5 sm:p-7 flex flex-col gap-4 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-3.5">
                    <div>
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-[#012383] dark:text-white">{{ __('تعديل بيانات الدرس / الفيديو') }}</h3>
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('المدة (بالدقائق):') }}</label>
                            <input type="number" name="duration_minutes" id="edit-lesson-duration" required min="1" max="300" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
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
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('رابط الفيديو أو معرّف بث MUX:') }}</label>
                        <input type="text" name="video_url" id="edit-lesson-url" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('أو استبدال الفيديو برفع ملف جديد إلى MUX:') }}</label>
                        <input type="file" name="video_file" accept="video/mp4,video/quicktime,video/webm" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#012383] file:text-white hover:file:bg-[#0B2A8F] cursor-pointer" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-[#012383] dark:text-blue-400 block mb-1">{{ __('المهمة السريرية الملحقة:') }}</label>
                        <input type="text" name="task_title_ar" id="edit-lesson-task" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
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
    </x-slot:modals>

    <script>
        function openModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.remove('modal-hidden');
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.add('modal-hidden');
        }

        function openAddLessonModal(levelId, levelTitle) {
            document.getElementById('add-lesson-level-id').value = levelId;
            document.getElementById('add-lesson-level-label').textContent = levelTitle;
            openModal('modal-add-lesson');
        }

        function openEditLevelModal(levelId, title, desc, sortOrder) {
            const form = document.getElementById('form-edit-level');
            form.action = `/admin/courses/{{ $course->id }}/levels/${levelId}`;
            document.getElementById('edit-level-title').value = title;
            document.getElementById('edit-level-desc').value = desc;
            openModal('modal-edit-level');
        }

        function openEditLessonModal(videoId, title, durationMinutes, url, muxId, taskTitle, levelId) {
            const form = document.getElementById('form-edit-lesson');
            form.action = `/admin/courses/{{ $course->id }}/videos/${videoId}`;
            document.getElementById('edit-lesson-title').value = title;
            document.getElementById('edit-lesson-duration').value = durationMinutes;
            document.getElementById('edit-lesson-url').value = muxId || url;
            document.getElementById('edit-lesson-task').value = taskTitle;
            if (document.getElementById('edit-lesson-level')) {
                document.getElementById('edit-lesson-level').value = levelId;
            }
            openModal('modal-edit-lesson');
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
