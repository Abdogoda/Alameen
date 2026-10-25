<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة مركز الأمين | تواصل معنا والدعم الفني</title>
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

          <!-- آراء الطلاب -->
          <a href="{{ route('home') }}#reviews" class="px-3.5 py-2 text-[#012383] hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors" data-i18n="landing.navTestimonials">
            آراء الطلاب
          </a>

          <!-- Active: تواصل معنا -->
          <a href="{{ route('contact') }}" class="px-4 py-2 bg-[#EAF1FB] text-[#0B2A8F] text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors" data-i18n="landing.navContact">
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
        <a href="{{ route('home') }}#reviews" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50" data-i18n="landing.navTestimonials">آراء الطلاب</a>
        <a href="{{ route('contact') }}" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm bg-[#EAF1FB] text-[#0B2A8F]" data-i18n="landing.navContact">تواصل معنا</a>
      </div>

      <!-- ============================================== -->
      <!-- MAIN PAGE CONTENT                              -->
      <!-- ============================================== -->
      <main class="flex-1 w-full px-6 lg:px-[48px] py-8 lg:py-12 flex flex-col gap-10">
        
        <!-- 1. HERO SECTION & AVAILABILITY STATUS (WorkDo Hero Style) -->
        <section class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto">
          
          <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#10B77F]/30 text-[#10B77F] text-xs font-bold shadow-xs">
            <span class="relative flex size-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full size-2.5 bg-emerald-500"></span>
            </span>
            <span data-i18n="contact.statusOnline">فريق الدعم متاح الآن للرد</span>
            <span class="text-emerald-300">•</span>
            <span class="text-[#475569] font-medium" data-i18n="contact.responseTime">متوسط زمن الاستجابة: أقل من ساعتين</span>
          </div>

          <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight" data-i18n="contact.heroTitle">
            نحن هنا لمساعدتك في كل خطوة
          </h1>

          <p class="text-base sm:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed" data-i18n="contact.heroDesc">
            يسعد فريق الدعم الفني والإرشاد الأكاديمي بمنصة مركز الأمين بالإجابة على استفساراتكم ومساندتكم طوال رحلتكم التعليمية.
          </p>

        </section>

        <!-- 2. TWO DIRECT SUPPORT CHANNELS (Email & WhatsApp Only) -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto w-full">
          
          <!-- Channel 1: Email -->
          <div class="bg-white border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[22px] p-6 sm:p-8 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div class="flex flex-col gap-3">
              <div class="size-14 rounded-2xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center font-bold text-2xl">
                ✉️
              </div>
              <h3 class="font-bold text-xl text-[#012383]" data-i18n="contact.channelTechTitle">البريد الإلكتروني الرسمي</h3>
              <p class="text-xs sm:text-sm text-[#475569] leading-relaxed">
                تواصل مباشر عبر البريد الإلكتروني للاستفسارات الأكاديمية عن الكورس ومتابعة حسابك وشهادتك.
              </p>
            </div>
            <div class="mt-6 pt-4 border-t border-[#DEE8F7] flex items-center justify-between">
              <span class="text-sm font-bold text-[#0B2A8F] dir-ltr">support@alameen.edu.sa</span>
              <a
                href="mailto:support@alameen.edu.sa"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#EAF1FB] hover:bg-[#DEE8F7] text-xs font-bold text-[#012383] hover:text-[#0B2A8F] transition-all"
                data-i18n="contact.channelTechBtn"
              >
                <span>إرسال بريد للدعم</span>
                <span class="text-xs">←</span>
              </a>
            </div>
          </div>

          <!-- Channel 2: WhatsApp -->
          <div class="bg-white border border-[#DEE8F7] hover:border-[#10B77F] rounded-[22px] p-6 sm:p-8 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div class="flex flex-col gap-3">
              <div class="size-14 rounded-2xl bg-[#EEF7F3] text-[#10B77F] flex items-center justify-center font-bold text-2xl">
                💬
              </div>
              <h3 class="font-bold text-xl text-[#012383]" data-i18n="contact.channelWhatsAppTitle">المحادثة المباشرة عبر واتساب</h3>
              <p class="text-xs sm:text-sm text-[#475569] leading-relaxed">
                تواصل فوري وسريع مع فريق خدمة الطلاب للرد على استفساراتك وتأكيد التسجيل في كورس التخاطب.
              </p>
            </div>
            <div class="mt-6 pt-4 border-t border-[#DEE8F7] flex items-center justify-between">
              <span class="text-sm font-bold text-[#10B77F] dir-ltr">+966 50 123 4567</span>
              <a
                href="https://wa.me/966501234567"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#EEF7F3] hover:bg-[#DEE8F7] text-xs font-bold text-emerald-700 hover:text-emerald-800 transition-all"
                data-i18n="contact.channelWhatsAppBtn"
              >
                <span>فتح محادثة واتساب</span>
                <span class="text-xs">←</span>
              </a>
            </div>
          </div>

        </section>

        <!-- 3. TWO-COLUMN INTERACTIVE INQUIRY WORKFLOW (WorkDo 2-Col Form & Companion) -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          
          <!-- Column 1: Main Inquiry Form (8 cols) -->
          <div class="lg:col-span-8 bg-white border border-[#DEE8F7] rounded-[18px] p-6 sm:p-10 shadow-xs flex flex-col gap-6">
            
            <div>
              <h2 class="font-['Cairo'] font-bold text-2xl sm:text-3xl text-[#012383]" data-i18n="contact.formHeading">
                أرسل استفسارك مباشرة
              </h2>
              <p class="text-xs sm:text-sm text-[#475569] font-medium mt-1" data-i18n="contact.formSubheading">
                اختر موضوع الاستفسار وسيقوم الفريق المختص بالتواصل معك.
              </p>
            </div>

            <!-- Topic Pills Group -->
            <div class="flex flex-col gap-2">
              <label class="text-xs font-bold text-[#012383]">موضوع الاستفسار</label>
              <div class="flex flex-wrap gap-2" id="topic-pills">
                <button
                  type="button"
                  data-topic="general"
                  class="topic-pill active px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer"
                  data-i18n="contact.topicGeneral"
                >
                  استفسار عام عن المنصة
                </button>
                <button
                  type="button"
                  data-topic="course"
                  class="topic-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
                  data-i18n="contact.topicCourse"
                >
                  استفسار عن محتوى الدورات
                </button>
                <button
                  type="button"
                  data-topic="technical"
                  class="topic-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
                  data-i18n="contact.topicTechnical"
                >
                  مشكلة تقنية أو تسجيل دخول
                </button>
                <button
                  type="button"
                  data-topic="cert"
                  class="topic-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
                  data-i18n="contact.topicCertificate"
                >
                  استفسار عن الشهادات والمهام
                </button>
                <button
                  type="button"
                  data-topic="partnership"
                  class="topic-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
                  data-i18n="contact.topicPartnership"
                >
                  مقترح أو شراكة مؤسسية
                </button>
              </div>
            </div>

            <!-- Form Fields Grid -->
            <form id="contact-form" class="flex flex-col gap-4">
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Name -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="contact.nameLabel">الاسم الكامل</label>
                  <input
                    type="text"
                    id="contact-name"
                    required
                    placeholder="مثال: أحمد محمد السالم"
                    data-i18n-placeholder="contact.namePlaceholder"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F]"
                  />
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="contact.emailLabel">البريد الإلكتروني</label>
                  <input
                    type="email"
                    id="contact-email"
                    required
                    placeholder="name@example.com"
                    data-i18n-placeholder="contact.emailPlaceholder"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F]"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Phone -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="contact.phoneLabel">رقم الهاتف / واتساب</label>
                  <input
                    type="tel"
                    id="contact-phone"
                    placeholder="05xxxxxxxx"
                    data-i18n-placeholder="contact.phonePlaceholder"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F]"
                  />
                </div>

                <!-- Subject -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="contact.subjectLabel">موضوع الرسالة</label>
                  <input
                    type="text"
                    id="contact-subject"
                    required
                    placeholder="اكتب عنواناً مختصراً لطلبك"
                    data-i18n-placeholder="contact.subjectPlaceholder"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F]"
                  />
                </div>
              </div>

              <!-- Message Textarea -->
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-[#012383]" data-i18n="contact.messageLabel">تفاصيل الاستفسار</label>
                <textarea
                  id="contact-message"
                  required
                  rows="4"
                  placeholder="اشرح طلبك أو المشكلة التي تواجهك بالتفصيل..."
                  data-i18n-placeholder="contact.messagePlaceholder"
                  class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl p-3.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F] resize-none"
                ></textarea>
              </div>

              <!-- Attachment Dropzone -->
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-[#012383]" data-i18n="contact.attachLabel">إرفاق لقطة شاشة أو ملف توضيحي (اختياري)</label>
                <div class="border-2 border-dashed border-[#DEE8F7] hover:border-[#0B2A8F] rounded-xl p-4 text-center cursor-pointer transition-colors bg-[#FAFCFF] flex items-center justify-center gap-3">
                  <svg class="size-5 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                  </svg>
                  <span class="text-xs font-semibold text-[#475569]" data-i18n="contact.attachPlaceholder">اسحب الملف هنا أو انقر للاختيار</span>
                </div>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                id="submit-contact-btn"
                class="mt-2 w-full sm:w-auto self-start px-8 py-3 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer"
              >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                <span data-i18n="contact.sendBtn">إرسال الرسالة الآن</span>
              </button>

            </form>

          </div>

          <!-- Column 2: Companion Office & Quick Links (4 cols) -->
          <div class="lg:col-span-4 flex flex-col gap-6">
            
            <!-- Headquarters & Hours Card -->
            <div class="bg-white border border-[#DEE8F7] rounded-[18px] p-6 shadow-xs flex flex-col gap-5">
              <h3 class="font-['Cairo'] font-bold text-lg text-[#012383]" data-i18n="contact.officeTitle">
                المقر الرئيسي وأوقات العمل
              </h3>

              <div class="flex flex-col gap-3.5 text-xs">
                
                <div class="flex items-start gap-3">
                  <div class="size-8 rounded-lg bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center shrink-0 text-sm">
                    📍
                  </div>
                  <div>
                    <span class="font-bold text-[#012383] block">فرع المملكة العربية السعودية</span>
                    <span class="text-[#475569]" data-i18n="contact.officeLocationRiyadh">الرياض، طريق الملك فهد، برج الفيصلية التقني</span>
                  </div>
                </div>

                <div class="flex items-start gap-3">
                  <div class="size-8 rounded-lg bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center shrink-0 text-sm">
                    📍
                  </div>
                  <div>
                    <span class="font-bold text-[#012383] block">المركز التقني في مصر</span>
                    <span class="text-[#475569]" data-i18n="contact.officeLocationCairo">القاهرة، المنطقة التكنولوجية بالمعادي</span>
                  </div>
                </div>

                <div class="flex items-start gap-3 pt-2 border-t border-[#DEE8F7]">
                  <div class="size-8 rounded-lg bg-[#EEF7F3] text-[#10B77F] flex items-center justify-center shrink-0 text-sm">
                    ⏰
                  </div>
                  <div>
                    <span class="font-bold text-[#012383] block">ساعات العمل الرسمية</span>
                    <span class="text-[#475569]" data-i18n="contact.workingHours">الأحد إلى الخميس: ٨:٠٠ ص - ١٠:٠٠ م</span>
                  </div>
                </div>

              </div>
            </div>

            <!-- Quick Links Box -->
            <div class="bg-gradient-to-br from-[#FAFCFF] to-[#EAF1FB] border border-[#DEE8F7] rounded-[18px] p-6 shadow-xs flex flex-col gap-4">
              <h3 class="font-bold text-sm text-[#012383]" data-i18n="contact.quickLinksTitle">روابط مساعدة سريعة</h3>
              
              <ul class="flex flex-col gap-2.5 text-xs font-semibold text-[#0B2A8F]">
                <li>
                  <a href="#faq" class="flex items-center justify-between p-2 rounded-lg bg-white/80 hover:bg-white transition-colors border border-[#DEE8F7]/50">
                    <span data-i18n="contact.linkFaq">الأسئلة الشائعة والأجوبة الفورية</span>
                    <span>←</span>
                  </a>
                </li>
                <li>
                  <a href="{{ route('dashboard') }}" class="flex items-center justify-between p-2 rounded-lg bg-white/80 hover:bg-white transition-colors border border-[#DEE8F7]/50">
                    <span data-i18n="contact.linkGuide">دليل استخدام منصة مركز الأمين</span>
                    <span>←</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center justify-between p-2 rounded-lg bg-white/80 hover:bg-white transition-colors border border-[#DEE8F7]/50">
                    <span data-i18n="contact.linkPolicy">سياسة الخصوصية وشروط الخدمة</span>
                    <span>←</span>
                  </a>
                </li>
              </ul>
            </div>

          </div>

        </section>

        <!-- 4. CATEGORIZED FAQ ACCORDION (WorkDo Accordion Pattern) -->
        <section id="faq" class="bg-white border border-[#DEE8F7] rounded-[24px] p-6 sm:p-10 shadow-xs flex flex-col gap-6">
          
          <div class="text-center max-w-2xl mx-auto flex flex-col gap-2">
            <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-[#012383]" data-i18n="contact.faqHeading">
              الأسئلة الأكثر تكراراً
            </h2>
            <p class="text-xs sm:text-sm text-[#475569] font-medium" data-i18n="contact.faqSubheading">
              إجابات سريعة على أبرز التساؤلات حول المنصة والدورات والشهادات.
            </p>
          </div>

          <div class="flex flex-col gap-3 max-w-3xl mx-auto w-full" id="faq-accordion-group">
            
            <!-- FAQ 1 -->
            <div class="faq-item border border-[#DEE8F7] rounded-[16px] overflow-hidden transition-all bg-[#FAFCFF]">
              <button
                type="button"
                class="faq-toggle w-full p-4 text-start font-bold text-xs sm:text-sm text-[#012383] flex items-center justify-between cursor-pointer hover:bg-[#EAF1FB]/50 transition-colors"
              >
                <span data-i18n="contact.faq1Q">كيف يمكنني الحصول على الشهادة بعد انتهاء الدورة؟</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p data-i18n="contact.faq1A">
                  تُصدر الشهادة الرقمية المعتمدة تلقائياً في صفحة «شهاداتي» بمجرد إكمال ٩٥٪ على الأقل من المشاهدة وتسليم المهام المطلوبة بنجاح.
                </p>
              </div>
            </div>

            <!-- FAQ 2 -->
            <div class="faq-item border border-[#DEE8F7] rounded-[16px] overflow-hidden transition-all bg-[#FAFCFF]">
              <button
                type="button"
                class="faq-toggle w-full p-4 text-start font-bold text-xs sm:text-sm text-[#012383] flex items-center justify-between cursor-pointer hover:bg-[#EAF1FB]/50 transition-colors"
              >
                <span data-i18n="contact.faq2Q">هل تتيح المنصة استشارات مباشرة مع أخصائيي النطق؟</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p data-i18n="contact.faq2A">
                  نعم، تتضمن الدورات مساحة لإرسال استفساراتك واستلام ملاحظات تقييمية مفصلة على مهامك العملية من المشرفين الأكاديميين.
                </p>
              </div>
            </div>

            <!-- FAQ 3 -->
            <div class="faq-item border border-[#DEE8F7] rounded-[16px] overflow-hidden transition-all bg-[#FAFCFF]">
              <button
                type="button"
                class="faq-toggle w-full p-4 text-start font-bold text-xs sm:text-sm text-[#012383] flex items-center justify-between cursor-pointer hover:bg-[#EAF1FB]/50 transition-colors"
              >
                <span data-i18n="contact.faq3Q">ما هي متطلبات تشغيل الفيديوهات وتسليم المهام؟</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p data-i18n="contact.faq3A">
                  تعمل المنصة على مختلف المتصفحات الحديثة على الحاسوب والهواتف الذكية دون الحاجة لتثبيت برامج إضافية.
                </p>
              </div>
            </div>

            <!-- FAQ 4 -->
            <div class="faq-item border border-[#DEE8F7] rounded-[16px] overflow-hidden transition-all bg-[#FAFCFF]">
              <button
                type="button"
                class="faq-toggle w-full p-4 text-start font-bold text-xs sm:text-sm text-[#012383] flex items-center justify-between cursor-pointer hover:bg-[#EAF1FB]/50 transition-colors"
              >
                <span data-i18n="contact.faq4Q">هل يمكنني استرداد قيمة الاشتراك في حال عدم الرضا؟</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p data-i18n="contact.faq4A">
                  نعم، نوفر ضمان استرداد كامل خلال ١٤ يوماً من تاريخ الاشتراك وفق الشروط والأحكام المعتمدة.
                </p>
              </div>
            </div>

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
    <script type="module" src="/src/js/i18n.js"></script>
    <script>
      // ==========================================
      // Contact Page Interactive Script
      // ==========================================
      document.addEventListener('DOMContentLoaded', () => {
        // 1. Topic Pills
        const topicPills = document.querySelectorAll('.topic-pill');
        topicPills.forEach(pill => {
          pill.addEventListener('click', () => {
            topicPills.forEach(p => {
              p.classList.remove('active', 'bg-[#012383]', 'text-white');
              p.classList.add('bg-[#EAF1FB]', 'text-[#0B2A8F]');
            });
            pill.classList.add('active', 'bg-[#012383]', 'text-white');
            pill.classList.remove('bg-[#EAF1FB]', 'text-[#0B2A8F]');
          });
        });

        // 2. Form submission
        const contactForm = document.getElementById('contact-form');
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        contactForm?.addEventListener('submit', (e) => {
          e.preventDefault();
          const isEn = document.documentElement.lang === 'en';
          toastMessage.textContent = isEn ? 'Your inquiry has been received! We will respond shortly.' : 'تم استلام استفسارك بنجاح! سيتواصل معك فريق الدعم قريباً.';
          toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
          
          contactForm.reset();
          setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
          }, 3500);
        });

        // 3. FAQ Accordion Toggle
        const faqToggles = document.querySelectorAll('.faq-toggle');
        faqToggles.forEach(toggle => {
          toggle.addEventListener('click', () => {
            const content = toggle.nextElementSibling;
            const icon = toggle.querySelector('.faq-icon');
            const isOpen = !content.classList.contains('hidden');

            if (isOpen) {
              content.classList.add('hidden');
              icon.style.transform = 'rotate(0deg)';
            } else {
              content.classList.remove('hidden');
              icon.style.transform = 'rotate(180deg)';
            }
          });
        });
      });
    </script>
      <script type="module" src="/src/js/animations.js"></script>
  </body>
</html>


