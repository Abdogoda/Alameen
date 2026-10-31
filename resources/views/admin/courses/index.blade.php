<x-admin-layout::admin
    :title="__('دليل وإدارة الكورسات التدريبية')"
    :description="__('عرض شامل لكافة الكورسات، إنشاء دورات جديدة، وتخصيص المناهج والمستويات')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-6 max-w-[1400px] w-full mx-auto min-w-0"
>
    <x-slot:actions>
        <button 
            type="button" 
            onclick="openModal('modal-add-course')" 
            class="group relative h-10 sm:h-11 px-4 sm:px-5 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-bold text-xs sm:text-sm shadow-sm hover:shadow-lg hover:shadow-blue-900/30 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer w-full sm:w-auto"
        >
            <svg class="size-4 group-hover:rotate-90 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>{{ __('إضافة كورس جديد') }}</span>
        </button>
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

    {{-- Stats Overview Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('إجمالي الكورسات') }}</span>
                <span class="text-xl sm:text-2xl font-black text-[#012383] dark:text-white">{{ $courses->count() }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('إجمالي المستويات') }}</span>
                <span class="text-xl sm:text-2xl font-black text-purple-700 dark:text-purple-300">{{ $courses->sum('levels_count') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('إجمالي الدروس') }}</span>
                <span class="text-xl sm:text-2xl font-black text-emerald-700 dark:text-emerald-400">{{ $courses->sum('videos_count') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('المتدربون المسجلون') }}</span>
                <span class="text-xl sm:text-2xl font-black text-amber-700 dark:text-amber-300">{{ $courses->sum('users_count') }}</span>
            </div>
        </div>
    </div>

    {{-- Courses Grid --}}
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between gap-2 flex-wrap">
            <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white">{{ __('قائمة الكورسات والبرامج التدريبية المتاحة') }}</h3>
            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-800 px-3 py-1 rounded-full">
                {{ __('عدد الكورسات: :count', ['count' => $courses->count()]) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @forelse($courses as $course)
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-xs hover:shadow-card hover:border-[#0B2A8F]/40 transition-all flex flex-col justify-between gap-5">
                    
                    {{-- Top Header --}}
                    <div class="flex items-start gap-4">
                        <div class="size-20 sm:size-24 rounded-2xl bg-[#012383] overflow-hidden shrink-0 border border-[#DEE8F7] dark:border-slate-700 shadow-xs relative">
                            <img src="{{ $course->thumbnail ?: '/assets/images/course-topic-basics.png' }}" alt="{{ $course->title_ar }}" class="size-full object-cover" />
                            @if($course->promo_video_source === 'mux' || !empty($course->mux_playback_id))
                                <span class="absolute bottom-1 end-1 bg-black/70 backdrop-blur-xs text-white text-[10px] font-bold px-1.5 py-0.5 rounded-md flex items-center gap-0.5">
                                    <span class="size-1.5 rounded-full bg-emerald-400"></span> MUX
                                </span>
                            @endif
                        </div>

                        <div class="space-y-1.5 min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                @if($course->is_published)
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-xs font-bold font-['Cairo']">
                                        {{ __('نشط ومنشور') }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 text-xs font-bold font-['Cairo']">
                                        {{ __('مسودة غير منشورة') }}
                                    </span>
                                @endif
                                <span class="text-xs text-slate-400">•</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">صلاحية {{ $course->access_duration_days }} يوم</span>
                            </div>

                            <h2 class="font-['Cairo'] font-extrabold text-lg sm:text-xl text-[#012383] dark:text-white leading-snug truncate" title="{{ $course->title_ar }}">
                                {{ $course->title_ar }}
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $course->description_ar ?: __('لا يوجد وصف متاح حالياً لهذا الكورس.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Metrics and Price Strip --}}
                    <div class="bg-slate-50/80 dark:bg-slate-800/40 border border-[#DEE8F7]/80 dark:border-slate-800 rounded-2xl p-3 sm:p-3.5 flex items-center justify-between gap-3 flex-wrap">
                        <div class="flex items-center gap-3 sm:gap-4 text-xs font-bold text-slate-600 dark:text-slate-300">
                            <span class="flex items-center gap-1.5">
                                <span class="size-2 rounded-full bg-blue-600"></span>
                                {{ $course->levels_count }} {{ __('مستويات') }}
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1.5">
                                <span class="size-2 rounded-full bg-emerald-500"></span>
                                {{ $course->videos_count }} {{ __('درس') }}
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1.5">
                                <span class="size-2 rounded-full bg-purple-500"></span>
                                {{ $course->users_count }} {{ __('متدرب') }}
                            </span>
                        </div>

                        {{-- Price --}}
                        <div class="flex items-baseline gap-2">
                            <span class="text-base sm:text-lg font-black text-[#012383] dark:text-blue-400">
                                {{ number_format($course->price) }} ج.م
                            </span>
                            @if($course->original_price && $course->original_price > $course->price)
                                <span class="text-xs text-slate-400 line-through">
                                    {{ number_format($course->original_price) }} ج.م
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Bottom Action Buttons (Responsive Layout) --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5 pt-3 border-t border-[#DEE8F7]/70 dark:border-slate-800">
                        {{-- Preview & Edit Course Details --}}
                        <div class="grid grid-cols-1 sm:flex items-center gap-2 flex-1 min-w-0">
                            <a 
                                href="{{ route('admin.courses.show', $course) }}" 
                                class="h-9.5 px-3.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap text-center"
                            >
                                <svg class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>{{ __('عرض المنهج والمستويات') }}</span>
                            </a>

                            <a 
                                href="{{ route('admin.courses.edit', $course) }}" 
                                class="h-9.5 px-3.5 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-[#0B2A8F] dark:text-blue-300 font-bold text-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap text-center"
                            >
                                <svg class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                <span>{{ __('تعديل بيانات الكورس') }}</span>
                            </a>
                        </div>

                        {{-- Quick Delete Course --}}
                        <div class="flex items-center justify-end sm:justify-start">
                            <form 
                                action="{{ route('admin.courses.destroy', $course) }}" 
                                method="POST" 
                                onsubmit="return confirm('{{ __('هل أنت متأكد من رغبتك في حذف هذا الكورس وجميع مستوياته ودروسه نهائياً؟') }}')"
                                class="w-full sm:w-auto"
                            >
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="w-full sm:w-auto h-9 sm:h-9 px-3 text-slate-400 hover:text-rose-600 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-950/40 border border-slate-200/80 sm:border-transparent dark:border-slate-800 sm:dark:border-transparent transition-colors cursor-pointer flex items-center justify-center gap-1.5 text-xs font-semibold" 
                                    title="{{ __('حذف الكورس') }}"
                                >
                                    <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span class="sm:hidden text-rose-600 dark:text-rose-400">{{ __('حذف الكورس') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full p-12 bg-white dark:bg-slate-900 border border-dashed border-[#DEE8F7] dark:border-slate-800 rounded-3xl text-center space-y-3">
                    <div class="size-16 rounded-2xl bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-400 mx-auto flex items-center justify-center">
                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h4 class="font-bold text-lg text-[#012383] dark:text-white">{{ __('لا توجد كورسات مسجلة حالياً') }}</h4>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">{{ __('ابدأ بإنشاء أول دبلوم أو برنامج تدريبي وتوزيع المحتوى على مستويات ديناميكية.') }}</p>
                    <button type="button" onclick="openModal('modal-add-course')" class="px-5 py-2.5 rounded-xl bg-[#012383] text-white font-bold text-xs">
                        {{ __('إضافة أول كورس الآن') }}
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <x-slot:modals>
        <!-- Modal: Add New Course -->
        <div id="modal-add-course" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-xl w-full p-5 sm:p-7 flex flex-col gap-4 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="size-9 rounded-xl bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-400 flex items-center justify-center font-bold">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-[#012383] dark:text-white">{{ __('إنشاء كورس تدريبي جديد') }}</h3>
                            <p class="text-xs text-slate-400">{{ __('قم بإدخال بيانات الكورس، السعر، وخيارات الفيديو الترويجي (MUX أو رابط)') }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal('modal-add-course')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('عنوان الكورس بالعربية:') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="title_ar" required placeholder="مثال: دبلوم تأهيل اضطرابات طيف التوحد والتواصل" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('عنوان الكورس بالإنجليزية (اختياري):') }}</label>
                        <input type="text" name="title_en" placeholder="e.g. Diploma in Autism Spectrum & Communication Disorders" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('السعر الفعلي (ج.م):') }} <span class="text-rose-500">*</span></label>
                            <input type="number" name="price" required min="0" value="1200" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-blue-400 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('السعر الأصلي (قبل الخصم):') }}</label>
                            <input type="number" name="original_price" min="0" value="1800" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-600 dark:text-slate-300 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('مدة الصلاحية (أيام):') }}</label>
                            <input type="number" name="access_duration_days" required min="1" value="365" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('وصف الكورس بالعربية:') }}</label>
                        <textarea name="description_ar" rows="2" placeholder="نبذة عن البرنامج التدريبي ومخرجات التعلم والشهادة المعتمدة..." class="w-full p-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] resize-none bg-slate-50/50 dark:bg-slate-800/50"></textarea>
                    </div>

                    {{-- Promo Video Source Options (MUX vs Link) --}}
                    <div class="p-3.5 rounded-2xl bg-blue-50/50 dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700 space-y-2.5">
                        <label class="text-xs font-bold text-[#012383] dark:text-blue-400 block flex items-center gap-1.5">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span>{{ __('الفيديو التعريفي للكورس (معاينة عبر MUX أو أي مصدر):') }}</span>
                        </label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <div>
                                <span class="text-[11px] font-bold text-slate-500 block mb-1">{{ __('الخيار ١: رفع ملف فيديو عبر المنصة إلى MUX') }}</span>
                                <input type="file" name="promo_video_file" accept="video/mp4,video/quicktime,video/webm" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#012383] file:text-white hover:file:bg-[#0B2A8F] cursor-pointer" />
                            </div>
                            <div>
                                <span class="text-[11px] font-bold text-slate-500 block mb-1">{{ __('الخيار ٢: رابط MUX أو معرّف بث أو YouTube') }}</span>
                                <input type="text" name="promo_video_url" placeholder="https://stream.mux.com/... أو رابط فيديو" class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-white dark:bg-slate-800" />
                            </div>
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('صورة غلاف الكورس:') }}</label>
                        <input type="file" name="thumbnail_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#EAF1FB] file:text-[#012383] hover:file:bg-[#DEE8F7] cursor-pointer" />
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-2 border-t border-[#DEE8F7] dark:border-slate-800">
                        <button type="button" onclick="closeModal('modal-add-course')" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm cursor-pointer">
                            {{ __('إنشاء الكورس ومتابعة المنهج') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-slot:modals>
</x-admin-layout::admin>
