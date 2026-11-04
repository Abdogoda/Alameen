<x-layouts::marketing :title="__('دليل البرامج والدورات')" active="courses">
    <div class="w-full max-w-[1536px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-24 2xl:px-32 py-8 lg:py-12 flex flex-col gap-8">
        
        <!-- 1. HERO SEARCH SECTION (WorkDo Marketplace Hero) -->
        <section class="flex flex-col items-center text-center gap-4 max-w-4xl mx-auto w-full">
          
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EAF1FB] border border-[#0B2A8F]/20 text-[#0B2A8F] text-xs font-bold">
            <span data-i18n="catalog.eyebrow">{{ __('catalog.eyebrow') }}</span>
          </div>

          <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl lg:text-5xl text-[#012383] tracking-tight leading-tight" data-i18n="catalog.heroTitle">
            {{ __('catalog.heroTitle') }}
          </h1>

          <p class="text-base sm:text-lg text-[#475569] max-w-2xl font-medium leading-relaxed" data-i18n="catalog.heroDesc">
            {{ __('catalog.heroDesc') }}
          </p>

          <!-- Search Input Bar -->
          <div class="w-full max-w-2xl mt-2 relative">
            <input
              type="text"
              id="course-search-input"
              placeholder="{{ __('catalog.searchPlaceholder') }}"
              data-i18n-placeholder="catalog.searchPlaceholder"
              class="w-full h-13 bg-white border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/20 rounded-2xl px-12 text-sm text-[#012383] placeholder-[#475569] outline-none shadow-sm transition-all"
            />
            <svg class="size-5 text-[#0B2A8F] absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>

        </section>

        <!-- 2. COURSE GRID -->
        <section class="flex flex-col gap-6">

          @php
            $courses = \App\Models\Course::query()
                ->where('is_published', true)
                ->withCount(['videos' => fn ($q) => $q->published()])
                ->orderBy('sort_order')
                ->get();
          @endphp

          <!-- Courses Grid -->
          <div class="grid gap-6 w-full {{ $courses->count() === 1 ? 'grid-cols-1 max-w-md mx-auto' : ($courses->count() === 2 ? 'grid-cols-1 md:grid-cols-2 max-w-3xl mx-auto' : 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3') }}" id="catalog-courses-grid">
            @forelse($courses as $course)
              <div class="course-card bg-white border border-[#DEE8F7] rounded-[18px] overflow-hidden shadow-xs hover:shadow-card hover:-translate-y-1 transition-all flex flex-col justify-between" data-category="speech" data-title="{{ $course->title_ar }}">
                <div>
                  <!-- Thumbnail -->
                  <div class="h-48 w-full bg-[#012383] relative overflow-hidden">
                    <img src="{{ $course->thumbnail ?: '/assets/images/course-topic-basics.png' }}" alt="{{ app()->getLocale() === 'en' && $course->title_en ? $course->title_en : $course->title_ar }}" class="size-full object-cover" />
                    <div class="absolute top-3 end-3 flex gap-2">
                      <span class="px-2.5 py-1 rounded-full bg-[#10B77F] text-white text-[11px] font-bold shadow-xs">{{ __('landing.mostPopular') }}</span>
                      @if($course->videos_count)
                        <span class="px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">{{ $course->videos_count }} {{ __('catalog.lessonsCount') }}</span>
                      @endif
                    </div>
                  </div>

                  <!-- Body -->
                  <div class="p-5 flex flex-col gap-3">
                    <div class="flex items-center justify-between text-xs font-bold">
                      <span class="text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-md">{{ __('landing.speechCategory') }}</span>
                      <div class="flex items-center gap-1 text-[#F59E0B]">
                        <span>★ 4.9</span>
                        <span class="text-slate-400 font-normal">(480)</span>
                      </div>
                    </div>

                    <h3 class="font-['Cairo'] font-bold text-[18px] md:text-[19px] text-[#012383] leading-snug">
                      {{ app()->getLocale() === 'en' && $course->title_en ? $course->title_en : $course->title_ar }}
                    </h3>

                    <p class="text-xs text-[#475569] leading-relaxed line-clamp-2">
                      {{ app()->getLocale() === 'en' && $course->description_en ? $course->description_en : $course->description_ar }}
                    </p>

                    <div class="flex items-center gap-2 pt-2 border-t border-[#DEE8F7]/60 text-xs text-[#475569]">
                      <div class="size-6 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-[10px] text-[#0B2A8F]">
                        د
                      </div>
                      <span>{{ __('د. سامي المنصور • محاضر التخاطب الشامل') }}</span>
                    </div>
                  </div>
                </div>

                <!-- Footer CTA -->
                <div class="p-5 pt-0 flex items-center justify-between gap-3">
                  <span class="text-xs font-bold text-emerald-600 bg-[#EEF7F3] px-2.5 py-1 rounded-lg" data-i18n="catalog.includedInPlan">
                    {{ __('catalog.includedInPlan') }}
                  </span>
                  <a
                    href="{{ route('courses.show') }}"
                    class="px-4 py-2 bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
                    data-i18n="catalog.viewCourseDetails"
                  >
                    {{ __('catalog.viewCourseDetails') }}
                  </a>
                </div>
              </div>
            @empty
              <div class="col-span-full flex flex-col items-center justify-center py-12 text-center bg-white border border-[#DEE8F7] rounded-[24px]">
                <p class="text-sm font-bold text-[#012383]" data-i18n="catalog.noCoursesFound">{{ __('catalog.noCoursesFound') }}</p>
              </div>
            @endforelse
          </div>

          <!-- Empty State (Hidden by default) -->
          <div id="catalog-empty" class="hidden flex-col items-center justify-center py-12 text-center bg-white border border-[#DEE8F7] rounded-[24px]">
            <p class="text-sm font-bold text-[#012383]" data-i18n="catalog.noCoursesFound">{{ __('catalog.noCoursesFound') }}</p>
          </div>

        </section>

    </div>

    <x-slot:scripts>
        <script>
          // ==========================================
          // Course Search & Filtering Script
          // ==========================================
          document.addEventListener('DOMContentLoaded', () => {
            const courseCards = document.querySelectorAll('.course-card');
            const searchInput = document.getElementById('course-search-input');
            const emptyState = document.getElementById('catalog-empty');

            let searchQuery = '';

            function filterCatalog() {
              let visibleCount = 0;
              courseCards.forEach(card => {
                const title = card.getAttribute('data-title')?.toLowerCase() || '';
                const matchesSearch = !searchQuery || title.includes(searchQuery);

                if (matchesSearch) {
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

            searchInput?.addEventListener('input', (e) => {
              searchQuery = e.target.value.trim().toLowerCase();
              filterCatalog();
            });
          });
        </script>
    </x-slot:scripts>
</x-layouts::marketing>



