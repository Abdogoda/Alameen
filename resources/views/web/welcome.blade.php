<x-layouts::marketing active="home">
@php
    $heroBlock = \App\Models\ContentBlock::where('section_key', 'hero')->first();
    $faqBlock = \App\Models\ContentBlock::where('section_key', 'faq')->first();
    $cmsSettings = app(\App\Services\Cms\SettingsService::class);
@endphp
<!-- SECTION 1: #home (HERO SLIDER SHOWCASE)                                   -->
      <!-- ========================================================================= -->
      <section id="home" class="w-full min-h-[calc(100vh-80px)] flex flex-col justify-between px-4 sm:px-8 lg:px-16 xl:px-24 2xl:px-32 py-5 sm:py-6 lg:py-8 relative overflow-hidden bg-gradient-to-b from-[#FAFCFF] to-white dark:from-[#0B1120] dark:to-[#0F172A] scroll-mt-20">
        
        <!-- Ambient Glowing Background Blobs -->
        <div class="absolute -top-20 start-1/4 size-[400px] rounded-full bg-blue-100/35 dark:bg-blue-600/10 blur-3xl pointer-events-none animate-ambient-glow -z-10"></div>
        <div class="absolute -bottom-10 end-12 size-[350px] rounded-full bg-indigo-50/45 dark:bg-indigo-500/10 blur-3xl pointer-events-none animate-float-reverse -z-10"></div>

        <!-- Hero Slider Canvas (Grid Stacking for Ultra-Smooth Transitions) -->
        <div class="hero-slider-canvas w-full flex-1 relative" id="hero-slider">
          
          <!-- SLIDE 1: مسارات التخاطب الشاملة (Default Active) -->
          <div class="hero-slide is-active w-full max-w-[1536px] mx-auto flex flex-col lg:flex-row items-center justify-between gap-6 sm:gap-8 lg:gap-12" data-slide="0">
            
            <!-- Slide 1 Text -->
            <div class="w-full lg:max-w-[620px] xl:max-w-[680px] flex flex-col items-center lg:items-start text-center lg:text-start shrink-0 order-1 gap-3.5 sm:gap-5 lg:gap-[20px]">
              
              <div class="slide-eyebrow inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF1FB] border border-[#0B2A8F]/20 text-[#0B2A8F] text-xs sm:text-sm font-extrabold shadow-xs">
                <span class="size-2 rounded-full bg-[#0B2A8F] animate-pulse"></span>
                <span>{{ __('landing.slide1Eyebrow') }}</span>
              </div>

              <div class="flex flex-col gap-2.5 sm:gap-4 items-center lg:items-start w-full">
                <h1 class="slide-title font-['Baloo_Bhaijaan_2'] font-bold text-[26px] xs:text-[30px] sm:text-[44px] lg:text-[56px] xl:text-[60px] leading-[1.2] text-[#012383] w-full tracking-tight">
                    {{ $heroBlock?->title ?: __('landing.slide1Title') }}
                </h1>

                <p class="slide-desc font-['Cairo'] font-semibold text-[14px] sm:text-[17px] lg:text-[20px] leading-[1.7] text-[#1E293B] dark:text-slate-300 w-full max-w-[580px]">
                    {{ $heroBlock?->subtitle ?: __('landing.slide1Desc') }}
                </p>
              </div>

              <div class="slide-ctas flex flex-wrap sm:flex-nowrap items-center justify-center lg:justify-start gap-2.5 sm:gap-3 pt-1 sm:pt-2 w-full sm:w-auto">
                <a
                  href="#courses"
                  class="w-full sm:w-auto px-6 sm:px-8 h-[46px] sm:h-[56px] bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center rounded-[14px] sm:rounded-[16px] text-white font-['Cairo'] font-extrabold text-[15px] sm:text-[18px] shadow-md hover:shadow-lg transition-all cursor-pointer whitespace-nowrap"
                >{{ __('landing.slide1Cta1') }}</a>
                <a
                  href="#about"
                  class="w-full sm:w-auto px-5 sm:px-6 h-[46px] sm:h-[56px] bg-white hover:bg-[#EAF1FB] text-[#012383] border border-[#DEE8F7] flex items-center justify-center rounded-[14px] sm:rounded-[16px] font-['Cairo'] font-bold text-[14px] sm:text-[16px] transition-colors cursor-pointer whitespace-nowrap shadow-xs"
                >{{ __('landing.slide1Cta2') }}</a>
              </div>

            </div>

            <!-- Slide 1 Photo Grid Collage -->
            <div class="slide-visual w-full max-w-[300px] xs:max-w-[340px] sm:max-w-[420px] lg:max-w-[480px] aspect-square shrink-0 order-2 mx-auto lg:mx-0 relative" dir="ltr">
              <div class="grid grid-cols-2 gap-2.5 sm:gap-[16px] size-full">
                
                <div class="bg-[#DCE8F2] overflow-hidden rounded-full aspect-square w-full relative shadow-sm hover:scale-[1.02] transition-transform">
                  <img src="/assets/images/photo1.png" alt="التدريب العملي" class="size-full object-cover pointer-events-none" />
                </div>

                <div class="bg-[#152D4D] overflow-hidden rounded-[16px] sm:rounded-[20px] aspect-square w-full relative shadow-sm group hover:scale-[1.02] transition-transform cursor-pointer">
                  <img src="/assets/images/photo2.png" alt="تنمية المهارات" class="size-full object-cover pointer-events-none" />
                  <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 size-[42px] sm:size-[56px] rounded-full group-hover:scale-110 transition-transform animate-pulse-glow">
                    <img src="/assets/images/play.svg" alt="تشغيل" class="size-full" />
                  </div>
                </div>

                <div class="bg-[#EDF2F7] overflow-hidden rounded-[16px] sm:rounded-[20px] aspect-square w-full relative shadow-sm group hover:scale-[1.02] transition-transform cursor-pointer">
                  <img src="/assets/images/photo3.png" alt="الجلسات التفاعلية" class="size-full object-cover pointer-events-none" />
                  <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 size-[42px] sm:size-[56px] rounded-full group-hover:scale-110 transition-transform animate-pulse-glow">
                    <img src="/assets/images/play.svg" alt="تشغيل" class="size-full" />
                  </div>
                </div>

                <div class="bg-[#203C60] overflow-hidden rounded-tl-[16px] rounded-bl-[16px] sm:rounded-tl-[20px] sm:rounded-bl-[20px] rounded-tr-[50%] rounded-br-[50%] aspect-square w-full relative shadow-sm hover:scale-[1.02] transition-transform">
                  <div class="absolute left-[10px] sm:left-[16px] top-[50px] sm:top-[80px] z-0 flex flex-col gap-1 pointer-events-none opacity-85">
                    <svg class="w-[22px] h-[22px] sm:w-[32px] sm:h-[32px] -rotate-12" viewBox="0 0 32 32" fill="none">
                      <path d="M4 16C4 7 13 4 28 4C28 19 19 28 4 16Z" fill="#7E95B1"/>
                    </svg>
                    <svg class="w-[22px] h-[22px] sm:w-[32px] sm:h-[32px] rotate-45 -mt-2" viewBox="0 0 32 32" fill="none">
                      <path d="M4 16C4 7 13 4 28 4C28 19 19 28 4 16Z" fill="#7E95B1"/>
                    </svg>
                  </div>
                  <img src="/assets/images/photo4.png" alt="الاعتماد الأكاديمي" class="size-full object-cover pointer-events-none relative z-10" />
                </div>

              </div>

              <!-- Floating Trust Badge with Automatic Gentle Floating Animation -->
              <div class="animate-float-slow absolute -bottom-3 start-2 sm:-bottom-4 sm:start-4 bg-white/95 backdrop-blur-md border border-[#DEE8F7] px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl shadow-card flex items-center gap-2 sm:gap-2.5 z-20">
                <div class="size-7 sm:size-8 rounded-full bg-[#EEF7F3] flex items-center justify-center text-emerald-700 font-bold text-xs shadow-xs">
                  ✓
                </div>
                <div class="flex flex-col text-start">
                  <span class="text-[11px] sm:text-xs font-bold text-[#012383]">{{ __('landing.statsStudents') }}</span>
                  <span class="text-[10px] sm:text-[11px] text-[#475569]">{{ __('landing.statsSub') }}</span>
                </div>
              </div>
            </div>

          </div>

          <!-- SLIDE 2: تطبيقات عملية وفيديوهات تفاعلية -->
          <div class="hero-slide w-full max-w-[1536px] mx-auto flex flex-col lg:flex-row items-center justify-between gap-6 sm:gap-8 lg:gap-12" data-slide="1">
            
            <!-- Slide 2 Text -->
            <div class="w-full lg:max-w-[620px] xl:max-w-[680px] flex flex-col items-center lg:items-start text-center lg:text-start shrink-0 order-1 gap-3.5 sm:gap-5 lg:gap-[20px]">
              
              <div class="slide-eyebrow inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#10B77F]/30 text-[#10B77F] text-xs sm:text-sm font-extrabold shadow-xs">
                <span class="size-2 rounded-full bg-[#10B77F] animate-ping"></span>
                <span>{{ __('landing.slide2Eyebrow') }}</span>
              </div>

              <div class="flex flex-col gap-2.5 sm:gap-4 items-center lg:items-start w-full">
                <h2 class="slide-title font-['Baloo_Bhaijaan_2'] font-bold text-[26px] xs:text-[30px] sm:text-[44px] lg:text-[56px] xl:text-[60px] leading-[1.2] text-[#012383] w-full tracking-tight">{{ __('landing.slide2Title') }}</h2>

                <p class="slide-desc font-['Cairo'] font-semibold text-[14px] sm:text-[17px] lg:text-[20px] leading-[1.7] text-[#1E293B] dark:text-slate-300 w-full max-w-[580px]">{{ __('landing.slide2Desc') }}</p>
              </div>

              <div class="slide-ctas flex flex-wrap sm:flex-nowrap items-center justify-center lg:justify-start gap-2.5 sm:gap-3 pt-1 sm:pt-2 w-full sm:w-auto">
                <a
                  href="#courses"
                  class="w-full sm:w-auto px-6 sm:px-8 h-[46px] sm:h-[56px] bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center rounded-[14px] sm:rounded-[16px] text-white font-['Cairo'] font-extrabold text-[15px] sm:text-[18px] shadow-md hover:shadow-lg transition-all cursor-pointer whitespace-nowrap"
                >{{ __('landing.slide2Cta1') }}</a>
                <a
                  href="./pages/lesson.html"
                  class="w-full sm:w-auto px-5 sm:px-6 h-[46px] sm:h-[56px] bg-white hover:bg-[#EAF1FB] text-[#012383] border border-[#DEE8F7] flex items-center justify-center rounded-[14px] sm:rounded-[16px] font-['Cairo'] font-bold text-[14px] sm:text-[16px] transition-colors cursor-pointer whitespace-nowrap shadow-xs"
                >{{ __('landing.slide2Cta2') }}</a>
              </div>

            </div>

            <!-- Slide 2 Video & Audio Training Showcase -->
            <div class="slide-visual w-full max-w-[320px] xs:max-w-[360px] sm:max-w-[440px] lg:max-w-[500px] shrink-0 order-2 mx-auto lg:mx-0 relative flex flex-col gap-3 sm:gap-4">
              
              <!-- Video Player Card -->
              <div class="bg-white border-2 border-[#DEE8F7] rounded-[20px] sm:rounded-[24px] p-2.5 sm:p-3 shadow-card relative overflow-hidden group">
                <div class="h-[190px] sm:h-[260px] w-full rounded-[16px] sm:rounded-[18px] bg-[#0B2A8F] relative overflow-hidden flex items-center justify-center">
                  <img src="/assets/images/lesson-video-frame.png" alt="فيديو الدرس" class="size-full object-cover pointer-events-none group-hover:scale-105 transition-transform duration-500" />
                  
                  <a href="./pages/lesson.html" class="absolute size-12 sm:size-16 rounded-full bg-white/95 text-[#012383] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform animate-pulse-glow">
                    <svg class="size-5 sm:size-6 text-[#012383] translate-x-0.5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z" />
                    </svg>
                  </a>

                  <div class="absolute bottom-2.5 end-2.5 sm:bottom-3 sm:end-3 px-2.5 sm:px-3 py-1 rounded-full bg-black/70 backdrop-blur-md text-white text-[11px] sm:text-xs font-bold font-['Cairo'] tabular-nums">
                    ١٤:٣٠ دقيقة
                  </div>

                  <div class="absolute top-2.5 start-2.5 sm:top-3 sm:start-3 px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full bg-[#10B77F] text-white text-[11px] sm:text-xs font-bold font-['Cairo'] shadow-sm flex items-center gap-1.5">
                    <span class="size-1.5 rounded-full bg-white animate-ping"></span>
                    <span>تطبيق عملي</span>
                  </div>
                </div>
              </div>

              <!-- Overlaid Voice Task Pill with Animated Dynamic Audio Waveform -->
              <div class="animate-float-slow bg-white border border-[#DEE8F7] rounded-xl sm:rounded-2xl p-2.5 sm:p-4 shadow-card flex items-center justify-between gap-2 sm:gap-3">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                  <div class="size-8 sm:size-10 rounded-xl bg-[#EEF7F3] text-emerald-700 flex items-center justify-center font-bold text-base sm:text-lg shrink-0">
                    🎙️
                  </div>
                  <div class="flex flex-col text-start min-w-0">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                      <span class="text-[11px] sm:text-xs font-bold text-[#012383] truncate">مهمة صوتية: تصحيح مخارج الحروف</span>
                      <div class="hidden xs:flex items-center gap-0.5 h-3.5 sm:h-4 px-1 bg-emerald-50 rounded-md shrink-0">
                        <span class="w-0.5 bg-[#10B77F] rounded-full voice-wave-bar"></span>
                        <span class="w-0.5 bg-[#10B77F] rounded-full voice-wave-bar"></span>
                        <span class="w-0.5 bg-[#10B77F] rounded-full voice-wave-bar"></span>
                        <span class="w-0.5 bg-[#10B77F] rounded-full voice-wave-bar"></span>
                      </div>
                    </div>
                    <span class="text-[10px] sm:text-[11px] text-emerald-600 font-semibold truncate">تم التقييم بنجاح • ٩٥٪ مستوى متميز</span>
                  </div>
                </div>
                <span class="px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full bg-[#EAF1FB] text-[#0B2A8F] text-[10px] sm:text-[11px] font-bold shrink-0">
                  مُقيّمة
                </span>
              </div>

            </div>

          </div>

          <!-- SLIDE 3: تقييم مستمر وشهادات معتمدة -->
          <div class="hero-slide w-full max-w-[1536px] mx-auto flex flex-col lg:flex-row items-center justify-between gap-6 sm:gap-8 lg:gap-12" data-slide="2">
            
            <!-- Slide 3 Text -->
            <div class="w-full lg:max-w-[620px] xl:max-w-[680px] flex flex-col items-center lg:items-start text-center lg:text-start shrink-0 order-1 gap-3.5 sm:gap-5 lg:gap-[20px]">
              
              <div class="slide-eyebrow inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF1FB] border border-[#0B2A8F]/20 text-[#0B2A8F] text-xs sm:text-sm font-extrabold shadow-xs">
                <span class="size-2 rounded-full bg-[#0B2A8F] animate-pulse"></span>
                <span>{{ __('landing.slide3Eyebrow') }}</span>
              </div>

              <div class="flex flex-col gap-2.5 sm:gap-4 items-center lg:items-start w-full">
                <h2 class="slide-title font-['Baloo_Bhaijaan_2'] font-bold text-[26px] xs:text-[30px] sm:text-[44px] lg:text-[56px] xl:text-[60px] leading-[1.2] text-[#012383] w-full tracking-tight">{{ __('landing.slide3Title') }}</h2>

                <p class="slide-desc font-['Cairo'] font-semibold text-[14px] sm:text-[17px] lg:text-[20px] leading-[1.7] text-[#1E293B] dark:text-slate-300 w-full max-w-[580px]">{{ __('landing.slide3Desc') }}</p>
              </div>

              <div class="slide-ctas flex flex-wrap sm:flex-nowrap items-center justify-center lg:justify-start gap-2.5 sm:gap-3 pt-1 sm:pt-2 w-full sm:w-auto">
                <a
                  href="{{ route('register') }}"
                  class="w-full sm:w-auto px-6 sm:px-8 h-[46px] sm:h-[56px] bg-[#012383] hover:bg-[#0B2A8F] flex items-center justify-center rounded-[14px] sm:rounded-[16px] text-white font-['Cairo'] font-extrabold text-[15px] sm:text-[18px] shadow-md hover:shadow-lg transition-all cursor-pointer whitespace-nowrap"
                >{{ __('landing.slide3Cta1') }}</a>
                <a
                  href="./pages/certificate.html"
                  class="w-full sm:w-auto px-5 sm:px-6 h-[46px] sm:h-[56px] bg-white hover:bg-[#EAF1FB] text-[#012383] border border-[#DEE8F7] flex items-center justify-center rounded-[14px] sm:rounded-[16px] font-['Cairo'] font-bold text-[14px] sm:text-[16px] transition-colors cursor-pointer whitespace-nowrap shadow-xs"
                >{{ __('landing.slide3Cta2') }}</a>
              </div>

            </div>

            <!-- Slide 3 Certificate Showcase -->
            <div class="slide-visual w-full max-w-[320px] xs:max-w-[360px] sm:max-w-[440px] lg:max-w-[500px] shrink-0 order-2 mx-auto lg:mx-0 relative flex flex-col gap-3 sm:gap-4">
              
              <div class="animate-float-slow bg-white border-2 border-[#DEE8F7] rounded-[20px] sm:rounded-[24px] p-2.5 sm:p-3 shadow-card relative overflow-hidden group hover:scale-[1.02] transition-transform">
                <div class="h-[190px] sm:h-[280px] w-full rounded-[16px] sm:rounded-[18px] overflow-hidden relative bg-slate-50 flex items-center justify-center">
                  <img src="/assets/images/certificate-preview.png" alt="معاينة الشهادة" class="size-full object-cover pointer-events-none" />
                  
                  <div class="absolute inset-0 bg-gradient-to-t from-[#012383]/80 via-transparent to-transparent flex flex-col justify-end p-4 sm:p-5 text-white">
                    <span class="text-[11px] sm:text-xs font-bold text-blue-200">شهادة معتمدة برقم توثيق رسمي</span>
                    <h4 class="text-sm sm:text-base font-bold font-['Cairo']">أساسيات التخاطب وتطبيقات النطق</h4>
                  </div>
                </div>
              </div>

              <div class="animate-float-reverse bg-white border border-[#DEE8F7] rounded-xl sm:rounded-2xl p-2.5 sm:p-4 shadow-card flex items-center justify-between gap-2 sm:gap-3">
                <div class="flex items-center gap-2 sm:gap-3">
                  <div class="size-8 sm:size-10 rounded-xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center font-bold text-base sm:text-lg shrink-0">
                    🎓
                  </div>
                  <div class="flex flex-col text-start">
                    <span class="text-[11px] sm:text-xs font-bold text-[#012383]">نسبة إنجاز الدورة: ٩٥٪</span>
                    <span class="text-[10px] sm:text-[11px] text-emerald-600 font-semibold">مؤهل لإصدار وتحميل الشهادة</span>
                  </div>
                </div>
                <a href="./pages/certificate.html" class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-xl bg-[#012383] text-white text-[11px] sm:text-xs font-bold hover:bg-[#0B2A8F] transition-colors shrink-0">
                  استعراض
                </a>
              </div>

            </div>

          </div>

          <!-- Slider Chevrons & Dots Navigation -->
          <div class="w-full max-w-[1536px] mx-auto flex items-center justify-between pt-4 lg:pt-6 z-20">
            
            <div class="flex items-center gap-2">
              <button
                type="button"
                id="slider-prev"
                aria-label="السابق"
                class="size-9 sm:size-10 rounded-full bg-white border border-[#DEE8F7] text-[#012383] hover:bg-[#EAF1FB] hover:border-[#0B2A8F]/40 flex items-center justify-center transition-all shadow-xs hover:shadow-md cursor-pointer"
              >
                <svg class="size-4 sm:size-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              <button
                type="button"
                id="slider-next"
                aria-label="التالي"
                class="size-9 sm:size-10 rounded-full bg-white border border-[#DEE8F7] text-[#012383] hover:bg-[#EAF1FB] hover:border-[#0B2A8F]/40 flex items-center justify-center transition-all shadow-xs hover:shadow-md cursor-pointer"
              >
                <svg class="size-4 sm:size-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
              <button type="button" class="slider-dot h-2 sm:h-2.5 rounded-full transition-all duration-300 w-6 sm:w-8 bg-[#012383] cursor-pointer" aria-label="الشريحة 1"></button>
              <button type="button" class="slider-dot h-2 sm:h-2.5 rounded-full transition-all duration-300 w-2 sm:w-2.5 bg-[#CBD5E1] hover:bg-[#94A3B8] cursor-pointer" aria-label="الشريحة 2"></button>
              <button type="button" class="slider-dot h-2 sm:h-2.5 rounded-full transition-all duration-300 w-2 sm:w-2.5 bg-[#CBD5E1] hover:bg-[#94A3B8] cursor-pointer" aria-label="الشريحة 3"></button>
            </div>

          </div>

        </div>

        <!-- Learning Journey Footer Tabs ( رحلتك مع الأمين ) -->
        <div class="w-full max-w-[1536px] mx-auto border-t border-[#DEE8F7] py-3 lg:h-[72px] flex flex-col sm:flex-row items-center justify-between shrink-0 z-20 gap-3 bg-white/80 backdrop-blur-xs mt-6 rounded-2xl px-3 sm:px-6 shadow-xs">
          
          <p class="font-['Cairo'] font-extrabold text-[15px] sm:text-[18px] lg:text-[20px] text-[#0B2A8F] whitespace-nowrap self-start sm:self-center">{{ __('landing.journeyTitle') }}</p>

          <div class="flex items-center gap-2 overflow-x-auto max-w-full pb-1 sm:pb-0 w-full sm:w-auto no-scrollbar">
            <button
              type="button"
              data-step-target="0"
              class="step-tab flex gap-2 items-center px-3 sm:px-3.5 py-1.5 rounded-full transition-all cursor-pointer bg-[#EAF1FB] border border-[#DEE8F7] shadow-xs shrink-0"
            >
              <div class="step-dot size-2 rounded-full bg-[#0B2A8F] scale-125 transition-transform"></div>
              <span class="step-text font-['Cairo'] font-black text-[13px] sm:text-[15px] lg:text-[17px] text-[#0B2A8F] whitespace-nowrap">{{ __('landing.step1') }}</span>
            </button>

            <button
              type="button"
              data-step-target="1"
              class="step-tab flex gap-2 items-center px-3 sm:px-3.5 py-1.5 rounded-full transition-all cursor-pointer hover:bg-slate-100/60 shrink-0"
            >
              <div class="step-dot size-2 rounded-full bg-slate-400 transition-transform"></div>
              <span class="step-text font-['Cairo'] font-semibold text-[13px] sm:text-[15px] lg:text-[17px] text-[#475569] whitespace-nowrap">{{ __('landing.step2') }}</span>
            </button>

            <button
              type="button"
              data-step-target="2"
              class="step-tab flex gap-2 items-center px-3 sm:px-3.5 py-1.5 rounded-full transition-all cursor-pointer hover:bg-slate-100/60 shrink-0"
            >
              <div class="step-dot size-2 rounded-full bg-slate-400 transition-transform"></div>
              <span class="step-text font-['Cairo'] font-semibold text-[13px] sm:text-[15px] lg:text-[17px] text-[#475569] whitespace-nowrap">{{ __('landing.step3') }}</span>
            </button>
          </div>

        </div>

      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 2: #courses (SPECIALIZED COURSES & CATALOG)                        -->
      <!-- ========================================================================= -->
      <section id="courses" class="w-full px-4 sm:px-8 lg:px-16 xl:px-24 2xl:px-32 py-10 sm:py-14 lg:py-20 flex flex-col gap-8 sm:gap-10 bg-white dark:bg-[#0B1120] border-t border-[#DEE8F7] dark:border-slate-800/80 scroll-mt-20">
        
        <!-- Header & Search -->
        <div class="flex flex-col items-center text-center gap-3.5 sm:gap-4 max-w-4xl mx-auto w-full">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF1FB] border border-[#0B2A8F]/20 text-[#0B2A8F] text-xs font-bold">
            <span>{{ __('catalog.eyebrow') }}</span>
          </div>

          <h2 class="font-['Cairo'] font-extrabold text-2xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight">{{ __('catalog.heroTitle') }}</h2>

          <p class="text-sm sm:text-base lg:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed">{{ __('catalog.heroDesc') }}</p>

          <!-- Search Input Bar -->
          <div class="w-full max-w-2xl mt-2 relative">
            <input
              type="text"
              id="course-search-input"
              placeholder="{{ __('catalog.searchPlaceholder') }}"
              class="w-full h-12 sm:h-13 bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/20 rounded-2xl px-11 sm:px-12 text-sm text-[#012383] placeholder-[#475569] outline-none shadow-sm transition-all"
            />
            <svg class="size-5 text-[#0B2A8F] absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>

        <!-- Filter Categories & Counter -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5 sm:gap-3 bg-[#FAFCFF] border border-[#DEE8F7] rounded-[18px] p-3 shadow-xs">
          <div class="flex flex-wrap items-center gap-2" id="catalog-category-group">
            <button
              type="button"
              data-cat="all"
              class="catalog-filter active px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer shadow-xs"
            >
              كورس التخاطب
            </button>
          </div>

          <span class="text-xs font-bold text-[#0B2A8F] bg-[#EAF1FB] px-3.5 py-1.5 rounded-full border border-[#DEE8F7] text-center sm:text-start self-start sm:self-auto">
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
                <img src="/assets/images/course-topic-basics.png" alt="أساسيات التخاطب" class="size-full object-cover" />
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
      <section id="about" class="w-full px-4 sm:px-8 lg:px-16 xl:px-24 2xl:px-32 py-10 sm:py-14 lg:py-20 flex flex-col gap-10 sm:gap-12 bg-[#FAFCFF] dark:bg-[#0B1120] border-t border-[#DEE8F7] dark:border-slate-800/80 scroll-mt-20">
        
        <!-- Header -->
        <div class="flex flex-col items-center text-center gap-3.5 sm:gap-4 max-w-4xl mx-auto">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF1FB] border border-[#0B2A8F]/20 text-[#0B2A8F] text-xs font-bold">
            <span>{{ __('landing.navAbout') }}</span>
          </div>

          <h2 class="font-['Baloo_Bhaijaan_2'] font-extrabold text-2xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight">
            منصة مركز الأمين.. شريكك الأول في تأهيل التخاطب والنطق
          </h2>

          <p class="text-sm sm:text-base lg:text-lg text-[#475569] max-w-3xl font-medium leading-relaxed">
            منصة تعليمية متخصصة تجمع بين المعرفة الأكاديمية والتطبيق السريري لتمكين طلاب التخاطب والدارسين من إتقان مهارات التشخيص والعلاج بأعلى معايير الجودة العلمية.
          </p>
        </div>

        <!-- 4 Pillars Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
          
          <div class="reveal-on-scroll bg-white border border-[#DEE8F7] rounded-[22px] p-5 sm:p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col gap-3.5">
            <div class="size-12 rounded-2xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center font-bold text-xl">
              🎯
            </div>
            <h3 class="font-bold text-base sm:text-lg text-[#012383]">مناهج تدريبية معتمدة</h3>
            <p class="text-xs text-[#475569] leading-relaxed">
              مسارات متسلسلة تبدأ من التشخيص الأولي وتمتد إلى جلسات النطق المتقدمة وتعديل السلوك خطوة بخطوة.
            </p>
          </div>

          <div class="reveal-on-scroll bg-white border border-[#DEE8F7] rounded-[22px] p-5 sm:p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col gap-3.5" style="transition-delay: 100ms;">
            <div class="size-12 rounded-2xl bg-[#EEF7F3] text-emerald-700 flex items-center justify-center font-bold text-xl">
              🎙️
            </div>
            <h3 class="font-bold text-base sm:text-lg text-[#012383]">مهام صوتية وتقييم مباشر</h3>
            <p class="text-xs text-[#475569] leading-relaxed">
              تطبيق عملي من خلال تسجيل المقاطع الصوتية ومخارج الحروف وتلقي ملاحظات وتوجيهات دقيقة من نخبة المحاضرين.
            </p>
          </div>

          <div class="reveal-on-scroll bg-white border border-[#DEE8F7] rounded-[22px] p-5 sm:p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col gap-3.5" style="transition-delay: 200ms;">
            <div class="size-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
              👨‍⚕️
            </div>
            <h3 class="font-bold text-base sm:text-lg text-[#012383]">أخصائيون واستشاريون موثوقون</h3>
            <p class="text-xs text-[#475569] leading-relaxed">
              فريق متكامل من أطباء ومحاضري التخاطب ذوي الخبرات الطويلة في المستشفيات والمراكز التأهيلية الكبرى.
            </p>
          </div>

          <div class="reveal-on-scroll bg-white border border-[#DEE8F7] rounded-[22px] p-5 sm:p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col gap-3.5" style="transition-delay: 300ms;">
            <div class="size-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xl">
              📜
            </div>
            <h3 class="font-bold text-base sm:text-lg text-[#012383]">شهادات إتمام موثقة برمز QR</h3>
            <p class="text-xs text-[#475569] leading-relaxed">
              شهادات إنجاز رسمية موثقة برقم مرجعي ورمز استجابة سريعة تثبت كفاءتك وساعاتك التدريبية في التخصص.
            </p>
          </div>

        </div>

        <!-- Numbers & Trust Metrics Banner -->
        <div class="reveal-on-scroll w-full bg-gradient-to-r from-[#012383] to-[#0B2A8F] rounded-[24px] p-6 sm:p-8 lg:p-12 text-white shadow-card grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
          
          <div class="flex flex-col items-center text-center">
            <span class="font-['Baloo_Bhaijaan_2'] font-black text-3xl sm:text-4xl lg:text-5xl text-blue-200">+١,٥٠٠</span>
            <span class="font-['Cairo'] text-xs sm:text-sm lg:text-base font-semibold text-blue-100 mt-1">طالب ودارس معتمد</span>
          </div>

          <div class="flex flex-col items-center text-center">
            <span class="font-['Baloo_Bhaijaan_2'] font-black text-3xl sm:text-4xl lg:text-5xl text-blue-200">+٢٥</span>
            <span class="font-['Cairo'] text-xs sm:text-sm lg:text-base font-semibold text-blue-100 mt-1">مسار ودورة تدريبية</span>
          </div>

          <div class="flex flex-col items-center text-center">
            <span class="font-['Baloo_Bhaijaan_2'] font-black text-3xl sm:text-4xl lg:text-5xl text-emerald-300">٩٨٪</span>
            <span class="font-['Cairo'] text-xs sm:text-sm lg:text-base font-semibold text-blue-100 mt-1">نسبة الرضا والتحسن</span>
          </div>

          <div class="flex flex-col items-center text-center">
            <span class="font-['Baloo_Bhaijaan_2'] font-black text-3xl sm:text-4xl lg:text-5xl text-amber-300">٤.٩ / ٥</span>
            <span class="font-['Cairo'] text-xs sm:text-sm lg:text-base font-semibold text-blue-100 mt-1">متوسط تقييم الدورات</span>
          </div>

        </div>

      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 4: #reviews (STUDENT REVIEWS & TESTIMONIALS)                      -->
      <!-- ========================================================================= -->
      <section id="reviews" class="w-full px-4 sm:px-8 lg:px-16 xl:px-24 2xl:px-32 py-10 sm:py-14 lg:py-20 flex flex-col gap-8 sm:gap-10 bg-white dark:bg-[#0B1120] border-t border-[#DEE8F7] dark:border-slate-800/80 scroll-mt-20">
        
        <!-- Header -->
        <div class="flex flex-col items-center text-center gap-3.5 sm:gap-4 max-w-4xl mx-auto">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#10B77F]/30 text-[#10B77F] text-xs font-bold">
            <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span>{{ __('reviews.eyebrow') }}</span>
          </div>

          <h2 class="font-['Cairo'] font-extrabold text-2xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight">{{ __('reviews.heroTitle') }}</h2>

          <p class="text-sm sm:text-base lg:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed">{{ __('reviews.heroDesc') }}</p>
        </div>

        <!-- Rating Breakdown & Review Action Card -->
        <div class="reveal-on-scroll grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <div class="lg:col-span-2 bg-[#FAFCFF] border border-[#DEE8F7] rounded-[22px] p-5 sm:p-6 lg:p-8 shadow-xs flex flex-col md:flex-row gap-6 sm:gap-8 items-center">
            <div class="flex flex-col items-center justify-center text-center shrink-0 md:pe-8 md:border-e md:border-[#DEE8F7]">
              <span class="font-['Cairo'] font-extrabold text-4xl sm:text-5xl text-[#012383]">4.9</span>
              <div class="flex items-center gap-1 text-[#F59E0B] text-base sm:text-lg my-1.5">
                ★★★★★
              </div>
              <span class="text-xs font-bold text-[#475569]">{{ __('reviews.statReviewsSub') }}</span>
            </div>

            <div class="flex-1 w-full flex flex-col gap-2.5">
              <div class="flex items-center gap-2.5 sm:gap-3 text-xs font-bold">
                <span class="w-12 sm:w-14 text-end text-[#012383]">٥ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-[#10B77F] rounded-full" style="width: 88%"></div>
                </div>
                <span class="w-8 sm:w-10 text-start text-[#475569]">88%</span>
              </div>
              <div class="flex items-center gap-2.5 sm:gap-3 text-xs font-bold">
                <span class="w-12 sm:w-14 text-end text-[#012383]">٤ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-[#0B2A8F] rounded-full" style="width: 9%"></div>
                </div>
                <span class="w-8 sm:w-10 text-start text-[#475569]">9%</span>
              </div>
              <div class="flex items-center gap-2.5 sm:gap-3 text-xs font-bold">
                <span class="w-12 sm:w-14 text-end text-[#012383]">٣ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-amber-400 rounded-full" style="width: 2%"></div>
                </div>
                <span class="w-8 sm:w-10 text-start text-[#475569]">2%</span>
              </div>
              <div class="flex items-center gap-2.5 sm:gap-3 text-xs font-bold">
                <span class="w-12 sm:w-14 text-end text-[#012383]">نجمتان</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-slate-400 rounded-full" style="width: 1%"></div>
                </div>
                <span class="w-8 sm:w-10 text-start text-[#475569]">1%</span>
              </div>
            </div>
          </div>

          <div class="bg-gradient-to-br from-[#012383] to-[#0B2A8F] rounded-[22px] p-5 sm:p-6 lg:p-8 text-white flex flex-col justify-between shadow-card relative overflow-hidden">
            <div class="flex flex-col gap-2 relative z-10">
              <span class="text-xs font-bold text-blue-200 uppercase tracking-wider">{{ __('reviews.eyebrow') }}</span>
              <h3 class="font-['Cairo'] font-bold text-xl sm:text-2xl">{{ __('reviews.addReviewCardTitle') }}</h3>
              <p class="text-xs text-blue-100 font-medium leading-relaxed">{{ __('reviews.addReviewCardDesc') }}</p>
            </div>

            <button
              type="button"
              id="open-review-modal-btn"
              class="mt-6 w-full py-3 px-4 bg-white !text-[#012383] font-bold text-sm rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 shadow-xs cursor-pointer"
              style="color: #012383 !important;"
            >
              <svg class="size-4 !text-[#012383]" style="color: #012383 !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              <span data-i18n="reviews.addReviewBtn" class="!text-[#012383]" style="color: #012383 !important;">{{ __('reviews.addReviewBtn') }}</span>
            </button>
          </div>

        </div>

        <!-- Reviews Cards Grid (3 Columns) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          
          <div class="reveal-on-scroll bg-[#FAFCFF] border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[20px] p-5 sm:p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between gap-4">
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

          <div class="reveal-on-scroll bg-[#FAFCFF] border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[20px] p-5 sm:p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between gap-4" style="transition-delay: 150ms;">
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

          <div class="reveal-on-scroll bg-[#FAFCFF] border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[20px] p-5 sm:p-6 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between gap-4" style="transition-delay: 300ms;">
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
      <section id="contact" class="w-full px-4 sm:px-8 lg:px-16 xl:px-24 2xl:px-32 py-10 sm:py-14 lg:py-20 flex flex-col gap-10 sm:gap-12 bg-[#FAFCFF] dark:bg-[#0B1120] border-t border-[#D7E3F5] dark:border-slate-800/80 scroll-mt-20">
        
        <!-- Header -->
        <div class="flex flex-col items-center text-center gap-3.5 sm:gap-4 max-w-4xl mx-auto">
          <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#10B77F]/30 text-[#10B77F] text-xs font-bold shadow-xs">
            <span class="relative flex size-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full size-2.5 bg-emerald-500"></span>
            </span>
            <span>{{ __('contact.statusOnline') }}</span>
            <span class="text-emerald-300">•</span>
            <span class="text-[#475569] font-medium">{{ __('contact.responseTime') }}</span>
          </div>

          <h2 class="font-['Cairo'] font-extrabold text-2xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight">{{ __('contact.heroTitle') }}</h2>

          <p class="text-sm sm:text-base lg:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed">{{ __('contact.heroDesc') }}</p>
        </div>

        <!-- 2 Direct Support Channels (Email & WhatsApp Only) -->
        <div class="reveal-on-scroll grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 max-w-4xl mx-auto w-full">
          
          <!-- Channel 1: Email -->
          <div class="bg-white border border-[#DEE8F7] hover:border-[#0B2A8F] rounded-[22px] p-5 sm:p-8 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div class="flex flex-col gap-3">
              <div class="size-12 sm:size-14 rounded-2xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center font-bold text-xl sm:text-2xl">
                ✉️
              </div>
              <h3 class="font-bold text-lg sm:text-xl text-[#012383]">{{ __('contact.channelTechTitle') }}</h3>
              <p class="text-xs sm:text-sm text-[#475569] leading-relaxed">
                {{ __('contact.channelTechDesc') }}
              </p>
            </div>
            <div class="mt-5 sm:mt-6 pt-4 border-t border-[#DEE8F7] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <span class="text-xs sm:text-sm font-bold text-[#0B2A8F] dir-ltr break-all">support@alameen.edu.sa</span>
              <a href="mailto:support@alameen.edu.sa" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 rounded-xl bg-[#EAF1FB] hover:bg-[#DEE8F7] text-xs font-bold text-[#012383] hover:text-[#0B2A8F] transition-all">
                <span>{{ __('contact.channelTechBtn') }}</span>
                <span class="text-xs">←</span>
              </a>
            </div>
          </div>

          <!-- Channel 2: WhatsApp -->
          <div class="bg-white border border-[#DEE8F7] hover:border-[#10B77F] rounded-[22px] p-5 sm:p-8 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" style="transition-delay: 150ms;">
            <div class="flex flex-col gap-3">
              <div class="size-12 sm:size-14 rounded-2xl bg-[#EEF7F3] text-[#10B77F] flex items-center justify-center font-bold text-xl sm:text-2xl">
                💬
              </div>
              <h3 class="font-bold text-lg sm:text-xl text-[#012383]">{{ __('contact.channelWhatsAppTitle') }}</h3>
              <p class="text-xs sm:text-sm text-[#475569] leading-relaxed">
                {{ __('contact.channelWhatsAppDesc') }}
              </p>
            </div>
            <div class="mt-5 sm:mt-6 pt-4 border-t border-[#DEE8F7] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <span class="text-xs sm:text-sm font-bold text-[#10B77F] dir-ltr">+966 50 123 4567</span>
              <a href="https://wa.me/966501234567" target="_blank" rel="noopener" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 rounded-xl bg-[#EEF7F3] hover:bg-[#DEE8F7] text-xs font-bold text-emerald-700 hover:text-emerald-800 transition-all">
                <span>{{ __('contact.channelWhatsAppBtn') }}</span>
                <span class="text-xs">←</span>
              </a>
            </div>
          </div>

        </div>

        <!-- Contact Form & Info Split -->
        <div class="reveal-on-scroll grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
          
          <!-- Contact Form (8 Cols) -->
          <div class="lg:col-span-8 bg-white border border-[#DEE8F7] rounded-[22px] p-5 sm:p-8 lg:p-10 shadow-xs flex flex-col gap-5 sm:gap-6">
            <div>
              <h3 class="font-['Cairo'] font-bold text-xl sm:text-2xl lg:text-3xl text-[#012383]">{{ __('contact.formHeading') }}</h3>
              <p class="text-xs sm:text-sm text-[#475569] font-medium mt-1">{{ __('contact.formSubheading') }}</p>
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-xs font-bold text-[#012383]">{{ __('contact.topicLabel') }}</label>
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
                    placeholder="{{ __('contact.placeholderName') }}"
                    class="h-12 bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/20 rounded-xl px-4 text-xs text-[#012383] outline-none transition-all"
                  />
                </div>
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]">{{ __('contact.labelEmail') }}</label>
                  <input
                    type="email"
                    required
                    placeholder="{{ __('contact.placeholderEmail') }}"
                    class="h-12 bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/20 rounded-xl px-4 text-xs text-[#012383] outline-none transition-all"
                  />
                </div>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-[#012383]">{{ __('contact.labelMessage') }}</label>
                <textarea
                  required
                  rows="4"
                  placeholder="{{ __('contact.placeholderMessage') }}"
                  class="bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/20 rounded-xl p-4 text-xs text-[#012383] outline-none resize-none transition-all"
                ></textarea>
              </div>

              <button
                type="submit"
                class="mt-2 h-12 sm:h-13 bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-sm rounded-xl transition-all shadow-sm hover:shadow-md cursor-pointer flex items-center justify-center gap-2"
              >{{ __('contact.btnSubmit') }}</button>
            </form>
          </div>

          <!-- Info Companion (4 Cols) -->
          <div class="lg:col-span-4 flex flex-col gap-6">
            <div class="bg-white border border-[#DEE8F7] rounded-[22px] p-6 shadow-xs flex flex-col gap-5">
              <h4 class="font-bold text-[#012383] text-base">{{ __('contact.workingHoursTitle') }}</h4>
              
              <div class="flex items-start gap-3">
                <div class="size-8 rounded-xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center text-sm shrink-0">
                  🕒
                </div>
                <div class="flex flex-col text-xs">
                  <span class="font-bold text-[#012383]">{{ __('contact.workingDays') }}</span>
                  <span class="text-[#475569] mt-0.5">{{ __('contact.workingHoursTime') }}</span>
                </div>
              </div>

              <div class="flex items-start gap-3">
                <div class="size-8 rounded-xl bg-[#EEF7F3] text-emerald-700 flex items-center justify-center text-sm shrink-0">
                  📍
                </div>
                <div class="flex flex-col text-xs">
                  <span class="font-bold text-[#012383]">{{ __('contact.headquartersTitle') }}</span>
                  <span class="text-[#475569] mt-0.5">{{ __('contact.headquartersAddress') }}</span>
                </div>
              </div>
            </div>
          </div>

      </section>

      <!-- SECTION 7: #faq (DYNAMIC FREQUENTLY ASKED QUESTIONS CMS) -->
      @if($faqBlock && !empty($faqBlock->metadata['items']))
      <section id="faq" class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 lg:px-16 xl:px-24 2xl:px-32 py-12 lg:py-20 flex flex-col items-center gap-10">
        <div class="flex flex-col items-center text-center max-w-2xl gap-3">
          <span class="px-4 py-1.5 rounded-full bg-[#EAF1FB] text-[#0B2A8F] text-xs font-bold border border-[#0B2A8F]/20">
            {{ __('الأسئلة الشائعة') }}
          </span>
          <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-3xl sm:text-4xl text-[#012383]">
            {{ $faqBlock->title ?: __('الأسئلة الأكثر تكراراً') }}
          </h2>
          <p class="text-xs sm:text-sm text-slate-500 font-medium">
            {{ $faqBlock->subtitle ?: __('كل ما تحتاج معرفته عن نظام الكورسات، طرق الدفع فودافون كاش وإنستاباي، والشهادات.') }}
          </p>
        </div>

        <div class="w-full max-w-3xl flex flex-col gap-3" x-data="{ activeAccordion: null }">
          @foreach($faqBlock->metadata['items'] as $index => $item)
            @php
              $q = app()->getLocale() === 'en' && !empty($item['question_en']) ? $item['question_en'] : $item['question_ar'];
              $a = app()->getLocale() === 'en' && !empty($item['answer_en']) ? $item['answer_en'] : $item['answer_ar'];
            @endphp
            <div class="rounded-2xl border border-[#DEE8F7] bg-white overflow-hidden shadow-xs transition-colors">
              <button
                type="button"
                @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}"
                class="w-full p-5 text-start flex items-center justify-between font-bold text-sm text-[#012383] hover:bg-slate-50/70 cursor-pointer"
              >
                <span>{{ $q }}</span>
                <span class="text-lg transition-transform duration-200" :class="activeAccordion === {{ $index }} ? 'rotate-180' : ''">↓</span>
              </button>
              <div
                x-show="activeAccordion === {{ $index }}"
                x-collapse
                class="px-5 pb-5 text-xs text-slate-600 leading-relaxed border-t border-[#DEE8F7]/50 pt-3"
              >
                {{ $a }}
              </div>
            </div>
          @endforeach
        </div>
      </section>
      @endif

      <!-- ============================================== -->

<x-slot:modals>
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
</x-slot:modals>
</x-layouts::marketing>
