<x-admin-layout::admin
    :title="__('دليل الطلاب والتحكم بصلاحيات الوصول')"
    :description="__('إدارة حسابات الدارسين، تتبع ساعات المشاهدة، ومنح أو إلغاء صلاحية الوصول لكورس التخاطب')"
    contentClass="p-4 sm:p-8 flex flex-col gap-6 max-w-[1500px] w-full mx-auto"
>
    <x-slot:actions>
        <span class="text-xs font-bold text-[#012383] dark:text-blue-400 bg-[#EAF1FB] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 px-3 py-1.5 rounded-xl">
            {{ __('إجمالي ٣٤٠ طالب مسجل') }}
        </span>
    </x-slot:actions>

          
          <!-- Filters & Live Search Toolbar -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl p-4 sm:p-6 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
              <button type="button" data-filter="all" class="student-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-[#012383] text-white">
                جميع الطلاب (٣٤٠)
              </button>
              <button type="button" data-filter="active" class="student-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-100 hover:bg-[#EAF1FB] text-slate-700">
                نشط ومفعل (٣٢٢)
              </button>
              <button type="button" data-filter="revoked" class="student-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-100 hover:bg-[#EAF1FB] text-slate-700">
                ملغي الصلاحية (١٨)
              </button>
            </div>

            <div class="relative w-full sm:w-80">
              <input type="text" id="students-search-input" placeholder="ابحث بالاسم، البريد أو رقم الهاتف..." class="w-full h-11 ps-4 pe-10 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-700 outline-none focus:border-[#0B2A8F] bg-slate-50/50" />
              <svg class="size-4 text-slate-400 absolute end-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

          </div>

          <!-- Students Roster Table -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
              <table class="w-full text-right border-collapse">
                <thead>
                  <tr class="bg-slate-50/80 border-b border-[#DEE8F7] text-[12px] font-bold text-slate-500">
                    <th class="py-4 px-5">الطالب وبيانات القيد</th>
                    <th class="py-4 px-4">تاريخ التسجيل</th>
                    <th class="py-4 px-4">الساعات المكتملة</th>
                    <th class="py-4 px-4">نسبة مشاهدة الفيديوهات</th>
                    <th class="py-4 px-4 text-center">متوسط التقييم</th>
                    <th class="py-4 px-4 text-center">حالة الصلاحية</th>
                    <th class="py-4 px-5 text-center">التحكم بالوصول</th>
                  </tr>
                </thead>
                <tbody id="students-table-body" class="divide-y divide-[#DEE8F7]/70 text-xs">
                  
                  <!-- Student 1 -->
                  <tr id="student-row-1" data-status="active" data-student-name="أحمد إبراهيم الحارثي" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F] shrink-0">
                          أ
                        </div>
                        <div>
                          <div class="font-bold text-sm text-[#012383]">أحمد إبراهيم الحارثي</div>
                          <div class="text-[11px] text-slate-500 font-mono">01011223344 • ahmed.ibrahim@gmail.com</div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">١٢ سبتمبر ٢٠٢٦</span>
                      <div class="text-[10px] text-slate-400">دفعة الخريف</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-[#012383]">٣.٤ ساعة</span>
                      <div class="text-[10px] text-slate-400">من إجمالي ٨ ساعات</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="w-36 flex flex-col gap-1">
                        <div class="flex items-center justify-between text-[11px]">
                          <span class="font-bold text-[#0B2A8F]">٤٢٪</span>
                          <span class="text-slate-400">٨ من ٢٠ فيديو</span>
                        </div>
                        <div class="h-2 w-full bg-[#DEE8F7] rounded-full overflow-hidden">
                          <div class="h-full bg-gradient-to-r from-[#0B2A8F] to-[#012383] rounded-full w-[42%]"></div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="font-bold text-sm text-emerald-700">٨٧ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="student-access-badge px-3 py-1 rounded-full text-xs font-bold badge-active">
                        نشط ومفعل
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="toggleStudentAccess('student-row-1', 'أحمد إبراهيم الحارثي', 'active')" class="student-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                        إلغاء الصلاحية
                      </button>
                    </td>
                  </tr>

                  <!-- Student 2 -->
                  <tr id="student-row-2" data-status="active" data-student-name="سارة خالد المنصور" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-purple-50 flex items-center justify-center font-bold text-sm text-purple-700 shrink-0">
                          س
                        </div>
                        <div>
                          <div class="font-bold text-sm text-[#012383]">سارة خالد المنصور</div>
                          <div class="text-[11px] text-slate-500 font-mono">01023456789 • sara.mansour@gmail.com</div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">٢٤ أكتوبر ٢٠٢٦</span>
                      <div class="text-[10px] text-emerald-600 font-bold">تسجيل جديد</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-[#012383]">٠.٦ ساعة</span>
                      <div class="text-[10px] text-slate-400">في البداية</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="w-36 flex flex-col gap-1">
                        <div class="flex items-center justify-between text-[11px]">
                          <span class="font-bold text-[#0B2A8F]">١٠٪</span>
                          <span class="text-slate-400">٢ من ٢٠ فيديو</span>
                        </div>
                        <div class="h-2 w-full bg-[#DEE8F7] rounded-full overflow-hidden">
                          <div class="h-full bg-gradient-to-r from-[#0B2A8F] to-[#012383] rounded-full w-[10%]"></div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="text-slate-400 font-bold">قيد التقييم</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="student-access-badge px-3 py-1 rounded-full text-xs font-bold badge-active">
                        نشط ومفعل
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="toggleStudentAccess('student-row-2', 'سارة خالد المنصور', 'active')" class="student-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                        إلغاء الصلاحية
                      </button>
                    </td>
                  </tr>

                  <!-- Student 3 (Achieved 95% Completion) -->
                  <tr id="student-row-3" data-status="active" data-student-name="مروان خالد العتيبي" class="hover:bg-slate-50/60 transition-colors bg-emerald-50/20">
                    <td class="py-4 px-5">
                      <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-emerald-100 flex items-center justify-center font-bold text-sm text-emerald-800 shrink-0">
                          م
                        </div>
                        <div>
                          <div class="font-bold text-sm text-[#012383] flex items-center gap-1.5">
                            <span>مروان خالد العتيبي</span>
                            <span class="text-[10px] text-emerald-700 font-bold bg-emerald-100 px-1.5 py-0.2 rounded">مؤهل للشهادة ✦</span>
                          </div>
                          <div class="text-[11px] text-slate-500 font-mono">marwan.otaibi@gmail.com</div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">٥ أغسطس ٢٠٢٦</span>
                      <div class="text-[10px] text-slate-400">دفعة الصيف</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-emerald-700">٧.٦ ساعة</span>
                      <div class="text-[10px] text-emerald-600 font-bold">أتم الكورس</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="w-36 flex flex-col gap-1">
                        <div class="flex items-center justify-between text-[11px]">
                          <span class="font-bold text-emerald-600">٩٥٪</span>
                          <span class="text-slate-400">١٩ من ٢٠ فيديو</span>
                        </div>
                        <div class="h-2 w-full bg-[#DEE8F7] rounded-full overflow-hidden">
                          <div class="h-full bg-emerald-500 rounded-full w-[95%]"></div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="font-bold text-sm text-emerald-700">٩٤ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="student-access-badge px-3 py-1 rounded-full text-xs font-bold badge-active">
                        نشط ومفعل
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="toggleStudentAccess('student-row-3', 'مروان خالد العتيبي', 'active')" class="student-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                        إلغاء الصلاحية
                      </button>
                    </td>
                  </tr>

                  <!-- Student 4 (Revoked Access) -->
                  <tr id="student-row-4" data-status="revoked" data-student-name="طارق يحيى السعيد" class="hover:bg-slate-50/60 transition-colors opacity-75">
                    <td class="py-4 px-5">
                      <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-sm text-slate-600 shrink-0">
                          ط
                        </div>
                        <div>
                          <div class="font-bold text-sm text-slate-700">طارق يحيى السعيد</div>
                          <div class="text-[11px] text-slate-400 font-mono">tariq.saeed@gmail.com</div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">١ يوليو ٢٠٢٦</span>
                      <div class="text-[10px] text-rose-600 font-bold">صلاحية مسحوبة</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-slate-500">١.٢ ساعة</span>
                    </td>
                    <td class="py-4 px-4">
                      <div class="w-36 flex flex-col gap-1">
                        <div class="flex items-center justify-between text-[11px]">
                          <span class="font-bold text-slate-500">١٥٪</span>
                          <span class="text-slate-400">متوقف</span>
                        </div>
                        <div class="h-2 w-full bg-[#DEE8F7] rounded-full overflow-hidden">
                          <div class="h-full bg-slate-400 rounded-full w-[15%]"></div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="font-bold text-slate-500">٧٠ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="student-access-badge px-3 py-1 rounded-full text-xs font-bold badge-revoked">
                        ملغي الصلاحية
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="toggleStudentAccess('student-row-4', 'طارق يحيى السعيد', 'revoked')" class="student-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors cursor-pointer">
                        منح الصلاحية
                      </button>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>

            <div class="p-4 bg-slate-50/70 border-t border-[#DEE8F7] flex items-center justify-between text-xs text-slate-500">
              <span>عرض ٤ من إجمالي ٣٤٠ طالب</span>
              <div class="flex items-center gap-1">
                <button type="button" class="px-3 py-1.5 rounded-lg bg-[#012383] text-white font-bold">١</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-white border border-[#DEE8F7] hover:bg-[#EAF1FB]">٢</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-white border border-[#DEE8F7] hover:bg-[#EAF1FB]">التالي</button>
              </div>
            </div>
          </div>

        </div>

    <x-slot:modals>
        <!-- Modal: Toggle Student Access -->
        <div id="modal-toggle-access" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 modal-hidden">
          <div class="modal-content bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7]">
            <div class="flex items-center gap-2.5 text-[#012383]">
              <div class="size-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="size-5 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              </div>
              <h3 id="toggle-access-title" class="font-bold text-lg text-[#012383]">تعديل صلاحية الوصول للطالب</h3>
            </div>
            <p id="toggle-access-desc" class="text-xs text-slate-500 leading-relaxed">
              هل أنت متأكد من تغيير صلاحية الوصول لهذا الطالب؟
            </p>

            <div class="flex items-center justify-end gap-2 pt-2">
              <button type="button" onclick="closeModal('modal-toggle-access')" class="px-4 h-10 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs cursor-pointer">إلغاء</button>
              <button type="button" id="toggle-access-confirm-btn" onclick="confirmToggleAccess()" class="px-5 h-10 rounded-xl bg-[#012383] text-white font-bold text-xs shadow-sm cursor-pointer">تأكيد الإجراء</button>
            </div>
          </div>
        </div>

        <!-- Script modules -->
        <script type="module">
          import { filterTableRows, filterByPill } from '/assets/js/admin.js';
          filterTableRows('students-search-input', 'students-table-body');
          filterByPill('.student-filter-pill', 'data-status', 'students-table-body');
        </script>
    </x-slot:modals>
</x-admin-layout::admin>



