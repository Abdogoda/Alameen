<x-layouts::app :title="__('مشاهدة الدرس')">
    <!-- ============================================== -->
    <!-- 3. PAGE BODY CONTENT                           -->
    <!-- ============================================== -->
    <div class="flex flex-col gap-6 w-full text-start">
          
        <!-- Page Introduction -->
        <div class="flex flex-col gap-1 items-start text-start w-full">
            <p class="font-['Cairo'] font-semibold text-xs leading-relaxed text-[#0B2A8F] dark:text-blue-400 w-full text-start">
                {{ __('lesson.breadcrumb') }}
            </p>
            <h1 class="font-['Baloo_Bhaijaan_2'] font-extrabold text-2xl sm:text-3xl md:text-4xl leading-tight text-[#012383] dark:text-white w-full text-start">
                {{ __('lesson.pageTitle') }}
            </h1>
            <p class="font-['Cairo'] font-medium text-xs sm:text-sm leading-relaxed text-[#475569] dark:text-slate-400 w-full text-start">
                {{ __('lesson.pageSubtitle') }}
            </p>
        </div>

        <!-- Lesson Learning Environment Grid -->
        <div class="flex flex-col lg:flex-row gap-6 items-start w-full">
            
            <!-- Video and Lesson Information Column -->
            <div class="flex flex-1 flex-col gap-5 items-start min-w-0 w-full order-1 lg:order-2 rtl:lg:order-2 ltr:lg:order-1">
              
                <!-- Returning Learner State Banner -->
                <div class="bg-blue-50/80 dark:bg-slate-800/80 border border-blue-200/60 dark:border-slate-700/80 rounded-2xl p-4 flex gap-3 items-center w-full shrink-0 shadow-xs">
                    <div class="size-8 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-[#012383] dark:text-blue-300 flex items-center justify-center shrink-0">
                        <svg class="size-4.5 text-[#012383] dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    </div>
                    <p class="flex-1 font-['Cairo'] font-semibold text-xs sm:text-sm leading-relaxed text-[#012383] dark:text-blue-200 text-start">
                        {{ __('lesson.returningBanner') }}
                    </p>
                </div>

                @if(!$canAccess)
                    <!-- Sequential Lock Card (Beautifully harmonious in Light & Dark Mode) -->
                    <div class="w-full p-6 sm:p-10 rounded-3xl bg-gradient-to-b from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white flex flex-col items-center justify-center text-center gap-4 shadow-sm relative overflow-hidden">
                        {{-- Ambient soft gold glow --}}
                        <div class="absolute -top-12 size-36 rounded-full bg-amber-400/15 dark:bg-amber-400/10 blur-2xl pointer-events-none"></div>

                        <div class="size-16 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-300 dark:border-amber-700/60 shadow-xs">
                            <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>

                        <div class="flex flex-col items-center gap-2 max-w-lg">
                            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-[#012383] dark:text-amber-300 leading-snug">
                                {{ __('المحاضرة مقيدة بالتسلسل الإلزامي') }}
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                                {{ __('حرصاً على التدرج التعليمي والتطبيقي للمنهج، لا يمكنك فتح هذه المحاضرة أو هذا المستوى حتى تكمل المحاضرة الحالية أولاً. بمجرد إكمال أي درس أو مستوى يمكنك فتحه ومراجعته في أي وقت.') }}
                            </p>
                        </div>

                        @php
                            $curUnlockedId = $curriculumStatus['current_video_id'] ?? null;
                            $curUnlockedVid = $curUnlockedId ? $videos->firstWhere('id', $curUnlockedId) : null;
                        @endphp
                        @if($curUnlockedVid && $curUnlockedVid->id !== $video->id)
                            <a 
                                href="{{ route('lessons.show', [$course->slug, $curUnlockedVid->slug]) }}"
                                class="inline-flex items-center gap-2.5 px-6 py-3 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-['Cairo'] font-bold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all cursor-pointer"
                            >
                                <svg class="size-4.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                                <span>{{ __('الانتقال إلى الدرس الحالي المفتوح: :title', ['title' => $curUnlockedVid->title_ar]) }}</span>
                            </a>
                        @endif
                    </div>
                @else
                    <!-- Dynamic MUX / Stream Video Player with Dynamic Watermark Canvas Overlay -->
                    <x-video-watermark-player 
                        :video="$video"
                        :embed-url="$embedUrl" 
                        :playback-token="$playbackToken ?? ''"
                        :storyboard-token="$storyboardToken ?? ''"
                        :thumbnail-token="$thumbnailToken ?? ''"
                        :student="auth()->user()" 
                    />
                @endif

                <!-- Resume Action and Saved Progress Banner -->
                <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 w-full shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="size-10 rounded-xl bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0 border border-[#DEE8F7] dark:border-slate-700">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex flex-col gap-0.5 min-w-0 text-start">
                            <p class="font-['Cairo'] font-bold text-xs sm:text-sm text-[#012383] dark:text-white truncate">
                                {{ __('lesson.resumeProgress') }}
                            </p>
                            <p class="font-['Cairo'] font-medium text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 truncate">
                                {{ __('lesson.resumeSavedTime') }}
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="px-5 py-2.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-['Cairo'] font-bold text-xs sm:text-sm shadow-sm hover:shadow transition-colors cursor-pointer shrink-0 flex items-center justify-center gap-2"
                    >
                        <svg class="size-4 rtl:rotate-180" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                        <span>{{ __('lesson.resumeCta') }}</span>
                    </button>
                </div>

                <!-- Lesson Details and Task Availability Card -->
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 flex flex-col gap-5 w-full shrink-0 shadow-xs" x-data="{ activeTab: 'about' }">
                    
                    <!-- Lesson Tabs -->
                    <div class="border-b border-[#DEE8F7] dark:border-slate-800 flex gap-6 items-center justify-start pb-3 w-full">
                        <button
                            type="button"
                            @click="activeTab = 'about'"
                            class="text-xs sm:text-sm font-['Cairo'] font-bold pb-2 -mb-3 transition-colors cursor-pointer flex items-center gap-2"
                            :class="activeTab === 'about' ? 'text-[#012383] dark:text-blue-400 border-b-2 border-[#012383] dark:border-blue-400 font-extrabold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
                        >
                            <span>📖</span>
                            <span>{{ __('lesson.tabAbout') }}</span>
                        </button>
                        <button
                            type="button"
                            @click="activeTab = 'task'"
                            class="text-xs sm:text-sm font-['Cairo'] font-bold pb-2 -mb-3 transition-colors cursor-pointer flex items-center gap-2"
                            :class="activeTab === 'task' ? 'text-[#012383] dark:text-blue-400 border-b-2 border-[#012383] dark:border-blue-400 font-extrabold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
                        >
                            <span>📝</span>
                            <span>{{ __('lesson.tabTask') }}</span>
                        </button>
                    </div>

                    <!-- Learning Objectives -->
                    <div x-show="activeTab === 'about'" class="flex flex-col gap-2.5">
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white text-start w-full">
                            {{ __('lesson.learningObjectivesTitle') }}
                        </h3>
                        <p class="font-['Cairo'] font-medium text-xs sm:text-sm leading-relaxed text-slate-600 dark:text-slate-300 text-start w-full">
                            {{ __('lesson.learningObjectivesDesc') }}
                        </p>
                    </div>

                    <!-- Task Tab Content -->
                    <div x-show="activeTab === 'task'" class="flex flex-col gap-2.5" style="display: none;">
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white text-start w-full">
                            {{ __('المهمة والتطبيق العملي') }}
                        </h3>
                        <p class="font-['Cairo'] font-medium text-xs sm:text-sm leading-relaxed text-slate-600 dark:text-slate-300 text-start w-full">
                            {{ __('تسجيل صوتي وتطبيق عملي لمخارج الحروف مع استمارة الملاحظة والتقييم المعتمدة.') }}
                        </p>
                    </div>

                    <!-- Information Note -->
                    <div class="bg-blue-50/80 dark:bg-slate-800/60 border border-blue-200/60 dark:border-slate-700/60 rounded-2xl p-4 sm:p-5 flex gap-3.5 items-start w-full">
                        <div class="size-9 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-[#012383] dark:text-blue-300 flex items-center justify-center shrink-0">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div class="flex flex-1 flex-col gap-1 items-start text-start min-w-0">
                            <h4 class="font-['Cairo'] font-bold text-xs sm:text-sm leading-tight text-[#012383] dark:text-blue-300 w-full text-start">
                                {{ __('lesson.lockedTaskTitle') }}
                            </h4>
                            <p class="font-['Cairo'] font-medium text-xs text-slate-600 dark:text-slate-300 leading-relaxed w-full text-start">
                                {{ __('lesson.lockedTaskDesc') }}
                            </p>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Curriculum Navigation Column (Right in RTL, Left in LTR) -->
            <div class="w-full lg:w-[320px] shrink-0 flex flex-col items-start order-2 lg:order-1 rtl:lg:order-1 ltr:lg:order-2">
              
                <!-- Course Outline Container -->
                <div class="w-full bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-4 sm:p-5 flex flex-col gap-4 shadow-xs">
                
                    <!-- Section Title -->
                    <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-3">
                        <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-base sm:text-lg text-[#012383] dark:text-white text-start">
                            {{ __('lesson.curriculumTitle') }}
                        </h2>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full">
                            {{ $course->published_videos_count ?? $videos->count() }} {{ __('درس') }}
                        </span>
                    </div>

                    <!-- Course Summary Card -->
                    <div class="bg-blue-50/70 dark:bg-slate-800/70 border border-blue-200/60 dark:border-slate-700/60 rounded-2xl p-4 flex flex-col gap-1.5 w-full shrink-0 text-start">
                        <div class="flex items-center justify-between">
                            <span class="font-['Cairo'] font-semibold text-xs text-slate-500 dark:text-slate-400">
                                {{ __('lesson.courseCardTitle') }}
                            </span>
                            <span class="font-['Cairo'] font-bold text-xs text-[#012383] dark:text-blue-300">
                                {{ __('lesson.courseCardPercent') }}
                            </span>
                        </div>
                        <p class="font-['Cairo'] font-bold text-sm text-[#012383] dark:text-white line-clamp-1">
                            {{ $course->title_ar }}
                        </p>
                        <p class="font-['Cairo'] font-medium text-[11px] text-slate-500 dark:text-slate-400">
                            {{ __('lesson.courseCardVideos') }}
                        </p>
                        <!-- Progress Bar -->
                        <div class="bg-blue-200/60 dark:bg-slate-700 h-2 rounded-full relative overflow-hidden w-full mt-1">
                            <div class="bg-[#012383] dark:bg-blue-500 h-full rounded-full w-[42%]"></div>
                        </div>
                    </div>

                    @if(isset($publishedLevels) && $publishedLevels->isNotEmpty())
                        <div class="flex flex-col gap-3 w-full">
                            @foreach($publishedLevels as $pLevel)
                                @php
                                    $levelVideos = $pLevel->publishedVideos;
                                    $isCurrentLevel = isset($video) && $video->level_id === $pLevel->id;
                                    $isLvlCompleted = in_array($pLevel->id, $curriculumStatus['completed_level_ids'] ?? [], true);
                                    $isLvlAccessible = in_array($pLevel->id, $curriculumStatus['accessible_level_ids'] ?? [], true);
                                @endphp
                                <div class="flex flex-col gap-2 w-full shrink-0 border rounded-2xl overflow-hidden p-3 transition-all {{ $isCurrentLevel ? 'bg-blue-50/50 dark:bg-slate-800/80 border-[#0B2A8F]/40 dark:border-blue-500/50 ring-1 ring-[#0B2A8F]/20 dark:ring-blue-500/20' : ($isLvlAccessible ? 'bg-white dark:bg-slate-900 border-[#DEE8F7] dark:border-slate-800' : 'bg-slate-50/70 dark:bg-slate-800/40 border-slate-200/70 dark:border-slate-800/50 opacity-80') }}">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-1.5 flex-1 min-w-0">
                                            @if($isLvlCompleted)
                                                <span class="size-4 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0" title="{{ __('مستوى مكتمل') }}">
                                                    <svg class="size-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                </span>
                                            @elseif(! $isLvlAccessible)
                                                <span class="size-4 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0" title="{{ __('مستوى مغلق') }}">
                                                    <svg class="size-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                </span>
                                            @elseif($levelVideos->isEmpty())
                                                <span class="size-4 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0" title="{{ __('قريباً') }}">
                                                    <svg class="size-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </span>
                                            @endif
                                            <span class="font-['Cairo'] font-bold text-xs sm:text-[13px] leading-tight {{ $isLvlAccessible ? 'text-[#012383] dark:text-white' : 'text-slate-500 dark:text-slate-400' }} truncate">
                                                {{ $pLevel->title_ar }}
                                            </span>
                                        </div>
                                        @if($isLvlCompleted)
                                            <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/60 dark:border-emerald-800/50 px-2 py-0.5 rounded-full shrink-0">
                                                {{ __('مكتمل') }}
                                            </span>
                                        @elseif(! $isLvlAccessible)
                                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 bg-slate-200/70 dark:bg-slate-700/60 px-2 py-0.5 rounded-full shrink-0">
                                                {{ __('مغلق') }}
                                            </span>
                                        @elseif($levelVideos->isEmpty())
                                            <span class="text-[10px] font-bold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 border border-amber-200/60 dark:border-amber-800/50 px-2 py-0.5 rounded-full shrink-0">
                                                {{ __('قريباً') }}
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full shrink-0">
                                                {{ __(':count دروس', ['count' => $levelVideos->count()]) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-col gap-1.5 w-full">
                                        @forelse($levelVideos as $lVideo)
                                            @php
                                                $isActive = isset($video) && $lVideo->id === $video->id;
                                                $isCompleted = in_array($lVideo->id, $curriculumStatus['completed_video_ids'] ?? [], true);
                                                $isAccessible = in_array($lVideo->id, $curriculumStatus['accessible_video_ids'] ?? [], true);
                                            @endphp
                                            <a 
                                                href="{{ route('lessons.show', [$course->slug, $lVideo->slug]) }}"
                                                @if(! $isAccessible) title="{{ __('مغلق حتى إكمال الدرس الحالي') }}" @endif
                                                class="rounded-xl p-2.5 flex flex-col gap-1 w-full text-start shrink-0 transition-all {{ $isActive ? 'bg-[#012383] dark:bg-blue-600 text-white shadow-xs' : ($isAccessible ? ($isCompleted ? 'bg-emerald-50/70 hover:bg-emerald-100/70 dark:bg-emerald-950/30 dark:hover:bg-emerald-950/50 text-slate-800 dark:text-emerald-200 border border-emerald-200/60 dark:border-emerald-800/50' : 'bg-[#FAFCFF] hover:bg-[#EAF1FB] dark:bg-slate-800/60 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border border-[#DEE8F7]/70 dark:border-slate-700/60') : 'bg-slate-100/70 hover:bg-slate-200/70 dark:bg-slate-800/30 dark:hover:bg-slate-800/50 text-slate-400 dark:text-slate-500 border border-slate-200/50 dark:border-slate-800/40') }}"
                                            >
                                                <div class="flex items-center justify-between gap-2">
                                                    <div class="flex items-center gap-1.5 flex-1 min-w-0">
                                                        @if($isCompleted && ! $isActive)
                                                             <svg class="size-3.5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                        @elseif(! $isAccessible && ! $isActive)
                                                            <svg class="size-3 text-slate-400 dark:text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                        @endif
                                                        <p class="font-['Cairo'] font-bold text-xs leading-snug {{ $isActive ? 'text-white' : ($isAccessible ? ($isCompleted ? 'text-emerald-950 dark:text-emerald-200' : 'text-[#012383] dark:text-white') : 'text-slate-400 dark:text-slate-500') }} truncate">
                                                            {{ $lVideo->title_ar }}
                                                        </p>
                                                    </div>
                                                    @if($isActive)
                                                        <span class="text-[9.5px] bg-white/20 text-white px-2 py-0.5 rounded-full font-bold shrink-0">{{ __('الدرس الحالي') }}</span>
                                                    @elseif($isCompleted)
                                                        <span class="text-[9.5px] bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 px-2 py-0.5 rounded-full font-bold shrink-0">{{ __('مكتمل') }}</span>
                                                    @elseif(! $isAccessible)
                                                        <span class="text-[9.5px] bg-slate-200 dark:bg-slate-700/80 text-slate-600 dark:text-slate-400 px-2 py-0.5 rounded-full font-bold shrink-0">{{ __('مغلق') }}</span>
                                                    @elseif($lVideo->is_free_preview)
                                                        <span class="text-[9.5px] bg-amber-100 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300 px-2 py-0.5 rounded-full font-bold shrink-0">{{ __('معاينة مجانية') }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2 text-[10px] {{ $isActive ? 'text-blue-100' : ($isAccessible ? 'text-slate-400 dark:text-slate-400' : 'text-slate-400 dark:text-slate-500') }}">
                                                    <span>{{ __(':min دقيقة', ['min' => $lVideo->duration_minutes]) }}</span>
                                                    @if($lVideo->clinicalTask)
                                                        <span>•</span>
                                                        <span>{{ __('مهمة تدريبية') }}</span>
                                                    @endif
                                                    @if($isCompleted && ! $isActive)
                                                        <span>•</span>
                                                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ __('يمكنك مراجعته') }}</span>
                                                    @endif
                                                </div>
                                            </a>
                                        @empty
                                            <p class="text-xs text-slate-400 dark:text-slate-500 px-2 py-1">
                                                @if(! $isLvlAccessible)
                                                    {{ __('محتوى المستوى مقفل بالتسلسل الإلزامي حتى إكمال المستويات السابقة.') }}
                                                @else
                                                    {{ __('لا توجد دروس منشورة في هذا المستوى حالياً.') }}
                                                @endif
                                            </p>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif(isset($videos) && $videos->isNotEmpty())
                        <div class="flex flex-col gap-1.5 w-full">
                            @foreach($videos as $lVideo)
                                @php
                                    $isActive = isset($video) && $lVideo->id === $video->id;
                                    $isCompleted = in_array($lVideo->id, $curriculumStatus['completed_video_ids'] ?? [], true);
                                    $isAccessible = in_array($lVideo->id, $curriculumStatus['accessible_video_ids'] ?? [], true);
                                @endphp
                                <a 
                                    href="{{ route('lessons.show', [$course->slug, $lVideo->slug]) }}"
                                    @if(! $isAccessible) title="{{ __('مغلق حتى إكمال الدرس الحالي') }}" @endif
                                    class="rounded-xl p-2.5 flex flex-col gap-1 w-full text-start shrink-0 transition-all {{ $isActive ? 'bg-[#012383] dark:bg-blue-600 text-white shadow-xs' : ($isAccessible ? ($isCompleted ? 'bg-emerald-50/70 hover:bg-emerald-100/70 dark:bg-emerald-950/30 dark:hover:bg-emerald-950/50 text-slate-800 dark:text-emerald-200 border border-emerald-200/60 dark:border-emerald-800/50' : 'bg-[#FAFCFF] hover:bg-[#EAF1FB] dark:bg-slate-800/60 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border border-[#DEE8F7]/70 dark:border-slate-700/60') : 'bg-slate-100/70 hover:bg-slate-200/70 dark:bg-slate-800/30 dark:hover:bg-slate-800/50 text-slate-400 dark:text-slate-500 border border-slate-200/50 dark:border-slate-800/40') }}"
                                >
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-1.5 flex-1 min-w-0">
                                            @if($isCompleted && ! $isActive)
                                                <svg class="size-3.5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            @elseif(! $isAccessible && ! $isActive)
                                                <svg class="size-3 text-slate-400 dark:text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            @endif
                                            <p class="font-['Cairo'] font-bold text-xs leading-snug {{ $isActive ? 'text-white' : ($isAccessible ? ($isCompleted ? 'text-emerald-950 dark:text-emerald-200' : 'text-[#012383] dark:text-white') : 'text-slate-400 dark:text-slate-500') }} truncate">
                                                {{ $lVideo->title_ar }}
                                            </p>
                                        </div>
                                        @if($isActive)
                                            <span class="text-[9.5px] bg-white/20 text-white px-2 py-0.5 rounded-full font-bold shrink-0">{{ __('الدرس الحالي') }}</span>
                                        @elseif($isCompleted)
                                            <span class="text-[9.5px] bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 px-2 py-0.5 rounded-full font-bold shrink-0">{{ __('مكتمل') }}</span>
                                        @elseif(! $isAccessible)
                                            <span class="text-[9.5px] bg-slate-200 dark:bg-slate-700/80 text-slate-600 dark:text-slate-400 px-2 py-0.5 rounded-full font-bold shrink-0">{{ __('مغلق') }}</span>
                                        @elseif($lVideo->is_free_preview)
                                            <span class="text-[9.5px] bg-amber-100 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300 px-2 py-0.5 rounded-full font-bold shrink-0">{{ __('معاينة مجانية') }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] {{ $isActive ? 'text-blue-100' : 'text-slate-400 dark:text-slate-500' }}">
                                        <span>{{ __(':min دقيقة', ['min' => $lVideo->duration_minutes]) }}</span>
                                        @if($isCompleted && ! $isActive)
                                            <span>•</span>
                                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ __('يمكنك مراجعته') }}</span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <!-- View Curriculum CTA -->
                    <a
                        href="{{ route('courses.index') }}"
                        class="bg-white dark:bg-slate-800 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 text-[#012383] dark:text-blue-300 border border-[#DEE8F7] dark:border-slate-700 rounded-xl h-11 flex items-center justify-center font-['Cairo'] font-bold text-xs transition-colors cursor-pointer w-full shrink-0 shadow-xs"
                    >
                        {{ __('lesson.viewCurriculumBtn') }}
                    </a>

                </div>

            </div>

        </div>

    </div>

</x-layouts::app>
