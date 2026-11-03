<x-layouts::app :title="__('تفاصيل الدورة والمنهج')">
        <!-- Page Content (Node 1155:17916) -->
        <div class="flex flex-col gap-6 w-full text-start" data-node-id="1155:17916">
          
          <!-- Page Introduction (Node 1155:17917) -->
          <div class="flex flex-col gap-[4px] items-start text-start w-full" data-node-id="1155:17917">
            <p class="font-['Cairo'] font-semibold text-[12px] leading-[1.8] text-[#0B2A8F] text-start w-full" data-i18n="courses.breadcrumb" data-node-id="1155:17918">{{ __('courses.breadcrumb') }}</p>
            <h1 class="font-['Cairo'] font-bold text-[28px] sm:text-[32px] leading-[1.3] text-[#012383] text-start w-full" data-i18n="courses.courseTitle" data-node-id="1155:17919">{{ __('courses.courseTitle') }}</h1>
            <p class="font-['Cairo'] font-medium text-[13px] sm:text-[14px] leading-[1.7] text-[#475569] text-start w-full" data-i18n="courses.courseSubtitle" data-node-id="1155:17920">{{ __('courses.courseSubtitle') }}</p>
          </div>

          <!-- Course Progress Overview Card (Node 1155:17921) -->
          <div class="bg-gradient-to-l from-[#EAF1FB] via-[#F4F8FD] to-white border border-[#DEE8F7] rounded-[16px] p-6 flex flex-col items-start w-full shadow-xs" data-node-id="1155:17921">
            <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-center w-full justify-between" data-node-id="1155:17922">
              
              <!-- Right in RTL: Course Viewing Progress (Node 1155:17938) -->
              <div class="flex flex-1 flex-col gap-[8px] items-start min-w-0 w-full" data-node-id="1155:17938">
                <div class="flex items-center justify-between font-['Cairo'] font-bold text-[#012383] text-start w-full" data-node-id="1155:17939">
                  <span class="text-[15px]" data-i18n="courses.progressLabel" data-node-id="1155:17941">{{ __('courses.progressLabel') }}</span>
                  <span class="text-[22px] sm:text-[24px] text-[#0B2A8F]" data-i18n="courses.progressPercent" data-node-id="1155:17940">{{ __('courses.progressPercent') }}</span>
                </div>
                <div class="bg-[#DEE8F7] h-[8px] rounded-full w-full relative overflow-hidden" data-node-id="1155:17942">
                  <div class="bg-gradient-to-r from-[#0B2A8F] to-[#012383] h-full rounded-full w-[42%]" data-node-id="1155:17943"></div>
                </div>
                <p class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569] text-start w-full" data-i18n="courses.progressMeta" data-node-id="1155:17944">{{ __('courses.progressMeta') }}</p>
              </div>

@php
    $currentCourse = \App\Models\Course::first();
    $hasActiveAccess = auth()->check() && $currentCourse ? app(\App\Services\Course\CourseEnrollmentService::class)->hasActiveAccess(auth()->user(), $currentCourse) : false;
    $cmsSettings = app(\App\Services\Cms\SettingsService::class);
