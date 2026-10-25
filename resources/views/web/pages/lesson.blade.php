<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة مركز الأمين | مشاهدة الدرس</title>
    <!-- Google Fonts Preconnect and Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@500;600;700;800&family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
    <!-- Stylesheet with Tailwind and Design Tokens -->
    <link rel="stylesheet" href="/src/css/styles.css" />
  </head>
  <body class="bg-[#F8FAFC] min-h-screen text-[#475569] font-body antialiased">
    <div class="max-w-[1440px] mx-auto min-h-screen flex flex-row bg-white shadow-sm" id="app-canvas">
      
      <!-- ============================================== -->
      <!-- 1. PRIMARY NAVIGATION SIDEBAR (Node 1155:18493) -->
      <!-- Width: 248px - Positioned on the RIGHT in RTL   -->
      <!-- ============================================== -->
      <aside class="w-[248px] bg-white border-l border-[#DEE8F7] flex flex-col gap-[28px] items-start pb-[24px] pt-[28px] px-[20px] shrink-0 min-h-screen order-1" data-node-id="1155:18493">
        
        <!-- Al Ameen Identity (Node 1155:18494) -->
        <a href="{{ route('home') }}" class="flex gap-[8px] h-[62px] items-center justify-end rtl:justify-end ltr:flex-row-reverse ltr:justify-end w-full shrink-0" data-node-id="1155:18494">
          <span class="font-['Baloo_Bhaijaan_2'] font-extrabold rtl:text-[25px] ltr:text-[17px] leading-[1.3] text-[#012383] text-start whitespace-nowrap shrink-0" data-i18n="brand.name" data-node-id="1155:18495">
            منصة مركز الأمين
          </span>
          <div class="size-[54px] shrink-0 relative" data-node-id="1155:18496">
            <img src="/assets/images/logo.png" alt="منصة مركز الأمين" class="size-full object-contain pointer-events-none" />
          </div>
        </a>

        <!-- Student Navigation Group (Node 1155:18497) -->
        <div class="flex flex-col gap-[7px] items-start w-full overflow-clip shrink-0" data-node-id="1155:18497">
          <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="nav.section" data-node-id="1155:18498">
            التعلّم الخاص بك
          </p>

          <!-- 1. لوحة الطالب -->
          <a
            href="{{ route('dashboard') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:18499"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.dashboard" data-node-id="1155:18500">
              لوحة الطالب
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:18501">
              <img src="/assets/images/icon-dashboard.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 2. دوراتي (ACTIVE - Node 1155:18509) -->
          <a
            href="{{ route('courses.show') }}"
            class="bg-[#EAF1FB] flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full shrink-0"
            data-node-id="1155:18509"
          >
            <span class="flex-1 font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#0B2A8F] text-start" data-i18n="nav.courses" data-node-id="1155:18510">
              دوراتي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19464">
              <img src="/assets/images/icon-courses.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 3. المهام -->
          <a
            href="{{ route('assignments.index') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:18512"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.assignments" data-node-id="1155:18513">
              المهام
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19467">
              <img src="/assets/images/icon-assignments.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 4. درجاتي -->
          <a
            href="{{ route('grades.index') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:18515"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.grades" data-node-id="1155:18516">
              درجاتي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19470">
              <img src="/assets/images/icon-grades.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 5. شهاداتي -->
          <a
            href="{{ route('certificates.index') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:18518"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.certificates" data-node-id="1155:18519">
              شهاداتي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19473">
              <img src="/assets/images/icon-certificates.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 6. حسابي -->
          <a
            href="{{ route('user.profile') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:18521"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.profile" data-node-id="1155:18522">
              حسابي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19476">
              <img src="/assets/images/icon-profile.svg" alt="" class="size-full" />
            </div>
          </a>
        </div>

        <!-- Learning Reminder Card (Node 1155:18524) -->
        <div class="bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-[10px] items-end rtl:items-end ltr:items-start p-[18px] rounded-[16px] w-full text-start overflow-clip shrink-0" data-node-id="1155:18524">
          <div class="size-[20px] shrink-0 relative" data-node-id="1155:19479">
            <img src="/assets/images/icon-bookmark.svg" alt="" class="size-full" />
          </div>
          <p class="font-['Cairo'] font-bold text-[#012383] text-[15px] leading-[1.8] text-start w-full" data-i18n="nav.reminderTitle" data-node-id="1155:18526">
            خطوة صغيرة، أثر مستمر
          </p>
          <p class="font-['Cairo'] font-medium text-[#475569] text-[12px] leading-[1.8] text-start w-full" data-i18n="nav.reminderDesc" data-node-id="1155:18527">
            تقدّم المشاهدة والمهام محفوظان في مساحة تعلّمك.
          </p>
        </div>

        <!-- Sign Out (Node 1155:18528) -->
        <a
          href="{{ route('login') }}"
          class="flex gap-[12px] h-[46px] items-center p-[12px] rounded-[10px] w-full text-[#475569] hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer shrink-0 mt-auto"
          data-node-id="1155:18528"
        >
          <span class="flex-1 font-['Cairo'] font-medium text-[13px] leading-[1.8] text-start" data-i18n="nav.signout" data-node-id="1155:18529">
            تسجيل الخروج
          </span>
          <div class="size-[18px] shrink-0 relative" data-node-id="1155:19482">
            <img src="/assets/images/icon-logout-gray.svg" alt="" class="size-full" />
          </div>
        </a>

      </aside>

      <!-- ============================================== -->
      <!-- 2. MAIN LEARNING WORKSPACE (Width: 1192px)     -->
      <!-- Positioned on the LEFT in RTL                  -->
      <!-- ============================================== -->
      <main class="flex-1 min-w-0 flex flex-col min-h-screen bg-[#FAFCFF] order-2" data-node-id="1155:18335">
        
        <!-- Account Bar (Height: 80px) -->
        <header class="h-[80px] bg-[#FAFCFF] border-b border-[#DEE8F7] px-4 sm:px-6 lg:px-[40px] flex items-center justify-between shrink-0">
          
          <!-- Search Courses Input (Node 1155:19223) -->
          <div class="hidden sm:flex w-[240px] md:w-[330px] h-[42px] bg-white border border-[#DEE8F7] rounded-[10px] px-[14px] items-center gap-[12px] focus-within:border-[#0B2A8F] focus-within:ring-1 focus-within:ring-[#0B2A8F] transition-all order-1">
            <input
              type="text"
              class="w-full bg-transparent text-[13px] text-[#012383] placeholder-[#475569] outline-none font-medium text-start font-['Cairo']"
              placeholder="ابحثي في دوراتك ودروسك"
              data-i18n-placeholder="header.searchPlaceholder"
            />
            <div class="size-[19px] shrink-0 relative">
              <img src="/assets/images/icon-search.svg" alt="" class="size-full" />
            </div>
          </div>

          <!-- Student Account & Language Switcher Group -->
          <div class="flex items-center gap-[16px] order-2">
            
            <!-- Language Switcher Pill Dropdown -->
            <div class="relative" id="lang-switcher">
              <button
                type="button"
                id="lang-toggle-btn"
                aria-haspopup="true"
                aria-expanded="false"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#EAF1FB] text-[#012383] hover:bg-[#DEE8F7] transition-all text-xs font-bold cursor-pointer border border-[#DEE8F7]/60"
              >
                <svg class="w-3.5 h-3.5 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8" />
                </svg>
                <span id="current-lang-label">العربية</span>
                <svg class="w-3.5 h-3.5 text-[#475569] lang-caret transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            <div class="h-5 w-px bg-[#DEE8F7]"></div>

            <!-- Notifications Bell -->
            <button
              type="button"
              class="relative size-[42px] flex items-center justify-center rounded-full hover:bg-[#EAF1FB] transition-colors cursor-pointer text-[#475569]"
              data-i18n-aria="header.notifications"
              aria-label="الإشعارات"
            >
              <div class="size-[20px] shrink-0 relative">
                <img src="/assets/images/icon-bell.svg" alt="" class="size-full" />
              </div>
              <span class="absolute top-2.5 end-2.5 size-2 bg-[#0B2A8F] rounded-full"></span>
            </button>

            <!-- Student Profile Summary -->
            <div class="flex items-center gap-[12px]">
              <div class="size-[38px] rounded-full bg-[#EAF1FB] flex items-center justify-center shrink-0">
                <span class="font-bold text-[15px] text-[#0B2A8F] font-['Cairo']" data-i18n="header.studentInitial">أ</span>
              </div>
              <span class="font-bold text-[14px] text-[#012383] font-['Cairo']" data-i18n="header.studentName">أحمد إبراهيم</span>
              <div class="size-[16px] shrink-0 relative cursor-pointer">
                <img src="/assets/images/icon-account-menu.svg" alt="" class="size-full" />
              </div>
            </div>

          </div>

        </header>

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

        <!-- Footer (Node 1155:18490) -->
        <footer class="h-[58px] border-t border-[#DEE8F7] px-[40px] flex items-center justify-between text-[12px] text-[#475569] font-['Cairo'] font-medium shrink-0 bg-[#FAFCFF]" data-node-id="1155:18490">
          <span data-i18n="dashboard.footerHelp">المساعدة والدعم</span>
          <span data-i18n="dashboard.footerTagline">منصة مركز الأمين • التعلّم يبدأ بخطوة</span>
        </footer>

      </main>

    </div>

    <!-- Core i18n & App Logic Script -->
    <script type="module" src="/src/js/i18n.js"></script>
      <script type="module" src="/src/js/animations.js"></script>
  </body>
</html>


