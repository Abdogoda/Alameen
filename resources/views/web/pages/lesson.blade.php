<x-layouts::app :title="__('مشاهدة الدرس')">
    <!-- ============================================== -->
        <!-- 3. PAGE BODY CONTENT (Node 1155:18352)         -->
        <!-- ============================================== -->
        <div class="px-4 sm:px-6 lg:px-[40px] pt-6 lg:pt-[32px] pb-[40px] flex-1 flex flex-col gap-[24px]" data-node-id="1155:18352">
          
          <!-- Page Introduction (Node 1155:18353) -->
          <div class="flex flex-col gap-[4px] items-start text-start w-full" data-node-id="1155:18353">
            <p class="font-['Cairo'] font-semibold text-[12px] leading-[1.8] text-[#0B2A8F] w-full text-start" data-i18n="lesson.breadcrumb" data-node-id="1155:18354">
              مساحة التعلّم  /  دوراتي
            </p>
            <h1 class="font-['Cairo'] font-extrabold text-[26px] sm:text-[30px] md:text-[34px] leading-[1.3] text-[#012383] w-full text-start" data-i18n="lesson.pageTitle" data-node-id="1155:18355">
              تدريبات الوعي الصوتي
            </h1>
            <p class="font-['Cairo'] font-medium text-[13px] md:text-[14px] leading-[1.8] text-[#475569] w-full text-start" data-i18n="lesson.pageSubtitle" data-node-id="1155:18356">
              أساسيات التخاطب  /  المستوى الثاني: مهارات الصوت والاستماع  /  الدرس ٩
            </p>
          </div>

          <!-- Lesson Learning Environment Grid (Node 1155:18357) -->
          <div class="flex flex-col lg:flex-row gap-[24px] items-start w-full" data-node-id="1155:18357">
            
            <!-- Video and Lesson Information Column (Node 1155:18358 - Left in RTL, order-2) -->
            <div class="flex flex-1 flex-col gap-[18px] items-start min-w-0 w-full order-2 rtl:order-2 ltr:order-1" data-node-id="1155:18358">
              
              <!-- Returning Learner State Banner (Node 1155:18359) -->
              <div class="bg-[#EAF1FB] border border-[#DEE8F7] rounded-[14px] p-[16px] flex gap-[12px] items-center w-full shrink-0" data-node-id="1155:18359">
                <p class="flex-1 font-['Cairo'] font-semibold text-[13px] leading-[1.8] text-[#012383] text-start" data-i18n="lesson.returningBanner" data-node-id="1155:18360">
                  أهلًا بعودتك بعد تسجيل الدخول. موضعك السابق محفوظ عند ٠٨:٠٠.
                </p>
                <div class="size-[20px] shrink-0 relative" data-node-id="1155:19434">
                  <img src="/assets/images/icon-bookmark-check.svg" alt="" class="size-full" />
                </div>
              </div>

              <!-- Paused Lesson Player (Node 1155:18362) -->
              <div class="bg-[#152D4D] rounded-[16px] md:rounded-[20px] overflow-hidden flex flex-col w-full shrink-0 relative shadow-md" data-node-id="1155:18362">
                
                <!-- Instructional Video Frame (Node 1155:18363) -->
                <div class="w-full aspect-video md:h-[366px] relative overflow-hidden shrink-0 bg-[#0F2038]" data-node-id="1155:18363">
                  <img src="/assets/images/lesson-video-frame.png" alt="تدريبات الوعي الصوتي" class="size-full object-cover pointer-events-none" />
                  <div class="absolute inset-0 bg-[#152D4D]/25"></div>

                  <!-- Resume Playback Overlay Button (Node 1155:18388) -->
                  <button
                    type="button"
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 size-[54px] md:size-[60px] rounded-full bg-[#0B2A8F] flex items-center justify-center drop-shadow-[0px_4px_12px_rgba(11,42,143,0.45)] hover:scale-105 active:scale-95 transition-all cursor-pointer"
                    aria-label="تشغيل الفيديو"
                    data-node-id="1155:18388"
                  >
                    <div class="size-[22px] shrink-0 relative translate-x-0.5 rtl:-translate-x-0.5" data-node-id="1155:19437">
                      <img src="/assets/images/icon-play-overlay.svg" alt="" class="size-full" />
                    </div>
                  </button>
                </div>

                <!-- Video Playback Controls (Height: 82px - Node 1155:18390) -->
                <div class="h-[82px] px-[20px] py-[14px] flex flex-col gap-[12px] w-full shrink-0 bg-[#152D4D]" data-node-id="1155:18390">
                  
                  <!-- Time Scrubber Track (Node 1155:18391) -->
                  <div class="h-[5px] bg-white/25 rounded-[10px] w-full relative overflow-visible shrink-0 cursor-pointer" data-node-id="1155:18391">
                    <!-- Played portion 40% -->
                    <div class="absolute top-0 bottom-0 start-0 w-[40%] bg-white rounded-[10px]" data-node-id="1155:18392"></div>
                    <!-- Playhead -->
                    <div class="absolute top-[-4px] start-[40%] -translate-x-1/2 size-[13px] rounded-full bg-white shadow-md" data-node-id="1155:18393"></div>
                  </div>

                  <!-- Playback Actions (Node 1155:18394) -->
                  <div class="flex items-center justify-between w-full shrink-0" data-node-id="1155:18394">
                    
                    <!-- Left / Time and Playback (Node 1155:18395) -->
                    <div class="flex items-center gap-[18px] shrink-0" data-node-id="1155:18395">
                      <button type="button" class="size-[20px] shrink-0 cursor-pointer text-white hover:opacity-80" data-node-id="1155:19440">
                        <img src="/assets/images/icon-play-btn.svg" alt="" class="size-full" />
                      </button>
                      <button type="button" class="size-[20px] shrink-0 cursor-pointer text-white hover:opacity-80" data-node-id="1155:19443">
                        <img src="/assets/images/icon-volume-2.svg" alt="" class="size-full" />
                      </button>
                      <div class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-white flex items-center gap-[5px]" data-node-id="1155:18398">
                        <span data-i18n="lesson.currentTime">٠٨:٠٠</span>
                        <span class="text-[#DEE8F7]">/</span>
                        <span class="text-[#DEE8F7]" data-i18n="lesson.totalTime">٢٠:٠٠</span>
                      </div>
                    </div>

                    <!-- Right / Player Preferences (Node 1155:18402) -->
                    <div class="flex items-center gap-[20px] shrink-0 text-white" data-node-id="1155:18402">
                      <span class="font-['Cairo'] font-medium text-[12px] leading-[1.8] cursor-pointer" data-i18n="lesson.playbackSpeed" data-node-id="1155:18403">١×</span>
                      <button type="button" class="size-[20px] shrink-0 cursor-pointer hover:opacity-80" data-node-id="1155:19446">
                        <img src="/assets/images/icon-captions.svg" alt="" class="size-full" />
                      </button>
                      <button type="button" class="size-[20px] shrink-0 cursor-pointer hover:opacity-80" data-node-id="1155:19449">
                        <img src="/assets/images/icon-maximize.svg" alt="" class="size-full" />
                      </button>
                    </div>

                  </div>

                </div>

              </div>

              <!-- Resume Action and Saved State Row (Node 1155:18406) -->
              <div class="flex gap-[20px] items-center w-full shrink-0" data-node-id="1155:18406">
                <!-- Resume CTA Button (Node 3201:1054) -->
                <button
                  type="button"
                  class="bg-[#0B2A8F] rounded-[16px] px-[28px] py-[14px] text-white font-['Cairo'] font-bold text-[16px] shadow-[0px_4px_6px_rgba(11,42,143,0.12)] hover:bg-[#012383] transition-colors cursor-pointer shrink-0"
                  data-i18n="lesson.resumeCta"
                  data-node-id="3201:1054"
                >
                  متابعة من ٠٨:٠٠
                </button>
                <!-- Saved Progress Info (Node 1155:18422) -->
                <div class="flex flex-1 flex-col gap-[2px] items-start text-start min-w-0" data-node-id="1155:18422">
                  <p class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#012383] w-full text-start" data-i18n="lesson.resumeProgress" data-node-id="1155:18423">
                    ٤٠٪ مشاهدة • آخر موضع محفوظ ٠٨:٠٠
                  </p>
                  <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] w-full text-start" data-i18n="lesson.resumeSavedTime" data-node-id="1155:18424">
                    آخر حفظ: ٢٢ أكتوبر ٢٠٢٦، ٧:٤٥ م
                  </p>
                </div>
              </div>

              <!-- Lesson Details and Task Availability Card (Node 1155:18425) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[20px] p-[24px] flex flex-col gap-[18px] w-full shrink-0 shadow-sm" data-node-id="1155:18425">
                
                <!-- Lesson Tabs (Node 1155:18426) -->
                <div class="border-b border-[#DEE8F7] flex gap-[24px] items-start justify-end rtl:justify-end ltr:justify-start pb-[12px] w-full" data-node-id="1155:18426">
                  <!-- Inactive tab -->
                  <button type="button" class="font-['Cairo'] font-medium text-[14px] leading-[1.8] text-[#475569] hover:text-[#0B2A8F] cursor-pointer" data-i18n="lesson.tabTask" data-node-id="1155:18427">
                    المهمة • بعد الفيديو
                  </button>
                  <!-- Active tab -->
                  <button type="button" class="font-['Cairo'] font-bold text-[15px] leading-[1.8] text-[#0B2A8F] border-b-2 border-[#0B2A8F] pb-[10px] -mb-[12px] cursor-pointer" data-i18n="lesson.tabAbout" data-node-id="1155:18428">
                    عن الدرس
                  </button>
                </div>

                <!-- Learning Objectives Title (Node 1155:18429) -->
                <h3 class="font-['Cairo'] font-bold text-[19px] leading-[1.8] text-[#012383] text-start w-full" data-i18n="lesson.learningObjectivesTitle" data-node-id="1155:18429">
                  ما الذي ستتعلّمه؟
                </h3>

                <!-- Learning Objectives Description (Node 1155:18430) -->
                <p class="font-['Cairo'] font-medium text-[14px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="lesson.learningObjectivesDesc" data-node-id="1155:18430">
                  التعرّف على موضع الصوت في الكلمة، واختيار كلمات بسيطة لبناء نشاط وعي صوتي متدرّج.
                </p>

                <!-- Information Note (Node 1155:18431) -->
                <div class="bg-[#EAF1FB] rounded-[12px] p-[18px] flex gap-[14px] items-start w-full" data-node-id="1155:18431">
                  <!-- Text content (Node 1155:18432 - order-1) -->
                  <div class="flex flex-1 flex-col gap-[4px] items-start text-start min-w-0 order-1" data-node-id="1155:18432">
                    <h4 class="font-['Cairo'] font-bold text-[15px] leading-[1.8] text-[#012383] w-full text-start" data-i18n="lesson.lockedTaskTitle" data-node-id="1155:18433">
                      المهمة متاحة بعد إكمال هذا الفيديو
                    </h4>
                    <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] w-full text-start" data-i18n="lesson.lockedTaskDesc" data-node-id="1155:18434">
                      بعد الانتهاء من الدرس ٩ ستظهر تعليمات إعداد نشاط الوعي الصوتي وإرساله للمحاضر. لم تُفتح المهمة بعد؛ تقدّم مشاهدتك الحالي ٤٠٪.
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
                  محتوى الكورس
                </h2>

                <!-- Course Summary Card (Node 3201:1056) -->
                <div class="bg-[#EAF1FB] rounded-[16px] p-[20px] flex flex-col gap-[2px] w-full shrink-0 text-start" data-node-id="3201:1056">
                  <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.courseCardTitle">
                    أساسيات التخاطب
                  </p>
                  <p class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#012383]" data-i18n="lesson.courseCardPercent">
                    ٤٢٪
                  </p>
                  <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.courseCardVideos">
                    ٨ من ٢٠ فيديو مكتمل
                  </p>
                </div>

                <!-- Progress Bar (Node 3201:1058) -->
                <div class="bg-[#EAF1FB] h-[6px] rounded-[4px] relative overflow-hidden w-full shrink-0" data-node-id="3201:1058">
                  <div class="bg-[#0B2A8F] h-full rounded-[4px] w-[42%]"></div>
                </div>

                <!-- Level 1 (Completed 100% - Node 3201:1060) -->
                <div class="h-[46px] px-[12px] flex items-center rounded-[10px] w-full text-start shrink-0 hover:bg-[#FAFCFF] transition-colors cursor-pointer" data-node-id="3201:1060">
                  <p class="font-['Cairo'] font-normal text-[13px] leading-[1.65] text-[#475569] w-full text-start" data-i18n="lesson.level1Title">
                    المستوى الأول • مكتمل ١٠٠٪
                  </p>
                </div>

                <!-- Expanded Current Level 2 (Node 1155:18446) -->
                <div class="flex flex-col gap-[8px] items-start w-full shrink-0" data-node-id="1155:18446">
                  
                  <!-- Level 2 Active Pill Header (Node 3201:1062) -->
                  <div class="bg-[#0B2A8F] text-white rounded-[10px] h-[46px] px-[12px] flex items-center justify-between w-full shrink-0 cursor-pointer" data-node-id="3201:1062">
                    <span class="font-['Cairo'] font-semibold text-[13px] leading-[1.65] text-start flex-1" data-i18n="lesson.level2Title">
                      المستوى الثاني • ٦٨٪
                    </span>
                    <div class="size-[20px] shrink-0 relative">
                      <img src="/assets/images/icon-layout-dashboard.svg" alt="" class="size-full" />
                    </div>
                  </div>

                  <!-- Category Subheading (Node 1155:18448) -->
                  <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569] text-start w-full px-1" data-i18n="lesson.level2Category" data-node-id="1155:18448">
                    مهارات الصوت والاستماع
                  </p>

                  <!-- Lesson 6: التنفس ودعم الصوت (Node 1155:18449) -->
                  <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-[12px] flex flex-col gap-[3px] w-full text-start shrink-0 hover:bg-[#EAF1FB]/30 transition-colors cursor-pointer" data-node-id="1155:18449">
                    <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson6Title">
                      ٦. التنفس ودعم الصوت
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#216350]" data-i18n="lesson.lesson6Status">
                      مكتمل • ١٠٠٪  /  ٢٠ دقيقة
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson6Task">
                      المهمة: مُقيّمة
                    </p>
                  </div>

                  <!-- Lesson 7: الإيقاع وسرعة الكلام (Node 1155:18453) -->
                  <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-[12px] flex flex-col gap-[3px] w-full text-start shrink-0 hover:bg-[#EAF1FB]/30 transition-colors cursor-pointer" data-node-id="1155:18453">
                    <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson7Title">
                      ٧. الإيقاع وسرعة الكلام
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#216350]" data-i18n="lesson.lesson7Status">
                      مكتمل • ١٠٠٪  /  ٢٠ دقيقة
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson7Task">
                      المهمة: بانتظار المراجعة
                    </p>
                  </div>

                  <!-- Lesson 8: التمييز السمعي (Node 1155:18457) -->
                  <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-[12px] flex flex-col gap-[3px] w-full text-start shrink-0 hover:bg-[#EAF1FB]/30 transition-colors cursor-pointer" data-node-id="1155:18457">
                    <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson8Title">
                      ٨. التمييز السمعي
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#216350]" data-i18n="lesson.lesson8Status">
                      مكتمل • ١٠٠٪  /  ٢٠ دقيقة
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson8Task">
                      المهمة: لم تُسلّم
                    </p>
                  </div>

                  <!-- Lesson 9: تدريبات الوعي الصوتي (ACTIVE - Node 1155:18461) -->
                  <div class="bg-[#EAF1FB] border border-[#0B2A8F] rounded-[10px] p-[12px] flex flex-col gap-[3px] w-full text-start shrink-0 shadow-sm" data-node-id="1155:18461">
                    <p class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#0B2A8F]" data-i18n="lesson.lesson9Title">
                      ٩. تدريبات الوعي الصوتي
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson9Status">
                      قيد المشاهدة • ٤٠٪  /  ٢٠ دقيقة
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson9Task">
                      المهمة: بعد إكمال الفيديو
                    </p>
                  </div>

                  <!-- Lesson 10: مراجعة المهارات الصوتية (Node 1155:18465) -->
                  <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-[12px] flex flex-col gap-[3px] w-full text-start shrink-0 hover:bg-[#EAF1FB]/30 transition-colors cursor-pointer" data-node-id="1155:18465">
                    <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson10Title">
                      ١٠. مراجعة المهارات الصوتية
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson10Status">
                      لم يبدأ • ٠٪  /  ٢٠ دقيقة
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] leading-[1.8] text-[#475569]" data-i18n="lesson.lesson10Task">
                      المهمة: بعد إكمال الفيديو
                    </p>
                  </div>

                </div>

                <!-- Collapsed Level 3 (Node 1155:18469) -->
                <div class="flex items-center justify-between py-[10px] w-full text-start shrink-0 cursor-pointer hover:bg-[#FAFCFF] px-2 rounded-lg" data-node-id="1155:18469">
                  <span class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="lesson.level3Title">
                    المستوى الثالث • ٠٪
                  </span>
                  <div class="size-[16px] shrink-0 relative">
                    <img src="/assets/images/icon-chevron-down.svg" alt="" class="size-full" />
                  </div>
                </div>

                <!-- Collapsed Level 4 (Node 1155:18472) -->
                <div class="flex items-center justify-between py-[10px] w-full text-start shrink-0 cursor-pointer hover:bg-[#FAFCFF] px-2 rounded-lg" data-node-id="1155:18472">
                  <span class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="lesson.level4Title">
                    المستوى الرابع • ٠٪
                  </span>
                  <div class="size-[16px] shrink-0 relative">
                    <img src="/assets/images/icon-chevron-down.svg" alt="" class="size-full" />
                  </div>
                </div>

                <!-- View Curriculum CTA (Node 3201:1066) -->
                <a
                  href="{{ route('courses.show') }}"
                  class="bg-white border border-[#DEE8F7] rounded-[12px] h-[48px] flex items-center justify-center text-[#0B2A8F] font-['Cairo'] font-bold text-[14px] hover:bg-[#EAF1FB] transition-colors cursor-pointer w-full shrink-0 shadow-sm"
                  data-i18n="lesson.viewCurriculumBtn"
                  data-node-id="3201:1066"
                >
                  عرض المنهج كاملًا
                </a>

              </div>

            </div>

          </div>

        </div>

</x-layouts::app>
