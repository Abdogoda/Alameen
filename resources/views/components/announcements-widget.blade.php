@props([
    'limit' => 3,
    'platform' => 'web',
])

@php
    $announcementService = app(\App\Services\Announcement\AnnouncementService::class);
    $currentUser = auth()->user();
    
    // Fetch active educational content / videos / inline announcements
    $items = $announcementService->getEligibleAnnouncements(
        platform: $platform,
        user: $currentUser,
        contentType: null
    )->take($limit);
@endphp

@if($items->isNotEmpty())
    <div
        x-data="{
            activeVideoModal: null,
            openVideo(item) {
                this.activeVideoModal = item;
            },
            closeVideo() {
                this.activeVideoModal = null;
            }
        }"
        class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col gap-5 w-full"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-xl bg-[#EAF1FB] dark:bg-blue-950/60 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-white leading-tight">
                        {{ __('الفيديوهات والإعلانات التعليمية المميزة') }}
                    </h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500">
                        {{ __('إرشادات، توجيهات أكاديمية، وتحديثات هامة مصممة لرحلتك التدريبية.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Grid of Educational Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($items as $item)
                <div class="bg-slate-50/70 dark:bg-slate-800/40 border border-[#DEE8F7]/70 dark:border-slate-800 rounded-2xl p-4 flex flex-col justify-between gap-3 group hover:border-[#012383]/40 dark:hover:border-blue-500/40 hover:shadow-md transition-all duration-200">
                    <div class="flex flex-col gap-2.5">
                        {{-- Media Thumbnail / Preview --}}
                        @if($item->has_video || $item->thumbnail_url)
                            <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-slate-900 border border-slate-200 dark:border-slate-700/60 shrink-0">
                                @if($item->thumbnail_url)
                                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="size-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                @else
                                    <div class="size-full flex items-center justify-center bg-gradient-to-br from-blue-900 to-indigo-950 text-white">
                                        <svg class="size-8 text-blue-300 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                @endif

                                @if($item->has_video)
                                    <button
                                        type="button"
                                        @click="openVideo({{ json_encode([
                                            'title' => $item->title,
                                            'has_video' => $item->has_video,
                                            'video_type' => $item->video_type,
                                            'embed_url' => $item->video_embed_url,
                                            'video_url' => $item->video_url,
                                            'mux_playback_id' => $item->video_mux_playback_id,
                                            'summary' => $item->summary,
                                            'body' => $item->body,
                                        ]) }})"
                                        class="absolute inset-0 flex items-center justify-center bg-black/30 hover:bg-black/50 transition-colors cursor-pointer group-hover:scale-105"
                                        aria-label="{{ __('تشغيل الفيديو') }}"
                                    >
                                        <div class="size-11 rounded-full bg-white/90 text-[#012383] flex items-center justify-center shadow-lg transform transition-transform group-hover:scale-110">
                                            <svg class="size-6 translate-x-0.5 rtl:-translate-x-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </button>
                                @endif

                                <span class="absolute top-2 start-2 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                    {{ in_array($item->content_type, ['video', 'mixed']) ? __('فيديو') : __('إعلان') }}
                                </span>
                            </div>
                        @endif

                        <h4 class="font-bold text-sm sm:text-base text-slate-900 dark:text-white line-clamp-2 leading-snug">
                            {{ $item->title }}
                        </h4>

                        @if($item->summary)
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $item->summary }}
                            </p>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="pt-2 border-t border-slate-200/60 dark:border-slate-800 flex items-center justify-between gap-2">
                        @if($item->has_video)
                            <button
                                type="button"
                                @click="openVideo({{ json_encode([
                                    'title' => $item->title,
                                    'has_video' => $item->has_video,
                                    'video_type' => $item->video_type,
                                    'embed_url' => $item->video_embed_url,
                                    'video_url' => $item->video_url,
                                    'mux_playback_id' => $item->video_mux_playback_id,
                                    'summary' => $item->summary,
                                    'body' => $item->body,
                                ]) }})"
                                class="text-xs font-bold text-[#012383] dark:text-blue-400 hover:underline flex items-center gap-1 cursor-pointer"
                            >
                                <span>{{ __('مشاهدة المحتوى') }}</span>
                                <span>▶</span>
                            </button>
                        @elseif($item->button_text && $item->button_url)
                            <a
                                href="{{ $item->button_url }}"
                                class="text-xs font-bold text-[#012383] dark:text-blue-400 hover:underline flex items-center gap-1"
                            >
                                <span>{{ $item->button_text }}</span>
                                <span>→</span>
                            </a>
                        @else
                            <span class="text-[11px] text-slate-400">{{ $item->created_at?->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Dedicated Viewer Modal --}}
        <template x-if="activeVideoModal">
            <div
                class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
                @keydown.escape.window="closeVideo()"
            >
                <div
                    @click.stop
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl max-w-2xl w-full p-6 flex flex-col gap-4 relative overflow-hidden my-auto"
                >
                    <button
                        type="button"
                        @click="closeVideo()"
                        class="absolute top-4 end-4 p-2 rounded-full text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors z-10 cursor-pointer"
                    >
                        ✕
                    </button>

                    <h3 class="text-lg font-bold text-slate-900 dark:text-white pr-8" x-text="activeVideoModal.title"></h3>

                    <template x-if="activeVideoModal.has_video">
                        <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-black shadow-inner">
                            <template x-if="activeVideoModal.video_type === 'youtube' || (activeVideoModal.embed_url && activeVideoModal.embed_url.includes('youtube'))">
                                <iframe :src="activeVideoModal.embed_url" class="w-full h-full border-0" allowfullscreen></iframe>
                            </template>
                            <template x-if="activeVideoModal.video_type !== 'youtube' && !activeVideoModal.embed_url?.includes('youtube') && activeVideoModal.video_url">
                                <video :src="activeVideoModal.video_url" controls class="w-full h-full object-contain"></video>
                            </template>
                        </div>
                    </template>

                    <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed max-h-48 overflow-y-auto" x-text="activeVideoModal.summary || activeVideoModal.body"></div>

                    <div class="flex justify-end pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            @click="closeVideo()"
                            class="px-5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs"
                        >
                            {{ __('إغلاق النافذة') }}
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endif
