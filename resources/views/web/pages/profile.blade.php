<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة مركز الأمين | حسابي</title>
    <!-- Google Fonts Preconnect and Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@500;600;700;800&family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
    <!-- Stylesheet with Tailwind and Design Tokens -->
    <link rel="stylesheet" href="/src/css/styles.css" />
  </head>
  <body class="bg-[#FAFCFF] min-h-screen text-[#475569] font-['Cairo'] antialiased">
    
    <!-- Toast Notification Container -->
    <div id="toast" class="fixed bottom-6 start-6 z-50 transform translate-y-20 opacity-0 pointer-events-none transition-all duration-300 bg-[#012383] text-white px-5 py-3.5 rounded-xl shadow-xl flex items-center gap-3">
      <svg class="size-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      <span id="toast-message" class="text-sm font-semibold"></span>
    </div>

    <!-- Main 1440px Canvas Container -->
    <div class="max-w-[1440px] mx-auto min-h-screen flex flex-row bg-[#FAFCFF]" id="app-canvas">
      
      <!-- ============================================== -->
      <!-- 1. PRIMARY NAVIGATION SIDEBAR (248px Width)     -->
      <!-- Positioned on the RIGHT in RTL (1st child)      -->
      <!-- ============================================== -->
      <aside class="w-[248px] bg-white border-l border-[#DEE8F7] flex flex-col gap-[28px] pb-[24px] pt-[22px] px-[20px] shrink-0 min-h-screen z-10">
        
        <!-- Al-Ameen Brand Identity -->
        <a href="{{ route('home') }}" class="flex gap-[8px] h-[62px] items-center justify-end w-full cursor-pointer">
          <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-[25px] leading-[1.45] text-[#012383] whitespace-nowrap" data-i18n="brand.name">
            منصة مركز الأمين
          </span>
          <div class="size-[54px] shrink-0 relative">
            <img src="/assets/images/logo.png" alt="منصة مركز الأمين" class="size-full object-contain pointer-events-none" />
          </div>
        </a>

        <!-- Student Navigation Group -->
        <div class="flex flex-col gap-[7px] items-start w-full">
          <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-right w-full px-2" data-i18n="nav.section">
            التعلّم الخاص بك
          </p>

          <!-- لوحة الطالب -->
          <a
            href="{{ route('dashboard') }}"
            class="flex gap-[12px] h-[50px] items-center justify-between px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors cursor-pointer"
          >
            <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-right flex-1" data-i18n="nav.dashboard">لوحة الطالب</span>
            <div class="size-[20px] shrink-0 relative">
              <img src="/assets/images/icon-dashboard.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- دوراتي -->
          <a
            href="{{ route('courses.show') }}"
            class="flex gap-[12px] h-[50px] items-center justify-between px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors cursor-pointer"
          >
            <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-right flex-1" data-i18n="nav.courses">دوراتي</span>
            <div class="size-[20px] shrink-0 relative">
              <img src="/assets/images/icon-courses.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- المهام -->
          <a
            href="{{ route('assignments.index') }}"
            class="flex gap-[12px] h-[50px] items-center justify-between px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors cursor-pointer"
          >
            <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-right flex-1" data-i18n="nav.assignments">المهام</span>
            <div class="size-[20px] shrink-0 relative">
              <img src="/assets/images/icon-assignments.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- درجاتي -->
          <a
            href="{{ route('grades.index') }}"
            class="flex gap-[12px] h-[50px] items-center justify-between px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors cursor-pointer"
          >
            <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-right flex-1" data-i18n="nav.grades">درجاتي</span>
            <div class="size-[20px] shrink-0 relative">
              <img src="/assets/images/icon-grades.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- شهاداتي -->
          <a
            href="{{ route('certificates.index') }}"
            class="flex gap-[12px] h-[50px] items-center justify-between px-[14px] rounded-[10px] w-full text-[#475569] hover:bg-[#EAF1FB]/50 hover:text-[#012383] transition-colors cursor-pointer"
          >
            <span class="font-['Cairo'] font-semibold text-[14px] leading-[1.8] text-right flex-1" data-i18n="nav.certificates">شهاداتي</span>
            <div class="size-[20px] shrink-0 relative">
              <img src="/assets/images/icon-certificates.svg" alt="" class="size-full" />
            </div>
          </a>

          <!-- حسابي - ACTIVE -->
          <a
            href="{{ route('user.profile') }}"
            class="bg-[#EAF1FB] flex gap-[12px] h-[50px] items-center justify-between px-[14px] rounded-[10px] w-full cursor-pointer"
          >
            <span class="font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#0B2A8F] text-right flex-1" data-i18n="nav.profile">حسابي</span>
            <div class="size-[20px] shrink-0 relative">
              <img src="/assets/images/icon-profile.svg" alt="" class="size-full" />
            </div>
          </a>
        </div>

        <!-- Learning Reminder Card -->
        <div class="bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-[10px] items-end rtl:items-end ltr:items-start p-[18px] rounded-[16px] w-full text-start overflow-clip shrink-0">
          <div class="size-[20px] shrink-0 relative">
            <img src="/assets/images/icon-bookmark.svg" alt="" class="size-full" />
          </div>
          <p class="font-['Cairo'] font-bold text-[#012383] text-[15px] leading-[1.8] text-start w-full" data-i18n="nav.reminderTitle">
            خطوة صغيرة، أثر مستمر
          </p>
          <p class="font-['Cairo'] font-medium text-[#475569] text-[12px] leading-[1.8] text-start w-full" data-i18n="nav.reminderDesc">
            تقدّم المشاهدة والمهام محفوظان في مساحة تعلّمك.
          </p>
        </div>

        <!-- Sign out -->
        <a
          href="{{ route('login') }}"
          class="flex gap-[12px] h-[46px] items-center justify-between p-[12px] rounded-[10px] w-full text-[#475569] hover:bg-red-50 hover:text-red-600 cursor-pointer transition-colors mt-auto"
        >
          <span class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-start flex-1" data-i18n="nav.signout">تسجيل الخروج</span>
          <div class="size-[18px] shrink-0 relative">
            <img src="/assets/images/icon-logout-gray.svg" alt="" class="size-full" />
          </div>
        </a>

      </aside>

      <!-- ============================================== -->
      <!-- 2. MAIN LEARNING WORKSPACE (1192px Area)       -->
      <!-- Positioned on the LEFT in RTL                  -->
      <!-- ============================================== -->
      <main class="flex-1 min-w-0 flex flex-col min-h-screen bg-[#FAFCFF] order-2">
        
        <!-- Account Bar (Height: 80px) -->
        <header class="h-[80px] bg-[#FAFCFF] border-b border-[#DEE8F7] px-[40px] flex items-center justify-between shrink-0">
          
          <!-- Search Courses Input -->
          <div class="w-[330px] h-[42px] bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] px-[14px] flex items-center gap-[12px] focus-within:border-[#0B2A8F] focus-within:ring-1 focus-within:ring-[#0B2A8F] transition-all order-1">
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
                <span class="font-bold text-[15px] text-[#0B2A8F] font-['Cairo']" data-i18n="header.studentInitial">
                  أ
                </span>
              </div>
              <span class="font-bold text-[14px] text-[#012383] font-['Cairo'] whitespace-nowrap" data-i18n="header.studentName">
                أحمد إبراهيم
              </span>
              <div class="size-[16px] shrink-0 relative cursor-pointer">
                <img src="/assets/images/icon-account-menu.svg" alt="" class="size-full" />
              </div>
            </div>

          </div>

        </header>

        <!-- ============================================== -->
        <!-- 3. PAGE BODY CONTENT                           -->
        <!-- ============================================== -->
        <div class="px-[40px] pt-[30px] pb-[40px] flex-1 flex flex-col gap-[24px]">
          
          <!-- Page Introduction -->
          <div class="flex flex-col gap-[2px] items-start text-start w-full overflow-clip">
            <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#0B2A8F] w-full text-start" data-i18n="profile.breadcrumb">
              مساحة التعلّم  /  حسابي
            </p>
            <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-[34px] leading-[1.45] text-[#012383] w-full text-start" data-i18n="profile.pageTitle">
              حسابي
            </h1>
            <p class="font-['Cairo'] font-medium text-[14px] leading-[1.8] text-[#475569] w-full text-start" data-i18n="profile.pageDesc">
              إدارة بياناتك الشخصية، كلمة المرور، وتفضيلات الإشعارات.
            </p>
          </div>

          <!-- Profile Hero Card (Clean & Focused) -->
          <div class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-6">
            
            <div class="flex items-center gap-5">
              <!-- Avatar with edit trigger -->
              <div class="relative group">
                <div class="size-20 rounded-full bg-gradient-to-tr from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-2xl shadow-md shrink-0">
                  أ
                </div>
                <button
                  type="button"
                  id="avatar-upload-btn"
                  title="تغيير الصورة"
                  class="absolute inset-0 rounded-full bg-black/40 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                >
                  <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </button>
              </div>

              <div class="flex flex-col gap-1.5 text-start">
                <div class="flex items-center gap-3">
                  <h2 class="font-bold text-xl text-[#012383]" data-i18n="header.studentName">أحمد إبراهيم السالم</h2>
                  <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-[#EEF7F3] text-emerald-700 text-xs font-bold">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span data-i18n="profile.studentRole">طالب مسجل</span>
                  </span>
                </div>
                <p class="text-xs text-[#475569]">ahmed.ibrahim@example.com</p>
                <div class="flex items-center gap-2 mt-0.5">
                  <span class="bg-[#EAF1FB] text-[#0B2A8F] text-[11px] font-bold px-2.5 py-1 rounded-full" data-i18n="profile.joinedDate">
                    تاريخ الانضمام: أكتوبر ٢٠٢٦
                  </span>
                </div>
              </div>
            </div>

            <!-- Avatar Action Button -->
            <div>
              <button
                type="button"
                id="change-avatar-trigger"
                class="flex items-center gap-2 px-4 py-2 bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#012383] font-bold text-xs rounded-xl transition-colors cursor-pointer"
              >
                <svg class="size-4 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span data-i18n="profile.changeAvatar">تغيير الصورة</span>
              </button>
            </div>

          </div>

          <!-- Profile Settings Card -->
          <div class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col gap-6">
            
            <!-- Tab Navigation Header (3 Clean Tabs) -->
            <div class="flex items-center gap-2 border-b border-[#DEE8F7] pb-3" id="profile-tabs-header">
              <button
                type="button"
                data-tab="personal"
                class="profile-tab-btn active px-4 py-2 rounded-xl text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer"
                data-i18n="profile.tabPersonal"
              >
                البيانات الشخصية
              </button>
              <button
                type="button"
                data-tab="security"
                class="profile-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all text-[#475569] hover:bg-[#EAF1FB] cursor-pointer"
                data-i18n="profile.tabSecurity"
              >
                كلمة المرور والأمان
              </button>
              <button
                type="button"
                data-tab="notifications"
                class="profile-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all text-[#475569] hover:bg-[#EAF1FB] cursor-pointer"
                data-i18n="profile.tabNotifications"
              >
                تفضيلات الإشعارات
              </button>
            </div>

            <!-- Tab 1: Personal Info -->
            <div id="tab-personal" class="profile-tab-content flex flex-col gap-5">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5 text-start">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.fullNameLabel">الاسم الكامل</label>
                  <input
                    type="text"
                    value="أحمد إبراهيم السالم"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
                <div class="flex flex-col gap-1.5 text-start">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.emailLabel">البريد الإلكتروني</label>
                  <input
                    type="email"
                    value="ahmed.ibrahim@example.com"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5 text-start">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.phoneLabel">رقم الجوال</label>
                  <input
                    type="tel"
                    value="+966 54 987 6543"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
                <div class="flex flex-col gap-1.5 text-start">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.cityLabel">المدينة والدولة</label>
                  <input
                    type="text"
                    value="الرياض، المملكة العربية السعودية"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
              </div>

              <div class="flex flex-col gap-1.5 text-start">
                <label class="text-xs font-bold text-[#012383]" data-i18n="profile.bioLabel">نبذة تعريفية</label>
                <textarea
                  rows="3"
                  class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl p-3.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15 resize-none leading-relaxed"
                  data-i18n-placeholder="profile.bioPlaceholder"
                  placeholder="اكتب نبذة تعريفية مختصرة عن اهتماماتك في علوم التخاطب والتعلم..."
                >طالب ودارس في علوم التخاطب واضطرابات النطق، أتابع كورس منصة مركز الأمين للتطبيق العملي واكتساب الخبرة السريرية المعتمدة.</textarea>
              </div>
            </div>

            <!-- Tab 2: Password & Security (Hidden by default) -->
            <div id="tab-security" class="profile-tab-content hidden flex-col gap-4">
              <div class="max-w-md flex flex-col gap-4 text-start">
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.currentPasswordLabel">كلمة المرور الحالية</label>
                  <input
                    type="password"
                    placeholder="••••••••"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.newPasswordLabel">كلمة المرور الجديدة</label>
                  <input
                    type="password"
                    placeholder="أدخل كلمة المرور الجديدة"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.confirmNewPasswordLabel">تأكيد كلمة المرور الجديدة</label>
                  <input
                    type="password"
                    placeholder="أعد إدخال كلمة المرور الجديدة"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>

                <div class="p-3.5 rounded-xl bg-[#EAF1FB]/50 border border-[#DEE8F7] flex items-center gap-2.5">
                  <svg class="size-4 text-[#0B2A8F] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <p class="text-[11px] text-[#012383] font-medium leading-relaxed" data-i18n="profile.passwordHint">
                    يجب أن تتكون كلمة المرور من ٨ خانات على الأقل وتتضمن أرقامًا وحروفًا.
                  </p>
                </div>
              </div>
            </div>

            <!-- Tab 3: Notification Preferences (Hidden by default) -->
            <div id="tab-notifications" class="profile-tab-content hidden flex-col gap-3">
              
              <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex items-center justify-between gap-4">
                <div class="text-start">
                  <h4 class="text-xs font-bold text-[#012383]" data-i18n="profile.notifNewLessons">إشعارات الدروس والمحتوى الجديد</h4>
                  <p class="text-[11px] text-[#475569] mt-0.5" data-i18n="profile.notifNewLessonsDesc">تنبيهك فور إضافة دروس وفيديوهات جديدة في كورساتك المسجلة.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                  <input type="checkbox" checked class="sr-only peer" />
                  <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0B2A8F]"></div>
                </label>
              </div>

              <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex items-center justify-between gap-4">
                <div class="text-start">
                  <h4 class="text-xs font-bold text-[#012383]" data-i18n="profile.notifAssignments">إشعارات تقييم المهام والواجبات</h4>
                  <p class="text-[11px] text-[#475569] mt-0.5" data-i18n="profile.notifAssignmentsDesc">تنبيهك عند استلام تقييم وملاحظات المحاضر على واجباتك المرسلة.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                  <input type="checkbox" checked class="sr-only peer" />
                  <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0B2A8F]"></div>
                </label>
              </div>

              <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex items-center justify-between gap-4">
                <div class="text-start">
                  <h4 class="text-xs font-bold text-[#012383]" data-i18n="profile.notifReminders">إشعارات التذكير بمتابعة التعلّم</h4>
                  <p class="text-[11px] text-[#475569] mt-0.5" data-i18n="profile.notifRemindersDesc">تذكيرات أسبوعية خفيفة لمساعدتك على إكمال مسارك التعليمي.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                  <input type="checkbox" checked class="sr-only peer" />
                  <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0B2A8F]"></div>
                </label>
              </div>

            </div>

            <!-- Footer Save Actions -->
            <div class="pt-4 border-t border-[#DEE8F7] flex items-center justify-end gap-3">
              <button
                type="button"
                id="profile-cancel-btn"
                class="px-5 py-2.5 rounded-full text-xs font-bold text-[#475569] hover:bg-[#EAF1FB] transition-colors cursor-pointer"
                data-i18n="profile.cancelBtn"
              >
                إلغاء
              </button>
              <button
                type="button"
                id="profile-save-btn"
                class="px-7 py-2.5 rounded-full text-xs font-bold bg-[#012383] text-white hover:bg-[#0B2A8F] transition-all shadow-sm hover:shadow-md cursor-pointer"
                data-i18n="profile.saveChangesBtn"
              >
                حفظ التغييرات
              </button>
            </div>

          </div>

        </div>

      </main>

    </div>

    <!-- Scripts -->
    <script type="module" src="/src/main.js"></script>
    <script type="module" src="/src/js/i18n.js"></script>
    <script>
      // ==========================================
      // Profile Tabs & Save Interactive Script
      // ==========================================
      document.addEventListener('DOMContentLoaded', () => {
        const tabBtns = document.querySelectorAll('.profile-tab-btn');
        const tabContents = document.querySelectorAll('.profile-tab-content');
        const saveBtn = document.getElementById('profile-save-btn');
        const cancelBtn = document.getElementById('profile-cancel-btn');
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        // Tab Switching
        tabBtns.forEach(btn => {
          btn.addEventListener('click', () => {
            const targetTab = btn.getAttribute('data-tab');

            tabBtns.forEach(b => {
              b.classList.remove('active', 'bg-[#012383]', 'text-white', 'shadow-xs');
              b.classList.add('text-[#475569]', 'hover:bg-[#EAF1FB]');
            });
            btn.classList.add('active', 'bg-[#012383]', 'text-white', 'shadow-xs');
            btn.classList.remove('text-[#475569]', 'hover:bg-[#EAF1FB]');

            tabContents.forEach(content => {
              if (content.id === `tab-${targetTab}`) {
                content.classList.remove('hidden');
                content.classList.add('flex');
              } else {
                content.classList.add('hidden');
                content.classList.remove('flex');
              }
            });
          });
        });

        // Save Button Feedback
        saveBtn?.addEventListener('click', () => {
          const isEn = document.documentElement.lang === 'en';
          toastMessage.textContent = isEn ? 'Changes have been saved successfully!' : 'تم حفظ التغييرات بنجاح!';
          toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
          setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
          }, 3500);
        });

        // Cancel Button Feedback
        cancelBtn?.addEventListener('click', () => {
          const isEn = document.documentElement.lang === 'en';
          toastMessage.textContent = isEn ? 'Changes reverted.' : 'تم التراجع عن التغييرات.';
          toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
          setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
          }, 2500);
        });
      });
    </script>
      <script type="module" src="/src/js/animations.js"></script>
  </body>
</html>


