<x-admin-layout::admin
    :title="__('إعدادات قنوات الدفع اليدوي والمنصة')"
    :description="__('تحديد أرقام المحافظ البنكية، شروط إصدار الشهادات، وتفاصيل إدارة النظام')"
    contentClass="p-4 sm:p-8 flex flex-col gap-6 max-w-[1200px] w-full mx-auto"
>

          
          <!-- Section 0: Theme & Appearance (Light / Dark / Device) -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col gap-5">
            <div class="border-b border-[#DEE8F7] pb-4">
              <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">مظهر لوحة التحكم والنظام (Theme)</h3>
              <p class="text-xs text-slate-500">اختر مظهر لوحة الإدارة: فاتح نهاري، داكن ليلي، أو متوافق تلقائياً مع نظام جهازك</p>
            </div>

            <x-admin::theme-switcher variant="segmented" />
          </div>

          <!-- Section 1: Manual Payment Channels -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col gap-6">
            <div class="border-b border-[#DEE8F7] pb-4">
              <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">بيانات استلام التحويلات اليدوية (تظهر للطلاب عند التسجيل)</h3>
              <p class="text-xs text-slate-500">سيقوم الطالب بتحويل المبلغ المطلوب إلى هذه الحسابات ثم رفع صورة الإيصال</p>
            </div>

            <form onsubmit="event.preventDefault(); showToast('تم حفظ الإعدادات', 'تم تحديث بيانات قنوات الدفع بنجاح.');" class="grid grid-cols-1 md:grid-cols-2 gap-5">
              
              <!-- Vodafone Cash -->
              <div class="p-5 rounded-2xl bg-slate-50/70 border border-[#DEE8F7]/80 flex flex-col gap-3">
                <div class="flex items-center gap-2 text-rose-700 font-bold text-sm">
                  <span class="size-2.5 rounded-full bg-rose-600"></span>
                  <span>محفظة فودافون كاش (Vodafone Cash)</span>
                </div>
                <div>
                  <label class="text-xs font-bold text-slate-600 block mb-1">رقم المحفظة المعتمد:</label>
                  <input type="text" value="01023456789" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-slate-800 bg-white" />
                </div>
                <div>
                  <label class="text-xs font-bold text-slate-600 block mb-1">اسم صاحب المحفظة:</label>
                  <input type="text" value="مركز الأمين للتخاطب" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-800 bg-white" />
                </div>
              </div>

              <!-- InstaPay -->
              <div class="p-5 rounded-2xl bg-slate-50/70 border border-[#DEE8F7]/80 flex flex-col gap-3">
                <div class="flex items-center gap-2 text-purple-700 font-bold text-sm">
                  <span class="size-2.5 rounded-full bg-purple-600"></span>
                  <span>حساب إنستاباي (InstaPay Address)</span>
                </div>
                <div>
                  <label class="text-xs font-bold text-slate-600 block mb-1">عنوان الدفع اللحظي (IPA):</label>
                  <input type="text" value="alameen@instapay" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold font-mono text-slate-800 bg-white" />
                </div>
                <div>
                  <label class="text-xs font-bold text-slate-600 block mb-1">اسم الحساب المسجل:</label>
                  <input type="text" value="Al-Ameen Speech Center" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-800 bg-white" />
                </div>
              </div>

              <!-- Bank Account -->
              <div class="p-5 rounded-2xl bg-slate-50/70 border border-[#DEE8F7]/80 flex flex-col gap-3 md:col-span-2">
                <div class="flex items-center gap-2 text-[#012383] font-bold text-sm">
                  <span class="size-2.5 rounded-full bg-[#0B2A8F]"></span>
                  <span>التحويل البنكي المباشر (National Bank / CIB)</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                  <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">اسم البنك:</label>
                    <input type="text" value="البنك الأهلي المصري (NBE)" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-bold text-slate-800 bg-white" />
                  </div>
                  <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">رقم الحساب:</label>
                    <input type="text" value="1234567890123456" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-mono font-bold text-slate-800 bg-white" />
                  </div>
                  <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">رقم الآيبان (IBAN):</label>
                    <input type="text" value="EG380002000100000012345678901" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-mono font-bold text-slate-800 bg-white" />
                  </div>
                </div>
              </div>

              <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer">
                  حفظ قنوات الدفع
                </button>
              </div>

            </form>
          </div>

          <!-- Section 2: Academic & Certificate Rules -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col gap-6">
            <div class="border-b border-[#DEE8F7] pb-4">
              <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">معايير إتمام الكورس والشهادات الرقمية</h3>
              <p class="text-xs text-slate-500">الضوابط التي يُصدر النظام بناءً عليها الشهادة أو يقفلها</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">نسبة المشاهدة الإلزامية لإصدار الشهادة:</label>
                <div class="flex items-center gap-2">
                  <input type="number" value="95" min="50" max="100" class="w-32 h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-[#012383]" />
                  <span class="text-sm font-bold text-slate-500">٪ من مجموع فيديوهات الكورس</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">الوضع الموصى به من العميل: ٩٥٪ (١٩ من ٢٠ فيديو).</p>
              </div>

              <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">الدرجة الصغرى لاجتياز المهام السريرية:</label>
                <div class="flex items-center gap-2">
                  <input type="number" value="60" min="50" max="100" class="w-32 h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-[#012383]" />
                  <span class="text-sm font-bold text-slate-500">من ١٠٠ كمتوسط تراكمي</span>
                </div>
              </div>
            </div>
          </div>

        </div>

</x-admin-layout::admin>



