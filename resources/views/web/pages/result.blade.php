<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة مركز الأمين | نتيجة المهمة</title>
    <!-- Google Fonts Preconnect and Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@500;600;700;800&family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
    <!-- Stylesheet with Tailwind and Design Tokens -->
    <link rel="stylesheet" href="/src/css/styles.css" />
  </head>
  <body class="bg-[#F8FAFC] min-h-screen text-[#475569] font-body antialiased">
    <div class="max-w-[1440px] mx-auto min-h-screen flex flex-row bg-white shadow-sm" id="app-canvas">
      
      <!-- Main Content -->
            <!-- ============================================== -->
      <!-- 1. PRIMARY NAVIGATION SIDEBAR (248px Width)     -->
      <!-- ============================================== -->
      <!-- 1. PRIMARY NAVIGATION SIDEBAR (Node 1155:18829) -->
      <!-- Width: 248px - Positioned on the RIGHT in RTL   -->
      <!-- ============================================== -->
      <aside class="w-[248px] bg-[#FAFCFF] border-l border-[#DEE8F7] flex flex-col gap-[28px] items-start pb-[24px] pt-[22px] px-[20px] shrink-0 min-h-screen order-1" data-node-id="1155:18829">
        
        <!-- Al Ameen Identity (Node 1155:18830) -->
        <a href="{{ route('home') }}" class="bg-[#EAF1FB] rounded-[16px] px-3 flex gap-[8px] h-[62px] items-center justify-end rtl:justify-end ltr:flex-row-reverse ltr:justify-end w-full shrink-0" data-node-id="1155:18830">
          <span class="font-['Baloo_Bhaijaan_2'] font-extrabold rtl:text-[25px] ltr:text-[17px] leading-[1.3] text-[#012383] text-start whitespace-nowrap shrink-0 opacity-90" data-i18n="brand.name" data-node-id="1155:18831">
            منصة مركز الأمين
          </span>
          <div class="size-[54px] shrink-0 relative" data-node-id="1155:18832">
            <img src="/assets/images/logo.png" alt="منصة مركز الأمين" class="size-full object-contain pointer-events-none" />
          </div>
        </a>

        <!-- Student Navigation Group (Node 1155:18833) -->
        <div class="flex flex-col gap-[7px] items-start w-full overflow-clip shrink-0" data-node-id="1155:18833">
          <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="nav.section" data-node-id="1155:18834">
            التعلّم الخاص بك
          </p>

          <!-- 1. لوحة الطالب -->
          <a
            href="{{ route('dashboard') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:18835"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.dashboard" data-node-id="1155:18836">
              لوحة الطالب
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:18837">
              <img src="/assets/images/icon-dashboard.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 2. دوراتي -->
          <a
            href="{{ route('courses.show') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:18845"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.courses" data-node-id="1155:18846">
              دوراتي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19536">
              <img src="/assets/images/icon-courses.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 3. المهام (ACTIVE - Node 1155:18848) -->
          <a
            href="{{ route('assignments.index') }}"
            class="bg-[#EAF1FB] flex gap-[12px] h-[50px] items-center px-[14px] rounded-[12px] w-full shrink-0"
            data-node-id="1155:18848"
          >
            <span class="flex-1 font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#0B2A8F] text-start" data-i18n="nav.assignments" data-node-id="1155:18849">
              المهام
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19539">
              <img src="/assets/images/icon-assignments-active.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 4. درجاتي -->
          <a
            href="{{ route('grades.index') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:18851"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.grades" data-node-id="1155:18852">
              درجاتي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19542">
              <img src="/assets/images/icon-grades.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 5. شهاداتي -->
          <a
            href="{{ route('certificates.index') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:18854"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.certificates" data-node-id="1155:18855">
              شهاداتي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19545">
              <img src="/assets/images/icon-certificates.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- 6. حسابي -->
          <a
            href="{{ route('user.profile') }}"
            class="flex gap-[12px] h-[50px] items-center px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors shrink-0"
            data-node-id="1155:18857"
          >
            <span class="flex-1 font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-start" data-i18n="nav.profile" data-node-id="1155:18858">
              حسابي
            </span>
            <div class="size-[20px] shrink-0 relative" data-node-id="1155:19548">
              <img src="/assets/images/icon-profile.svg" alt="" class="size-full" />
            </div>
          </a>
        </div>

        <!-- Learning Reminder Card (Node 1155:18860) -->
        <div class="bg-[#EAF1FB] border border-[#DEE8F7] flex flex-col gap-[10px] items-end rtl:items-end ltr:items-start p-[18px] rounded-[16px] w-full text-start overflow-clip shrink-0" data-node-id="1155:18860">
          <div class="size-[20px] shrink-0 relative" data-node-id="1155:19551">
            <img src="/assets/images/icon-bookmark.svg" alt="" class="size-full" />
          </div>
          <p class="font-['Cairo'] font-bold text-[#012383] text-[15px] leading-[1.8] text-start w-full" data-i18n="nav.reminderTitle" data-node-id="1155:18862">
            خطوة صغيرة، أثر مستمر
          </p>
          <p class="font-['Cairo'] font-medium text-[#475569] text-[12px] leading-[1.8] text-start w-full" data-i18n="nav.reminderDesc" data-node-id="1155:18863">
            تقدّم المشاهدة والمهام محفوظان في مساحة تعلّمك.
          </p>
        </div>

        <!-- Sign Out (Node 1155:18864) -->
        <a
          href="{{ route('login') }}"
          class="flex gap-[12px] h-[46px] items-center p-[12px] rounded-[10px] w-full text-[#475569] hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer shrink-0 mt-auto"
          data-node-id="1155:18864"
        >
          <span class="flex-1 font-['Cairo'] font-medium text-[13px] leading-[1.8] text-start" data-i18n="nav.signout" data-node-id="1155:18865">
            تسجيل الخروج
          </span>
          <div class="size-[18px] shrink-0 relative" data-node-id="1155:19554">
            <img src="/assets/images/icon-logout-gray.svg" alt="" class="size-full" />
          </div>
        </a>

      </aside>

      <main class="flex-1 min-w-0 flex flex-col min-h-screen bg-white order-2">
        
        <!-- Header Bar -->
                <!-- Top Account Bar (Node 1155:19213 - Height: 80px) -->
        <!-- Search on Right (adjacent to sidebar), Student Account on Left -->
        <header class="h-[80px] bg-[#FAFCFF] border-b border-[#DEE8F7] px-4 sm:px-6 lg:px-10 flex items-center justify-between shrink-0">
          
          <!-- Search Courses Input (On the RIGHT in RTL) -->
          <div class="hidden sm:flex w-[240px] md:w-[330px] h-[42px] bg-white border border-[#DEE8F7] rounded-[10px] px-3.5 items-center gap-3 focus-within:border-[#0B2A8F] focus-within:ring-1 focus-within:ring-[#0B2A8F] transition-all order-1">
            <input
              type="text"
              class="w-full bg-transparent text-[13px] text-[#012383] placeholder-[#475569] outline-none font-medium text-right font-['Cairo']"
              placeholder="ابحثي في دوراتك ودروسك"
              data-i18n-placeholder="header.searchPlaceholder"
            />
            <div class="size-[19px] shrink-0 relative">
              <img src="/assets/images/icon-search.svg" alt="" class="size-full" />
            </div>
          </div>

          <!-- Student Account & Language Switcher Group (On the LEFT in RTL) -->
          <div class="flex items-center gap-4 order-2">
            
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
            <div class="flex items-center gap-3">
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

        <!-- Result Content (Node 1155:18694) -->
        <div class="px-4 sm:px-6 lg:px-10 py-6 md:py-8 flex-1 flex flex-col gap-6">
          
          <div>
            <div class="flex items-center gap-2 text-xs text-[#94A3B8] mb-1 font-semibold font-['Cairo']">
              <a href="{{ route('assignments.index') }}" class="hover:underline text-[#0B2A8F]">المهام</a>
              <span>/</span>
              <span class="text-[#475569]">نتيجة التقييم</span>
            </div>
            <h1 class="text-[28px] sm:text-[32px] md:text-[34px] font-extrabold text-[#012383] font-['Cairo'] leading-[1.3] mb-1.5" data-i18n="result.pageTitle">
              نتيجة المهمة والتقييم
            </h1>
            <p class="text-[14px] md:text-[15px] text-[#475569] font-medium font-['Cairo']">
              إعداد خطة علاجية لحالة تأخر لغوي — تم التقييم والاعتماد من قبل الاستشاري
            </p>
          </div>

          <!-- Score Card -->
          <div class="w-full bg-white border border-[#DEE8F7] rounded-[16px] md:rounded-[20px] p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
            
            <div class="flex items-center gap-6">
              <div class="size-24 rounded-2xl bg-[#EEF7F3] border border-[#D1EADB] text-[#216350] flex flex-col items-center justify-center shadow-inner font-['Cairo']">
                <span class="text-[36px] font-extrabold leading-none text-[#216350]">٩٨</span>
                <span class="text-[11px] font-bold text-slate-500 uppercase">من ١٠٠</span>
              </div>

              <div>
                <span class="px-3 py-1 rounded-full bg-[#EEF7F3] text-[#216350] text-xs font-bold font-['Cairo']" data-i18n="result.gradeStatus">
                  ممتاز - تم الاعتماد ✦
                </span>
                <h3 class="text-[20px] md:text-[22px] font-bold text-[#012383] mt-2 font-['Cairo']">
                  تم اجتياز متطلبات المهمة بنجاح
                </h3>
                <p class="text-xs text-[#94A3B8] mt-1">
                  تاريخ التقييم: ٢٣ أكتوبر ٢٠٢٦ • د. أحمد عبد الرحمن
                </p>
              </div>
            </div>

            <!-- Action Button -->
            <a
              href="{{ route('certificates.index') }}"
              class="h-[46px] px-6 rounded-[10px] bg-[#0B2A8F] hover:bg-[#012383] text-white text-[14px] font-bold inline-flex items-center gap-2 shadow-sm transition-all"
            >
              <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <span data-i18n="result.viewCert">الانتقال إلى الشهادات</span>
            </a>

          </div>

          <!-- Instructor Feedback Box -->
          <div class="w-full bg-[#FAFCFF] border border-[#DEE8F7] rounded-[16px] p-6 shadow-sm flex flex-col gap-3">
            <div class="flex items-center gap-2 text-[#012383] font-bold text-[16px]">
              <svg class="size-5 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
              <span data-i18n="result.instructorFeedback">ملاحظات المحاضر</span>
            </div>
            <p class="text-[15px] text-[#475569] leading-relaxed bg-white p-4 rounded-[10px] border border-[#DEE8F7]" data-i18n="result.feedbackText">
              أداء متميز وتطبيق دقيق لأساليب التخاطب المعتمدة. الخطة شملت تشخيصاً دقيقاً وتدرجاً منطقياً في الأنشطة الموجهة لتنمية الحصيلة اللغوية. استمر في هذا المستوى المتقدم!
            </p>
          </div>

          <!-- Submission File Meta -->
          <div class="w-full p-4 rounded-[12px] bg-white border border-[#DEE8F7] flex items-center justify-between text-xs text-[#475569]">
            <div class="flex items-center gap-3">
              <div class="size-10 rounded-lg bg-red-50 text-red-600 font-bold flex items-center justify-center text-xs">PDF</div>
              <div>
                <p class="font-bold text-[#012383]">Language_Therapy_Plan_Ahmed.pdf</p>
                <p class="text-[11px] text-[#94A3B8]">2.4 MB • تم الرفع في ٢٢ أكتوبر ٢٠٢٦</p>
              </div>
            </div>
            <a href="#" class="text-[#0B2A8F] font-bold hover:underline" data-i18n="common.download">تنزيل نسختك</a>
          </div>

        </div>

        <footer class="h-[58px] border-t border-[#DEE8F7] px-10 flex items-center justify-between text-[12px] text-[#94A3B8] shrink-0">
          <span data-i18n="certificate.footerCopy">منصة مركز الأمين © ٢٠٢٦</span>
          <span data-i18n="certificate.footerLegal">منصة تعليمية لكورسات التخاطب</span>
        </footer>

      </main>

      <!-- Sidebar -->
      

    </div>
    <script type="module" src="/src/js/i18n.js"></script>
      <script type="module" src="/src/js/animations.js"></script>
  </body>
</html>


