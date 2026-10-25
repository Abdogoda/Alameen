<x-layouts::app :title="__('شهادة الإتمام')">
    <!-- Page Body Content (Node 1155:19229) -->
        <div class="px-4 sm:px-6 lg:px-10 py-6 md:py-8 flex-1 flex flex-col gap-6" data-node-id="1155:19229">
          
          <!-- Page Introduction (Node 1155:19230) -->
          <div data-node-id="1155:19230">
            <p class="text-[13px] md:text-[14px] text-[#0B2A8F] font-semibold mb-1 font-['Cairo']" data-node-id="1155:19231" data-i18n="certificate.pageEyebrow">
              شهاداتك وكورساتك
            </p>
            <h1 class="text-[28px] sm:text-[32px] md:text-[36px] font-extrabold text-[#012383] font-['Cairo'] leading-[1.3] mb-1.5" data-node-id="1155:19232" data-i18n="certificate.pageTitle">
              شهادة الإتمام
            </h1>
            <p class="text-[14px] md:text-[15px] text-[#475569] font-medium font-['Cairo']" data-node-id="1155:19233" data-i18n="certificate.pageDesc">
              عرض وتنزيل شهادة إتمام كورس استراتيجيات تنمية اللغة
            </p>
          </div>

          <!-- Toolbar (Node 1155:19234 - Actions & Status) -->
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4" data-node-id="1155:19234">
            <!-- Buttons Group (Node 1155:19235) -->
            <div class="flex flex-wrap items-center gap-3">
              <!-- Primary CTA: Download PDF (Node 1155:19236) -->
              <button
                type="button"
                onclick="window.print()"
                class="h-[44px] px-5 rounded-[10px] bg-[#0B2A8F] hover:bg-[#012383] text-white text-[14px] font-bold flex items-center gap-2 shadow-sm transition-all cursor-pointer font-['Cairo']"
                data-node-id="1155:19236"
              >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span data-i18n="certificate.downloadPdf">تنزيل الشهادة PDF</span>
              </button>

              <!-- Secondary Button: Back to Certificates (Node 1155:19251) -->
              <a
                href="{{ route('certificates.index') }}"
                class="h-[44px] px-5 rounded-[10px] bg-white border border-[#DEE8F7] hover:bg-[#FAFCFF] text-[#0B2A8F] text-[14px] font-bold flex items-center gap-2 transition-all font-['Cairo']"
                data-node-id="1155:19251"
              >
                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span data-i18n="certificate.backToCertificates">العودة إلى شهاداتي</span>
              </a>
            </div>

            <!-- Status Badge (Node 1155:19266) -->
            <div class="px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#D1EADB] flex items-center gap-1.5" data-node-id="1155:19266">
              <span class="size-2 rounded-full bg-[#216350]"></span>
              <span class="text-[12px] font-bold text-[#216350] font-['Cairo']" data-node-id="1155:19267" data-i18n="certificate.statusBadge">
                مؤهلة عند إكمال ٩٥٪ ✦
              </span>
            </div>
          </div>

          <!-- Pixel-Perfect Certificate Card (Node 1155:19268) -->
          <div class="w-full bg-white border border-[#CFDBEA] rounded-[14px] p-4 sm:p-[20px] relative overflow-hidden shadow-lg" id="printable-certificate" data-node-id="1155:19268">
            <!-- Certificate Brand Edge Ribbon (Node 1155:19269 - 14px Navy bar) -->
            <div class="absolute start-0 top-0 bottom-0 w-[12px] md:w-[14px] bg-[#012383]" data-node-id="1155:19269"></div>

            <!-- Subtle Decorative Radial Arc Motifs (Nodes 1155:19270 & 1155:19271) -->
            <div class="absolute -start-20 -top-20 size-[260px] rounded-full pointer-events-none arc-motif"></div>
            <div class="absolute -end-16 -bottom-16 size-[200px] rounded-full pointer-events-none arc-motif"></div>

            <!-- Certificate Inner Border Box (Node 1155:19272) -->
            <div class="border border-[#DEE8F7] rounded-[10px] px-4 sm:px-8 md:px-12 py-8 flex flex-col items-center text-center relative z-10 bg-white/95" data-node-id="1155:19272">
              
              <!-- Issuer Header (Logo + Brand Name - Node 1155:19273) -->
              <div class="flex items-center gap-3 mb-2" data-node-id="1155:19273">
                <span class="text-[24px] sm:text-[28px] font-extrabold text-[#012383] font-['Cairo']" data-node-id="1155:19274" data-i18n="certificate.issuerName">
                  منصة مركز الأمين
                </span>
                <div class="size-[48px] sm:size-[54px] shrink-0 relative" data-node-id="1155:19275">
                  <img src="/assets/images/logo.png" alt="منصة مركز الأمين" class="size-full object-contain pointer-events-none" />
                </div>
              </div>

              <!-- Main Title (Node 1155:19276) -->
              <h2 class="text-[32px] sm:text-[38px] md:text-[42px] font-extrabold text-[#012383] font-['Cairo'] mb-1 tracking-tight" data-node-id="1155:19276" data-i18n="certificate.certHeading">
                شهادة إتمام
              </h2>

              <!-- Subtitle (Node 1155:19277) -->
              <p class="text-[14px] sm:text-[15px] text-[#475569] font-medium mb-1.5 font-['Cairo']" data-node-id="1155:19277" data-i18n="certificate.certPresented">
                تُمنح هذه الشهادة من منصة مركز الأمين إلى
              </p>

              <!-- Student Full Name (Node 1155:19278) -->
              <div class="my-2 flex flex-col items-center">
                <p class="text-[36px] sm:text-[42px] md:text-[48px] font-extrabold text-[#012383] font-['Cairo'] leading-[1.2]" data-node-id="1155:19278" data-i18n="certificate.studentName">
                  أحمد إبراهيم
                </p>
                <div class="w-[180px] h-[2px] bg-[#CFDBEA] mt-1 mb-2" data-node-id="1155:19279"></div>
              </div>

              <!-- Course Qualification Text (Node 1155:19280) -->
              <p class="text-[14px] sm:text-[16px] text-[#475569] font-medium mb-1 font-['Cairo']" data-node-id="1155:19280" data-i18n="certificate.certFor">
                لإتمامه المتطلبات الخاصة بمشاهدة الكورس التعليمي
              </p>

              <!-- Course Title (Node 1155:19281) -->
              <p class="text-[24px] sm:text-[28px] md:text-[32px] font-bold text-[#0B2A8F] font-['Cairo'] mb-2" data-node-id="1155:19281" data-i18n="certificate.courseTitle">
                استراتيجيات تنمية اللغة
              </p>

              <!-- Completion Stat (Node 1155:19282) -->
              <p class="text-[15px] sm:text-[17px] font-bold text-[#012383] mb-6 font-['Cairo']" data-node-id="1155:19282" data-i18n="certificate.certCompletionStat">
                بنسبة إكمال ٩٥٪ • ١٩ من ٢٠ فيديو
              </p>

              <!-- Certificate Record - Completion Date Only (User requested: خلي تاريخ التمام بس) -->
              <div class="w-full pt-6 flex items-center justify-start px-6" data-node-id="1155:19287">
                <div class="flex flex-col text-start">
                  <span class="text-[13px] text-[#475569] font-medium mb-0.5 font-['Cairo']" data-node-id="1155:19288" data-i18n="certificate.dateLabel">
                    تاريخ الإتمام
                  </span>
                  <span class="text-[16px] font-bold text-[#012383] font-['Cairo']" data-node-id="1155:19289" data-i18n="certificate.dateValue">
                    ٢٣ أكتوبر ٢٠٢٦
                  </span>
                </div>
              </div>

            </div>
          </div>

          <!-- Information Note Card (Node 1155:19291) -->
          <div class="w-full p-4 rounded-[10px] bg-[#FAFCFF] border border-[#DEE8F7] flex items-start gap-3.5" data-node-id="1155:19291">
            <div class="size-9 rounded-full bg-[#EAF1FB] flex items-center justify-center text-[#0B2A8F] shrink-0">
              <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <div data-node-id="1155:19292">
              <p class="text-[13px] font-bold text-[#012383] mb-0.5 font-['Cairo']" data-node-id="1155:19293" data-i18n="certificate.infoTitle">
                عن هذه الشهادة
              </p>
              <p class="text-[12px] text-[#475569] leading-relaxed font-['Cairo']" data-node-id="1155:19294" data-i18n="certificate.infoDesc">
                توثق شهادة الإتمام بلوغ ٩٥٪ من مشاهدة الدورة. درجات المهام منفصلة ولا تظهر كتقدير مهني أو ترخيص للممارسة.
              </p>
            </div>
          </div>

        </div>

</x-layouts::app>
