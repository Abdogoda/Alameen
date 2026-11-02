<x-layouts::app :title="__('دوراتي ومحاضراتي السريرية')">

    <div
        class="flex flex-col gap-6 w-full text-start"
        x-data="{
            activeTab: 'enrolled', // 'enrolled', 'in_progress', 'completed', 'catalog'
            searchQuery: '',
            matchesSearch(title, desc) {
                if (!this.searchQuery.trim()) return true;
                const q = this.searchQuery.toLowerCase().trim();
                return (title && title.toLowerCase().includes(q)) || (desc && desc.toLowerCase().includes(q));
            }
        }"
    >

        <!-- ============================================== -->
        <!-- 1. BREADCRUMBS & PAGE HEADER                   -->
        <!-- ============================================== -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div class="flex flex-col gap-1 items-start text-start">
                <nav class="flex items-center gap-2 text-xs font-semibold text-[#0B2A8F] dark:text-blue-400" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="hover:underline flex items-center gap-1" wire:navigate>
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>{{ __('لوحة الطالب') }}</span>
                    </a>
                    <span class="text-slate-300 dark:text-slate-600">/</span>
                    <span class="text-slate-500 dark:text-slate-400">{{ __('دوراتي ومحاضراتي السريرية') }}</span>
                </nav>

                <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-[#012383] dark:text-white leading-tight">
                    {{ __('دوراتي ومحاضراتي السريرية') }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-3xl">
                    {{ __('تابع تقدمك في البرامج المسجّلة، استكمل مشاهدة الدروس من آخر موضع، واطّلع على شهاداتك ونسب إتقانك السريري.') }}
                </p>
            </div>

            <!-- Header Quick Action -->
            <div class="flex items-center gap-2.5 shrink-0">
                <button
                    type="button"
                    @click="activeTab = 'catalog'"
                    class="h-10 px-4 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer flex items-center gap-2 shadow-xs border"
                    :class="activeTab === 'catalog' 
                        ? 'bg-[#012383] text-white border-[#012383]' 
                        : 'bg-white hover:bg-[#EAF1FB] text-[#012383] border-[#DEE8F7] dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700'"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>{{ __('استكشاف البرامج المتاحة') }}</span>
                </button>
                <a
                    href="{{ route('certificates.index') }}"
                    class="h-10 px-4 rounded-xl text-xs font-bold bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#0B2A8F] dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-blue-300 border border-[#C5D7F2] dark:border-slate-700 transition-colors flex items-center gap-1.5"
                    wire:navigate
                >
                    <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>{{ __('شهاداتي') }}</span>
                </a>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- 2. STATS OVERVIEW CARDS                        -->
        <!-- ============================================== -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4 w-full">
            
            <!-- Card 1: Enrolled Courses -->
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between gap-3 relative overflow-hidden group hover:border-[#0B2A8F]/30 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('الدورات المسجلة') }}</span>
                    <div class="size-9 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0 border border-[#DEE8F7] dark:border-slate-700">
                        <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-[#012383] dark:text-white leading-none">
                        {{ $stats['total_enrolled'] }}
                    </span>
                    <span class="text-xs font-semibold text-slate-400">{{ __('برنامج تدريبي') }}</span>
                </div>
            </div>

            <!-- Card 2: Completed Courses -->
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between gap-3 relative overflow-hidden group hover:border-emerald-300 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('الدورات المكتملة') }}</span>
                    <div class="size-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900">
                        <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-emerald-600 dark:text-emerald-400 leading-none">
                        {{ $stats['completed'] }}
                    </span>
                    <span class="text-xs font-semibold text-slate-400">/ {{ $stats['total_enrolled'] }} {{ __('مكتملة') }}</span>
                </div>
            </div>

            <!-- Card 3: Completed Lessons -->
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between gap-3 relative overflow-hidden group hover:border-indigo-300 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('الدروس المنجزة') }}</span>
                    <div class="size-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-100 dark:border-indigo-900">
                        <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-indigo-600 dark:text-indigo-400 leading-none">
                        {{ $stats['completed_lessons'] }}
                    </span>
                    <span class="text-xs font-semibold text-slate-400">{{ __('محاضرة تدريبية') }}</span>
                </div>
            </div>

            <!-- Card 4: Average Progress -->
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between gap-3 relative overflow-hidden group hover:border-[#012383]/40 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('متوسط الإنجاز العام') }}</span>
                    <div class="size-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#012383] dark:text-blue-300 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-900">
                        <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5 w-full">
                    <div class="flex items-baseline justify-between">
                        <span class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-[#012383] dark:text-white leading-none">
                            {{ $stats['avg_progress'] }}%
                        </span>
                        <span class="text-[11px] font-semibold text-slate-400">{{ __('نسبة الإتقان') }}</span>
                    </div>
                    <div class="h-2 w-full bg-[#DEE8F7] dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#0B2A8F] to-[#012383] dark:from-blue-500 dark:to-indigo-500 rounded-full transition-all duration-500" style="width: {{ $stats['avg_progress'] }}%;"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ============================================== -->
        <!-- 3. INTERACTIVE TABS & SEARCH CONTROLS          -->
        <!-- ============================================== -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-3 sm:p-3.5 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            
            <!-- Tab Buttons -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 scrollbar-none">
                
                <!-- Tab: All Enrolled -->
                <button
                    type="button"
                    @click="activeTab = 'enrolled'"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer whitespace-nowrap flex items-center gap-2 shadow-2xs"
                    :class="activeTab === 'enrolled' 
                        ? 'bg-[#012383] text-white shadow-xs' 
                        : 'text-slate-600 hover:text-[#012383] hover:bg-[#EAF1FB]/60 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'"
                >
                    <span>{{ __('دوراتي المسجّلة') }}</span>
                    <span
                        class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold"
                        :class="activeTab === 'enrolled' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'"
                    >
                        {{ $stats['total_enrolled'] }}
                    </span>
                </button>

                <!-- Tab: In Progress -->
                <button
                    type="button"
                    @click="activeTab = 'in_progress'"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer whitespace-nowrap flex items-center gap-2"
                    :class="activeTab === 'in_progress' 
                        ? 'bg-[#012383] text-white shadow-xs' 
                        : 'text-slate-600 hover:text-[#012383] hover:bg-[#EAF1FB]/60 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'"
                >
                    <span class="size-1.5 rounded-full bg-blue-500"></span>
                    <span>{{ __('قيد التعلّم') }}</span>
                    <span
                        class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold"
                        :class="activeTab === 'in_progress' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'"
                    >
                        {{ $stats['in_progress'] }}
                    </span>
                </button>

                <!-- Tab: Completed -->
                <button
                    type="button"
                    @click="activeTab = 'completed'"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer whitespace-nowrap flex items-center gap-2"
                    :class="activeTab === 'completed' 
                        ? 'bg-[#012383] text-white shadow-xs' 
                        : 'text-slate-600 hover:text-[#012383] hover:bg-[#EAF1FB]/60 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'"
                >
                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('مكتملة') }}</span>
                    <span
                        class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold"
                        :class="activeTab === 'completed' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'"
                    >
                        {{ $stats['completed'] }}
                    </span>
                </button>

                <!-- Tab: Catalog / All Available -->
                <button
                    type="button"
                    @click="activeTab = 'catalog'"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer whitespace-nowrap flex items-center gap-2"
                    :class="activeTab === 'catalog' 
                        ? 'bg-[#012383] text-white shadow-xs' 
                        : 'text-slate-600 hover:text-[#012383] hover:bg-[#EAF1FB]/60 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'"
                >
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>{{ __('دليل البرامج المتاحة') }}</span>
                    <span
                        class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold"
                        :class="activeTab === 'catalog' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'"
                    >
                        {{ $allCourses->count() }}
                    </span>
                </button>

            </div>

            <!-- Search Bar -->
            <div class="relative w-full sm:w-64 md:w-72 shrink-0">
                <input
                    type="text"
                    x-model="searchQuery"
                    placeholder="{{ __('ابحث في دوراتك ومحاضراتك...') }}"
                    class="w-full h-9 sm:h-9.5 bg-slate-50 dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 rounded-xl ps-9 pe-8 text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all"
                />
                <svg class="size-4 text-slate-400 absolute start-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <button
                    x-show="searchQuery.length > 0"
                    x-cloak
                    @click="searchQuery = ''"
                    type="button"
                    class="size-5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center absolute end-2.5 top-1/2 -translate-y-1/2 cursor-pointer text-xs"
                >
                    ✕
                </button>
            </div>

        </div>

        <!-- ============================================== -->
        <!-- 4. ENROLLED COURSES SECTION                    -->
        <!-- ============================================== -->
        <div x-show="activeTab !== 'catalog'" class="flex flex-col gap-5 w-full">
            
            @if(empty($myCoursesData))
                <!-- Empty State (No Enrolled Courses) -->
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-8 sm:p-12 text-center flex flex-col items-center justify-center gap-4 shadow-xs">
                    <div class="size-16 rounded-2xl bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-400 flex items-center justify-center shadow-xs border border-[#DEE8F7] dark:border-slate-700">
                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div class="max-w-md flex flex-col gap-1.5">
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383] dark:text-white">
                            {{ __('لم تسجّل في أي دورة تدريبية بعد') }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                            {{ __('استكشف البرامج المعتمدة في التخاطب وعلاج النطق وابدأ رحلتك التخصصية بإشراف نخبة من الأطباء والاستشاريين.') }}
                        </p>
                    </div>
                    <button
                        type="button"
                        @click="activeTab = 'catalog'"
                        class="mt-2 h-11 px-6 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs flex items-center gap-2 shadow-sm transition-all cursor-pointer"
                    >
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>{{ __('استكشف الدورات المتاحة الآن') }}</span>
                    </button>
                </div>
            @else
                <!-- Courses Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6 w-full">
                    @foreach($myCoursesData as $item)
                        @php
                            $c = $item['course'];
                            $title = app()->getLocale() === 'en' && $c->title_en ? $c->title_en : $c->title_ar;
                            $desc = app()->getLocale() === 'en' && $c->description_en ? $c->description_en : $c->description_ar;
                            $isComplete = $item['progress'] >= 100;
                            $isInProgress = $item['progress'] > 0 && $item['progress'] < 100;
                        @endphp
                        
                        <div
                            class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group"
                            x-show="(
                                (activeTab === 'enrolled') ||
                                (activeTab === 'in_progress' && {{ $isInProgress ? 'true' : 'false' }}) ||
                                (activeTab === 'completed' && {{ $isComplete ? 'true' : 'false' }})
                            ) && matchesSearch('{{ addslashes($title) }}', '{{ addslashes($desc) }}')"
                            x-transition
                        >
                            <div>
                                <!-- Course Image Thumbnail -->
                                <div class="h-44 sm:h-48 w-full bg-[#012383] relative overflow-hidden shrink-0">
                                    <img
                                        src="{{ $c->thumbnail ?: '/assets/images/course-topic-basics.png' }}"
                                        alt="{{ $title }}"
                                        class="size-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>

                                    <!-- Top Right: Status Badge -->
                                    <div class="absolute top-3 end-3 flex items-center gap-1.5 z-10">
                                        @if($isComplete)
                                            <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white font-extrabold text-[11px] shadow-sm flex items-center gap-1">
                                                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>{{ __('مكتمل ١٠٠٪') }}</span>
                                            </span>
                                        @elseif($isInProgress)
                                            <span class="px-2.5 py-1 rounded-full bg-[#012383]/90 backdrop-blur-md border border-white/20 text-white font-bold text-[11px] shadow-sm flex items-center gap-1.5">
                                                <span class="size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                                <span>{{ __('قيد التعلّم') }}</span>
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-white font-bold text-[11px] shadow-sm">
                                                {{ __('لم يبدأ بعد') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Top Left: Certificate Available Pill -->
                                    @if($item['certificate'])
                                        <div class="absolute top-3 start-3 z-10">
                                            <span class="px-2.5 py-1 rounded-full bg-amber-400 text-slate-950 font-black text-[10px] shadow-sm flex items-center gap-1">
                                                <span>🎓</span>
                                                <span>{{ __('شهادة صادرة') }}</span>
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Bottom Overlay Meta -->
                                    <div class="absolute bottom-3 inset-x-3 flex items-center justify-between text-white text-xs font-semibold z-10">
                                        <span class="px-2.5 py-0.5 rounded-lg bg-black/40 backdrop-blur-xs text-[11px]">
                                            {{ $item['total_lessons'] }} {{ __('محاضرة تدريبية') }}
                                        </span>
                                        <span class="text-white/90 text-[11px] font-bold">
                                            {{ $c->level ?? __('البرنامج السريري المتكامل') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="p-4 sm:p-5 flex flex-col gap-3">
                                    
                                    <!-- Title -->
                                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-[#012383] dark:text-white leading-snug group-hover:text-[#0B2A8F] dark:group-hover:text-blue-300 transition-colors line-clamp-2">
                                        {{ $title }}
                                    </h3>

                                    <!-- Short Description -->
                                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                                        {{ $desc }}
                                    </p>

                                    <!-- Progress Box -->
                                    <div class="flex flex-col gap-1.5 pt-2 border-t border-[#DEE8F7] dark:border-slate-800">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="font-bold text-[#0B2A8F] dark:text-blue-300 flex items-center gap-1">
                                                <span>{{ $item['progress'] }}%</span>
                                                <span class="font-normal text-slate-400">{{ __('مكتمل') }}</span>
                                            </span>
                                            <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                                                {{ $item['completed_lessons'] }} {{ __('من') }} {{ $item['total_lessons'] }} {{ __('درس') }}
                                            </span>
                                        </div>

                                        <div class="h-2 w-full bg-[#DEE8F7] dark:bg-slate-700 rounded-full overflow-hidden relative">
                                            <div
                                                class="h-full rounded-full transition-all duration-500 {{ $isComplete ? 'bg-emerald-500' : 'bg-gradient-to-r from-[#0B2A8F] to-[#012383] dark:from-blue-500 dark:to-indigo-500' }}"
                                                style="width: {{ $item['progress'] }}%;"
                                            ></div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Card Footer Action Buttons -->
                            <div class="p-4 sm:p-5 pt-0 flex flex-col gap-2">
                                
                                @if($isComplete)
                                    <!-- If 100% Completed -->
                                    <div class="grid grid-cols-2 gap-2 w-full">
                                        @if($item['certificate'])
                                            <a
                                                href="{{ route('certificates.show', $item['certificate']->id) }}"
                                                class="h-10 px-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition-all"
                                                wire:navigate
                                            >
                                                <span>🎓</span>
                                                <span>{{ __('عرض الشهادة') }}</span>
                                            </a>
                                        @else
                                            <a
                                                href="{{ route('certificates.index') }}"
                                                class="h-10 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition-all"
                                                wire:navigate
                                            >
                                                <span>🏆</span>
                                                <span>{{ __('إصدار الشهادة') }}</span>
                                            </a>
                                        @endif

                                        <a
                                            href="{{ route('lessons.show', ['course' => $c->slug]) }}"
                                            class="h-10 px-3 rounded-xl bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#012383] dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-blue-300 font-bold text-xs flex items-center justify-center gap-1 transition-colors"
                                            wire:navigate
                                        >
                                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            <span>{{ __('مراجعة الدروس') }}</span>
                                        </a>
                                    </div>
                                @else
                                    <!-- If In Progress or Not Started -->
                                    <div class="flex items-center gap-2 w-full">
                                        @php
                                            $targetVideoSlug = $item['resume_video']?->slug ?? null;
                                        @endphp
                                        <a
                                            href="{{ route('lessons.show', ['course' => $c->slug, 'video' => $targetVideoSlug]) }}"
                                            class="flex-1 h-10 px-4 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs flex items-center justify-center gap-2 shadow-xs transition-all cursor-pointer group-hover:shadow"
                                            wire:navigate
                                        >
                                            <svg class="size-4 rtl:rotate-180" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            <span>{{ $isInProgress ? __('مواصلة المشاهدة') : __('بدء التعلّم الآن') }}</span>
                                        </a>

                                        <a
                                            href="{{ route('lessons.show', ['course' => $c->slug]) }}"
                                            class="size-10 rounded-xl bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#012383] dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-blue-300 flex items-center justify-center shrink-0 border border-[#DEE8F7] dark:border-slate-700 transition-colors"
                                            title="{{ __('فهرس المحاضرات الكامل') }}"
                                            wire:navigate
                                        >
                                            <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        <!-- ============================================== -->
        <!-- 5. EXPLORE ALL COURSES CATALOGUE TAB           -->
        <!-- ============================================== -->
        <div x-show="activeTab === 'catalog'" class="flex flex-col gap-6 w-full">
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                <div class="flex flex-col gap-0.5">
                    <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383] dark:text-white">
                        {{ __('دليل البرامج الأكاديمية والسريرية المتاحة') }}
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('استكشف باقي التخصصات والمستويات المعتمدة في منصة الأمين وانضم إليها فوراً.') }}
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 font-bold text-xs border border-[#DEE8F7] dark:border-slate-700">
                    {{ $allCourses->count() }} {{ __('برامج تدريبية') }}
                </span>
            </div>

            <!-- All Courses Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6 w-full">
                @foreach($allCourses as $c)
                    @php
                        $title = app()->getLocale() === 'en' && $c->title_en ? $c->title_en : $c->title_ar;
                        $desc = app()->getLocale() === 'en' && $c->description_en ? $c->description_en : $c->description_ar;
                        $isEnrolled = in_array($c->id, $enrolledCourseIds ?? []);
                    @endphp
                    
                    <div
                        class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group"
                        x-show="matchesSearch('{{ addslashes($title) }}', '{{ addslashes($desc) }}')"
                        x-transition
                    >
                        <div>
                            <!-- Thumbnail -->
                            <div class="h-44 sm:h-48 w-full bg-[#012383] relative overflow-hidden shrink-0">
                                <img
                                    src="{{ $c->thumbnail ?: '/assets/images/course-topic-basics.png' }}"
                                    alt="{{ $title }}"
                                    class="size-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>

                                <div class="absolute top-3 end-3 flex gap-2 z-10">
                                    @if($isEnrolled)
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white font-extrabold text-[11px] shadow-sm flex items-center gap-1">
                                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>{{ __('أنت مسجّل') }}</span>
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-[#012383]/90 text-white font-bold text-[11px] shadow-sm">
                                            {{ $c->level ?? __('برنامج معتمد') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="absolute bottom-3 inset-x-3 flex items-center justify-between text-white text-xs font-semibold z-10">
                                    <span class="px-2.5 py-0.5 rounded-lg bg-black/40 backdrop-blur-xs text-[11px]">
                                        {{ $c->videos_count }} {{ __('محاضرة تدريبية') }}
                                    </span>
                                    <span class="text-amber-300 font-bold text-xs flex items-center gap-1">
                                        <span>★ 4.9</span>
                                        <span class="text-white/80 font-normal">(480)</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-4 sm:p-5 flex flex-col gap-2.5">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-md bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 text-[11px] font-bold">
                                        {{ __('تخاطب ونطق سريري') }}
                                    </span>
                                </div>

                                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-[#012383] dark:text-white leading-snug group-hover:text-[#0B2A8F] dark:group-hover:text-blue-300 transition-colors line-clamp-2">
                                    {{ $title }}
                                </h3>

                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                                    {{ $desc }}
                                </p>
                            </div>
                        </div>

                        <!-- Footer Action -->
                        <div class="p-4 sm:p-5 pt-0 flex items-center justify-between gap-3 border-t border-[#DEE8F7] dark:border-slate-800 mt-2">
                            <div>
                                <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-lg text-[#012383] dark:text-white">
                                    {{ number_format((float) $c->price, 0) }} {{ __('ج.م') }}
                                </span>
                            </div>

                            @if($isEnrolled)
                                <a
                                    href="{{ route('lessons.show', ['course' => $c->slug]) }}"
                                    class="h-9.5 px-4 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs flex items-center gap-1.5 shadow-xs transition-colors"
                                    wire:navigate
                                >
                                    <span>{{ __('فتح الدورة') }}</span>
                                    <svg class="size-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <a
                                    href="{{ route('courses.show') }}?course={{ $c->slug }}"
                                    class="h-9.5 px-4 rounded-xl bg-[#EAF1FB] hover:bg-[#012383] text-[#012383] hover:text-white dark:bg-slate-800 dark:text-blue-300 dark:hover:bg-slate-700 font-bold text-xs flex items-center gap-1.5 transition-all"
                                >
                                    <span>{{ __('تفاصيل والتسجيل') }}</span>
                                    <span class="text-xs">↗</span>
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        </div>

    </div>

</x-layouts::app>
