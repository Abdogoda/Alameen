<x-layouts::app :title="__('بوابة الطالب الأكاديمية')">

    <!-- ============================================== -->
    <!-- STUDENT WORKSPACE (بوابة الطالب الأكاديمية)     -->
    <!-- ============================================== -->
    <div class="flex flex-col gap-6 sm:gap-8 w-full text-start">
        
        @if(auth('admin')->check())
            <div class="bg-gradient-to-r from-[#012383] to-[#0B2A8F] text-white p-4 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm border border-blue-900">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 bg-[#F2AE2E] text-slate-900 font-extrabold text-xs rounded-lg">{{ __('وضع المسؤول') }}</span>
                    <span class="text-xs sm:text-sm font-semibold text-white/90">{{ __('أنت مسجّل كمسؤول في النظام.') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('log-viewer.index') }}" target="_blank" class="px-3.5 py-2 bg-white/20 hover:bg-white/30 rounded-xl text-xs font-bold text-white transition-colors flex items-center gap-1.5 border border-white/20">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ __('سجلات النظام (Log Viewer)') }} ↗</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 bg-white text-[#012383] hover:bg-slate-50 rounded-xl text-xs font-bold transition-colors">
                        {{ __('لوحة الإدارة') }}
                    </a>
                </div>
            </div>
        @endif

        <!-- 1. Page Header Introduction -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 w-full">
            <div class="flex flex-col gap-1 items-start text-start">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300">
                        {{ __('مساحة التعلّم / لوحة الطالب') }}
                    </span>
                    <span class="text-xs text-slate-400 dark:text-slate-500">•</span>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                        <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ __('حساب نشط ومسار سريري معتمد') }}
                    </span>
                </div>
                <h1 class="font-['Baloo_Bhaijaan_2'] font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#012383] dark:text-white leading-tight">
                    {{ __('أهلًا بعودتك، :name', ['name' => auth()->user()->name]) }} 👋
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-2xl">
                    {{ __('كورساتك ومهامك في مكان واحد. تابع من حيث توقّفت، بخطوة واضحة نحو الإتقان السريري.') }}
                </p>
            </div>

            <!-- Header Quick Action -->
            <div class="flex items-center gap-2.5 self-start md:self-auto shrink-0">
                <a
                    href="{{ route('courses.index') }}"
                    class="h-10.5 px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#012383] dark:hover:border-blue-400 text-[#012383] dark:text-blue-300 font-bold text-xs shadow-xs hover:shadow-sm transition-all flex items-center gap-2"
                    wire:navigate
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>{{ __('دليل جميع الدورات') }}</span>
                </a>
            </div>
        </div>

        <!-- 2. Academic Learning Stats Bar (4 Metric Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 w-full">
            
            <!-- Stat 1: Enrolled Courses -->
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs hover:shadow-card hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('الدورات المسجلة') }}</span>
                    <div class="size-10 rounded-xl bg-[#EAF1FB] dark:bg-blue-950/60 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-3xl text-[#012383] dark:text-white">
                        {{ max($stats['total_enrolled'] ?? 0, ($isDemo ?? false) ? 1 : 0) }}
                    </span>
                    <span class="text-xs text-slate-400 font-semibold">{{ __('برامج تدريبية') }}</span>
                </div>
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <span class="size-2 rounded-full bg-[#0B2A8F]"></span>
                        <span>{{ max($stats['in_progress_courses'] ?? 0, ($isDemo ?? false) ? 1 : 0) }} {{ __('قيد التعلّم') }}</span>
                    </span>
                    <span class="font-bold text-[#0B2A8F] dark:text-blue-400">{{ $stats['completed_courses'] ?? 0 }} {{ __('مكتملة') }}</span>
                </div>
            </div>

            <!-- Stat 2: Completed Lessons -->
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs hover:shadow-card hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('المحاضرات المنجزة') }}</span>
                    <div class="size-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-3xl text-emerald-600 dark:text-emerald-400">
                        {{ ($isDemo ?? false) && ($stats['completed_lessons'] ?? 0) === 0 ? 8 : ($stats['completed_lessons'] ?? 0) }}
                    </span>
                    <span class="text-xs text-slate-400 font-semibold">{{ __('محاضرة تدريبية') }}</span>
                </div>
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>{{ __('إجمالي الدروس:') }}</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200">{{ max($stats['total_lessons'] ?? 0, 20) }} {{ __('محاضرة') }}</span>
                </div>
            </div>

            <!-- Stat 3: Average Progress -->
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs hover:shadow-card hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('متوسط الإنجاز العام') }}</span>
                    <div class="size-10 rounded-xl bg-[#EAF1FB] dark:bg-blue-950/60 text-[#0B2A8F] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                @php
                    $displayAvg = ($isDemo ?? false) && ($stats['avg_progress'] ?? 0) == 0 ? 42 : ($stats['avg_progress'] ?? 0);
                @endphp
                <div class="flex items-baseline gap-2">
                    <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-3xl text-[#012383] dark:text-white">
                        {{ $displayAvg }}%
                    </span>
                    <span class="text-xs text-emerald-600 font-bold">
                        {{ $displayAvg >= 80 ? __('متقدم ممتاز') : ($displayAvg > 0 ? __('تقدّم مستمر') : __('بداية المسار')) }}
                    </span>
                </div>
                <!-- Mini Progress Bar -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800/80 flex flex-col gap-1.5 w-full">
                    <div class="h-2 rounded-full bg-[#EAF1FB] dark:bg-slate-800 overflow-hidden w-full">
                        <div class="h-full bg-gradient-to-r from-[#012383] to-emerald-500 rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $displayAvg)) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Stat 4: Certificates & Clinical Tasks -->
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs hover:shadow-card hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('الشهادات والمهام') }}</span>
                    <div class="size-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-3xl text-amber-600 dark:text-amber-400">
                        {{ $stats['certificates_count'] ?? 0 }}
                    </span>
                    <span class="text-xs text-slate-400 font-semibold">{{ __('شهادة معتمدة') }}</span>
                </div>
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>{{ __('المهام السريرية:') }}</span>
                    <span class="font-bold text-[#012383] dark:text-blue-300">{{ $stats['tasks_count'] ?? 0 }} {{ __('مسلّمة') }}</span>
                </div>
            </div>

        </div>

        <!-- 3. Resume Learning Split-Card (Hero Card) -->
        @php
            $heroCourse = $lastWatchedCourse ?? ($enrolledCourses->first() ?? null);
            $heroVideo = $lastWatchedVideo ?? ($heroCourse?->videos?->first() ?? null);
            $heroCourseTitle = $heroCourse ? (app()->getLocale() === 'en' && $heroCourse->title_en ? $heroCourse->title_en : $heroCourse->title_ar) : __('أساسيات التخاطب / المستوى الثاني / الدرس ٩');
            $heroVideoTitle = $heroVideo ? (app()->getLocale() === 'en' && $heroVideo->title_en ? $heroVideo->title_en : $heroVideo->title_ar) : __('تدريبات الوعي الصوتي ونطق الحروف');
            $heroProgress = $lastWatchedOverall ? ($lastWatchedOverall->completion_percentage ?? 40) : (($isDemo ?? false) ? 40 : 80);
            $heroThumbnail = $heroVideo?->thumbnail ?: ($heroCourse?->thumbnail ?: '/assets/images/video-preview.png');
            $resumeUrl = ($heroCourse && $heroVideo)
                ? route('lessons.show', ['course' => $heroCourse->slug, 'video' => $heroVideo->id])
                : ($heroCourse ? route('lessons.show', ['course' => $heroCourse->slug]) : route('courses.index'));
        @endphp

        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs hover:shadow-card transition-all flex flex-col-reverse lg:flex-row items-stretch gap-6 w-full relative overflow-hidden group">
            
            <!-- Content Column (On Right in RTL, Left in LTR) -->
            <div class="flex-1 flex flex-col justify-between gap-4 text-start min-w-0">
                
                <div class="flex flex-col gap-2 items-start text-start w-full">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-3 py-1 rounded-full bg-[#EAF1FB] dark:bg-blue-950/70 text-[#0B2A8F] dark:text-blue-300 text-xs font-bold border border-[#DEE8F7] dark:border-blue-900/40">
                            {{ __('واصل تعلّمك • آخر موضع محفوظ') }}
                        </span>
                        <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ __('قيد التقدم') }}</span>
                    </div>

                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl lg:text-3xl text-[#012383] dark:text-white text-start w-full mt-1 leading-snug">
                        {{ $heroVideoTitle }}
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 text-start w-full">
                        {{ $heroCourseTitle }}
                    </p>
                </div>

                <!-- Progress Track -->
                <div class="flex flex-col gap-2 w-full mt-auto pt-3">
                    <div class="flex items-center justify-between text-xs w-full">
                        <span class="font-bold text-[#0B2A8F] dark:text-blue-300">
                            {{ __('٠٨:٠٠ مكتمل') }} • {{ $heroProgress }}% {{ __('مكتمل') }}
                        </span>
                        <span class="font-semibold text-slate-500 dark:text-slate-400">
                            {{ $heroProgress }}% {{ __('من الدرس') }}
                        </span>
                    </div>

                    <div class="bg-[#DEE8F7] dark:bg-slate-800 h-2.5 rounded-full w-full overflow-hidden relative">
                        <div class="bg-gradient-to-r from-[#0B2A8F] to-[#012383] h-full rounded-full transition-all duration-500" style="width: {{ max(10, min(100, (int) $heroProgress)) }}%"></div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex items-center gap-3 pt-2">
                    <a
                        href="{{ $resumeUrl }}"
                        class="h-12 px-7 rounded-2xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-['Cairo'] font-bold text-sm shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer active:scale-[0.99] flex items-center justify-center gap-2.5"
                        wire:navigate
                    >
                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        <span>{{ __('أكمل المشاهدة الآن') }}</span>
                    </a>

                    <a
                        href="{{ route('courses.index') }}"
                        class="h-12 px-5 rounded-2xl bg-[#EAF1FB] dark:bg-slate-800 hover:bg-[#012383] text-[#012383] dark:text-blue-300 hover:text-white dark:hover:text-white font-bold text-xs transition-colors flex items-center justify-center gap-1.5"
                        wire:navigate
                    >
                        <span>{{ __('دوراتي ومحاضراتي') }}</span>
                        <span class="rtl:rotate-180">←</span>
                    </a>
                </div>

            </div>

            <!-- Video Preview Box (On Left in RTL, Right in LTR) -->
            <div class="w-full lg:w-[400px] xl:w-[450px] h-[210px] sm:h-[240px] lg:h-auto rounded-2xl overflow-hidden relative shrink-0 shadow-sm bg-slate-900 group/vid">
                <img
                    src="{{ $heroThumbnail }}"
                    alt="{{ $heroVideoTitle }}"
                    class="size-full object-cover pointer-events-none group-hover/vid:scale-105 transition-transform duration-500"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                
                <!-- Center Play Button with direct link -->
                <a
                    href="{{ $resumeUrl }}"
                    class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 size-16 rounded-full bg-white/95 hover:bg-white text-[#012383] shadow-xl flex items-center justify-center cursor-pointer z-10 group-hover/vid:scale-110 active:scale-95 transition-all"
                    title="{{ __('مشاهدة المحاضرة الآن') }}"
                    wire:navigate
                >
                    <svg class="size-7 translate-x-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z" />
                    </svg>
                </a>

                <!-- Duration Badge -->
                <div class="absolute top-3 end-3 bg-[#012383]/90 backdrop-blur-md border border-white/20 rounded-full px-3 py-1 flex items-center gap-1.5 text-xs text-white z-10 shadow-sm font-bold">
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $heroVideo?->duration ? gmdate('i:s', $heroVideo->duration) . ' ' . __('دقيقة') : '٢٠:٠٠ دقيقة' }}</span>
                </div>

                <!-- Level Badge -->
                <div class="absolute bottom-3 start-3 bg-black/60 backdrop-blur-md rounded-lg px-2.5 py-1 text-[11px] text-white font-bold z-10">
                    {{ $heroCourse?->level ?? __('برنامج تدريبي سريري') }}
                </div>
            </div>

        </div>

        <!-- 4. Enrolled Courses Section -->
        <div class="flex flex-col gap-4 w-full">
            <div class="flex items-center justify-between w-full border-b border-[#DEE8F7] dark:border-slate-800 pb-3">
                <div class="flex items-center gap-2.5">
                    <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-[#012383] dark:text-white">
                        {{ __('كورساتي المسجّلة في البرنامج') }}
                    </h2>
                    <span class="text-xs font-bold text-[#0B2A8F] dark:text-blue-300 bg-[#EAF1FB] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 px-3 py-0.5 rounded-full">
                        {{ count($myCoursesData) > 0 ? count($myCoursesData) : (($isDemo ?? false) ? 1 : 0) }} {{ __('كورس مسجّل') }}
                    </span>
                </div>

                <a
                    href="{{ route('courses.index') }}"
                    class="text-xs font-bold text-[#0B2A8F] dark:text-blue-400 hover:underline flex items-center gap-1"
                    wire:navigate
                >
                    <span>{{ __('إدارة ومتابعة الكل') }}</span>
                    <span class="rtl:rotate-180">←</span>
                </a>
            </div>

            <!-- Full-width Responsive Grid without max-w-md bug -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 w-full">
                @if(count($myCoursesData) > 0)
                    @foreach($myCoursesData as $item)
                        @php
                            $c = $item['course'];
                            $title = app()->getLocale() === 'en' && $c->title_en ? $c->title_en : $c->title_ar;
                            $desc = app()->getLocale() === 'en' && $c->description_en ? $c->description_en : $c->description_ar;
                            $progress = (int) $item['progress'];
                            $isComplete = $progress >= 100;
                            $isInProgress = $progress > 0 && ! $isComplete;
                            $resumeVid = $item['resume_video'];
                        @endphp

                        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group">
                            <div>
                                <!-- Course Thumbnail & Status Badge -->
                                <div class="h-44 rounded-t-2xl overflow-hidden relative w-full bg-[#012383] shrink-0">
                                    <img
                                        src="{{ $c->thumbnail ?: '/assets/images/course-topic-basics.png' }}"
                                        alt="{{ $title }}"
                                        class="size-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>

                                    <!-- Status Badge -->
                                    <div class="absolute top-3 end-3 z-10">
                                        @if($isComplete)
                                            <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white font-extrabold text-[11px] shadow-sm flex items-center gap-1">
                                                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>{{ __('مكتمل ١٠٠٪') }}</span>
                                            </span>
                                        @elseif($isInProgress)
                                            <span class="px-2.5 py-1 rounded-full bg-[#012383]/90 backdrop-blur-md text-white font-bold text-[11px] shadow-sm flex items-center gap-1.5 border border-white/20">
                                                <span class="size-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                <span>{{ __('قيد المشاهدة') }}</span>
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-white font-bold text-[11px] shadow-sm">
                                                {{ __('جديد لم يبدأ') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Lessons count in thumbnail footer -->
                                    <div class="absolute bottom-2.5 inset-x-3 flex items-center justify-between text-white text-[11px] font-semibold z-10">
                                        <span class="px-2 py-0.5 rounded-md bg-black/50 backdrop-blur-xs">
                                            {{ $item['total_lessons'] }} {{ __('محاضرة تدريبية') }}
                                        </span>
                                        @if($item['certificate'])
                                            <span class="px-2 py-0.5 rounded-md bg-amber-400 text-slate-950 font-black text-[10px] shadow-sm flex items-center gap-1">
                                                <span>🎓</span>
                                                <span>{{ __('شهادة صادرة') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Card Body -->
                                <div class="p-4 sm:p-5 flex flex-col gap-2 text-start w-full">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] font-bold text-[#0B2A8F] dark:text-blue-300 px-2 py-0.5 rounded bg-[#EAF1FB] dark:bg-slate-800">
                                            {{ $c->level ?? __('برنامج تدريبي سريري متكامل') }}
                                        </span>
                                    </div>

                                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-[#012383] dark:text-white leading-snug line-clamp-1 group-hover:text-[#0B2A8F] dark:group-hover:text-blue-300 transition-colors">
                                        {{ $title }}
                                    </h3>

                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                        {{ $desc ?: __('برنامج تدريبي سريري متكامل') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Card Footer: Progress & Action -->
                            <div class="p-4 sm:p-5 pt-0 flex flex-col gap-3 w-full">
                                
                                <!-- Progress Bar -->
                                <div class="flex flex-col gap-1.5 w-full pt-2 border-t border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-slate-500 dark:text-slate-400 font-medium">
                                            {{ __('تقدّم المشاهدة') }}
                                        </span>
                                        <span class="font-bold {{ $isComplete ? 'text-emerald-600 dark:text-emerald-400' : 'text-[#012383] dark:text-blue-300' }}">
                                            {{ $progress }}%
                                        </span>
                                    </div>
                                    <div class="bg-[#DEE8F7] dark:bg-slate-800 h-2 rounded-full overflow-hidden w-full">
                                        <div class="h-full {{ $isComplete ? 'bg-emerald-500' : 'bg-gradient-to-r from-[#012383] to-[#0B2A8F]' }} rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="flex items-center gap-2 w-full pt-1">
                                    <a
                                        href="{{ route('lessons.show', ['course' => $c->slug, 'video' => $resumeVid?->id]) }}"
                                        class="flex-1 h-10 bg-[#012383] hover:bg-[#0B2A8F] text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5"
                                        wire:navigate
                                    >
                                        <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        <span>{{ $isComplete ? __('مراجعة المحاضرات') : ($progress > 0 ? __('متابعة الكورس') : __('بدء التعلّم')) }}</span>
                                    </a>

                                    @if($item['certificate'])
                                        <a
                                            href="{{ route('certificates.show', ['certificate' => $item['certificate']->id]) }}"
                                            class="h-10 px-3 bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 hover:bg-amber-100 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-1"
                                            title="{{ __('عرض الشهادة المعتمدة') }}"
                                            wire:navigate
                                        >
                                            <span>🎓</span>
                                            <span>{{ __('الشهادة') }}</span>
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Demo Card for new students -->
                    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group">
                        <div>
                            <div class="h-44 rounded-t-2xl overflow-hidden relative w-full bg-[#012383] shrink-0">
                                <img
                                    src="/assets/images/course-topic-basics.png"
                                    alt="دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة"
                                    class="size-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>

                                <div class="absolute top-3 end-3 z-10">
                                    <span class="px-2.5 py-1 rounded-full bg-[#012383]/90 backdrop-blur-md text-white font-bold text-[11px] shadow-sm flex items-center gap-1.5 border border-white/20">
                                        <span class="size-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <span>{{ __('قيد المشاهدة') }}</span>
                                    </span>
                                </div>

                                <div class="absolute bottom-2.5 inset-x-3 flex items-center justify-between text-white text-[11px] font-semibold z-10">
                                    <span class="px-2 py-0.5 rounded-md bg-black/50 backdrop-blur-xs">
                                        {{ __('٢٠ محاضرة تدريبية') }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 sm:p-5 flex flex-col gap-2 text-start w-full">
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] font-bold text-[#0B2A8F] dark:text-blue-300 px-2 py-0.5 rounded bg-[#EAF1FB] dark:bg-slate-800">
                                        {{ __('برنامج تدريبي سريري متكامل') }}
                                    </span>
                                </div>

                                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-[#012383] dark:text-white leading-snug line-clamp-1 group-hover:text-[#0B2A8F] transition-colors">
                                    {{ __('دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة') }}
                                </h3>

                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                    {{ __('برنامج تطبيقي سريري معتمد يشمل تدريبات التخاطب واضطرابات النطق والكلام.') }}
                                </p>
                            </div>
                        </div>

                        <div class="p-4 sm:p-5 pt-0 flex flex-col gap-3 w-full">
                            <div class="flex flex-col gap-1.5 w-full pt-2 border-t border-slate-100 dark:border-slate-800">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium">{{ __('تقدّم المشاهدة') }}</span>
                                    <span class="font-bold text-[#012383] dark:text-blue-300">٤٢٪</span>
                                </div>
                                <div class="bg-[#DEE8F7] dark:bg-slate-800 h-2 rounded-full overflow-hidden w-full">
                                    <div class="h-full bg-gradient-to-r from-[#012383] to-[#0B2A8F] rounded-full transition-all duration-500" style="width: 42%"></div>
                                </div>
                            </div>

                            <div class="pt-1">
                                <a
                                    href="{{ route('courses.index') }}"
                                    class="w-full h-10 bg-[#012383] hover:bg-[#0B2A8F] text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5"
                                    wire:navigate
                                >
                                    <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    <span>{{ __('متابعة الكورس') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- 5. Quick Links / Student Services Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 w-full pt-2">
            
            <a
                href="{{ route('assignments.index') }}"
                class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 hover:border-[#012383] dark:hover:border-blue-400 rounded-2xl p-4 shadow-xs hover:shadow-card hover:-translate-y-0.5 transition-all flex flex-col items-center text-center gap-2 group"
                wire:navigate
            >
                <div class="size-11 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-[#012383] dark:text-blue-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <span class="font-bold text-xs text-[#012383] dark:text-white">{{ __('المهام السريرية') }}</span>
                <span class="text-[11px] text-slate-400">{{ __('التسجيلات والتقييمات') }}</span>
            </a>

            <a
                href="{{ route('certificates.index') }}"
                class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 hover:border-[#012383] dark:hover:border-blue-400 rounded-2xl p-4 shadow-xs hover:shadow-card hover:-translate-y-0.5 transition-all flex flex-col items-center text-center gap-2 group"
                wire:navigate
            >
                <div class="size-11 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <span class="font-bold text-xs text-[#012383] dark:text-white">{{ __('شهاداتي المعتمدة') }}</span>
                <span class="text-[11px] text-slate-400">{{ __('طباعة وتحميل PDF') }}</span>
            </a>

            <a
                href="{{ route('notifications.index') }}"
                class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 hover:border-[#012383] dark:hover:border-blue-400 rounded-2xl p-4 shadow-xs hover:shadow-card hover:-translate-y-0.5 transition-all flex flex-col items-center text-center gap-2 group"
                wire:navigate
            >
                <div class="size-11 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <span class="font-bold text-xs text-[#012383] dark:text-white">{{ __('صندوق الإشعارات') }}</span>
                <span class="text-[11px] text-slate-400">{{ __('تنبيهات الدروس والمهام') }}</span>
            </a>

            <a
                href="{{ route('support.index') }}"
                class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 hover:border-[#012383] dark:hover:border-blue-400 rounded-2xl p-4 shadow-xs hover:shadow-card hover:-translate-y-0.5 transition-all flex flex-col items-center text-center gap-2 group"
                wire:navigate
            >
                <div class="size-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <span class="font-bold text-xs text-[#012383] dark:text-white">{{ __('الدعم والرسائل') }}</span>
                <span class="text-[11px] text-slate-400">{{ __('تواصل مباشر مع الإشراف') }}</span>
            </a>

        </div>

        <!-- 6. Suggested Programs (Displays when few courses enrolled) -->
        @if(isset($suggestedCourses) && $suggestedCourses->isNotEmpty())
            <div class="flex flex-col gap-4 w-full pt-4 border-t border-[#DEE8F7] dark:border-slate-800">
                <div class="flex items-center justify-between w-full">
                    <div class="flex flex-col gap-0.5">
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white">
                            {{ __('برامج مقترحة لتوسيع مسارك السريري') }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ __('دورات متخصصة تدعم مهاراتك التشخيصية والعلاجية في مجال التخاطب.') }}
                        </p>
                    </div>
                    <a href="{{ route('courses.index') }}" class="text-xs font-bold text-[#0B2A8F] dark:text-blue-400 hover:underline shrink-0" wire:navigate>
                        {{ __('عرض الدليل الكامل') }} ←
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full">
                    @foreach($suggestedCourses as $sc)
                        @php
                            $sTitle = app()->getLocale() === 'en' && $sc->title_en ? $sc->title_en : $sc->title_ar;
                            $sDesc = app()->getLocale() === 'en' && $sc->description_en ? $sc->description_en : $sc->description_ar;
                        @endphp
                        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between gap-3 group">
                            <div class="flex gap-3 items-center">
                                <div class="size-16 rounded-xl overflow-hidden shrink-0 bg-[#012383] relative">
                                    <img src="{{ $sc->thumbnail ?: '/assets/images/course-topic-basics.png' }}" alt="{{ $sTitle }}" class="size-full object-cover group-hover:scale-110 transition-transform duration-300" />
                                </div>
                                <div class="flex flex-col gap-0.5 min-w-0 flex-1">
                                    <span class="text-[10px] font-bold text-[#0B2A8F] dark:text-blue-300">{{ $sc->level ?? __('برنامج معتمد') }}</span>
                                    <h4 class="font-bold text-xs text-[#012383] dark:text-white line-clamp-1 group-hover:text-[#0B2A8F] transition-colors">{{ $sTitle }}</h4>
                                    <span class="text-[11px] text-slate-400">{{ $sc->videos_count }} {{ __('محاضرة تدريبية') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
                                <span class="font-bold text-sm text-[#012383] dark:text-white">{{ number_format((float) $sc->price, 0) }} {{ __('ج.م') }}</span>
                                <a href="{{ route('courses.show') }}?course={{ $sc->slug }}" class="h-8 px-3 rounded-lg bg-[#EAF1FB] dark:bg-slate-800 hover:bg-[#012383] text-[#012383] dark:text-blue-300 hover:text-white text-xs font-bold transition-all flex items-center gap-1">
                                    <span>{{ __('تفاصيل') }}</span>
                                    <span class="text-[10px]">↗</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</x-layouts::app>
