<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة مركز الأمين | درجاتي</title>
    <!-- Google Fonts Preconnect and Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@500;600;700;800&family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
    <!-- Stylesheet with Tailwind and Design Tokens -->
    <link rel="stylesheet" href="/src/css/styles.css" />
  </head>
  <body class="bg-[#FAFCFF] min-h-screen text-[#475569] font-['Cairo'] antialiased">
    <div class="max-w-[1440px] mx-auto min-h-screen flex flex-row bg-white shadow-sm" id="app-canvas" data-node-id="1155:18868">
      
      <!-- ============================================== -->
      <!-- 1. PRIMARY NAVIGATION SIDEBAR (248px Width)     -->
      <!-- Positioned on the RIGHT in RTL                  -->
      <!-- ============================================== -->
      <aside class="w-[248px] bg-white border-l border-[#DEE8F7] flex flex-col justify-between pb-[24px] pt-[22px] px-[20px] shrink-0 min-h-screen order-1" data-node-id="1155:19015">
        
        <div class="flex flex-col gap-[28px] w-full">
          <!-- Al-Ameen Brand Identity (Real Figma Logo) -->
          <a href="{{ route('home') }}" class="flex gap-[8px] h-[62px] items-center justify-end w-full" data-node-id="1155:19016">
            <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-[25px] leading-[1.45] text-[#012383] whitespace-nowrap" data-i18n="brand.name" data-node-id="1155:19017">
              منصة مركز الأمين
            </span>
            <div class="size-[54px] shrink-0 relative" data-node-id="1155:19018">
              <img src="/assets/images/logo.png" alt="منصة مركز الأمين" class="size-full object-contain pointer-events-none" />
            </div>
          </a>

          <!-- Student Navigation Group -->
          <div class="flex flex-col gap-[7px] items-start w-full" data-node-id="1155:19019">
            <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full px-2" data-i18n="nav.section" data-node-id="1155:19020">
              التعلّم الخاص بك
            </p>
            
            <!-- لوحة الطالب -->
            <a
              href="{{ route('dashboard') }}"
              class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors"
              data-node-id="1155:19021"
            >
              <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start flex-1" data-i18n="nav.dashboard" data-node-id="1155:19022">لوحة الطالب</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19023">
                <img src="/assets/images/icon-dashboard.svg" alt="" class="size-full" />
              </div>
            </a>

            <!-- دوراتي -->
            <a
              href="{{ route('courses.show') }}"
              class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors"
              data-node-id="1155:19031"
            >
              <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start flex-1" data-i18n="nav.courses" data-node-id="1155:19032">دوراتي</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19569">
                <img src="/assets/images/icon-courses.svg" alt="" class="size-full" />
              </div>
            </a>

            <!-- المهام -->
            <a
              href="{{ route('assignments.index') }}"
              class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors"
              data-node-id="1155:19034"
            >
              <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start flex-1" data-i18n="nav.assignments" data-node-id="1155:19035">المهام</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19572">
                <img src="/assets/images/icon-assignments.svg" alt="" class="size-full" />
              </div>
            </a>

            <!-- درجاتي - ACTIVE (Node 1155:19037) -->
            <a
              href="{{ route('grades.index') }}"
              class="bg-[#EAF1FB] flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full"
              data-node-id="1155:19037"
            >
              <span class="font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#0B2A8F] text-start flex-1" data-i18n="nav.grades" data-node-id="1155:19038">درجاتي</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19575">
                <img src="/assets/images/icon-grades-active.svg" alt="" class="size-full" />
              </div>
            </a>

            <!-- شهاداتي -->
            <a
              href="{{ route('certificates.index') }}"
              class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors"
              data-node-id="1155:19040"
            >
              <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start flex-1" data-i18n="nav.certificates" data-node-id="1155:19041">شهاداتي</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19578">
                <img src="/assets/images/icon-certificates.svg" alt="" class="size-full" />
              </div>
            </a>

            <!-- حسابي -->
            <a
              href="{{ route('user.profile') }}"
              class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors"
              data-node-id="1155:19043"
            >
              <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start flex-1" data-i18n="nav.profile" data-node-id="1155:19044">حسابي</span>
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19581">
                <img src="/assets/images/icon-profile.svg" alt="" class="size-full" />
              </div>
            </a>
          </div>

          <!-- Learning Reminder Card (Node 1155:19046) -->
          <div class="bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-[10px] items-end rtl:items-end ltr:items-start p-[18px] rounded-[16px] w-full text-start" data-node-id="1155:19046">
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19584">
              <img src="/assets/images/icon-bookmark.svg" alt="" class="size-full" />
            </div>
            <p class="font-['Cairo'] font-bold text-[#012383] text-[15px] leading-[1.8] text-start w-full" data-i18n="nav.reminderTitle" data-node-id="1155:19048">
              خطوة صغيرة، أثر مستمر
            </p>
            <p class="font-['Cairo'] font-medium text-[#475569] text-[12px] leading-[1.8] text-start w-full" data-i18n="nav.reminderDesc" data-node-id="1155:19049">
              تقدّم المشاهدة والمهام محفوظان في مساحة تعلّمك.
            </p>
          </div>
        </div>

        <!-- Sign Out (Node 1155:19050) -->
        <a
          href="{{ route('login') }}"
          class="flex gap-[12px] h-[46px] items-center p-[12px] rounded-[10px] w-full text-[#475569] hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer shrink-0 mt-auto"
          data-node-id="1155:19050"
        >
          <span class="flex-1 font-['Cairo'] font-medium text-[13px] leading-[1.8] text-start" data-i18n="nav.signout" data-node-id="1155:19051">
            تسجيل الخروج
          </span>
          <div class="size-[18px] shrink-0 relative" data-node-id="1155:19587">
            <img src="/assets/images/icon-logout-gray.svg" alt="" class="size-full" />
          </div>
        </a>

      </aside>

      <!-- ============================================== -->
      <!-- 2. MAIN LEARNING WORKSPACE (Width: 1192px)     -->
      <!-- Positioned on the LEFT in RTL                  -->
      <!-- ============================================== -->
      <main class="flex-1 min-w-0 flex flex-col min-h-screen bg-[#FAFCFF] order-2" data-node-id="1155:18869">
        
        <!-- Account Bar (Height: 80px - Node 1155:18870) -->
        <header class="h-[80px] bg-[#FAFCFF] border-b border-[#DEE8F7] px-[40px] flex items-center justify-between shrink-0" data-node-id="1155:18870">
          
          <!-- Search Courses Input (Node 1155:18880) -->
          <div class="w-[330px] h-[42px] bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] px-[14px] flex items-center gap-[12px] focus-within:border-[#0B2A8F] focus-within:ring-1 focus-within:ring-[#0B2A8F] transition-all order-1">
            <input
              type="text"
              class="w-full bg-transparent text-[13px] text-[#012383] placeholder-[#475569] outline-none font-medium text-start font-['Cairo']"
              placeholder="ابحثي في دوراتك ودروسك"
              data-i18n-placeholder="header.searchPlaceholder"
              data-node-id="1155:18881"
            />
            <div class="size-[19px] shrink-0 relative" data-node-id="1155:18882">
              <img src="/assets/images/icon-search.svg" alt="" class="size-full" />
            </div>
          </div>

          <!-- Student Account & Language Switcher Group (Node 1155:18871) -->
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

            <!-- Notifications (Node 1155:18878) -->
            <button type="button" class="size-[42px] flex items-center justify-center rounded-full hover:bg-slate-100 transition-colors relative cursor-pointer" data-node-id="1155:18878">
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19557">
                <img src="/assets/images/icon-bell.svg" alt="" class="size-full" />
              </div>
            </button>

            <!-- Student Profile Summary (Node 1155:18876) -->
            <div class="flex items-center gap-[12px]">
              <div class="size-[38px] rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[#0B2A8F] text-[15px]" data-node-id="1155:18876">
                <span data-i18n="header.studentInitial" data-node-id="1155:18877">أ</span>
              </div>
              <span class="font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#012383] whitespace-nowrap" data-i18n="header.studentName" data-node-id="1155:18875">
                أحمد إبراهيم
              </span>
              <div class="size-[16px] shrink-0 relative cursor-pointer" data-node-id="1155:18872">
                <img src="/assets/images/icon-chevron-down.svg" alt="" class="size-full" />
              </div>
            </div>

          </div>

        </header>

        <!-- Page Content (Node 1155:18886) -->
        <div class="px-4 sm:px-6 lg:px-[40px] pt-[24px] lg:pt-[30px] pb-[40px] flex flex-col gap-[24px] w-full" data-node-id="1155:18886">
          
          <!-- Page Introduction (Node 1155:18887) -->
          <div class="flex flex-col gap-[4px] items-start text-start w-full" data-node-id="1155:18887">
            <p class="font-['Cairo'] font-semibold text-[12px] leading-[1.8] text-[#0B2A8F] text-start w-full" data-i18n="grades.breadcrumb" data-node-id="1155:18888">
              مساحة التعلّم / درجاتي
            </p>
            <h1 class="font-['Cairo'] font-bold text-[28px] sm:text-[32px] leading-[1.3] text-[#012383] text-start w-full" data-i18n="grades.pageTitle" data-node-id="1155:18889">
              درجاتي
            </h1>
            <p class="font-['Cairo'] font-medium text-[13px] sm:text-[14px] leading-[1.7] text-[#475569] text-start w-full" data-i18n="grades.pageSubtitle" data-node-id="1155:18890">
              راجع تقييم مهامك، واقرأ ملاحظات المحاضر لتطوير ممارستك خطوة بخطوة.
            </p>
          </div>

          <!-- Course Grade Filter (Node 1155:18891) -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 w-full" data-node-id="1155:18891">
            <!-- Right in RTL: Course Selector -->
            <div class="flex gap-[12px] items-center" data-node-id="1155:18893">
              <span class="font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#475569] whitespace-nowrap" data-i18n="grades.courseLabel" data-node-id="1155:18897">
                الكورس
              </span>
              <div class="bg-white border border-[#DEE8F7] hover:border-[#0B2A8F]/40 flex gap-[16px] h-[46px] items-center px-[16px] rounded-[10px] w-full sm:w-[290px] justify-between cursor-pointer transition-colors shadow-2xs" data-node-id="1155:18894">
                <span class="font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#012383] flex-1 text-start" data-i18n="grades.selectedCourse" data-node-id="1155:18896">
                  أساسيات التخاطب
                </span>
                <div class="size-[16px] shrink-0 relative" data-node-id="1155:19560">
                  <img src="/assets/images/icon-chevron-down.svg" alt="" class="size-full" />
                </div>
              </div>
            </div>

            <!-- Left in RTL: Filter notice -->
            <p class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569]" data-i18n="grades.filterNotice" data-node-id="1155:18892">
              تعرض النتائج الخاصة بالكورس المختار فقط
            </p>
          </div>

          <!-- Current Grading Summary Metrics (Node 1155:18898) -->
          <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-6 sm:p-7 flex flex-col items-start w-full shadow-[0px_4px_20px_rgba(11,42,143,0.03)]" data-node-id="1155:18898">
            <div class="flex flex-col md:flex-row gap-6 md:gap-[40px] items-start md:items-center w-full" data-node-id="1155:18899">
              
              <!-- Current Equal Weight Average (Right in RTL - w:340px) -->
              <div class="w-full md:w-[340px] flex flex-col gap-[4px] items-start text-start shrink-0 border-b md:border-b-0 md:border-s-0 md:border-l border-[#DEE8F7] pb-4 md:pb-0 md:pe-6" data-node-id="1155:18908">
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#334155] text-start w-full" data-i18n="grades.avgTitle" data-node-id="1155:18909">
                  متوسط المهام المُقيّمة حاليًا
                </p>
                <div class="flex items-baseline gap-2.5">
                  <p class="font-['Cairo'] font-black text-[46px] sm:text-[52px] leading-none text-[#012383] text-start tracking-tight tabular-nums" data-i18n="grades.avgScore" data-node-id="1155:18910">
                    ٨٧
                  </p>
                  <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full font-['Cairo']">
                    مستوى متميز
                  </span>
                </div>
                <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full tabular-nums" data-i18n="grades.avgMeta" data-node-id="1155:18911">
                  من ١٠٠ • بناءً على ٦ مهام مُقيّمة فقط
                </p>
              </div>

              <!-- Submissions (Middle) -->
              <div class="flex flex-1 flex-col gap-[4px] items-start text-start" data-node-id="1155:18904">
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#334155] text-start w-full" data-i18n="grades.submissionsTitle" data-node-id="1155:18905">
                  تسليماتك
                </p>
                <p class="font-['Cairo'] font-black text-[28px] sm:text-[32px] leading-[1.2] text-[#012383] text-start w-full tabular-nums" data-i18n="grades.submissionsCount" data-node-id="1155:18906">
                  ٧ مهام
                </p>
                <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full tabular-nums" data-i18n="grades.submissionsMeta" data-node-id="1155:18907">
                  ٦ مُقيّمة • ١ بانتظار المراجعة
                </p>
              </div>

              <!-- Available Tasks (Left in RTL) -->
              <div class="flex flex-1 flex-col gap-[4px] items-start text-start" data-node-id="1155:18900">
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#334155] text-start w-full" data-i18n="grades.availableTasksTitle" data-node-id="1155:18901">
                  المهام المتاحة
                </p>
                <p class="font-['Cairo'] font-black text-[28px] sm:text-[32px] leading-[1.2] text-[#012383] text-start w-full tabular-nums" data-i18n="grades.availableTasksCount" data-node-id="1155:18902">
                  ٨ من ٢٠
                </p>
                <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full tabular-nums" data-i18n="grades.availableTasksMeta" data-node-id="1155:18903">
                  ١٢ مهمة بعد إكمال فيديوهاتها
                </p>
              </div>

            </div>
          </div>

          <!-- Information Policy Note (Node 1155:18912) -->
          <div class="bg-[#EAF1FB] rounded-[12px] p-[18px] flex gap-[14px] items-start w-full border border-[#DEE8F7]/70" data-node-id="1155:18912">
            <div class="size-[20px] shrink-0 relative mt-[2px]" data-node-id="1155:19563">
              <img src="/assets/images/icon-info-circle.svg" alt="" class="size-full" />
            </div>
            <div class="flex-1 flex flex-col gap-[4px] items-start text-start" data-node-id="1155:18913">
              <h3 class="font-['Cairo'] font-bold text-[14px] leading-[1.6] text-[#012383] text-start w-full" data-i18n="grades.policyTitle" data-node-id="1155:18914">
                كيف يُحسب المتوسط؟ — سياسة تقييم مقترحة
              </h3>
              <p class="font-['Cairo'] font-medium text-[13px] leading-[1.7] text-[#475569] text-start w-full" data-i18n="grades.policyDesc" data-node-id="1155:18915">
                كل مهمة من ١٠٠ درجة، والمهام المُقيّمة متساوية الوزن. مجموع الدرجات ٥٢٢، مقسومًا على ٦ مهام، يعطي متوسطًا قدره ٨٧ من ١٠٠. المهام قيد المراجعة أو غير المُسلّمة لا تُحسب صفرًا، ولا تدخل في المتوسط حتى تقييمها.
              </p>
            </div>
          </div>

          <!-- Task Grades Table Card (Node 1155:18917) -->
          <div class="border border-[#DEE8F7] rounded-[16px] overflow-hidden w-full bg-white shadow-[0px_4px_20px_rgba(11,42,143,0.03)]" data-node-id="1155:18917">
            
            <!-- Grades Table Heading (Node 1155:18918) -->
            <div class="bg-white p-[20px] flex items-center justify-between text-start w-full whitespace-nowrap" data-node-id="1155:18918">
              <h2 class="font-['Cairo'] font-bold text-[19px] sm:text-[20px] leading-[1.4] text-[#012383]" data-i18n="grades.tableTitle" data-node-id="1155:18920">
                سجل مهام الكورس
              </h2>
              <span class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569] bg-slate-50 border border-[#DEE8F7] px-3 py-1 rounded-full" data-i18n="grades.tableCount" data-node-id="1155:18919">
                ٨ مهام متاحة
              </span>
            </div>

            <!-- Responsive Table Scroll Wrapper -->
            <div class="w-full overflow-x-auto">
              <div class="min-w-[680px]">

                <!-- Grades Column Headings (Node 1155:18921) -->
                <div class="bg-[#EAF1FB] flex gap-[18px] items-center px-[18px] py-[14px] font-['Cairo'] font-bold text-[12px] leading-[1.8] text-[#334155] text-start w-full border-t border-[#DEE8F7]" data-node-id="1155:18921">
                  <div class="flex-1 min-w-0" data-i18n="grades.colTask" data-node-id="1155:18926">
                    المهمة والدرس المرتبط
                  </div>
                  <div class="w-[120px] shrink-0" data-i18n="grades.colLevel" data-node-id="1155:18925">
                    المستوى / الدرس
                  </div>
                  <div class="w-[175px] shrink-0" data-i18n="grades.colStatus" data-node-id="1155:18924">
                    حالة المهمة
                  </div>
                  <div class="w-[105px] shrink-0" data-i18n="grades.colGrade" data-node-id="1155:18923">
                    الدرجة من ١٠٠
                  </div>
                  <div class="w-[110px] shrink-0" data-i18n="grades.colDetails" data-node-id="1155:18922">
                    التفاصيل
                  </div>
                </div>

                <!-- Row 1 (Node 1155:18927) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18927">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18934">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row1Title" data-node-id="1155:18935">
                      تطبيق: مدخل إلى التواصل والتخاطب
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row1Sub" data-node-id="1155:18936">
                      مدخل إلى التواصل والتخاطب
                    </p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row1Level" data-node-id="1155:18933">
                    الأول • الدرس ١
                  </p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18930">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18932">
                      مُقيّمة
                    </span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row1Score" data-node-id="1155:18929">
                    ٩٠
                  </p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18928">
                    عرض الملاحظات
                  </a>
                </div>

                <!-- Row 2 (Node 1155:18937) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18937">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18944">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row2Title" data-node-id="1155:18945">
                      تطبيق: مكوّنات عملية التواصل
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row2Sub">
                      مكوّنات عملية التواصل
                    </p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row2Level" data-node-id="1155:18943">
                    الأول • الدرس ٢
                  </p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18940">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18942">
                      مُقيّمة
                    </span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row2Score" data-node-id="1155:18939">
                    ٨٥
                  </p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18938">
                    عرض الملاحظات
                  </a>
                </div>

                <!-- Row 3 (Node 1155:18947) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18947">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18954">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row3Title" data-node-id="1155:18955">
                      تطبيق: الإنصات والملاحظة
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row3Sub" data-node-id="1155:18956">
                      الإنصات والملاحظة
                    </p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row3Level" data-node-id="1155:18953">
                    الأول • الدرس ٣
                  </p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18950">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18952">
                      مُقيّمة
                    </span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row3Score" data-node-id="1155:18949">
                    ٨٨
                  </p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18948">
                    عرض الملاحظات
                  </a>
                </div>

                <!-- Row 4 (Node 1155:18957) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18957">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18964">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row4Title" data-node-id="1155:18965">
                      تطبيق: بيئة التعلّم الداعمة
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row4Sub" data-node-id="1155:18966">
                      بيئة التعلّم الداعمة
                    </p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row4Level" data-node-id="1155:18963">
                    الأول • الدرس ٤
                  </p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18960">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18962">
                      مُقيّمة
                    </span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row4Score" data-node-id="1155:18959">
                    ٩٢
                  </p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18958">
                    عرض الملاحظات
                  </a>
                </div>

                <!-- Row 5 (Node 1155:18967) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18967">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18974">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row5Title" data-node-id="1155:18975">
                      تطبيق: مراجعة المفاهيم الأساسية
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row5Sub" data-node-id="1155:18976">
                      مراجعة المفاهيم الأساسية
                    </p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row5Level" data-node-id="1155:18973">
                    الأول • الدرس ٥
                  </p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18970">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18972">
                      مُقيّمة
                    </span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row5Score" data-node-id="1155:18969">
                    ٨١
                  </p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18968">
                    عرض الملاحظات
                  </a>
                </div>

                <!-- Row 6 - HIGHLIGHTED ROW (Node 1155:18977) -->
                <div class="bg-[#EAF1FB] border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full" data-node-id="1155:18977">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18984">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row6Title" data-node-id="1155:18985">
                      تصميم تدريب التنفس ودعم الصوت
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row6Sub" data-node-id="1155:18986">
                      التنفس ودعم الصوت
                    </p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row6Level" data-node-id="1155:18983">
                    الثاني • الدرس ٦
                  </p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18980">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18982">
                      مُقيّمة
                    </span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row6Score" data-node-id="1155:18979">
                    ٨٦
                  </p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18978">
                    عرض الملاحظات
                  </a>
                </div>

                <!-- Row 7 (Node 1155:18987 - Awaiting Review) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18987">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18995">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row7Title" data-node-id="1155:18996">
                      تطبيق: الإيقاع وسرعة الكلام
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row7Sub" data-node-id="1155:18997">
                      الإيقاع وسرعة الكلام
                    </p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row7Level" data-node-id="1155:18994">
                    الثاني • الدرس ٧
                  </p>
                  <div class="w-[175px] shrink-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18990">
                    <span class="bg-[#F8F4EA] text-[#705B2E] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusPending" data-node-id="1155:18992">
                      بانتظار المراجعة
                    </span>
                    <span class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="grades.submittedToInstructor" data-node-id="1155:18993">
                      مُسلّمة إلى المحاضر
                    </span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-bold text-[20px] text-[#94A3B8] text-start shrink-0 tabular-nums" data-node-id="1155:18989">
                    —
                  </p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-medium text-[13px] text-[#475569] hover:text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewSubmission" data-node-id="1155:18988">
                    عرض التسليم
                  </a>
                </div>

                <!-- Row 8 (Node 1155:18998 - Not Submitted) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18998">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:19005">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row8Title" data-node-id="1155:19006">
                      تطبيق التمييز السمعي
                    </p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row8Sub" data-node-id="1155:19007">
                      التمييز السمعي
                    </p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row8Level" data-node-id="1155:19004">
                    الثاني • الدرس ٨
                  </p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:19001">
                    <span class="bg-[#FAFCFF] border border-[#DEE8F7] text-[#475569] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusNotSubmitted" data-node-id="1155:19003">
                      لم تُسلّم
                    </span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-bold text-[20px] text-[#94A3B8] text-start shrink-0 tabular-nums" data-node-id="1155:19000">
                    —
                  </p>
                  <a href="{{ route('assignments.index') }}" class="w-[110px] font-['Cairo'] font-medium text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.openTask" data-node-id="1155:18999">
                    فتح المهمة
                  </a>
                </div>

              </div>
            </div>

            <!-- Unavailable Later Tasks Notice (Node 1155:19008) -->
            <div class="bg-[#FAFCFF] border-t border-[#DEE8F7] p-[20px] flex gap-[14px] items-center w-full" data-node-id="1155:19008">
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19566">
                <img src="/assets/images/icon-lock-keyhole.svg" alt="" class="size-full" />
              </div>
              <p class="flex-1 font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="grades.unavailableNotice" data-node-id="1155:19009">
                المهام من الدرس ٩ إلى الدرس ٢٠ غير متاحة بعد. تُفتح مهمة كل درس بعد إكمال الفيديو المرتبط بها، ولا توجد درجات لها حاليًا.
              </p>
            </div>

          </div>

          <!-- Bottom Certificate Eligibility Notice (Node 1155:19011) -->
          <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="grades.certRuleNote" data-node-id="1155:19011">
            شهادة الإتمام تعتمد على بلوغ ٩٥٪ من مشاهدة الكورس، بصورة مستقلة عن درجات المهام.
          </p>

        </div>

        <!-- Workspace Footer (Height: 56px - Node 1155:19012) -->
        <footer class="mt-auto border-t border-[#DEE8F7] px-[40px] py-[18px] flex items-center justify-between text-[12px] text-[#475569] font-medium shrink-0 bg-[#FAFCFF]" data-node-id="1155:19012">
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


