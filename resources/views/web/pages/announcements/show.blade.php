<x-layouts::app :title="$announcement->title">

    <div class="flex flex-col gap-6 w-full text-start">
        <!-- ============================================== -->
        <!-- 1. BREADCRUMBS & TOP BAR                       -->
        <!-- ============================================== -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-[#0B2A8F] dark:text-blue-400" aria-label="Breadcrumb">
            @if(app(\App\Services\Cms\SettingsService::class)->isPageEnabled('student_dashboard'))
                <a href="{{ route('dashboard') }}" class="hover:underline flex items-center gap-1" wire:navigate>
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('لوحة الطالب') }}</span>
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
            @endif
            <a href="{{ route('announcements.index') }}" class="hover:underline" wire:navigate>
                {{ __('الإعلانات والشروحات') }}
            </a>
            <span class="text-slate-300 dark:text-slate-600">/</span>
            <span class="text-slate-500 dark:text-slate-400 truncate max-w-xs">{{ $announcement->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- ============================================== -->
            <!-- 2. MAIN CONTENT (VIDEO PLAYER + BODY)          -->
            <!-- ============================================== -->
            <div class="lg:col-span-2 flex flex-col gap-5">
                
                {{-- Video Player Container --}}
                @if($announcement->has_video)
                    <div class="bg-black rounded-3xl overflow-hidden shadow-lg border border-slate-800 aspect-video w-full relative">
                        @if($announcement->video_embed_url)
                            <iframe
                                src="{{ $announcement->video_embed_url }}"
                                class="size-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        @elseif($announcement->video_url)
                            <video
                                src="{{ $announcement->video_url }}"
                                controls
                                class="size-full"
                                poster="{{ $announcement->thumbnail_url }}"
                            ></video>
                        @endif
                    </div>
                @elseif($announcement->thumbnail_url)
                    <div class="rounded-3xl overflow-hidden shadow-xs border border-slate-200 dark:border-slate-800 aspect-video w-full">
                        <img src="{{ $announcement->thumbnail_url }}" alt="{{ $announcement->title }}" class="size-full object-cover" />
                    </div>
                @endif

                {{-- Article / Content Card --}}
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col gap-5">
                    
                    {{-- Badges & Date --}}
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-300">
                                @if($announcement->has_video)
                                    🎥 {{ __('فيديو تعليمي وشرح') }}
                                @else
                                    📢 {{ __('إعلان وتوجيه') }}
                                @endif
                            </span>

                            @if($announcement->display_mode === 'force')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                    ⚠️ {{ __('هام ومؤكد') }}
                                </span>
                            @endif
                        </div>

                        <span class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ $announcement->created_at->translatedFormat('d F Y') }}</span>
                        </span>
                    </div>

                    {{-- Title --}}
                    <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl lg:text-3xl text-slate-900 dark:text-white leading-tight">
                        {{ $announcement->title }}
                    </h1>

                    {{-- Summary --}}
                    @if($announcement->summary)
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 text-xs sm:text-sm text-slate-700 dark:text-slate-300 font-semibold leading-relaxed">
                            {{ $announcement->summary }}
                        </div>
                    @endif

                    {{-- Body Content --}}
                    @if($announcement->body)
                        <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line text-sm">
                            {{ $announcement->body }}
                        </div>
                    @endif

                    {{-- Action Button --}}
                    @if($announcement->action_url && $announcement->action_label)
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-3">
                            <a
                                href="{{ $announcement->action_url }}"
                                target="_blank"
                                class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2"
                            >
                                <span>{{ $announcement->action_label }}</span>
                                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    @endif

                </div>

            </div>

            <!-- ============================================== -->
            <!-- 3. SIDEBAR (OTHER RECENT ANNOUNCEMENTS)        -->
            <!-- ============================================== -->
            <div class="flex flex-col gap-5">
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col gap-4">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-base text-[#012383] dark:text-white flex items-center gap-2">
                        <span>📚</span>
                        <span>{{ __('شروحات وإعلانات أخرى') }}</span>
                    </h3>

                    @if($recentAnnouncements->isNotEmpty())
                        <div class="flex flex-col gap-3">
                            @foreach($recentAnnouncements as $recent)
                                <a
                                    href="{{ route('announcements.show', $recent) }}"
                                    class="p-3 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-[#012383]/40 dark:hover:border-blue-500/40 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-all flex gap-3 group"
                                >
                                    @if($recent->thumbnail_url)
                                        <div class="size-14 rounded-xl overflow-hidden bg-slate-900 shrink-0">
                                            <img src="{{ $recent->thumbnail_url }}" alt="{{ $recent->title }}" class="size-full object-cover group-hover:scale-105 transition-transform" />
                                        </div>
                                    @else
                                        <div class="size-14 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0 text-lg">
                                            @if($recent->has_video) 🎥 @else 📢 @endif
                                        </div>
                                    @endif

                                    <div class="flex flex-col justify-center min-w-0">
                                        <h4 class="font-bold text-xs text-slate-800 dark:text-slate-200 line-clamp-2 group-hover:text-[#012383] dark:group-hover:text-blue-400 transition-colors">
                                            {{ $recent->title }}
                                        </h4>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">
                                            {{ $recent->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400 text-center py-4">
                            {{ __('لا توجد إعلانات أخرى حالياً.') }}
                        </p>
                    @endif

                    <a
                        href="{{ route('announcements.index') }}"
                        class="mt-2 text-center text-xs font-bold text-[#012383] dark:text-blue-400 hover:underline py-2"
                        wire:navigate
                    >
                        {{ __('عرض جميع الإعلانات والشروحات ←') }}
                    </a>
                </div>
            </div>

        </div>
    </div>

</x-layouts::app>
