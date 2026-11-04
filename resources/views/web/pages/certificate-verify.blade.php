<x-layouts::marketing
    :title="__('التحقق من الشهادة الرقمية المعتمدة • :code', ['code' => $certificate->certificate_code])"
    :description="__('فحص وثيقة إتمام المسار التدريبي المعتمدة من منصة الأمين للتخاطب')"
>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10 px-4 sm:px-6 flex flex-col items-center">
        
        <div class="max-w-[1100px] w-full flex flex-col items-center gap-8">
            
            {{-- Official Verification Status Header --}}
            <div class="w-full bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-[#DEE8F7] dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6 print:hidden">
                <div class="flex items-center gap-4 text-start">
                    <div class="size-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-200 dark:border-emerald-800 shrink-0">
                        <svg class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">
                                {{ __('شهادة إلكترونية أصلية وموثقة رسمياً') }}
                            </span>
                            <span class="text-xs font-mono font-bold text-[#012383] dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40 px-2 py-0.5 rounded-md">
                                {{ $certificate->certificate_code }}
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-[#012383] dark:text-white mt-1.5 {{ app()->getLocale() === 'ar' ? "font-['Cairo']" : "" }}">
                            {{ $certificate->course?->title }}
                        </h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ __('تؤكد إدارة منصة الأمين أن الطالب (:name) قد أتم بنجاح متطلبات التدريب والمهام التطبيقية بنسبة :pct%.', ['name' => $certificate->user?->name, 'pct' => round($certificate->completion_percentage, 1)]) }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('home') }}" class="px-5 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center justify-center transition-colors">
                        {{ __('الرئيسية') }}
                    </a>
                    <button type="button" onclick="window.print()" class="px-5 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs flex items-center gap-1.5 shadow-xs transition-colors cursor-pointer">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>{{ __('طباعة الشهادة') }}</span>
                    </button>
                </div>
            </div>

            {{-- Uploaded Certificate Display --}}
            <div class="w-full bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-[#DEE8F7] dark:border-slate-800 shadow-sm flex flex-col items-center">
                @if($certificate->file_path)
                    @if($certificate->isPdf())
                        <div class="w-full flex flex-col items-center gap-4">
                            <div class="w-full h-[650px] sm:h-[750px] rounded-2xl overflow-hidden border border-[#DEE8F7] dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
                                <iframe src="{{ $certificate->fileUrl() }}" class="w-full h-full" title="{{ __('ملف الشهادة المعتمد') }}"></iframe>
                            </div>
                            <div class="flex items-center gap-3">
                                <a 
                                    href="{{ $certificate->fileUrl() }}" 
                                    target="_blank" 
                                    class="h-11 px-5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-xs inline-flex items-center gap-2 transition-colors"
                                >
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    <span>{{ __('فتح في نافذة جديدة') }}</span>
                                </a>
                                <a 
                                    href="{{ $certificate->fileUrl() }}" 
                                    download 
                                    class="h-11 px-6 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs inline-flex items-center gap-2 shadow-xs transition-colors"
                                >
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>{{ __('تحميل الشهادة (PDF)') }}</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="w-full flex flex-col items-center gap-4">
                            <div class="w-full max-w-4xl rounded-2xl overflow-hidden border border-[#DEE8F7] dark:border-slate-800 shadow-md bg-slate-50 dark:bg-slate-950 p-2">
                                <img src="{{ $certificate->fileUrl() }}" alt="{{ __('الشهادة المعتمدة') }}" class="w-full h-auto rounded-xl object-contain mx-auto">
                            </div>
                            <div class="flex items-center gap-3">
                                <a 
                                    href="{{ $certificate->fileUrl() }}" 
                                    target="_blank" 
                                    class="h-11 px-5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-xs inline-flex items-center gap-2 transition-colors"
                                >
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>{{ __('عرض بالدقة الكاملة') }}</span>
                                </a>
                                <a 
                                    href="{{ $certificate->fileUrl() }}" 
                                    download 
                                    class="h-11 px-6 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs inline-flex items-center gap-2 shadow-xs transition-colors"
                                >
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>{{ __('تحميل الشهادة') }}</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="w-full max-w-4xl flex flex-col items-center gap-6">
                        <x-certificate-preview 
                            :student-name="$certificate->user?->name ?? 'Student Name'"
                            :course-title="$certificate->course?->title ?? 'Level 1 of the Comprehensive Speech and Communication Course'"
                            :serial-number="$certificate->certificate_code"
                            :completion-date="$certificate->issued_at?->format('F d, Y')"
                        />
                    </div>
                @endif
            </div>

            {{-- Verification Credentials & Forensic Log --}}
            <div class="w-full bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-[#DEE8F7] dark:border-slate-800 shadow-sm print:hidden">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-3 mb-5 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base text-[#012383] dark:text-blue-400 {{ app()->getLocale() === 'ar' ? "font-['Cairo']" : "" }}">
                            {{ __('بيانات الاعتماد وسجل التدقيق الأكاديمي') }}
                        </h3>
                        <p class="text-xs text-slate-400">
                            {{ __('معلومات الشهادة المحفوظة في قاعدة بيانات المنصة برمز التحقق المشفر') }}
                        </p>
                    </div>
                    <span class="text-xs text-emerald-600 font-bold flex items-center gap-1">
                        <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>{{ __('بيانات مطابقة وسارية') }}</span>
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs text-start">
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-[#DEE8F7]/60 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">{{ __('اسم الطالب الخريج:') }}</span>
                        <span class="font-bold text-slate-800 dark:text-white text-sm">{{ $certificate->user?->name }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-[#DEE8F7]/60 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">{{ __('رمز التحقق المشفر (Code):') }}</span>
                        <span class="font-mono font-bold text-[#012383] dark:text-blue-400 text-sm">{{ $certificate->certificate_code }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-[#DEE8F7]/60 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">{{ __('تاريخ الإصدار والاعتماد:') }}</span>
                        <span class="font-bold text-slate-800 dark:text-white text-sm">{{ $certificate->issued_at?->format('Y/m/d') }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-[#DEE8F7]/60 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">{{ __('نسبة إنجاز الكورس:') }}</span>
                        <span class="font-black text-emerald-600 text-sm">{{ round($certificate->completion_percentage, 1) }}% ({{ __('حد أدنى ٩٥٪') }})</span>
                    </div>
                </div>

                {{-- Direct Share & Copy Link Section --}}
                <div class="mt-4 pt-4 border-t border-[#DEE8F7] dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 text-xs" x-data="{ copied: false }">
                    <div class="flex items-center gap-2 min-w-0 max-w-full">
                        <span class="text-slate-400 font-medium shrink-0">{{ __('رابط الفحص المباشر:') }}</span>
                        <span class="font-mono text-[#012383] dark:text-blue-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg select-all truncate dir-ltr text-start">
                            {{ $certificate->verification_url }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button
                            type="button"
                            @click="navigator.clipboard.writeText('{{ $certificate->verification_url }}'); copied = true; setTimeout(() => copied = false, 2500)"
                            class="h-9 px-3.5 rounded-xl bg-slate-100 hover:bg-[#EAF1FB] text-slate-700 hover:text-[#012383] dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 font-bold transition-all cursor-pointer inline-flex items-center gap-1.5"
                        >
                            <span x-show="!copied">📋 {{ __('نسخ الرابط') }}</span>
                            <span x-show="copied" x-cloak class="text-emerald-600 font-bold">✓ {{ __('تم النسخ!') }}</span>
                        </button>
                        <a
                            href="https://api.whatsapp.com/send?text={{ urlencode(__('شهادة معتمدة من منصة الأمين: ').$certificate->verification_url) }}"
                            target="_blank"
                            class="h-9 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition-all inline-flex items-center gap-1.5 shadow-2xs"
                        >
                            <span>💬 {{ __('مشاركة واتساب') }}</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-layouts::marketing>
