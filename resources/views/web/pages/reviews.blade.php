<x-layouts::marketing :title="__('آراء وتقييمات الطلاب')" active="reviews">
    <div class="flex-1 w-full max-w-[1536px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-24 2xl:px-32 py-8 lg:py-12 flex flex-col gap-10">
<!-- 1. HERO SECTION & TRUST METRICS (WorkDo SaaS Hero Pattern) -->
        <section class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto">
          
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#10B77F]/30 text-[#10B77F] text-xs font-bold">
            <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span data-i18n="reviews.eyebrow">آراء وتقييمات الطلاب</span>
          </div>

          <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight" data-i18n="reviews.heroTitle">
            تجارب واقعية وقصص نجاح في رحلة التخاطب
          </h1>

          <p class="text-base sm:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed" data-i18n="reviews.heroDesc">
            اكتشف كيف ساعدت دورات منصة مركز الأمين آلاف الطلاب والدارسين في تطوير مهارات النطق والتخاطب السريري.
          </p>

          <!-- Trust Badges 4-Item Grid -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full mt-4">
            
            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <div class="flex items-center gap-1 text-[#F59E0B] text-xl font-extrabold mb-1">
                <span>★</span>
                <span data-i18n="reviews.statRating">٤.٩ من ٥</span>
              </div>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statRatingSub">متوسط التقييم العام</span>
            </div>

            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <span class="text-xl font-extrabold text-[#0B2A8F] mb-1" data-i18n="reviews.statReviews">+١,٤٨٠ تقييم</span>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statReviewsSub">تقييمات موثقة من الطلاب</span>
            </div>

            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <span class="text-xl font-extrabold text-[#10B77F] mb-1" data-i18n="reviews.statSatisfaction">٩٨٪</span>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statSatisfactionSub">نسبة الرضا عن المحتوى</span>
            </div>

            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <span class="text-xl font-extrabold text-[#012383] mb-1" data-i18n="reviews.statVerified">١٠٠٪</span>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statVerifiedSub">طلاب أتموا الدورات</span>
            </div>

          </div>

        </section>

        <!-- 2. RATING BREAKDOWN & CTA CARD (WorkDo 2-Column Split Pattern) -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- Breakdown Bars Card (2 cols on lg) -->
          <div class="lg:col-span-2 bg-white border border-[#DEE8F7] rounded-[20px] p-6 lg:p-8 shadow-xs flex flex-col md:flex-row gap-8 items-center">
            
            <!-- Left: Giant Score -->
            <div class="flex flex-col items-center justify-center text-center shrink-0 md:pe-8 md:border-e md:border-[#DEE8F7]">
              <span class="font-['Cairo'] font-extrabold text-5xl text-[#012383]">4.9</span>
              <div class="flex items-center gap-1 text-[#F59E0B] text-lg my-1.5">
                ★★★★★
              </div>
              <span class="text-xs font-bold text-[#475569]" data-i18n="reviews.statReviewsSub">بناءً على ١,٤٨٠ تقييماً</span>
            </div>

            <!-- Right: Breakdown Bars -->
            <div class="flex-1 w-full flex flex-col gap-2.5">
              
              <!-- 5 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star5">٥ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-[#10B77F] rounded-full" style="width: 88%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">88%</span>
              </div>

              <!-- 4 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star4">٤ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-[#0B2A8F] rounded-full" style="width: 9%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">9%</span>
              </div>

              <!-- 3 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star3">٣ نجوم</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-amber-400 rounded-full" style="width: 2%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">2%</span>
              </div>

              <!-- 2 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star2">نجمتان</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-slate-400 rounded-full" style="width: 1%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">1%</span>
              </div>

              <!-- 1 Star -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star1">نجمة واحدة</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-slate-300 rounded-full" style="width: 0%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">0%</span>
              </div>

            </div>

          </div>

          <!-- Right / Action Card -->
          <div class="bg-gradient-to-br from-[#012383] to-[#0B2A8F] rounded-[20px] p-6 lg:p-8 text-white flex flex-col justify-between shadow-card relative overflow-hidden">
            <div class="absolute -end-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>
            
            <div class="flex flex-col gap-2 relative z-10">
              <span class="text-xs font-bold text-blue-200 uppercase tracking-wider" data-i18n="reviews.eyebrow">صوت المتعلم</span>
              <h3 class="font-['Cairo'] font-bold text-2xl" data-i18n="reviews.addReviewCardTitle">شاركنا تجربتك التعليمية</h3>
              <p class="text-xs text-blue-100 font-medium leading-relaxed" data-i18n="reviews.addReviewCardDesc">
                رأيك يساهم في إلهام غيرك وتطوير برامجنا التعليمية باستمرار.
              </p>
            </div>

            <button
              type="button"
              id="open-review-modal-btn"
              class="mt-6 w-full py-3 px-4 bg-white text-[#012383] font-bold text-sm rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 shadow-xs cursor-pointer"
            >
              <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              <span data-i18n="reviews.addReviewBtn">أضف تقييمك الآن</span>
            </button>
          </div>

        </section>

        <!-- 3. INTERACTIVE FILTER CHIPS & SORT BAR (WorkDo SaaS Filters) -->
        <section class="flex flex-col md:flex-row gap-4 items-center justify-between bg-white border border-[#DEE8F7] rounded-[16px] p-3.5 shadow-xs">
          
          <!-- Category Pills -->
          <div class="flex flex-wrap items-center gap-2 w-full md:w-auto" id="filter-pills-group">
            <button
              type="button"
              data-filter="all"
              class="filter-pill active px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer"
              data-i18n="reviews.filterAll"
            >
              الكل
            </button>
            <button
              type="button"
              data-filter="5star"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filter5Star"
            >
              ٥ نجوم
            </button>
            <button
              type="button"
              data-filter="cert"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterWithCert"
            >
              مع شهادة إتمام
            </button>
            <button
              type="button"
              data-filter="speech"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterSpeechDelay"
            >
              تأخر الكلام
            </button>
            <button
              type="button"
              data-filter="articulation"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterArticulation"
            >
              مخارج الحروف
            </button>
            <button
              type="button"
              data-filter="parent"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterParentTraining"
            >
              تدريب الوالدين
            </button>
          </div>

          <!-- Sort Select -->
          <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
            <span class="text-xs font-semibold text-[#475569] whitespace-nowrap" data-i18n="reviews.sortLabel">الترتيب حسب:</span>
            <select
              id="sort-select"
              class="bg-[#FAFCFF] border border-[#DEE8F7] text-xs font-bold text-[#012383] rounded-lg px-2.5 py-1.5 outline-none cursor-pointer focus:border-[#0B2A8F]"
            >
              <option value="recent" data-i18n="reviews.sortRecent">الأحدث</option>
              <option value="highest" data-i18n="reviews.sortHighest">الأعلى تقييماً</option>
              <option value="helpful" data-i18n="reviews.sortHelpful">الأكثر فائدة</option>
            </select>
          </div>

        </section>

        <!-- 4. SPOTLIGHT CASE STUDY CARD (WorkDo Featured Card) -->
        <section class="bg-gradient-to-r from-white via-white to-[#FAFCFF] border-2 border-[#10B77F]/30 rounded-[20px] p-6 lg:p-8 shadow-card flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
          <div class="size-24 rounded-2xl bg-[#EEF7F3] border border-[#10B77F]/20 flex items-center justify-center shrink-0 overflow-hidden relative shadow-xs">
            <img src="/assets/images/tip-1.png" alt="د. ريم القحطاني" class="size-full object-cover" />
          </div>

          <div class="flex-1 flex flex-col gap-3">
            <div class="flex flex-wrap items-center gap-2">
              <span class="px-3 py-1 rounded-full bg-[#EEF7F3] text-[#10B77F] text-xs font-bold" data-i18n="reviews.spotlightEyebrow">
                قصة نجاح مميزة
              </span>
              <span class="px-2.5 py-0.5 rounded-full bg-[#EAF1FB] text-[#0B2A8F] text-[11px] font-bold" data-i18n="reviews.spotlightCourse">
                دورة استراتيجيات تنمية اللغة
              </span>
              <div class="flex text-[#F59E0B] text-sm">★★★★★</div>
            </div>

            <h3 class="font-['Cairo'] font-bold text-lg md:text-xl text-[#012383]" data-i18n="reviews.spotlightTitle">
              «تطوّر مستواي في التشخيص السريري خلال ٦ أسابيع من التمارين العملية المنتظمة»
            </h3>

            <p class="text-sm text-[#475569] font-medium leading-relaxed" data-i18n="reviews.spotlightQuote">
              كنت أبحث عن برنامج علمي وتطبيقي موثوق لتنمية مهاراتي في قيادة جلسات التخاطب. التمارين التفاعلية والتقييم الدقيق في منصة مركز الأمين أحدثا فارقاً ملموساً في كفاءتي السريرية وثقتي المهنية.
            </p>

            <div class="flex items-center gap-3 pt-2 text-xs font-bold text-[#012383]">
              <span data-i18n="reviews.spotlightAuthor">د. عمر القحطاني</span>
              <span class="text-slate-300">•</span>
              <span class="text-[#475569]" data-i18n="reviews.spotlightRole">طالب وباحث في علوم التخاطب</span>
              <span class="text-slate-300">•</span>
              <span class="text-emerald-600 flex items-center gap-1 font-semibold">
                <svg class="size-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span data-i18n="reviews.verifiedBadge">طالب موثق</span>
              </span>
            </div>
          </div>
        </section>

        <!-- 5. REVIEWS MASONRY / GRID (6 Realistic Reviews) -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="reviews-grid">
          
          <!-- Card 1 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="speech" data-rating="5" data-cert="true" data-helpful="24">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    س
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">سامي المنصور</h4>
                    <span class="text-[11px] text-[#475569]">منذ أسبوعين</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">تطبيقات مخارج الحروف</span>
                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold">شهادة معتمدة</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«تمارين واضحة ونتائج مباشرة في تصحيح مخارج الحروف سريرياً»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                الكورس ممتاز من حيث الترتيب المنهجي. الفيديوهات مركزة، وملاحظات المحاضر على تحليلي الصوتي ساعدتني كثيراً في فهم مخارج الحروف سريرياً.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">أتم ٩٥٪ من متطلبات الكورس</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">24</span>
              </button>
            </div>
          </article>

          <!-- Card 2 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="articulation" data-rating="5" data-cert="true" data-helpful="18">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    خ
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">خالد العمري</h4>
                    <span class="text-[11px] text-[#475569]">منذ ٣ أسابيع</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">أساسيات التخاطب</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«أفضل استثمار في مسيرتي كطالب وباحث في علوم التخاطب»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                استفدت كثيراً من النماذج التطبيقية واستمارة الملاحظة السريرية المرفقة بتوجيه المحاضرين. أنصح كل دارس في علوم التخاطب بهذه المنصة.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">أتم الكورس بدرجة امتياز</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">18</span>
              </button>
            </div>
          </article>

          <!-- Card 3 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="parent" data-rating="5" data-cert="false" data-helpful="31">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ف
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">فهد الدوسري</h4>
                    <span class="text-[11px] text-[#475569]">منذ شهر</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">تدريب التخاطب السريري</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«منهجية علمية واضحة في تصحيح اللدغات الصوتية»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                الكورس قدّم تدريباً دقيقاً على تقنيات المحاكاة وتعديل مخارج الأصوات. التقييم المباشر للمهام من المحاضر عزز مهاراتي التطبيقية بشكل ملحوظ.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-blue-600 font-bold">طالب معتمد - نتائج ممتازة</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">31</span>
              </button>
            </div>
          </article>

          <!-- Card 4 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="stuttering" data-rating="4" data-cert="true" data-helpful="14">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ع
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">عبدالله الحربي</h4>
                    <span class="text-[11px] text-[#475569]">منذ شهر</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★☆</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">إدارة التأتأة والطلاقة</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«تمارين التنفس والتحكم بالإيقاع أحدثت فارقاً»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                المحتوى عميق والأسلوب مبسط جداً. تقييم المحاضر الكتابي للمهام السريرية كان ممتازاً ومفصلاً وساعدني على فهم آليات التحكم بالطلاقة.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">شهادة معتمدة</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">14</span>
              </button>
            </div>
          </article>

          <!-- Card 5 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="speech" data-rating="5" data-cert="true" data-helpful="20">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ن
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">نايف القاسم</h4>
                    <span class="text-[11px] text-[#475569]">منذ ٥ أسابيع</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">تطبيقات مخارج الحروف</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«تطبيقات علمية دقيقة في الفحص السريري للنطق»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                طالب دراسات عليا وكنت أحتاج أدوات علمية للتعامل مع اضطرابات مخارج الحروف سريرياً. الأنشطة والتدريبات التفاعلية مع المحاضر ممتعة وعميقة للغاية.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">شهادة إتمام معتمدة</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">20</span>
              </button>
            </div>
          </article>

          <!-- Card 6 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="speech" data-rating="5" data-cert="true" data-helpful="28">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ي
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">يوسف النجار</h4>
                    <span class="text-[11px] text-[#475569]">منذ ٦ أسابيع</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">استراتيجيات تنمية اللغة</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«تدرج منهجي متقن من التقييم حتى التدخل العلاجي»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                الشكر موصول لمنصة مركز الأمين والمحاضرين على الدعم المتواصل. الملاحظات الدورية بعد كل مهمة جعلتني أمتلك ثقة تامة في تطبيق خطط التخاطب السريرية.
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">أتم الكورس بنجاح</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">28</span>
              </button>
            </div>
          </article>

        </section>
    </div>

    <x-slot:modals>
