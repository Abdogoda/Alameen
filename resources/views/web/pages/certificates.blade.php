<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة مركز الأمين | شهاداتي</title>
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
          <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="nav.section" data-node-id="1155:19177">
            التعلّم الخاص بك
          </p>

          <!-- 1. لوحة الطالب -->
          <a
            href="{{ route('dashboard') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:19178"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.dashboard" data-node-id="1155:19179">
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
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.courses" data-node-id="1155:19189">
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
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.assignments" data-node-id="1155:19192">
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
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.grades" data-node-id="1155:19195">
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
            <span class="flex-1 font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#0B2A8F] text-start" data-i18n="nav.certificates" data-node-id="1155:19198">
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
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.profile" data-node-id="1155:19201">
              حسابي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19614">
              <img src="/assets/images/icon-profile.svg" alt="" class="size-full" />
            </div>
          </a>
        </div>

        <!-- Learning Reminder Card (Node 1155:19203) -->
        <div class="bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-[10px] items-end rtl:items-end ltr:items-start p-[18px] rounded-[16px] w-full text-start overflow-clip shrink-0" data-node-id="1155:19203">
          <div class="size-[20px] shrink-0 relative" data-node-id="1155:19617">
            <img src="/assets/images/icon-bookmark.svg" alt="" class="size-full" />
          </div>
          <p class="font-['Cairo'] font-bold text-[#012383] text-[15px] leading-[1.8] text-start w-full" data-i18n="nav.reminderTitle" data-node-id="1155:19205">
            خطوة صغيرة، أثر مستمر
          </p>
          <p class="font-['Cairo'] font-medium text-[#475569] text-[12px] leading-[1.8] text-start w-full" data-i18n="nav.reminderDesc" data-node-id="1155:19206">
            تقدّم المشاهدة والمهام محفوظان في مساحة تعلّمك.
          </p>
        </div>

        <!-- Sign Out (Node 1155:19207) -->
        <a
          href="{{ route('login') }}"
          class="flex gap-[12px] h-[46px] items-center p-[12px] rounded-[10px] w-full text-[#475569] hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer shrink-0 mt-auto"
          data-node-id="1155:19207"
        >
          <span class="flex-1 font-['Cairo'] font-medium text-[13px] leading-[1.8] text-start" data-i18n="nav.signout" data-node-id="1155:19208">
            تسجيل الخروج
          </span>
          <div class="size-[18px] shrink-0 relative" data-node-id="1155:19620">
            <img src="/assets/images/icon-logout-gray.svg" alt="" class="size-full" />
          </div>
        </a>

      </aside>

      <!-- ============================================== -->
      <!-- 2. MAIN LEARNING WORKSPACE (Width: 1192px)     -->
      <!-- Positioned on the LEFT in RTL                  -->
      <!-- ============================================== -->
      <main class="flex-1 min-w-0 flex flex-col min-h-screen bg-white order-2">
        
        <!-- Account Bar (Node 1155:19213 - Height: 80px) -->
        <header class="h-[80px] bg-[#FAFCFF] border-b border-[#DEE8F7] px-4 sm:px-6 lg:px-[40px] flex items-center justify-between shrink-0" data-node-id="1155:19213">
          
          <!-- Search Courses Input (Node 1155:19223) -->
          <div class="hidden sm:flex w-[240px] md:w-[330px] h-[42px] bg-white border border-[#DEE8F7] rounded-[10px] px-[14px] items-center gap-[12px] focus-within:border-[#0B2A8F] focus-within:ring-1 focus-within:ring-[#0B2A8F] transition-all order-1" data-node-id="1155:19223">
            <input
              type="text"
              class="w-full bg-transparent text-[13px] text-[#012383] placeholder-[#475569] outline-none font-medium text-start font-['Cairo']"
              placeholder="ابحثي في دوراتك ودروسك"
              data-i18n-placeholder="header.searchPlaceholder"
              data-node-id="1155:19224"
            />
            <div class="size-[19px] shrink-0 relative" data-node-id="1155:19225">
              <img src="/assets/images/icon-search.svg" alt="" class="size-full" />
            </div>
          </div>

          <!-- Student Account & Language Switcher Group (Node 1155:19214) -->
          <div class="flex items-center gap-[16px] order-2" data-node-id="1155:19214">
            
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

            <!-- Notifications Bell (Node 1155:19221) -->
            <button
              type="button"
              class="relative size-[42px] flex items-center justify-center rounded-full hover:bg-[#EAF1FB] transition-colors cursor-pointer text-[#475569]"
              data-i18n-aria="header.notifications"
              aria-label="الإشعارات"
              data-node-id="1155:19221"
            >
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19623">
                <img src="/assets/images/icon-bell.svg" alt="" class="size-full" />
              </div>
              <span class="absolute top-2.5 end-2.5 size-2 bg-[#0B2A8F] rounded-full"></span>
            </button>

            <!-- Student Profile Summary -->
            <div class="flex items-center gap-[12px]">
              <!-- Initials Circle (Node 1155:19219) -->
              <div class="size-[38px] rounded-full bg-[#EAF1FB] flex items-center justify-center shrink-0" data-node-id="1155:19219">
                <span class="font-bold text-[15px] text-[#0B2A8F] font-['Cairo']" data-i18n="header.studentInitial" data-node-id="1155:19220">أ</span>
              </div>
              <!-- Student Name (Node 1155:19218) -->
              <span class="font-bold text-[14px] text-[#012383] font-['Cairo']" data-i18n="header.studentName" data-node-id="1155:19218">أحمد إبراهيم</span>
              <!-- Menu Caret (Node 1155:19215) -->
              <div class="size-[16px] shrink-0 relative cursor-pointer" data-node-id="1155:19215">
                <img src="/assets/images/icon-account-menu.svg" alt="" class="size-full" />
              </div>
            </div>

          </div>

        </header>

        <!-- ============================================== -->
        <!-- 3. PAGE BODY CONTENT (Node 1155:19072)         -->
        <!-- ============================================== -->
        <div class="px-4 sm:px-6 lg:px-[40px] pt-6 lg:pt-[30px] pb-[40px] flex-1 flex flex-col gap-[24px]" data-node-id="1155:19072">
          
          <!-- Page Introduction (Node 1155:19073) -->
          <div class="flex flex-col gap-[4px] items-start text-start w-full" data-node-id="1155:19073">
            <p class="font-['Cairo'] font-semibold text-[12px] leading-[1.8] text-[#0B2A8F] w-full text-start" data-i18n="certificates.breadcrumb" data-node-id="1155:19074">
              مساحة التعلّم  /  شهاداتي
            </p>
            <h1 class="font-['Cairo'] font-extrabold text-[26px] sm:text-[30px] md:text-[34px] leading-[1.3] text-[#012383] w-full text-start" data-i18n="certificates.pageTitle" data-node-id="1155:19075">
              شهاداتي
            </h1>
            <p class="font-['Cairo'] font-medium text-[13px] md:text-[14px] leading-[1.8] text-[#475569] w-full text-start" data-i18n="certificates.pageDesc" data-node-id="1155:19076">
              كل خطوة في التعلّم تقرّبك من إتمام كورسك. تابع أهليتك للشهادة هنا.
            </p>
          </div>

          <!-- ============================================== -->
          <!-- Exact Certificate Eligibility Rule (Node 1155:19077) -->
          <!-- ============================================== -->
          <div class="bg-gradient-to-l from-[#012383] to-[#0B2A8F] border border-[#0B2A8F]/40 flex flex-col md:flex-row gap-6 md:gap-[32px] items-start md:items-center p-6 md:p-[32px] rounded-[16px] md:rounded-[20px] shadow-[0px_12px_32px_rgba(1,35,131,0.18)] w-full overflow-hidden" data-node-id="1155:19077">
            
            <!-- Award Icon (Node 1155:19593 - Right in RTL, Left in LTR) -->
            <div class="size-[52px] md:size-[56px] shrink-0 relative order-1 rtl:order-1 ltr:order-3" data-node-id="1155:19593">
              <img src="/assets/images/icon-award-white.svg" alt="" class="size-full" />
            </div>

            <!-- Eligibility Explanation (Node 1155:19081 - Middle) -->
            <div class="flex flex-1 flex-col gap-[8px] items-start min-w-0 text-start order-2" data-node-id="1155:19081">
              <h2 class="font-['Cairo'] font-bold text-[22px] md:text-[26px] leading-[1.35] text-white w-full text-start" data-i18n="certificates.ruleTitle" data-node-id="1155:19082">
                شهادة إتمام عند إنجاز ٩٥٪ من الكورس
              </h2>
              <p class="font-['Cairo'] font-normal text-[14px] md:text-[15px] leading-[1.7] text-[#DEE8F7] w-full text-start" data-i18n="certificates.ruleDesc" data-node-id="1155:19083">
                تُحتسب النسبة من مدّة مشاهدة فيديوهات الكورس. أهلية الشهادة مستقلة عن درجات المهام، ولا تشترط إكمال ١٠٠٪ أو درجة نجاح إضافية.
              </p>
            </div>

            <!-- Eligibility Threshold (Node 1155:19078 - Left in RTL, Right in LTR) -->
            <div class="flex flex-col gap-[4px] items-start md:items-center text-start md:text-center w-auto md:w-[200px] shrink-0 order-3 rtl:order-3 ltr:order-1" data-node-id="1155:19078">
              <p class="font-['Cairo'] font-extrabold text-[48px] md:text-[64px] leading-none text-white tracking-tight" data-i18n="certificates.ruleThreshold" data-node-id="1155:19079">
                ٩٥٪
              </p>
              <p class="font-['Cairo'] font-semibold text-[14px] leading-[1.6] text-[#DEE8F7]" data-i18n="certificates.ruleThresholdLabel" data-node-id="1155:19080">
                حد إكمال الكورس
              </p>
            </div>

          </div>

          <!-- ============================================== -->
          <!-- Eligible Language Course Certificate (Node 1155:19085) -->
          <!-- ============================================== -->
          <div class="bg-white border border-[#DEE8F7] rounded-[16px] md:rounded-[20px] p-5 md:p-[24px] flex flex-col lg:flex-row gap-6 lg:gap-[32px] items-start lg:items-center w-full shadow-sm" data-node-id="1155:19085">
            
            <!-- Certificate Information & Actions (Node 1155:19094 - Left in RTL, Right in LTR) -->
            <div class="flex flex-1 flex-col gap-[14px] items-start min-w-0 text-start w-full order-2 rtl:order-2 ltr:order-1" data-node-id="1155:19094">
              
              <!-- Status Badge (Node 1155:19095) -->
              <div class="bg-[#EEF7F3] px-[12px] py-[4px] rounded-full shrink-0" data-node-id="1155:19095">
                <span class="font-['Cairo'] font-bold text-[12px] leading-[1.8] text-[#216350] whitespace-nowrap block" data-i18n="certificates.statusEligible" data-node-id="1155:19096">
                  مؤهل • الشهادة متاحة ✦
                </span>
              </div>

              <!-- Course Title (Node 1155:19097) -->
              <h3 class="font-['Cairo'] font-bold text-[22px] md:text-[26px] leading-[1.35] text-[#012383] text-start w-full" data-i18n="certificates.readyCourseTitle" data-node-id="1155:19097">
                استراتيجيات تنمية اللغة
              </h3>

              <!-- Course Meta Description (Node 1155:19098) -->
              <p class="font-['Cairo'] font-medium text-[13px] md:text-[14px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="certificates.eligibleSubtitle" data-node-id="1155:19098">
                أتممت ١٩ من ٢٠ فيديو متساوية المدّة؛ نسبة الإكمال ٩٥٪.
              </p>

              <!-- Watched Progress Bar (Node 1155:19099) -->
              <div class="bg-[#DEE8F7] h-[8px] rounded-full w-full overflow-hidden relative shrink-0" data-node-id="1155:19099">
                <div class="bg-[#0B2A8F] h-full rounded-full w-[95%]" data-node-id="1155:19100"></div>
              </div>

              <!-- Eligibility Note (Node 1155:19101) -->
              <p class="font-['Cairo'] font-semibold text-[13px] leading-[1.8] text-[#216350] text-start w-full" data-i18n="certificates.eligibleNotice" data-node-id="1155:19101">
                وصلت إلى حد الأهلية. يمكنك الآن عرض شهادة إتمام الكورس وتنزيلها.
              </p>

              <!-- Actions (Node 1155:19102) -->
              <div class="flex flex-wrap gap-[12px] items-center pt-[4px] shrink-0" data-node-id="1155:19102">
                <!-- عرض الشهادة (Primary Blue Button - Node 1155:19118) -->
                <a
                  href="{{ route('certificates.show') }}"
                  class="bg-[#0B2A8F] border border-[#0B2A8F] rounded-[10px] h-[44px] px-[20px] py-[8px] text-white font-['Cairo'] font-bold text-[14px] leading-[1.5] flex items-center justify-center hover:bg-[#012383] transition-colors shadow-sm cursor-pointer whitespace-nowrap"
                  data-i18n="certificates.btnViewCert"
                  data-node-id="1155:19118"
                >
                  عرض الشهادة
                </a>

                <!-- تنزيل PDF (Secondary Outlined Button - Node 1155:19103) -->
                <button
                  type="button"
                  onclick="window.print()"
                  class="bg-white border border-[#DEE8F7] rounded-[10px] h-[44px] px-[20px] py-[8px] text-[#0B2A8F] font-['Cairo'] font-bold text-[14px] leading-[1.5] flex items-center justify-center hover:bg-[#EAF1FB] transition-colors cursor-pointer whitespace-nowrap"
                  data-i18n="certificates.btnDownloadPdf"
                  data-node-id="1155:19103"
                >
                  تنزيل PDF
                </button>
              </div>

            </div>

            <!-- Certificate Thumbnail Preview (Node 1155:19087 - Right in RTL, Left in LTR) -->
            <div class="bg-[#FAFCFF] border border-[#CFDBEA] rounded-[12px] h-[220px] md:h-[230px] w-full max-w-[320px] lg:w-[300px] p-[12px] flex items-center justify-center shrink-0 overflow-hidden order-1 rtl:order-1 ltr:order-2 shadow-inner" data-node-id="1155:19087">
              <div class="size-full rounded-[6px] overflow-hidden shadow-sm relative" data-node-id="3122:169">
                <img src="/assets/images/certificate-preview.png" alt="شهادة إتمام - استراتيجيات تنمية اللغة" class="size-full object-cover pointer-events-none rounded-[6px]" />
              </div>
            </div>

          </div>

          <!-- ============================================== -->
          <!-- Section Heading (Node 1155:19133)              -->
          <!-- ============================================== -->
          <div class="flex items-center justify-between w-full shrink-0 pt-2" data-node-id="1155:19133">
            <!-- Count of Inactive Certificates (Left in RTL, Right in LTR) -->
            <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] whitespace-nowrap order-2 rtl:order-2 ltr:order-2" data-i18n="certificates.pendingCount" data-node-id="1155:19134">
              شهادتان غير متاحتين بعد
            </p>
            <!-- Title (Right in RTL, Left in LTR) -->
            <h3 class="font-['Cairo'] font-bold text-[20px] md:text-[22px] leading-[1.4] text-[#012383] order-1 rtl:order-1 ltr:order-1" data-i18n="certificates.pendingHeading" data-node-id="1155:19135">
              كورساتك قيد الإكمال
            </h3>
          </div>

          <!-- ============================================== -->
          <!-- Locked Certificate Eligibility Grid (Node 1155:19136) -->
          <!-- ============================================== -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-[20px] w-full" data-node-id="1155:19136">
            
            <!-- Card 1: تطبيقات مخارج الحروف (75% - Node 1155:19137) -->
            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-[24px] flex flex-col gap-[16px] shadow-sm overflow-clip" data-node-id="1155:19137">
              
              <!-- Eligibility Status Row (Node 1155:19138) -->
              <div class="flex items-center justify-between w-full shrink-0" data-node-id="1155:19138">
                <!-- Status Badge (Node 1155:19139 - Left in RTL, Right in LTR) -->
                <div class="bg-[#FAFCFF] border border-[#DEE8F7] px-[10px] py-[3px] rounded-[100px] shrink-0 order-2 rtl:order-2 ltr:order-2" data-node-id="1155:19139">
                  <span class="font-['Cairo'] font-bold text-[12px] leading-[1.8] text-[#475569] whitespace-nowrap block" data-i18n="certificates.statusNotEligible" data-node-id="1155:19140">
                    غير مؤهل بعد
                  </span>
                </div>
                <!-- Lock Icon (Node 1155:19596 - Right in RTL, Left in LTR) -->
                <div class="size-[24px] shrink-0 relative order-1 rtl:order-1 ltr:order-1" data-node-id="1155:19596">
                  <img src="/assets/images/icon-lock-keyhole.svg" alt="" class="size-full" />
                </div>
              </div>

              <!-- Course Title (Node 1155:19142) -->
              <h4 class="font-['Cairo'] font-bold text-[20px] md:text-[22px] leading-[1.4] text-[#012383] text-start w-full" data-i18n="certificates.course1Title" data-node-id="1155:19142">
                تطبيقات مخارج الحروف
              </h4>

              <!-- Threshold Progress Label (Node 1155:19143) -->
              <div class="flex items-start justify-between w-full font-['Cairo'] text-start shrink-0" data-node-id="1155:19143">
                <p class="font-['Cairo'] font-bold text-[20px] text-[#012383] order-2 rtl:order-2 ltr:order-2" data-i18n="certificates.course1Percent" data-node-id="1155:19144">
                  ٧٥٪
                </p>
                <p class="font-['Cairo'] font-medium text-[13px] text-[#475569] order-1 rtl:order-1 ltr:order-1" data-i18n="certificates.watchProgressLabel" data-node-id="1155:19145">
                  نسبة إكمال المشاهدة
                </p>
              </div>

              <!-- Watched Progress Bar (Node 1155:19146) -->
              <div class="bg-[#DEE8F7] h-[7px] rounded-[100px] w-full overflow-hidden relative shrink-0" data-node-id="1155:19146">
                <div class="bg-[#0B2A8F] h-full rounded-[100px] w-[75%]" data-node-id="1155:19147"></div>
              </div>

              <!-- Progress Meta Text (Node 1155:19148) -->
              <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="certificates.course1ProgressText" data-node-id="1155:19148">
                تقدّمك الحالي ٧٥٪، وحد الأهلية ٩٥٪.
              </p>

              <!-- Progress Callout Box (Node 1155:19149) -->
              <div class="bg-[#EAF1FB] rounded-[10px] p-[16px] flex flex-col gap-[4px] text-start w-full shrink-0" data-node-id="1155:19149">
                <p class="font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#012383] w-full text-start" data-i18n="certificates.course1CalloutTitle" data-node-id="1155:19150">
                  تبقّت ٢٠ نقطة مئوية للوصول إلى ٩٥٪
                </p>
                <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] w-full text-start" data-i18n="certificates.course1CalloutDesc" data-node-id="1155:19151">
                  ستتاح الشهادة عند بلوغ الحد، ولا يلزم الوصول إلى ١٠٠٪.
                </p>
              </div>

              <!-- Ineligible Note (Node 1155:19152) -->
              <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="certificates.course1FooterNote" data-node-id="1155:19152">
                عرض الشهادة وتنزيلها غير متاحين لهذا الكورس بعد.
              </p>

            </div>

            <!-- Card 2: أساسيات التخاطب (42% - Node 1155:19153) -->
            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-[24px] flex flex-col gap-[16px] shadow-sm overflow-clip" data-node-id="1155:19153">
              
              <!-- Eligibility Status Row (Node 1155:19154) -->
              <div class="flex items-center justify-between w-full shrink-0" data-node-id="1155:19154">
                <!-- Status Badge (Node 1155:19155 - Left in RTL, Right in LTR) -->
                <div class="bg-[#FAFCFF] border border-[#DEE8F7] px-[10px] py-[3px] rounded-[100px] shrink-0 order-2 rtl:order-2 ltr:order-2" data-node-id="1155:19155">
                  <span class="font-['Cairo'] font-bold text-[12px] leading-[1.8] text-[#475569] whitespace-nowrap block" data-i18n="certificates.statusNotEligible" data-node-id="1155:19156">
                    غير مؤهل بعد
                  </span>
                </div>
                <!-- Lock Icon (Node 1155:19599 - Right in RTL, Left in LTR) -->
                <div class="size-[24px] shrink-0 relative order-1 rtl:order-1 ltr:order-1" data-node-id="1155:19599">
                  <img src="/assets/images/icon-lock-keyhole.svg" alt="" class="size-full" />
                </div>
              </div>

              <!-- Course Title (Node 1155:19158) -->
              <h4 class="font-['Cairo'] font-bold text-[20px] md:text-[22px] leading-[1.4] text-[#012383] text-start w-full" data-i18n="certificates.course2Title" data-node-id="1155:19158">
                أساسيات التخاطب
              </h4>

              <!-- Threshold Progress Label (Node 1155:19159) -->
              <div class="flex items-start justify-between w-full font-['Cairo'] text-start shrink-0" data-node-id="1155:19159">
                <p class="font-['Cairo'] font-bold text-[20px] text-[#012383] order-2 rtl:order-2 ltr:order-2" data-i18n="certificates.course2Percent" data-node-id="1155:19160">
                  ٤٢٪
                </p>
                <p class="font-['Cairo'] font-medium text-[13px] text-[#475569] order-1 rtl:order-1 ltr:order-1" data-i18n="certificates.watchProgressLabel" data-node-id="1155:19161">
                  نسبة إكمال المشاهدة
                </p>
              </div>

              <!-- Watched Progress Bar (Node 1155:19162) -->
              <div class="bg-[#DEE8F7] h-[7px] rounded-[100px] w-full overflow-hidden relative shrink-0" data-node-id="1155:19162">
                <div class="bg-[#0B2A8F] h-full rounded-[100px] w-[42%]" data-node-id="1155:19163"></div>
              </div>

              <!-- Progress Meta Text (Node 1155:19164) -->
              <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="certificates.course2ProgressText" data-node-id="1155:19164">
                ١٦٨ من ٤٠٠ دقيقة مشاهدة • ٨ فيديوهات مكتملة ودرس قيد المشاهدة.
              </p>

              <!-- Progress Callout Box (Node 1155:19165) -->
              <div class="bg-[#EAF1FB] rounded-[10px] p-[16px] flex flex-col gap-[4px] text-start w-full shrink-0" data-node-id="1155:19165">
                <p class="font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#012383] w-full text-start" data-i18n="certificates.course2CalloutTitle" data-node-id="1155:19166">
                  تبقّت ٥٣ نقطة مئوية للوصول إلى ٩٥٪
                </p>
                <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] w-full text-start" data-i18n="certificates.course2CalloutDesc" data-node-id="1155:19167">
                  ستتاح الشهادة عند بلوغ الحد، ولا يلزم الوصول إلى ١٠٠٪.
                </p>
              </div>

              <!-- Ineligible Note (Node 1155:19168) -->
              <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="certificates.course2FooterNote" data-node-id="1155:19168">
                عرض الشهادة وتنزيلها غير متاحين لهذا الكورس بعد.
              </p>

            </div>

          </div>

        </div>

        <!-- Footer -->
        <footer class="h-[58px] border-t border-[#DEE8F7] px-[40px] flex items-center justify-between text-[12px] text-[#94A3B8] shrink-0 bg-white">
          <span data-i18n="certificate.footerCopy">منصة مركز الأمين © ٢٠٢٦</span>
          <span data-i18n="certificate.footerLegal">منصة تعليمية لكورسات التخاطب</span>
        </footer>

      </main>

    </div>

    <!-- Core i18n & App Logic Script -->
    <script type="module" src="/src/js/i18n.js"></script>
      <script type="module" src="/src/js/animations.js"></script>
  </body>
</html>


