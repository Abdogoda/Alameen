<x-layouts::marketing
    :title="__('التحقق من الشهادة الرقمية المعتمدة')"
    :description="__('فحص وثيقة إتمام المسار السريري المعتمدة من منصة الأمين للتخاطب')"
>
    <div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 flex items-center justify-center">
        <div class="max-w-xl w-full bg-white rounded-3xl p-8 sm:p-10 shadow-2xl border border-[#DEE8F7] flex flex-col items-center text-center gap-6">
            
            <!-- Success Badge -->
            <div class="size-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200">
                <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>

            <div>
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                    {{ __('شهادة رقمية موثقة ومعتمدة إكلينيكياً') }}
                </span>
                <h1 class="text-2xl font-black text-[#012383] mt-3 font-['Cairo']">
                    {{ $certificate->course?->title }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    {{ __('تؤكد إدارة منصة الأمين أن الطالب الموضح أدناه قد أتم بنجاح متطلبات التدريب والمهام السريرية بنسبة ٩٥٪ فما فوق.') }}
                </p>
            </div>

            <!-- Student Data Details Box -->
            <div class="w-full bg-slate-50/80 rounded-2xl p-5 border border-[#DEE8F7] text-xs text-right grid grid-cols-2 gap-4">
                <div>
                    <span class="text-slate-400 block">{{ __('اسم الطالب:') }}</span>
                    <span class="font-bold text-slate-800 text-sm block mt-0.5">{{ $certificate->user?->name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">{{ __('الرقم التعريفي (ID):') }}</span>
                    <span class="font-mono font-bold text-slate-700 block mt-0.5">#{{ $certificate->user?->id }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">{{ __('رمز وثيقة التحقق:') }}</span>
                    <span class="font-mono font-bold text-[#012383] block mt-0.5">{{ $certificate->certificate_code }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">{{ __('تاريخ الاعتماد والإصدار:') }}</span>
                    <span class="font-bold text-slate-700 block mt-0.5">{{ $certificate->issued_at?->format('Y-m-d') }}</span>
                </div>
                <div class="col-span-2 border-t border-[#DEE8F7]/60 pt-3 flex items-center justify-between">
                    <span class="text-slate-500">{{ __('نسبة الإنجاز المحققة:') }}</span>
                    <span class="font-black text-emerald-700 text-sm">{{ $certificate->completion_percentage }}%</span>
                </div>
            </div>

            <!-- QR Code Verification Badge -->
            <div class="flex flex-col items-center gap-2">
                <div class="p-3 bg-white rounded-2xl border border-[#DEE8F7] shadow-xs">
                    {!! $certificate->qr_code_svg !!}
                </div>
                <span class="text-[11px] font-mono text-slate-400">{{ $certificate->verification_url }}</span>
            </div>

            <a href="{{ route('home') }}" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs flex items-center justify-center transition-colors">
                {{ __('العودة للرئيسية') }}
            </a>
        </div>
    </div>
</x-layouts::marketing>
