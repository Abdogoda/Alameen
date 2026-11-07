<x-layouts::marketing :title="__('آراء وتقييمات الطلاب')" active="reviews">
    <div class="flex-1 w-full max-w-[1536px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-24 2xl:px-32 py-8 lg:py-12 flex flex-col gap-10">
<!-- 1. HERO SECTION & TRUST METRICS (WorkDo SaaS Hero Pattern) -->
        <section class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto">
          
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#10B77F]/30 text-[#10B77F] text-xs font-bold">
            <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span data-i18n="reviews.eyebrow">{{ __('reviews.eyebrow') }}</span>
          </div>

          <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight" data-i18n="reviews.heroTitle">
            {{ __('reviews.heroTitle') }}
          </h1>

          <p class="text-base sm:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed" data-i18n="reviews.heroDesc">
            {{ __('reviews.heroDesc') }}
          </p>

          <!-- Trust Badges 4-Item Grid -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full mt-4">
            
            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <div class="flex items-center gap-1 text-[#F59E0B] text-xl font-extrabold mb-1">
                <span>★</span>
                <span data-i18n="reviews.statRating">{{ __('reviews.statRating') }}</span>
              </div>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statRatingSub">{{ __('reviews.statRatingSub') }}</span>
            </div>

            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <span class="text-xl font-extrabold text-[#0B2A8F] mb-1" data-i18n="reviews.statReviews">{{ __('reviews.statReviews') }}</span>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statReviewsSub">{{ __('reviews.statReviewsSub') }}</span>
            </div>

            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <span class="text-xl font-extrabold text-[#10B77F] mb-1" data-i18n="reviews.statSatisfaction">{{ __('reviews.statSatisfaction') }}</span>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statSatisfactionSub">{{ __('reviews.statSatisfactionSub') }}</span>
            </div>

            <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-4 flex flex-col items-center justify-center shadow-xs">
              <span class="text-xl font-extrabold text-[#012383] mb-1" data-i18n="reviews.statVerified">{{ __('reviews.statVerified') }}</span>
              <span class="text-xs font-semibold text-[#475569]" data-i18n="reviews.statVerifiedSub">{{ __('reviews.statVerifiedSub') }}</span>
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
              <span class="text-xs font-bold text-[#475569]" data-i18n="reviews.statReviewsSub">{{ __('reviews.statReviewsSub') }}</span>
            </div>

            <!-- Right: Breakdown Bars -->
            <div class="flex-1 w-full flex flex-col gap-2.5">
              
              <!-- 5 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star5">{{ __('reviews.star5') }}</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-[#10B77F] rounded-full" style="width: 88%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">88%</span>
              </div>

              <!-- 4 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star4">{{ __('reviews.star4') }}</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-[#0B2A8F] rounded-full" style="width: 9%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">9%</span>
              </div>

              <!-- 3 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star3">{{ __('reviews.star3') }}</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-amber-400 rounded-full" style="width: 2%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">2%</span>
              </div>

              <!-- 2 Stars -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star2">{{ __('reviews.star2') }}</span>
                <div class="flex-1 h-2.5 bg-[#EAF1FB] rounded-full overflow-hidden">
                  <div class="h-full bg-slate-400 rounded-full" style="width: 1%"></div>
                </div>
                <span class="w-10 text-start text-[#475569]">1%</span>
              </div>

              <!-- 1 Star -->
              <div class="flex items-center gap-3 text-xs font-bold">
                <span class="w-14 text-end text-[#012383]" data-i18n="reviews.star1">{{ __('reviews.star1') }}</span>
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
              <span class="text-xs font-bold text-blue-200 uppercase tracking-wider" data-i18n="reviews.eyebrow">{{ __('reviews.eyebrow') }}</span>
              <h3 class="font-['Cairo'] font-bold text-2xl" data-i18n="reviews.addReviewCardTitle">{{ __('reviews.addReviewCardTitle') }}</h3>
              <p class="text-xs text-blue-100 font-medium leading-relaxed" data-i18n="reviews.addReviewCardDesc">
                {{ __('reviews.addReviewCardDesc') }}
              </p>
            </div>

            <button
              type="button"
              id="open-review-modal-btn"
              class="mt-6 w-full py-3 px-4 bg-white !text-[#012383] font-bold text-sm rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 shadow-xs cursor-pointer"
              style="color: #012383 !important;"
            >
              <svg class="size-4 !text-[#012383]" style="color: #012383 !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              <span data-i18n="reviews.addReviewBtn" class="!text-[#012383]" style="color: #012383 !important;">{{ __('reviews.addReviewBtn') }}</span>
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
              class="filter-pill active px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#012383] text-white hover:bg-[#0B2A8F] cursor-pointer"
              data-i18n="reviews.filterAll"
            >
              {{ __('reviews.filterAll') }}
            </button>
            <button
              type="button"
              data-filter="images"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer flex items-center gap-1.5"
            >
              <span>📷</span>
              <span data-i18n="reviews.filterWithImages">{{ __('آراء بالصور المرفقة') }}</span>
            </button>
            <button
              type="button"
              data-filter="5star"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filter5Star"
            >
              {{ __('reviews.filter5Star') }}
            </button>
            <button
              type="button"
              data-filter="cert"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterWithCert"
            >
              {{ __('reviews.filterWithCert') }}
            </button>
            <button
              type="button"
              data-filter="speech"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterSpeechDelay"
            >
              {{ __('reviews.filterSpeechDelay') }}
            </button>
            <button
              type="button"
              data-filter="articulation"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterArticulation"
            >
              {{ __('reviews.filterArticulation') }}
            </button>
            <button
              type="button"
              data-filter="stuttering"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterStuttering"
            >
              {{ __('reviews.filterStuttering') }}
            </button>
            <button
              type="button"
              data-filter="parent"
              class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#EAF1FB] text-[#0B2A8F] hover:bg-[#DEE8F7] cursor-pointer"
              data-i18n="reviews.filterParentTraining"
            >
              {{ __('reviews.filterParentTraining') }}
            </button>
          </div>

          <!-- Sort Select -->
          <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
            <span class="text-xs font-semibold text-[#475569] whitespace-nowrap" data-i18n="reviews.sortLabel">{{ __('reviews.sortLabel') }}</span>
            <select
              id="sort-select"
              class="bg-[#FAFCFF] border border-[#DEE8F7] text-xs font-bold text-[#012383] rounded-lg px-2.5 py-1.5 outline-none cursor-pointer focus:border-[#0B2A8F]"
            >
              <option value="recent" data-i18n="reviews.sortRecent">{{ __('reviews.sortRecent') }}</option>
              <option value="highest" data-i18n="reviews.sortHighest">{{ __('reviews.sortHighest') }}</option>
              <option value="helpful" data-i18n="reviews.sortHelpful">{{ __('reviews.sortHelpful') }}</option>
            </select>
          </div>

        </section>

        <!-- 4. SPOTLIGHT CASE STUDY CARD (WorkDo Featured Card) -->
        <section id="spotlight-card" class="bg-gradient-to-r from-white via-white to-[#FAFCFF] border-2 border-[#10B77F]/30 rounded-[20px] p-6 lg:p-8 shadow-card flex flex-col md:flex-row items-center gap-8 relative overflow-hidden transition-all duration-300" data-category="speech" data-rating="5" data-cert="true">
          <div class="size-24 rounded-2xl bg-[#EEF7F3] border border-[#10B77F]/20 flex items-center justify-center shrink-0 overflow-hidden relative shadow-xs">
            <img src="/assets/images/tip-1.png" alt="{{ __('reviews.spotlightAuthor') }}" class="size-full object-cover" />
          </div>

          <div class="flex-1 flex flex-col gap-3">
            <div class="flex flex-wrap items-center gap-2">
              <span class="px-3 py-1 rounded-full bg-[#EEF7F3] text-[#10B77F] text-xs font-bold" data-i18n="reviews.spotlightEyebrow">
                {{ __('reviews.spotlightEyebrow') }}
              </span>
              <span class="px-2.5 py-0.5 rounded-full bg-[#EAF1FB] text-[#0B2A8F] text-[11px] font-bold" data-i18n="reviews.spotlightCourse">
                {{ __('reviews.spotlightCourse') }}
              </span>
              <div class="flex text-[#F59E0B] text-sm">★★★★★</div>
            </div>

            <h3 class="font-['Cairo'] font-bold text-lg md:text-xl text-[#012383]" data-i18n="reviews.spotlightTitle">
              {{ __('reviews.spotlightTitle') }}
            </h3>

            <p class="text-sm text-[#475569] font-medium leading-relaxed" data-i18n="reviews.spotlightQuote">
              {{ __('reviews.spotlightQuote') }}
            </p>

            <div class="flex items-center gap-3 pt-2 text-xs font-bold text-[#012383]">
              <span data-i18n="reviews.spotlightAuthor">{{ __('reviews.spotlightAuthor') }}</span>
              <span class="text-slate-300">•</span>
              <span class="text-[#475569]" data-i18n="reviews.spotlightRole">{{ __('reviews.spotlightRole') }}</span>
              <span class="text-slate-300">•</span>
              <span class="text-emerald-600 flex items-center gap-1 font-semibold">
                <svg class="size-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span data-i18n="reviews.verifiedBadge">{{ __('reviews.verifiedBadge') }}</span>
              </span>
            </div>
          </div>
        </section>

        <!-- 5. REVIEWS MASONRY / GRID (6 Realistic Reviews) -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="reviews-grid">
          
          <!-- Card 1 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="speech" data-rating="5" data-cert="true" data-has-image="true" data-helpful="24" data-order="1">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    س
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">{{ __('reviews.card1Author') }}</h4>
                    <span class="text-[11px] text-[#475569]">{{ __('reviews.card1Time') }}</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">{{ __('reviews.verifiedBadge') }}</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">{{ __('reviews.card1Course') }}</span>
                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold">{{ __('reviews.card1BadgeCert') }}</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">{{ __('reviews.card1Title') }}</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                {{ __('reviews.card1Text') }}
              </p>

              <!-- Attached Review Image -->
              <div class="mt-1 pt-1">
                <div
                  class="group/attach relative rounded-xl overflow-hidden border border-[#DEE8F7] bg-slate-50 cursor-pointer hover:border-[#0B2A8F] hover:shadow-xs transition-all"
                  onclick="openReviewImageLightbox('/assets/images/alameen-images/2.jpeg')"
                  title="{{ __('انقر لتكبير الصورة') }}"
                >
                  <img
                    src="/assets/images/alameen-images/2.jpeg"
                    alt="{{ __('صورة تقييم موثق') }}"
                    class="w-full h-36 sm:h-40 object-cover object-top group-hover/attach:scale-105 transition-transform duration-300"
                    loading="lazy"
                  />
                  <div class="absolute inset-0 bg-black/25 opacity-0 group-hover/attach:opacity-100 transition-opacity flex items-center justify-center gap-1.5 text-white text-xs font-bold pointer-events-none">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                    </svg>
                    <span>{{ __('تكبير الصورة') }}</span>
                  </div>
                  <span class="absolute bottom-2 start-2 bg-black/65 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 pointer-events-none">
                    <span>📷</span>
                    <span>{{ __('صورة مرفقة') }}</span>
                  </span>
                </div>
              </div>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">{{ __('reviews.card1Footer') }}</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">24</span>
              </button>
            </div>
          </article>

          <!-- Card 2 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="articulation" data-rating="5" data-cert="true" data-has-image="true" data-helpful="18" data-order="2">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    خ
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">{{ __('reviews.card2Author') }}</h4>
                    <span class="text-[11px] text-[#475569]">{{ __('reviews.card2Time') }}</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">{{ __('reviews.verifiedBadge') }}</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">{{ __('reviews.card2Course') }}</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">{{ __('reviews.card2Title') }}</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                {{ __('reviews.card2Text') }}
              </p>

              <!-- Attached Review Image -->
              <div class="mt-1 pt-1">
                <div
                  class="group/attach relative rounded-xl overflow-hidden border border-[#DEE8F7] bg-slate-50 cursor-pointer hover:border-[#0B2A8F] hover:shadow-xs transition-all"
                  onclick="openReviewImageLightbox('/assets/images/alameen-images/1.jpeg')"
                  title="{{ __('انقر لتكبير الصورة') }}"
                >
                  <img
                    src="/assets/images/alameen-images/1.jpeg"
                    alt="{{ __('صورة تقييم موثق') }}"
                    class="w-full h-36 sm:h-40 object-cover object-top group-hover/attach:scale-105 transition-transform duration-300"
                    loading="lazy"
                  />
                  <div class="absolute inset-0 bg-black/25 opacity-0 group-hover/attach:opacity-100 transition-opacity flex items-center justify-center gap-1.5 text-white text-xs font-bold pointer-events-none">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                    </svg>
                    <span>{{ __('تكبير الصورة') }}</span>
                  </div>
                  <span class="absolute bottom-2 start-2 bg-black/65 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 pointer-events-none">
                    <span>📷</span>
                    <span>{{ __('صورة مرفقة') }}</span>
                  </span>
                </div>
              </div>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">{{ __('reviews.card2Footer') }}</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">18</span>
              </button>
            </div>
          </article>

          <!-- Card 3 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="parent" data-rating="5" data-cert="false" data-has-image="true" data-helpful="31" data-order="3">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ف
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">{{ __('reviews.card3Author') }}</h4>
                    <span class="text-[11px] text-[#475569]">{{ __('reviews.card3Time') }}</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">{{ __('reviews.verifiedBadge') }}</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">{{ __('reviews.card3Course') }}</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">{{ __('reviews.card3Title') }}</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                {{ __('reviews.card3Text') }}
              </p>

              <!-- Attached Review Image -->
              <div class="mt-1 pt-1">
                <div
                  class="group/attach relative rounded-xl overflow-hidden border border-[#DEE8F7] bg-slate-50 cursor-pointer hover:border-[#0B2A8F] hover:shadow-xs transition-all"
                  onclick="openReviewImageLightbox('/assets/images/alameen-images/3.jpeg')"
                  title="{{ __('انقر لتكبير الصورة') }}"
                >
                  <img
                    src="/assets/images/alameen-images/3.jpeg"
                    alt="{{ __('صورة تقييم موثق') }}"
                    class="w-full h-36 sm:h-40 object-cover object-top group-hover/attach:scale-105 transition-transform duration-300"
                    loading="lazy"
                  />
                  <div class="absolute inset-0 bg-black/25 opacity-0 group-hover/attach:opacity-100 transition-opacity flex items-center justify-center gap-1.5 text-white text-xs font-bold pointer-events-none">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                    </svg>
                    <span>{{ __('تكبير الصورة') }}</span>
                  </div>
                  <span class="absolute bottom-2 start-2 bg-black/65 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 pointer-events-none">
                    <span>📷</span>
                    <span>{{ __('صورة مرفقة') }}</span>
                  </span>
                </div>
              </div>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-blue-600 font-bold">{{ __('reviews.card3Footer') }}</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">31</span>
              </button>
            </div>
          </article>

          <!-- Card 4 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="stuttering" data-rating="4" data-cert="true" data-has-image="true" data-helpful="14" data-order="4">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ع
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">{{ __('reviews.card4Author') }}</h4>
                    <span class="text-[11px] text-[#475569]">{{ __('reviews.card4Time') }}</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★☆</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">{{ __('reviews.verifiedBadge') }}</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">{{ __('reviews.card4Course') }}</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">{{ __('reviews.card4Title') }}</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                {{ __('reviews.card4Text') }}
              </p>

              <!-- Attached Review Image (Certificate Verification) -->
              <div class="mt-1 pt-1">
                <div
                  class="group/attach relative rounded-xl overflow-hidden border border-[#DEE8F7] bg-slate-50 cursor-pointer hover:border-[#0B2A8F] hover:shadow-xs transition-all"
                  onclick="openReviewImageLightbox('/assets/images/alameen-images/certificate.png')"
                  title="{{ __('انقر لتكبير الشهادة المعتمدة') }}"
                >
                  <img
                    src="/assets/images/alameen-images/certificate.png"
                    alt="{{ __('شهادة معتمدة موثقة') }}"
                    class="w-full h-36 sm:h-40 object-cover object-center group-hover/attach:scale-105 transition-transform duration-300"
                    loading="lazy"
                  />
                  <div class="absolute inset-0 bg-black/25 opacity-0 group-hover/attach:opacity-100 transition-opacity flex items-center justify-center gap-1.5 text-white text-xs font-bold pointer-events-none">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                    </svg>
                    <span>{{ __('تكبير الشهادة') }}</span>
                  </div>
                  <span class="absolute bottom-2 start-2 bg-emerald-800/80 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 pointer-events-none">
                    <span>🎓</span>
                    <span>{{ __('شهادة موثقة') }}</span>
                  </span>
                </div>
              </div>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">{{ __('reviews.card4BadgeCert') }}</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">14</span>
              </button>
            </div>
          </article>

          <!-- Card 5 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="speech" data-rating="5" data-cert="true" data-has-image="true" data-helpful="20" data-order="5">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ن
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">{{ __('reviews.card5Author') }}</h4>
                    <span class="text-[11px] text-[#475569]">{{ __('reviews.card5Time') }}</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">{{ __('reviews.verifiedBadge') }}</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">{{ __('reviews.card5Course') }}</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">{{ __('reviews.card5Title') }}</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                {{ __('reviews.card5Text') }}
              </p>

              <!-- Attached Review Image -->
              <div class="mt-1 pt-1">
                <div
                  class="group/attach relative rounded-xl overflow-hidden border border-[#DEE8F7] bg-slate-50 cursor-pointer hover:border-[#0B2A8F] hover:shadow-xs transition-all"
                  onclick="openReviewImageLightbox('/assets/images/alameen-images/4.jpeg')"
                  title="{{ __('انقر لتكبير الصورة') }}"
                >
                  <img
                    src="/assets/images/alameen-images/4.jpeg"
                    alt="{{ __('صورة تقييم موثق') }}"
                    class="w-full h-36 sm:h-40 object-cover object-top group-hover/attach:scale-105 transition-transform duration-300"
                    loading="lazy"
                  />
                  <div class="absolute inset-0 bg-black/25 opacity-0 group-hover/attach:opacity-100 transition-opacity flex items-center justify-center gap-1.5 text-white text-xs font-bold pointer-events-none">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                    </svg>
                    <span>{{ __('تكبير الصورة') }}</span>
                  </div>
                  <span class="absolute bottom-2 start-2 bg-black/65 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 pointer-events-none">
                    <span>📷</span>
                    <span>{{ __('صورة مرفقة') }}</span>
                  </span>
                </div>
              </div>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">{{ __('reviews.card5Footer') }}</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">20</span>
              </button>
            </div>
          </article>

          <!-- Card 6 -->
          <article class="review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all" data-category="speech" data-rating="5" data-cert="true" data-has-image="true" data-helpful="28" data-order="6">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    ي
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">{{ __('reviews.card6Author') }}</h4>
                    <span class="text-[11px] text-[#475569]">{{ __('reviews.card6Time') }}</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">★★★★★</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">{{ __('reviews.verifiedBadge') }}</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">{{ __('reviews.card6Course') }}</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">{{ __('reviews.card6Title') }}</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">
                {{ __('reviews.card6Text') }}
              </p>

              <!-- Attached Review Image -->
              <div class="mt-1 pt-1">
                <div
                  class="group/attach relative rounded-xl overflow-hidden border border-[#DEE8F7] bg-slate-50 cursor-pointer hover:border-[#0B2A8F] hover:shadow-xs transition-all"
                  onclick="openReviewImageLightbox('/assets/images/alameen-images/5.jpeg')"
                  title="{{ __('انقر لتكبير الصورة') }}"
                >
                  <img
                    src="/assets/images/alameen-images/5.jpeg"
                    alt="{{ __('صورة تقييم موثق') }}"
                    class="w-full h-36 sm:h-40 object-cover object-top group-hover/attach:scale-105 transition-transform duration-300"
                    loading="lazy"
                  />
                  <div class="absolute inset-0 bg-black/25 opacity-0 group-hover/attach:opacity-100 transition-opacity flex items-center justify-center gap-1.5 text-white text-xs font-bold pointer-events-none">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                    </svg>
                    <span>{{ __('تكبير الصورة') }}</span>
                  </div>
                  <span class="absolute bottom-2 start-2 bg-black/65 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 pointer-events-none">
                    <span>📷</span>
                    <span>{{ __('صورة مرفقة') }}</span>
                  </span>
                </div>
              </div>
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">{{ __('reviews.card6Footer') }}</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">28</span>
              </button>
            </div>
          </article>

          <!-- Empty State -->
          <div id="reviews-empty" class="col-span-full hidden py-16 flex flex-col items-center justify-center text-center">
            <div class="size-16 rounded-full bg-[#EAF1FB] flex items-center justify-center text-[#0B2A8F] mb-4 shadow-xs">
              <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-base font-bold text-[#012383] mb-1">{{ __('reviews.emptyTitle') }}</h3>
            <p class="text-xs text-[#475569]">{{ __('reviews.emptyDesc') }}</p>
          </div>

        </section>
    </div>

    <x-slot:modals>
      <div id="review-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
        <div class="bg-white rounded-[24px] max-w-lg w-full shadow-2xl border border-[#DEE8F7] relative flex flex-col my-auto overflow-hidden animate-in fade-in zoom-in-95 duration-200">
          
          <!-- Modal Header (Fixed) -->
          <div class="flex items-center justify-between px-6 py-4 border-b border-[#DEE8F7] shrink-0 bg-white">
            <div>
              <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-[#012383]" data-i18n="reviews.modalTitle">{{ __('reviews.modalTitle') }}</h3>
              <p class="text-xs text-[#475569] font-medium" data-i18n="reviews.modalSubtitle">{{ __('reviews.modalSubtitle') }}</p>
            </div>
            <button
              type="button"
              id="close-review-modal-btn"
              class="size-8 rounded-full bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#012383] flex items-center justify-center font-bold text-base transition-colors cursor-pointer shrink-0"
            >
              ✕
            </button>
          </div>

          <!-- Modal Body (Compact & Scrollable if needed) -->
          <div class="px-6 py-4 flex flex-col gap-3.5 overflow-y-auto max-h-[calc(85vh-130px)] [scrollbar-width:thin] [scrollbar-color:#DEE8F7_transparent]">
            
            <!-- Interactive Star Picker -->
            <div class="flex flex-col gap-1">
              <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalRatingLabel">{{ __('reviews.modalRatingLabel') }}</label>
              <div class="flex items-center gap-1.5 text-2xl cursor-pointer" id="modal-star-group">
                <span class="star-item text-amber-400 select-none hover:scale-110 transition-transform" data-val="1">★</span>
                <span class="star-item text-amber-400 select-none hover:scale-110 transition-transform" data-val="2">★</span>
                <span class="star-item text-amber-400 select-none hover:scale-110 transition-transform" data-val="3">★</span>
                <span class="star-item text-amber-400 select-none hover:scale-110 transition-transform" data-val="4">★</span>
                <span class="star-item text-amber-400 select-none hover:scale-110 transition-transform" data-val="5">★</span>
              </div>
              <input type="hidden" id="modal-rating-value" value="5" />
            </div>

            <!-- Course Selector -->
            <div class="flex flex-col gap-1">
              <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalCourseLabel">{{ __('reviews.modalCourseLabel') }}</label>
              <select id="modal-course-select" class="w-full bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-3.5 py-2 text-xs font-medium text-[#012383] outline-none focus:border-[#0B2A8F]">
                <option value="lang-dev">{{ __('catalog.course1Title') }}</option>
                <option value="articulation">{{ __('catalog.course2Title') }}</option>
                <option value="basics">{{ __('catalog.course3Title') }}</option>
                <option value="stuttering">{{ __('catalog.course4Title') }}</option>
              </select>
            </div>

            <!-- Review Headline -->
            <div class="flex flex-col gap-1">
              <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalTitleLabel">{{ __('reviews.modalTitleLabel') }}</label>
              <input
                type="text"
                id="modal-review-title"
                placeholder="{{ __('reviews.modalTitlePlaceholder') }}"
                data-i18n-placeholder="reviews.modalTitlePlaceholder"
                class="w-full bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-3.5 py-2 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F]"
              />
            </div>

            <!-- Feedback Details -->
            <div class="flex flex-col gap-1">
              <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalFeedbackLabel">{{ __('reviews.modalFeedbackLabel') }}</label>
              <textarea
                id="modal-review-text"
                rows="2"
                placeholder="{{ __('reviews.modalFeedbackPlaceholder') }}"
                data-i18n-placeholder="reviews.modalFeedbackPlaceholder"
                class="w-full bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl p-2.5 text-xs text-[#012383] placeholder-slate-400 outline-none focus:border-[#0B2A8F] resize-none"
              ></textarea>
            </div>

            <!-- File Upload Dropzone with Live Image Preview -->
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-bold text-[#012383]" data-i18n="reviews.modalUploadLabel">{{ __('reviews.modalUploadLabel') }}</label>
              <input type="file" id="modal-file-input" accept="image/*,.pdf" class="hidden" />
              
              <div
                id="modal-upload-box"
                class="border-2 border-dashed border-[#DEE8F7] hover:border-[#0B2A8F] rounded-xl p-3 text-center cursor-pointer transition-colors bg-[#FAFCFF] flex flex-col items-center justify-center gap-2 group"
              >
                <!-- Default Placeholder State -->
                <div id="modal-upload-placeholder" class="flex flex-col items-center gap-1.5 py-1">
                  <div class="size-8 rounded-full bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center text-sm group-hover:scale-110 transition-transform">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <p id="modal-upload-filename" class="text-xs text-[#012383] font-bold">
                    {{ __('انقر لرفع صورة التقييم أو لقطة شاشة') }}
                  </p>
                  <span class="text-[10px] text-[#475569]">PNG, JPG, WebP أو لقطات محادثات حتى 5MB</span>
                </div>

                <!-- Live Preview State (Hidden initially) -->
                <div id="modal-image-preview-wrap" class="hidden w-full flex flex-col items-center gap-2 py-1">
                  <div class="relative max-h-36 rounded-lg overflow-hidden border border-[#DEE8F7] shadow-xs bg-black/5">
                    <img id="modal-image-preview" src="" alt="معاينة الصورة" class="max-h-36 object-contain rounded-lg" />
                    <button
                      type="button"
                      id="remove-modal-image-btn"
                      class="absolute top-1 end-1 size-6 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center text-xs font-bold transition-colors cursor-pointer shadow-md"
                      title="إزالة الصورة"
                    >
                      ✕
                    </button>
                  </div>
                  <span id="modal-image-name" class="text-[11px] text-[#012383] font-semibold truncate max-w-[220px]"></span>
                </div>
              </div>
            </div>

          </div>

          <!-- Modal Footer (Docked / Always Visible) -->
          <div class="flex items-center justify-end gap-3 px-6 py-3.5 bg-slate-50 border-t border-[#DEE8F7] shrink-0 rounded-b-[24px]">
            <button
              type="button"
              id="cancel-review-btn"
              class="px-4 py-2 rounded-xl text-xs font-bold text-[#475569] hover:bg-slate-200/60 transition-colors cursor-pointer"
              data-i18n="reviews.modalCancelBtn"
            >
              {{ __('reviews.modalCancelBtn') }}
            </button>
            <button
              type="button"
              id="submit-review-btn"
              class="px-6 py-2.5 rounded-xl text-xs font-bold bg-[#012383] text-white hover:bg-[#0B2A8F] transition-all shadow-xs cursor-pointer"
              data-i18n="reviews.modalSubmitBtn"
            >
              {{ __('reviews.modalSubmitBtn') }}
            </button>
          </div>

        </div>
      </div>

      <!-- Fullscreen High-Resolution Review Image Lightbox Modal -->
      <div
        id="review-image-lightbox"
        class="fixed inset-0 z-[9999] bg-black/85 backdrop-blur-md flex items-center justify-center p-3 sm:p-6 opacity-0 pointer-events-none transition-opacity duration-300"
        onclick="if(event.target === this) closeReviewImageLightbox()"
      >
        <div class="relative max-w-4xl max-h-[90vh] flex flex-col items-center">
          <button
            type="button"
            id="close-review-image-btn"
            onclick="closeReviewImageLightbox()"
            class="absolute -top-12 end-0 size-9 rounded-full bg-white/15 hover:bg-white/30 text-white flex items-center justify-center text-lg font-bold transition-all cursor-pointer shadow-lg"
            aria-label="إغلاق"
          >
            ✕
          </button>

          <div class="overflow-hidden rounded-2xl border border-white/20 shadow-2xl bg-black/40 flex items-center justify-center">
            <img
              id="review-lightbox-img"
              src=""
              alt="صورة التقييم مكبرة"
              class="max-w-full max-h-[80vh] w-auto h-auto object-contain rounded-2xl shadow-2xl"
            />
          </div>

          <div class="mt-3 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-sm text-white text-xs font-semibold flex items-center gap-2">
            <span>📷</span>
            <span id="review-lightbox-caption">{{ __('صورة مرفقة مع التقييم') }}</span>
          </div>
        </div>
      </div>

      <!-- ============================================== -->
    </x-slot:modals>

    <x-slot:scripts>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        // ----------------------------------------------------
        // 1. FILTERING & SORTING LOGIC
        // ----------------------------------------------------
        const filterPills = document.querySelectorAll('.filter-pill');
        const sortSelect = document.getElementById('sort-select');
        const grid = document.getElementById('reviews-grid');
        const spotlightCard = document.getElementById('spotlight-card');
        const emptyState = document.getElementById('reviews-empty');

        let activeFilter = 'all';

        function applyFilter() {
          const cards = document.querySelectorAll('.review-card');
          let visibleCount = 0;

          cards.forEach(card => {
            const cat = card.getAttribute('data-category');
            const rating = card.getAttribute('data-rating');
            const cert = card.getAttribute('data-cert');
            const hasImg = card.getAttribute('data-has-image');

            let matches = false;
            if (activeFilter === 'all') {
              matches = true;
            } else if (activeFilter === 'images') {
              matches = (hasImg === 'true');
            } else if (activeFilter === '5star') {
              matches = (rating === '5');
            } else if (activeFilter === 'cert') {
              matches = (cert === 'true');
            } else {
              matches = (cat === activeFilter);
            }

            if (matches) {
              card.style.display = 'flex';
              visibleCount++;
            } else {
              card.style.display = 'none';
            }
          });

          // Spotlight featured card filtering
          if (spotlightCard) {
            const spotCat = spotlightCard.getAttribute('data-category') || 'speech';
            const spotRating = spotlightCard.getAttribute('data-rating') || '5';
            const spotCert = spotlightCard.getAttribute('data-cert') || 'true';

            let showSpotlight = false;
            if (activeFilter === 'all' || activeFilter === 'images') {
              showSpotlight = true;
            } else if (activeFilter === '5star') {
              showSpotlight = (spotRating === '5');
            } else if (activeFilter === 'cert') {
              showSpotlight = (spotCert === 'true');
            } else {
              showSpotlight = (spotCat === activeFilter);
            }

            spotlightCard.style.display = showSpotlight ? 'flex' : 'none';
          }

          // Empty state visibility
          if (emptyState) {
            if (visibleCount === 0) {
              emptyState.classList.remove('hidden');
              emptyState.classList.add('flex');
            } else {
              emptyState.classList.add('hidden');
              emptyState.classList.remove('flex');
            }
          }
        }

        function sortCards(criteria) {
          if (!grid) return;
          const cardsArray = Array.from(grid.querySelectorAll('.review-card'));

          cardsArray.sort((a, b) => {
            if (criteria === 'highest') {
              const rA = parseInt(a.getAttribute('data-rating') || '0', 10);
              const rB = parseInt(b.getAttribute('data-rating') || '0', 10);
              if (rB !== rA) return rB - rA;
              return (parseInt(a.getAttribute('data-order') || '0', 10)) - (parseInt(b.getAttribute('data-order') || '0', 10));
            } else if (criteria === 'helpful') {
              const hA = parseInt(a.getAttribute('data-helpful') || '0', 10);
              const hB = parseInt(b.getAttribute('data-helpful') || '0', 10);
              if (hB !== hA) return hB - hA;
              return (parseInt(a.getAttribute('data-order') || '0', 10)) - (parseInt(b.getAttribute('data-order') || '0', 10));
            } else {
              // 'recent'
              const oA = parseInt(a.getAttribute('data-order') || '0', 10);
              const oB = parseInt(b.getAttribute('data-order') || '0', 10);
              return oA - oB;
            }
          });

          cardsArray.forEach(card => {
            if (emptyState) {
              grid.insertBefore(card, emptyState);
            } else {
              grid.appendChild(card);
            }
          });
        }

        filterPills.forEach(pill => {
          pill.addEventListener('click', () => {
            filterPills.forEach(p => {
              p.classList.remove('active', 'bg-[#012383]', 'text-white', 'hover:bg-[#0B2A8F]');
              p.classList.add('bg-[#EAF1FB]', 'text-[#0B2A8F]', 'hover:bg-[#DEE8F7]');
            });
            pill.classList.add('active', 'bg-[#012383]', 'text-white', 'hover:bg-[#0B2A8F]');
            pill.classList.remove('bg-[#EAF1FB]', 'text-[#0B2A8F]', 'hover:bg-[#DEE8F7]');

            activeFilter = pill.getAttribute('data-filter') || 'all';
            applyFilter();
          });
        });

        sortSelect?.addEventListener('change', () => {
          sortCards(sortSelect.value);
        });

        // ----------------------------------------------------
        // 2. HELPFUL VOTE COUNTERS
        // ----------------------------------------------------
        function attachHelpfulHandlers() {
          const helpfulBtns = document.querySelectorAll('.helpful-btn');
          helpfulBtns.forEach(btn => {
            if (btn.dataset.initialized) return;
            btn.dataset.initialized = 'true';
            let clicked = false;
            btn.addEventListener('click', (e) => {
              e.preventDefault();
              if (!clicked) {
                const countEl = btn.querySelector('.helpful-count');
                const card = btn.closest('.review-card');
                let count = parseInt(countEl?.textContent || '0', 10);
                count += 1;
                if (countEl) countEl.textContent = count;
                if (card) card.setAttribute('data-helpful', count);
                btn.classList.add('text-[#0B2A8F]', 'font-bold');
                clicked = true;
              }
            });
          });
        }
        attachHelpfulHandlers();

        // ----------------------------------------------------
        // 3. REVIEW MODAL & SUBMISSION
        // ----------------------------------------------------
        const modal = document.getElementById('review-modal');
        const openBtn = document.getElementById('open-review-modal-btn');
        const closeBtn = document.getElementById('close-review-modal-btn');
        const cancelBtn = document.getElementById('cancel-review-btn');
        const submitBtn = document.getElementById('submit-review-btn');
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        // File dropzone click & preview
        const uploadBox = document.getElementById('modal-upload-box');
        const fileInput = document.getElementById('modal-file-input');
        const uploadPlaceholder = document.getElementById('modal-upload-placeholder');
        const previewWrap = document.getElementById('modal-image-preview-wrap');
        const previewImg = document.getElementById('modal-image-preview');
        const imageName = document.getElementById('modal-image-name');
        const removeImageBtn = document.getElementById('remove-modal-image-btn');

        uploadBox?.addEventListener('click', (e) => {
          if (e.target.closest('#remove-modal-image-btn')) return;
          fileInput?.click();
        });

        fileInput?.addEventListener('change', () => {
          if (fileInput.files && fileInput.files[0]) {
            const file = fileInput.files[0];
            if (file.type.startsWith('image/')) {
              const reader = new FileReader();
              reader.onload = (e) => {
                if (previewImg) previewImg.src = e.target.result;
                if (imageName) imageName.textContent = file.name;
                uploadPlaceholder?.classList.add('hidden');
                previewWrap?.classList.remove('hidden');
                uploadBox?.classList.add('border-[#0B2A8F]', 'bg-blue-50/40');
              };
              reader.readAsDataURL(file);
            } else {
              if (imageName) imageName.textContent = file.name;
              uploadPlaceholder?.classList.add('hidden');
              previewWrap?.classList.remove('hidden');
            }
          }
        });

        removeImageBtn?.addEventListener('click', (e) => {
          e.stopPropagation();
          if (fileInput) fileInput.value = '';
          if (previewImg) previewImg.src = '';
          previewWrap?.classList.add('hidden');
          uploadPlaceholder?.classList.remove('hidden');
          uploadBox?.classList.remove('border-[#0B2A8F]', 'bg-blue-50/40');
        });

        const resetModalForm = () => {
          const titleInput = document.getElementById('modal-review-title');
          const textInput = document.getElementById('modal-review-text');
          if (titleInput) titleInput.value = '';
          if (textInput) textInput.value = '';
          if (fileInput) fileInput.value = '';
          if (previewImg) previewImg.src = '';
          previewWrap?.classList.add('hidden');
          uploadPlaceholder?.classList.remove('hidden');
          uploadBox?.classList.remove('border-[#0B2A8F]', 'bg-blue-50/40');
        };

        const showModal = () => {
          modal?.classList.remove('hidden');
          modal?.classList.add('flex');
        };

        const hideModal = () => {
          modal?.classList.add('hidden');
          modal?.classList.remove('flex');
          resetModalForm();
        };

        openBtn?.addEventListener('click', showModal);
        closeBtn?.addEventListener('click', hideModal);
        cancelBtn?.addEventListener('click', hideModal);

        modal?.addEventListener('click', (e) => {
          if (e.target === modal) hideModal();
        });

        document.addEventListener('keydown', (e) => {
          if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            hideModal();
          }
        });

        // Star picker in modal
        const starItems = document.querySelectorAll('.star-item');
        const ratingVal = document.getElementById('modal-rating-value');
        starItems.forEach(star => {
          star.addEventListener('click', () => {
            const val = parseInt(star.getAttribute('data-val'), 10);
            if (ratingVal) ratingVal.value = val;
            starItems.forEach(s => {
              const sVal = parseInt(s.getAttribute('data-val'), 10);
              if (sVal <= val) {
                s.classList.add('text-amber-400');
                s.classList.remove('text-slate-300');
              } else {
                s.classList.remove('text-amber-400');
                s.classList.add('text-slate-300');
              }
            });
          });
        });

        // Submit review
        submitBtn?.addEventListener('click', () => {
          const titleInput = document.getElementById('modal-review-title');
          const textInput = document.getElementById('modal-review-text');
          const courseSelect = document.getElementById('modal-course-select');
          const rating = ratingVal ? parseInt(ratingVal.value, 10) : 5;

          const title = titleInput?.value.trim() || '';
          const text = textInput?.value.trim() || '';

          if (!title || !text) {
            alert(document.documentElement.lang === 'en' ? 'Please fill in the review title and details.' : 'يرجى كتابة عنوان التقييم والتفاصيل.');
            return;
          }

          const courseMap = {
            'lang-dev': { cat: 'speech', name: 'استراتيجيات تنمية اللغة' },
            'articulation': { cat: 'articulation', name: 'تطبيقات مخارج الحروف' },
            'basics': { cat: 'speech', name: 'أساسيات التخاطب' },
            'stuttering': { cat: 'stuttering', name: 'إدارة التأتأة والطلاقة' }
          };
          const selectedCourse = courseMap[courseSelect?.value] || { cat: 'speech', name: 'دورة تدريبية' };
          const starString = '★'.repeat(rating) + '☆'.repeat(5 - rating);

          const uploadedImgSrc = (previewImg && previewImg.src && !previewWrap.classList.contains('hidden'))
            ? previewImg.src
            : null;

          const newCard = document.createElement('article');
          newCard.className = 'review-card bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col justify-between hover:shadow-card hover:-translate-y-1 transition-all';
          newCard.setAttribute('data-category', selectedCourse.cat);
          newCard.setAttribute('data-rating', rating);
          newCard.setAttribute('data-cert', 'true');
          newCard.setAttribute('data-has-image', uploadedImgSrc ? 'true' : 'false');
          newCard.setAttribute('data-helpful', '0');
          newCard.setAttribute('data-order', '0');

          newCard.innerHTML = `
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F]">
                    أ
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-[#012383]">${document.documentElement.lang === 'en' ? 'Verified Student' : 'طالب موثق'}</h4>
                    <span class="text-[11px] text-[#475569]">${document.documentElement.lang === 'en' ? 'Just now' : 'الآن'}</span>
                  </div>
                </div>
                <div class="flex text-[#F59E0B] text-xs">${starString}</div>
              </div>

              <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2 py-0.5 rounded bg-[#EEF7F3] text-emerald-700 text-[10px] font-bold">طالب موثق</span>
                <span class="px-2 py-0.5 rounded bg-[#EAF1FB] text-[#0B2A8F] text-[10px] font-bold">${selectedCourse.name}</span>
                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold">شهادة معتمدة</span>
              </div>

              <h5 class="text-xs font-bold text-[#012383] mt-1">«${title}»</h5>
              <p class="text-xs text-[#475569] font-medium leading-relaxed">${text}</p>

              ${uploadedImgSrc ? `
              <div class="mt-1 pt-1">
                <div
                  class="group/attach relative rounded-xl overflow-hidden border border-[#DEE8F7] bg-slate-50 cursor-pointer hover:border-[#0B2A8F] hover:shadow-xs transition-all"
                  onclick="openReviewImageLightbox('${uploadedImgSrc}')"
                  title="${document.documentElement.lang === 'en' ? 'Click to zoom' : 'انقر لتكبير الصورة'}"
                >
                  <img
                    src="${uploadedImgSrc}"
                    alt="صورة تقييم موثق"
                    class="w-full h-36 sm:h-40 object-cover object-top group-hover/attach:scale-105 transition-transform duration-300"
                  />
                  <div class="absolute inset-0 bg-black/25 opacity-0 group-hover/attach:opacity-100 transition-opacity flex items-center justify-center gap-1.5 text-white text-xs font-bold pointer-events-none">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                    </svg>
                    <span>${document.documentElement.lang === 'en' ? 'Zoom Image' : 'تكبير الصورة'}</span>
                  </div>
                  <span class="absolute bottom-2 start-2 bg-black/65 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 pointer-events-none">
                    <span>📷</span>
                    <span>${document.documentElement.lang === 'en' ? 'Attached Image' : 'صورة مرفقة'}</span>
                  </span>
                </div>
              </div>` : ''}
            </div>

            <div class="mt-4 pt-3 border-t border-[#DEE8F7] flex items-center justify-between text-xs">
              <span class="text-[11px] text-emerald-600 font-bold">طالب معتمد</span>
              <button type="button" class="helpful-btn flex items-center gap-1.5 text-[#475569] hover:text-[#0B2A8F] font-semibold cursor-pointer">
                <span>👍</span>
                <span class="helpful-count">0</span>
              </button>
            </div>
          `;

          if (grid) {
            grid.insertBefore(newCard, grid.firstChild);
          }
          attachHelpfulHandlers();

          hideModal();
          resetModalForm();

          applyFilter();

          if (toast && toastMessage) {
            const isEn = document.documentElement.lang === 'en';
            toastMessage.textContent = isEn ? 'Your review has been submitted successfully!' : 'تم نشر تقييمك بنجاح! شكراً لمشاركتك.';
            toast.classList.remove('-translate-y-20', 'translate-y-20', 'opacity-0', 'pointer-events-none');
            setTimeout(() => {
              toast.classList.add('-translate-y-20', 'opacity-0', 'pointer-events-none');
            }, 3500);
          }
        });

        // ----------------------------------------------------
        // 4. IMAGE LIGHTBOX HANDLER
        // ----------------------------------------------------
        window.openReviewImageLightbox = function (src) {
          const lightbox = document.getElementById('review-image-lightbox');
          const img = document.getElementById('review-lightbox-img');
          const caption = document.getElementById('review-lightbox-caption');
          if (!lightbox || !img) return;

          img.src = src;
          if (caption) {
            const isEn = document.documentElement.lang === 'en';
            caption.textContent = isEn ? 'Verified student testimonial (Anonymous)' : 'صورة رأي متدرب موثق (مجهول الهوية)';
          }
          lightbox.classList.remove('opacity-0', 'pointer-events-none');
          lightbox.classList.add('opacity-100', 'pointer-events-auto');
          document.body.classList.add('overflow-hidden');
        };

        window.closeReviewImageLightbox = function () {
          const lightbox = document.getElementById('review-image-lightbox');
          const img = document.getElementById('review-lightbox-img');
          if (!lightbox) return;
          lightbox.classList.add('opacity-0', 'pointer-events-none');
          lightbox.classList.remove('opacity-100', 'pointer-events-auto');
          document.body.classList.remove('overflow-hidden');
          if (img) img.src = '';
        };

        document.getElementById('close-review-image-btn')?.addEventListener('click', window.closeReviewImageLightbox);
        document.getElementById('review-image-lightbox')?.addEventListener('click', (e) => {
          if (e.target.id === 'review-image-lightbox') {
            window.closeReviewImageLightbox();
          }
        });
        document.addEventListener('keydown', (e) => {
          if (e.key === 'Escape') window.closeReviewImageLightbox();
        });
      });
    </script>
    </x-slot:scripts>
</x-layouts::marketing>
