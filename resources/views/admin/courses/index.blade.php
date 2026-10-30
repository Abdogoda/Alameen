<x-admin-layout::admin
    :title="__('إدارة محتوى الكورس والمستويات')"
    :description="__('تحكم كامل في المستويات، رفع الفيديوهات، ربط المهام، وتعديل السعر ديناميكياً')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-5 sm:gap-6 max-w-[1400px] w-full mx-auto min-w-0"
>
    <x-slot:actions>
        <button type="button" onclick="openModal('modal-add-level')" class="h-9 sm:h-11 px-3 sm:px-5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-all flex items-center gap-1.5 sm:gap-2 cursor-pointer shrink-0">
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden xs:inline">{{ __('إضافة مستوى جديد') }}</span>
            <span class="xs:hidden">{{ __('مستوى جديد') }}</span>
        </button>
    </x-slot:actions>

          
          <!-- Course Card Info Bar with Dynamic Pricing Controller -->
          <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-4 sm:p-7 shadow-xs flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-5 sm:gap-6 min-w-0">
            <div class="flex items-start sm:items-center gap-3.5 sm:gap-4 min-w-0">
              <div class="size-16 sm:size-20 rounded-2xl bg-[#012383] overflow-hidden shrink-0 border border-[#DEE8F7] dark:border-slate-700">
                <img src="/assets/images/course-topic-basics.png" alt="كورس التخاطب" class="size-full object-cover" />
              </div>
              <div class="space-y-1 text-right min-w-0">
                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap text-xs">
                  <span class="px-2.5 py-0.5 rounded-full bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 text-xs font-bold font-['Cairo']">الكورس الأساسي النشط</span>
                  <span class="text-xs text-slate-400">•</span>
                  <span class="text-xs text-slate-600 dark:text-slate-300 font-medium">٤ مستويات معتمدة</span>
                  <span class="text-xs text-slate-400">•</span>
                  <span class="text-xs text-slate-600 dark:text-slate-300 font-medium">٢٠ فيديو و٢٠ مهمة</span>
                </div>
                <h2 class="font-['Cairo'] font-extrabold text-lg sm:text-2xl text-[#012383] dark:text-white leading-snug">
                  دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xl leading-relaxed">
                  البرنامج التدريبي المتكامل لتأهيل أخصائيي التخاطب وأولياء الأمور مع نظام المهام بعد كل درس وشهادة إتمام عند ٩٥٪.
                </p>
              </div>
            </div>

            <!-- Dynamic Price Badge & Editor Button -->
            <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/60 p-3.5 sm:p-4 rounded-2xl border border-[#DEE8F7]/80 dark:border-slate-700 w-full lg:w-auto justify-between lg:justify-start shrink-0">
              <div class="text-right">
                <span class="text-[11px] text-slate-400 font-bold block">سعر الكورس المعتمد</span>
                <div class="flex items-baseline gap-2 mt-0.5">
                  <span class="text-xl sm:text-2xl font-black text-[#012383] dark:text-blue-400 dynamic-course-price">١,٢٠٠ ج.م</span>
                  <span class="text-xs text-slate-400 line-through dynamic-course-old-price">١,٨٠٠ ج.م</span>
                </div>
              </div>
              <button type="button" onclick="openModal('modal-edit-price')" class="h-10 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 text-[#0B2A8F] dark:text-blue-300 font-bold text-xs transition-colors shadow-2xs cursor-pointer flex items-center gap-1.5 shrink-0">
                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>تعديل السعر</span>
              </button>
            </div>
          </div>

          <!-- Dynamic Levels Curriculum Container -->
          <div class="flex flex-col gap-4 sm:gap-5 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <div>
                <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-blue-400">هيكل المستويات والدروس (Dynamic Levels Architecture)</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">لكل مستوى: قائمة بالفيديوهات المندرجة تحته مع المهمة السريرية الملحقة به</p>
              </div>
              <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 px-3 py-1 rounded-full w-fit">
                إجمالي ٤ مستويات
              </span>
            </div>

            <!-- Levels Container -->
            <div id="levels-curriculum-container" class="space-y-4">
              
              <!-- Level 1 -->
              <div id="level-card-1" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs transition-all">
                <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-50 via-white to-white dark:from-slate-800/50 dark:via-slate-800/30 dark:to-slate-800/30 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 border-b border-[#DEE8F7]/60 dark:border-slate-800">
                  <div class="flex items-start sm:items-center gap-3 min-w-0">
                    <span class="size-9 rounded-xl bg-[#012383] text-white font-bold text-sm flex items-center justify-center font-['Cairo'] shadow-2xs shrink-0">
                      ١
                    </span>
                    <div class="min-w-0">
                      <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                        <h4 class="font-bold text-sm sm:text-base text-[#012383] dark:text-blue-400">المستوى الأول: مدخل إلى علوم التخاطب والتواصل</h4>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold">٥ فيديوهات</span>
                        <span class="px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 text-[11px] font-bold">٥ مهام</span>
                      </div>
                      <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">أساسيات عملية التواصل، التشريح الصوتي، وتصنيف اضطرابات النطق</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                    <button type="button" onclick="openAddLessonModal('level-card-1', 'المستوى الأول: مدخل إلى علوم التخاطب')" class="px-3.5 py-1.5 rounded-xl bg-[#0B2A8F] hover:bg-[#012383] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                      <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                      <span>إضافة درس</span>
                    </button>
                    <button type="button" onclick="showToast('تعديل المستوى', 'يمكنك تعديل اسم ووصف المستوى الأول', 'info')" class="p-2 text-slate-400 hover:text-[#012383] dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="تعديل">
                      <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                  </div>
                </div>

                <!-- Lessons List -->
                <div class="lessons-list p-3 sm:p-5 flex flex-col gap-2.5 bg-slate-50/30 dark:bg-slate-900/40">
                  
                  <!-- Lesson 1 -->
                  <div class="p-3 sm:p-3.5 bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 shadow-2xs hover:border-[#0B2A8F]/40 transition-colors">
                    <div class="flex items-start sm:items-center gap-3 min-w-0">
                      <div class="size-8 rounded-lg bg-[#EAF1FB] dark:bg-slate-700 flex items-center justify-center shrink-0 text-[#0B2A8F] dark:text-blue-400 mt-0.5 sm:mt-0">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                      </div>
                      <div class="text-right min-w-0">
                        <h5 class="font-bold text-xs sm:text-sm text-[#012383] dark:text-slate-100">الدرس ١: مدخل إلى عملية التواصل البشري</h5>
                        <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex-wrap">
                          <span>٢٠ دقيقة</span>
                          <span>•</span>
                          <span class="text-purple-700 dark:text-purple-400 font-medium">مهمة مرتبطة: مخطط أركان التواصل</span>
                        </div>
                      </div>
                    </div>
                    <div class="flex items-center gap-1.5 self-end sm:self-auto shrink-0">
                      <button type="button" onclick="showToast('معاينة الفيديو', 'جاري فتح مشغل الفيديو في نافذة المعاينة', 'info')" class="px-2.5 py-1 text-xs font-bold text-[#0B2A8F] dark:text-blue-400 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 rounded-lg">تشغيل ↗</button>
                    </div>
                  </div>

                  <!-- Lesson 2 -->
                  <div class="p-3 sm:p-3.5 bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 shadow-2xs hover:border-[#0B2A8F]/40 transition-colors">
                    <div class="flex items-start sm:items-center gap-3 min-w-0">
                      <div class="size-8 rounded-lg bg-[#EAF1FB] dark:bg-slate-700 flex items-center justify-center shrink-0 text-[#0B2A8F] dark:text-blue-400 mt-0.5 sm:mt-0">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                      </div>
                      <div class="text-right min-w-0">
                        <h5 class="font-bold text-xs sm:text-sm text-[#012383] dark:text-slate-100">الدرس ٢: مكوّنات عملية التواصل وعناصر الرسالة</h5>
                        <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex-wrap">
                          <span>٢٠ دقيقة</span>
                          <span>•</span>
                          <span class="text-purple-700 dark:text-purple-400 font-medium">مهمة مرتبطة: تحليل تشويش الرسالة السريرية</span>
                        </div>
                      </div>
                    </div>
                    <div class="flex items-center gap-1.5 self-end sm:self-auto shrink-0">
                      <button type="button" onclick="showToast('معاينة الفيديو', 'جاري فتح مشغل الفيديو في نافذة المعاينة', 'info')" class="px-2.5 py-1 text-xs font-bold text-[#0B2A8F] dark:text-blue-400 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 rounded-lg">تشغيل ↗</button>
                    </div>
                  </div>

                  <!-- Lesson 3 -->
                  <div class="p-3 sm:p-3.5 bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 shadow-2xs hover:border-[#0B2A8F]/40 transition-colors">
                    <div class="flex items-start sm:items-center gap-3 min-w-0">
                      <div class="size-8 rounded-lg bg-[#EAF1FB] dark:bg-slate-700 flex items-center justify-center shrink-0 text-[#0B2A8F] dark:text-blue-400 mt-0.5 sm:mt-0">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                      </div>
                      <div class="text-right min-w-0">
                        <h5 class="font-bold text-xs sm:text-sm text-[#012383] dark:text-slate-100">الدرس ٣: مراحل النمو اللغوي عند الأطفال</h5>
                        <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex-wrap">
                          <span>٢٠ دقيقة</span>
                          <span>•</span>
                          <span class="text-purple-700 dark:text-purple-400 font-medium">مهمة مرتبطة: جدول مقارنة المعالم اللغوية</span>
                        </div>
                      </div>
                    </div>
                    <div class="flex items-center gap-1.5 self-end sm:self-auto shrink-0">
                      <button type="button" onclick="showToast('معاينة الفيديو', 'جاري فتح مشغل الفيديو في نافذة المعاينة', 'info')" class="px-2.5 py-1 text-xs font-bold text-[#0B2A8F] dark:text-blue-400 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 rounded-lg">تشغيل ↗</button>
                    </div>
                  </div>

                </div>
              </div>

              <!-- Level 2 -->
              <div id="level-card-2" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs transition-all">
                <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-50 via-white to-white dark:from-slate-800/50 dark:via-slate-800/30 dark:to-slate-800/30 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 border-b border-[#DEE8F7]/60 dark:border-slate-800">
                  <div class="flex items-start sm:items-center gap-3 min-w-0">
                    <span class="size-9 rounded-xl bg-[#012383] text-white font-bold text-sm flex items-center justify-center font-['Cairo'] shadow-2xs shrink-0">
                      ٢
                    </span>
                    <div class="min-w-0">
                      <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                        <h4 class="font-bold text-sm sm:text-base text-[#012383] dark:text-blue-400">المستوى الثاني: مهارات الصوت والاستماع والتمييز</h4>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold">٥ فيديوهات</span>
                        <span class="px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 text-[11px] font-bold">٥ مهام</span>
                      </div>
                      <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">الوعي الصوتي، التمييز السمعي للأصوات، وبناء الأنشطة المنزلية</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                    <button type="button" onclick="openAddLessonModal('level-card-2', 'المستوى الثاني: مهارات الصوت والاستماع')" class="px-3.5 py-1.5 rounded-xl bg-[#0B2A8F] hover:bg-[#012383] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                      <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                      <span>إضافة درس</span>
                    </button>
                  </div>
                </div>

                <div class="lessons-list p-3 sm:p-5 flex flex-col gap-2.5 bg-slate-50/30 dark:bg-slate-900/40">
                  <div class="p-3 sm:p-3.5 bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 shadow-2xs">
                    <div class="flex items-start sm:items-center gap-3 min-w-0">
                      <div class="size-8 rounded-lg bg-[#EAF1FB] dark:bg-slate-700 flex items-center justify-center shrink-0 text-[#0B2A8F] dark:text-blue-400 mt-0.5 sm:mt-0">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                      </div>
                      <div class="text-right min-w-0">
                        <h5 class="font-bold text-xs sm:text-sm text-[#012383] dark:text-slate-100">الدرس ٨: مهارات التمييز السمعي للأصوات المتشابهة</h5>
                        <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex-wrap">
                          <span>٢٠ دقيقة</span>
                          <span>•</span>
                          <span class="text-purple-700 dark:text-purple-400 font-medium">مهمة مرتبطة: ورقة نشاط التمييز الصوتي (٦ أزواج)</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="p-3 sm:p-3.5 bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 shadow-2xs">
                    <div class="flex items-start sm:items-center gap-3 min-w-0">
                      <div class="size-8 rounded-lg bg-[#EAF1FB] dark:bg-slate-700 flex items-center justify-center shrink-0 text-[#0B2A8F] dark:text-blue-400 mt-0.5 sm:mt-0">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                      </div>
                      <div class="text-right min-w-0">
                        <h5 class="font-bold text-xs sm:text-sm text-[#012383] dark:text-slate-100">الدرس ٩: تدريبات الوعي الصوتي والتقطيع</h5>
                        <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex-wrap">
                          <span>٢٠ دقيقة</span>
                          <span>•</span>
                          <span class="text-purple-700 dark:text-purple-400 font-medium">مهمة مرتبطة: استراتيجية التقطيع الصوتي للكلمات</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Level 3 -->
              <div id="level-card-3" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs transition-all">
                <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-50 via-white to-white dark:from-slate-800/50 dark:via-slate-800/30 dark:to-slate-800/30 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 border-b border-[#DEE8F7]/60 dark:border-slate-800">
                  <div class="flex items-start sm:items-center gap-3 min-w-0">
                    <span class="size-9 rounded-xl bg-[#012383] text-white font-bold text-sm flex items-center justify-center font-['Cairo'] shadow-2xs shrink-0">
                      ٣
                    </span>
                    <div class="min-w-0">
                      <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                        <h4 class="font-bold text-sm sm:text-base text-[#012383] dark:text-blue-400">المستوى الثالث: مخارج الحروف وتصحيح اللدغات والتشويه</h4>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold">٥ فيديوهات</span>
                        <span class="px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 text-[11px] font-bold">٥ مهام</span>
                      </div>
                      <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">تصحيح نطق الراء والسين والكاف والتمارين العضلية المصاحبة</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                    <button type="button" onclick="openAddLessonModal('level-card-3', 'المستوى الثالث: مخارج الحروف وتصحيح اللدغات')" class="px-3.5 py-1.5 rounded-xl bg-[#0B2A8F] hover:bg-[#012383] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                      <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                      <span>إضافة درس</span>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Level 4 -->
              <div id="level-card-4" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs transition-all">
                <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-50 via-white to-white dark:from-slate-800/50 dark:via-slate-800/30 dark:to-slate-800/30 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 border-b border-[#DEE8F7]/60 dark:border-slate-800">
                  <div class="flex items-start sm:items-center gap-3 min-w-0">
                    <span class="size-9 rounded-xl bg-[#012383] text-white font-bold text-sm flex items-center justify-center font-['Cairo'] shadow-2xs shrink-0">
                      ٤
                    </span>
                    <div class="min-w-0">
                      <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                        <h4 class="font-bold text-sm sm:text-base text-[#012383] dark:text-blue-400">المستوى الرابع: التطبيقات السريرية ودراسة الحالات والتقييم النهائي</h4>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold">٥ فيديوهات</span>
                        <span class="px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 text-[11px] font-bold">٥ مهام</span>
                      </div>
                      <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">دراسة حالات واقعية، كتابة الخطة العلاجية الفردية، وتأهيل شهادة الـ ٩٥٪</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                    <button type="button" onclick="openAddLessonModal('level-card-4', 'المستوى الرابع: التطبيقات السريرية')" class="px-3.5 py-1.5 rounded-xl bg-[#0B2A8F] hover:bg-[#012383] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                      <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                      <span>إضافة درس</span>
                    </button>
                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>

    <x-slot:modals>
        <!-- Modal: Add New Lesson / Video -->
        <div id="modal-add-lesson" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
          <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-lg w-full p-5 sm:p-8 flex flex-col gap-4 sm:gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
              <div>
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white">إضافة فيديو ودرس جديد</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">المستوى المستهدف: <span id="add-lesson-level-label" class="font-bold text-[#0B2A8F] dark:text-blue-400">المستوى الأول</span></p>
              </div>
              <button type="button" onclick="closeModal('modal-add-lesson')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>

            <form onsubmit="submitAddLesson(event)" class="flex flex-col gap-4">
              <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">عنوان الفيديو / الدرس:</label>
                <input type="text" id="new-lesson-title" required placeholder="مثال: الدرس ٦: تمارين تقوية عضلات النطق" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">مدة الفيديو (بالدقائق):</label>
                  <input type="number" id="new-lesson-duration" required min="1" max="300" value="20" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                </div>
                <div>
                  <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">ترتيب العرض:</label>
                  <input type="number" min="1" value="6" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                </div>
              </div>

              <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">رابط الفيديو أو معرّف البث (Vimeo / Bunny / HLS):</label>
                <input type="text" required placeholder="https://player.vimeo.com/video/..." class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
              </div>

              <div class="border-t border-[#DEE8F7] dark:border-slate-800 pt-3">
                <label class="text-xs font-bold text-[#012383] dark:text-blue-400 block mb-1">المهمة السريرية الملحقة بالفيديو (تفتح بعد إتمام المشاهدة):</label>
                <input type="text" id="new-lesson-task-title" required placeholder="عنوان المهمة (مثال: إعداد خطة تمارين عملية)" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
              </div>

              <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-add-lesson')" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs">إلغاء</button>
                <button type="submit" class="w-full sm:w-auto px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm">حفظ وإضافة الدرس</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Modal: Add New Level -->
        <div id="modal-add-level" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
          <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-md w-full p-5 sm:p-8 flex flex-col gap-4 sm:gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
              <div class="flex items-center gap-2">
                <div class="size-8 rounded-lg bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400 flex items-center justify-center font-bold">
                  <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white">إضافة مستوى ديناميكي جديد</h3>
              </div>
              <button type="button" onclick="closeModal('modal-add-level')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>

            <form onsubmit="submitAddLevel(event)" class="flex flex-col gap-4">
              <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">عنوان المستوى الجديد:</label>
                <input type="text" id="new-level-title" required placeholder="مثال: المستوى الخامس: استراتيجيات التدخل المبكر" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
              </div>

              <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">وصف موجز للمستوى:</label>
                <textarea id="new-level-desc" rows="2" placeholder="أهداف المستوى وعدد الفيديوهات والمهام..." class="w-full p-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] resize-none bg-slate-50/50 dark:bg-slate-800/50"></textarea>
              </div>

              <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-add-level')" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs">إلغاء</button>
                <button type="submit" class="w-full sm:w-auto px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm">إنشاء المستوى</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Modal: Edit Price -->
        <div id="modal-edit-price" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
          <div class="modal-content bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl max-w-md w-full p-5 sm:p-8 flex flex-col gap-4 sm:gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 max-h-[90dvh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
              <div class="flex items-center gap-2">
                <div class="size-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold">
                  <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white">تعديل سعر كورس التخاطب</h3>
              </div>
              <button type="button" onclick="closeModal('modal-edit-price')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>

            <form onsubmit="handleUpdatePrice(event)" class="flex flex-col gap-4">
              <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">السعر الأصلي (قبل الخصم):</label>
                <input type="number" name="originalPrice" required value="1800" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-600 dark:text-slate-300 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
              </div>

              <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">السعر الفعلي المطلوب دفعه:</label>
                <input type="number" name="currentPrice" required value="1200" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-blue-400 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
              </div>

              <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-edit-price')" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs">إلغاء</button>
                <button type="submit" class="w-full sm:w-auto px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm">حفظ ونشر السعر</button>
              </div>
            </form>
          </div>
        </div>
    </x-slot:modals>
</x-admin-layout::admin>



