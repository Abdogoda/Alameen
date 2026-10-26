<x-admin-layout::admin
    :title="__('سجل الشهادات المعتمدة ومعيار الـ 95%')"
    :description="__('إدارة إصدار الشهادات الرقمية المؤتمتة وفق شرط إكمال مشاهدة ٩٥٪ من فيديوهات الكورس')"
    contentClass="p-4 sm:p-8 flex flex-col gap-6 max-w-[1500px] w-full mx-auto"
>
    <!-- Strict 95% Rule Banner -->
    <div class="bg-gradient-to-r from-emerald-900 via-[#0B2A8F] to-[#012383] rounded-3xl p-6 sm:p-7 text-white shadow-card flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
        <div class="space-y-1 text-right">
            <div class="flex items-center gap-2">
                <span class="px-3 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-bold">
                    {{ __('قاعدة الأمان الأكاديمي الصارمة') }}
                </span>
                <span class="text-xs text-white/70">• {{ __('إكمال ٩٥٪ كحد أدنى') }}</span>
            </div>
            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-white">
                {{ __('شرط إتمام المشاهدة الإلزامي (Strict 95% Video Completion Gate)') }}
            </h3>
            <p class="text-xs sm:text-sm text-white/80 max-w-2xl leading-relaxed">
                {{ __('المنصة مبرمجة برمجياً بحيث يُقفل رابط إصدار الشهادة ولا يستطيع أي طالب استخراجها إلا إذا بلغت نسبة مشاهدته الفعلية المسجلة في قاعدة البيانات ٩٥٪ فما فوق.') }}
            </p>
        </div>
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-center shrink-0 w-full sm:w-auto">
            <span class="text-xs text-white/70 block">{{ __('الشهادات المصدرة حتى الآن') }}</span>
            <span class="text-3xl font-black text-white block mt-0.5">{{ $totalCertificatesCount ?? 0 }} {{ __('شهادة') }}</span>
            <span class="text-[11px] text-emerald-300 block">✦ {{ __('جميعها تحققت برمجياً') }}</span>
        </div>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-4 sm:p-6 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.certificates.index') }}" class="relative w-full sm:w-96">
            <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('ابحث برقم الشهادة، اسم الطالب أو الكورس...') }}" class="w-full h-11 ps-4 pe-10 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
            <button type="submit" class="absolute end-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#0B2A8F]">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </form>
    </div>

    <!-- Certificates Table -->
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl overflow-hidden shadow-xs">
        <div class="p-5 border-b border-[#DEE8F7] dark:border-slate-800 flex items-center justify-between">
            <div>
                <h4 class="font-bold text-base text-[#012383] dark:text-blue-400">{{ __('سجل الطلاب المؤهلين والحاصلين على الشهادة') }}</h4>
                <p class="text-xs text-slate-400">{{ __('تحقق من بيانات الاعتماد، كود التوثيق، وتاريخ الإصدار') }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-[#DEE8F7] dark:border-slate-800 text-[12px] font-bold text-slate-500">
                        <th class="py-4 px-5">{{ __('اسم الخريج / الطالب') }}</th>
                        <th class="py-4 px-4">{{ __('الكورس التدريبي') }}</th>
                        <th class="py-4 px-4">{{ __('رقم الاعتماد التسلسلي') }}</th>
                        <th class="py-4 px-4 text-center">{{ __('نسبة المشاهدة الفعلية') }}</th>
                        <th class="py-4 px-4">{{ __('تاريخ الإصدار') }}</th>
                        <th class="py-4 px-5 text-center">{{ __('إجراءات الشهادة والتحقق') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#DEE8F7]/70 dark:divide-slate-800">
                    
                    @forelse ($certificates as $cert)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-4 px-5">
                            <div class="font-bold text-sm text-[#012383] dark:text-blue-400">{{ $cert->user?->name ?? '—' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $cert->user?->email }}</div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $cert->course?->title ?? '—' }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-mono text-xs font-bold text-[#0B2A8F] dark:text-blue-400 bg-[#EAF1FB] dark:bg-slate-800 px-2.5 py-1 rounded-md">
                                {{ $cert->certificate_code }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ round($cert->completion_percentage, 1) }}%
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $cert->issued_at?->format('d/m/Y') }}</span>
                            <div class="text-[10px] text-slate-400">{{ $cert->issued_at?->diffForHumans() }}</div>
                        </td>
                        <td class="py-4 px-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a
                                    href="{{ route('certificates.verify', $cert->certificate_code) }}"
                                    target="_blank"
                                    class="px-3 py-1.5 rounded-xl bg-[#012383] text-white hover:bg-[#0B2A8F] font-bold text-xs transition-colors shadow-2xs"
                                >
                                    {{ __('صفحة التحقق العلني ↗') }}
                                </a>
                                <a
                                    href="{{ route('certificates.show', $cert->id) }}"
                                    target="_blank"
                                    class="px-3 py-1.5 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-[#0B2A8F] dark:text-blue-400 hover:bg-[#EAF1FB] font-bold text-xs transition-colors"
                                >
                                    {{ __('طباعة A4 🖨️') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-400">
                            {{ __('لا توجد شهادات صادرة تطابق خيارات البحث.') }}
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        @if($certificates->hasPages())
        <div class="p-4 bg-slate-50/70 dark:bg-slate-800/40 border-t border-[#DEE8F7] dark:border-slate-800">
            {{ $certificates->links() }}
        </div>
        @endif
    </div>

    <x-slot:modals>
    </x-slot:modals>
</x-admin-layout::admin>
