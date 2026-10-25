<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
  <head>
    @include('partials.head')
    <title>{{ config('app.name', 'منصة مركز الأمين') }} | {{ __('كورس التخاطب الشامل') }}</title>
</head>
  <body class="bg-[#FAFCFF] min-h-screen text-[#334155] font-body antialiased selection:bg-[#EAF1FB] selection:text-[#0B2A8F] overflow-x-hidden relative">
    
    <!-- Toast Notification Element -->
    <div
      id="toast"
      class="fixed bottom-6 start-6 z-50 bg-[#012383] text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none"
    >
      <svg class="size-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      <span id="toast-message" class="text-sm font-semibold"></span>
    </div>

    <!-- Main 1440px Canvas Container -->
    <div class="w-full max-w-[1440px] mx-auto min-h-screen flex flex-col relative bg-[#FAFCFF]" id="landing-frame">
      
      <!-- ============================================== -->
      <!-- UNIFIED MASTER HEADER (Sticky at Top)          -->
      <!-- ============================================== -->
      <header class="w-full bg-[#FAFCFF]/95 backdrop-blur-md border-b border-[#D7E3F5] flex items-center justify-between px-4 sm:px-6 lg:px-[48px] py-3 sm:py-4 shrink-0 sticky top-0 z-50 shadow-xs" id="main-header">
        
        <!-- Brand Identity -->
        <a href="#home" class="flex gap-2.5 sm:gap-3 lg:gap-[14px] items-center shrink-0 cursor-pointer">
          <div class="size-[42px] sm:size-[52px] lg:size-[64px] shrink-0 relative">
            <img src="/assets/images/logo.png" alt="منصة مركز الأمين" class="size-full object-contain pointer-events-none" />
          </div>
          <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-[22px] sm:text-[30px] lg:text-[38px] leading-tight text-[#012383] whitespace-nowrap tracking-tight">{{ __('brand.name') }}</span>
        </a>

        <!-- Main Navigation Links (Controlled via ScrollSpy) -->
        <nav class="hidden lg:flex gap-[6px] xl:gap-[12px] items-center shrink-0">
          
          <!-- الرئيسية -->
          <a href="#home" class="px-4 py-2 bg-[#EAF1FB] text-[#0B2A8F] text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors">{{ __('landing.navHome') }}</a>

          <!-- الدورات -->
          <a href="#courses" class="px-3.5 py-2 text-[#012383] hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors">{{ __('landing.navCourses') }}</a>

          <!-- من نحن -->
          <a href="#about" class="px-3.5 py-2 text-[#012383] hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors">{{ __('landing.navAbout') }}</a>

          <!-- آراء الطلاب -->
          <a href="#reviews" class="px-3.5 py-2 text-[#012383] hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors">{{ __('landing.navTestimonials') }}</a>

          <!-- تواصل معنا -->
          <a href="#contact" class="px-3.5 py-2 text-[#012383] hover:text-[#0B2A8F] hover:bg-[#EAF1FB]/50 text-[16px] xl:text-[18px] font-bold font-['Cairo'] rounded-[14px] whitespace-nowrap transition-colors">{{ __('landing.navContact') }}</a>

        </nav>

        <!-- Right Group (Language Switcher & Auth) -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
          
          <!-- Interactive Language Switcher Pill -->
          <x-language-switcher variant="pill" />

                    @if (auth('admin')->check())
            <a
              href="{{ route('admin.dashboard') }}"
              class="bg-gradient-to-r from-[#F2AE2E] to-amber-500 hover:from-amber-400 hover:to-[#F2AE2E] flex items-center justify-center px-4 sm:px-6 h-[36px] sm:h-[40px] rounded-full text-slate-950 text-xs sm:text-sm font-bold font-['Cairo'] transition-all shadow-sm hover:shadow-md cursor-pointer whitespace-nowrap"
            >
              {{ __('لوحة الإدارة') }}
            </a>
          @elseif (auth()->check())
            <a
              href="{{ route('dashboard') }}"
              class="bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center px-4 sm:px-6 h-[36px] sm:h-[40px] rounded-full !text-white text-xs sm:text-sm font-bold font-['Cairo'] transition-all shadow-sm hover:shadow-md cursor-pointer whitespace-nowrap"
              style="color: #ffffff !important;"
            >
              <span class="text-white" style="color: #ffffff !important;">{{ __('بوابة الطالب') }}</span>
            </a>
          @else
            <div class="flex items-center gap-2">
              <a
                href="{{ route('login') }}"
                class="bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center px-3.5 sm:px-5 h-[36px] sm:h-[40px] rounded-full !text-white text-xs sm:text-sm font-bold font-['Cairo'] transition-all shadow-sm hover:shadow-md cursor-pointer whitespace-nowrap"
                style="color: #ffffff !important;"
              >
                <span class="text-white" style="color: #ffffff !important;">{{ __('دخول الطلاب') }}</span>
              </a>
              @if (Route::has('register'))
                <a
                  href="{{ route('register') }}"
                  class="hidden sm:flex border border-[#012383] text-[#012383] hover:bg-[#EAF1FB] items-center justify-center px-3.5 sm:px-5 h-[36px] sm:h-[40px] rounded-full text-xs sm:text-sm font-bold font-['Cairo'] transition-all cursor-pointer whitespace-nowrap"
                >
                  {{ __('حساب جديد') }}
                </a>
              @endif
            </div>
          @endif

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
      <div id="mobile-nav-menu" class="hidden lg:hidden w-full bg-[#FAFCFF] border-b border-[#D7E3F5] px-4 py-3 flex-col gap-1.5 sticky top-[68px] z-40 shadow-md">
        <a href="#home" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm bg-[#EAF1FB] text-[#0B2A8F]">{{ __('landing.navHome') }}</a>
        <a href="#courses" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50">{{ __('landing.navCourses') }}</a>
        <a href="#about" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50">{{ __('landing.navAbout') }}</a>
        <a href="#reviews" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50">{{ __('landing.navTestimonials') }}</a>
        <a href="#contact" class="mobile-nav-link px-4 py-2.5 rounded-xl font-['Cairo'] font-bold text-sm text-[#012383] hover:bg-[#EAF1FB]/50">{{ __('landing.navContact') }}</a>
      </div>



      <!-- ========================================================================= -->
      <!-- SECTION 1: #home (HERO SLIDER SHOWCASE)                                   -->
      <!-- ========================================================================= -->
      <section id="home" class="w-full min-h-[calc(100vh-80px)] flex flex-col justify-between px-4 sm:px-6 lg:px-[48px] py-6 lg:py-8 relative overflow-hidden bg-gradient-to-b from-[#FAFCFF] to-white scroll-mt-20">
        
        <!-- Ambient Glowing Background Blobs -->
        <div class="absolute -top-20 start-1/4 size-[400px] rounded-full bg-blue-100/35 blur-3xl pointer-events-none animate-ambient-glow -z-10"></div>
        <div class="absolute -bottom-10 end-12 size-[350px] rounded-full bg-indigo-50/45 blur-3xl pointer-events-none animate-float-reverse -z-10"></div>

        <!-- Hero Slider Canvas (Grid Stacking for Ultra-Smooth Transitions) -->
        <div class="hero-slider-canvas w-full flex-1 relative" id="hero-slider">
          
          <!-- SLIDE 1: مسارات التخاطب الشاملة (Default Active) -->
          <div class="hero-slide is-active w-full flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12" data-slide="0">
            
            <!-- Slide 1 Text -->
            <div class="w-full lg:max-w-[620px] xl:max-w-[680px] flex flex-col items-center lg:items-start text-center lg:text-start shrink-0 order-1 gap-4 sm:gap-5 lg:gap-[20px]">
              
              <div class="slide-eyebrow inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF1FB] border border-[#0B2A8F]/20 text-[#0B2A8F] text-xs sm:text-sm font-extrabold shadow-xs">
                <span class="size-2 rounded-full bg-[#0B2A8F] animate-pulse"></span>
                <span>{{ __('landing.slide1Eyebrow') }}</span>
              </div>

              <div class="flex flex-col gap-3 sm:gap-4 items-center lg:items-start w-full">
                <h1 class="slide-title font-['Baloo_Bhaijaan_2'] font-bold text-[32px] sm:text-[46px] lg:text-[60px] leading-[1.18] text-[#012383] w-full tracking-tight">{{ __('landing.slide1Title') }}</h1>

                <p class="slide-desc font-['Cairo'] font-semibold text-[15px] sm:text-[18px] lg:text-[20px] leading-[1.8] text-[#1E293B] w-full max-w-[580px]">{{ __('landing.slide1Desc') }}</p>
              </div>

              <div class="slide-ctas flex flex-wrap items-center justify-center lg:justify-start gap-3 pt-2">
                <a
                  href="#courses"
                  class="px-7 sm:px-8 h-[50px] sm:h-[58px] bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center rounded-[16px] text-white font-['Cairo'] font-extrabold text-[17px] sm:text-[19px] shadow-md hover:shadow-lg transition-all cursor-pointer whitespace-nowrap"
                >{{ __('landing.slide1Cta1') }}</a>
                <a
                  href="#about"
                  class="px-6 h-[50px] sm:h-[58px] bg-white hover:bg-[#EAF1FB] text-[#012383] border border-[#DEE8F7] flex items-center justify-center rounded-[16px] font-['Cairo'] font-bold text-[15px] sm:text-[17px] transition-colors cursor-pointer whitespace-nowrap shadow-xs"
                >{{ __('landing.slide1Cta2') }}</a>
              </div>

            </div>

            <!-- Slide 1 Photo Grid Collage -->
            <div class="slide-visual w-full max-w-[360px] sm:max-w-[420px] lg:max-w-[480px] aspect-square shrink-0 order-2 mx-auto lg:mx-0 relative" dir="ltr">
              <div class="grid grid-cols-2 gap-3 sm:gap-[16px] size-full">
                
                <div class="bg-[#DCE8F2] overflow-hidden rounded-full aspect-square w-full relative shadow-sm hover:scale-[1.02] transition-transform">
                  <img src="/assets/images/photo1.png" alt="التدريب العملي" class="size-full object-cover pointer-events-none" />
                </div>

                <div class="bg-[#152D4D] overflow-hidden rounded-[16px] sm:rounded-[20px] aspect-square w-full relative shadow-sm group hover:scale-[1.02] transition-transform cursor-pointer">
                  <img src="/assets/images/photo2.png" alt="تنمية المهارات" class="size-full object-cover pointer-events-none" />
                  <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 size-[48px] sm:size-[56px] rounded-full group-hover:scale-110 transition-transform animate-pulse-glow">
                    <img src="/assets/images/play.svg" alt="تشغيل" class="size-full" />
                  </div>
                </div>

                <div class="bg-[#EDF2F7] overflow-hidden rounded-[16px] sm:rounded-[20px] aspect-square w-full relative shadow-sm group hover:scale-[1.02] transition-transform cursor-pointer">
                  <img src="/assets/images/photo3.png" alt="الجلسات التفاعلية" class="size-full object-cover pointer-events-none" />
                  <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 size-[48px] sm:size-[56px] rounded-full group-hover:scale-110 transition-transform animate-pulse-glow">
                    <img src="/assets/images/play.svg" alt="تشغيل" class="size-full" />
                  </div>
                </div>

                <div class="bg-[#203C60] overflow-hidden rounded-tl-[16px] rounded-bl-[16px] sm:rounded-tl-[20px] sm:rounded-bl-[20px] rounded-tr-[50%] rounded-br-[50%] aspect-square w-full relative shadow-sm hover:scale-[1.02] transition-transform">
                  <div class="absolute left-[12px] sm:left-[16px] top-[60px] sm:top-[80px] z-0 flex flex-col gap-1 pointer-events-none opacity-85">
                    <svg class="w-[26px] h-[26px] sm:w-[32px] sm:h-[32px] -rotate-12" viewBox="0 0 32 32" fill="none">
                      <path d="M4 16C4 7 13 4 28 4C28 19 19 28 4 16Z" fill="#7E95B1"/>
                    </svg>
                    <svg class="w-[26px] h-[26px] sm:w-[32px] sm:h-[32px] rotate-45 -mt-2" viewBox="0 0 32 32" fill="none">
                      <path d="M4 16C4 7 13 4 28 4C28 19 19 28 4 16Z" fill="#7E95B1"/>
                    </svg>
                  </div>
                  <img src="/assets/images/photo4.png" alt="الاعتماد الأكاديمي" class="size-full object-cover pointer-events-none relative z-10" />
                </div>

              </div>

              <!-- Floating Trust Badge with Automatic Gentle Floating Animation -->
              <div class="animate-float-slow absolute -bottom-4 start-4 bg-white/95 backdrop-blur-md border border-[#DEE8F7] px-4 py-2.5 rounded-2xl shadow-card flex items-center gap-2.5 z-20">
                <div class="size-8 rounded-full bg-[#EEF7F3] flex items-center justify-center text-emerald-700 font-bold text-xs shadow-xs">
                  ✓
                </div>
                <div class="flex flex-col text-start">
                  <span class="text-xs font-bold text-[#012383]">{{ __('landing.statsStudents') }}</span>
                  <span class="text-[11px] text-[#475569]">{{ __('landing.statsSub') }}</span>
                </div>
              </div>
            </div>

          </div>

          <!-- SLIDE 2: تطبيقات عملية وفيديوهات تفاعلية -->
          <div class="hero-slide w-full flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12" data-slide="1">
            
            <!-- Slide 2 Text -->
            <div class="w-full lg:max-w-[620px] xl:max-w-[680px] flex flex-col items-center lg:items-start text-center lg:text-start shrink-0 order-1 gap-4 sm:gap-5 lg:gap-[20px]">
              
              <div class="slide-eyebrow inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#10B77F]/30 text-[#10B77F] text-xs sm:text-sm font-extrabold shadow-xs">
                <span class="size-2 rounded-full bg-[#10B77F] animate-ping"></span>
                <span>{{ __('landing.slide2Eyebrow') }}</span>
              </div>

              <div class="flex flex-col gap-3 sm:gap-4 items-center lg:items-start w-full">
                <h2 class="slide-title font-['Baloo_Bhaijaan_2'] font-bold text-[32px] sm:text-[46px] lg:text-[60px] leading-[1.18] text-[#012383] w-full tracking-tight">{{ __('landing.slide2Title') }}</h2>

                <p class="slide-desc font-['Cairo'] font-semibold text-[15px] sm:text-[18px] lg:text-[20px] leading-[1.8] text-[#1E293B] w-full max-w-[580px]">{{ __('landing.slide2Desc') }}</p>
              </div>

              <div class="slide-ctas flex flex-wrap items-center justify-center lg:justify-start gap-3 pt-2">
                <a
                  href="#courses"
                  class="px-7 sm:px-8 h-[50px] sm:h-[58px] bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center rounded-[16px] text-white font-['Cairo'] font-extrabold text-[17px] sm:text-[19px] shadow-md hover:shadow-lg transition-all cursor-pointer whitespace-nowrap"
                >{{ __('landing.slide2Cta1') }}</a>
                <a
                  href="./pages/lesson.html"
                  class="px-6 h-[50px] sm:h-[58px] bg-white hover:bg-[#EAF1FB] text-[#012383] border border-[#DEE8F7] flex items-center justify-center rounded-[16px] font-['Cairo'] font-bold text-[15px] sm:text-[17px] transition-colors cursor-pointer whitespace-nowrap shadow-xs"
                >{{ __('landing.slide2Cta2') }}</a>
              </div>

            </div>

            <!-- Slide 2 Video & Audio Training Showcase -->
            <div class="slide-visual w-full max-w-[380px] sm:max-w-[440px] lg:max-w-[500px] shrink-0 order-2 mx-auto lg:mx-0 relative flex flex-col gap-4">
              
              <!-- Video Player Card -->
              <div class="bg-white border-2 border-[#DEE8F7] rounded-[24px] p-3 shadow-card relative overflow-hidden group">
                <div class="h-[240px] sm:h-[270px] w-full rounded-[18px] bg-[#0B2A8F] relative overflow-hidden flex items-center justify-center">
                  <img src="/assets/images/lesson-video-frame.png" alt="فيديو الدرس" class="size-full object-cover pointer-events-none group-hover:scale-105 transition-transform duration-500" />
                  
                  <a href="./pages/lesson.html" class="absolute size-14 sm:size-16 rounded-full bg-white/95 text-[#012383] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform animate-pulse-glow">
                    <svg class="size-6 text-[#012383] translate-x-0.5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z" />
                    </svg>
                  </a>

                  <div class="absolute bottom-3 end-3 px-3 py-1 rounded-full bg-black/70 backdrop-blur-md text-white text-xs font-bold font-['Cairo'] tabular-nums">
                    ١٤:٣٠ دقيقة
                  </div>

                  <div class="absolute top-3 start-3 px-3 py-1 rounded-full bg-[#10B77F] text-white text-xs font-bold font-['Cairo'] shadow-sm flex items-center gap-1.5">
                    <span class="size-1.5 rounded-full bg-white animate-ping"></span>
                    <span>تطبيق عملي</span>
                  </div>
                </div>
              </div>

              <!-- Overlaid Voice Task Pill with Animated Dynamic Audio Waveform -->
              <div class="animate-float-slow bg-white border border-[#DEE8F7] rounded-2xl p-4 shadow-card flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-xl bg-[#EEF7F3] text-emerald-700 flex items-center justify-center font-bold text-lg shrink-0">
                    🎙️
                  </div>
                  <div class="flex flex-col text-start">
                    <div class="flex items-center gap-2">
                      <span class="text-xs font-bold text-[#012383]">مهمة صوتية: تصحيح مخارج الحروف</span>
                      <div class="flex items-center gap-0.5 h-4 px-1 bg-emerald-50 rounded-md">
                        <span class="w-0.5 bg-[#10B77F] rounded-full voice-wave-bar"></span>
                        <span class="w-0.5 bg-[#10B77F] rounded-full voice-wave-bar"></span>
                        <span class="w-0.5 bg-[#10B77F] rounded-full voice-wave-bar"></span>
                        <span class="w-0.5 bg-[#10B77F] rounded-full voice-wave-bar"></span>
                        <span class="w-0.5 bg-[#10B77F] rounded-full voice-wave-bar"></span>
                      </div>
                    </div>
                    <span class="text-[11px] text-emerald-600 font-semibold">تم التقييم بنجاح • ٩٥٪ مستوى متميز</span>
                  </div>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-[#EAF1FB] text-[#0B2A8F] text-[11px] font-bold shrink-0">
                  مُقيّمة
                </span>
              </div>

            </div>

          </div>

          <!-- SLIDE 3: تقييم مستمر وشهادات معتمدة -->
          <div class="hero-slide w-full flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12" data-slide="2">
            
            <!-- Slide 3 Text -->
            <div class="w-full lg:max-w-[620px] xl:max-w-[680px] flex flex-col items-center lg:items-start text-center lg:text-start shrink-0 order-1 gap-4 sm:gap-5 lg:gap-[20px]">
              
              <div class="slide-eyebrow inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF1FB] border border-[#0B2A8F]/20 text-[#0B2A8F] text-xs sm:text-sm font-extrabold shadow-xs">
                <span class="size-2 rounded-full bg-[#0B2A8F] animate-pulse"></span>
                <span>{{ __('landing.slide3Eyebrow') }}</span>
              </div>

              <div class="flex flex-col gap-3 sm:gap-4 items-center lg:items-start w-full">
                <h2 class="slide-title font-['Baloo_Bhaijaan_2'] font-bold text-[32px] sm:text-[46px] lg:text-[60px] leading-[1.18] text-[#012383] w-full tracking-tight">{{ __('landing.slide3Title') }}</h2>

                <p class="slide-desc font-['Cairo'] font-semibold text-[15px] sm:text-[18px] lg:text-[20px] leading-[1.8] text-[#1E293B] w-full max-w-[580px]">{{ __('landing.slide3Desc') }}</p>
              </div>

              <div class="slide-ctas flex flex-wrap items-center justify-center lg:justify-start gap-3 pt-2">
                <a
                  href="{{ route('register') }}"
                  class="px-7 sm:px-8 h-[50px] sm:h-[58px] bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center rounded-[16px] text-white font-['Cairo'] font-extrabold text-[17px] sm:text-[19px] shadow-md hover:shadow-lg transition-all cursor-pointer whitespace-nowrap"
                >{{ __('landing.slide3Cta1') }}</a>
                <a
                  href="./pages/certificate.html"
                  class="px-6 h-[50px] sm:h-[58px] bg-white hover:bg-[#EAF1FB] text-[#012383] border border-[#DEE8F7] flex items-center justify-center rounded-[16px] font-['Cairo'] font-bold text-[15px] sm:text-[17px] transition-colors cursor-pointer whitespace-nowrap shadow-xs"
                >{{ __('landing.slide3Cta2') }}</a>
              </div>

            </div>

            <!-- Slide 3 Certificate Showcase -->
            <div class="slide-visual w-full max-w-[380px] sm:max-w-[440px] lg:max-w-[500px] shrink-0 order-2 mx-auto lg:mx-0 relative flex flex-col gap-4">
              
              <div class="animate-float-slow bg-white border-2 border-[#DEE8F7] rounded-[24px] p-3 shadow-card relative overflow-hidden group hover:scale-[1.02] transition-transform">
                <div class="h-[250px] sm:h-[280px] w-full rounded-[18px] overflow-hidden relative bg-slate-50 flex items-center justify-center">
                  <img src="/assets/images/certificate-preview.png" alt="معاينة الشهادة" class="size-full object-cover pointer-events-none" />
                  
                  <div class="absolute inset-0 bg-gradient-to-t from-[#012383]/80 via-transparent to-transparent flex flex-col justify-end p-5 text-white">
                    <span class="text-xs font-bold text-blue-200">شهادة معتمدة برقم توثيق رسمي</span>
                    <h4 class="text-base font-bold font-['Cairo']">أساسيات التخاطب وتطبيقات النطق</h4>
                  </div>
                </div>
              </div>

              <div class="animate-float-reverse bg-white border border-[#DEE8F7] rounded-2xl p-4 shadow-card flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center font-bold text-lg shrink-0">
                    🎓
                  </div>
                  <div class="flex flex-col text-start">
                    <span class="text-xs font-bold text-[#012383]">نسبة إنجاز الدورة: ٩٥٪</span>
                    <span class="text-[11px] text-emerald-600 font-semibold">مؤهل لإصدار وتحميل الشهادة</span>
                  </div>
                </div>
                <a href="./pages/certificate.html" class="px-3 py-1.5 rounded-xl bg-[#012383] text-white text-xs font-bold hover:bg-[#0B2A8F] transition-colors shrink-0">
                  استعراض
                </a>
              </div>

            </div>

          </div>

          <!-- Slider Chevrons & Dots Navigation -->
          <div class="w-full flex items-center justify-between pt-4 lg:pt-6 z-20">
            
            <div class="flex items-center gap-2">
              <button
                type="button"
                id="slider-prev"
                aria-label="السابق"
                class="size-10 rounded-full bg-white border border-[#DEE8F7] text-[#012383] hover:bg-[#EAF1FB] hover:border-[#0B2A8F]/40 flex items-center justify-center transition-all shadow-xs hover:shadow-md cursor-pointer"
              >
                <svg class="size-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              <button
                type="button"
                id="slider-next"
                aria-label="التالي"
                class="size-10 rounded-full bg-white border border-[#DEE8F7] text-[#012383] hover:bg-[#EAF1FB] hover:border-[#0B2A8F]/40 flex items-center justify-center transition-all shadow-xs hover:shadow-md cursor-pointer"
              >
                <svg class="size-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>

            <!-- Smooth Scroll Cue To Next Section -->
            <a
              href="#courses"
              class="hidden sm:inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/90 border border-[#DEE8F7] hover:border-[#0B2A8F]/40 shadow-xs hover:shadow-sm transition-all cursor-pointer text-[#012383] group"
              aria-label="الانتقال إلى الدورات"
            >
              <div class="w-3.5 h-5 border-2 border-[#0B2A8F] rounded-full flex justify-center pt-0.5 group-hover:scale-105 transition-transform">
                <div class="size-1 rounded-full bg-[#0B2A8F] animate-scroll-bounce"></div>
              </div>
              <span class="text-xs font-bold font-['Cairo'] text-[#0B2A8F] group-hover:text-[#012383]">
                استكشف الدورات بالأسفل
              </span>
              <svg class="size-3.5 text-[#0B2A8F] animate-scroll-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
              </svg>
            </a>

            <!-- Dot Indicators -->
            <div class="flex items-center gap-2">
              <button type="button" class="slider-dot h-2.5 rounded-full transition-all duration-300 w-8 bg-[#012383] cursor-pointer" aria-label="الشريحة 1"></button>
              <button type="button" class="slider-dot h-2.5 rounded-full transition-all duration-300 w-2.5 bg-[#CBD5E1] hover:bg-[#94A3B8] cursor-pointer" aria-label="الشريحة 2"></button>
              <button type="button" class="slider-dot h-2.5 rounded-full transition-all duration-300 w-2.5 bg-[#CBD5E1] hover:bg-[#94A3B8] cursor-pointer" aria-label="الشريحة 3"></button>
            </div>

          </div>

        </div>

        <!-- Learning Journey Footer Tabs ( رحلتك مع الأمين ) -->
        <div class="w-full border-t border-[#DEE8F7] py-3 lg:h-[72px] flex flex-wrap items-center justify-between shrink-0 z-20 gap-3 sm:gap-4 bg-white/80 backdrop-blur-xs mt-6 rounded-2xl px-4 lg:px-6 shadow-xs">
          
          <p class="font-['Cairo'] font-extrabold text-[16px] sm:text-[18px] lg:text-[20px] text-[#0B2A8F] whitespace-nowrap order-1">{{ __('landing.journeyTitle') }}</p>

          <button
            type="button"
            data-step-target="0"
            class="step-tab flex gap-2 sm:gap-2.5 items-center px-3.5 py-1.5 rounded-full transition-all cursor-pointer bg-[#EAF1FB] border border-[#DEE8F7] shadow-xs order-2"
          >
            <div class="step-dot size-2 rounded-full bg-[#0B2A8F] scale-125 transition-transform"></div>
            <span class="step-text font-['Cairo'] font-black text-[14px] sm:text-[16px] lg:text-[17px] text-[#0B2A8F] whitespace-nowrap">{{ __('landing.step1') }}</span>
          </button>

          <button
            type="button"
            data-step-target="1"
            class="step-tab flex gap-2 sm:gap-2.5 items-center px-3.5 py-1.5 rounded-full transition-all cursor-pointer hover:bg-slate-100/60 order-3"
          >
            <div class="step-dot size-2 rounded-full bg-slate-400 transition-transform"></div>
            <span class="step-text font-['Cairo'] font-semibold text-[14px] sm:text-[16px] lg:text-[17px] text-[#475569] whitespace-nowrap">{{ __('landing.step2') }}</span>
          </button>

          <button
            type="button"
            data-step-target="2"
            class="step-tab flex gap-2 sm:gap-2.5 items-center px-3.5 py-1.5 rounded-full transition-all cursor-pointer hover:bg-slate-100/60 order-4"
          >
            <div class="step-dot size-2 rounded-full bg-slate-400 transition-transform"></div>
            <span class="step-text font-['Cairo'] font-semibold text-[14px] sm:text-[16px] lg:text-[17px] text-[#475569] whitespace-nowrap">{{ __('landing.step3') }}</span>
          </button>

        </div>

      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 2: #courses (SPECIALIZED COURSES & CATALOG)                        -->
      <!-- ========================================================================= -->
      <section id="courses" class="w-full px-4 sm:px-6 lg:px-[48px] py-14 lg:py-20 flex flex-col gap-10 bg-white border-t border-[#DEE8F7] scroll-mt-20">
        
        <!-- Header & Search -->
        <div class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto w-full">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF1FB] border border-[#0B2A8F]/20 text-[#0B2A8F] text-xs font-bold">
            <span>{{ __('catalog.eyebrow') }}</span>
          </div>

          <h2 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight">{{ __('catalog.heroTitle') }}</h2>

          <p class="text-base sm:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed">{{ __('catalog.heroDesc') }}</p>

          <!-- Search Input Bar -->
          <div class="w-full max-w-2xl mt-2 relative">
            <input
              type="text"
              id="course-search-input"
              placeholder="ابحث باسم الدورة، الأخصائي، أو المهارة..."
              placeholder="{{ __('catalog.searchPlaceholder') }}"class="w-full h-13 bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/20 rounded-2xl px-12 text-sm text-[#012383] placeholder-[#475569] outline-none shadow-sm transition-all"
            />
            <svg class="size-5 text-[#0B2A8F] absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>

        <!-- Filter Categories & Counter -->
        <div class="flex flex-wrap items-center justify-between gap-3 bg-[#FAFCFF] border border-[#DEE8F7] rounded-[18px] p-3 shadow-xs">
          <div class="flex flex-wrap items-center gap-2" id="catalog-category-group">
            <button
              type="button"
              data-cat="all"
              class="catalog-filter active px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer shadow-xs"
            >
              كورس التخاطب
            </button>
          </div>

          <span class="text-xs font-bold text-[#0B2A8F] bg-[#EAF1FB] px-3.5 py-1.5 rounded-full border border-[#DEE8F7]">
            مسار التخاطب السريري للطلاب
          </span>
        </div>

        <!-- Courses Grid (3 Columns) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="catalog-courses-grid">
          
          <!-- Card 1 -->
          <div class="reveal-on-scroll course-card bg-white border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[20px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1.5 transition-all flex flex-col justify-between" data-category="speech" data-title="استراتيجيات تنمية اللغة">
            <div>
              <div class="h-48 w-full bg-[#012383] relative overflow-hidden">
                <img src="/assets/images/course-topic-strategies.png" alt="استراتيجيات تنمية اللغة" class="size-full object-cover" />
                <div class="absolute top-3 end-3 flex gap-2">
                  <span class="px-2.5 py-1 rounded-full bg-[#10B77F] text-white text-[11px] font-bold shadow-xs">الأكثر طلباً</span>
                  <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">٢٠ درساً</span>
                </div>
              </div>
              <div class="p-5 flex flex-col gap-3">
                <div class="flex items-center justify-between text-xs font-bold">
                  <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">تخاطب ونطق</span>
                  <div class="flex items-center gap-1 text-[#F59E0B]">
                    <span>★ 4.9</span>
                    <span class="text-slate-400 font-normal">(480)</span>
                  </div>
                </div>
                <h3 class="font-['Cairo'] font-bold text-[18px] text-[#012383] leading-snug">
                  استراتيجيات تنمية اللغة والتواصل
                </h3>
                <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                  دليل شامل يركز على التطبيقات السريرية والمنهجية لتطوير الحصيلة اللغوية عند حالات التأخر النمائي.
                </p>
                <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                  <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                    د
                  </div>
                  <span>د. عمر القحطاني • محاضر وباحث إكلينيكي</span>
                </div>
              </div>
            </div>
            <div class="p-5 pt-0 flex items-center justify-between gap-3">
              <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg">
                مشمول بالاشتراك
              </span>
              <a href="./pages/course-details.html" class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs">
                عرض تفاصيل الكورس
              </a>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="reveal-on-scroll course-card bg-white border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[20px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1.5 transition-all flex flex-col justify-between" style="transition-delay: 150ms;" data-category="speech" data-title="تطبيقات مخارج الحروف">
            <div>
              <div class="h-48 w-full bg-[#0B2A8F] relative overflow-hidden">
                <img src="/assets/images/course-topic-articulation.png" alt="تطبيقات مخارج الحروف" class="size-full object-cover" />
                <div class="absolute top-3 end-3 flex gap-2">
                  <span class="px-2.5 py-1 rounded-full bg-amber-500 text-white text-[11px] font-bold shadow-xs">مميز</span>
                  <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٦ درساً</span>
                </div>
              </div>
              <div class="p-5 flex flex-col gap-3">
                <div class="flex items-center justify-between text-xs font-bold">
                  <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">تخاطب ونطق</span>
                  <div class="flex items-center gap-1 text-[#F59E0B]">
                    <span>★ 4.8</span>
                    <span class="text-slate-400 font-normal">(310)</span>
                  </div>
                </div>
                <h3 class="font-['Cairo'] font-bold text-[18px] text-[#012383] leading-snug">
                  تطبيقات مخارج الحروف وتصحيح النطق
                </h3>
                <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                  برنامج عملي مركز لتدريب الطلاب على تصحيح إبدال وتشويه الأصوات الكلامية (الراء، السين، الكاف) بالتمارين السريرية.
                </p>
                <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                  <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                    أ
                  </div>
                  <span>أ. أحمد السالم • محاضر التخاطب السريري</span>
                </div>
              </div>
            </div>
            <div class="p-5 pt-0 flex items-center justify-between gap-3">
              <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg">
                مشمول بالاشتراك
              </span>
              <a href="./pages/course-details.html" class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs">
                عرض تفاصيل الكورس
              </a>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="reveal-on-scroll course-card bg-white border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[20px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1.5 transition-all flex flex-col justify-between" style="transition-delay: 300ms;" data-category="speech" data-title="أساسيات التخاطب">
            <div>
              <div class="h-48 w-full bg-[#152D4D] relative overflow-hidden">
                <img src="/assets/images/course-topic-foundations.png" alt="أساسيات التخاطب" class="size-full object-cover" />
                <div class="absolute top-3 end-3 flex gap-2">
                  <span class="px-2.5 py-1 rounded-full bg-blue-600 text-white text-[11px] font-bold shadow-xs">أساسي</span>
                  <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٢ درساً</span>
                </div>
              </div>
              <div class="p-5 flex flex-col gap-3">
                <div class="flex items-center justify-between text-xs font-bold">
                  <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">تخاطب ونطق</span>
                  <div class="flex items-center gap-1 text-[#F59E0B]">
                    <span>★ 4.9</span>
                    <span class="text-slate-400 font-normal">(520)</span>
                  </div>
                </div>
                <h3 class="font-['Cairo'] font-bold text-[18px] text-[#012383] leading-snug">
                  أساسيات التخاطب واضطرابات التواصل
                </h3>
                <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                  المدخل الأكاديمي الشامل لطلاب التخاطب لفهم التطور اللغوي الطبيعي وتشخيص الفروق الفردية ومؤشرات التدخل المبكر.
                </p>
                <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                  <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                    د
                  </div>
                  <span>د. سامي المنصور • محاضر اضطرابات التواصل</span>
                </div>
              </div>
            </div>
            <div class="p-5 pt-0 flex items-center justify-between gap-3">
              <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg">
                مشمول بالاشتراك
              </span>
              <a href="./pages/course-details.html" class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs">
                عرض تفاصيل الكورس
              </a>
            </div>
          </div>

        </div>

        <div class="flex justify-center pt-4">
          <a
            href="./pages/courses.html"
            class="px-8 py-3.5 bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#012383] text-sm font-bold rounded-2xl transition-all shadow-xs flex items-center gap-2"
          >
            <span>استعراض محتوى الكورس في صفحة مستقلة</span>
            <span class="text-xs">←</span>
          </a>
        </div>

      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 3: #about (ABOUT AL-AMEEN & CORE VALUES)                          -->
      <!-- ========================================================================= -->
      <section id="about" class="w-full px-4 sm:px-6 lg:px-[48px] py-14 lg:py-20 flex flex-col gap-12 bg-[#FAFCFF] border-t border-[#DEE8F7] scroll-mt-20">
        
        <!-- Header -->
        <div class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF1FB] border border-[#0B2A8F]/20 text-[#0B2A8F] text-xs font-bold">
            <span>{{ __('landing.navAbout') }}</span>
          </div>

          <h2 class="font-['Baloo_Bhaijaan_2'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight">
            منصة مركز الأمين.. شريكك الأول في تأهيل التخاطب والنطق
          </h2>

          <p class="text-base sm:text-lg text-[#475569] max-w-3xl font-medium leading-relaxed">
            منصة تعليمية متخصصة تجمع بين المعرفة الأكاديمية والتطبيق السريري لتمكين طلاب التخاطب والدارسين من إتقان مهارات التشخيص والعلاج بأعلى معايير الجودة العلمية.
          </p>
        </div>

        <!-- 4 Pillars Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          
          <div class="reveal-on-scroll bg-white border border-[#DEE8F7] rounded-[22px] p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col gap-3.5">
            <div class="size-12 rounded-2xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center font-bold text-xl">
              🎯
            </div>
            <h3 class="font-bold text-lg text-[#012383]">مناهج تدريبية معتمدة</h3>
            <p class="text-xs text-[#475569] leading-relaxed">
              مسارات متسلسلة تبدأ من التشخيص الأولي وتمتد إلى جلسات النطق المتقدمة وتعديل السلوك خطوة بخطوة.
            </p>
          </div>

          <div class="reveal-on-scroll bg-white border border-[#DEE8F7] rounded-[22px] p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col gap-3.5" style="transition-delay: 100ms;">
            <div class="size-12 rounded-2xl bg-[#EEF7F3] text-emerald-700 flex items-center justify-center font-bold text-xl">
              🎙️
            </div>
            <h3 class="font-bold text-lg text-[#012383]">مهام صوتية وتقييم مباشر</h3>
            <p class="text-xs text-[#475569] leading-relaxed">
              تطبيق عملي من خلال تسجيل المقاطع الصوتية ومخارج الحروف وتلقي ملاحظات وتوجيهات دقيقة من نخبة المحاضرين.
            </p>
          </div>

          <div class="reveal-on-scroll bg-white border border-[#DEE8F7] rounded-[22px] p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col gap-3.5" style="transition-delay: 200ms;">
            <div class="size-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
              👨‍⚕️
            </div>
            <h3 class="font-bold text-lg text-[#012383]">أخصائيون واستشاريون موثوقون</h3>
            <p class="text-xs text-[#475569] leading-relaxed">
              فريق متكامل من أطباء ومحاضري التخاطب ذوي الخبرات الطويلة في المستشفيات والمراكز التأهيلية الكبرى.
            </p>
          </div>

          <div class="reveal-on-scroll bg-white border border-[#DEE8F7] rounded-[22px] p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col gap-3.5" style="transition-delay: 300ms;">
            <div class="size-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xl">
              📜
            </div>
            <h3 class="font-bold text-lg text-[#012383]">شهادات إتمام موثقة برمز QR</h3>
            <p class="text-xs text-[#475569] leading-relaxed">
              شهادات إنجاز رسمية موثقة برقم مرجعي ورمز استجابة سريعة تثبت كفاءتك وساعاتك التدريبية في التخصص.
            </p>
          </div>

        </div>

        <!-- Numbers & Trust Metrics Banner -->
        <div class="reveal-on-scroll w-full bg-gradient-to-r from-[#012383] to-[#0B2A8F] rounded-[24px] p-8 lg:p-12 text-white shadow-card flex flex-wrap items-center justify-around gap-8">
          
          <div class="flex flex-col items-center text-center">
            <span class="font-['Baloo_Bhaijaan_2'] font-black text-4xl lg:text-5xl text-blue-200">+١,٥٠٠</span>
            <span class="font-['Cairo'] text-sm lg:text-base font-semibold text-blue-100 mt-1">طالب ودارس معتمد</span>
          </div>

          <div class="h-12 w-px bg-white/20 hidden md:block"></div>

          <div class="flex flex-col items-center text-center">
            <span class="font-['Baloo_Bhaijaan_2'] font-black text-4xl lg:text-5xl text-blue-200">+٢٥</span>
            <span class="font-['Cairo'] text-sm lg:text-base font-semibold text-blue-100 mt-1">مسار ودورة تدريبية</span>
          </div>

          <div class="h-12 w-px bg-white/20 hidden md:block"></div>

          <div class="flex flex-col items-center text-center">
            <span class="font-['Baloo_Bhaijaan_2'] font-black text-4xl lg:text-5xl text-emerald-300">٩٨٪</span>
            <span class="font-['Cairo'] text-sm lg:text-base font-semibold text-blue-100 mt-1">نسبة الرضا والتحسن</span>
          </div>

          <div class="h-12 w-px bg-white/20 hidden md:block"></div>

          <div class="flex flex-col items-center text-center">
            <span class="font-['Baloo_Bhaijaan_2'] font-black text-4xl lg:text-5xl text-amber-300">٤.٩ / ٥</span>
            <span class="font-['Cairo'] text-sm lg:text-base font-semibold text-blue-100 mt-1">متوسط تقييم الدورات</span>
          </div>

        </div>

      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 4: #reviews (STUDENT REVIEWS & TESTIMONIALS)                      -->
      <!-- ========================================================================= -->
      <section id="reviews" class="w-full px-4 sm:px-6 lg:px-[48px] py-14 lg:py-20 flex flex-col gap-10 bg-white border-t border-[#DEE8F7] scroll-mt-20">
        
        <!-- Header -->
        <div class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#10B77F]/30 text-[#10B77F] text-xs font-bold">
            <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span>{{ __('reviews.eyebrow') }}</span>
          </div>

          <h2 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight">{{ __('reviews.heroTitle') }}</h2>

          <p class="text-base sm:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed">{{ __('reviews.heroDesc') }}</p>
        </div>

        <!-- Rating Breakdown & Review Action Card -->
        <div class="reveal-on-scroll grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <div class="lg:col-span-2 bg-[#FAFCFF] border border-[#DEE8F7] rounded-[22px] p-6 lg:p-8 shadow-xs flex flex-col md:flex-row gap-8 items-center">
            <div class="flex flex-col items-center justify-center text-center shrink-0 md:pe-8 md:border-e md:border-[#DEE8F7]">
              <span class="font-['Cairo'] font-extrabold text-5xl text-[#012383]">4.9</span>
              <div class="flex items-center gap-1 text-[#F59E0B] text-lg my-1.5">
                ★★★★★
              </div>
              <span class="text-xs font-bold text-[#475569]">{{ __('reviews.statReviewsSub') }}</span>
            </div>

            <div class="flex-1 w-full flex flex-col gap-2.5">
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]">٥ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-[#10B77F] rounded-full" style="width: 88%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">88%</span>
              </div>
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]">٤ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-[#0B2A8F] rounded-full" style="width: 9%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">9%</span>
              </div>
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]">٣ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-amber-400 rounded-full" style="width: 2%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">2%</span>
              </div>
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]">نجمتان</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-slate-400 rounded-full" style="width: 1%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">1%</span>
              </div>
            </div>
          </div>

          <div class="bg-gradient-to-br from-[#012383] to-[#0B2A8F] rounded-[22px] p-6 lg:p-8 text-white flex flex-col justify-between shadow-card relative overflow-hidden">
            <div class="flex flex-col gap-2 relative z-10">
              <span class="text-xs font-bold text-blue-200 uppercase tracking-wider">{{ __('reviews.eyebrow') }}</span>
              <h3 class="font-['Cairo'] font-bold text-2xl">{{ __('reviews.addReviewCardTitle') }}</h3>
              <p class="text-xs text-blue-100 font-medium leading-relaxed">{{ __('reviews.addReviewCardDesc') }}</p>
            </div>

            <button
              type="button"
              id="open-review-modal-btn"
              class="mt-6 w-full py-3 px-4 bg-white text-[#012383] font-bold text-sm rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 shadow-xs cursor-pointer"
            >
              <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              <span>{{ __('reviews.addReviewBtn') }}</span>
            </button>
          </div>

        </div>

        <!-- Reviews Cards Grid (3 Columns) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          
          <div class="reveal-on-scroll bg-[#FAFCFF] border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[20px] p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between gap-4">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-1 text-[#F59E0B] text-sm">★★★★★</div>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">طالب معتمد ✓</span>
              </div>
              <h4 class="font-bold text-[#012383] text-base">تطوّر ملحوظ في التدريب السريري على مخارج الحروف</h4>
              <p class="text-xs text-[#475569] leading-relaxed">
                "الكورس ساعدني جداً كطالب في استيعاب التمارين الحركية النطقية الصحيحة. بعد ٣ أسابيع من الالتزام بتسجيل المهام الصوتية وتلقي توجيهات المحاضر، اكتسبت خبرة سريرية ممتازة."
              </p>
            </div>
            <div class="flex items-center gap-3 pt-3 border-t border-[#DEE8F7]">
              <div class="size-9 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-xs text-[#0B2A8F]">
                أ
              </div>
              <div class="flex flex-col">
                <span class="text-xs font-bold text-[#012383]">أحمد الخالدي</span>
                <span class="text-[11px] text-[#475569]">طالب علوم التخاطب • كورس مخارج الحروف</span>
              </div>
            </div>
          </div>

          <div class="reveal-on-scroll bg-[#FAFCFF] border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[20px] p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between gap-4" style="transition-delay: 150ms;">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-1 text-[#F59E0B] text-sm">★★★★★</div>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">أخصائي معتمد ✓</span>
              </div>
              <h4 class="font-bold text-[#012383] text-base">محتوى سريري تطبيقي راقٍ جداً</h4>
              <p class="text-xs text-[#475569] leading-relaxed">
                "بصفتي باحث وممارس نطق مبتدئ، الكورس قدّم لي خططاً تدريبية سريرية واضحة وجاهزة للتطبيق. ميزة تسجيل المهام الصوتية وتلقي تقييم المحاضر وفرت علي شهوراً من التجربة."
              </p>
            </div>
            <div class="flex items-center gap-3 pt-3 border-t border-[#DEE8F7]">
              <div class="size-9 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-xs text-[#0B2A8F]">
                ط
              </div>
              <div class="flex flex-col">
                <span class="text-xs font-bold text-[#012383]">طارق العمري</span>
                <span class="text-[11px] text-[#475569]">باحث في اضطرابات التواصل • استراتيجيات تنمية اللغة</span>
              </div>
            </div>
          </div>

          <div class="reveal-on-scroll bg-[#FAFCFF] border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[20px] p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between gap-4" style="transition-delay: 300ms;">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-1 text-[#F59E0B] text-sm">★★★★★</div>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">طالب معتمد ✓</span>
              </div>
              <h4 class="font-bold text-[#012383] text-base">سهولة المشاهدة والمرونة الأكاديمية العالية</h4>
              <p class="text-xs text-[#475569] leading-relaxed">
                "أفضل منصة تعليمية عربية لطلاب التخاطب! الدروس مقسمة لوحدات مركزة، واستطعت إنهاء الكورس والحصول على الشهادة الرسمية المعتمَدة بكل سلاسة."
              </p>
            </div>
            <div class="flex items-center gap-3 pt-3 border-t border-[#DEE8F7]">
              <div class="size-9 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-xs text-[#0B2A8F]">
                ن
              </div>
              <div class="flex flex-col">
                <span class="text-xs font-bold text-[#012383]">ناصر السبيعي</span>
                <span class="text-[11px] text-[#475569]">طالب دراسات عليا • أساسيات التخاطب واضطرابات التواصل</span>
              </div>
            </div>
          </div>

        </div>

      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 5: #contact (SUPPORT & DIRECT CONTACT)                            -->
      <!-- ========================================================================= -->
      <section id="contact" class="w-full px-4 sm:px-6 lg:px-[48px] py-14 lg:py-20 flex flex-col gap-12 bg-[#FAFCFF] border-t border-[#D7E3F5] scroll-mt-20">
        
        <!-- Header -->
        <div class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto">
          <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#10B77F]/30 text-[#10B77F] text-xs font-bold shadow-xs">
            <span class="relative flex size-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full size-2.5 bg-emerald-500"></span>
            </span>
            <span>{{ __('contact.statusOnline') }}</span>
            <span class="text-emerald-300">•</span>
            <span class="text-[#475569] font-medium">{{ __('contact.responseTime') }}</span>
          </div>

          <h2 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight">{{ __('contact.heroTitle') }}</h2>

          <p class="text-base sm:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed">{{ __('contact.heroDesc') }}</p>
        </div>

        <!-- 2 Direct Support Channels (Email & WhatsApp Only) -->
        <div class="reveal-on-scroll grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto w-full">
          
          <!-- Channel 1: Email -->
          <div class="bg-white border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[22px] p-6 sm:p-8 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div class="flex flex-col gap-3">
              <div class="size-14 rounded-2xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center font-bold text-2xl">
                ✉️
              </div>
              <h3 class="font-bold text-xl text-[#012383]">{{ __('contact.channelTechTitle') }}</h3>
              <p class="text-xs sm:text-sm text-[#475569] leading-relaxed">
                تواصل مباشر عبر البريد الإلكتروني للاستفسارات الأكاديمية عن الكورس ومتابعة حسابك وشهادتك.
              </p>
            </div>
            <div class="mt-6 pt-4 border-t border-[#DEE8F7] flex items-center justify-between">
              <span class="text-sm font-bold text-[#0B2A8F] dir-ltr">support@alameen.edu.sa</span>
              <a href="mailto:support@alameen.edu.sa" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#EAF1FB] hover:bg-[#DEE8F7] text-xs font-bold text-[#012383] hover:text-[#0B2A8F] transition-all">
                <span>مراسلة عبر البريد</span>
                <span class="text-xs">←</span>
              </a>
            </div>
          </div>

          <!-- Channel 2: WhatsApp -->
          <div class="bg-white border border-[#DEE8F7] hover:border-[#10B77F] rounded-[22px] p-6 sm:p-8 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" style="transition-delay: 150ms;">
            <div class="flex flex-col gap-3">
              <div class="size-14 rounded-2xl bg-[#EEF7F3] text-[#10B77F] flex items-center justify-center font-bold text-2xl">
                💬
              </div>
              <h3 class="font-bold text-xl text-[#012383]">{{ __('contact.channelWhatsAppTitle') }}</h3>
              <p class="text-xs sm:text-sm text-[#475569] leading-relaxed">
                تواصل فوري وسريع مع فريق خدمة الطلاب للرد على استفساراتك وتأكيد التسجيل في كورس التخاطب.
              </p>
            </div>
            <div class="mt-6 pt-4 border-t border-[#DEE8F7] flex items-center justify-between">
              <span class="text-sm font-bold text-[#10B77F] dir-ltr">+966 50 123 4567</span>
              <a href="https://wa.me/966501234567" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#EEF7F3] hover:bg-[#DEE8F7] text-xs font-bold text-emerald-700 hover:text-emerald-800 transition-all">
                <span>فتح محادثة واتساب</span>
                <span class="text-xs">←</span>
              </a>
            </div>
          </div>

        </div>

        <!-- Contact Form & Info Split -->
        <div class="reveal-on-scroll grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          
          <!-- Contact Form (8 Cols) -->
          <div class="lg:col-span-8 bg-white border border-[#DEE8F7] rounded-[22px] p-6 sm:p-10 shadow-xs flex flex-col gap-6">
            <div>
              <h3 class="font-['Cairo'] font-bold text-2xl sm:text-3xl text-[#012383]">{{ __('contact.formHeading') }}</h3>
              <p class="text-xs sm:text-sm text-[#475569] font-medium mt-1">{{ __('contact.formSubheading') }}</p>
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-xs font-bold text-[#012383]">موضوع الاستفسار</label>
              <div class="flex flex-wrap gap-2" id="topic-pills">
                <button
                  type="button"
                  data-topic="general"
                  class="topic-pill active px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer"
                >{{ __('contact.topicGeneral') }}</button>
                <button
                  type="button"
                  data-topic="technical"
                  class="topic-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
                >{{ __('contact.topicTechnical') }}</button>
                <button
                  type="button"
                  data-topic="certificate"
                  class="topic-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
                >{{ __('contact.topicCertificate') }}</button>
              </div>
            </div>

            <form id="contact-form" class="flex flex-col gap-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]">{{ __('contact.labelName') }}</label>
                  <input
                    type="text"
                    required
                    placeholder="مثال: أحمد محمد"
                    placeholder="{{ __('contact.placeholderName') }}"class="h-12 bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/20 rounded-xl px-4 text-xs text-[#012383] outline-none transition-all"
                  />
                </div>
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]">{{ __('contact.labelEmail') }}</label>
                  <input
                    type="email"
                    required
                    placeholder="name@example.com"
                    placeholder="{{ __('contact.placeholderEmail') }}"class="h-12 bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/20 rounded-xl px-4 text-xs text-[#012383] outline-none transition-all"
                  />
                </div>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-[#012383]">{{ __('contact.labelMessage') }}</label>
                <textarea
                  required
                  rows="4"
                  placeholder="اكتب استفسارك هنا بكل وضوح..."
                  placeholder="{{ __('contact.placeholderMessage') }}"class="bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/20 rounded-xl p-4 text-xs text-[#012383] outline-none resize-none transition-all"
                ></textarea>
              </div>

              <button
                type="submit"
                class="mt-2 h-13 bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-sm rounded-xl transition-all shadow-sm hover:shadow-md cursor-pointer flex items-center justify-center gap-2"
              >{{ __('contact.btnSubmit') }}</button>
            </form>
          </div>

          <!-- Info Companion (4 Cols) -->
          <div class="lg:col-span-4 flex flex-col gap-6">
            <div class="bg-white border border-[#DEE8F7] rounded-[22px] p-6 shadow-xs flex flex-col gap-5">
              <h4 class="font-bold text-[#012383] text-base">ساعات العمل والرد</h4>
              
              <div class="flex items-start gap-3">
                <div class="size-8 rounded-xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center text-sm shrink-0">
                  🕒
                </div>
                <div class="flex flex-col text-xs">
                  <span class="font-bold text-[#012383]">الأحد - الخميس</span>
                  <span class="text-[#475569] mt-0.5">من ٩:٠٠ صباحاً حتى ٦:٠٠ مساءً</span>
                </div>
              </div>

              <div class="flex items-start gap-3">
                <div class="size-8 rounded-xl bg-[#EEF7F3] text-emerald-700 flex items-center justify-center text-sm shrink-0">
                  📍
                </div>
                <div class="flex flex-col text-xs">
                  <span class="font-bold text-[#012383]">المقر الرئيسي</span>
                  <span class="text-[#475569] mt-0.5">المملكة العربية السعودية • الرياض</span>
                </div>
              </div>
            </div>
          </div>

        </div>

      </section>

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
              <a href="#home" class="flex gap-3 items-center group w-fit">
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
                  <a href="#courses" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                    <span class="text-blue-400">›</span>
                    محاور كورس التخاطب الشامل
                  </a>
                </li>
                <li>
                  <a href="#courses" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                    <span class="text-blue-400">›</span>
                    استراتيجيات تنمية اللغة والتواصل
                  </a>
                </li>
                <li>
                  <a href="#courses" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                    <span class="text-blue-400">›</span>
                    تطبيقات مخارج الحروف والأصوات
                  </a>
                </li>
                <li>
                  <a href="#courses" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                    <span class="text-blue-400">›</span>
                    التدريب السريري لاضطرابات النطق
                  </a>
                </li>
                <li>
                  <a href="#about" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
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
              <span>•</span>
              <a href="{{ route('admin.login') }}" class="text-[#F2AE2E] hover:underline font-bold">بوابة الإدارة</a>
            </div>

          </div>

        </div>

      </footer>

    </div>

    <!-- Review Modal Dialog -->
    <div
      id="review-modal"
      class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all"
    >
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl flex flex-col gap-5 relative border border-[#DEE8F7]">
        <button
          type="button"
          id="close-review-modal-btn"
          class="absolute top-5 end-5 size-8 rounded-full bg-[#EAF1FB] text-[#012383] flex items-center justify-center hover:bg-[#DEE8F7] transition-colors cursor-pointer"
        >
          ✕
        </button>
        <div>
          <h3 class="font-bold text-xl text-[#012383]">شاركنا تجربتك وتقييمك</h3>
          <p class="text-xs text-[#475569] mt-1">تقييمك يساعد زملاءك الطلاب في اختيار مسارهم التعليمي.</p>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs font-bold text-[#012383]">التقييم العام</label>
          <div class="flex items-center gap-2 text-2xl text-amber-400 cursor-pointer" id="modal-star-group">
            <span class="modal-star" data-value="1">★</span>
            <span class="modal-star" data-value="2">★</span>
            <span class="modal-star" data-value="3">★</span>
            <span class="modal-star" data-value="4">★</span>
            <span class="modal-star" data-value="5">★</span>
          </div>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-bold text-[#012383]">عنوان التقييم</label>
          <input
            type="text"
            id="modal-review-title"
            placeholder="{{ __('مثال: دورة ممتازة وتطبيق عملي رائع') }}"
            class="h-11 bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 text-xs text-[#012383] outline-none"
          />
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-bold text-[#012383]">{{ __('تفاصيل تجربتك') }}</label>
          <textarea
            id="modal-review-text"
            rows="3"
            placeholder="{{ __('اكتب كيف ساعدتك الدورة في النطق أو التطبيق السريري...') }}"
            class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl p-3 text-xs text-[#012383] outline-none resize-none"
          ></textarea>
        </div>
        <div class="flex items-center justify-end gap-3 pt-2">
          <button
            type="button"
            id="cancel-review-modal-btn"
            class="px-5 py-2.5 rounded-xl border border-[#DEE8F7] text-xs font-bold text-[#475569] hover:bg-slate-50 transition-colors cursor-pointer"
          >
            إلغاء
          </button>
          <button
            type="button"
            id="submit-review-modal-btn"
            class="px-6 py-2.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold transition-all shadow-xs cursor-pointer"
          >
            نشر التقييم
          </button>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script type="module" src="/src/js/landing.js"></script>
    <script type="module" src="/src/js/animations.js"></script>
  </body>
</html>

