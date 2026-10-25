<x-layouts::app :title="__('نتيجة التقييم')">
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

</x-layouts::app>
