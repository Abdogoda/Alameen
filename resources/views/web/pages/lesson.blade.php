<x-layouts::app :title="__('مشاهدة الدرس')">
    <!-- ============================================== -->
    <!-- 3. PAGE BODY CONTENT (Node 1155:18352)         -->
    <!-- ============================================== -->
    <div class="flex flex-col gap-6 w-full text-start" data-node-id="1155:18352">
          
          <!-- Page Introduction (Node 1155:18353) -->
          <div class="flex flex-col gap-[4px] items-start text-start w-full" data-node-id="1155:18353">
            <p class="font-['Cairo'] font-semibold text-[12px] leading-[1.8] text-[#0B2A8F] w-full text-start" data-i18n="lesson.breadcrumb" data-node-id="1155:18354">
              {{ __('lesson.breadcrumb') }}
            </p>
            <h1 class="font-['Cairo'] font-extrabold text-[26px] sm:text-[30px] md:text-[34px] leading-[1.3] text-[#012383] w-full text-start" data-i18n="lesson.pageTitle" data-node-id="1155:18355">
              {{ __('lesson.pageTitle') }}
            </h1>
            <p class="font-['Cairo'] font-medium text-[13px] md:text-[14px] leading-[1.8] text-[#475569] w-full text-start" data-i18n="lesson.pageSubtitle" data-node-id="1155:18356">
              {{ __('lesson.pageSubtitle') }}
            </p>
          </div>

          <!-- Lesson Learning Environment Grid (Node 1155:18357) -->
          <div class="flex flex-col lg:flex-row gap-[24px] items-start w-full" data-node-id="1155:18357">
            
            <!-- Video and Lesson Information Column (Node 1155:18358 - Left in RTL, order-2) -->
            <div class="flex flex-1 flex-col gap-[18px] items-start min-w-0 w-full order-2 rtl:order-2 ltr:order-1" data-node-id="1155:18358">
              
              <!-- Returning Learner State Banner (Node 1155:18359) -->
              <div class="bg-[#EAF1FB] border border-[#DEE8F7] rounded-[14px] p-[16px] flex gap-[12px] items-center w-full shrink-0" data-node-id="1155:18359">
                <p class="flex-1 font-['Cairo'] font-semibold text-[13px] leading-[1.8] text-[#012383] text-start" data-i18n="lesson.returningBanner" data-node-id="1155:18360">
                  {{ __('lesson.returningBanner') }}
                </p>
                <div class="size-[20px] shrink-0 relative" data-node-id="1155:19434">
                  <img src="/assets/images/icon-bookmark-check.svg" alt="" class="size-full" />
                </div>
              </div>

              @if(!$canAccess)
                <!-- Sequential Lock Card -->
                <div class="w-full p-8 rounded-2xl bg-slate-900 border border-amber-500/30 text-white flex flex-col items-center justify-center text-center gap-4">
                  <div class="size-14 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-500/30">
                    <svg class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                  </div>
                  <div class="flex flex-col items-center gap-1.5 max-w-md">
                    <h3 class="font-bold text-lg text-amber-300 font-['Cairo']">{{ __('المحاضرة مقيدة بالتسلسل الإلزامي') }}</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                      {{ __('حرصاً على التدرج السريري للمنهج، لا يمكنك فتح هذه المحاضرة أو هذا المستوى حتى تكمل المحاضرة الحالية أولاً. بمجرد إكمال أي درس أو مستوى يمكنك فتحه ومراجعته في أي وقت.') }}
                    </p>
                  </div>
                  @php
                    $curUnlockedId = $curriculumStatus['current_video_id'] ?? null;
                    $curUnlockedVid = $curUnlockedId ? $videos->firstWhere('id', $curUnlockedId) : null;
                  @endphp
                  @if($curUnlockedVid && $curUnlockedVid->id !== $video->id)
                    <a 
                      href="{{ route('lessons.show', [$course->slug, $curUnlockedVid->slug]) }}"
                      class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0B2A8F] hover:bg-[#012383] text-white font-['Cairo'] font-bold text-[13px] shadow-sm transition-all cursor-pointer"
                    >
                      <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
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

              <!-- Resume Action and Saved State Row (Node 1155:18406) -->
              <div class="flex gap-[20px] items-center w-full shrink-0" data-node-id="1155:18406">
                <!-- Resume CTA Button (Node 3201:1054) -->
                <button
                  type="button"
                  class="bg-[#0B2A8F] rounded-[16px] px-[28px] py-[14px] text-white font-['Cairo'] font-bold text-[16px] shadow-[0px_4px_6px_rgba(11,42,143,0.12)] hover:bg-[#012383] transition-colors cursor-pointer shrink-0"
                  data-i18n="lesson.resumeCta"
                  data-node-id="3201:1054"
                >
                  {{ __('lesson.resumeCta') }}
                </button>
                <!-- Saved Progress Info (Node 1155:18422) -->
                <div class="flex flex-1 flex-col gap-[2px] items-start text-start min-w-0" data-node-id="1155:18422">
                  <p class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#012383] w-full text-start" data-i18n="lesson.resumeProgress" data-node-id="1155:18423">
                    {{ __('lesson.resumeProgress') }}
                  </p>
                  <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] w-full text-start" data-i18n="lesson.resumeSavedTime" data-node-id="1155:18424">
                    {{ __('lesson.resumeSavedTime') }}
                  </p>
                </div>
              </div>

              <!-- Lesson Details and Task Availability Card (Node 1155:18425) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[20px] p-[24px] flex flex-col gap-[18px] w-full shrink-0 shadow-sm" data-node-id="1155:18425">
                
                <!-- Lesson Tabs (Node 1155:18426) -->
                <div class="border-b border-[#DEE8F7] flex gap-[24px] items-start justify-end rtl:justify-end ltr:justify-start pb-[12px] w-full" data-node-id="1155:18426">
                  <!-- Inactive tab -->
                  <button type="button" class="font-['Cairo'] font-medium text-[14px] leading-[1.8] text-[#475569] hover:text-[#0B2A8F] cursor-pointer" data-i18n="lesson.tabTask" data-node-id="1155:18427">
                    {{ __('lesson.tabTask') }}
                  </button>
                  <!-- Active tab -->
                  <button type="button" class="font-['Cairo'] font-bold text-[15px] leading-[1.8] text-[#0B2A8F] border-b-2 border-[#0B2A8F] pb-[10px] -mb-[12px] cursor-pointer" data-i18n="lesson.tabAbout" data-node-id="1155:18428">
                    {{ __('lesson.tabAbout') }}
                  </button>
                </div>

                <!-- Learning Objectives Title (Node 1155:18429) -->
                <h3 class="font-['Cairo'] font-bold text-[19px] leading-[1.8] text-[#012383] text-start w-full" data-i18n="lesson.learningObjectivesTitle" data-node-id="1155:18429">
                  {{ __('lesson.learningObjectivesTitle') }}
                </h3>

                <!-- Learning Objectives Description (Node 1155:18430) -->
                <p class="font-['Cairo'] font-medium text-[14px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="lesson.learningObjectivesDesc" data-node-id="1155:18430">
                  {{ __('lesson.learningObjectivesDesc') }}
                </p>

                <!-- Information Note (Node 1155:18431) -->
                <div class="bg-[#EAF1FB] rounded-[12px] p-[18px] flex gap-[14px] items-start w-full" data-node-id="1155:18431">
                  <!-- Text content (Node 1155:18432 - order-1) -->
                  <div class="flex flex-1 flex-col gap-[4px] items-start text-start min-w-0 order-1" data-node-id="1155:18432">
                    <h4 class="font-['Cairo'] font-bold text-[15px] leading-[1.8] text-[#012383] w-full text-start" data-i18n="lesson.lockedTaskTitle" data-node-id="1155:18433">
                      {{ __('lesson.lockedTaskTitle') }}
                    </h4>
                    <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] w-full text-start" data-i18n="lesson.lockedTaskDesc" data-node-id="1155:18434">
                      {{ __('lesson.lockedTaskDesc') }}
                    </p>
                  </div>
                  <!-- Lock Icon (Node 1155:19452 - order-2) -->
                  <div class="size-[20px] shrink-0 relative order-2" data-node-id="1155:19452">
                    <img src="/assets/images/icon-lock-keyhole.svg" alt="" class="size-full" />
                  </div>
                </div>

              </div>

            </div>

            <!-- Curriculum Navigation Column (Node 1155:18436 - Right in RTL) -->
            <div class="w-full lg:w-[310px] shrink-0 flex flex-col items-start order-1 rtl:order-1 ltr:order-2" data-node-id="1155:18436">
              
              <!-- Course Outline Container (Node 1155:18437) -->
              <div class="w-full bg-white border border-[#DEE8F7] rounded-[16px] md:rounded-[20px] p-[20px] flex flex-col gap-[14px] shadow-sm" data-node-id="1155:18437">
                
                <!-- Section Title (Node 1155:18438) -->
                <h2 class="font-['Cairo'] font-bold text-[18px] md:text-[20px] leading-[1.4] text-[#012383] text-start w-full" data-i18n="lesson.curriculumTitle" data-node-id="1155:18438">
                  {{ __('lesson.curriculumTitle') }}
                </h2>

                <!-- Course Summary Card (Node 3201:1056) -->
                <div class="bg-[#EAF1FB] rounded-[16px] p-[20px] flex flex-col gap-[2px] w-full shrink-0 text-start" data-node-id="3201:1056">
                  <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.courseCardTitle">
                    {{ __('lesson.courseCardTitle') }}
                  </p>
                  <p class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#012383]" data-i18n="lesson.courseCardPercent">
                    {{ __('lesson.courseCardPercent') }}
                  </p>
                  <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.courseCardVideos">
                    {{ __('lesson.courseCardVideos') }}
                  </p>
                </div>

                <!-- Progress Bar (Node 3201:1058) -->
                <div class="bg-[#EAF1FB] h-[6px] rounded-[4px] relative overflow-hidden w-full shrink-0" data-node-id="3201:1058">
                  <div class="bg-[#0B2A8F] h-full rounded-[4px] w-[42%]"></div>
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
                      <div class="flex flex-col gap-2 w-full shrink-0 border rounded-[14px] overflow-hidden p-3 transition-all {{ $isCurrentLevel ? 'bg-[#FAFCFF] border-[#0B2A8F]/40 ring-1 ring-[#0B2A8F]/20' : ($isLvlAccessible ? 'bg-white border-[#DEE8F7]' : 'bg-slate-50/70 border-slate-200/80') }}">
                        <div class="flex items-center justify-between gap-2">
                          <div class="flex items-center gap-1.5 flex-1 min-w-0">
                            @if($isLvlCompleted)
                              <span class="size-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0" title="{{ __('مستوى مكتمل') }}">
                                <svg class="size-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                              </span>
                            @elseif(! $isLvlAccessible)
                              <span class="size-4 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center shrink-0" title="{{ __('مستوى مغلق') }}">
                                <svg class="size-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                              </span>
                            @endif
                            <span class="font-['Cairo'] font-bold text-[13px] leading-[1.65] {{ $isLvlAccessible ? 'text-[#012383]' : 'text-slate-500' }} truncate">
                              {{ $pLevel->title_ar }}
                            </span>
                          </div>
                          @if($isLvlCompleted)
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2 py-0.5 rounded-full shrink-0">
                              {{ __('مكتمل') }}
                            </span>
                          @elseif(! $isLvlAccessible)
                            <span class="text-[10px] font-bold text-slate-500 bg-slate-200/70 px-2 py-0.5 rounded-full shrink-0">
                              {{ __('مغلق') }}
                            </span>
                          @else
                            <span class="text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full shrink-0">
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
                              class="rounded-[10px] p-[10px] flex flex-col gap-[2px] w-full text-start shrink-0 transition-all {{ $isActive ? 'bg-[#0B2A8F] text-white shadow-xs' : ($isAccessible ? ($isCompleted ? 'bg-[#F0FDF4] hover:bg-[#DCFCE7] text-slate-800 border border-emerald-200/60' : 'bg-[#FAFCFF] hover:bg-[#EAF1FB] text-slate-700 border border-[#DEE8F7]/60') : 'bg-slate-100/80 hover:bg-slate-200/80 text-slate-400 border border-slate-200/60') }}"
                            >
                              <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 flex-1 min-w-0">
                                  @if($isCompleted && ! $isActive)
                                    <svg class="size-3.5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                  @elseif(! $isAccessible && ! $isActive)
                                    <svg class="size-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                  @endif
                                  <p class="font-['Cairo'] font-bold text-[12px] leading-[1.6] {{ $isActive ? 'text-white' : ($isAccessible ? ($isCompleted ? 'text-emerald-900' : 'text-[#012383]') : 'text-slate-500') }} truncate">
                                    {{ $lVideo->title_ar }}
                                  </p>
                                </div>
                                @if($isActive)
                                  <span class="text-[10px] bg-white/20 text-white px-1.5 py-0.2 rounded font-bold shrink-0">{{ __('الدرس الحالي') }}</span>
                                @elseif($isCompleted)
                                  <span class="text-[10px] bg-emerald-100 text-emerald-800 px-1.5 py-0.2 rounded font-bold shrink-0">{{ __('مكتمل') }}</span>
                                @elseif(! $isAccessible)
                                  <span class="text-[10px] bg-slate-200 text-slate-500 px-1.5 py-0.2 rounded font-bold shrink-0">{{ __('مغلق') }}</span>
                                @elseif($lVideo->is_free_preview)
                                  <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.2 rounded font-bold shrink-0">{{ __('معاينة مجانية') }}</span>
                                @endif
                              </div>
                              <div class="flex items-center gap-2 text-[10px] {{ $isActive ? 'text-blue-100' : ($isAccessible ? 'text-slate-400' : 'text-slate-400') }}">
                                <span>{{ __(':min دقيقة', ['min' => $lVideo->duration_minutes]) }}</span>
                                @if($lVideo->clinicalTask)
                                  <span>•</span>
                                  <span>{{ __('مهمة سريرية') }}</span>
                                @endif
                                @if($isCompleted && ! $isActive)
                                  <span>•</span>
                                  <span class="text-emerald-600 font-medium">{{ __('يمكنك مراجعته بأي وقت') }}</span>
                                @endif
                              </div>
                            </a>
                          @empty
                            <p class="text-xs text-slate-400 px-2 py-1">{{ __('لا توجد دروس منشورة في هذا المستوى.') }}</p>
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
                        class="rounded-[10px] p-[10px] flex flex-col gap-[2px] w-full text-start shrink-0 transition-all {{ $isActive ? 'bg-[#0B2A8F] text-white shadow-xs' : ($isAccessible ? ($isCompleted ? 'bg-[#F0FDF4] hover:bg-[#DCFCE7] text-slate-800 border border-emerald-200/60' : 'bg-[#FAFCFF] hover:bg-[#EAF1FB] text-slate-700 border border-[#DEE8F7]/60') : 'bg-slate-100/80 hover:bg-slate-200/80 text-slate-400 border border-slate-200/60') }}"
                      >
                        <div class="flex items-center justify-between gap-2">
                          <div class="flex items-center gap-1.5 flex-1 min-w-0">
                            @if($isCompleted && ! $isActive)
                              <svg class="size-3.5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            @elseif(! $isAccessible && ! $isActive)
                              <svg class="size-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            @endif
                            <p class="font-['Cairo'] font-bold text-[12px] leading-[1.6] {{ $isActive ? 'text-white' : ($isAccessible ? ($isCompleted ? 'text-emerald-900' : 'text-[#012383]') : 'text-slate-500') }} truncate">
                              {{ $lVideo->title_ar }}
                            </p>
                          </div>
                          @if($isActive)
                            <span class="text-[10px] bg-white/20 text-white px-1.5 py-0.2 rounded font-bold shrink-0">{{ __('الدرس الحالي') }}</span>
                          @elseif($isCompleted)
                            <span class="text-[10px] bg-emerald-100 text-emerald-800 px-1.5 py-0.2 rounded font-bold shrink-0">{{ __('مكتمل') }}</span>
                          @elseif(! $isAccessible)
                            <span class="text-[10px] bg-slate-200 text-slate-500 px-1.5 py-0.2 rounded font-bold shrink-0">{{ __('مغلق') }}</span>
                          @elseif($lVideo->is_free_preview)
                            <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.2 rounded font-bold shrink-0">{{ __('معاينة مجانية') }}</span>
                          @endif
                        </div>
                        <div class="flex items-center gap-2 text-[10px] {{ $isActive ? 'text-blue-100' : 'text-slate-400' }}">
                          <span>{{ __(':min دقيقة', ['min' => $lVideo->duration_minutes]) }}</span>
                          @if($isCompleted && ! $isActive)
                            <span>•</span>
                            <span class="text-emerald-600 font-medium">{{ __('يمكنك مراجعته بأي وقت') }}</span>
                          @endif
                        </div>
                      </a>
                    @endforeach
                  </div>
                @else
                <!-- Level 1 (Completed 100% - Node 3201:1060) -->
                <div class="h-[46px] px-[12px] flex items-center rounded-[10px] w-full text-start shrink-0 hover:bg-[#FAFCFF] transition-colors cursor-pointer" data-node-id="3201:1060">
                  <p class="font-['Cairo'] font-normal text-[13px] leading-[1.65] text-[#475569] w-full text-start" data-i18n="lesson.level1Title">
                    {{ __('lesson.level1Title') }}
                  </p>
                </div>

                <!-- Expanded Current Level 2 (Node 1155:18446) -->
                <div class="flex flex-col gap-[8px] items-start w-full shrink-0" data-node-id="1155:18446">
                  
                  <!-- Level 2 Active Pill Header (Node 3201:1062) -->
                  <div class="bg-[#0B2A8F] text-white rounded-[10px] h-[46px] px-[12px] flex items-center justify-between w-full shrink-0 cursor-pointer" data-node-id="3201:1062">
                    <span class="font-['Cairo'] font-semibold text-[13px] leading-[1.65] text-start flex-1" data-i18n="lesson.level2Title">
                      {{ __('lesson.level2Title') }}
                    </span>
                    <div class="size-[20px] shrink-0 relative">
                      <img src="/assets/images/icon-layout-dashboard.svg" alt="" class="size-full" />
                    </div>
                  </div>

                  <!-- Category Subheading (Node 1155:18448) -->
                  <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569] text-start w-full px-1" data-i18n="lesson.level2Category" data-node-id="1155:18448">
                    {{ __('lesson.level2Category') }}
                  </p>

                  <!-- Lesson 6: التنفس ودعم الصوت (Node 1155:18449) -->
                  <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-[12px] flex flex-col gap-[3px] w-full text-start shrink-0 hover:bg-[#EAF1FB]/30 transition-colors cursor-pointer" data-node-id="1155:18449">
                    <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson6Title">
                      {{ __('lesson.lesson6Title') }}
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#216350]" data-i18n="lesson.lesson6Status">
                      {{ __('lesson.lesson6Status') }}
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson6Task">
                      {{ __('lesson.lesson6Task') }}
                    </p>
                  </div>

                  <!-- Lesson 7: الإيقاع وسرعة الكلام (Node 1155:18453) -->
                  <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-[12px] flex flex-col gap-[3px] w-full text-start shrink-0 hover:bg-[#EAF1FB]/30 transition-colors cursor-pointer" data-node-id="1155:18453">
                    <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson7Title">
                      {{ __('lesson.lesson7Title') }}
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#216350]" data-i18n="lesson.lesson7Status">
                      {{ __('lesson.lesson7Status') }}
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson7Task">
                      {{ __('lesson.lesson7Task') }}
                    </p>
                  </div>

                  <!-- Lesson 8: التمييز السمعي (Node 1155:18457) -->
                  <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-[12px] flex flex-col gap-[3px] w-full text-start shrink-0 hover:bg-[#EAF1FB]/30 transition-colors cursor-pointer" data-node-id="1155:18457">
                    <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson8Title">
                      {{ __('lesson.lesson8Title') }}
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#216350]" data-i18n="lesson.lesson8Status">
                      {{ __('lesson.lesson8Status') }}
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson8Task">
                      {{ __('lesson.lesson8Task') }}
                    </p>
                  </div>

                  <!-- Lesson 9: تدريبات الوعي الصوتي (ACTIVE - Node 1155:18461) -->
                  <div class="bg-[#EAF1FB] border border-[#0B2A8F] rounded-[10px] p-[12px] flex flex-col gap-[3px] w-full text-start shrink-0 shadow-sm" data-node-id="1155:18461">
                    <p class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#0B2A8F]" data-i18n="lesson.lesson9Title">
                      {{ __('lesson.lesson9Title') }}
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson9Status">
                      {{ __('lesson.lesson9Status') }}
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson9Task">
                      {{ __('lesson.lesson9Task') }}
                    </p>
                  </div>

                  <!-- Lesson 10: مراجعة المهارات الصوتية (Node 1155:18465) -->
                  <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-[12px] flex flex-col gap-[3px] w-full text-start shrink-0 hover:bg-[#EAF1FB]/30 transition-colors cursor-pointer" data-node-id="1155:18465">
                    <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson10Title">
                      {{ __('lesson.lesson10Title') }}
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson10Status">
                      {{ __('lesson.lesson10Status') }}
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson10Task">
                      {{ __('lesson.lesson10Task') }}
                    </p>
                  </div>

                </div>

                <!-- Collapsed Level 3 (Node 1155:18469) -->
                <div class="flex items-center justify-between py-[10px] w-full text-start shrink-0 cursor-pointer hover:bg-[#FAFCFF] px-2 rounded-lg" data-node-id="1155:18469">
                  <span class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="lesson.level3Title">
                    {{ __('lesson.level3Title') }}
                  </span>
                  <div class="size-[16px] shrink-0 relative">
                    <img src="/assets/images/icon-chevron-down.svg" alt="" class="size-full" />
                  </div>
                </div>

                <!-- Collapsed Level 4 (Node 1155:18472) -->
                <div class="flex items-center justify-between py-[10px] w-full text-start shrink-0 cursor-pointer hover:bg-[#FAFCFF] px-2 rounded-lg" data-node-id="1155:18472">
                  <span class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="lesson.level4Title">
                    {{ __('lesson.level4Title') }}
                  </span>
                  <div class="size-[16px] shrink-0 relative">
                    <img src="/assets/images/icon-chevron-down.svg" alt="" class="size-full" />
                  </div>
                </div>
                @endif

                <!-- View Curriculum CTA (Node 3201:1066) -->
                <a
                  href="{{ route('courses.show') }}"
                  class="bg-white border border-[#DEE8F7] rounded-[12px] h-[48px] flex items-center justify-center text-[#0B2A8F] font-['Cairo'] font-bold text-[14px] hover:bg-[#EAF1FB] transition-colors cursor-pointer w-full shrink-0 shadow-sm"
                  data-i18n="lesson.viewCurriculumBtn"
                  data-node-id="3201:1066"
                >
                  {{ __('lesson.viewCurriculumBtn') }}
                </a>

              </div>

            </div>

          </div>

        </div>

</x-layouts::app>
