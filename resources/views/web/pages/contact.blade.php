<x-layouts::marketing :title="__('تواصل معنا والدعم الفني')" active="contact">
@php
    $cmsSettings = app(\App\Services\Cms\SettingsService::class);
    $contactEmail = $cmsSettings->get('contact_email', 'contact@alameen-speech.com');
    $contactPhone = $cmsSettings->get('contact_phone', '+20 100 000 0000');
    $whatsappUrl = $cmsSettings->get('social_whatsapp', 'https://wa.me/201000000000');
    $contactAddress = $cmsSettings->get('contact_address', 'القاهرة، جمهورية مصر العربية');
    $workingHours = $cmsSettings->get('contact_working_hours', 'السبت - الخميس: 9:00 ص - 9:00 م');
    $supportStatusOnline = $cmsSettings->get('support_status_online', '1') === '1';
@endphp

    <div class="flex-1 w-full max-w-[1280px] mx-auto px-4 sm:px-8 lg:px-12 py-8 lg:py-12 flex flex-col gap-10">

        <!-- 1. HERO SECTION & AVAILABILITY STATUS -->
        <section class="flex flex-col items-center text-center gap-3.5 max-w-3xl mx-auto">
          <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full {{ $supportStatusOnline ? 'bg-[#EEF7F3] border-[#10B77F]/30 text-[#10B77F]' : 'bg-slate-100 border-slate-300 text-slate-600' }} border text-xs font-bold shadow-xs">
            <span class="relative flex size-2.5">
              @if($supportStatusOnline)
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full size-2.5 bg-emerald-500"></span>
              @else
              <span class="relative inline-flex rounded-full size-2.5 bg-slate-400"></span>
              @endif
            </span>
            <span>{{ $supportStatusOnline ? __('contact.statusOnline') : __('الدعم غير متاح حالياً') }}</span>
            <span class="text-emerald-300">•</span>
            <span class="text-[#475569] font-medium">{{ $workingHours }}</span>
          </div>

          <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl text-[#012383] tracking-tight leading-tight">
            {{ __('contact.heroTitle') }}
          </h1>

          <p class="text-sm sm:text-base text-[#475569] max-w-xl font-medium leading-relaxed">
            {{ __('contact.heroDesc') }}
          </p>
        </section>

        <!-- 2. SIMPLIFIED TWO-COLUMN CONTACT WORKFLOW -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start max-w-5xl mx-auto w-full">
          
          <!-- Column 1: Direct Channels & Office Info (5 cols) -->
          <div class="lg:col-span-5 flex flex-col gap-5">
            
            <!-- WhatsApp Primary Card -->
            <div class="bg-gradient-to-br from-[#EEF7F3] to-[#E3F5EC] border border-[#10B77F]/30 rounded-[20px] p-6 shadow-xs flex flex-col gap-4">
              <div class="flex items-center gap-3">
                <div class="size-11 rounded-xl bg-[#10B77F] text-white flex items-center justify-center font-bold text-xl shadow-xs">
                  💬
                </div>
                <div>
                  <h3 class="font-bold text-base text-[#012383]">{{ __('contact.channelWhatsAppTitle') }}</h3>
                  <span class="text-xs text-emerald-800 font-medium">{{ __('تواصل مباشر وسريع') }}</span>
                </div>
              </div>
              <p class="text-xs text-[#475569] leading-relaxed">
                {{ __('contact.channelWhatsAppDesc') }}
              </p>
              <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <span class="text-sm font-bold text-emerald-700 dir-ltr">{{ $contactPhone }}</span>
                <a
                  href="{{ $whatsappUrl }}"
                  target="_blank"
                  rel="noopener"
                  class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#10B77F] hover:bg-[#0D9768] text-white text-xs font-bold transition-all shadow-xs"
                >
                  <span>{{ __('contact.channelWhatsAppBtn') }}</span>
                  <span class="text-xs rtl:rotate-180">←</span>
                </a>
              </div>
            </div>

            <!-- Email & Office Info Card -->
            <div class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col gap-5">
              <h3 class="font-bold text-base text-[#012383]">{{ __('contact.officeTitle') }}</h3>

              <div class="flex flex-col gap-4 text-xs">
                <!-- Location: Egypt Only -->
                <div class="flex items-start gap-3">
                  <div class="size-9 rounded-xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center shrink-0 text-base">
                    📍
                  </div>
                  <div class="flex flex-col">
                    <span class="font-bold text-[#012383]">{{ __('contact.headquartersTitle') }}</span>
                    <span class="text-[#475569] mt-0.5">{{ $contactAddress }}</span>
                  </div>
                </div>

                <!-- Email -->
                <div class="flex items-start gap-3">
                  <div class="size-9 rounded-xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center shrink-0 text-base">
                    ✉️
                  </div>
                  <div class="flex flex-col">
                    <span class="font-bold text-[#012383]">{{ __('contact.channelTechTitle') }}</span>
                    <a href="mailto:{{ $contactEmail }}" class="text-[#0B2A8F] hover:underline dir-ltr text-start mt-0.5 font-semibold">{{ $contactEmail }}</a>
                  </div>
                </div>

                <!-- Working Hours -->
                <div class="flex items-start gap-3 pt-3 border-t border-[#DEE8F7]">
                  <div class="size-9 rounded-xl bg-[#EEF7F3] text-[#10B77F] flex items-center justify-center shrink-0 text-base">
                    ⏰
                  </div>
                  <div class="flex flex-col">
                    <span class="font-bold text-[#012383]">{{ __('contact.workingHoursTitle') }}</span>
                    <span class="text-[#475569] mt-0.5">{{ $workingHours }}</span>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- Column 2: Simplified Contact Form (7 cols) -->
          <div class="lg:col-span-7 bg-white border border-[#DEE8F7] rounded-[20px] p-6 sm:p-8 shadow-xs flex flex-col gap-5">
            <div>
              <h2 class="font-['Cairo'] font-bold text-xl sm:text-2xl text-[#012383]">
                {{ __('contact.formHeading') }}
              </h2>
              <p class="text-xs sm:text-sm text-[#475569] font-medium mt-1">
                {{ __('contact.formSubheading') }}
              </p>
            </div>

            <form id="contact-form" class="flex flex-col gap-4">
              <!-- Name -->
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-[#012383]">{{ __('contact.nameLabel') }} <span class="text-rose-500">*</span></label>
                <input
                  type="text"
                  id="contact-name"
                  required
                  placeholder="{{ __('contact.namePlaceholder') }}"
                  class="bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15 rounded-xl px-4 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none transition-all"
                />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Phone (Egyptian) -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]">{{ __('contact.phoneLabel') }} <span class="text-rose-500">*</span></label>
                  <input
                    type="tel"
                    id="contact-phone"
                    required
                    placeholder="{{ __('contact.phonePlaceholder') }}"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15 rounded-xl px-4 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none transition-all dir-ltr text-end"
                  />
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]">{{ __('contact.emailLabel') }} <span class="text-rose-500">*</span></label>
                  <input
                    type="email"
                    id="contact-email"
                    required
                    placeholder="{{ __('contact.emailPlaceholder') }}"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15 rounded-xl px-4 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none transition-all dir-ltr text-end"
                  />
                </div>
              </div>

              <!-- Message -->
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-[#012383]">{{ __('contact.messageLabel') }} <span class="text-rose-500">*</span></label>
                <textarea
                  id="contact-message"
                  required
                  rows="4"
                  placeholder="{{ __('contact.messagePlaceholder') }}"
                  class="bg-[#FAFCFF] border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15 rounded-xl p-3.5 text-xs text-[#012383] placeholder-slate-400 outline-none transition-all resize-none"
                ></textarea>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                id="submit-contact-btn"
                class="mt-1 w-full sm:w-auto self-start px-8 py-3 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer"
              >
                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                <span>{{ __('contact.sendBtn') }}</span>
              </button>
            </form>
          </div>

        </section>

        <!-- 3. CATEGORIZED FAQ ACCORDION -->
        <section id="faq" class="bg-white border border-[#DEE8F7] rounded-[24px] p-6 sm:p-10 shadow-xs flex flex-col gap-6 max-w-4xl mx-auto w-full">
          
          <div class="text-center max-w-2xl mx-auto flex flex-col gap-2">
            <h2 class="font-['Cairo'] font-bold text-2xl sm:text-3xl text-[#012383]">
              {{ __('contact.faqHeading') }}
            </h2>
            <p class="text-xs sm:text-sm text-[#475569] font-medium">
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
                <span>{{ __('contact.faq1Q') }}</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p>
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
                <span>{{ __('contact.faq2Q') }}</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p>
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
                <span>{{ __('contact.faq3Q') }}</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p>
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
                <span>{{ __('contact.faq4Q') }}</span>
                <span class="faq-icon text-[#0B2A8F] text-base transition-transform">▼</span>
              </button>
              <div class="faq-content hidden px-4 pb-4 pt-1 text-xs text-[#475569] leading-relaxed border-t border-[#DEE8F7]/60">
                <p>
                  {{ __('contact.faq4A') }}
                </p>
              </div>
            </div>

          </div>

        </section>

    </div>

    <x-slot:scripts>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        // Form submission
        const contactForm = document.getElementById('contact-form');
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        contactForm?.addEventListener('submit', (e) => {
          e.preventDefault();
          const submitBtn = document.getElementById('submit-contact-btn');
          const isEn = document.documentElement.lang === 'en';
          if (window.AlameenButtonLoader && submitBtn) {
            window.AlameenButtonLoader.setButtonLoading(submitBtn, true, isEn ? 'Sending...' : 'جاري الإرسال...');
          }

          setTimeout(() => {
            if (toastMessage && toast) {
              toastMessage.textContent = isEn ? 'Your message has been received! We will contact you shortly.' : 'تم استلام رسالتك بنجاح! سيتواصل معك فريق الدعم قريباً.';
              toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
            }
            
            contactForm.reset();
            if (window.AlameenButtonLoader && submitBtn) {
              window.AlameenButtonLoader.setButtonLoading(submitBtn, false);
            }
            setTimeout(() => {
              if (toast) {
                toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
              }
            }, 3500);
          }, 450);
        });

        // FAQ Accordion Toggle
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
