<x-layouts::marketing :title="__('دليل البرامج والدورات')" active="courses">
    <div class="w-full max-w-[1536px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-24 2xl:px-32 py-8 lg:py-12 flex flex-col gap-8">
        
        <!-- 1. HERO SEARCH SECTION (WorkDo Marketplace Hero) -->
        <section class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto w-full">
          
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF1FB] border border-[#0B2A8F]/20 text-[#0B2A8F] text-xs font-bold">
            <span data-i18n="catalog.eyebrow">دليل كورسات التخاطب للطلاب</span>
          </div>

          <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight" data-i18n="catalog.heroTitle">
            استكشف كورسات التخاطب للطلاب
          </h1>

          <p class="text-base sm:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed" data-i18n="catalog.heroDesc">
            اختر الكورس الأنسب لمسيرتك التعليمية مع مناهج تدريبية معتمدة وتطبيقات عملية شاملة للطلاب.
          </p>

          <!-- Search Input Bar -->
          <div class="w-full max-w-2xl mt-2 relative">
            <input
              type="text"
              id="course-search-input"
              placeholder="ابحث باسم الكورس، المحاضر، أو المهارة..."
              data-i18n-placeholder="catalog.searchPlaceholder"
              class="w-full h-13 bg-white border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/20 rounded-2xl px-12 text-sm text-[#012383] placeholder-[#475569] outline-none shadow-sm transition-all"
            />
            <svg class="size-5 text-[#0B2A8F] absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>

        </section>

        <!-- 2. FILTER TABS & COURSE GRID (WorkDo Product Grid) -->
        <section class="flex flex-col gap-6">
          
          <!-- Category Filter Bar -->
          <div class="flex flex-wrap items-center justify-between gap-3 bg-white border border-[#DEE8F7] rounded-[16px] p-3 shadow-xs">
            <div class="flex flex-wrap items-center gap-2" id="catalog-category-group">
              <button
                type="button"
                data-cat="all"
                class="catalog-filter active px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer shadow-xs"
              >
                كورس التخاطب
              </button>
            </div>

            <!-- Stats Badge -->
            <span class="text-xs font-bold text-[#0B2A8F] bg-[#EAF1FB] px-3.5 py-1.5 rounded-full border border-[#DEE8F7]">
              مسار التخاطب السريري للطلاب
            </span>
          </div>

          <!-- Courses Grid (3 Columns) -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="catalog-courses-grid">
            
            <!-- Course Card 1: Language Development Strategies -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="استراتيجيات تنمية اللغة">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#012383] relative overflow-hidden">
                  <img src="/assets/images/course-topic-strategies.png" alt="استراتيجيات تنمية اللغة" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-[#10B77F] text-white text-[11px] font-bold shadow-xs">الأكثر طلباً</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">٢٠ درساً</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">تخاطب ونطق</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 4.9</span>
                      <span class="text-slate-400 font-normal">(480)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    استراتيجيات تنمية اللغة والتواصل
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    دليل شامل يركز على التطبيقات السريرية لتطوير الحصيلة اللغوية وتأهيل اضطرابات التواصل للطلاب والدارسين.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      د
                    </div>
                    <span>د. عمر القحطاني • ٨ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

            <!-- Course Card 2: Articulation Disorders Applications -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="تطبيقات مخارج الحروف">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#0B2A8F] relative overflow-hidden">
                  <img src="/assets/images/course-topic-articulation.png" alt="تطبيقات مخارج الحروف" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-amber-500 text-white text-[11px] font-bold shadow-xs">مميز</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٦ درساً</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">تخاطب ونطق</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 4.8</span>
                      <span class="text-slate-400 font-normal">(310)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    تطبيقات مخارج الحروف وتصحيح النطق
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    برنامج عملي مركز لتصحيح إبدال وتشويه الأصوات الكلامية (الراء، السين، الكاف) بالتمارين العضلية.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      أ
                    </div>
                    <span>أ. أحمد السالم • ٦ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

            <!-- Course Card 3: Speech Therapy Fundamentals -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="أساسيات التخاطب">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#152D4D] relative overflow-hidden">
                  <img src="/assets/images/course-topic-basics.png" alt="أساسيات التخاطب" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-blue-600 text-white text-[11px] font-bold shadow-xs">مبتدئ</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٢ درساً</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">أساسيات علمية</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 4.9</span>
                      <span class="text-slate-400 font-normal">(520)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    أساسيات التخاطب والتقييم اللغوي
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    المدخل الأكاديمي الشامل لفهم مراحل التطور اللغوي الطبيعي واكتشاف مؤشرات التأخر المبكر للدارسين.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      س
                    </div>
                    <span>د. سامي البدر • ٥ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

            <!-- Course Card 4: Stuttering & Fluency Management -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="إدارة التأتأة والطلاقة الكلامية">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#0B2A8F] relative overflow-hidden">
                  <img src="/assets/images/photo1.png" alt="إدارة التأتأة" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-emerald-600 text-white text-[11px] font-bold shadow-xs">متوسط</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٤ درساً</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">تخاطب ونطق</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 4.8</span>
                      <span class="text-slate-400 font-normal">(195)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    إدارة التأتأة وتعزيز الطلاقة الكلامية
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    استراتيجيات تقليل التوتر والتحكم بمعدل وسرعة الكلام للأطفال واليافعين بطرق علمية مؤكدة.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      ع
                    </div>
                    <span>د. عمر الفاروق • ٦ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

            <!-- Course Card 5: Autism Communication -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="التدخل المبكر لأطفال طيف التوحد">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#152D4D] relative overflow-hidden">
                  <img src="/assets/images/photo2.png" alt="طيف التوحد" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-purple-600 text-white text-[11px] font-bold shadow-xs">تخصصي</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٨ درساً</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">تخاطب ونطق</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 5.0</span>
                      <span class="text-slate-400 font-normal">(340)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    التدخل المبكر لتطوير التواصل وتأهيل التوحد
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    تطبيق نظام بيكس (PECS) والتواصل البديل والمعزز لتدريب طلاب التخاطب على الخطط الفردية.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      ن
                    </div>
                    <span>أ. ناصر العتيبي • ٩ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

            <!-- Course Card 6: Clinical Speech Diagnostics -->
            <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="التدريب السريري على تشخيص اضطرابات النطق">
              <div>
                <!-- Thumbnail -->
                <div class="h-48 w-full bg-[#012383] relative overflow-hidden">
                  <img src="/assets/images/photo3.png" alt="التدريب السريري على تشخيص اضطرابات النطق" class="size-full object-cover" />
                  <div class="absolute top-3 end-3 flex gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-[#10B77F] text-white text-[11px] font-bold shadow-xs">تطبيق عملي</span>
                    <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">١٠ دروس</span>
                  </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col gap-3">
                  <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">ممارسة سريرية</span>
                    <div class="flex items-center gap-1 text-[#F59E0B]">
                      <span>★ 4.9</span>
                      <span class="text-slate-400 font-normal">(280)</span>
                    </div>
                  </div>

                  <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                    التدريب السريري على تشخيص اضطرابات النطق
                  </h3>

                  <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                    تأهيل الطلاب والدارسين على إجراء المقاييس التشخيصية وتحديد الخطة العلاجية الفردية لاضطرابات النطق.
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                    <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                      م
                    </div>
                    <span>د. مروان الشريف • ٤ ساعات تدريبية</span>
                  </div>
                </div>
              </div>

              <!-- Footer CTA -->
              <div class="p-5 pt-0 flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                  مشمول بالاشتراك
                </span>
                <a
                  href="{{ route('courses.show') }}"
                  class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                  data-i18n="catalog.viewCourseDetails"
                >
                  عرض تفاصيل الكورس
                </a>
              </div>
            </div>

          </div>

          <!-- Empty State (Hidden by default) -->
          <div id="catalog-empty" class="hidden flex-col items-center justify-center py-12 text-center bg-white border border-[#DEE8F7] rounded-[24px]">
            <p class="text-sm font-bold text-[#012383]" data-i18n="catalog.noCoursesFound">لا توجد دورات تطابق معايير البحث الحالية.</p>
          </div>

        </section>

    </div>

    <x-slot:scripts>
        <script>
          // ==========================================
          // Catalog Interactive Filtering Script
          // ==========================================
          document.addEventListener('DOMContentLoaded', () => {
            const catButtons = document.querySelectorAll('.catalog-filter');
            const courseCards = document.querySelectorAll('.course-card');
            const searchInput = document.getElementById('course-search-input');
            const emptyState = document.getElementById('catalog-empty');

            let activeCat = 'all';
            let searchQuery = '';

            function filterCatalog() {
              let visibleCount = 0;
              courseCards.forEach(card => {
                const matchesCat = activeCat === 'all' || card.getAttribute('data-category') === activeCat;
                const title = card.getAttribute('data-title')?.toLowerCase() || '';
                const matchesSearch = !searchQuery || title.includes(searchQuery);

                if (matchesCat && matchesSearch) {
                  card.style.display = 'flex';
                  visibleCount++;
                } else {
                  card.style.display = 'none';
                }
              });

              if (visibleCount === 0) {
                emptyState?.classList.remove('hidden');
                emptyState?.classList.add('flex');
              } else {
                emptyState?.classList.add('hidden');
                emptyState?.classList.remove('flex');
              }
            }

            catButtons.forEach(btn => {
              btn.addEventListener('click', () => {
                catButtons.forEach(b => {
                  b.classList.remove('active', 'bg-[#012383]', 'text-white');
                  b.classList.add('bg-[#EAF1FB]', 'text-[#0B2A8F]');
                });
                btn.classList.add('active', 'bg-[#012383]', 'text-white');
                btn.classList.remove('bg-[#EAF1FB]', 'text-[#0B2A8F]');

                activeCat = btn.getAttribute('data-cat') || 'all';
                filterCatalog();
              });
            });

            searchInput?.addEventListener('input', (e) => {
              searchQuery = e.target.value.trim().toLowerCase();
              filterCatalog();
            });
          });
        </script>
    </x-slot:scripts>
</x-layouts::marketing>



