<x-admin-layout::admin
    :title="__('مركز تصحيح المهام والتقييم السريري')"
    :description="__('مراجعة حلول الطلاب للمهام الملحقة بالفيديوهات ورصد الدرجات وإرسال الملاحظات')"
    contentClass="p-4 sm:p-8 flex flex-col gap-6 max-w-[1500px] w-full mx-auto"
>
    <x-slot:actions>
        <span class="text-xs font-bold text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
            <span class="size-2 rounded-full bg-purple-500 animate-ping"></span>
            {{ __('٢٨ مهمة بانتظار تصحيحك') }}
        </span>
    </x-slot:actions>

          
          <!-- Quick Status Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-2xl bg-white border border-[#DEE8F7] shadow-xs flex items-center justify-between">
              <div>
                <span class="text-xs font-bold text-slate-400">إجمالي المهام المرفوعة</span>
                <span class="text-2xl font-black text-[#012383] block mt-0.5">٥٦ مهمة</span>
              </div>
              <div class="size-10 rounded-xl bg-blue-50 text-[#0B2A8F] flex items-center justify-center font-bold">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
            </div>

            <div class="p-4 rounded-2xl bg-white border border-[#DEE8F7] shadow-xs flex items-center justify-between">
              <div>
                <span class="text-xs font-bold text-slate-400">بانتظار رصد الدرجة</span>
                <span class="text-2xl font-black text-amber-600 block mt-0.5">٢٨ مهمة</span>
              </div>
              <div class="size-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
            </div>

            <div class="p-4 rounded-2xl bg-white border border-[#DEE8F7] shadow-xs flex items-center justify-between">
              <div>
                <span class="text-xs font-bold text-slate-400">متوسط درجات الطلاب</span>
                <span class="text-2xl font-black text-emerald-600 block mt-0.5">٨٧.٥٪</span>
              </div>
              <div class="size-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
            </div>
          </div>

          <!-- Filters & Search Toolbar -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl p-4 sm:p-6 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
              <button type="button" data-filter="all" class="task-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-[#012383] text-white">
                جميع المهام (٥٦)
              </button>
              <button type="button" data-filter="pending" class="task-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-100 hover:bg-[#EAF1FB] text-slate-700">
                بانتظار التصحيح (٢٨)
              </button>
              <button type="button" data-filter="graded" class="task-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-100 hover:bg-[#EAF1FB] text-slate-700">
                مُقيّمة ومُعتمدة (٢٨)
              </button>
            </div>

            <div class="relative w-full sm:w-72">
              <input type="text" id="tasks-search-input" placeholder="ابحث باسم الطالب أو الدرس..." class="w-full h-11 ps-4 pe-10 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-700 outline-none focus:border-[#0B2A8F] bg-slate-50/50" />
              <svg class="size-4 text-slate-400 absolute end-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

          </div>

          <!-- Tasks Submissions Table -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
              <table class="w-full text-right border-collapse">
                <thead>
                  <tr class="bg-slate-50/80 border-b border-[#DEE8F7] text-[12px] font-bold text-slate-500">
                    <th class="py-4 px-5">الطالب</th>
                    <th class="py-4 px-4">الدرس والمستوى المرتبط</th>
                    <th class="py-4 px-4">تاريخ الرفع</th>
                    <th class="py-4 px-4">ملف الحل المرفق</th>
                    <th class="py-4 px-4 text-center">الدرجة</th>
                    <th class="py-4 px-4 text-center">الحالة</th>
                    <th class="py-4 px-5 text-center">الإجراء</th>
                  </tr>
                </thead>
                <tbody id="tasks-table-body" class="divide-y divide-[#DEE8F7]/70 text-xs">
                  
                  <!-- Task 1 (Pending) -->
                  <tr id="task-row-201" data-status="pending" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">أحمد إبراهيم الحارثي</div>
                      <div class="text-[11px] text-slate-400">ahmed.ibrahim@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="font-bold text-slate-800">الدرس ٨: مهارات التمييز السمعي</div>
                      <div class="text-[11px] text-slate-400">المستوى الثاني • ٦ أزواج صوتية</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="text-slate-600 font-medium">اليوم، ١:١٥ م</span>
                    </td>
                    <td class="py-4 px-4">
                      <button type="button" onclick="showToast('تنزيل الملف', 'جاري تحميل Auditory_Discrimination_Plan.pdf', 'info')" class="px-2.5 py-1 rounded-lg bg-slate-50 border border-[#DEE8F7] text-[#0B2A8F] font-bold text-xs hover:bg-[#EAF1FB] flex items-center gap-1.5 cursor-pointer">
                        <svg class="size-3.5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                        <span>ملف الإجابة PDF</span>
                      </button>
                    </td>
                    <td class="task-score-cell py-4 px-4 text-center">
                      <span class="text-slate-400 font-semibold">—</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="task-status-badge px-3 py-1 rounded-full text-xs font-bold badge-pending inline-block">
                        بانتظار التصحيح
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="openGradingModal({taskId: 'task-row-201', studentName: 'أحمد إبراهيم الحارثي', lessonName: 'الدرس ٨: مهارات التمييز السمعي', submittedDate: 'اليوم، ١:١٥ م', fileName: 'Auditory_Discrimination_Plan.pdf', currentScore: '', currentFeedback: ''})" class="px-4 py-1.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-2xs transition-colors cursor-pointer">
                        تصحيح ورصد
                      </button>
                    </td>
                  </tr>

                  <!-- Task 2 (Pending) -->
                  <tr id="task-row-202" data-status="pending" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">فاطمة الزهراء علي</div>
                      <div class="text-[11px] text-slate-400">fatima.ali@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="font-bold text-slate-800">الدرس ٩: تدريبات الوعي الصوتي</div>
                      <div class="text-[11px] text-slate-400">المستوى الثاني • أنشطة التقطيع</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="text-slate-600 font-medium">اليوم، ١٠:٣٠ ص</span>
                    </td>
                    <td class="py-4 px-4">
                      <button type="button" onclick="showToast('تنزيل الملف', 'جاري تحميل Phonological_Awareness_Activity.docx', 'info')" class="px-2.5 py-1 rounded-lg bg-slate-50 border border-[#DEE8F7] text-[#0B2A8F] font-bold text-xs hover:bg-[#EAF1FB] flex items-center gap-1.5 cursor-pointer">
                        <svg class="size-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                        <span>ملف النشاط DOCX</span>
                      </button>
                    </td>
                    <td class="task-score-cell py-4 px-4 text-center">
                      <span class="text-slate-400 font-semibold">—</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="task-status-badge px-3 py-1 rounded-full text-xs font-bold badge-pending inline-block">
                        بانتظار التصحيح
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="openGradingModal({taskId: 'task-row-202', studentName: 'فاطمة الزهراء علي', lessonName: 'الدرس ٩: تدريبات الوعي الصوتي', submittedDate: 'اليوم، ١٠:٣٠ ص', fileName: 'Phonological_Awareness_Activity.docx', currentScore: '', currentFeedback: ''})" class="px-4 py-1.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-2xs transition-colors cursor-pointer">
                        تصحيح ورصد
                      </button>
                    </td>
                  </tr>

                  <!-- Task 3 (Graded) -->
                  <tr id="task-row-203" data-status="graded" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">مروان خالد العتيبي</div>
                      <div class="text-[11px] text-slate-400">marwan.otaibi@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="font-bold text-slate-800">الدرس ١: أركان ومكونات عملية التواصل</div>
                      <div class="text-[11px] text-slate-400">المستوى الأول • التشويش السريري</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="text-slate-600 font-medium">منذ يومين</span>
                    </td>
                    <td class="py-4 px-4">
                      <button type="button" onclick="showToast('تنزيل الملف', 'جاري تحميل Communication_Model.pdf', 'info')" class="px-2.5 py-1 rounded-lg bg-slate-50 border border-[#DEE8F7] text-[#0B2A8F] font-bold text-xs hover:bg-[#EAF1FB] flex items-center gap-1.5 cursor-pointer">
                        <svg class="size-3.5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                        <span>نموذج الحل PDF</span>
                      </button>
                    </td>
                    <td class="task-score-cell py-4 px-4 text-center">
                      <span class="font-bold text-sm text-[#012383]">٩٤ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="task-status-badge px-3 py-1 rounded-full text-xs font-bold badge-active inline-block">
                        مُقيّمة ومُعتمدة
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="openGradingModal({taskId: 'task-row-203', studentName: 'مروان خالد العتيبي', lessonName: 'الدرس ١: أركان عملية التواصل', submittedDate: 'منذ يومين', fileName: 'Communication_Model.pdf', currentScore: '94', currentFeedback: 'ممتاز جداً في تحديد دور التغذية الراجعة السمعية في تصحيح النطق.'})" class="px-3 py-1.5 rounded-xl border border-[#DEE8F7] text-slate-700 hover:bg-[#EAF1FB] font-bold text-xs transition-colors cursor-pointer">
                        تعديل الدرجة
                      </button>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>

            <div class="p-4 bg-slate-50/70 border-t border-[#DEE8F7] flex items-center justify-between text-xs text-slate-500">
              <span>عرض ٣ من إجمالي ٥٦ تسليم مهمة</span>
              <div class="flex items-center gap-1">
                <button type="button" class="px-3 py-1.5 rounded-lg bg-[#012383] text-white font-bold">١</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-white border border-[#DEE8F7] hover:bg-[#EAF1FB]">٢</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-white border border-[#DEE8F7] hover:bg-[#EAF1FB]">التالي</button>
              </div>
            </div>
          </div>

        </div>

    <x-slot:modals>
        <!-- Modal: Grade Task -->
        <div id="modal-grade-task" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 modal-hidden">
          <div class="modal-content bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7]">
            <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
              <div class="flex items-center gap-2">
                <div class="size-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center font-bold">
                  <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">تصحيح المهمة السريرية</h3>
              </div>
              <button type="button" onclick="closeModal('modal-grade-task')" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl text-xs space-y-1.5 border border-[#DEE8F7]/60">
              <p><span class="text-slate-400">الطالب:</span> <strong id="grade-student-name" class="text-[#012383]">أحمد إبراهيم</strong></p>
              <p><span class="text-slate-400">الدرس:</span> <strong id="grade-lesson-name" class="text-slate-700">الدرس ٨: مهارات التمييز السمعي</strong></p>
              <p><span class="text-slate-400">تاريخ الرفع:</span> <span id="grade-submitted-date" class="text-slate-600">اليوم، ١:١٥ م</span></p>
              <div class="pt-2 flex items-center justify-between border-t border-[#DEE8F7]/50 mt-2">
                <span class="text-slate-500 font-bold" id="grade-file-name">Auditory_Discrimination_Plan.pdf</span>
                <button type="button" onclick="showToast('تنزيل الملف', 'جاري تحميل ملف إجابة الطالب للمراجعة', 'info')" class="px-3 py-1 rounded-lg bg-white border border-[#DEE8F7] text-[#0B2A8F] font-bold text-xs hover:bg-[#EAF1FB]">تحميل الملف ⭳</button>
              </div>
            </div>

            <form onsubmit="submitTaskGrade(event)" class="flex flex-col gap-4">
              <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">الدرجة المستحقة (من ١٠٠):</label>
                <input type="number" id="grade-score-input" required min="0" max="100" placeholder="مثال: 90" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-[#012383] outline-none focus:border-[#0B2A8F]" />
              </div>

              <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">ملاحظات المحاضر والتوجيهات السريرية:</label>
                <textarea id="grade-feedback-input" rows="3" placeholder="اكتب ملاحظاتك للطالب لتوجيهه في الممارسة العيادية..." class="w-full p-3.5 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-700 outline-none focus:border-[#0B2A8F] resize-none"></textarea>
              </div>

              <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-grade-task')" class="px-4 h-10 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs">إلغاء</button>
                <button type="submit" class="px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm">اعتماد ورصد الدرجة</button>
              </div>
            </form>
          </div>
        </div>

        <script type="module">
          import { filterTableRows, filterByPill } from '/assets/js/admin.js';
          filterTableRows('tasks-search-input', 'tasks-table-body');
          filterByPill('.task-filter-pill', 'data-status', 'tasks-table-body');
        </script>
    </x-slot:modals>
</x-admin-layout::admin>



