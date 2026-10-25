<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة مركز الأمين | شهادة الإتمام</title>
    <!-- Google Fonts Preconnect and Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@500;600;700;800&family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
    <!-- Stylesheet with Tailwind and Design Tokens -->
    <link rel="stylesheet" href="/src/css/styles.css" />
  </head>
  <body class="bg-[#F8FAFC] min-h-screen text-[#475569] font-body antialiased">
    <!-- Main 1440px Canvas Container -->
    <div class="max-w-[1440px] mx-auto min-h-screen flex flex-row bg-white shadow-sm" id="app-canvas">
      
      <!-- ============================================== -->
      <!-- 1. PRIMARY NAVIGATION SIDEBAR (248px Width)     -->
      <!-- ============================================== -->
      <!-- 1. PRIMARY NAVIGATION SIDEBAR (Node 1155:19172) -->
      <!-- Width: 248px - Positioned on the RIGHT in RTL   -->
      <!-- ============================================== -->
      <aside class="w-[248px] bg-white border-l border-[#DEE8F7] flex flex-col gap-[28px] items-start pb-[24px] pt-[22px] px-[20px] shrink-0 min-h-screen order-1" data-node-id="1155:19172">
        
        <!-- Al Ameen Identity (Node 1155:19173) -->
        <a href="{{ route('home') }}" class="flex gap-[8px] h-[62px] items-center justify-end rtl:justify-end ltr:flex-row-reverse ltr:justify-end w-full shrink-0" data-node-id="1155:19173">
          <span class="font-['Baloo_Bhaijaan_2'] font-extrabold rtl:text-[25px] ltr:text-[17px] leading-[1.3] text-[#012383] text-start whitespace-nowrap shrink-0" data-i18n="brand.name" data-node-id="1155:19174">
            منصة مركز الأمين
          </span>
          <div class="size-[54px] shrink-0 relative" data-node-id="1155:19175">
            <img src="/assets/images/logo.png" alt="منصة مركز الأمين" class="size-full object-contain pointer-events-none" />
          </div>
        </a>

        <!-- Student Navigation Group (Node 1155:19176) -->
        <div class="flex flex-col gap-[7px] items-start w-full overflow-clip shrink-0" data-node-id="1155:19176">
          <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-right w-full" data-i18n="nav.section" data-node-id="1155:19177">
            التعلّم الخاص بك
          </p>

          <!-- 1. لوحة الطالب -->
          <a
            href="{{ route('dashboard') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:19178"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-right" data-i18n="nav.dashboard" data-node-id="1155:19179">
              لوحة الطالب
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19180">
              <img src="/assets/images/icon-dashboard.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 2. دوراتي -->
          <a
            href="{{ route('courses.show') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:19188"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-right" data-i18n="nav.courses" data-node-id="1155:19189">
              دوراتي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19602">
              <img src="/assets/images/icon-courses.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 3. المهام -->
          <a
            href="{{ route('assignments.index') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:19191"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-right" data-i18n="nav.assignments" data-node-id="1155:19192">
              المهام
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19605">
              <img src="/assets/images/icon-assignments.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 4. درجاتي -->
          <a
            href="{{ route('grades.index') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:19194"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-right" data-i18n="nav.grades" data-node-id="1155:19195">
              درجاتي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19608">
              <img src="/assets/images/icon-grades.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 5. شهاداتي (ACTIVE - Node 1155:19197) -->
          <a
            href="{{ route('certificates.index') }}"
            class="bg-[#EAF1FB] flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full shrink-0"
            data-node-id="1155:19197"
          >
            <span class="flex-1 font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#0B2A8F] text-right" data-i18n="nav.certificates" data-node-id="1155:19198">
              شهاداتي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19611">
              <img src="/assets/images/icon-award-active.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 6. حسابي -->
          <a
            href="{{ route('user.profile') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:19200"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-right" data-i18n="nav.profile" data-node-id="1155:19201">
              حسابي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19614">
              <img src="/assets/images/icon-profile.svg" alt="" class="size-full" />
            </div>
          </a>
        </div>

        <!-- Learning Reminder Card (Node 1155:19203) -->
        <div class="bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-[10px] items-end p-[18px] rounded-[16px] w-full text-right overflow-clip shrink-0" data-node-id="1155:19203">
          <div class="size-[20px] shrink-0 relative" data-node-id="1155:19617">
            <img src="/assets/images/icon-bookmark.svg" alt="" class="size-full" />
          </div>
          <p class="font-['Cairo'] font-bold text-[#012383] text-[15px] leading-[1.8] text-right w-full" data-i18n="nav.reminderTitle" data-node-id="1155:19205">
            خطوة صغيرة، أثر مستمر
          </p>
          <p class="font-['Cairo'] font-medium text-[#475569] text-[12px] leading-[1.8] text-right w-full" data-i18n="nav.reminderDesc" data-node-id="1155:19206">
            تقدّم المشاهدة والمهام محفوظان في مساحة تعلّمك.
          </p>
        </div>

        <!-- Sign Out (Node 1155:19207) -->
        <a
          href="{{ route('login') }}"
          class="flex gap-[12px] h-[46px] items-center p-[12px] rounded-[10px] w-full text-[#475569] hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer shrink-0 mt-auto"
          data-node-id="1155:19207"
        >
          <span class="flex-1 font-['Cairo'] font-medium text-[13px] leading-[1.8] text-right" data-i18n="nav.signout" data-node-id="1155:19208">
            تسجيل الخروج
          </span>
          <div class="size-[18px] shrink-0 relative" data-node-id="1155:19620">
            <img src="/assets/images/icon-logout-gray.svg" alt="" class="size-full" />
          </div>
        </a>

      </aside>

      <!-- ============================================== -->
      <!-- 2. LEARNING WORKSPACE (1192px Content Area)    -->
      <!-- Node 1155:19212 - Positioned on the LEFT in RTL-->
      <!-- ============================================== -->
      <main class="flex-1 min-w-0 flex flex-col min-h-screen bg-white order-2" data-node-id="1155:19212">
        
        <!-- Top Account Bar (Node 1155:19213 - Height: 80px) -->
        <!-- Search on Right (adjacent to sidebar), Student Account on Left -->
        <header class="h-[80px] bg-[#FAFCFF] border-b border-[#DEE8F7] px-4 sm:px-6 lg:px-10 flex items-center justify-between shrink-0" data-node-id="1155:19213">
          
          <!-- Search Courses Input (Node 1155:19223 - On the RIGHT in RTL) -->
          <div class="hidden sm:flex w-[240px] md:w-[330px] h-[42px] bg-white border border-[#DEE8F7] rounded-[10px] px-3.5 items-center gap-3 focus-within:border-[#0B2A8F] focus-within:ring-1 focus-within:ring-[#0B2A8F] transition-all order-1" data-node-id="1155:19223">
            <input
              type="text"
              class="w-full bg-transparent text-[13px] text-[#012383] placeholder-[#475569] outline-none font-medium text-right font-['Cairo']"
              placeholder="ابحثي في دوراتك ودروسك"
              data-node-id="1155:19224"
              data-i18n-placeholder="header.searchPlaceholder"
            />
            <div class="size-[19px] shrink-0 relative" data-node-id="1155:19225">
              <img src="/assets/images/icon-search.svg" alt="" class="size-full" />
            </div>
          </div>

          <!-- Student Account & Language Switcher Group (Node 1155:19214 - On the LEFT in RTL) -->
          <div class="flex items-center gap-4 order-2" data-node-id="1155:19214">
            
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

              <!-- Language Menu Dropdown -->
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

            <!-- Notifications Bell (Node 1155:19221 - size 42px) -->
            <button
              type="button"
              class="relative size-[42px] flex items-center justify-center rounded-full hover:bg-[#EAF1FB] transition-colors cursor-pointer text-[#475569]"
              data-node-id="1155:19221"
              data-i18n-aria="header.notifications"
              aria-label="الإشعارات"
            >
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19623">
                <img src="/assets/images/icon-bell.svg" alt="" class="size-full" />
              </div>
              <span class="absolute top-2.5 end-2.5 size-2 bg-[#0B2A8F] rounded-full"></span>
            </button>

            <!-- Student Profile Summary (Node 1155:19214) -->
            <div class="flex items-center gap-3">
              <!-- Avatar Circle (Node 1155:19219 - size 38px) -->
              <div class="size-[38px] rounded-full bg-[#EAF1FB] flex items-center justify-center shrink-0" data-node-id="1155:19219">
                <span class="font-bold text-[15px] text-[#0B2A8F] font-['Cairo']" data-node-id="1155:19220" data-i18n="header.studentInitial">أ</span>
              </div>
              <span class="font-bold text-[14px] text-[#012383] font-['Cairo']" data-node-id="1155:19218" data-i18n="header.studentName">أحمد إبراهيم</span>
              <div class="size-[16px] shrink-0 relative cursor-pointer" data-node-id="1155:19215">
                <img src="/assets/images/icon-account-menu.svg" alt="" class="size-full" />
              </div>
            </div>

          </div>

        </header>

        <!-- Page Body Content (Node 1155:19229) -->
        <div class="px-4 sm:px-6 lg:px-10 py-6 md:py-8 flex-1 flex flex-col gap-6" data-node-id="1155:19229">
          
          <!-- Page Introduction (Node 1155:19230) -->
          <div data-node-id="1155:19230">
            <p class="text-[13px] md:text-[14px] text-[#0B2A8F] font-semibold mb-1 font-['Cairo']" data-node-id="1155:19231" data-i18n="certificate.pageEyebrow">
              شهاداتك وكورساتك
            </p>
            <h1 class="text-[28px] sm:text-[32px] md:text-[36px] font-extrabold text-[#012383] font-['Cairo'] leading-[1.3] mb-1.5" data-node-id="1155:19232" data-i18n="certificate.pageTitle">
              شهادة الإتمام
            </h1>
            <p class="text-[14px] md:text-[15px] text-[#475569] font-medium font-['Cairo']" data-node-id="1155:19233" data-i18n="certificate.pageDesc">
              عرض وتنزيل شهادة إتمام كورس استراتيجيات تنمية اللغة
            </p>
          </div>

          <!-- Toolbar (Node 1155:19234 - Actions & Status) -->
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4" data-node-id="1155:19234">
            <!-- Buttons Group (Node 1155:19235) -->
            <div class="flex flex-wrap items-center gap-3">
              <!-- Primary CTA: Download PDF (Node 1155:19236) -->
              <button
                type="button"
                onclick="window.print()"
                class="h-[44px] px-5 rounded-[10px] bg-[#0B2A8F] hover:bg-[#012383] text-white text-[14px] font-bold flex items-center gap-2 shadow-sm transition-all cursor-pointer font-['Cairo']"
                data-node-id="1155:19236"
              >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span data-i18n="certificate.downloadPdf">تنزيل الشهادة PDF</span>
              </button>

              <!-- Secondary Button: Back to Certificates (Node 1155:19251) -->
              <a
                href="{{ route('certificates.index') }}"
                class="h-[44px] px-5 rounded-[10px] bg-white border border-[#DEE8F7] hover:bg-[#FAFCFF] text-[#0B2A8F] text-[14px] font-bold flex items-center gap-2 transition-all font-['Cairo']"
                data-node-id="1155:19251"
              >
                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span data-i18n="certificate.backToCertificates">العودة إلى شهاداتي</span>
              </a>
            </div>

            <!-- Status Badge (Node 1155:19266) -->
            <div class="px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#D1EADB] flex items-center gap-1.5" data-node-id="1155:19266">
              <span class="size-2 rounded-full bg-[#216350]"></span>
              <span class="text-[12px] font-bold text-[#216350] font-['Cairo']" data-node-id="1155:19267" data-i18n="certificate.statusBadge">
                مؤهلة عند إكمال ٩٥٪ ✦
              </span>
            </div>
          </div>

          <!-- Pixel-Perfect Certificate Card (Node 1155:19268) -->
          <div class="w-full bg-white border border-[#CFDBEA] rounded-[14px] p-4 sm:p-[20px] relative overflow-hidden shadow-lg" id="printable-certificate" data-node-id="1155:19268">
            <!-- Certificate Brand Edge Ribbon (Node 1155:19269 - 14px Navy bar) -->
            <div class="absolute start-0 top-0 bottom-0 w-[12px] md:w-[14px] bg-[#012383]" data-node-id="1155:19269"></div>

            <!-- Subtle Decorative Radial Arc Motifs (Nodes 1155:19270 & 1155:19271) -->
            <div class="absolute -start-20 -top-20 size-[260px] rounded-full pointer-events-none arc-motif"></div>
            <div class="absolute -end-16 -bottom-16 size-[200px] rounded-full pointer-events-none arc-motif"></div>

            <!-- Certificate Inner Border Box (Node 1155:19272) -->
            <div class="border border-[#DEE8F7] rounded-[10px] px-4 sm:px-8 md:px-12 py-8 flex flex-col items-center text-center relative z-10 bg-white/95" data-node-id="1155:19272">
              
              <!-- Issuer Header (Logo + Brand Name - Node 1155:19273) -->
              <div class="flex items-center gap-3 mb-2" data-node-id="1155:19273">
                <span class="text-[24px] sm:text-[28px] font-extrabold text-[#012383] font-['Cairo']" data-node-id="1155:19274" data-i18n="certificate.issuerName">
                  منصة مركز الأمين
                </span>
                <div class="size-[48px] sm:size-[54px] shrink-0 relative" data-node-id="1155:19275">
                  <img src="/assets/images/logo.png" alt="منصة مركز الأمين" class="size-full object-contain pointer-events-none" />
                </div>
              </div>

              <!-- Main Title (Node 1155:19276) -->
              <h2 class="text-[32px] sm:text-[38px] md:text-[42px] font-extrabold text-[#012383] font-['Cairo'] mb-1 tracking-tight" data-node-id="1155:19276" data-i18n="certificate.certHeading">
                شهادة إتمام
              </h2>

              <!-- Subtitle (Node 1155:19277) -->
              <p class="text-[14px] sm:text-[15px] text-[#475569] font-medium mb-1.5 font-['Cairo']" data-node-id="1155:19277" data-i18n="certificate.certPresented">
                تُمنح هذه الشهادة من منصة مركز الأمين إلى
              </p>

              <!-- Student Full Name (Node 1155:19278) -->
              <div class="my-2 flex flex-col items-center">
                <p class="text-[36px] sm:text-[42px] md:text-[48px] font-extrabold text-[#012383] font-['Cairo'] leading-[1.2]" data-node-id="1155:19278" data-i18n="certificate.studentName">
                  أحمد إبراهيم
                </p>
                <div class="w-[180px] h-[2px] bg-[#CFDBEA] mt-1 mb-2" data-node-id="1155:19279"></div>
              </div>

              <!-- Course Qualification Text (Node 1155:19280) -->
              <p class="text-[14px] sm:text-[16px] text-[#475569] font-medium mb-1 font-['Cairo']" data-node-id="1155:19280" data-i18n="certificate.certFor">
                لإتمامه المتطلبات الخاصة بمشاهدة الكورس التعليمي
              </p>

              <!-- Course Title (Node 1155:19281) -->
              <p class="text-[24px] sm:text-[28px] md:text-[32px] font-bold text-[#0B2A8F] font-['Cairo'] mb-2" data-node-id="1155:19281" data-i18n="certificate.courseTitle">
                استراتيجيات تنمية اللغة
              </p>

              <!-- Completion Stat (Node 1155:19282) -->
              <p class="text-[15px] sm:text-[17px] font-bold text-[#012383] mb-6 font-['Cairo']" data-node-id="1155:19282" data-i18n="certificate.certCompletionStat">
                بنسبة إكمال ٩٥٪ • ١٩ من ٢٠ فيديو
              </p>

              <!-- Certificate Record - Completion Date Only (User requested: خلي تاريخ التمام بس) -->
              <div class="w-full pt-6 flex items-center justify-start px-6" data-node-id="1155:19287">
                <div class="flex flex-col text-start">
                  <span class="text-[13px] text-[#475569] font-medium mb-0.5 font-['Cairo']" data-node-id="1155:19288" data-i18n="certificate.dateLabel">
                    تاريخ الإتمام
                  </span>
                  <span class="text-[16px] font-bold text-[#012383] font-['Cairo']" data-node-id="1155:19289" data-i18n="certificate.dateValue">
                    ٢٣ أكتوبر ٢٠٢٦
                  </span>
                </div>
              </div>

            </div>
          </div>

          <!-- Information Note Card (Node 1155:19291) -->
          <div class="w-full p-4 rounded-[10px] bg-[#FAFCFF] border border-[#DEE8F7] flex items-start gap-3.5" data-node-id="1155:19291">
            <div class="size-9 rounded-full bg-[#EAF1FB] flex items-center justify-center text-[#0B2A8F] shrink-0">
              <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <div data-node-id="1155:19292">
              <p class="text-[13px] font-bold text-[#012383] mb-0.5 font-['Cairo']" data-node-id="1155:19293" data-i18n="certificate.infoTitle">
                عن هذه الشهادة
              </p>
              <p class="text-[12px] text-[#475569] leading-relaxed font-['Cairo']" data-node-id="1155:19294" data-i18n="certificate.infoDesc">
                توثق شهادة الإتمام بلوغ ٩٥٪ من مشاهدة الدورة. درجات المهام منفصلة ولا تظهر كتقدير مهني أو ترخيص للممارسة.
              </p>
            </div>
          </div>

        </div>

        <!-- Workspace Footer (Node 1155:19296) -->
        <footer class="h-[58px] border-t border-[#DEE8F7] px-10 flex items-center justify-between text-[12px] text-[#94A3B8] shrink-0" data-node-id="1155:19296">
          <span data-node-id="1155:19297" data-i18n="certificate.footerCopy">منصة مركز الأمين © ٢٠٢٦</span>
          <span data-node-id="1155:19298" data-i18n="certificate.footerLegal">منصة تعليمية لكورسات التخاطب</span>
        </footer>

      </main>

    </div>

    <!-- Script loading for multi-language engine -->
    <script type="module" src="/src/js/i18n.js"></script>
      <script type="module" src="/src/js/animations.js"></script>
  </body>
</html>


