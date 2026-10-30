<x-layouts::app :title="__('شهادة الإتمام المعتمدة • :code', ['code' => $certificate->certificate_code])">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-certificate, #printable-certificate * {
                visibility: visible;
            }
            #printable-certificate {
                position: fixed;
                left: 0;
                top: 0;
                width: 100vw;
                height: 100vh;
                margin: 0;
                padding: 24px;
                box-shadow: none;
                border: none;
                background: white !important;
                z-index: 99999;
                page-break-after: avoid;
                page-break-inside: avoid;
            }
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }
    </style>

    <div class="flex flex-col gap-6 w-full text-start">
        
        <!-- Page Introduction (Hidden on Print) -->
        <div class="print:hidden">
            <p class="text-[13px] md:text-[14px] text-[#0B2A8F] font-semibold mb-1 font-['Cairo']">
                {{ __('شهاداتك وكورساتك') }}
            </p>
            <h1 class="text-[28px] sm:text-[32px] md:text-[36px] font-extrabold text-[#012383] font-['Cairo'] leading-[1.3] mb-1.5">
                {{ __('شهادة إتمام معتمدة') }}
            </h1>
            <p class="text-[14px] md:text-[15px] text-[#475569] font-medium font-['Cairo']">
                {{ __('عرض وتنزيل شهادة إتمام كورس :course', ['course' => $certificate->course?->title]) }}
            </p>
        </div>

        <!-- Toolbar (Hidden on Print) -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 print:hidden">
            <div class="flex flex-wrap items-center gap-3">
                <button
                    type="button"
                    onclick="window.print()"
                    class="h-[44px] px-5 rounded-[10px] bg-[#0B2A8F] hover:bg-[#012383] text-white text-[14px] font-bold flex items-center gap-2 shadow-sm transition-all cursor-pointer font-['Cairo']"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>{{ __('طباعة / تنزيل PDF الشهادة') }}</span>
                </button>

                <a
                    href="{{ route('certificates.index') }}"
                    class="h-[44px] px-5 rounded-[10px] bg-white border border-[#DEE8F7] hover:bg-[#FAFCFF] text-[#0B2A8F] text-[14px] font-bold flex items-center gap-2 transition-all font-['Cairo']"
                >
                    <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>{{ __('العودة إلى سجل الشهادات') }}</span>
                </a>
            </div>

            <div class="px-3.5 py-1.5 rounded-full bg-[#EEF7F3] border border-[#D1EADB] flex items-center gap-1.5">
                <span class="size-2 rounded-full bg-[#216350]"></span>
                <span class="text-[12px] font-bold text-[#216350] font-['Cairo']">
                    {{ __('شهادة رسمية موثقة برمز QR وكود تحقق') }} ✦
                </span>
            </div>
        </div>

        <!-- Pixel-Perfect Certificate Canvas Card -->
        <div class="w-full bg-white border border-[#CFDBEA] rounded-[14px] p-6 sm:p-10 relative overflow-hidden shadow-xl" id="printable-certificate">
            <!-- Navy Bar Ribbon -->
            <div class="absolute start-0 top-0 bottom-0 w-[14px] bg-[#012383]"></div>

            <!-- Inner Gold/Navy Frame -->
            <div class="border-2 border-[#DEE8F7] rounded-[10px] px-6 sm:px-12 md:px-16 py-10 flex flex-col items-center text-center relative z-10 bg-white/95">
                
                <!-- Issuer Header -->
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-[26px] sm:text-[30px] font-extrabold text-[#012383] font-['Cairo']">
                        {{ __('منصة مركز الأمين للتخاطب وعلاج النطق') }}
                    </span>
                    <div class="size-[48px] sm:size-[56px] shrink-0">
                        <img src="/assets/images/logo.png" alt="{{ __('شعار المركز') }}" class="size-full object-contain" />
                    </div>
                </div>

                <!-- Main Heading -->
                <h2 class="text-[34px] sm:text-[42px] font-black text-[#012383] font-['Cairo'] mb-1 tracking-tight">
                    {{ __('شـــهــادة إتـــمــــام') }}
                </h2>

                <p class="text-[14px] sm:text-[16px] text-[#475569] font-medium mb-3 font-['Cairo']">
                    {{ __('تَشهد إدارة منصة مركز الأمين بأن الدارس:') }}
                </p>

                <!-- Student Full Name -->
                <div class="my-3 flex flex-col items-center">
                    <p class="text-[36px] sm:text-[46px] font-black text-[#012383] font-['Cairo'] leading-tight">
                        {{ $certificate->user?->name }}
                    </p>
                    <div class="w-[240px] h-[3px] bg-gradient-to-r from-transparent via-[#0B2A8F] to-transparent mt-2"></div>
                </div>

                <!-- Course Qualification Text -->
                <p class="text-[14px] sm:text-[16px] text-[#475569] font-medium mb-1 font-['Cairo']">
                    {{ __('قد أتم بنجاح وبكفاءة عالية المتطلبات الأكاديمية لمشاهدة ودراسة كورس:') }}
                </p>

                <!-- Course Title -->
                <p class="text-[24px] sm:text-[30px] font-black text-[#0B2A8F] font-['Cairo'] mb-2">
                    {{ $certificate->course?->title }}
                </p>

                <!-- Completion Stats -->
                <p class="text-[15px] sm:text-[17px] font-bold text-[#012383] mb-6 font-['Cairo']">
                    {{ __('بنسبة إكمال فعلية بلغت :pct% وفق المعيار الأكاديمي الصارم (حد أدنى ٩٥٪)', ['pct' => round($certificate->completion_percentage, 1)]) }}
                </p>

                <!-- Certificate Footer with QR Code & Metadata -->
                <div class="w-full pt-6 mt-4 border-t border-[#DEE8F7] flex flex-col sm:flex-row items-center justify-between gap-6 px-4">
                    
                    <div class="flex flex-col text-start">
                        <span class="text-[12px] text-[#475569] font-medium mb-0.5 font-['Cairo']">
                            {{ __('تاريخ الإصدار والاعتماد:') }}
                        </span>
                        <span class="text-[15px] font-bold text-[#012383] font-['Cairo']">
                            {{ $certificate->issued_at?->format('d/m/Y') ?? now()->format('d/m/Y') }}
                        </span>
                    </div>

                    <!-- Unique Verification Code and QR Code -->
                    <div class="flex items-center gap-4">
                        <div class="size-20 shrink-0 bg-white p-1 rounded-lg border border-[#DEE8F7] shadow-2xs">
                            @if($certificate->qr_code_svg)
                                {!! $certificate->qr_code_svg !!}
                            @else
                                <div class="size-full bg-slate-100 flex items-center justify-center font-mono text-[9px] text-slate-500">QR</div>
                            @endif
                        </div>
                        <div class="flex flex-col text-start">
                            <span class="text-[11px] text-[#475569] font-medium mb-0.5 font-['Cairo']">
                                {{ __('كود التحقق الرقمي:') }}
                            </span>
                            <a href="{{ route('certificates.verify', $certificate->certificate_code) }}" target="_blank" class="text-[13px] font-mono font-bold text-[#0B2A8F] underline">
                                {{ $certificate->certificate_code }}
                            </a>
                            <span class="text-[10px] text-emerald-600 font-bold mt-0.5">
                                ✓ {{ __('شهادة موثقة وصالحة للتحقق العلني') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col text-center sm:text-end">
                        <span class="text-[12px] text-[#475569] font-medium mb-0.5 font-['Cairo']">
                            {{ __('اعتماد إدارة التدريب والتأهيل:') }}
                        </span>
                        <span class="text-[15px] font-black text-[#012383] font-['Cairo']">
                            {{ __('د. مركز الأمين للتخاطب') }}
                        </span>
                    </div>

                </div>

            </div>
        </div>

        <!-- Information Note Card (Hidden on Print) -->
        <div class="w-full p-4 rounded-[12px] bg-[#FAFCFF] border border-[#DEE8F7] flex items-start gap-3.5 print:hidden">
            <div class="size-9 rounded-full bg-[#EAF1FB] flex items-center justify-center text-[#0B2A8F] shrink-0">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-bold text-[#012383] mb-0.5 font-['Cairo']">
                    {{ __('عن التحقق من هذه الشهادة') }}
                </p>
                <p class="text-[12px] text-[#475569] leading-relaxed font-['Cairo']">
                    {{ __('يمكن لأي جهة توظيف أو مستشفى فحص صحة الشهادة بمسح رمز الاستجابة السريعة (QR Code) أو بزيارة رابط التحقق العلني:') }}
                    <a href="{{ route('certificates.verify', $certificate->certificate_code) }}" class="text-[#0B2A8F] underline font-bold" target="_blank">
                        {{ url('/verify-certificate/' . $certificate->certificate_code) }}
                    </a>
                </p>
            </div>
        </div>

    </div>
</x-layouts::app>
