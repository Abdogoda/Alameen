<x-layouts::app :title="__('الإعلانات والشروحات التعليمية')">

    <div
        class="flex flex-col gap-6 w-full text-start"
        x-data="{
            videoModalOpen: false,
            currentVideo: null,
            openVideo(item) {
                this.currentVideo = item;
                this.videoModalOpen = true;
            },
            closeVideo() {
                this.videoModalOpen = false;
                this.currentVideo = null;
            }
        }"
    >
        <!-- ============================================== -->
        <!-- 1. BREADCRUMBS & PAGE HEADER                   -->
        <!-- ============================================== -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div class="flex flex-col gap-1 items-start text-start">
                <nav class="flex items-center gap-2 text-xs font-semibold text-[#0B2A8F] dark:text-blue-400" aria-label="Breadcrumb">
                    @if(app(\App\Services\Cms\SettingsService::class)->isPageEnabled('student_dashboard'))
                        <a href="{{ route('dashboard') }}" class="hover:underline flex items-center gap-1" wire:navigate>
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>{{ __('لوحة الطالب') }}</span>
                        </a>
                        <span class="text-slate-300 dark:text-slate-600">/</span>
                    @endif
                    <span class="text-slate-500 dark:text-slate-400">{{ __('الإعلانات والشروحات') }}</span>
                </nav>

                <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-[#012383] dark:text-white leading-tight">
                    {{ __('الإعلانات والشروحات التعليمية') }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-3xl">
                    {{ __('مكتبتك الشاملة لمتابعة كافة الإعلانات الإدارية، التوجيهات الأكاديمية، والشروحات التوضيحية المصورة المصممة لمساعدتك.') }}
                </p>
            </div>

            <!-- Total count badge -->
            <div class="flex items-center gap-2 shrink-0">
                <span class="px-3.5 py-2 rounded-xl text-xs font-bold bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-300 border border-[#DEE8F7] dark:border-slate-700 flex items-center gap-2">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                    <span>{{ __('إجمالي المواد المتاحة:') }} {{ $announcements->total() }}</span>
                </span>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- 2. FILTER & SEARCH BAR                         -->
        <!-- ============================================== -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
            {{-- Filter Pills --}}
            <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                <a
                    href="{{ route('announcements.index', array_filter(['q' => $searchQuery])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ empty($currentType) ? 'bg-[#012383] text-white shadow-xs' : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}"
                >
                    {{ __('الكل') }}
                </a>
                <a
                    href="{{ route('announcements.index', array_filter(['type' => 'video', 'q' => $searchQuery])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ $currentType === 'video' ? 'bg-[#012383] text-white shadow-xs' : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}"
                >
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <span>{{ __('فيديوهات وشروحات') }}</span>
                </a>
                <a
                    href="{{ route('announcements.index', array_filter(['type' => 'text', 'q' => $searchQuery])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ $currentType === 'text' ? 'bg-[#012383] text-white shadow-xs' : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}"
                >
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('إعلانات نصية') }}</span>
                </a>
            </div>

            {{-- Search Form --}}
            <form method="GET" action="{{ route('announcements.index') }}" class="w-full md:w-80 relative">
                @if(!empty($currentType))
                    <input type="hidden" name="type" value="{{ $currentType }}">
                @endif
                <input
                    type="text"
                    name="q"
                    value="{{ $searchQuery }}"
                    placeholder="{{ __('ابحث في الشروحات والإعلانات...') }}"
                    class="w-full h-10 ps-10 pe-4 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#012383]"
                />
                <button type="submit" class="absolute start-3 top-2.5 text-slate-400 hover:text-[#012383]" aria-label="{{ __('بحث') }}">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>
        </div>

        <!-- ============================================== -->
        <!-- 3. ANNOUNCEMENTS & VIDEOS GRID                 -->
        <!-- ============================================== -->
        @if($announcements->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($announcements as $item)
                    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                        
                        {{-- Card Media or Top Header --}}
                        <div class="flex flex-col">
                            @if($item->has_video || $item->thumbnail_url)
                                <div class="relative w-full aspect-video bg-slate-900 overflow-hidden shrink-0">
                                    @if($item->thumbnail_url)
                                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="size-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                    @else
                                        <div class="size-full flex items-center justify-center bg-gradient-to-br from-[#012383] to-slate-900 text-white">
                                            <svg class="size-12 text-blue-300/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif

                                    @if($item->has_video)
                                        <button
                                            type="button"
                                            @click="openVideo({{ json_encode([
                                                'id' => $item->id,
                                                'title' => $item->title,
                                                'has_video' => $item->has_video,
                                                'video_type' => $item->video_type,
                                                'embed_url' => $item->video_embed_url,
                                                'video_url' => $item->video_url,
                                                'mux_playback_id' => $item->video_mux_playback_id,
                                                'summary' => $item->summary,
                                                'body' => $item->body,
                                                'action_url' => $item->action_url,
                                                'action_label' => $item->action_label,
                                            ]) }})"
                                            class="absolute inset-0 flex items-center justify-center bg-black/35 hover:bg-black/55 transition-colors cursor-pointer group-hover:scale-105"
                                            aria-label="{{ __('تشغيل الفيديو') }}"
                                        >
                                            <div class="size-12 rounded-full bg-white/95 text-[#012383] flex items-center justify-center shadow-xl transform transition-transform group-hover:scale-110">
                                                <svg class="size-6 translate-x-0.5 rtl:-translate-x-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </button>
                                    @endif

                                    {{-- Badges on top of thumbnail --}}
                                    <div class="absolute top-3 inset-x-3 flex items-center justify-between pointer-events-none">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-900/80 text-white backdrop-blur-xs flex items-center gap-1">
                                            @if($item->has_video)
                                                <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                                <span>{{ __('فيديو تعليمي') }}</span>
                                            @else
                                                <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                                <span>{{ __('إعلان') }}</span>
                                            @endif
                                        </span>

                                        @if($item->display_mode === 'force')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500 text-white shadow-xs">
                                                {{ __('هام ومؤكد') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="p-5 pb-0 flex items-center justify-between">
                                    <div class="size-10 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-[#012383] dark:text-blue-400 flex items-center justify-center font-bold text-lg">
                                        📢
                                    </div>
                                    @if($item->display_mode === 'force')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500 text-white shadow-xs">
                                            {{ __('هام ومؤكد') }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            {{-- Card Body --}}
                            <div class="p-5 flex flex-col gap-2.5">
                                <a href="{{ route('announcements.show', $item) }}" class="group-hover:text-[#012383] dark:group-hover:text-blue-400 transition-colors">
                                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-base sm:text-lg text-slate-900 dark:text-white leading-snug line-clamp-2">
                                        {{ $item->title }}
                                    </h3>
                                </a>

                                @if($item->summary)
                                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 line-clamp-3 leading-relaxed">
                                        {{ $item->summary }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Card Footer --}}
                        <div class="p-5 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3 text-xs">
                            <span class="text-slate-400 dark:text-slate-500 flex items-center gap-1.5 text-[11px]">
                                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ $item->created_at->diffForHumans() }}</span>
                            </span>

                            <div class="flex items-center gap-2">
                                @if($item->action_url && $item->action_label)
                                    <a
                                        href="{{ $item->action_url }}"
                                        target="_blank"
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-400 hover:bg-[#012383] hover:text-white transition-colors"
                                    >
                                        {{ $item->action_label }}
                                    </a>
                                @endif

                                <a
                                    href="{{ route('announcements.show', $item) }}"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-[#012383] hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                                    title="{{ __('عرض التفاصيل') }}"
                                >
                                    <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $announcements->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-12 text-center flex flex-col items-center justify-center gap-4 max-w-xl mx-auto my-8">
                <div class="size-16 rounded-full bg-blue-50 dark:bg-blue-950/40 text-[#012383] dark:text-blue-400 flex items-center justify-center text-2xl font-bold">
                    📭
                </div>
                <div class="flex flex-col gap-1">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-slate-900 dark:text-white">
                        {{ __('لا توجد إعلانات أو شروحات حالياً') }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        @if($searchQuery)
                            {{ __('لم يتم العثور على أية نتائج مطابقة لبحثك. يرجى تجربة كلمات بحث أخرى.') }}
                        @else
                            {{ __('سيتم نشر الفيديوهات والشروحات التعليمية والتحديثات الهامة هنا فور إتاحتها.') }}
                        @endif
                    </p>
                </div>
                @if($searchQuery || $currentType)
                    <a href="{{ route('announcements.index') }}" class="mt-2 px-5 py-2.5 rounded-xl bg-[#012383] text-white text-xs font-bold hover:bg-[#0B2A8F] transition-colors">
                        {{ __('إعادة ضبط البحث') }}
                    </a>
                @endif
            </div>
        @endif

        <!-- ============================================== -->
        <!-- 4. ALPINE VIDEO MODAL PLAYER                   -->
        <!-- ============================================== -->
        <div
            x-show="videoModalOpen"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.window.escape="closeVideo()"
        >
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-700/50 w-full max-w-3xl overflow-hidden flex flex-col"
                @click.away="closeVideo()"
            >
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between gap-4">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-base sm:text-lg text-slate-900 dark:text-white truncate" x-text="currentVideo?.title"></h3>
                    <button
                        type="button"
                        @click="closeVideo()"
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                        aria-label="{{ __('إغلاق') }}"
                    >
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Player Container --}}
                <div class="relative w-full aspect-video bg-black">
                    <template x-if="currentVideo && currentVideo.embed_url">
                        <iframe
                            :src="currentVideo.embed_url"
                            class="size-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                        ></iframe>
                    </template>
                    <template x-if="currentVideo && !currentVideo.embed_url && currentVideo.video_url">
                        <video
                            :src="currentVideo.video_url"
                            controls
                            autoplay
                            class="size-full"
                        ></video>
                    </template>
                </div>

                {{-- Body Details --}}
                <div class="p-6 flex flex-col gap-3">
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed" x-text="currentVideo?.summary"></p>
                    <div class="flex items-center justify-between gap-3 pt-2">
                        <template x-if="currentVideo?.action_url && currentVideo?.action_label">
                            <a
                                :href="currentVideo.action_url"
                                target="_blank"
                                class="px-5 py-2.5 rounded-xl bg-[#012383] text-white text-xs font-bold hover:bg-[#0B2A8F] transition-colors"
                                x-text="currentVideo.action_label"
                            ></a>
                        </template>
                        <button
                            type="button"
                            @click="closeVideo()"
                            class="ms-auto px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold hover:bg-slate-200 transition-colors"
                        >
                            {{ __('إغلاق') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts::app>
