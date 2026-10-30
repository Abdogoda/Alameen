<x-layouts::app :title="__('شهاداتي المعتمدة')">
    <div class="flex flex-col gap-6 w-full text-start">
        
        <!-- Page Introduction -->
        <div class="flex flex-col gap-[4px] items-start text-start w-full">
            <p class="font-['Cairo'] font-semibold text-[12px] leading-[1.8] text-[#0B2A8F] w-full text-start">
                {{ __('مساحة التعلّم / شهاداتي المعتمدة') }}
            </p>
            <h1 class="font-['Cairo'] font-extrabold text-[26px] sm:text-[30px] md:text-[34px] leading-[1.3] text-[#012383] w-full text-start">
                {{ __('سجل الشهادات الأكاديمية') }}
            </h1>
            <p class="font-['Cairo'] font-medium text-[13px] md:text-[14px] leading-[1.8] text-[#475569] w-full text-start">
                {{ __('تُمنح الشهادة آلياً وفورياً عند بلوغ ٩٥٪ من إجمالي مشاهدة فيديوهات الكورس.') }}
            </p>
        </div>

        @if(session('info'))
        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-[#012383] text-sm font-bold flex items-center gap-3">
            <svg class="size-5 shrink-0 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('info') }}</span>
        </div>
        @endif

        <!-- Strict 95% Eligibility Explanation Card -->
        <div class="bg-gradient-to-l from-[#012383] to-[#0B2A8F] border border-[#0B2A8F]/40 flex flex-col md:flex-row gap-6 md:gap-[32px] items-start md:items-center p-6 md:p-[32px] rounded-[16px] md:rounded-[20px] shadow-[0px_12px_32px_rgba(1,35,131,0.18)] w-full overflow-hidden">
            <div class="size-[52px] md:size-[56px] shrink-0 relative">
                <img src="/assets/images/icon-award-white.svg" alt="" class="size-full" />
            </div>

            <div class="flex flex-1 flex-col gap-[8px] items-start min-w-0 text-start">
                <h2 class="font-['Cairo'] font-bold text-[22px] md:text-[26px] leading-[1.35] text-white w-full text-start">
                    {{ __('شهادة إتمام معتمدة عند إنجاز ٩٥٪ من مشاهدة الكورس') }}
                </h2>
                <p class="font-['Cairo'] font-normal text-[14px] md:text-[15px] leading-[1.7] text-[#DEE8F7] w-full text-start">
                    {{ __('تُحتسب نسبة الإكمال بدقة عبر منصة الفيديوهات الآمنة. بمجرد وصول مجموع ساعات المشاهدة إلى ٩٥٪ يصدر النظام شهادتك المزودة برمز QR فريد وكود تحقق رقمي رسمي.') }}
                </p>
            </div>

            <div class="flex flex-col gap-[4px] items-start md:items-center text-start md:text-center w-auto md:w-[200px] shrink-0">
                <p class="font-['Cairo'] font-extrabold text-[48px] md:text-[64px] leading-none text-white tracking-tight">
                    ٩٥٪
                </p>
                <p class="font-['Cairo'] font-semibold text-[14px] leading-[1.6] text-[#DEE8F7]">
                    {{ __('حد إكمال المشاهدة') }}
                </p>
            </div>
        </div>

        @php
            $eligibleList = collect($courseProgressData)->filter(fn($i) => $i['is_eligible']);
            $pendingList = collect($courseProgressData)->filter(fn($i) => ! $i['is_eligible']);
        @endphp

        <!-- 1. Eligible / Issued Certificates Section -->
        @if($eligibleList->count() > 0)
        <div class="space-y-4">
            <h3 class="font-['Cairo'] font-bold text-[20px] md:text-[22px] leading-[1.4] text-[#012383]">
                {{ __('الشهادات المتاحة والمعتمدة') }} ({{ $eligibleList->count() }})
            </h3>

            <div class="grid grid-cols-1 gap-4">
                @foreach($eligibleList as $item)
                <div class="bg-white border border-[#DEE8F7] rounded-[16px] md:rounded-[20px] p-5 md:p-[24px] flex flex-col lg:flex-row gap-6 lg:gap-[32px] items-start lg:items-center w-full shadow-sm">
                    <div class="flex flex-1 flex-col gap-[14px] items-start min-w-0 text-start w-full">
                        <div class="bg-[#EEF7F3] px-[12px] py-[4px] rounded-full shrink-0">
                            <span class="font-['Cairo'] font-bold text-[12px] leading-[1.8] text-[#216350] whitespace-nowrap block">
                                {{ __('مؤهل • الشهادة جاهزة ومعتمدة ✦') }}
                            </span>
                        </div>

                        <h3 class="font-['Cairo'] font-bold text-[22px] md:text-[26px] leading-[1.35] text-[#012383] text-start w-full">
                            {{ $item['course']->title }}
                        </h3>

                        <p class="font-['Cairo'] font-medium text-[13px] md:text-[14px] leading-[1.8] text-[#475569] text-start w-full">
                            {{ __('أتممت نسبة :pct% من فيديوهات هذا الكورس بنجاح.', ['pct' => round($item['progress'], 1)]) }}
                        </p>

                        <div class="bg-[#DEE8F7] h-[8px] rounded-full w-full overflow-hidden relative shrink-0">
                            <div class="bg-emerald-600 h-full rounded-full" style="width: {{ min(100, $item['progress']) }}%"></div>
                        </div>

                        <div class="flex flex-wrap gap-[12px] items-center pt-[4px] shrink-0">
                            <a
                                href="{{ route('certificates.show', $item['certificate']?->id) }}"
                                class="bg-[#0B2A8F] border border-[#0B2A8F] rounded-[10px] h-[44px] px-[20px] py-[8px] text-white font-['Cairo'] font-bold text-[14px] leading-[1.5] flex items-center justify-center hover:bg-[#012383] transition-colors shadow-sm cursor-pointer whitespace-nowrap"
                            >
                                {{ __('عرض الشهادة والـ QR كود') }}
                            </a>
                            <a
                                href="{{ route('certificates.show', $item['certificate']?->id) }}"
                                class="bg-white border border-[#DEE8F7] rounded-[10px] h-[44px] px-[20px] py-[8px] text-[#0B2A8F] font-['Cairo'] font-bold text-[14px] leading-[1.5] flex items-center justify-center hover:bg-[#EAF1FB] transition-colors cursor-pointer whitespace-nowrap"
                            >
                                {{ __('طباعة / تنزيل PDF ⭳') }}
                            </a>
                        </div>
                    </div>

                    <div class="bg-[#FAFCFF] border border-[#CFDBEA] rounded-[12px] h-[180px] w-full max-w-[280px] p-3 flex flex-col items-center justify-center shrink-0 shadow-inner text-center">
                        <div class="size-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold mb-2">
                            🎓
                        </div>
                        <span class="font-bold text-xs text-[#012383]">{{ $item['certificate']?->certificate_code ?? __('شهادة إتمام') }}</span>
                        <span class="text-[11px] text-slate-400 mt-1">{{ __('معتمدة برمجياً بنسبة :pct%', ['pct' => round($item['progress'], 1)]) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 2. Pending Courses Section -->
        @if($pendingList->count() > 0)
        <div class="space-y-4 pt-2">
            <div class="flex items-center justify-between w-full">
                <h3 class="font-['Cairo'] font-bold text-[20px] md:text-[22px] leading-[1.4] text-[#012383]">
                    {{ __('كورساتك قيد التعلّم والإكمال') }} ({{ $pendingList->count() }})
                </h3>
                <span class="text-xs text-slate-500 font-medium">
                    {{ __('تحتاج إلى ٩٥٪ لفتح شهادة كل كورس') }}
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-[20px] w-full">
                @foreach($pendingList as $item)
                @php
                    $pct = round($item['progress'], 1);
                    $rem = max(0, 95 - $pct);
                @endphp
                <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-[24px] flex flex-col gap-[16px] shadow-sm">
                    <div class="flex items-center justify-between w-full">
                        <div class="size-[24px] text-slate-400 shrink-0">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div class="bg-[#FAFCFF] border border-[#DEE8F7] px-[10px] py-[3px] rounded-full">
                            <span class="font-['Cairo'] font-bold text-[12px] leading-[1.8] text-[#475569] whitespace-nowrap">
                                {{ __('غير مؤهل بعد') }}
                            </span>
                        </div>
                    </div>

                    <h4 class="font-['Cairo'] font-bold text-[20px] md:text-[22px] leading-[1.4] text-[#012383] text-start">
                        {{ $item['course']->title }}
                    </h4>

                    <div class="flex items-start justify-between w-full font-['Cairo']">
                        <p class="font-['Cairo'] font-medium text-[13px] text-[#475569]">
                            {{ __('نسبة إكمال المشاهدة') }}
                        </p>
                        <p class="font-['Cairo'] font-bold text-[20px] text-[#012383]">
                            {{ $pct }}%
                        </p>
                    </div>

                    <div class="bg-[#DEE8F7] h-[8px] rounded-full w-full overflow-hidden">
                        <div class="bg-[#0B2A8F] h-full rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>

                    <div class="bg-[#EAF1FB] rounded-[10px] p-[16px] flex flex-col gap-[4px] text-start">
                        <p class="font-['Cairo'] font-bold text-[14px] text-[#012383]">
                            {{ __('متبقي :rem% فقط للوصول إلى شرط الشهادة (٩٥٪)', ['rem' => $rem]) }}
                        </p>
                        <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]">
                            {{ __('تابع مشاهدة الدروس المتبقية لتفعيل زر طباعة الشهادة آلياً.') }}
                        </p>
                    </div>

                    <div class="pt-2">
                        <a
                            href="{{ route('lessons.show', ['course' => $item['course']->id]) }}"
                            class="inline-flex items-center justify-center w-full h-11 rounded-xl bg-slate-50 border border-[#DEE8F7] hover:bg-[#EAF1FB] text-[#0B2A8F] font-bold text-xs transition-colors"
                        >
                            {{ __('متابعة مشاهدة الكورس ◀') }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(count($courseProgressData) === 0)
        <div class="bg-white border border-[#DEE8F7] rounded-3xl p-12 text-center flex flex-col items-center justify-center gap-4">
            <div class="size-16 rounded-full bg-blue-50 text-[#0B2A8F] flex items-center justify-center font-bold text-2xl">
                📚
            </div>
            <h3 class="font-bold text-lg text-[#012383]">{{ __('أنت غير مسجل في أي كورس بعد') }}</h3>
            <p class="text-xs text-slate-500 max-w-md">
                {{ __('تصفح دليل الكورسات المتاحة وسجل الآن للبدء في رحلتك التعليمية والحصول على الشهادات المعتمدة.') }}
            </p>
            <a href="{{ route('courses.index') }}" class="px-6 h-11 rounded-xl bg-[#012383] text-white hover:bg-[#0B2A8F] font-bold text-xs flex items-center justify-center transition-colors shadow-sm">
                {{ __('استعراض الكورسات المتاحة') }}
            </a>
        </div>
        @endif

    </div>
</x-layouts::app>
