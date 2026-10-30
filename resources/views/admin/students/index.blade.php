<x-admin-layout::admin
    :title="__('دليل الطلاب والتحكم بصلاحيات الوصول')"
    :description="__('إدارة حسابات الدارسين، تتبع ساعات المشاهدة، ومنح أو إلغاء صلاحية الوصول لكورس التخاطب')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-5 sm:gap-6 max-w-[1500px] w-full mx-auto min-w-0"
>
    <x-slot:actions>
        <span class="text-xs font-bold text-[#012383] dark:text-blue-400 bg-[#EAF1FB] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 px-3 py-1.5 rounded-xl self-start sm:self-auto">
            {{ __('إجمالي :count طالب مسجل', ['count' => $counts['all'] ?? 0]) }}
        </span>
    </x-slot:actions>

    <!-- Filters & Live Search Toolbar -->
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-3 sm:p-4 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4">
        <div class="flex items-center gap-2 overflow-x-auto scrollbar-none whitespace-nowrap pb-1 sm:pb-0">
            <a href="{{ route('admin.students.index', ['status' => 'all', 'search' => $search]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $status === 'all' ? 'bg-[#012383] text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#EAF1FB]' }}">
                {{ __('جميع الطلاب') }} ({{ $counts['all'] ?? 0 }})
            </a>
            <a href="{{ route('admin.students.index', ['status' => 'active', 'search' => $search]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $status === 'active' ? 'bg-[#012383] text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#EAF1FB]' }}">
                {{ __('نشط ومفعل') }} ({{ $counts['active'] ?? 0 }})
            </a>
            <a href="{{ route('admin.students.index', ['status' => 'suspended', 'search' => $search]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $status === 'suspended' ? 'bg-[#012383] text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#EAF1FB]' }}">
                {{ __('ملغي الصلاحية') }} ({{ $counts['suspended'] ?? 0 }})
            </a>
        </div>

        <form method="GET" action="{{ route('admin.students.index') }}" class="relative w-full sm:w-80">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('ابحث بالاسم، البريد أو الهاتف...') }}" class="w-full h-11 ps-4 pe-10 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
            <button type="submit" class="absolute end-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#0B2A8F]">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </form>
    </div>

    <!-- Students Roster Container -->
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl overflow-hidden shadow-xs">
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto min-w-0">
            <table class="w-full text-right border-collapse min-w-[760px]">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-[#DEE8F7] dark:border-slate-800 text-[12px] font-bold text-slate-500">
                        <th class="py-4 px-5">{{ __('الطالب وبيانات القيد') }}</th>
                        <th class="py-4 px-4">{{ __('تاريخ التسجيل') }}</th>
                        <th class="py-4 px-4">{{ __('الكورسات المشترك بها') }}</th>
                        <th class="py-4 px-4">{{ __('المدينة / الدولة') }}</th>
                        <th class="py-4 px-4 text-center">{{ __('حالة الحساب') }}</th>
                        <th class="py-4 px-5 text-center">{{ __('التحكم بالوصول') }}</th>
                    </tr>
                </thead>
                <tbody id="students-table-body" class="divide-y divide-[#DEE8F7]/70 dark:divide-slate-800 text-xs">
                    @forelse ($students as $student)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="size-10 rounded-full {{ $student->isActive() ? 'bg-[#EAF1FB] text-[#0B2A8F] dark:bg-blue-950/60 dark:text-blue-300' : 'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-300' }} flex items-center justify-center font-bold text-sm shrink-0">
                                    {{ mb_substr($student->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-sm text-[#012383] dark:text-blue-400 truncate">{{ $student->name }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono truncate">{{ $student->phone ?? '—' }} • {{ $student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $student->created_at?->format('d/m/Y') }}</span>
                            <div class="text-[10px] text-slate-400">{{ $student->created_at?->diffForHumans() }}</div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-sm text-[#012383] dark:text-blue-400">{{ $student->courses->count() }} {{ __('كورس') }}</span>
                            @if($student->certificates->count() > 0)
                                <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">🎓 {{ $student->certificates->count() }} {{ __('شهادة معتمدة') }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $student->city ?? '—' }}</span>
                            @if($student->country)
                                <div class="text-[10px] text-slate-400">{{ $student->country }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($student->isActive())
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                    {{ __('نشط ومفعل') }}
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800">
                                    {{ __('معلق / ملغي الصلاحية') }}
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center">
                            <form method="POST" action="{{ route('admin.students.toggle', $student->id) }}" onsubmit="return confirm('{{ $student->isActive() ? __('هل أنت متأكد من تعليق حساب هذا الطالب؟') : __('هل أنت متأكد من إعادة تنشيط هذا الحساب؟') }}');">
                                @csrf
                                @if($student->isActive())
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800 transition-colors cursor-pointer">
                                        {{ __('تعليق الحساب') }}
                                    </button>
                                @else
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 transition-colors cursor-pointer">
                                        {{ __('إعادة التنشيط') }}
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-400">
                            {{ __('لا يوجد طلاب يطابقون خيارات البحث.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-[#DEE8F7]/70 dark:divide-slate-800">
            @forelse ($students as $student)
            <div class="p-4 flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="size-10 rounded-full {{ $student->isActive() ? 'bg-[#EAF1FB] text-[#0B2A8F] dark:bg-blue-950/60 dark:text-blue-300' : 'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-300' }} flex items-center justify-center font-bold text-sm shrink-0">
                            {{ mb_substr($student->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <div class="font-bold text-sm text-[#012383] dark:text-blue-400 truncate">{{ $student->name }}</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono truncate">{{ $student->phone ?? '—' }}</div>
                            <div class="text-[10px] text-slate-400 font-mono truncate">{{ $student->email }}</div>
                        </div>
                    </div>
                    @if($student->isActive())
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 shrink-0">
                            {{ __('نشط') }}
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800 shrink-0">
                            {{ __('معلق') }}
                        </span>
                    @endif
                </div>

                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[11px]">{{ __('الكورسات:') }}</span>
                        <span class="font-bold text-[#012383] dark:text-blue-400">{{ $student->courses->count() }} {{ __('كورس') }}</span>
                        @if($student->certificates->count() > 0)
                            <span class="block text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">🎓 {{ $student->certificates->count() }} {{ __('شهادة') }}</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">{{ __('الموقع:') }}</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ $student->city ?? '—' }} {{ $student->country ? "({$student->country})" : '' }}</span>
                    </div>
                    <div class="col-span-2 text-[10px] text-slate-400 pt-1 border-t border-slate-200/50 dark:border-slate-700/50 flex justify-between">
                        <span>{{ __('تاريخ التسجيل:') }} {{ $student->created_at?->format('d/m/Y') }}</span>
                        <span>{{ $student->created_at?->diffForHumans() }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.students.toggle', $student->id) }}" onsubmit="return confirm('{{ $student->isActive() ? __('هل أنت متأكد من تعليق حساب هذا الطالب؟') : __('هل أنت متأكد من إعادة تنشيط هذا الحساب؟') }}');" class="pt-1">
                    @csrf
                    @if($student->isActive())
                        <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800 transition-colors cursor-pointer text-center">
                            {{ __('تعليق الحساب') }}
                        </button>
                    @else
                        <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 transition-colors cursor-pointer text-center">
                            {{ __('إعادة التنشيط') }}
                        </button>
                    @endif
                </form>
            </div>
            @empty
            <div class="text-center py-12 text-slate-400 text-xs">
                {{ __('لا يوجد طلاب يطابقون خيارات البحث.') }}
            </div>
            @endforelse
        </div>

        @if($students->hasPages())
        <div class="p-4 bg-slate-50/70 dark:bg-slate-800/40 border-t border-[#DEE8F7] dark:border-slate-800">
            {{ $students->links() }}
        </div>
        @endif
    </div>

    <x-slot:modals>
    </x-slot:modals>
</x-admin-layout::admin>
