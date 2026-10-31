<x-layouts::marketing :title="__('تواصل معنا والدعم الفني')" active="contact">
    <div class="flex-1 w-full max-w-[1536px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-24 2xl:px-32 py-8 lg:py-12 flex flex-col gap-10">
<!-- 1. HERO SECTION & AVAILABILITY STATUS (WorkDo Hero Style) -->
        <section class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto">
          
          <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#10B77F]/30 text-[#10B77F] text-xs font-bold shadow-xs">
            <span class="relative flex size-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full size-2.5 bg-emerald-500"></span>
            </span>
            <span data-i18n="contact.statusOnline">{{ __('contact.statusOnline') }}</span>
            <span class="text-emerald-300">•</span>
            <span class="text-[#475569] font-medium" data-i18n="contact.responseTime">{{ __('contact.responseTime') }}</span>
          </div>

          <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight" data-i18n="contact.heroTitle">
            {{ __('contact.heroTitle') }}
          </h1>

          <p class="text-base sm:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed" data-i18n="contact.heroDesc">
            {{ __('contact.heroDesc') }}
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
              <h3 class="font-bold text-xl text-[#012383]" data-i18n="contact.channelTechTitle">{{ __('contact.channelTechTitle') }}</h3>
              <p class="text-xs sm:text-sm text-[#475569] leading-relaxed">
                {{ __('contact.channelTechDesc') }}
              </p>
            </div>
            <div class="mt-6 pt-4 border-t border-[#DEE8F7] flex items-center justify-between">
              <span class="text-sm font-bold text-[#0B2A8F] dir-ltr">support@alameen.edu.sa</span>
              <a
                href="mailto:support@alameen.edu.sa"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#EAF1FB] hover:bg-[#DEE8F7] text-xs font-bold text-[#012383] hover:text-[#0B2A8F] transition-all"
                data-i18n="contact.channelTechBtn"
              >
                <span>{{ __('contact.channelTechBtn') }}</span>
                <span class="text-xs rtl:rotate-180">←</span>
              </a>
            </div>
          </div>

          <!-- Channel 2: WhatsApp -->
          <div class="bg-white border border-[#DEE8F7] hover:border-[#10B77F] rounded-[22px] p-6 sm:p-8 shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div class="flex flex-col gap-3">
              <div class="size-14 rounded-2xl bg-[#EEF7F3] text-[#10B77F] flex items-center justify-center font-bold text-2xl">
                💬
              </div>
              <h3 class="font-bold text-xl text-[#012383]" data-i18n="contact.channelWhatsAppTitle">{{ __('contact.channelWhatsAppTitle') }}</h3>
              <p class="text-xs sm:text-sm text-[#475569] leading-relaxed">
                {{ __('contact.channelWhatsAppDesc') }}
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
                <span>{{ __('contact.channelWhatsAppBtn') }}</span>
                <span class="text-xs rtl:rotate-180">←</span>
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
                {{ __('contact.formHeading') }}
              </h2>
              <p class="text-xs sm:text-sm text-[#475569] font-medium mt-1" data-i18n="contact.formSubheading">
                {{ __('contact.formSubheading') }}
              </p>
            </div>

            <!-- Topic Pills Group -->
            <div class="flex flex-col gap-2">
              <label class="text-xs font-bold text-[#012383]">{{ __('contact.topicLabel') }}</label>
              <div class="flex flex-wrap gap-2" id="topic-pills">
                <button
                  type="button"
                  data-topic="general"
                  class="topic-pill active px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer"
                  data-i18n="contact.topicGeneral"
                >
                  {{ __('contact.topicGeneral') }}
                </button>
                <button
                  type="button"
                  data-topic="course"
                  class="topic-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
                  data-i18n="contact.topicCourse"
                >
                  {{ __('contact.topicCourse') }}
                </button>
                <button
                  type="button"
                  data-topic="technical"
                  class="topic-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
                  data-i18n="contact.topicTechnical"
                >
                  {{ __('contact.topicTechnical') }}
                </button>
                <button
                  type="button"
                  data-topic="cert"
                  class="topic-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
                  data-i18n="contact.topicCertificate"
                >
                  {{ __('contact.topicCertificate') }}
                </button>
                <button
                  type="button"
                  data-topic="partnership"
                  class="topic-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
                  data-i18n="contact.topicPartnership"
                >
                  {{ __('contact.topicPartnership') }}
                </button>
              </div>
            </div>

            <!-- Form Fields Grid -->
            <form id="contact-form" class="flex flex-col gap-4">
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Name -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="contact.nameLabel">{{ __('contact.nameLabel') }}</label>
                  <input
                    type="text"
                    id="contact-name"
                    required
                    placeholder="{{ __('contact.namePlaceholder') }}"
                    data-i18n-placeholder="contact.namePlaceholder"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F]"
                  />
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="contact.emailLabel">{{ __('contact.emailLabel') }}</label>
                  <input
                    type="email"
                    id="contact-email"
                    required
                    placeholder="{{ __('contact.emailPlaceholder') }}"
                    data-i18n-placeholder="contact.emailPlaceholder"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F]"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Phone -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="contact.phoneLabel">{{ __('contact.phoneLabel') }}</label>
                  <input
                    type="tel"
                    id="contact-phone"
                    placeholder="{{ __('contact.phonePlaceholder') }}"
                    data-i18n-placeholder="contact.phonePlaceholder"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F]"
                  />
                </div>

                <!-- Subject -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="contact.subjectLabel">{{ __('contact.subjectLabel') }}</label>
                  <input
                    type="text"
                    id="contact-subject"
                    required
                    placeholder="{{ __('contact.subjectPlaceholder') }}"
                    data-i18n-placeholder="contact.subjectPlaceholder"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F]"
                  />
                </div>
              </div>

              <!-- Message Textarea -->
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-[#012383]" data-i18n="contact.messageLabel">{{ __('contact.messageLabel') }}</label>
                <textarea
                  id="contact-message"
                  required
                  rows="4"
                  placeholder="{{ __('contact.messagePlaceholder') }}"
                  data-i18n-placeholder="contact.messagePlaceholder"
                  class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl p-3.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F] resize-none"
                ></textarea>
              </div>

              <!-- Attachment Dropzone -->
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-[#012383]" data-i18n="contact.attachLabel">{{ __('contact.attachLabel') }}</label>
                <div class="border-2 border-dashed border-[#DEE8F7] hover:border-[#0B2A8F] rounded-xl p-4 text-center cursor-pointer transition-colors bg-[#FAFCFF] flex items-center justify-center gap-3">
                  <svg class="size-5 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                  </svg>
                  <span class="text-xs font-semibold text-[#475569]" data-i18n="contact.attachPlaceholder">{{ __('contact.attachPlaceholder') }}</span>
                </div>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                id="submit-contact-btn"
                class="mt-2 w-full sm:w-auto self-start px-8 py-3 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer"
              >
                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                <span data-i18n="contact.sendBtn">{{ __('contact.sendBtn') }}</span>
              </button>

            </form>

          </div>

          <!-- Column 2: Companion Office & Quick Links (4 cols) -->
          <div class="lg:col-span-4 flex flex-col gap-6">
            
            <!-- Headquarters & Hours Card -->
            <div class="bg-white border border-[#DEE8F7] rounded-[18px] p-6 shadow-xs flex flex-col gap-5">
              <h3 class="font-['Cairo'] font-bold text-lg text-[#012383]" data-i18n="contact.officeTitle">
                {{ __('contact.officeTitle') }}
              </h3>

              <div class="flex flex-col gap-3.5 text-xs">
                
                <div class="flex items-start gap-3">
                  <div class="size-8 rounded-lg bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center shrink-0 text-sm">
                    📍
                  </div>
                  <div>
                    <span class="font-bold text-[#012383] block">{{ __('contact.headquartersTitle') }}</span>
                    <span class="text-[#475569]" data-i18n="contact.officeLocationRiyadh">{{ __('contact.officeLocationRiyadh') }}</span>
                  </div>
                </div>

                <div class="flex items-start gap-3">
                  <div class="size-8 rounded-lg bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center shrink-0 text-sm">
                    📍
                  </div>
                  <div>
                    <span class="font-bold text-[#012383] block">{{ __('ui.المركز التقني في مصر') }}</span>
                    <span class="text-[#475569]" data-i18n="contact.officeLocationCairo">{{ __('contact.officeLocationCairo') }}</span>
                  </div>
                </div>

                <div class="flex items-start gap-3 pt-2 border-t border-[#DEE8F7]">
                  <div class="size-8 rounded-lg bg-[#EEF7F3] text-[#10B77F] flex items-center justify-center shrink-0 text-sm">
                    ⏰
                  </div>
                  <div>
                    <span class="font-bold text-[#012383] block">{{ __('contact.workingHoursTitle') }}</span>
                    <span class="text-[#475569]" data-i18n="contact.workingHours">{{ __('contact.workingHours') }}</span>
                  </div>
                </div>

              </div>
            </div>

            <!-- Quick Links Box -->
            <div class="bg-gradient-to-br from-[#FAFCFF] to-[#EAF1FB] border border-[#DEE8F7] rounded-[18px] p-6 shadow-xs flex flex-col gap-4">
              <h3 class="font-bold text-sm text-[#012383]" data-i18n="contact.quickLinksTitle">{{ __('contact.quickLinksTitle') }}</h3>
              
              <ul class="flex flex-col gap-2.5 text-xs font-semibold text-[#0B2A8F]">
                <li>
                  <a href="#faq" class="flex items-center justify-between p-2 rounded-lg bg-white/80 hover:bg-white transition-colors border border-[#DEE8F7]/50">
                    <span data-i18n="contact.linkFaq">{{ __('contact.linkFaq') }}</span>
                    <span class="rtl:rotate-180">←</span>
                  </a>
                </li>
                <li>
                  <a href="{{ route('dashboard') }}" class="flex items-center justify-between p-2 rounded-lg bg-white/80 hover:bg-white transition-colors border border-[#DEE8F7]/50">
                    <span data-i18n="contact.linkGuide">{{ __('contact.linkGuide') }}</span>
                    <span class="rtl:rotate-180">←</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center justify-between p-2 rounded-lg bg-white/80 hover:bg-white transition-colors border border-[#DEE8F7]/50">
                    <span data-i18n="contact.linkPolicy">{{ __('contact.linkPolicy') }}</span>
                    <span class="rtl:rotate-180">←</span>
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
              {{ __('contact.faqHeading') }}
            </h2>
            <p class="text-xs sm:text-sm text-[#475569] font-medium" data-i18n="contact.faqSubheading">
              {{ __('contact.faqSubheading') }}
            </p>
          </div>

          <div class="flex flex-col gap-3 max-w-3xl mx-auto w-full" id="faq-accordion-group">
            
            <!-- FAQ 1 -->
            <div class="faq-item border border-[#DEE8F7] rounded-[16px] overflow-hidden transition-all bg-[#FAFCFF]">
              <button
                type="button"
                class="faq-toggle w-full p-4 text-start font-bold text-xs sm:text-sm text-[#012383] flex items-center justify-between cursor-pointer hover:bg-[#EAF1FB]/50 transition-colors"
              >
                <span data-i18n="contact.faq1Q">{{ __('contact.faq1Q') }}</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p data-i18n="contact.faq1A">
                  {{ __('contact.faq1A') }}
                </p>
              </div>
            </div>

            <!-- FAQ 2 -->
            <div class="faq-item border border-[#DEE8F7] rounded-[16px] overflow-hidden transition-all bg-[#FAFCFF]">
              <button
                type="button"
                class="faq-toggle w-full p-4 text-start font-bold text-xs sm:text-sm text-[#012383] flex items-center justify-between cursor-pointer hover:bg-[#EAF1FB]/50 transition-colors"
              >
                <span data-i18n="contact.faq2Q">{{ __('contact.faq2Q') }}</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p data-i18n="contact.faq2A">
                  {{ __('contact.faq2A') }}
                </p>
              </div>
            </div>

            <!-- FAQ 3 -->
            <div class="faq-item border border-[#DEE8F7] rounded-[16px] overflow-hidden transition-all bg-[#FAFCFF]">
              <button
                type="button"
                class="faq-toggle w-full p-4 text-start font-bold text-xs sm:text-sm text-[#012383] flex items-center justify-between cursor-pointer hover:bg-[#EAF1FB]/50 transition-colors"
              >
                <span data-i18n="contact.faq3Q">{{ __('contact.faq3Q') }}</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p data-i18n="contact.faq3A">
                  {{ __('contact.faq3A') }}
                </p>
              </div>
            </div>

            <!-- FAQ 4 -->
            <div class="faq-item border border-[#DEE8F7] rounded-[16px] overflow-hidden transition-all bg-[#FAFCFF]">
              <button
                type="button"
                class="faq-toggle w-full p-4 text-start font-bold text-xs sm:text-sm text-[#012383] flex items-center justify-between cursor-pointer hover:bg-[#EAF1FB]/50 transition-colors"
              >
                <span data-i18n="contact.faq4Q">{{ __('contact.faq4Q') }}</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p data-i18n="contact.faq4A">
                  {{ __('contact.faq4A') }}
                </p>
              </div>
            </div>

          </div>

        </section>
    </div>

    <x-slot:scripts>
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
          const submitBtn = document.getElementById('submit-contact-btn');
          if (window.AlameenButtonLoader && submitBtn) {
            window.AlameenButtonLoader.setButtonLoading(submitBtn, true, isEn ? 'Sending message...' : 'جاري إرسال الرسالة...');
          }

          setTimeout(() => {
            const isEn = document.documentElement.lang === 'en';
            toastMessage.textContent = isEn ? 'Your inquiry has been received! We will respond shortly.' : 'تم استلام استفسارك بنجاح! سيتواصل معك فريق الدعم قريباً.';
            toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
            
            contactForm.reset();
            if (window.AlameenButtonLoader && submitBtn) {
              window.AlameenButtonLoader.setButtonLoading(submitBtn, false);
            }
            setTimeout(() => {
              toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
            }, 3500);
          }, 450);
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
    </x-slot:scripts>
</x-layouts::marketing>
