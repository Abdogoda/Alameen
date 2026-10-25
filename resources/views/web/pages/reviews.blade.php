<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة مركز الأمين | آراء وتقييمات الطلاب</title>
    <!-- Google Fonts Preconnect and Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@500;600;700;800&family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
    <!-- Stylesheet with Tailwind and Design Tokens -->
    <link rel="stylesheet" href="/src/css/styles.css" />
  </head>
  <body class="bg-[#FAFCFF] min-h-screen text-[#475569] font-['Cairo'] antialiased flex flex-col justify-between selection:bg-[#EAF1FB] selection:text-[#0B2A8F]">
    
    <!-- Toast Notification Container -->
    <div id="toast" class="fixed bottom-6 start-6 z-50 transform translate-y-20 opacity-0 pointer-events-none transition-all duration-300 bg-[#012383] text-white px-5 py-3.5 rounded-xl shadow-xl flex items-center gap-3">
      <svg class="size-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      <span id="toast-message" class="text-sm font-semibold"></span>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-[1440px] mx-auto min-h-screen flex flex-col justify-between relative bg-[#FAFCFF]">
      
      <!-- ============================================== -->
      <!-- HEADER (WorkDo Clean Header Architecture)       -->
      <!-- ============================================== -->
      <header class="w-full bg-[#FAFCFF] border-b border-[#D7E3F5] flex items-center justify-between px-4 sm:px-6 lg:px-[48px] py-3 sm:py-4 shrink-0 z-20">
        
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

          <!-- الدورات -->
          <a href="../#courses" class="px-3.5 py-2 text-[#012383] hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors" data-i18n="landing.navCourses">
            الدورات
          </a>

          <!-- من نحن -->
          <a href="{{ route('home') }}#about" class="px-3.5 py-2 text-[#012383] hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors" data-i18n="landing.navAbout">
            من نحن
          </a>

          <!-- Active: آراء الطلاب -->
          <a href="{{ route('reviews.index') }}" class="px-4 py-2 bg-[#EAF1FB] text-[#0B2A8F] text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors" data-i18n="landing.navTestimonials">
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
        <a href="../#courses" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50" data-i18n="landing.navCourses">الدورات</a>
        <a href="{{ route('home') }}#about" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50" data-i18n="landing.navAbout">من نحن</a>
        <a href="{{ route('reviews.index') }}" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm bg-[#EAF1FB] text-[#0B2A8F]" data-i18n="landing.navTestimonials">آراء الطلاب</a>
        <a href="{{ route('home') }}#contact" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50" data-i18n="landing.navContact">تواصل معنا</a>
      </div>

      <!-- ============================================== -->
      <!-- MAIN PAGE CONTENT                              -->
      <!-- ============================================== -->
      <main class="flex-1 w-full px-6 lg:px-[48px] py-8 lg:py-12 flex flex-col gap-10">
        
        <!-- 1. HERO SECTION & TRUST METRICS (WorkDo SaaS Hero Pattern) -->
        <section class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto">
          
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#10B77F]/30 text-[#10B77F] text-xs font-bold">
            <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span data-i18n="reviews.eyebrow">آراء وتقييمات الطلاب</span>
          </div>

          <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight" data-i18n="reviews.heroTitle">
            تجارب واقعية وقصص نجاح في رحلة التخاطب
          </h1>

          <p class="text-base sm:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed" data-i18n="reviews.heroDesc">
            اكتشف كيف ساعدت دورات منصة مركز الأمين آلاف الطلاب والدارسين في تطوير مهارات النطق والتخاطب السريري.
          </p>

          <!-- Trust Badges 4-Item Grid -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full mt-4">
            
            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <div class="flex items-center gap-1 text-[#F59E0B] text-xl font-extrabold mb-1">
                <span>★</span>
                <span data-i18n="reviews.statRating">٤.٩ من ٥</span>
              </div>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statRatingSub">متوسط التقييم العام</span>
            </div>

            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <span class="text-xl font-extrabold text-[#0B2A8F] mb-1" data-i18n="reviews.statReviews">+١,٤٨٠ تقييم</span>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statReviewsSub">تقييمات موثقة من الطلاب</span>
            </div>

            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <span class="text-xl font-extrabold text-[#10B77F] mb-1" data-i18n="reviews.statSatisfaction">٩٨٪</span>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statSatisfactionSub">نسبة الرضا عن المحتوى</span>
            </div>

            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <span class="text-xl font-extrabold text-[#012383] mb-1" data-i18n="reviews.statVerified">١٠٠٪</span>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statVerifiedSub">طلاب أتموا الدورات</span>
            </div>

          </div>

        </section>

        <!-- 2. RATING BREAKDOWN & CTA CARD (WorkDo 2-Column Split Pattern) -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- Breakdown Bars Card (2 cols on lg) -->
          <div class="lg:col-span-2 bg-white border border-[#DEE8F7] rounded-[20px] p-6 lg:p-8 shadow-xs flex flex-col md:flex-row gap-8 items-center">
            
            <!-- Left: Giant Score -->
            <div class="flex flex-col items-center justify-center text-center shrink-0 md:pe-8 md:border-e md:border-[#DEE8F7]">
              <span class="font-['Cairo'] font-extrabold text-5xl text-[#012383]">4.9</span>
              <div class="flex items-center gap-1 text-[#F59E0B] text-lg my-1.5">
                ★★★★★
              </div>
              <span class="text-xs font-bold text-[#475569]" data-i18n="reviews.statReviewsSub">بناءً على ١,٤٨٠ تقييماً</span>
            </div>

            <!-- Right: Breakdown Bars -->
            <div class="flex-1 w-full flex flex-col gap-2.5">
              
              <!-- 5 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star5">٥ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-[#10B77F] rounded-full" style="width: 88%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">88%</span>
              </div>

              <!-- 4 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star4">٤ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-[#0B2A8F] rounded-full" style="width: 9%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">9%</span>
              </div>

              <!-- 3 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star3">٣ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-amber-400 rounded-full" style="width: 2%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">2%</span>
              </div>

              <!-- 2 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star2">نجمتان</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-slate-400 rounded-full" style="width: 1%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">1%</span>
              </div>

              <!-- 1 Star -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star1">نجمة واحدة</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-slate-300 rounded-full" style="width: 0%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">0%</span>
              </div>

            </div>

          </div>

          <!-- Right / Action Card -->
          <div class="bg-gradient-to-br from-[#012383] to-[#0B2A8F] rounded-[20px] p-6 lg:p-8 text-white flex flex-col justify-between shadow-card relative overflow-hidden">
            <div class="absolute -end-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>
            
            <div class="flex flex-col gap-2 relative z-10">
              <span class="text-xs font-bold text-blue-200 uppercase tracking-wider" data-i18n="reviews.eyebrow">صوت المتعلم</span>
              <h3 class="font-['Cairo'] font-bold text-2xl" data-i18n="reviews.addReviewCardTitle">شاركنا تجربتك التعليمية</h3>
              <p class="text-xs text-blue-100 font-medium leading-relaxed" data-i18n="reviews.addReviewCardDesc">
                رأيك يساهم في إلهام غيرك وتطوير برامجنا التعليمية باستمرار.
              </p>
            </div>

            <button
              type="button"
              id="open-review-modal-btn"
              class="mt-6 w-full py-3 px-4 bg-white text-[#012383] font-bold text-sm rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 shadow-xs cursor-pointer"
            >
              <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              <span data-i18n="reviews.addReviewBtn">أضف تقييمك الآن</span>
            </button>
          </div>

        </section>

        <!-- 3. INTERACTIVE FILTER CHIPS & SORT BAR (WorkDo SaaS Filters) -->
        <section class="flex flex-col md:flex-row gap-4 items-center justify-between bg-white border border-[#DEE8F7] rounded-[16px] p-3.5 shadow-xs">
          
          <!-- Category Pills -->
          <div class="flex flex-wrap items-center gap-2 w-full md:w-auto" id="filter-pills-group">
            <button
              type="button"
              data-filter="all"
              class="filter-pill active px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer"
              data-i18n="reviews.filterAll"
            >
              الكل
            </button>
            <button
              type="button"
              data-filter="5star"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filter5Star"
            >
              ٥ نجوم
            </button>
            <button
              type="button"
              data-filter="cert"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterWithCert"
            >
              مع شهادة إتمام
            </button>
            <button
              type="button"
              data-filter="speech"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterSpeechDelay"
            >
              تأخر الكلام
            </button>
            <button
              type="button"
              data-filter="articulation"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterArticulation"
            >
              مخارج الحروف
            </button>
            <button
              type="button"
              data-filter="parent"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterParentTraining"
            >
              تدريب الوالدين
            </button>
          </div>

          <!-- Sort Select -->
          <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
            <span class="text-xs font-semibold text-[#475569] whitespace-nowrap" data-i18n="reviews.sortLabel">الترتيب حسب:</span>
            <select
              id="sort-select"
              class="bg-[#FAFCFF] border border-[#DEE8F7] text-xs font-bold text-[#012383] rounded-lg px-2.5 py-1.5 outline-none cursor-pointer focus:border-[#0B2A8F]"
            >
              <option value="recent" data-i18n="reviews.sortRecent">الأحدث</option>
              <option value="highest" data-i18n="reviews.sortHighest">الأعلى تقييماً</option>
              <option value="helpful" data-i18n="reviews.sortHelpful">الأكثر فائدة</option>
            </select>
          </div>

        </section>

        <!-- 4. SPOTLIGHT CASE STUDY CARD (WorkDo Featured Card) -->
        <section class="bg-gradient-to-r from-white via-white to-[#FAFCFF] border-2 border-[#10B77F]/30 rounded-[20px] p-6 lg:p-8 shadow-card flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
          <div class="size-24 rounded-2xl bg-[#EEF7F3] border border-[#10B77F]/20 flex items-center justify-center shrink-0 overflow-hidden relative shadow-xs">
            <img src="/assets/images/tip-1.png" alt="د. ريم القحطاني" class="size-full object-cover" />
          </div>

          <div class="flex-1 flex flex-col gap-3">
            <div class="flex flex-wrap items-center gap-2">
              <span class="px-3 py-1 rounded-full bg-[#EEF7F3] text-[#10B77F] text-xs font-bold" data-i18n="reviews.spotlightEyebrow">
                قصة نجاح مميزة
              </span>
              <span class="px-2.5 py-0.5 rounded-full bg-[#EAF1FB] text-[#0B2A8F] text-[11px] font-bold" data-i18n="reviews.spotlightCourse">
                دورة استراتيجيات تنمية اللغة
              </span>
              <div class="flex text-[#F59E0B] text-sm">★★★★★</div>
            </div>

            <h3 class="font-['Cairo'] font-bold text-lg md:text-xl text-[#012383]" data-i18n="reviews.spotlightTitle">
              «تطوّر مستواي في التشخيص السريري خلال ٦ أسابيع من التمارين العملية المنتظمة»
            </h3>

            <p class="text-sm text-[#475569] font-medium leading-relaxed" data-i18n="reviews.spotlightQuote">
              كنت أبحث عن برنامج علمي وتطبيقي موثوق لتنمية مهاراتي في قيادة جلسات التخاطب. التمارين التفاعلية والتقييم الدقيق في منصة مركز الأمين أحدثا فارقاً ملموساً في كفاءتي السريرية وثقتي المهنية.
            </p>

            <div class="flex items-center gap-3 pt-2 text-xs font-bold text-[#012383]">
              <span data-i18n="reviews.spotlightAuthor">د. عمر القحطاني</span>
              <span class="text-slate-300">•</span>
              <span class="text-[#475569]" data-i18n="reviews.spotlightRole">طالب وباحث في علوم التخاطب</span>
              <span class="text-slate-300">•</span>
              <span class="text-emerald-600 flex items-center gap-1 font-semibold">
                <svg class="size-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span data-i18n="reviews.verifiedBadge">طالب موثق</span>
              </span>
            </div>
          </div>
        </section>

        <!-- 5. REVIEWS MASONRY / GRID (6 Realistic Reviews) -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="reviews-grid">
          
          <!-- Card 1 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="speech" data-rating="5" data-cert="true" data-helpful="24">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    س
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">سامي المنصور</h4>
                    <span class="text-[11px] text-[#475569]">منذ أسبوعين</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">تطبيقات مخارج الحروف</span>
                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold">شهادة معتمدة</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«تمارين واضحة ونتائج مباشرة في تصحيح مخارج الحروف سريرياً»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                الكورس ممتاز من حيث الترتيب المنهجي. الفيديوهات مركزة، وملاحظات المحاضر على تحليلي الصوتي ساعدتني كثيراً في فهم مخارج الحروف سريرياً.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">أتم ٩٥٪ من متطلبات الكورس</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">24</span>
              </button>
            </div>
          </article>

          <!-- Card 2 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="articulation" data-rating="5" data-cert="true" data-helpful="18">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    خ
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">خالد العمري</h4>
                    <span class="text-[11px] text-[#475569]">منذ ٣ أسابيع</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">أساسيات التخاطب</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«أفضل استثمار في مسيرتي كطالب وباحث في علوم التخاطب»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                استفدت كثيراً من النماذج التطبيقية واستمارة الملاحظة السريرية المرفقة بتوجيه المحاضرين. أنصح كل دارس في علوم التخاطب بهذه المنصة.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">أتم الكورس بدرجة امتياز</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">18</span>
              </button>
            </div>
          </article>

          <!-- Card 3 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="parent" data-rating="5" data-cert="false" data-helpful="31">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ف
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">فهد الدوسري</h4>
                    <span class="text-[11px] text-[#475569]">منذ شهر</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">تدريب التخاطب السريري</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«منهجية علمية واضحة في تصحيح اللدغات الصوتية»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                الكورس قدّم تدريباً دقيقاً على تقنيات المحاكاة وتعديل مخارج الأصوات. التقييم المباشر للمهام من المحاضر عزز مهاراتي التطبيقية بشكل ملحوظ.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-blue-600 font-bold">طالب معتمد - نتائج ممتازة</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">31</span>
              </button>
            </div>
          </article>

          <!-- Card 4 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="stuttering" data-rating="4" data-cert="true" data-helpful="14">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ع
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">عبدالله الحربي</h4>
                    <span class="text-[11px] text-[#475569]">منذ شهر</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★☆</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">إدارة التأتأة والطلاقة</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«تمارين التنفس والتحكم بالإيقاع أحدثت فارقاً»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                المحتوى عميق والأسلوب مبسط جداً. تقييم المحاضر الكتابي للمهام السريرية كان ممتازاً ومفصلاً وساعدني على فهم آليات التحكم بالطلاقة.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">شهادة معتمدة</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">14</span>
              </button>
            </div>
          </article>

          <!-- Card 5 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="speech" data-rating="5" data-cert="true" data-helpful="20">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ن
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">نايف القاسم</h4>
                    <span class="text-[11px] text-[#475569]">منذ ٥ أسابيع</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">تطبيقات مخارج الحروف</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«تطبيقات علمية دقيقة في الفحص السريري للنطق»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                طالب دراسات عليا وكنت أحتاج أدوات علمية للتعامل مع اضطرابات مخارج الحروف سريرياً. الأنشطة والتدريبات التفاعلية مع المحاضر ممتعة وعميقة للغاية.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">شهادة إتمام معتمدة</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">20</span>
              </button>
            </div>
          </article>

          <!-- Card 6 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="speech" data-rating="5" data-cert="true" data-helpful="28">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ي
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">يوسف النجار</h4>
                    <span class="text-[11px] text-[#475569]">منذ ٦ أسابيع</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">استراتيجيات تنمية اللغة</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«تدرج منهجي متقن من التقييم حتى التدخل العلاجي»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                الشكر موصول لمنصة مركز الأمين والمحاضرين على الدعم المتواصل. الملاحظات الدورية بعد كل مهمة جعلتني أمتلك ثقة تامة في تطبيق خطط التخاطب السريرية.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">أتم الكورس بنجاح</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">28</span>
              </button>
            </div>
          </article>

        </section>

      </main>

      <!-- ============================================== -->
      <!-- WRITE A REVIEW MODAL (WorkDo Feedback Modal)   -->
      <!-- ============================================== -->
      <div id="review-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-[24px] max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-[#DEE8F7] relative flex flex-col gap-5 max-h-[90vh] overflow-y-auto">
          
          <!-- Modal Header -->
          <div class="flex items-start justify-between">
            <div>
              <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl text-[#012383]" data-i18n="reviews.modalTitle">إضافة تقييم جديد</h3>
              <p class="text-xs text-[#475569] font-medium" data-i18n="reviews.modalSubtitle">شارك تجربتك وانطباعك عن الدورة التدريبية</p>
            </div>
            <button
              type="button"
              id="close-review-modal-btn"
              class="size-8 rounded-full bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#012383] flex items-center justify-center font-bold text-lg transition-colors cursor-pointer"
            >
              ✕
            </button>
          </div>

          <!-- Interactive Star Picker -->
          <div class="flex flex-col gap-2">
            <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalRatingLabel">تقييمك العام للدورة</label>
            <div class="flex items-center gap-2 text-3xl cursor-pointer" id="modal-star-group">
              <span class="star-item text-amber-400" data-val="1">★</span>
              <span class="star-item text-amber-400" data-val="2">★</span>
              <span class="star-item text-amber-400" data-val="3">★</span>
              <span class="star-item text-amber-400" data-val="4">★</span>
              <span class="star-item text-amber-400" data-val="5">★</span>
            </div>
            <input type="hidden" id="modal-rating-value" value="5" />
          </div>

          <!-- Course Selector -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalCourseLabel">الدورة التي حضرتها</label>
            <select id="modal-course-select" class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-3.5 py-2.5 text-xs font-medium text-[#012383] outline-none focus:border-[#0B2A8F]">
              <option value="lang-dev">دورة استراتيجيات تنمية اللغة</option>
              <option value="articulation">دورة تطبيقات مخارج الحروف</option>
              <option value="basics">دورة أساسيات التخاطب</option>
              <option value="stuttering">دورة إدارة التأتأة والطلاقة</option>
            </select>
          </div>

          <!-- Review Headline -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalTitleLabel">عنوان التقييم</label>
            <input
              type="text"
              id="modal-review-title"
              placeholder="مثال: تجربة رائعة وتمارين عملية فعالة"
              data-i18n-placeholder="reviews.modalTitlePlaceholder"
              class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-3.5 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F]"
            />
          </div>

          <!-- Feedback Details -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalFeedbackLabel">تفاصيل تجربتك والنتائج التي حققتها</label>
            <textarea
              id="modal-review-text"
              rows="3"
              placeholder="اكتب رأيك بالتفصيل هنا وكيف أثرت الدورة في تعلّمك..."
              data-i18n-placeholder="reviews.modalFeedbackPlaceholder"
              class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl p-3 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F] resize-none"
            ></textarea>
          </div>

          <!-- File Upload Dropzone -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalUploadLabel">إرفاق إثبات أو شهادة (اختياري)</label>
            <div class="border-2 border-dashed border-[#DEE8F7] hover:border-[#0B2A8F] rounded-xl p-4 text-center cursor-pointer transition-colors bg-[#FAFCFF]">
              <p class="text-[11px] text-[#475569] font-medium" data-i18n="reviews.modalUploadHint">
                اسحب الملف هنا أو انقر للتصفح (PNG, JPG, PDF حتى 5MB)
              </p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end gap-3 pt-2">
            <button
              type="button"
              id="cancel-review-btn"
              class="px-4 py-2 rounded-xl text-xs font-bold text-[#475569] hover:bg-slate-100 transition-colors cursor-pointer"
              data-i18n="reviews.modalCancelBtn"
            >
              إلغاء
            </button>
            <button
              type="button"
              id="submit-review-btn"
              class="px-6 py-2.5 rounded-xl text-xs font-bold bg-[#012383] text-white hover:bg-[#0B2A8F] transition-all shadow-xs cursor-pointer"
              data-i18n="reviews.modalSubmitBtn"
            >
              نشر التقييم
            </button>
          </div>

        </div>
      </div>

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
      // Reviews Page Interactive Script
      // ==========================================
      document.addEventListener('DOMContentLoaded', () => {
        // 1. Filter Pills
        const filterPills = document.querySelectorAll('.filter-pill');
        const cards = document.querySelectorAll('.review-card');

        filterPills.forEach(pill => {
          pill.addEventListener('click', () => {
            filterPills.forEach(p => {
              p.classList.remove('active', 'bg-[#012383]', 'text-white');
              p.classList.add('bg-[#EAF1FB]', 'text-[#0B2A8F]');
            });
            pill.classList.add('active', 'bg-[#012383]', 'text-white');
            pill.classList.remove('bg-[#EAF1FB]', 'text-[#0B2A8F]');

            const filterVal = pill.getAttribute('data-filter');
            cards.forEach(card => {
              if (filterVal === 'all') {
                card.style.display = 'flex';
              } else if (filterVal === '5star') {
                card.style.display = card.getAttribute('data-rating') === '5' ? 'flex' : 'none';
              } else if (filterVal === 'cert') {
                card.style.display = card.getAttribute('data-cert') === 'true' ? 'flex' : 'none';
              } else {
                card.style.display = card.getAttribute('data-category') === filterVal ? 'flex' : 'none';
              }
            });
          });
        });

        // 2. Helpful vote counters
        const helpfulBtns = document.querySelectorAll('.helpful-btn');
        helpfulBtns.forEach(btn => {
          let clicked = false;
          btn.addEventListener('click', () => {
            if (!clicked) {
              const countEl = btn.querySelector('.helpful-count');
              let count = parseInt(countEl.textContent, 10) || 0;
              countEl.textContent = count + 1;
              btn.classList.add('text-[#0B2A8F]', 'font-bold');
              clicked = true;
            }
          });
        });

        // 3. Modal open / close
        const modal = document.getElementById('review-modal');
        const openBtn = document.getElementById('open-review-modal-btn');
        const closeBtn = document.getElementById('close-review-modal-btn');
        const cancelBtn = document.getElementById('cancel-review-btn');
        const submitBtn = document.getElementById('submit-review-btn');
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        const showModal = () => {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        };

        const hideModal = () => {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
        };

        openBtn?.addEventListener('click', showModal);
        closeBtn?.addEventListener('click', hideModal);
        cancelBtn?.addEventListener('click', hideModal);

        // Modal Stars
        const starItems = document.querySelectorAll('.star-item');
        const ratingVal = document.getElementById('modal-rating-value');
        starItems.forEach(star => {
          star.addEventListener('click', () => {
            const val = parseInt(star.getAttribute('data-val'), 10);
            ratingVal.value = val;
            starItems.forEach(s => {
              const sVal = parseInt(s.getAttribute('data-val'), 10);
              if (sVal <= val) {
                s.classList.add('text-amber-400');
                s.classList.remove('text-slate-300');
              } else {
                s.classList.remove('text-amber-400');
                s.classList.add('text-slate-300');
              }
            });
          });
        });

        // Submit Review
        submitBtn?.addEventListener('click', () => {
          const title = document.getElementById('modal-review-title').value.trim();
          const text = document.getElementById('modal-review-text').value.trim();

          if (!title || !text) {
            alert(document.documentElement.lang === 'en' ? 'Please fill in the review title and details.' : 'يرجى كتابة عنوان التقييم والتفاصيل.');
            return;
          }

          hideModal();
          // Reset form
          document.getElementById('modal-review-title').value = '';
          document.getElementById('modal-review-text').value = '';

          // Show Toast
          const isEn = document.documentElement.lang === 'en';
          toastMessage.textContent = isEn ? 'Your review has been submitted successfully!' : 'تم نشر تقييمك بنجاح! شكراً لمشاركتك.';
          toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
          setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
          }, 3500);
        });
      });
    </script>
    <script type="module" src="/src/js/i18n.js"></script>
      <script type="module" src="/src/js/animations.js"></script>
  </body>
</html>