@endphp

              <!-- Left in RTL: CTA Button -->
              @if($hasActiveAccess)
                <a
                  href="{{ route('lessons.show') }}"
                  class="bg-[#0B2A8F] hover:bg-[#012383] rounded-[10px] h-[46px] px-6 flex items-center justify-center text-white font-['Cairo'] font-bold text-[14px] shadow-sm hover:shadow transition-all shrink-0 w-full sm:w-auto"
                >
                  {{ __('استئناف مشاهدة المحاضرات') }}
                </a>
              @else
                <button
                  type="button"
                  onclick="document.getElementById('modal-upload-receipt').classList.remove('hidden')"
                  class="bg-emerald-600 hover:bg-emerald-700 rounded-[10px] h-[46px] px-6 flex items-center justify-center text-white font-['Cairo'] font-bold text-[14px] shadow-sm hover:shadow transition-all shrink-0 w-full sm:w-auto cursor-pointer"
                >
                  {{ __('💳 سداد وتفعيل الاشتراك بالإيصال') }}
                </button>
              @endif

            </div>
          </div>

          <!-- Curriculum and Learning Companion Grid (Node 1155:17945) -->
          <div class="flex flex-col lg:flex-row gap-[24px] items-start w-full" data-node-id="1155:17945">
            
            <!-- Right in RTL: Complete Curriculum Column (Node 1155:18000) -->
            <div class="flex-1 min-w-0 flex flex-col gap-[18px] w-full" data-node-id="1155:18000">
              
              <!-- Section Heading (Node 1155:18001) -->
              <div class="flex items-center justify-between text-start w-full" data-node-id="1155:18001">
                <h2 class="font-['Cairo'] font-bold text-[19px] sm:text-[20px] leading-[1.4] text-[#012383]" data-i18n="courses.curriculumTitle" data-node-id="1155:18003">{{ __('courses.curriculumTitle') }}</h2>
                <p class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569] bg-white border border-[#DEE8F7] px-3 py-0.5 rounded-full whitespace-nowrap" data-i18n="courses.curriculumMeta" data-node-id="1155:18002">{{ __('courses.curriculumMeta') }}</p>
              </div>
              <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="courses.curriculumDesc" data-node-id="1155:18004">{{ __('courses.curriculumDesc') }}</p>

              <!-- Level 1 (Node 1155:18005 - 100% Completed) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] overflow-hidden w-full" data-node-id="1155:18005">
                <div class="bg-[#FAFCFF] p-[20px] flex flex-col gap-[10px] w-full" data-node-id="1155:18006">
                  <div class="flex gap-[12px] items-center w-full" data-node-id="1155:18007">
                    <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18010">
                      <h4 class="font-['Cairo'] font-bold text-[18px] leading-[1.8] text-[#012383]" data-i18n="courses.level1Title" data-node-id="1155:18011">{{ __('courses.level1Title') }}</h4>
                      <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569]" data-i18n="courses.level1Meta" data-node-id="1155:18012">{{ __('courses.level1Meta') }}</p>
                    </div>
                    <span class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#0B2A8F] whitespace-nowrap" data-i18n="courses.level1Percent" data-node-id="1155:18009">{{ __('courses.level1Percent') }}</span>
                    <div class="size-[18px] shrink-0 relative cursor-pointer" data-node-id="1155:19374">
                      <img src="/assets/images/icon-chevron-up.svg" alt="" class="size-full" />
                    </div>
                  </div>
                  <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden" data-node-id="1155:18013">
                    <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full" data-node-id="1155:18014"></div>
                  </div>
                </div>

                <!-- Lesson 1 (Node 1155:18015) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18015">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18026">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18023">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l1Title">{{ __('courses.l1Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18019">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">{{ __('courses.lessonCompleted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18016">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL1">{{ __('courses.taskL1') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">{{ __('courses.taskGraded') }}</p>
                  </div>
                </div>

                <!-- Lesson 2 (Node 1155:18028) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18028">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18039">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18036">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l2Title">{{ __('courses.l2Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18032">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">{{ __('courses.lessonCompleted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18029">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL2">{{ __('courses.taskL2') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">{{ __('courses.taskGraded') }}</p>
                  </div>
                </div>

                <!-- Lesson 3 (Node 1155:18041) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18041">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18052">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18049">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l3Title">{{ __('courses.l3Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18045">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">{{ __('courses.lessonCompleted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18042">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL3">{{ __('courses.taskL3') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">{{ __('courses.taskGraded') }}</p>
                  </div>
                </div>

                <!-- Lesson 4 (Node 1155:18054) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18054">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18065">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18062">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l4Title">{{ __('courses.l4Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18058">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">{{ __('courses.lessonCompleted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18055">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL4">{{ __('courses.taskL4') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">{{ __('courses.taskGraded') }}</p>
                  </div>
                </div>

                <!-- Lesson 5 (Node 1155:18067) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18067">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18078">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18075">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l5Title">{{ __('courses.l5Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18071">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">{{ __('courses.lessonCompleted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18068">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL5">{{ __('courses.taskL5') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">{{ __('courses.taskGraded') }}</p>
                  </div>
                </div>

              </div>

              <!-- Level 2 (Node 1155:18080 - 68% In Progress - Current Level) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] overflow-hidden w-full" data-node-id="1155:18080">
                <div class="bg-[#FAFCFF] p-[20px] flex flex-col gap-[10px] w-full" data-node-id="1155:18081">
                  <div class="flex gap-[12px] items-center w-full" data-node-id="1155:18082">
                    <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18085">
                      <h4 class="font-['Cairo'] font-bold text-[18px] leading-[1.8] text-[#012383]" data-i18n="courses.level2Title" data-node-id="1155:18086">{{ __('courses.level2Title') }}</h4>
                      <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569]" data-i18n="courses.level2Meta" data-node-id="1155:18087">{{ __('courses.level2Meta') }}</p>
                    </div>
                    <span class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#0B2A8F] whitespace-nowrap" data-i18n="courses.level2Percent" data-node-id="1155:18084">{{ __('courses.level2Percent') }}</span>
                    <div class="size-[18px] shrink-0 relative cursor-pointer" data-node-id="1155:19392">
                      <img src="/assets/images/icon-chevron-up.svg" alt="" class="size-full" />
                    </div>
                  </div>
                  <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden" data-node-id="1155:18088">
                    <div class="bg-[#0B2A8F] h-[7px] rounded-full w-[68%]" data-node-id="1155:18089"></div>
                  </div>
                </div>

                <!-- Lesson 6 (Node 1155:18090) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18090">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18101">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18098">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l6Title">{{ __('courses.l6Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18094">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">{{ __('courses.lessonCompleted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18091">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL6">{{ __('courses.taskL6') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">{{ __('courses.taskGraded') }}</p>
                  </div>
                </div>

                <!-- Lesson 7 (Node 1155:18103) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18103">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18114">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18111">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l7Title">{{ __('courses.l7Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18107">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">{{ __('courses.lessonCompleted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18104">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL7">{{ __('courses.taskL7') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskPending">{{ __('courses.taskPending') }}</p>
                  </div>
                </div>

                <!-- Lesson 8 (Node 1155:18116) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18116">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18127">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18124">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l8Title">{{ __('courses.l8Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18120">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">{{ __('courses.lessonCompleted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18117">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL8">{{ __('courses.taskL8') }}</p>
                    <p class="font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]" data-i18n="courses.taskNotSubmitted">{{ __('courses.taskNotSubmitted') }}</p>
                  </div>
                </div>

                <!-- Lesson 9 - ACTIVE (Node 1155:18129) -->
                <a
                  href="{{ route('lessons.show') }}"
                  class="bg-[#EAF1FB] border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full block hover:bg-[#DEE8F7]/70 transition-colors"
                  data-node-id="1155:18129"
                >
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]" data-node-id="1155:18140">
                    ٩
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18137">
                    <h5 class="font-['Cairo'] font-bold text-[14px] text-[#012383]" data-i18n="courses.l9Title" data-node-id="1155:18138">{{ __('courses.l9Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.l9Meta" data-node-id="1155:18139">{{ __('courses.l9Meta') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18133">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonInProgress">{{ __('courses.lessonInProgress') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden" data-node-id="1155:18135">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-[40%]" data-node-id="1155:18136"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18130">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL9">{{ __('courses.taskL9') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </a>

                <!-- Lesson 10 (Node 1155:18142) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18142">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]" data-node-id="1155:18152">
                    ١٠
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18149">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l10Title">{{ __('courses.l10Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18146">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">{{ __('courses.lessonNotStarted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden" data-node-id="1155:18148"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18143">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL10">{{ __('courses.taskL10') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </div>

              </div>

              <!-- Level 3 (Node 1155:18154 - 0% Language Skills Development) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] overflow-hidden w-full" data-node-id="1155:18154">
                <div class="bg-[#FAFCFF] p-[20px] flex flex-col gap-[10px] w-full" data-node-id="1155:18155">
                  <div class="flex gap-[12px] items-center w-full" data-node-id="1155:18156">
                    <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18159">
                      <h4 class="font-['Cairo'] font-bold text-[18px] leading-[1.8] text-[#012383]" data-i18n="courses.level3Title" data-node-id="1155:18160">{{ __('courses.level3Title') }}</h4>
                      <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569]" data-i18n="courses.level3Meta" data-node-id="1155:18161">{{ __('courses.level3Meta') }}</p>
                    </div>
                    <span class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#0B2A8F] whitespace-nowrap" data-i18n="courses.level3Percent" data-node-id="1155:18158">{{ __('courses.level3Percent') }}</span>
                    <div class="size-[18px] shrink-0 relative cursor-pointer" data-node-id="1155:19404">
                      <img src="/assets/images/icon-chevron-up.svg" alt="" class="size-full" />
                    </div>
                  </div>
                  <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden" data-node-id="1155:18162"></div>
                </div>

                <!-- Lessons 11 to 15 -->
                <!-- Lesson 11 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١١
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l11Title">{{ __('courses.l11Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">{{ __('courses.lessonNotStarted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL11">{{ __('courses.taskL11') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </div>

                <!-- Lesson 12 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٢
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l12Title">{{ __('courses.l12Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">{{ __('courses.lessonNotStarted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL12">{{ __('courses.taskL12') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </div>

                <!-- Lesson 13 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٣
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l13Title">{{ __('courses.l13Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">{{ __('courses.lessonNotStarted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL13">{{ __('courses.taskL13') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </div>

                <!-- Lesson 14 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٤
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l14Title">{{ __('courses.l14Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">{{ __('courses.lessonNotStarted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL14">{{ __('courses.taskL14') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </div>

                <!-- Lesson 15 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٥
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l15Title">{{ __('courses.l15Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">{{ __('courses.lessonNotStarted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL15">{{ __('courses.taskL15') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </div>

              </div>

              <!-- Level 4 (Node 1155:18207 - 0% Application & Practice) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] overflow-hidden w-full" data-node-id="1155:18207">
                <div class="bg-[#FAFCFF] p-[20px] flex flex-col gap-[10px] w-full">
                  <div class="flex gap-[12px] items-center w-full">
                    <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                      <h4 class="font-['Cairo'] font-bold text-[18px] leading-[1.8] text-[#012383]" data-i18n="courses.level4Title">{{ __('courses.level4Title') }}</h4>
                      <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569]" data-i18n="courses.level4Meta">{{ __('courses.level4Meta') }}</p>
                    </div>
                    <span class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#0B2A8F] whitespace-nowrap" data-i18n="courses.level4Percent">{{ __('courses.level4Percent') }}</span>
                    <div class="size-[18px] shrink-0 relative cursor-pointer">
                      <img src="/assets/images/icon-chevron-up.svg" alt="" class="size-full" />
                    </div>
                  </div>
                  <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden"></div>
                </div>

                <!-- Lessons 16 to 20 -->
                <!-- Lesson 16 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٦
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l16Title">{{ __('courses.l16Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">{{ __('courses.lessonNotStarted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL16">{{ __('courses.taskL16') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </div>

                <!-- Lesson 17 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٧
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l17Title">{{ __('courses.l17Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">{{ __('courses.lessonNotStarted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL17">{{ __('courses.taskL17') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </div>

                <!-- Lesson 18 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٨
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l18Title">{{ __('courses.l18Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">{{ __('courses.lessonNotStarted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL18">{{ __('courses.taskL18') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </div>

                <!-- Lesson 19 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٩
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l19Title">{{ __('courses.l19Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">{{ __('courses.lessonNotStarted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL19">{{ __('courses.taskL19') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </div>

                <!-- Lesson 20 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ٢٠
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l20Title">{{ __('courses.l20Title') }}</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">{{ __('courses.duration20min') }}</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">{{ __('courses.lessonNotStarted') }}</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL20">{{ __('courses.taskL20') }}</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">{{ __('courses.taskAfterVideo') }}</p>
                  </div>
                </div>

              </div>

            </div>

            <!-- Left in RTL: Course Companion Widgets (w: 270px - Node 1155:17946) -->
            <div class="w-[270px] flex flex-col gap-[20px] shrink-0" data-node-id="1155:17946">
              
              <!-- Widget 1: Current Place Card (Node 1155:17947) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-[24px] flex flex-col gap-[16px] w-full" data-node-id="1155:17947">
                <div class="bg-[#EAF1FB] rounded-full px-[10px] py-[3px] w-fit" data-node-id="1155:17948">
                  <p class="font-['Cairo'] font-bold text-[12px] leading-[1.8] text-[#0B2A8F]" data-i18n="courses.companionBadge" data-node-id="1155:17949">{{ __('courses.companionBadge') }}</p>
                </div>
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-[21px] leading-[1.45] text-[#012383] text-start" data-i18n="courses.companionTitle" data-node-id="1155:17950">{{ __('courses.companionTitle') }}</h3>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.companionLevel" data-node-id="1155:17951">{{ __('courses.companionLevel') }}</p>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.companionDesc" data-node-id="1155:17952">{{ __('courses.companionDesc') }}</p>
                <a
                  href="{{ route('lessons.show') }}"
                  class="bg-[#0B2A8F] border border-[#0B2A8F] rounded-[10px] h-[46px] w-full flex items-center justify-center px-[18px] py-[8px] text-white font-['Cairo'] font-bold text-[14px] hover:bg-[#012383] transition-colors"
                  data-i18n="courses.companionResumeBtn"
                  data-node-id="1155:17953"
                >{{ __('courses.companionResumeBtn') }}</a>
              </div>

              <!-- Widget 2: Course Tasks Overview (Node 1155:17968) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-[24px] flex flex-col gap-[16px] w-full" data-node-id="1155:17968">
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-[22px] leading-[1.45] text-[#012383] text-start" data-i18n="courses.tasksOverviewTitle" data-node-id="1155:17970">{{ __('courses.tasksOverviewTitle') }}</h3>
                <p class="font-['Cairo'] font-medium text-[14px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.tasksMeta1" data-node-id="1155:17971">{{ __('courses.tasksMeta1') }}</p>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.tasksMeta2" data-node-id="1155:17972">{{ __('courses.tasksMeta2') }}</p>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.tasksMeta3" data-node-id="1155:17973">{{ __('courses.tasksMeta3') }}</p>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.tasksMeta4" data-node-id="1155:17974">{{ __('courses.tasksMeta4') }}</p>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.tasksMeta5" data-node-id="1155:17975">{{ __('courses.tasksMeta5') }}</p>
                <a
                  href="{{ route('assignments.index') }}"
                  class="bg-white border border-[#DEE8F7] rounded-[10px] h-[46px] w-full flex items-center justify-center px-[18px] py-[8px] text-[#0B2A8F] font-['Cairo'] font-bold text-[14px] hover:bg-[#EAF1FB] transition-colors"
                  data-i18n="courses.openTask8Btn"
                  data-node-id="1155:17976"
                >{{ __('courses.openTask8Btn') }}</a>
              </div>

              <!-- Widget 3: Info Callout Note (Node 1155:17991) -->
              <div class="bg-[#EAF1FB] rounded-[10px] p-[18px] flex gap-[14px] items-center w-full" data-node-id="1155:17991">
                <div class="flex-1 flex flex-col gap-[4px] items-start text-start" data-node-id="3118:154">
                  <p class="font-['Cairo'] font-bold text-[15px] leading-[1.8] text-[#012383] text-start" data-i18n="courses.noteTitle" data-node-id="1155:17993">{{ __('courses.noteTitle') }}</p>
                  <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.noteDesc" data-node-id="1155:17994">{{ __('courses.noteDesc') }}</p>
                </div>
                <div class="size-[20px] shrink-0 relative" data-node-id="1155:19368">
                  <img src="/assets/images/icon-info-circle.svg" alt="" class="size-full" />
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



