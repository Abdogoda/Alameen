<x-admin-layout::admin
    :title="__('إحصائيات ومعاينة الإعلان: :title', ['title' => $announcement->title_ar])"
    :description="__('استعراض نسب المشاهدة والتفاعل، منصات العرض، والمعاينة الحية لكيفية ظهور المحتوى')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-6 w-full min-w-0"
>
    <x-slot:actions>
        <a
            href="{{ route('admin.announcements.edit', $announcement) }}"
            class="h-10 px-4 rounded-xl bg-[#012383] text-white font-bold text-xs hover:bg-[#0B2A8F] flex items-center gap-1.5 transition-colors"
        >
            <span>✏️</span>
            <span>{{ __('تعديل الإعلان') }}</span>
        </a>
        <a
            href="{{ route('admin.announcements.index') }}"
            class="h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-50 flex items-center gap-1.5 transition-colors"
        >
            <span>←</span>
            <span>{{ __('العودة للقائمة') }}</span>
        </a>
    </x-slot:actions>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-blue-50 dark:bg-blue-950 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('إجمالي مرات الظهور') }}</span>
                <span class="text-2xl font-black text-[#012383] dark:text-white">{{ $stats['views'] }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('تأكيدات القراءة (Acknowledged)') }}</span>
                <span class="text-2xl font-black text-emerald-700 dark:text-emerald-400">{{ $stats['acknowledgments'] }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-amber-50 dark:bg-amber-950 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('مرات الإغلاق (Dismissed)') }}</span>
                <span class="text-2xl font-black text-amber-700 dark:text-amber-400">{{ $stats['dismissals'] }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('توزيع المنصات') }}</span>
                <div class="flex items-center gap-2 mt-0.5 text-xs font-bold">
                    <span class="text-blue-600">Web: {{ $stats['by_platform']['web'] }}</span>
                    <span class="text-emerald-600">App: {{ $stats['by_platform']['mobile'] }}</span>
                    <span class="text-purple-600">Adm: {{ $stats['by_platform']['admin'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Preview Box --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 shadow-xs flex flex-col gap-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                    <span>🗔</span>
                    <span>{{ __('المعاينة الحية للمحتوى') }}</span>
                </h3>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $announcement->display_mode === 'force' ? 'bg-red-100 text-red-700' : 'bg-purple-100 text-purple-800' }}">
                    {{ match($announcement->display_mode) { 'force' => '🔒 إجباري مانع', 'popup' => '🗔 نافذة منبثقة', 'banner' => '📢 شريط علوي', default => '🪟 بطاقة مدمجة' } }}
                </span>
            </div>

            {{-- Mock Screen Container --}}
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5 bg-slate-50 dark:bg-slate-800/60 flex flex-col gap-4">
                <div class="flex items-start gap-3">
                    <div class="size-10 rounded-xl bg-blue-100 dark:bg-blue-950 text-[#012383] flex items-center justify-center font-bold shrink-0">
                        @if($announcement->has_video) 🎥 @else 📝 @endif
                    </div>
                    <div>
                        <h4 class="font-black text-lg text-slate-900 dark:text-white leading-tight">
                            {{ $announcement->title_ar }}
                        </h4>
                        @if($announcement->title_en)
                            <span class="text-xs text-slate-400 block">{{ $announcement->title_en }}</span>
                        @endif
                    </div>
                </div>

                {{-- Video Mock --}}
                @if($announcement->has_video)
                    <div class="w-full aspect-video rounded-xl overflow-hidden bg-black flex items-center justify-center">
                        @if($announcement->video_embed_url && str_contains($announcement->video_embed_url, 'youtube'))
                            <iframe src="{{ $announcement->video_embed_url }}" class="w-full h-full border-0" allowfullscreen></iframe>
                        @elseif($announcement->video_url)
                            <video src="{{ $announcement->video_url }}" controls class="w-full h-full object-contain"></video>
                        @else
                            <span class="text-white text-xs">Mux Playback ID: {{ $announcement->video_mux_playback_id }}</span>
                        @endif
                    </div>
                @endif

                @if($announcement->summary_ar)
                    <p class="font-bold text-sm text-slate-800 dark:text-slate-200">
                        {{ $announcement->summary_ar }}
                    </p>
                @endif

                @if($announcement->body_ar)
                    <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed max-h-40 overflow-y-auto">
                        {!! nl2br(e($announcement->body_ar)) !!}
                    </div>
                @endif

                @if($announcement->button_text_ar && $announcement->button_url)
                    <div class="pt-2">
                        <a href="{{ $announcement->button_url }}" target="_blank" class="h-10 px-5 rounded-xl bg-[#012383] text-white font-bold text-xs inline-flex items-center gap-1.5">
                            <span>{{ $announcement->button_text_ar }}</span>
                            <span>→</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Configuration Summary & Recent Interactions --}}
        <div class="flex flex-col gap-6">

            {{-- Config Card --}}
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 shadow-xs flex flex-col gap-4">
                <h3 class="font-bold text-base text-slate-900 dark:text-white pb-2 border-b border-slate-100 dark:border-slate-800">
                    {{ __('تفاصيل الإعدادات والتحكم') }}
                </h3>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                        <span class="text-slate-400 block mb-0.5">{{ __('المنصات') }}</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ implode(', ', $announcement->target_platforms ?? ['web', 'mobile', 'admin']) }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                        <span class="text-slate-400 block mb-0.5">{{ __('الجمهور') }}</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $announcement->target_audience }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                        <span class="text-slate-400 block mb-0.5">{{ __('التكرار') }}</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $announcement->frequency }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                        <span class="text-slate-400 block mb-0.5">{{ __('الأولوية') }}</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $announcement->priority }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                        <span class="text-slate-400 block mb-0.5">{{ __('تاريخ البدء') }}</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $announcement->start_at ? $announcement->start_at->format('Y-m-d H:i') : __('فوري') }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                        <span class="text-slate-400 block mb-0.5">{{ __('تاريخ الانتهاء') }}</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $announcement->end_at ? $announcement->end_at->format('Y-m-d H:i') : __('مفتوح') }}</span>
                    </div>
                </div>
            </div>

            {{-- Recent Activity / Interactions --}}
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 shadow-xs flex flex-col gap-4">
                <h3 class="font-bold text-base text-slate-900 dark:text-white pb-2 border-b border-slate-100 dark:border-slate-800">
                    {{ __('سجل تفاعلات المستخدمين الأحدث (آخر 20 تفاعل)') }}
                </h3>

                <div class="overflow-x-auto max-h-72 overflow-y-auto">
                    <table class="w-full text-xs text-start">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 dark:border-slate-800 pb-2">
                                <th class="py-2 text-start">{{ __('المستخدم') }}</th>
                                <th class="py-2 text-start">{{ __('المنصة') }}</th>
                                <th class="py-2 text-start">{{ __('الإجراء') }}</th>
                                <th class="py-2 text-end">{{ __('الوقت') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($recentInteractions as $interaction)
                                <tr>
                                    <td class="py-2 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $interaction->user?->name ?? ($interaction->admin?->name ?? 'Guest / ' . substr($interaction->device_fingerprint ?? 'Unknown', 0, 8)) }}
                                    </td>
                                    <td class="py-2">
                                        <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[10px] font-bold">{{ $interaction->platform }}</span>
                                    </td>
                                    <td class="py-2">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $interaction->action === 'acknowledged' ? 'bg-emerald-100 text-emerald-800' : ($interaction->action === 'dismissed' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-[#012383]') }}">
                                            {{ $interaction->action }}
                                        </span>
                                    </td>
                                    <td class="py-2 text-end text-slate-400">
                                        {{ $interaction->created_at?->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400">
                                        {{ __('لا توجد تفاعلات مسجلة حتى الآن.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</x-admin-layout::admin>
