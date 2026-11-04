<x-layouts::app :title="$course->title . ' - ' . __('تفاصيل الدورة والمنهج')" :description="$course->description" :course="$course">
        <!-- Page Content -->
        <div class="flex flex-col gap-6 w-full text-start">
          
          <!-- Page Introduction -->
          <div class="flex flex-col gap-[4px] items-start text-start w-full">
            <p class="font-['Cairo'] font-semibold text-[12px] leading-[1.8] text-[#0B2A8F] dark:text-blue-400 text-start w-full">
              <a href="{{ route('courses.index') }}" class="hover:underline">{{ __('courses.breadcrumb') }}</a>
              <span> / </span>
              <span class="text-slate-500 dark:text-slate-400">{{ $course->title }}</span>
            </p>
            <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-[28px] sm:text-[32px] leading-[1.3] text-[#012383] dark:text-white text-start w-full">{{ $course->title }}</h1>
            <p class="font-['Cairo'] font-medium text-[13px] sm:text-[14px] leading-[1.7] text-[#475569] dark:text-slate-400 text-start w-full">{{ $course->description }}</p>
          </div>

          @if(session('success'))
            <div class="w-full p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 font-bold text-xs sm:text-sm flex items-center gap-3 shadow-xs">
              <span class="size-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 text-xs font-black">✓</span>
              <span>{{ session('success') }}</span>
            </div>
          @endif

          @if(session('error'))
            <div class="w-full p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 font-bold text-xs sm:text-sm flex items-center gap-3 shadow-xs">
              <span class="size-6 rounded-full bg-rose-500 text-white flex items-center justify-center shrink-0 text-xs font-black">✕</span>
              <span>{{ session('error') }}</span>
            </div>
          @endif

          <!-- Course Progress Overview Card -->
          <div class="bg-gradient-to-l from-[#EAF1FB] via-[#F4F8FD] to-white dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 border border-[#DEE8F7] dark:border-slate-800 rounded-[20px] p-6 flex flex-col items-start w-full shadow-xs">
            <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-center w-full justify-between">
              
              <!-- Right in RTL: Course Viewing Progress -->
              <div class="flex flex-1 flex-col gap-[8px] items-start min-w-0 w-full">
                <div class="flex items-center justify-between font-['Cairo'] font-bold text-[#012383] dark:text-white text-start w-full">
                  <span class="text-[15px]">{{ __('courses.progressLabel') }}</span>
                  <span class="text-[22px] sm:text-[24px] text-[#0B2A8F] dark:text-blue-400 font-mono">{{ round($courseProgressPercentage) }}%</span>
                </div>
                <div class="bg-[#DEE8F7] dark:bg-slate-700 h-[8px] rounded-full w-full relative overflow-hidden">
                  <div class="bg-gradient-to-r from-[#0B2A8F] to-[#012383] dark:from-blue-500 dark:to-indigo-600 h-full rounded-full transition-all duration-500" style="width: {{ min(100, max(0, round($courseProgressPercentage))) }}%"></div>
                </div>
                <p class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569] dark:text-slate-400 text-start w-full">
                  @if(app()->getLocale() === 'en')
                    {{ $watchedMinutes }} of {{ $totalDurationMinutes }} minutes • {{ $completedVideosCount }} of {{ $totalVideosCount }} videos completed • {{ $totalLevelsCount }} levels
                  @else
                    {{ $watchedMinutes }} من {{ $totalDurationMinutes }} دقيقة • {{ $completedVideosCount }} من {{ $totalVideosCount }} فيديوهات مكتملة • {{ $totalLevelsCount }} مستويات
                  @endif
                </p>
              </div>

              <!-- Left in RTL: CTA Button -->
              @if($hasActiveAccess)
                <a
                  href="{{ $resumeVideo ? route('lessons.show', [$course->slug, $resumeVideo->slug]) : route('lessons.show', $course->slug) }}"
                  class="bg-[#012383] hover:bg-[#0B2A8F] rounded-xl h-[46px] px-6 flex items-center justify-center text-white font-['Cairo'] font-bold text-[14px] shadow-sm hover:shadow transition-all shrink-0 w-full sm:w-auto"
                >
                  {{ __('استئناف مشاهدة المحاضرات') }}
                </a>
              @elseif($pendingReceipt)
                <div class="flex flex-col sm:flex-row items-center gap-2.5 shrink-0 w-full sm:w-auto">
                  <div class="bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 rounded-xl h-[46px] px-4 flex items-center gap-2 text-amber-800 dark:text-amber-200 font-['Cairo'] font-bold text-[13px] shadow-xs">
                    <span class="animate-pulse">⏳</span>
                    <span>{{ __('إيصال السداد قيد المراجعة والاعتماد المالي') }}</span>
                  </div>
                  <button
                    type="button"
                    onclick="document.getElementById('modal-upload-receipt').classList.remove('hidden')"
                    class="text-xs font-bold text-slate-500 hover:text-[#012383] dark:text-slate-400 dark:hover:text-white underline cursor-pointer"
                  >
                    {{ __('إعادة الرفع أو التعديل') }}
                  </button>
                </div>
              @else
                <button
                  type="button"
                  onclick="document.getElementById('modal-upload-receipt').classList.remove('hidden')"
                  class="bg-emerald-600 hover:bg-emerald-700 rounded-xl h-[46px] px-6 flex items-center justify-center text-white font-['Cairo'] font-bold text-[14px] shadow-sm hover:shadow transition-all shrink-0 w-full sm:w-auto cursor-pointer"
                >
                  {{ __('💳 سداد وتفعيل الاشتراك بالإيصال') }}
                </button>
              @endif

            </div>
          </div>

          <!-- Curriculum and Learning Companion Grid -->
          <div class="flex flex-col lg:flex-row gap-[24px] items-start w-full">
            
            <!-- Right in RTL: Complete Curriculum Column -->
            <div class="flex-1 min-w-0 flex flex-col gap-[18px] w-full">
              
              <!-- Section Heading -->
              <div class="flex items-center justify-between text-start w-full">
                <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-[19px] sm:text-[20px] leading-[1.4] text-[#012383] dark:text-white">{{ __('courses.curriculumTitle') }}</h2>
                <p class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569] dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 px-3 py-0.5 rounded-full whitespace-nowrap">
                  @if(app()->getLocale() === 'en')
                    {{ $totalVideosCount }} videos • {{ $totalTasksCount }} tasks
                  @else
                    {{ $totalVideosCount }} فيديو • {{ $totalTasksCount }} مهمة
                  @endif
                </p>
              </div>
              <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] dark:text-slate-400 text-start w-full">{{ __('courses.curriculumDesc') }}</p>

              <!-- Dynamic Levels Loop -->
              @forelse($levels as $levelIndex => $level)
                @php
                  $levelVideos = $level->publishedVideos;
                  $levelVideosCount = $levelVideos->count();
                  $levelDurationSeconds = (int) $levelVideos->sum('duration_seconds');
                  $levelMinutes = (int) round($levelDurationSeconds / 60);
                  $levelTasksCount = $levelVideos->filter(fn($v) => $v->clinicalTask !== null)->count();
                  $levelCompletedCount = $levelVideos->filter(fn($v) => in_array($v->id, $completedVideoIds, true))->count();
                  $levelProgressPct = $levelVideosCount > 0 ? round(($levelCompletedCount / $levelVideosCount) * 100) : 0;
                  $levelAccessible = in_array($level->id, $accessibleLevelIds, true);
                @endphp
                <div class="level-card bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl overflow-hidden w-full shadow-xs">
                  <div class="bg-[#FAFCFF] dark:bg-slate-800/60 p-[20px] flex flex-col gap-[10px] w-full cursor-pointer select-none" onclick="const body = this.nextElementSibling; const chevron = this.querySelector('.level-chevron'); body.classList.toggle('hidden'); chevron.classList.toggle('rotate-180');">
                    <div class="flex gap-[12px] items-center w-full">
                      <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                        <div class="flex items-center gap-2">
                          <h4 class="font-['Cairo'] font-bold text-[18px] leading-[1.8] text-[#012383] dark:text-white">{{ $level->title }}</h4>
                          @if(! $levelAccessible && ! $hasActiveAccess)
                            <span class="text-xs px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 font-medium">🔒 {{ __('مقفل') }}</span>
                          @endif
                        </div>
                        <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] dark:text-slate-400">
                          @if(app()->getLocale() === 'en')
                            {{ $levelVideosCount }} videos • {{ $levelMinutes }} min • {{ $levelTasksCount }} tasks
                          @else
                            {{ $levelVideosCount }} فيديوهات • {{ $levelMinutes }} دقيقة • {{ $levelTasksCount }} مهام
                          @endif
                        </p>
                      </div>
                      <span class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#0B2A8F] dark:text-blue-400 whitespace-nowrap">{{ $levelProgressPct }}%</span>
                      <div class="level-chevron size-[18px] shrink-0 relative transition-transform duration-200">
                        <img src="/assets/images/icon-chevron-up.svg" alt="" class="size-full dark:invert" />
                      </div>
                    </div>
                    <div class="bg-[#DEE8F7] dark:bg-slate-700 h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] dark:bg-blue-500 h-[7px] rounded-full transition-all duration-500" style="width: {{ $levelProgressPct }}%"></div>
                    </div>
                  </div>

                  <div class="level-body">
                    @forelse($levelVideos as $vIndex => $video)
                      @php
                        $isCompleted = in_array($video->id, $completedVideoIds, true);
                        $vProg = $userProgressMap->get($video->id);
                        $vPct = $isCompleted ? 100 : ($vProg ? round($vProg->completion_percentage) : 0);
                        $isInProgress = ! $isCompleted && $vProg && $vProg->watched_seconds > 0;
                        $canAccess = $hasActiveAccess ? in_array($video->id, $accessibleVideoIds, true) : (bool) $video->is_free_preview;
                        $durationMin = max(1, (int) round($video->duration_seconds / 60));
                      @endphp
                      <div class="border-t border-[#DEE8F7] dark:border-slate-800 flex flex-col sm:flex-row gap-3 sm:gap-[16px] items-start sm:items-center px-[16px] py-[13px] w-full hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        
                        <div class="flex items-center gap-3 flex-1 min-w-0 w-full sm:w-auto">
                          <!-- Status Icon -->
                          @if($isCompleted)
                            <div class="size-[30px] rounded-full bg-[#EEF7F3] dark:bg-emerald-950/60 flex items-center justify-center shrink-0">
                              <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                            </div>
                          @elseif($isInProgress)
                            <div class="size-[30px] rounded-full bg-blue-50 dark:bg-blue-950/60 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0 text-xs font-bold">
                              ▶
                            </div>
                          @elseif($canAccess)
                            <div class="size-[30px] rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0 text-xs font-bold font-['Cairo']">
                              {{ $vIndex + 1 }}
                            </div>
                          @else
                            <div class="size-[30px] rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center shrink-0">
                              <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                          @endif

                          <!-- Video Info -->
                          <div class="flex-1 flex flex-col gap-[2px] items-start text-start min-w-0">
                            @if($canAccess)
                              <a href="{{ route('lessons.show', [$course->slug, $video->slug]) }}" class="hover:underline font-['Cairo'] font-semibold text-[14px] text-[#012383] dark:text-white truncate max-w-full">
                                {{ $video->title }}
                              </a>
                            @else
                              <span class="font-['Cairo'] font-semibold text-[14px] text-slate-600 dark:text-slate-400 truncate max-w-full">
                                {{ $video->title }}
                              </span>
                            @endif
                            <div class="flex items-center gap-2">
                              <span class="font-['Cairo'] font-medium text-[11px] text-[#475569] dark:text-slate-400">
                                @if(app()->getLocale() === 'en')
                                  {{ $durationMin }} min
                                @else
                                  {{ $durationMin }} دقيقة
                                @endif
                              </span>
                              @if($video->is_free_preview)
                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-bold">{{ __('معاينة مجانية') }}</span>
                              @endif
                            </div>
                          </div>
                        </div>

                        <!-- Progress Bar & Percentage -->
                        <div class="w-full sm:w-[140px] flex flex-col gap-[5px] items-start shrink-0">
                          <p class="font-['Cairo'] font-semibold text-[12px] {{ $isCompleted ? 'text-[#216350] dark:text-emerald-400' : ($isInProgress ? 'text-[#0B2A8F] dark:text-blue-400' : 'text-slate-500 dark:text-slate-400') }}">
                            @if($isCompleted)
                              {{ __('مكتمل • 100%') }}
                            @elseif($isInProgress)
                              {{ __('قيد المشاهدة') }} • {{ $vPct }}%
                            @else
                              {{ __('لم يبدأ • 0%') }}
                            @endif
                          </p>
                          <div class="bg-[#DEE8F7] dark:bg-slate-700 h-[7px] rounded-full w-full relative overflow-hidden">
                            <div class="bg-[#0B2A8F] dark:bg-blue-500 h-[7px] rounded-full transition-all duration-300" style="width: {{ $vPct }}%"></div>
                          </div>
                        </div>

                        <!-- Associated Task Column -->
                        <div class="w-full sm:w-[150px] flex flex-col gap-[2px] items-start sm:items-end text-start sm:text-end shrink-0">
                          @if($video->clinicalTask)
                            @php
                              $submission = $userSubmissions->get($video->clinicalTask->id);
                            @endphp
                            <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] dark:text-slate-400 truncate max-w-full" title="{{ $video->clinicalTask->title }}">
                              {{ $video->clinicalTask->title }}
                            </p>
                            @if($submission && $submission->status === 'graded')
                              <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                                ✓ {{ __('تم التقييم') }} ({{ $submission->score }}/{{ $video->clinicalTask->max_score }})
                              </span>
                            @elseif($submission && $submission->status === 'pending')
                              <span class="text-[11px] font-bold text-amber-600 dark:text-amber-400">{{ __('قيد المراجعة') }}</span>
                            @elseif($isCompleted)
                              @if($hasActiveAccess && $cmsSettings->isPageEnabled('tasks'))
                                <a href="{{ route('assignments.index', $video->clinicalTask->id) }}" class="text-[11px] font-bold text-[#0B2A8F] dark:text-blue-400 hover:underline">
                                  {{ __('متاح للتسليم') }} ←
                                </a>
                              @else
                                <span class="text-[11px] font-medium text-slate-400">{{ __('متاح للتسليم') }}</span>
                              @endif
                            @else
                              <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500">{{ __('بعد إكمال الفيديو') }}</span>
                            @endif
                          @else
                            <p class="font-['Cairo'] font-medium text-[11px] text-slate-400 dark:text-slate-500">{{ __('لا توجد مهمة') }}</p>
                          @endif
                        </div>

                      </div>
                    @empty
                      <div class="p-4 text-center text-xs text-slate-500 font-medium">
                        {{ __('لا توجد فيديوهات منشورة في هذا المستوى حالياً.') }}
                      </div>
                    @endforelse
                  </div>
                </div>
              @empty
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-8 text-center text-slate-500">
                  {{ __('لا توجد مستويات منشورة في هذه الدورة حتى الآن.') }}
                </div>
              @endforelse

              <!-- Standalone Videos (If any) -->
              @if($standaloneVideos->isNotEmpty())
                <div class="level-card bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl overflow-hidden w-full shadow-xs">
                  <div class="bg-[#FAFCFF] dark:bg-slate-800/60 p-[20px] flex flex-col gap-[10px] w-full cursor-pointer select-none" onclick="const body = this.nextElementSibling; const chevron = this.querySelector('.level-chevron'); body.classList.toggle('hidden'); chevron.classList.toggle('rotate-180');">
                    <div class="flex gap-[12px] items-center w-full">
                      <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                        <h4 class="font-['Cairo'] font-bold text-[18px] leading-[1.8] text-[#012383] dark:text-white">{{ __('المحاضرات العامة والإضافية') }}</h4>
                        <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] dark:text-slate-400">{{ $standaloneVideos->count() }} {{ __('فيديوهات') }}</p>
                      </div>
                      <div class="level-chevron size-[18px] shrink-0 relative transition-transform duration-200">
                        <img src="/assets/images/icon-chevron-up.svg" alt="" class="size-full dark:invert" />
                      </div>
                    </div>
                  </div>
                  <div class="level-body">
                    @foreach($standaloneVideos as $vIndex => $video)
                      @php
                        $isCompleted = in_array($video->id, $completedVideoIds, true);
                        $vProg = $userProgressMap->get($video->id);
                        $vPct = $isCompleted ? 100 : ($vProg ? round($vProg->completion_percentage) : 0);
                        $isInProgress = ! $isCompleted && $vProg && $vProg->watched_seconds > 0;
                        $canAccess = $hasActiveAccess ? in_array($video->id, $accessibleVideoIds, true) : (bool) $video->is_free_preview;
                        $durationMin = max(1, (int) round($video->duration_seconds / 60));
                      @endphp
                      <div class="border-t border-[#DEE8F7] dark:border-slate-800 flex flex-col sm:flex-row gap-3 sm:gap-[16px] items-start sm:items-center px-[16px] py-[13px] w-full hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <div class="flex items-center gap-3 flex-1 min-w-0 w-full sm:w-auto">
                          @if($isCompleted)
                            <div class="size-[30px] rounded-full bg-[#EEF7F3] dark:bg-emerald-950/60 flex items-center justify-center shrink-0">
                              <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                            </div>
                          @elseif($isInProgress)
                            <div class="size-[30px] rounded-full bg-blue-50 dark:bg-blue-950/60 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0 text-xs font-bold">
                              ▶
                            </div>
                          @else
                            <div class="size-[30px] rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0 text-xs font-bold font-['Cairo']">
                              {{ $vIndex + 1 }}
                            </div>
                          @endif
                          <div class="flex-1 flex flex-col gap-[2px] items-start text-start min-w-0">
                            @if($canAccess)
                              <a href="{{ route('lessons.show', [$course->slug, $video->slug]) }}" class="hover:underline font-['Cairo'] font-semibold text-[14px] text-[#012383] dark:text-white truncate max-w-full">
                                {{ $video->title }}
                              </a>
                            @else
                              <span class="font-['Cairo'] font-semibold text-[14px] text-slate-600 dark:text-slate-400 truncate max-w-full">
                                {{ $video->title }}
                              </span>
                            @endif
                            <span class="font-['Cairo'] font-medium text-[11px] text-[#475569] dark:text-slate-400">{{ $durationMin }} {{ __('دقيقة') }}</span>
                          </div>
                        </div>
                        <div class="w-full sm:w-[140px] flex flex-col gap-[5px] items-start shrink-0">
                          <p class="font-['Cairo'] font-semibold text-[12px] {{ $isCompleted ? 'text-[#216350] dark:text-emerald-400' : ($isInProgress ? 'text-[#0B2A8F] dark:text-blue-400' : 'text-slate-500 dark:text-slate-400') }}">
                            {{ $isCompleted ? __('مكتمل • 100%') : ($isInProgress ? __('قيد المشاهدة') . ' • ' . $vPct . '%' : __('لم يبدأ • 0%')) }}
                          </p>
                          <div class="bg-[#DEE8F7] dark:bg-slate-700 h-[7px] rounded-full w-full relative overflow-hidden">
                            <div class="bg-[#0B2A8F] dark:bg-blue-500 h-[7px] rounded-full" style="width: {{ $vPct }}%"></div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

            </div>

            <!-- Left in RTL: Course Companion Widgets -->
            <div class="w-full lg:w-[280px] flex flex-col gap-[20px] shrink-0">
              
              <!-- Widget 1: Current Place Card -->
              @if($resumeVideo)
                @php
                  $pausedSec = $resumeProgress?->watched_seconds ?? 0;
                  $totalSec = max(1, $resumeVideo->duration_seconds);
                  $pausedFormatted = sprintf('%02d:%02d', floor($pausedSec / 60), $pausedSec % 60);
                  $totalFormatted = sprintf('%02d:%02d', floor($totalSec / 60), $totalSec % 60);
                @endphp
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-6 flex flex-col gap-4 w-full shadow-xs">
                  <div class="bg-blue-50 dark:bg-slate-800 rounded-full px-3 py-1 w-fit">
                    <p class="font-['Cairo'] font-bold text-xs leading-none text-[#0B2A8F] dark:text-blue-300">{{ __('courses.companionBadge') }}</p>
                  </div>
                  <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl leading-snug text-[#012383] dark:text-white text-start">{{ $resumeVideo->title }}</h3>
                  <p class="font-['Cairo'] font-semibold text-xs leading-relaxed text-[#0B2A8F] dark:text-blue-400 text-start">{{ $resumeVideo->level?->title ?? __('المنهج الدراسي') }}</p>
                  @if($pausedSec > 0)
                    <p class="font-['Cairo'] font-medium text-xs leading-relaxed text-[#475569] dark:text-slate-300 text-start">
                      {{ __('توقفت عند :paused من :total، ويمكنك المتابعة من الموضع نفسه.', ['paused' => $pausedFormatted, 'total' => $totalFormatted]) }}
                    </p>
                    <a
                      href="{{ route('lessons.show', [$course->slug, $resumeVideo->slug]) }}"
                      class="bg-[#012383] hover:bg-[#0B2A8F] rounded-xl h-11 w-full flex items-center justify-center px-4 text-white font-['Cairo'] font-bold text-xs shadow-sm hover:shadow transition-colors"
                    >{{ __('استئناف من :time', ['time' => $pausedFormatted]) }}</a>
                  @else
                    <p class="font-['Cairo'] font-medium text-xs leading-relaxed text-[#475569] dark:text-slate-300 text-start">
                      {{ __('ابدأ دراستك للدرس وتابع خطتك التعليمية خطوة بخطوة.') }}
                    </p>
                    <a
                      href="{{ route('lessons.show', [$course->slug, $resumeVideo->slug]) }}"
                      class="bg-[#012383] hover:bg-[#0B2A8F] rounded-xl h-11 w-full flex items-center justify-center px-4 text-white font-['Cairo'] font-bold text-xs shadow-sm hover:shadow transition-colors"
                    >{{ __('بدء المشاهدة الآن') }}</a>
                  @endif
                </div>
              @endif

              <!-- Widget 2: Tasks Overview -->
              @if($cmsSettings->isPageEnabled('tasks'))
              <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-6 flex flex-col gap-4 w-full shadow-xs">
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl leading-snug text-[#012383] dark:text-white text-start">{{ __('courses.tasksOverviewTitle') }}</h3>
                <div class="flex flex-col gap-2 text-xs text-[#475569] dark:text-slate-300 font-medium">
                  <p>
                    @if(app()->getLocale() === 'en')
                      {{ $totalTasksCount }} tasks; one per video
                    @else
                      {{ $totalTasksCount }} مهمة؛ مهمة لكل فيديو
                    @endif
                  </p>
                  <p class="text-emerald-700 dark:text-emerald-400 font-bold">
                    @if(app()->getLocale() === 'en')
                      {{ $gradedTasksCount }} graded
                    @else
                      {{ $gradedTasksCount }} مُقيّمة
                    @endif
                  </p>
                  <p class="text-amber-700 dark:text-amber-400 font-bold">
                    @if(app()->getLocale() === 'en')
                      {{ $pendingTasksCount }} awaiting review
                    @else
                      {{ $pendingTasksCount }} بانتظار المراجعة
                    @endif
                  </p>
                  <p>
                    @if(app()->getLocale() === 'en')
                      {{ $availableTasksCount }} available and not submitted
                    @else
                      {{ $availableTasksCount }} متاحة ولم تُسلّم
                    @endif
                  </p>
                  <p class="text-slate-400 dark:text-slate-500">
                    @if(app()->getLocale() === 'en')
                      {{ $lockedTasksCount }} available after completing videos
                    @else
                      {{ $lockedTasksCount }} متاحة بعد إكمال الفيديوهات
                    @endif
                  </p>
                </div>
                @if($nextAvailableTask)
                  <a
                    href="{{ route('assignments.index', $nextAvailableTask->id) }}"
                    class="border border-[#012383] dark:border-blue-400 hover:bg-[#012383] hover:text-white text-[#012383] dark:text-blue-300 rounded-xl h-10 w-full flex items-center justify-center px-3 font-['Cairo'] font-bold text-xs transition-colors truncate"
                  >{{ __('فتح مهمة') }}: {{ $nextAvailableTask->title }}</a>
                @else
                  <a
                    href="{{ route('assignments.index') }}"
                    class="border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl h-10 w-full flex items-center justify-center px-3 font-['Cairo'] font-bold text-xs transition-colors"
                  >{{ __('عرض المهام') }}</a>
                @endif
              </div>
              @endif

              <!-- Widget 3: Pedagogical note -->
              <div class="bg-blue-50/80 dark:bg-slate-800/60 border border-blue-200/60 dark:border-slate-700/60 rounded-2xl p-4 flex gap-3.5 items-start w-full shadow-xs">
                <div class="size-8 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-[#012383] dark:text-blue-300 flex items-center justify-center shrink-0">
                  <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1 flex flex-col gap-1 items-start text-start">
                  <p class="font-['Cairo'] font-bold text-xs leading-relaxed text-[#012383] dark:text-blue-300 text-start">{{ __('courses.noteTitle') }}</p>
                  <p class="font-['Cairo'] font-medium text-xs leading-relaxed text-[#475569] dark:text-slate-300 text-start">{{ __('courses.noteDesc') }}</p>
                </div>
              </div>

            </div>

          </div>

        </div>

    <x-slot:modals>
        @if($currentCourse)
        <!-- Modal: Upload Manual Payment Receipt -->
        <div id="modal-upload-receipt" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden" onclick="if (event.target === this) this.classList.add('hidden')">
          <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 sm:p-8 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-3">
              <div class="flex items-center gap-2">
                <span class="size-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold">💳</span>
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383] dark:text-white">{{ __('رفع إيصال سداد الدورة') }}</h3>
              </div>
              <button type="button" onclick="document.getElementById('modal-upload-receipt').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer text-lg leading-none">✕</button>
            </div>

            <!-- Dynamic Payment Instructions from SettingsService -->
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300 flex flex-col gap-2">
              <span class="font-bold text-[#012383] dark:text-blue-300">{{ __('بيانات التحويل الحالية:') }}</span>
              <div class="flex items-center justify-between">
                <span>📱 {{ __('فودافون كاش:') }}</span>
                <span class="font-mono font-bold text-rose-700 dark:text-rose-400">{{ $cmsSettings->get('vodafone_cash_number', '01012345678') }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span>⚡ {{ __('إنستاباي (InstaPay):') }}</span>
                <span class="font-mono font-bold text-purple-700 dark:text-purple-400">{{ $cmsSettings->get('instapay_handle', 'alameen@instapay') }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span>🏦 {{ __('سعر الدورة:') }}</span>
                <span class="font-bold text-[#0B2A8F] dark:text-blue-400">{{ number_format((float) $currentCourse->price, 2) }} {{ $currencySymbol ?? platform_currency() }}</span>
              </div>
            </div>

            <form method="POST" action="{{ route('courses.receipt.submit', $currentCourse->id) }}" enctype="multipart/form-data" class="flex flex-col gap-4 text-xs">
              @csrf
              <div>
                <label class="font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('وسيلة التحويل:') }}</label>
                <select name="payment_method" required class="w-full h-11 px-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-white dark:bg-slate-800 font-bold text-slate-800 dark:text-slate-100">
                  <option value="vodafone_cash">{{ __('محفظة فودافون كاش (Vodafone Cash)') }}</option>
                  <option value="instapay">{{ __('تطبيق إنستاباي (InstaPay IPA)') }}</option>
                  <option value="bank_transfer">{{ __('تحويل بنكي مباشر (Bank Wire)') }}</option>
                </select>
              </div>

              <div>
                <label class="font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('المبلغ المحول:') }}</label>
                <input type="number" step="0.01" name="amount" value="{{ $currentCourse->price }}" required class="w-full h-11 px-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-white dark:bg-slate-800 font-bold text-[#012383] dark:text-blue-300" />
              </div>

              <div>
                <label class="font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('رقم هاتفك المحول منه أو حسابك:') }}</label>
                <input type="text" name="sender_account" placeholder="e.g. 01012345678" class="w-full h-11 px-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100" />
              </div>

              <div>
                <label class="font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('صورة الإيصال أو لقطة الشاشة:') }}</label>
                <input type="file" name="receipt_image" accept="image/*" required class="w-full h-11 p-2 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-white dark:bg-slate-800 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#EAF1FB] file:text-[#0B2A8F] dark:file:bg-slate-700 dark:file:text-blue-300 text-slate-800 dark:text-slate-100" />
              </div>

              <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#DEE8F7] dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-upload-receipt').classList.add('hidden')" class="px-4 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold transition-colors cursor-pointer">{{ __('إلغاء') }}</button>
                <button type="submit" class="px-6 h-10 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-xs transition-colors cursor-pointer">{{ __('تأكيد ورفع الإيصال') }}</button>
              </div>
            </form>
          </div>
        </div>
        @endif
    </x-slot:modals>

</x-layouts::app>
