<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة مركز الأمين | دليل البرامج والدورات</title>
    <!-- Google Fonts Preconnect and Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@500;600;700;800&family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
    <!-- Stylesheet with Tailwind and Design Tokens -->
    <link rel="stylesheet" href="/src/css/styles.css" />
  </head>
  <body class="bg-[#FAFCFF] min-h-screen text-[#475569] font-['Cairo'] antialiased flex flex-col justify-between selection:bg-[#EAF1FB] selection:text-[#0B2A8F]">
    
    <!-- Main Container -->
    <div class="w-full max-w-[1440px] mx-auto min-h-screen flex flex-col justify-between relative bg-[#FAFCFF]">
      
      <!-- ============================================== -->
      <!-- UNIFIED MASTER HEADER                          -->
      <!-- ============================================== -->
      <header class="w-full bg-[#FAFCFF] border-b border-[#D7E3F5] flex items-center justify-between px-4 sm:px-6 lg:px-[48px] h-[80px] sm:h-[88px] lg:h-[95px] shrink-0 z-30 relative">
        
        <!-- Brand Identity -->
        <a href="{{ route('home') }}" class="flex gap-2.5 sm:gap-3 lg:gap-[14px] items-center shrink-0 cursor-pointer">
          <div class="size-[42px] sm:size-[52px] lg:size-[64px] shrink-0 relative">
            <img src="/assets/images/logo.png" alt="منصة مركز الأمين" class="size-full object-contain pointer-events-none" />
          </div>
          <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-[22px] sm:text-[30px] lg:text-[38px] leading-tight text-[#012383] whitespace-nowrap tracking-tight" data-i18n="brand.name">
            منصة مركز الأمين
          </span>
        </a>

        <!-- Main Navigation Links -->
        <nav class="hidden lg:flex gap-[6px] xl:gap-[12px] items-center shrink-0">
          
          <!-- الرئيسية -->
          <a href="{{ route('home') }}#home" class="px-3.5 py-2 text-[#012383] hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors" data-i18n="landing.navHome">
            الرئيسية
          </a>

          <!-- Active: الدورات -->
          <a href="{{ route('courses.index') }}" class="px-4 py-2 bg-[#EAF1FB] text-[#0B2A8F] text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors" data-i18n="landing.navCourses">
            الدورات
          </a>

          <!-- من نحن -->
          <a href="{{ route('home') }}#about" class="px-3.5 py-2 text-[#012383] hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors" data-i18n="landing.navAbout">
            من نحن
          </a>

          <!-- آراء الطلاب -->
          <a href="{{ route('home') }}#reviews" class="px-3.5 py-2 text-[#012383] hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors" data-i18n="landing.navTestimonials">
            آراء الطلاب
          </a>

          <!-- تواصل معنا -->
          <a href="{{ route('home') }}#contact" class="px-3.5 py-2 text-[#012383] hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors" data-i18n="landing.navContact">
            تواصل معنا
          </a>

        </nav>

        <!-- Left Group (Language Switcher & Auth) -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
          
          <!-- Interactive Language Switcher Pill -->
          <div class="relative" id="lang-switcher">
            <button
              type="button"
              id="lang-toggle-btn"
              aria-haspopup="true"
              aria-expanded="false"
              class="flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-3.5 h-[36px] sm:h-[40px] rounded-full bg-[#EAF1FB] text-[#012383] hover:bg-[#DEE8F7] transition-all text-xs font-bold cursor-pointer border border-[#D7E3F5]/80 shadow-xs"
            >
              <svg class="size-3.5 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8" />
              </svg>
              <span id="current-lang-label">العربية</span>
              <svg class="size-2.5 sm:size-3 text-[#475569] lang-caret transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <div
              id="lang-dropdown"
              class="hidden absolute start-0 top-[calc(100%+8px)] w-36 rounded-2xl bg-white shadow-xl border border-[#DEE8F7] p-1.5 z-50 transition-all flex-col gap-1"
            >
              <button
                type="button"
                data-set-lang="ar"
                class="w-full flex items-center justify-between px-3 py-2 text-xs text-[#012383] hover:bg-[#EAF1FB] rounded-xl font-bold cursor-pointer text-start transition-colors"
              >
                <span>العربية</span>
                <svg class="size-3.5 text-[#0B2A8F] lang-check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
              </button>
              <button
                type="button"
                data-set-lang="en"
                class="w-full flex items-center justify-between px-3 py-2 text-xs text-[#475569] hover:bg-[#EAF1FB] rounded-xl font-bold cursor-pointer text-start transition-colors"
              >
                <span>English</span>
                <svg class="size-3.5 text-[#0B2A8F] lang-check-icon opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Button: تسجيل الدخول -->
          <a
            href="{{ route('login') }}"
            class="bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center px-4 sm:px-6 h-[36px] sm:h-[40px] rounded-full text-white text-xs sm:text-sm font-bold font-['Cairo'] transition-all shadow-sm hover:shadow-md cursor-pointer whitespace-nowrap"
            data-i18n="landing.loginBtn"
          >
            تسجيل الدخول
          </a>

          <!-- Mobile Hamburger Toggle Button -->
          <button
            type="button"
            id="mobile-menu-toggle"
            aria-label="القائمة الرئيسية"
            class="lg:hidden flex items-center justify-center size-[36px] rounded-xl bg-[#EAF1FB] text-[#012383] hover:bg-[#DEE8F7] transition-colors cursor-pointer"
          >
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

        </div>

      </header>

      <!-- Mobile Dropdown Navigation Menu -->
      <div id="mobile-nav-menu" class="hidden lg:hidden w-full bg-[#FAFCFF] border-b border-[#D7E3F5] px-4 py-3 flex-col gap-1.5 z-20 shadow-md">
        <a href="{{ route('home') }}#home" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50" data-i18n="landing.navHome">الرئيسية</a>
        <a href="{{ route('courses.index') }}" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm bg-[#EAF1FB] text-[#0B2A8F]" data-i18n="landing.navCourses">الكورسات</a>
        <a href="{{ route('home') }}#about" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50" data-i18n="landing.navAbout">من نحن</a>
        <a href="{{ route('home') }}#reviews" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50" data-i18n="landing.navTestimonials">آراء الطلاب</a>
        <a href="{{ route('home') }}#contact" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50" data-i18n="landing.navContact">تواصل معنا</a>
      </div>

      <!-- ============================================== -->
      <!-- MAIN PAGE CONTENT                              -->
      <!-- ============================================== -->
      <main class="flex-1 w-full px-6 lg:px-[48px] py-8 lg:py-12 flex flex-col gap-8">
        
        <!-- 1. HERO SEARCH SECTION (WorkDo Marketplace Hero) -->
        <section class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto w-full">
          
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF1FB] border border-[#0B2A8F]/20 text-[#0B2A8F] text-xs font-bold">
            <span data-i18n="catalog.eyebrow">دليل كورسات التخاطب للطلاب</span>
          </div>

          <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight" data-i18n="catalog.heroTitle">
            استكشف كورسات التخاطب للطلاب
          </h1>

          <p class="text-base sm:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed" data-i18n="catalog.heroDesc">
            اختر الكورس الأنسب لمسيرتك التعليمية مع مناهج تدريبية معتمدة وتطبيقات عملية شاملة للطلاب.
          </p>

          <!-- Search Input Bar -->
          <div class="w-full max-w-2xl mt-2 relative">
            <input
              type="text"
              id="course-search-input"
              placeholder="ابحث باسم الكورس، المحاضر، أو المهارة..."
              data-i18n-placeholder="catalog.searchPlaceholder"
              class="w-full h-13 bg-white border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/20 rounded-2xl px-12 text-sm text-[#012383] placeholder-[#475569] outline-none shadow-sm transition-all"
            />
            <svg class="size-5 text-[#0B2A8F] absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>

        </section>

        <!-- 2. FILTER TABS & COURSE GRID (WorkDo Product Grid) -->
        <section class="flex flex-col gap-6">
          
          <!-- Category Filter Bar -->
          <div class="flex flex-wrap items-center justify-between gap-3 bg-white border border-[#DEE8F7] rounded-[16px] p-3 shadow-xs">
            <div class="flex flex-wrap items-center gap-2" id="catalog-category-group">
              <button
                type="button"
                data-cat="all"
                class="catalog-filter active px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer shadow-xs"
              >
                كورس التخاطب
              </button>
            </div>

            <!-- Stats Badge -->
            <span class="text-xs font-bold text-[#0B2A8F] bg-[#EAF1FB] px-3.5 py-1.5 rounded-full border border-[#DEE8F7]">
              مسار التخاطب السريري للطلاب
            </span>
          </div>

          <!-- Courses Grid (3 Columns) -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="catalog-courses-grid">
            
            <!-- Course Card 1: Language Development Strategies -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="استراتيجيات تنمية اللغة">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#012383] relative overflow-hidden">
                  <img src="/assets/images/course-topic-strategies.png" alt="استراتيجيات تنمية اللغة" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-[#10B77F] text-white text-[11px] font-bold shadow-xs">الأكثر طلباً</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">٢٠ درساً</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">تخاطب ونطق</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 4.9</span>
                      <span class="text-slate-400 font-normal">(480)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    استراتيجيات تنمية اللغة والتواصل
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    دليل شامل يركز على التطبيقات السريرية لتطوير الحصيلة اللغوية وتأهيل اضطرابات التواصل للطلاب والدارسين.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      د
                    </div>
                    <span>د. عمر القحطاني • ٨ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

            <!-- Course Card 2: Articulation Disorders Applications -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="تطبيقات مخارج الحروف">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#0B2A8F] relative overflow-hidden">
                  <img src="/assets/images/course-topic-articulation.png" alt="تطبيقات مخارج الحروف" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-amber-500 text-white text-[11px] font-bold shadow-xs">مميز</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٦ درساً</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">تخاطب ونطق</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 4.8</span>
                      <span class="text-slate-400 font-normal">(310)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    تطبيقات مخارج الحروف وتصحيح النطق
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    برنامج عملي مركز لتصحيح إبدال وتشويه الأصوات الكلامية (الراء، السين، الكاف) بالتمارين العضلية.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      أ
                    </div>
                    <span>أ. أحمد السالم • ٦ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

            <!-- Course Card 3: Speech Therapy Fundamentals -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="أساسيات التخاطب">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#152D4D] relative overflow-hidden">
                  <img src="/assets/images/course-topic-basics.png" alt="أساسيات التخاطب" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-blue-600 text-white text-[11px] font-bold shadow-xs">مبتدئ</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٢ درساً</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">أساسيات علمية</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 4.9</span>
                      <span class="text-slate-400 font-normal">(520)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    أساسيات التخاطب والتقييم اللغوي
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    المدخل الأكاديمي الشامل لفهم مراحل التطور اللغوي الطبيعي واكتشاف مؤشرات التأخر المبكر للدارسين.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      س
                    </div>
                    <span>د. سامي البدر • ٥ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

            <!-- Course Card 4: Stuttering & Fluency Management -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="إدارة التأتأة والطلاقة الكلامية">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#0B2A8F] relative overflow-hidden">
                  <img src="/assets/images/photo1.png" alt="إدارة التأتأة" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-emerald-600 text-white text-[11px] font-bold shadow-xs">متوسط</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٤ درساً</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">تخاطب ونطق</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 4.8</span>
                      <span class="text-slate-400 font-normal">(195)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    إدارة التأتأة وتعزيز الطلاقة الكلامية
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    استراتيجيات تقليل التوتر والتحكم بمعدل وسرعة الكلام للأطفال واليافعين بطرق علمية مؤكدة.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      ع
                    </div>
                    <span>د. عمر الفاروق • ٦ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

            <!-- Course Card 5: Autism Communication -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="التدخل المبكر لأطفال طيف التوحد">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#152D4D] relative overflow-hidden">
                  <img src="/assets/images/photo2.png" alt="طيف التوحد" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-purple-600 text-white text-[11px] font-bold shadow-xs">تخصصي</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٨ درساً</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">تخاطب ونطق</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 5.0</span>
                      <span class="text-slate-400 font-normal">(340)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    التدخل المبكر لتطوير التواصل وتأهيل التوحد
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    تطبيق نظام بيكس (PECS) والتواصل البديل والمعزز لتدريب طلاب التخاطب على الخطط الفردية.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      ن
                    </div>
                    <span>أ. ناصر العتيبي • ٩ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

            <!-- Course Card 6: Clinical Speech Diagnostics -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="التدريب السريري على تشخيص اضطرابات النطق">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#012383] relative overflow-hidden">
                  <img src="/assets/images/photo3.png" alt="التدريب السريري على تشخيص اضطرابات النطق" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-[#10B77F] text-white text-[11px] font-bold shadow-xs">تطبيق عملي</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٠ دروس</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">ممارسة سريرية</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 4.9</span>
                      <span class="text-slate-400 font-normal">(280)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    التدريب السريري على تشخيص اضطرابات النطق
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    تأهيل الطلاب والدارسين على إجراء المقاييس التشخيصية وتحديد الخطة العلاجية الفردية لاضطرابات النطق.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      م
                    </div>
                    <span>د. مروان الشريف • ٤ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

          </div>

          <!-- Empty State (Hidden by default) -->
          <div id="catalog-empty" class="hidden flex-col items-center justify-center py-12 text-center bg-white border border-[#DEE8F7] rounded-[24px]">
            <p class="text-sm font-bold text-[#012383]" data-i18n="catalog.noCoursesFound">لا توجد دورات تطابق معايير البحث الحالية.</p>
          </div>

        </section>

      </main>

      <!-- ============================================== -->
      <!-- GLOBAL FOOTER (Deep Navy Premium Edition)      -->
      <!-- ============================================== -->
      <footer class="w-full bg-[#031338] text-white relative overflow-hidden border-t border-[#122A63]">
        
        <!-- Ambient Decorative Glows -->
        <div class="absolute -top-32 start-10 size-[380px] rounded-full bg-blue-600/10 blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute -bottom-24 end-12 size-[320px] rounded-full bg-indigo-500/10 blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 pt-14 lg:pt-16 pb-8 flex flex-col gap-12">
          
          <!-- Top Row: Main 4 Columns -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10">
            
            <!-- Column 1: Brand & Identity (Lg: 4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-5">
              
              <!-- Brand Logo with soft badge -->
              <a href="{{ route('home') }}#home" class="flex gap-3 items-center group w-fit">
                <div class="size-11 rounded-2xl bg-white p-1.5 flex items-center justify-center shadow-md shadow-blue-950/50 group-hover:scale-105 transition-transform">
                  <img src="/assets/images/logo.png" alt="منصة مركز الأمين" class="size-full object-contain" />
                </div>
                <div class="flex flex-col text-start">
                  <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-2xl text-white tracking-tight leading-tight">
                    منصة مركز الأمين
                  </span>
                  <span class="text-[11px] text-blue-200/80 font-bold font-['Cairo']">
                    لتأهيل التخاطب والنطق
                  </span>
                </div>
              </a>

              <!-- Mission Statement -->
              <p class="text-xs sm:text-[13px] leading-relaxed text-blue-100/70 font-medium max-w-sm">
                المنصة العربية المتخصصة في تدريب وتأهيل مهارات النطق والتخاطب، نجمع بين الأسس العلمية المعتمدة والتطبيقات السريرية العملية بإشراف نخبة من كبار أخصائيي التخاطب.
              </p>


              <!-- Social Media Links -->
              <div class="flex items-center gap-2.5 pt-2">
                <!-- WhatsApp -->
                <a
                  href="https://wa.me/966501234567"
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label="واتساب"
                  class="size-9 rounded-xl bg-blue-950/80 border border-blue-800/50 hover:bg-[#10B77F] hover:border-[#10B77F] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                >
                  <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.634.07-.999-.074-.755-.298-1.52-.752-2.227-1.458-.894-.893-1.425-1.923-1.688-2.338-.263-.414-.029-.636.143-.808.156-.156.342-.405.513-.607.172-.202.229-.344.343-.573.114-.229.057-.428-.029-.6-.086-.172-.771-1.859-1.057-2.545-.278-.669-.561-.579-.771-.59-.2-.01-.429-.012-.657-.012-.229 0-.6.086-.914.428-.314.343-1.2 1.171-1.2 2.858 0 1.687 1.229 3.315 1.4 3.543.172.229 2.417 3.691 5.857 5.176 2.851 1.231 3.432.986 4.05.928.618-.057 1.99-.813 2.275-1.599.285-.785.285-1.457.2-1.599-.085-.143-.314-.229-.657-.401z"/>
                  </svg>
                </a>
                <!-- X / Twitter -->
                <a
                  href="#"
                  aria-label="منصة إكس"
                  class="size-9 rounded-xl bg-blue-950/80 border border-blue-800/50 hover:bg-[#0B2A8F] hover:border-blue-500 text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                >
                  <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                  </svg>
                </a>
                <!-- LinkedIn -->
                <a
                  href="#"
                  aria-label="لينكد إن"
                  class="size-9 rounded-xl bg-blue-950/80 border border-blue-800/50 hover:bg-[#0077B5] hover:border-[#0077B5] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                >
                  <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                  </svg>
                </a>
                <!-- YouTube -->
                <a
                  href="#"
                  aria-label="يوتيوب"
                  class="size-9 rounded-xl bg-blue-950/80 border border-blue-800/50 hover:bg-[#FF0000] hover:border-[#FF0000] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                >
                  <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                  </svg>
                </a>
              </div>

            </div>

            <!-- Column 2: كورس التخاطب (Lg: 4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-4">
              <div class="flex items-center gap-2">
                <span class="w-1.5 h-4 rounded-full bg-[#10B77F]"></span>
                <h4 class="font-['Cairo'] font-bold text-sm text-white tracking-wide">
                  كورس التخاطب
                </h4>
              </div>
              <ul class="flex flex-col gap-2.5 text-xs font-semibold text-blue-200/75">
                <li>
                  <a href="{{ route('courses.index') }}" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                    <span class="text-blue-400">›</span>
                    محاور كورس التخاطب الشامل
                  </a>
                </li>
                <li>
                  <a href="{{ route('courses.index') }}" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                    <span class="text-blue-400">›</span>
                    استراتيجيات تنمية اللغة والتواصل
                  </a>
                </li>
                <li>
                  <a href="{{ route('courses.index') }}" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                    <span class="text-blue-400">›</span>
                    تطبيقات مخارج الحروف والأصوات
                  </a>
                </li>
                <li>
                  <a href="{{ route('courses.index') }}" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                    <span class="text-blue-400">›</span>
                    التدريب السريري لاضطرابات النطق
                  </a>
                </li>
                <li>
                  <a href="../index.html#about" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                    <span class="text-blue-400">›</span>
                    منهجية التعلّم والممارسة السريرية
                  </a>
                </li>
              </ul>
            </div>

            <!-- Column 3: قنوات الدعم المباشرة (Lg: 4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-4">
              <div class="flex items-center gap-2">
                <span class="w-1.5 h-4 rounded-full bg-amber-400"></span>
                <h4 class="font-['Cairo'] font-bold text-sm text-white tracking-wide">
                  الدعم الفني والأكاديمي
                </h4>
              </div>

              <!-- Status Badge -->
              <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-950/80 border border-blue-800/50 text-[11px] font-bold text-emerald-300 w-fit">
                <span class="size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>فريق الدعم متاح وجاهز لمساعدتك</span>
              </div>

              <!-- Channels -->
              <div class="flex flex-col gap-2.5 text-xs text-blue-100/70">
                <div class="flex items-center gap-2.5">
                  <div class="size-7 rounded-lg bg-blue-900/40 border border-blue-800/40 flex items-center justify-center text-blue-300 shrink-0">
                    ✉️
                  </div>
                  <div class="flex flex-col">
                    <span class="text-[10px] text-blue-300 font-bold">البريد الأكاديمي</span>
                    <a href="mailto:support@alameen.edu.sa" class="font-medium hover:text-white transition-colors">support@alameen.edu.sa</a>
                  </div>
                </div>

                <div class="flex items-center gap-2.5">
                  <div class="size-7 rounded-lg bg-blue-900/40 border border-blue-800/40 flex items-center justify-center text-blue-300 shrink-0">
                    📞
                  </div>
                  <div class="flex flex-col">
                    <span class="text-[10px] text-blue-300 font-bold">واتساب المباشر</span>
                    <a href="https://wa.me/966501234567" target="_blank" rel="noopener noreferrer" class="font-medium text-white dir-ltr text-start hover:text-emerald-400 transition-colors">+966 50 123 4567</a>
                  </div>
                </div>

                <div class="flex items-center gap-2.5">
                  <div class="size-7 rounded-lg bg-blue-900/40 border border-blue-800/40 flex items-center justify-center text-blue-300 shrink-0">
                    🕒
                  </div>
                  <div class="flex flex-col">
                    <span class="text-[10px] text-blue-300 font-bold">أوقات العمل</span>
                    <span class="font-medium text-blue-100/80">السبت - الخميس (٨:٠٠ ص - ١٠:٠٠ م)</span>
                  </div>
                </div>
              </div>

            </div>

          </div>

          <!-- Bottom Row: Sub-footer Divider & Legal Links -->
          <div class="pt-8 border-t border-blue-900/40 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-blue-200/60 font-medium">
            
            <div class="flex items-center gap-2 text-center md:text-start">
              <span>© ٢٠٢٦ منصة مركز الأمين لتأهيل التخاطب والنطق. جميع الحقوق محفوظة.</span>
            </div>

            <!-- Security & Legal -->
            <div class="flex flex-wrap items-center justify-center gap-4 text-[11px] sm:text-xs">
              <a href="#" class="hover:text-white transition-colors">سياسة الخصوصية</a>
              <span>•</span>
              <a href="#" class="hover:text-white transition-colors">الشروط والأحكام</a>
              <span>•</span>
              <a href="#" class="hover:text-white transition-colors">سياسة الاعتماد الأكاديمي</a>
            </div>

          </div>

        </div>

      </footer>

    </div>

    <!-- Scripts -->
    <script type="module" src="/src/main.js"></script>
    <script>
      // ==========================================
      // Catalog Interactive Filtering Script
      // ==========================================
      document.addEventListener('DOMContentLoaded', () => {
        const catButtons = document.querySelectorAll('.catalog-filter');
        const courseCards = document.querySelectorAll('.course-card');
        const searchInput = document.getElementById('course-search-input');
        const emptyState = document.getElementById('catalog-empty');

        let activeCat = 'all';
        let searchQuery = '';

        function filterCatalog() {
          let visibleCount = 0;
          courseCards.forEach(card => {
            const matchesCat = activeCat === 'all' || card.getAttribute('data-category') === activeCat;
            const title = card.getAttribute('data-title')?.toLowerCase() || '';
            const matchesSearch = !searchQuery || title.includes(searchQuery);

            if (matchesCat && matchesSearch) {
              card.style.display = 'flex';
              visibleCount++;
            } else {
              card.style.display = 'none';
            }
          });

          if (visibleCount === 0) {
            emptyState.classList.remove('hidden');
            emptyState.classList.add('flex');
          } else {
            emptyState.classList.add('hidden');
            emptyState.classList.remove('flex');
          }
        }

        catButtons.forEach(btn => {
          btn.addEventListener('click', () => {
            catButtons.forEach(b => {
              b.classList.remove('active', 'bg-[#012383]', 'text-white');
              b.classList.add('bg-[#EAF1FB]', 'text-[#0B2A8F]');
            });
            btn.classList.add('active', 'bg-[#012383]', 'text-white');
            btn.classList.remove('bg-[#EAF1FB]', 'text-[#0B2A8F]');

            activeCat = btn.getAttribute('data-cat') || 'all';
            filterCatalog();
          });
        });

        searchInput?.addEventListener('input', (e) => {
          searchQuery = e.target.value.trim().toLowerCase();
          filterCatalog();
        });
      });
    </script>
    <script type="module" src="/src/js/i18n.js"></script>
      <script type="module" src="/src/js/animations.js"></script>
  </body>
</html>


