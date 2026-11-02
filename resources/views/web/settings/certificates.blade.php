<x-layouts::app :title="__('الشهادات المعتمدة')">
    <div class="w-full text-start">
        <x-settings.layout :heading="__('الشهادات الأكاديمية المعتمدة')" :subheading="__('سجل الشهادات الرسمية الصادرة والمرفوعة لك والموثقة برمز QR ورابط تحقق علني')">
            
            @if($certificates->isEmpty())
                <div class="bg-gradient-to-b from-slate-50 to-white dark:from-slate-800/40 dark:to-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-8 sm:p-12 text-center shadow-xs">
                    <div class="size-16 rounded-2xl bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-400 mx-auto flex items-center justify-center mb-4 border border-blue-100 dark:border-slate-700">
                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white">
                        {{ __('لم يتم إصدار أي شهادة لك حتى الآن') }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 max-w-md mx-auto leading-relaxed">
                        {{ __('عند قيام إدارة الأكاديمية برفع وتوثيق شهادة إتمام لدوراتك التدريبية، ستظهر هنا فورياً مع رابط التحقق الرسمي وإمكانية التحميل والمعاينة.') }}
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                    @foreach($certificates as $cert)
                        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 shadow-xs hover:border-[#012383]/40 dark:hover:border-blue-500/40 transition-all flex flex-col justify-between gap-5 relative overflow-hidden group">
                            
                            {{-- Header & File Badge --}}
                            <div>
                                <div class="flex items-start justify-between gap-3 min-w-0">
                                    <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                        @if($cert->file_path)
                                            @if($cert->isPdf())
                                                <a href="{{ $cert->fileUrl() }}" target="_blank" class="size-16 rounded-2xl bg-rose-50 dark:bg-slate-800 p-2 flex flex-col items-center justify-center shrink-0 border border-rose-100 dark:border-slate-700 hover:scale-105 transition-transform" title="{{ __('فتح ملف PDF') }}">
                                                    <svg class="size-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    <span class="text-[9px] font-black text-rose-700 dark:text-rose-400 uppercase tracking-wider mt-0.5">PDF</span>
                                                </a>
                                            @else
                                                <a href="{{ $cert->fileUrl() }}" target="_blank" class="size-16 rounded-2xl overflow-hidden shrink-0 border border-slate-200 dark:border-slate-700 hover:scale-105 transition-transform block bg-slate-100 dark:bg-slate-800" title="{{ __('عرض صورة الشهادة') }}">
                                                    <img src="{{ $cert->fileUrl() }}" alt="Certificate" class="w-full h-full object-cover">
                                                </a>
                                            @endif
                                        @else
                                            <div class="size-16 rounded-2xl bg-purple-50 dark:bg-slate-800 p-1 flex items-center justify-center shrink-0 border border-purple-100 dark:border-slate-700">
                                                {!! $cert->qr_code_svg !!}
                                            </div>
                                        @endif

                                        <div class="min-w-0 flex-1 text-start">
                                            <h4 class="font-bold text-sm sm:text-base text-slate-900 dark:text-white truncate">
                                                {{ $cert->course?->title ?? __('شهادة إتمام تدريب') }}
                                            </h4>
                                            <div class="font-mono text-xs font-bold text-[#012383] dark:text-blue-400 mt-1 truncate">
                                                {{ $cert->certificate_code }}
                                            </div>
                                            <div class="text-[11px] text-slate-400 mt-1">
                                                {{ __('تاريخ الإصدار:') }} {{ $cert->issued_at?->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>

                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 shrink-0">
                                        {{ round($cert->completion_percentage, 0) }}% {{ __('إنجاز') }}
                                    </span>
                                </div>

                                {{-- Verification Link Display --}}
                                <div class="mt-4 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2 min-w-0">
                                    <div class="text-[11px] font-mono text-slate-600 dark:text-slate-300 truncate min-w-0 flex-1 dir-ltr text-start">
                                        {{ $cert->verification_url }}
                                    </div>
                                    <a 
                                        href="{{ $cert->verification_url }}" 
                                        target="_blank" 
                                        class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-white dark:bg-slate-700 text-[#012383] dark:text-blue-300 border border-slate-200 dark:border-slate-600 hover:border-blue-500 shrink-0 inline-flex items-center gap-1 shadow-2xs"
                                    >
                                        <span>{{ __('رابط التحقق') }}</span>
                                        <svg class="size-3 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Actions Footer --}}
                            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3">
                                @if($cert->file_path)
                                    <a 
                                        href="{{ $cert->fileUrl() }}" 
                                        target="_blank" 
                                        class="h-9 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 inline-flex items-center gap-1.5 transition-colors"
                                    >
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>{{ __('معاينة الشهادة') }}</span>
                                    </a>

                                    <a 
                                        href="{{ $cert->fileUrl() }}" 
                                        download 
                                        class="h-9 px-4 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-xs font-bold text-white inline-flex items-center gap-1.5 shadow-2xs transition-colors"
                                    >
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>{{ __('تحميل الشهادة') }}</span>
                                    </a>
                                @else
                                    <a 
                                        href="{{ $cert->verification_url }}" 
                                        target="_blank" 
                                        class="h-9 px-4 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-xs font-bold text-white inline-flex items-center gap-1.5 shadow-2xs transition-colors"
                                    >
                                        <span>{{ __('عرض صفحة الشهادة') }}</span>
                                        <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </x-settings.layout>
    </div>
</x-layouts::app>
