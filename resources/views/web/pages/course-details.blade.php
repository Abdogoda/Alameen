<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة مركز الأمين | تفاصيل الدورة والمنهج</title>
    <!-- Google Fonts Preconnect and Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@500;600;700;800&family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
    <!-- Stylesheet with Tailwind and Design Tokens -->
    <link rel="stylesheet" href="/src/css/styles.css" />
  </head>
  <body class="bg-[#FAFCFF] min-h-screen text-[#475569] font-['Cairo'] antialiased">
    <div class="max-w-[1440px] mx-auto min-h-screen flex flex-row bg-white shadow-sm" id="app-canvas" data-node-id="1155:17898">
      
      <!-- ============================================== -->
      <!-- 1. PRIMARY NAVIGATION SIDEBAR (248px Width)     -->
      <!-- Positioned on the RIGHT in RTL                  -->
      <!-- ============================================== -->
      <aside class="w-[248px] bg-white border-l border-[#DEE8F7] flex flex-col justify-between pb-[24px] pt-[22px] px-[20px] shrink-0 min-h-screen order-1" data-node-id="1155:18239">
        
        <div class="flex flex-col gap-[28px] w-full">
          <!-- Al-Ameen Brand Identity (Real Figma Logo) -->
          <a href="{{ route('home') }}" class="flex gap-[8px] h-[62px] items-center justify-end w-full" data-node-id="1155:18240">
            <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-[25px] leading-[1.45] text-[#012383] whitespace-nowrap" data-i18n="brand.name" data-node-id="1155:18241">
              منصة مركز الأمين
            </span>
            <div class="size-[54px] shrink-0 relative" data-node-id="1155:18242">
              <img src="/assets/images/logo.png" alt="منصة مركز الأمين" class="size-full object-contain pointer-events-none" />
            </div>
          </a>

          <!-- Student Navigation Group -->
          <div class="flex flex-col gap-[7px] items-start w-full" data-node-id="1155:18243">
            <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full px-2" data-i18n="nav.section" data-node-id="1155:18244">
              التعلّم الخاص بك
            </p>
            
            <!-- لوحة الطالب -->
            <a
              href="{{ route('dashboard') }}"
              class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors"
              data-node-id="1155:18245"
            >
              <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start flex-1" data-i18n="nav.dashboard" data-node-id="1155:18246">لوحة الطالب</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:18247">
                <img src="/assets/images/icon-dashboard.svg" alt="" class="size-full" />
              </div>
            </a>

            <!-- دوراتي - ACTIVE (Node 1155:18255) -->
            <a
              href="{{ route('courses.show') }}"
              class="bg-[#EAF1FB] flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full"
              data-node-id="1155:18255"
            >
              <span class="font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#0B2A8F] text-start flex-1" data-i18n="nav.courses" data-node-id="1155:18256">دوراتي</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19413">
                <img src="/assets/images/icon-courses-active.svg" alt="" class="size-full" />
              </div>
            </a>

            <!-- المهام -->
            <a
              href="{{ route('assignments.index') }}"
              class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors"
              data-node-id="1155:18258"
            >
              <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start flex-1" data-i18n="nav.assignments" data-node-id="1155:18259">المهام</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19416">
                <img src="/assets/images/icon-assignments.svg" alt="" class="size-full" />
              </div>
            </a>

            <!-- درجاتي -->
            <a
              href="{{ route('grades.index') }}"
              class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors"
              data-node-id="1155:18261"
            >
              <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start flex-1" data-i18n="nav.grades" data-node-id="1155:18262">درجاتي</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19419">
                <img src="/assets/images/icon-grades.svg" alt="" class="size-full" />
              </div>
            </a>

            <!-- شهاداتي -->
            <a
              href="{{ route('certificates.index') }}"
              class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors"
              data-node-id="1155:18264"
            >
              <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start flex-1" data-i18n="nav.certificates" data-node-id="1155:18265">شهاداتي</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19422">
                <img src="/assets/images/icon-certificates.svg" alt="" class="size-full" />
              </div>
            </a>

            <!-- حسابي -->
            <a
              href="{{ route('user.profile') }}"
              class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors"
              data-node-id="1155:18267"
            >
              <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start flex-1" data-i18n="nav.profile" data-node-id="1155:18268">حسابي</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19425">
                <img src="/assets/images/icon-profile.svg" alt="" class="size-full" />
              </div>
            </a>
          </div>
        </div>

        <!-- Sign Out (Node 1155:18274) -->
        <a
          href="{{ route('login') }}"
          class="flex gap-[12px] h-[46px] items-center p-[12px] rounded-[10px] w-full text-[#475569] hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer shrink-0 mt-auto"
          data-node-id="1155:18274"
        >
          <span class="flex-1 font-['Cairo'] font-medium text-[13px] leading-[1.8] text-start" data-i18n="nav.signout" data-node-id="1155:18275">
            تسجيل الخروج
          </span>
          <div class="size-[18px] shrink-0 relative" data-node-id="1155:19431">
            <img src="/assets/images/icon-logout-gray.svg" alt="" class="size-full" />
          </div>
        </a>

      </aside>

      <!-- ============================================== -->
      <!-- 2. MAIN LEARNING WORKSPACE (Width: 1192px)     -->
      <!-- Positioned on the LEFT in RTL                  -->
      <!-- ============================================== -->
      <main class="flex-1 min-w-0 flex flex-col min-h-screen bg-[#FAFCFF] order-2" data-node-id="1155:17899">
        
        <!-- Account Bar (Height: 80px - Node 1155:17900) -->
        <header class="h-[80px] bg-[#FAFCFF] border-b border-[#DEE8F7] px-[40px] flex items-center justify-between shrink-0" data-node-id="1155:17900">
          
          <!-- Search Courses Input (Node 1155:17910) -->
          <div class="w-[330px] h-[42px] bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] px-[14px] flex items-center gap-[12px] focus-within:border-[#0B2A8F] focus-within:ring-1 focus-within:ring-[#0B2A8F] transition-all order-1">
            <input
              type="text"
              class="w-full bg-transparent text-[13px] text-[#012383] placeholder-[#475569] outline-none font-medium text-start font-['Cairo']"
              placeholder="ابحثي في دوراتك ودروسك"
              data-i18n-placeholder="header.searchPlaceholder"
              data-node-id="1155:17911"
            />
            <div class="size-[19px] shrink-0 relative" data-node-id="1155:17912">
              <img src="/assets/images/icon-search.svg" alt="" class="size-full" />
            </div>
          </div>

          <!-- Student Account & Language Switcher Group (Node 1155:17901) -->
          <div class="flex items-center gap-[16px] order-2">
            
            <!-- Language Switcher Pill Dropdown -->
            <div class="relative" id="lang-switcher">
              <button
                type="button"
                id="lang-toggle-btn"
                aria-haspopup="true"
                aria-expanded="false"
                class="flex items-center gap-[8px] h-[38px] px-[14px] rounded-full border border-[#DEE8F7] bg-[#EAF1FB]/50 text-[#012383] font-['Cairo'] font-semibold text-[13px] hover:bg-[#EAF1FB] transition-all cursor-pointer"
              >
                <svg class="size-[16px] text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                <span id="current-lang-label" data-i18n="header.currentLang">العربية</span>
                <svg class="size-[12px] text-[#475569] lang-caret transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div
                id="lang-dropdown"
                class="hidden absolute start-0 top-[calc(100%+8px)] w-40 rounded-2xl bg-white shadow-xl border border-[#DEE8F7] p-1.5 z-50 transition-all flex-col gap-1"
              >
                <button
                  type="button"
                  data-set-lang="ar"
                  class="w-full flex items-center justify-between px-3 py-2 text-xs text-[#012383] hover:bg-[#EAF1FB] rounded-xl font-bold cursor-pointer text-start transition-colors"
                >
                  <span>العربية</span>
                  <svg class="size-4 text-[#0B2A8F] lang-check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
                <button
                  type="button"
                  data-set-lang="en"
                  class="w-full flex items-center justify-between px-3 py-2 text-xs text-[#475569] hover:bg-[#EAF1FB] rounded-xl font-bold cursor-pointer text-start transition-colors"
                >
                  <span>English</span>
                  <svg class="size-4 text-[#0B2A8F] lang-check-icon opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Notifications (Node 1155:17908) -->
            <button type="button" class="size-[42px] flex items-center justify-center rounded-full hover:bg-slate-100 transition-colors relative cursor-pointer" data-node-id="1155:17908">
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19365">
                <img src="/assets/images/icon-bell.svg" alt="" class="size-full" />
              </div>
            </button>

            <!-- Student Profile Summary (Node 1155:17906) -->
            <div class="flex items-center gap-[12px]">
              <div class="size-[38px] rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[#0B2A8F] text-[15px]" data-node-id="1155:17906">
                <span data-i18n="header.studentInitial" data-node-id="1155:17907">أ</span>
              </div>
              <span class="font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#012383] whitespace-nowrap" data-i18n="header.studentName" data-node-id="1155:17905">
                أحمد إبراهيم
              </span>
              <div class="size-[16px] shrink-0 relative cursor-pointer" data-node-id="1155:17902">
                <img src="/assets/images/icon-chevron-down.svg" alt="" class="size-full" />
              </div>
            </div>

          </div>

        </header>

        <!-- Page Content (Node 1155:17916) -->
        <div class="px-4 sm:px-6 lg:px-[40px] pt-[24px] lg:pt-[30px] pb-[40px] flex flex-col gap-[24px] w-full" data-node-id="1155:17916">
          
          <!-- Page Introduction (Node 1155:17917) -->
          <div class="flex flex-col gap-[4px] items-start text-start w-full" data-node-id="1155:17917">
            <p class="font-['Cairo'] font-semibold text-[12px] leading-[1.8] text-[#0B2A8F] text-start w-full" data-i18n="courses.breadcrumb" data-node-id="1155:17918">
              مساحة التعلّم / كورساتي
            </p>
            <h1 class="font-['Cairo'] font-bold text-[28px] sm:text-[32px] leading-[1.3] text-[#012383] text-start w-full" data-i18n="courses.courseTitle" data-node-id="1155:17919">
              أساسيات التخاطب
            </h1>
            <p class="font-['Cairo'] font-medium text-[13px] sm:text-[14px] leading-[1.7] text-[#475569] text-start w-full" data-i18n="courses.courseSubtitle" data-node-id="1155:17920">
              خطة واضحة من المفاهيم إلى الممارسة. لكل فيديو مهمة مستقلة بعد إكماله.
            </p>
          </div>

          <!-- Course Progress Overview Card (Node 1155:17921) -->
          <div class="bg-gradient-to-l from-[#EAF1FB] via-[#F4F8FD] to-white border border-[#DEE8F7] rounded-[16px] p-6 flex flex-col items-start w-full shadow-xs" data-node-id="1155:17921">
            <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-center w-full justify-between" data-node-id="1155:17922">
              
              <!-- Right in RTL: Course Viewing Progress (Node 1155:17938) -->
              <div class="flex flex-1 flex-col gap-[8px] items-start min-w-0 w-full" data-node-id="1155:17938">
                <div class="flex items-center justify-between font-['Cairo'] font-bold text-[#012383] text-start w-full" data-node-id="1155:17939">
                  <span class="text-[15px]" data-i18n="courses.progressLabel" data-node-id="1155:17941">تقدّم المشاهدة في الكورس</span>
                  <span class="text-[22px] sm:text-[24px] text-[#0B2A8F]" data-i18n="courses.progressPercent" data-node-id="1155:17940">٤٢٪</span>
                </div>
                <div class="bg-[#DEE8F7] h-[8px] rounded-full w-full relative overflow-hidden" data-node-id="1155:17942">
                  <div class="bg-gradient-to-r from-[#0B2A8F] to-[#012383] h-full rounded-full w-[42%]" data-node-id="1155:17943"></div>
                </div>
                <p class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569] text-start w-full" data-i18n="courses.progressMeta" data-node-id="1155:17944">
                  ١٦٨ من ٤٠٠ دقيقة • ٨ فيديوهات مكتملة من ٢٠ • ٤ مستويات
                </p>
              </div>

              <!-- Left in RTL: CTA Button (Node 1155:17923) -->
              <a
                href="{{ route('lessons.show') }}"
                class="bg-[#0B2A8F] hover:bg-[#012383] rounded-[10px] h-[46px] px-6 flex items-center justify-center text-white font-['Cairo'] font-bold text-[14px] shadow-sm hover:shadow transition-all shrink-0 w-full sm:w-auto"
                data-i18n="courses.resumeLesson9"
                data-node-id="1155:17923"
              >
                استئناف الدرس ٩
              </a>

            </div>
          </div>

          <!-- Curriculum and Learning Companion Grid (Node 1155:17945) -->
          <div class="flex flex-col lg:flex-row gap-[24px] items-start w-full" data-node-id="1155:17945">
            
            <!-- Right in RTL: Complete Curriculum Column (Node 1155:18000) -->
            <div class="flex-1 min-w-0 flex flex-col gap-[18px] w-full" data-node-id="1155:18000">
              
              <!-- Section Heading (Node 1155:18001) -->
              <div class="flex items-center justify-between text-start w-full" data-node-id="1155:18001">
                <h2 class="font-['Cairo'] font-bold text-[19px] sm:text-[20px] leading-[1.4] text-[#012383]" data-i18n="courses.curriculumTitle" data-node-id="1155:18003">
                  محتوى الكورس
                </h2>
                <p class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569] bg-white border border-[#DEE8F7] px-3 py-0.5 rounded-full whitespace-nowrap" data-i18n="courses.curriculumMeta" data-node-id="1155:18002">
                  ٢٠ فيديو • ٢٠ مهمة
                </p>
              </div>
              <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="courses.curriculumDesc" data-node-id="1155:18004">
                لكل درس: عنوان الفيديو، تقدّم المشاهدة، ثم حالة المهمة المرتبطة به.
              </p>

              <!-- Level 1 (Node 1155:18005 - 100% Completed) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] overflow-hidden w-full" data-node-id="1155:18005">
                <div class="bg-[#FAFCFF] p-[20px] flex flex-col gap-[10px] w-full" data-node-id="1155:18006">
                  <div class="flex gap-[12px] items-center w-full" data-node-id="1155:18007">
                    <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18010">
                      <h4 class="font-['Cairo'] font-bold text-[18px] leading-[1.8] text-[#012383]" data-i18n="courses.level1Title" data-node-id="1155:18011">
                        المستوى الأول: مدخل إلى التخاطب
                      </h4>
                      <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569]" data-i18n="courses.level1Meta" data-node-id="1155:18012">
                        ٥ فيديوهات • ١٠٠ دقيقة • ٥ مهام
                      </p>
                    </div>
                    <span class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#0B2A8F] whitespace-nowrap" data-i18n="courses.level1Percent" data-node-id="1155:18009">
                      ١٠٠٪
                    </span>
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
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l1Title">مدخل إلى التواصل والتخاطب</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18019">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">مكتمل • ١٠٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18016">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL1">مهمة الدرس ١</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">مُقيّمة</p>
                  </div>
                </div>

                <!-- Lesson 2 (Node 1155:18028) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18028">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18039">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18036">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l2Title">مكوّنات عملية التواصل</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18032">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">مكتمل • ١٠٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18029">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL2">مهمة الدرس ٢</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">مُقيّمة</p>
                  </div>
                </div>

                <!-- Lesson 3 (Node 1155:18041) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18041">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18052">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18049">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l3Title">الإنصات والملاحظة</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18045">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">مكتمل • ١٠٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18042">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL3">مهمة الدرس ٣</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">مُقيّمة</p>
                  </div>
                </div>

                <!-- Lesson 4 (Node 1155:18054) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18054">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18065">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18062">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l4Title">بيئة التعلّم الداعمة</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18058">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">مكتمل • ١٠٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18055">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL4">مهمة الدرس ٤</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">مُقيّمة</p>
                  </div>
                </div>

                <!-- Lesson 5 (Node 1155:18067) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18067">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18078">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18075">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l5Title">مراجعة المفاهيم الأساسية</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18071">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">مكتمل • ١٠٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18068">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL5">مهمة الدرس ٥</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">مُقيّمة</p>
                  </div>
                </div>

              </div>

              <!-- Level 2 (Node 1155:18080 - 68% In Progress - Current Level) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] overflow-hidden w-full" data-node-id="1155:18080">
                <div class="bg-[#FAFCFF] p-[20px] flex flex-col gap-[10px] w-full" data-node-id="1155:18081">
                  <div class="flex gap-[12px] items-center w-full" data-node-id="1155:18082">
                    <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18085">
                      <h4 class="font-['Cairo'] font-bold text-[18px] leading-[1.8] text-[#012383]" data-i18n="courses.level2Title" data-node-id="1155:18086">
                        المستوى الثاني: مهارات الصوت والاستماع
                      </h4>
                      <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569]" data-i18n="courses.level2Meta" data-node-id="1155:18087">
                        ٥ فيديوهات • ١٠٠ دقيقة • ٥ مهام
                      </p>
                    </div>
                    <span class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#0B2A8F] whitespace-nowrap" data-i18n="courses.level2Percent" data-node-id="1155:18084">
                      ٦٨٪
                    </span>
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
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l6Title">التنفس ودعم الصوت</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18094">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">مكتمل • ١٠٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18091">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL6">مهمة الدرس ٦</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskGraded">مُقيّمة</p>
                  </div>
                </div>

                <!-- Lesson 7 (Node 1155:18103) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18103">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18114">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18111">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l7Title">الإيقاع وسرعة الكلام</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18107">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">مكتمل • ١٠٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18104">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL7">مهمة الدرس ٧</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskPending">بانتظار المراجعة</p>
                  </div>
                </div>

                <!-- Lesson 8 (Node 1155:18116) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18116">
                  <div class="size-[30px] rounded-full bg-[#EEF7F3] flex items-center justify-center shrink-0" data-node-id="1155:18127">
                    <img src="/assets/images/icon-check-green.svg" alt="" class="size-[16px]" />
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18124">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l8Title">التمييز السمعي</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18120">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#216350]" data-i18n="courses.lessonCompleted">مكتمل • ١٠٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-full"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18117">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL8">مهمة الدرس ٨</p>
                    <p class="font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]" data-i18n="courses.taskNotSubmitted">لم تُسلّم</p>
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
                    <h5 class="font-['Cairo'] font-bold text-[14px] text-[#012383]" data-i18n="courses.l9Title" data-node-id="1155:18138">تدريبات الوعي الصوتي</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.l9Meta" data-node-id="1155:18139">٢٠ دقيقة • الموضع المحفوظ ٠٨:٠٠</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18133">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonInProgress">قيد المشاهدة • ٤٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden" data-node-id="1155:18135">
                      <div class="bg-[#0B2A8F] h-[7px] rounded-full w-[40%]" data-node-id="1155:18136"></div>
                    </div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18130">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL9">مهمة الدرس ٩</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </a>

                <!-- Lesson 10 (Node 1155:18142) -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18142">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]" data-node-id="1155:18152">
                    ١٠
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18149">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l10Title">مراجعة المهارات الصوتية</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start" data-node-id="1155:18146">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">لم يبدأ • ٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full relative overflow-hidden" data-node-id="1155:18148"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end" data-node-id="1155:18143">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL10">مهمة الدرس ١٠</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </div>

              </div>

              <!-- Level 3 (Node 1155:18154 - 0% Language Skills Development) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] overflow-hidden w-full" data-node-id="1155:18154">
                <div class="bg-[#FAFCFF] p-[20px] flex flex-col gap-[10px] w-full" data-node-id="1155:18155">
                  <div class="flex gap-[12px] items-center w-full" data-node-id="1155:18156">
                    <div class="flex-1 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18159">
                      <h4 class="font-['Cairo'] font-bold text-[18px] leading-[1.8] text-[#012383]" data-i18n="courses.level3Title" data-node-id="1155:18160">
                        المستوى الثالث: بناء مهارات اللغة
                      </h4>
                      <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569]" data-i18n="courses.level3Meta" data-node-id="1155:18161">
                        ٥ فيديوهات • ١٠٠ دقيقة • ٥ مهام
                      </p>
                    </div>
                    <span class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#0B2A8F] whitespace-nowrap" data-i18n="courses.level3Percent" data-node-id="1155:18158">
                      ٠٪
                    </span>
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
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l11Title">الانتباه المشترك</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">لم يبدأ • ٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL11">مهمة الدرس ١١</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </div>

                <!-- Lesson 12 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٢
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l12Title">التقليد اللفظي</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">لم يبدأ • ٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL12">مهمة الدرس ١٢</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </div>

                <!-- Lesson 13 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٣
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l13Title">توسيع المفردات</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">لم يبدأ • ٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL13">مهمة الدرس ١٣</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </div>

                <!-- Lesson 14 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٤
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l14Title">بناء الجملة البسيطة</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">لم يبدأ • ٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL14">مهمة الدرس ١٤</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </div>

                <!-- Lesson 15 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٥
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l15Title">توظيف الأسئلة</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">لم يبدأ • ٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL15">مهمة الدرس ١٥</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </div>

              </div>

              <!-- Level 4 (Node 1155:18207 - 0% Application & Practice) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] overflow-hidden w-full" data-node-id="1155:18207">
                <div class="bg-[#FAFCFF] p-[20px] flex flex-col gap-[10px] w-full">
                  <div class="flex gap-[12px] items-center w-full">
                    <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                      <h4 class="font-['Cairo'] font-bold text-[18px] leading-[1.8] text-[#012383]" data-i18n="courses.level4Title">
                        المستوى الرابع: التطبيق والممارسة
                      </h4>
                      <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569]" data-i18n="courses.level4Meta">
                        ٥ فيديوهات • ١٠٠ دقيقة • ٥ مهام
                      </p>
                    </div>
                    <span class="font-['Cairo'] font-bold text-[13px] leading-[1.8] text-[#0B2A8F] whitespace-nowrap" data-i18n="courses.level4Percent">
                      ٠٪
                    </span>
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
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l16Title">اختيار أنشطة التدريب</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">لم يبدأ • ٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL16">مهمة الدرس ١٦</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </div>

                <!-- Lesson 17 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٧
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l17Title">تدرج صعوبة النشاط</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">لم يبدأ • ٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL17">مهمة الدرس ١٧</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </div>

                <!-- Lesson 18 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٨
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l18Title">تسجيل الملاحظات</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">لم يبدأ • ٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL18">مهمة الدرس ١٨</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </div>

                <!-- Lesson 19 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ١٩
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l19Title">مراجعة التقدم</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">لم يبدأ • ٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL19">مهمة الدرس ١٩</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </div>

                <!-- Lesson 20 -->
                <div class="border-t border-[#DEE8F7] flex gap-[16px] items-center px-[16px] py-[13px] w-full hover:bg-slate-50 transition-colors">
                  <div class="size-[30px] rounded-full bg-[#FAFCFF] border border-[#DEE8F7] flex items-center justify-center shrink-0 font-['Cairo'] font-bold text-[12px] text-[#0B2A8F]">
                    ٢٠
                  </div>
                  <div class="flex-1 flex flex-col gap-[2px] items-start text-start">
                    <h5 class="font-['Cairo'] font-semibold text-[14px] text-[#012383]" data-i18n="courses.l20Title">خطة الممارسة المستمرة</h5>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.duration20min">٢٠ دقيقة</p>
                  </div>
                  <div class="w-[140px] flex flex-col gap-[5px] items-start">
                    <p class="font-['Cairo'] font-semibold text-[12px] text-[#0B2A8F]" data-i18n="courses.lessonNotStarted">لم يبدأ • ٠٪</p>
                    <div class="bg-[#DEE8F7] h-[7px] rounded-full w-full"></div>
                  </div>
                  <div class="w-[143px] flex flex-col gap-[2px] items-end text-end">
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="courses.taskL20">مهمة الدرس ٢٠</p>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="courses.taskAfterVideo">بعد إكمال الفيديو</p>
                  </div>
                </div>

              </div>

            </div>

            <!-- Left in RTL: Course Companion Widgets (w: 270px - Node 1155:17946) -->
            <div class="w-[270px] flex flex-col gap-[20px] shrink-0" data-node-id="1155:17946">
              
              <!-- Widget 1: Current Place Card (Node 1155:17947) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-[24px] flex flex-col gap-[16px] w-full" data-node-id="1155:17947">
                <div class="bg-[#EAF1FB] rounded-full px-[10px] py-[3px] w-fit" data-node-id="1155:17948">
                  <p class="font-['Cairo'] font-bold text-[12px] leading-[1.8] text-[#0B2A8F]" data-i18n="courses.companionBadge" data-node-id="1155:17949">
                    درسك الحالي
                  </p>
                </div>
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-[21px] leading-[1.45] text-[#012383] text-start" data-i18n="courses.companionTitle" data-node-id="1155:17950">
                  تدريبات الوعي الصوتي
                </h3>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.companionLevel" data-node-id="1155:17951">
                  المستوى الثاني • الدرس ٩
                </p>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.companionDesc" data-node-id="1155:17952">
                  توقّفت عند ٠٨:٠٠ من ٢٠:٠٠، ويمكنك المتابعة من الموضع نفسه بعد تسجيل الدخول.
                </p>
                <a
                  href="{{ route('lessons.show') }}"
                  class="bg-[#0B2A8F] border border-[#0B2A8F] rounded-[10px] h-[46px] w-full flex items-center justify-center px-[18px] py-[8px] text-white font-['Cairo'] font-bold text-[14px] hover:bg-[#012383] transition-colors"
                  data-i18n="courses.companionResumeBtn"
                  data-node-id="1155:17953"
                >
                  متابعة من ٠٨:٠٠
                </a>
              </div>

              <!-- Widget 2: Course Tasks Overview (Node 1155:17968) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-[24px] flex flex-col gap-[16px] w-full" data-node-id="1155:17968">
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-[22px] leading-[1.45] text-[#012383] text-start" data-i18n="courses.tasksOverviewTitle" data-node-id="1155:17970">
                  مهام الكورس
                </h3>
                <p class="font-['Cairo'] font-medium text-[14px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.tasksMeta1" data-node-id="1155:17971">
                  ٢٠ مهمة؛ واحدة لكل فيديو
                </p>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.tasksMeta2" data-node-id="1155:17972">
                  ٦ مُقيّمة
                </p>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.tasksMeta3" data-node-id="1155:17973">
                  ١ بانتظار المراجعة
                </p>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.tasksMeta4" data-node-id="1155:17974">
                  ١ متاحة ولم تُسلّم
                </p>
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.tasksMeta5" data-node-id="1155:17975">
                  ١٢ متاحة بعد إكمال فيديوهاتها
                </p>
                <a
                  href="{{ route('assignments.index') }}"
                  class="bg-white border border-[#DEE8F7] rounded-[10px] h-[46px] w-full flex items-center justify-center px-[18px] py-[8px] text-[#0B2A8F] font-['Cairo'] font-bold text-[14px] hover:bg-[#EAF1FB] transition-colors"
                  data-i18n="courses.openTask8Btn"
                  data-node-id="1155:17976"
                >
                  فتح مهمة الدرس ٨
                </a>
              </div>

              <!-- Widget 3: Info Callout Note (Node 1155:17991) -->
              <div class="bg-[#EAF1FB] rounded-[10px] p-[18px] flex gap-[14px] items-center w-full" data-node-id="1155:17991">
                <div class="flex-1 flex flex-col gap-[4px] items-start text-start" data-node-id="3118:154">
                  <p class="font-['Cairo'] font-bold text-[15px] leading-[1.8] text-[#012383] text-start" data-i18n="courses.noteTitle" data-node-id="1155:17993">
                    المشاهدة ليست الدرجة
                  </p>
                  <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="courses.noteDesc" data-node-id="1155:17994">
                    نسبة المشاهدة تحتسب من مدة الفيديو. تسليم الم  همة وتقييمها حالتان منفصلتان، ولا يغيّران نسبة المشاهدة.
                  </p>
                </div>
                <div class="size-[20px] shrink-0 relative" data-node-id="1155:19368">
                  <img src="/assets/images/icon-info-circle.svg" alt="" class="size-full" />
                </div>
               </div>

            </div>

          </div>

        </div>

        <!-- Workspace Footer (Height: 56px - Node 1155:18235) -->
        <footer class="mt-auto border-t border-[#DEE8F7] px-[40px] py-[18px] flex items-center justify-between text-[12px] text-[#475569] font-medium shrink-0 bg-[#FAFCFF]" data-node-id="1155:18235">
          <a href="#" class="hover:underline" data-i18n="dashboard.footerHelp">المساعدة والدعم</a>
          <span data-i18n="dashboard.footerTagline">منصة مركز الأمين • التعلّم يبدأ بخطوة</span>
        </footer>

      </main>

    </div>

    <!-- Multi-Language i18n Script -->
    <script type="module" src="/src/js/i18n.js"></script>
      <script type="module" src="/src/js/animations.js"></script>
  </body>
</html>


