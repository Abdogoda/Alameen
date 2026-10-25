<x-admin-layout::admin
    :title="__('سجل الشهادات المعتمدة ومعيار الـ 95%')"
    :description="__('إدارة إصدار الشهادات الرقمية المؤتمتة وفق شرط إكمال مشاهدة ٩٥٪ من فيديوهات الكورس')"
    contentClass="p-4 sm:p-8 flex flex-col gap-6 max-w-[1500px] w-full mx-auto"
>

          
          <!-- Strict 95% Rule Banner -->
          <div class="bg-gradient-to-r from-emerald-900 via-[#0B2A8F] to-[#012383] rounded-3xl p-6 sm:p-7 text-white shadow-card flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
            <div class="space-y-1 text-right">
              <div class="flex items-center gap-2">
                <span class="px-3 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-bold">
                  قاعدة الأمان الأكاديمي الصارمة
                </span>
                <span class="text-xs text-white/70">• إكمال ٩٥٪ كحد أدنى</span>
              </div>
              <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-white">
                شرط إتمام المشاهدة الإلزامي (Strict 95% Video Completion Gate)
              </h3>
              <p class="text-xs sm:text-sm text-white/80 max-w-2xl leading-relaxed">
                المنصة مبرمجة برمجياً بحيث يُقفل رابط إصدار الشهادة ولا يستطيع أي طالب استخراجها إلا إذا بلغت نسبة مشاهدته الفعلية المسجلة في قاعدة البيانات ٩٥٪ فما فوق (١٩ من ٢٠ فيديو على الأقل).
              </p>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-center shrink-0 w-full sm:w-auto">
              <span class="text-xs text-white/70 block">الشهادات المصدرة حتى الآن</span>
              <span class="text-3xl font-black text-white block mt-0.5">٢٤ شهادة</span>
              <span class="text-[11px] text-emerald-300 block">جميعها تحققت برمجياً ✦</span>
            </div>
          </div>

          <!-- Certificates Table -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl overflow-hidden shadow-xs">
            <div class="p-5 border-b border-[#DEE8F7] flex items-center justify-between">
              <div>
                <h4 class="font-bold text-base text-[#012383]">سجل الطلاب المؤهلين والحاصلين على الشهادة</h4>
                <p class="text-xs text-slate-400">تحقق من بيانات الاعتماد، كود التوثيق، وتاريخ التخرج</p>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-right border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-50/80 border-b border-[#DEE8F7] text-[12px] font-bold text-slate-500">
                    <th class="py-4 px-5">اسم الخريج / الطالب</th>
                    <th class="py-4 px-4">رقم الاعتماد التسلسلي</th>
                    <th class="py-4 px-4 text-center">نسبة المشاهدة الفعلية</th>
                    <th class="py-4 px-4 text-center">معدل المهام السريرية</th>
                    <th class="py-4 px-4">تاريخ التخرج</th>
                    <th class="py-4 px-5 text-center">إجراءات الشهادة</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-[#DEE8F7]/70">
                  
                  <!-- Student 1 -->
                  <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">مروان خالد العتيبي</div>
                      <div class="text-[11px] text-slate-400">marwan.otaibi@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-mono text-xs font-bold text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-1 rounded-md">ALM-2026-SP-0489</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        ٩٥٪ (١٩ فيديو)
                      </span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="font-bold text-sm text-[#012383]">٩٤ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4">
                      <span class="text-slate-600 font-medium">٢٠ أكتوبر ٢٠٢٦</span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="openModal('modal-preview-cert')" class="px-3 py-1.5 rounded-xl bg-[#012383] text-white hover:bg-[#0B2A8F] font-bold text-xs transition-colors shadow-2xs">
                          معاينة الشهادة
                        </button>
                        <button type="button" onclick="showToast('طباعة الشهادة', 'جاري إعداد نسخة PDF عالية الجودة للطباعة', 'info')" class="px-3 py-1.5 rounded-xl border border-[#DEE8F7] text-[#0B2A8F] hover:bg-[#EAF1FB] font-bold text-xs transition-colors">
                          تنزيل PDF ⭳
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Student 2 -->
                  <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">نورة سعد الغامدي</div>
                      <div class="text-[11px] text-slate-400">noura.ghamdi@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-mono text-xs font-bold text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-1 rounded-md">ALM-2026-SP-0490</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        ١٠٠٪ (٢٠ فيديو)
                      </span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="font-bold text-sm text-[#012383]">٩٨ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4">
                      <span class="text-slate-600 font-medium">١٨ أكتوبر ٢٠٢٦</span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="openModal('modal-preview-cert')" class="px-3 py-1.5 rounded-xl bg-[#012383] text-white hover:bg-[#0B2A8F] font-bold text-xs transition-colors shadow-2xs">
                          معاينة الشهادة
                        </button>
                        <button type="button" onclick="showToast('طباعة الشهادة', 'جاري إعداد نسخة PDF عالية الجودة للطباعة', 'info')" class="px-3 py-1.5 rounded-xl border border-[#DEE8F7] text-[#0B2A8F] hover:bg-[#EAF1FB] font-bold text-xs transition-colors">
                          تنزيل PDF ⭳
                        </button>
                      </div>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>

        </div>

    <x-slot:modals>
        <!-- Modal: Preview Certificate -->
        <div id="modal-preview-cert" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 modal-hidden">
          <div class="modal-content bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 flex flex-col gap-6 shadow-2xl border border-[#DEE8F7] max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
              <div>
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">معاينة شهادة الإتمام المعتمدة</h3>
                <p class="text-xs text-slate-500">تم التحقق من معيار المشاهدة ٩٥٪ والكود الأكاديمي الموحد</p>
              </div>
              <button type="button" onclick="closeModal('modal-preview-cert')" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>

            <!-- Certificate Card View -->
            <div class="p-8 rounded-2xl bg-gradient-to-br from-white via-slate-50 to-[#EAF1FB]/40 border-2 border-[#DEE8F7] relative text-center flex flex-col items-center gap-4 shadow-sm">
              <div class="size-16 rounded-full bg-[#EAF1FB] p-2 flex items-center justify-center border border-[#DEE8F7]">
                <img src="/assets/images/logo.png" alt="منصة الأمين" class="size-full object-contain" />
              </div>
              
              <h2 class="font-['Baloo_Bhaijaan_2'] font-extrabold text-2xl text-[#012383]">شهادة إتمام برنامج تدريبي معتمد</h2>
              <p class="text-xs text-slate-500">تشهد منصة الأمين لتأهيل علوم التخاطب بأن الدارس / الدارسة:</p>
              
              <p class="font-['Baloo_Bhaijaan_2'] font-black text-2xl text-[#0B2A8F] border-b border-[#DEE8F7] pb-2 px-8">
                مروان خالد العتيبي
              </p>

              <p class="text-xs text-slate-600 max-w-md leading-relaxed">
                قد أكمل بنجاح متطلبات <strong>دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة</strong> بعد إتمام أكثر من ٩٥٪ من ساعات المشاهدة التدريبية واجتياز المهام السريرية بنسبة <strong>٩٤٪</strong>.
              </p>

              <div class="w-full flex items-center justify-between border-t border-[#DEE8F7] pt-4 mt-2 text-xs">
                <div class="text-right">
                  <span class="text-slate-400 block text-[10px]">كود التحقق الرقمي:</span>
                  <span class="font-mono font-bold text-[#012383]">ALM-2026-SP-0489</span>
                </div>
                <div class="text-center">
                  <span class="text-slate-400 block text-[10px]">ختم الاعتماد:</span>
                  <span class="text-emerald-700 font-bold">معتمد سريرياً ✦</span>
                </div>
                <div class="text-left">
                  <span class="text-slate-400 block text-[10px]">تاريخ المنح:</span>
                  <span class="font-bold text-slate-700">٢٠ أكتوبر ٢٠٢٦</span>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
              <button type="button" onclick="closeModal('modal-preview-cert')" class="px-5 h-11 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs">إغلاق</button>
              <button type="button" onclick="window.print()" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs flex items-center gap-2 shadow-sm">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>طباعة وتنزيل PDF</span>
              </button>
            </div>
          </div>
        </div>
    </x-slot:modals>
</x-admin-layout::admin>