<div id="review-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-[24px] max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-[#DEE8F7] relative flex flex-col gap-5 max-h-[90vh] overflow-y-auto">
          
          <!-- Modal Header -->
          <div class="flex items-start justify-between">
            <div>
              <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl text-[#012383]" data-i18n="reviews.modalTitle">إضافة تقييم جديد</h3>
              <p class="text-xs text-[#475569] font-medium" data-i18n="reviews.modalSubtitle">شارك تجربتك وانطباعك عن الدورة التدريبية</p>
            </div>
            <button
              type="button"
              id="close-review-modal-btn"
              class="size-8 rounded-full bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#012383] flex items-center justify-center font-bold text-lg transition-colors cursor-pointer"
            >
              ✕
            </button>
          </div>

          <!-- Interactive Star Picker -->
          <div class="flex flex-col gap-2">
            <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalRatingLabel">تقييمك العام للدورة</label>
            <div class="flex items-center gap-2 text-3xl cursor-pointer" id="modal-star-group">
              <span class="star-item text-amber-400" data-val="1">★</span>
              <span class="star-item text-amber-400" data-val="2">★</span>
              <span class="star-item text-amber-400" data-val="3">★</span>
              <span class="star-item text-amber-400" data-val="4">★</span>
              <span class="star-item text-amber-400" data-val="5">★</span>
            </div>
            <input type="hidden" id="modal-rating-value" value="5" />
          </div>

          <!-- Course Selector -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalCourseLabel">الدورة التي حضرتها</label>
            <select id="modal-course-select" class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-3.5 py-2.5 text-xs font-medium text-[#012383] outline-none focus:border-[#0B2A8F]">
              <option value="lang-dev">دورة استراتيجيات تنمية اللغة</option>
              <option value="articulation">دورة تطبيقات مخارج الحروف</option>
              <option value="basics">دورة أساسيات التخاطب</option>
              <option value="stuttering">دورة إدارة التأتأة والطلاقة</option>
            </select>
          </div>

          <!-- Review Headline -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalTitleLabel">عنوان التقييم</label>
            <input
              type="text"
              id="modal-review-title"
              placeholder="مثال: تجربة رائعة وتمارين عملية فعالة"
              data-i18n-placeholder="reviews.modalTitlePlaceholder"
              class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-3.5 py-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F]"
            />
          </div>

          <!-- Feedback Details -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalFeedbackLabel">تفاصيل تجربتك والنتائج التي حققتها</label>
            <textarea
              id="modal-review-text"
              rows="3"
              placeholder="اكتب رأيك بالتفصيل هنا وكيف أثرت الدورة في تعلّمك..."
              data-i18n-placeholder="reviews.modalFeedbackPlaceholder"
              class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl p-3 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F] resize-none"
            ></textarea>
          </div>

          <!-- File Upload Dropzone -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalUploadLabel">إرفاق إثبات أو شهادة (اختياري)</label>
            <div class="border-2 border-dashed border-[#DEE8F7] hover:border-[#0B2A8F] rounded-xl p-4 text-center cursor-pointer transition-colors bg-[#FAFCFF]">
              <p class="text-[11px] text-[#475569] font-medium" data-i18n="reviews.modalUploadHint">
                اسحب الملف هنا أو انقر للتصفح (PNG, JPG, PDF حتى 5MB)
              </p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end gap-3 pt-2">
            <button
              type="button"
              id="cancel-review-btn"
              class="px-4 py-2 rounded-xl text-xs font-bold text-[#475569] hover:bg-slate-100 transition-colors cursor-pointer"
              data-i18n="reviews.modalCancelBtn"
            >
              إلغاء
            </button>
            <button
              type="button"
              id="submit-review-btn"
              class="px-6 py-2.5 rounded-xl text-xs font-bold bg-[#012383] text-white hover:bg-[#0B2A8F] transition-all shadow-xs cursor-pointer"
              data-i18n="reviews.modalSubmitBtn"
            >
              نشر التقييم
            </button>
          </div>

        </div>
      </div>

      <!-- ============================================== -->
    </x-slot:modals>

    <x-slot:scripts>

    </x-slot:scripts>
</x-layouts::marketing>
