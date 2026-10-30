<x-admin-layout::admin
    :title="__('مركز تصحيح المهام والتقييم السريري')"
    :description="__('مراجعة حلول الطلاب للمهام السريرية الملحقة بالفيديوهات ورصد الدرجات وإرسال الملاحظات التوجيهية')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-5 sm:gap-6 max-w-[1500px] w-full mx-auto min-w-0"
>
    <x-slot:actions>
        <span class="text-xs font-bold text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800 px-3 py-1.5 rounded-xl flex items-center gap-1.5 self-start sm:self-auto">
            <span class="size-2 rounded-full bg-purple-500 animate-ping"></span>
            {{ __(':count مهمة بانتظار تصحيحك', ['count' => $counts['pending'] ?? 0]) }}
        </span>
    </x-slot:actions>

    <!-- Quick Status Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400">{{ __('إجمالي المهام المرفوعة') }}</span>
                <span class="text-2xl font-black text-[#012383] dark:text-blue-400 block mt-0.5">{{ $counts['all'] ?? 0 }} {{ __('مهمة') }}</span>
            </div>
            <div class="size-10 rounded-xl bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400 flex items-center justify-center font-bold shrink-0">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400">{{ __('بانتظار رصد الدرجة') }}</span>
                <span class="text-2xl font-black text-amber-600 block mt-0.5">{{ $counts['pending'] ?? 0 }} {{ __('مهمة') }}</span>
            </div>
            <div class="size-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 flex items-center justify-center font-bold shrink-0">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400">{{ __('مُقيّمة ومُعتمدة') }}</span>
                <span class="text-2xl font-black text-emerald-600 block mt-0.5">{{ $counts['graded'] ?? 0 }} {{ __('مهمة') }}</span>
            </div>
            <div class="size-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 flex items-center justify-center font-bold shrink-0">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Filters Toolbar -->
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-3 sm:p-4 shadow-xs">
        <div class="flex items-center gap-2 overflow-x-auto scrollbar-none whitespace-nowrap pb-1 sm:pb-0">
            <a href="{{ route('admin.tasks.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $status === 'all' ? 'bg-[#012383] text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#EAF1FB]' }}">
                {{ __('جميع المهام') }} ({{ $counts['all'] ?? 0 }})
            </a>
            <a href="{{ route('admin.tasks.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $status === 'pending' ? 'bg-[#012383] text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#EAF1FB]' }}">
                {{ __('بانتظار التصحيح') }} ({{ $counts['pending'] ?? 0 }})
            </a>
            <a href="{{ route('admin.tasks.index', ['status' => 'graded']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $status === 'graded' ? 'bg-[#012383] text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#EAF1FB]' }}">
                {{ __('مُقيّمة ومُعتمدة') }} ({{ $counts['graded'] ?? 0 }})
            </a>
        </div>
    </div>

    <!-- Tasks Submissions Container -->
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl overflow-hidden shadow-xs">
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto min-w-0">
            <table class="w-full text-right border-collapse min-w-[820px]">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-[#DEE8F7] dark:border-slate-800 text-[12px] font-bold text-slate-500">
                        <th class="py-4 px-5">{{ __('الطالب') }}</th>
                        <th class="py-4 px-4">{{ __('المهمة السريرية / الكورس') }}</th>
                        <th class="py-4 px-4">{{ __('تاريخ التسليم') }}</th>
                        <th class="py-4 px-4">{{ __('التسجيل الصوتي والملاحظات') }}</th>
                        <th class="py-4 px-4 text-center">{{ __('الدرجة') }}</th>
                        <th class="py-4 px-4 text-center">{{ __('الحالة') }}</th>
                        <th class="py-4 px-5 text-center">{{ __('الإجراء') }}</th>
                    </tr>
                </thead>
                <tbody id="tasks-table-body" class="divide-y divide-[#DEE8F7]/70 dark:divide-slate-800 text-xs">
                    @forelse ($submissions as $sub)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-4 px-5">
                            <div class="font-bold text-sm text-[#012383] dark:text-blue-400">{{ $sub->user?->name ?? '—' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $sub->user?->email }}</div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-bold text-slate-800 dark:text-slate-200">{{ $sub->task?->title ?? __('مهمة تقييم سريري') }}</div>
                            <div class="text-[11px] text-slate-400">{{ $sub->task?->course?->title ?? __('الكورس التدريبي') }}</div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $sub->created_at?->format('d/m/Y h:i A') }}</span>
                            <div class="text-[10px] text-slate-400">{{ $sub->created_at?->diffForHumans() }}</div>
                        </td>
                        <td class="py-4 px-4">
                            @if($sub->audio_recording_path)
                                <div class="flex flex-col gap-1.5">
                                    <audio controls class="h-8 max-w-[220px]">
                                        <source src="{{ asset('storage/' . $sub->audio_recording_path) }}">
                                        {{ __('متصفحك لا يدعم مشغل الصوت') }}
                                    </audio>
                                    @if($sub->student_notes)
                                        <p class="text-[11px] text-slate-500 max-w-[220px] truncate" title="{{ $sub->student_notes }}">
                                            💬 {{ $sub->student_notes }}
                                        </p>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-400">{{ __('لا يوجد تسجيل مرفق') }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($sub->score !== null)
                                <span class="font-bold text-sm {{ $sub->score >= 80 ? 'text-emerald-600' : ($sub->score >= 60 ? 'text-blue-600' : 'text-amber-600') }}">
                                    {{ $sub->score }} / 100
                                </span>
                            @else
                                <span class="text-slate-400 font-medium">—</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($sub->status === 'graded')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                    {{ __('تم التقييم') }}
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800 animate-pulse">
                                    {{ __('بانتظار التقييم') }}
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center">
                            <button
                                type="button"
                                onclick="openGradeModal('{{ $sub->id }}', '{{ addslashes($sub->user?->name ?? '') }}', '{{ addslashes($sub->task?->title ?? '') }}', '{{ $sub->score ?? '' }}', '{{ addslashes($sub->clinical_feedback ?? '') }}')"
                                class="px-3 py-1.5 rounded-xl bg-[#012383] text-white hover:bg-[#0B2A8F] font-bold text-xs transition-colors shadow-2xs cursor-pointer"
                            >
                                {{ $sub->status === 'graded' ? __('تعديل الدرجة') : __('رصد الدرجة') }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-400">
                            {{ __('لا توجد تسليمات مهام تطابق هذا التصنيف.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-[#DEE8F7]/70 dark:divide-slate-800">
            @forelse ($submissions as $sub)
            <div class="p-4 flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <div class="font-bold text-sm text-[#012383] dark:text-blue-400 truncate">{{ $sub->user?->name ?? '—' }}</div>
                        <div class="text-[11px] text-slate-400 font-mono truncate">{{ $sub->user?->email }}</div>
                    </div>
                    @if($sub->status === 'graded')
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 shrink-0">
                            {{ __('تم التقييم') }}
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800 shrink-0">
                            {{ __('بانتظار التقييم') }}
                        </span>
                    @endif
                </div>

                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex flex-col gap-1">
                    <span class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ $sub->task?->title ?? __('مهمة تقييم سريري') }}</span>
                    <span class="text-[11px] text-slate-400">{{ $sub->task?->course?->title ?? __('الكورس التدريبي') }}</span>
                    <div class="text-[10px] text-slate-400 mt-1 flex items-center justify-between">
                        <span>{{ $sub->created_at?->format('d/m/Y h:i A') }}</span>
                        <span>{{ $sub->created_at?->diffForHumans() }}</span>
                    </div>
                </div>

                @if($sub->audio_recording_path)
                    <div class="flex flex-col gap-1.5">
                        <audio controls class="w-full h-9">
                            <source src="{{ asset('storage/' . $sub->audio_recording_path) }}">
                            {{ __('متصفحك لا يدعم مشغل الصوت') }}
                        </audio>
                        @if($sub->student_notes)
                            <p class="text-[11px] text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/40 p-2 rounded-lg border border-slate-100 dark:border-slate-800">
                                <span class="font-bold text-slate-500">💬 {{ __('ملاحظة الطالب:') }}</span> {{ $sub->student_notes }}
                            </p>
                        @endif
                    </div>
                @else
                    <div class="text-xs text-slate-400 italic bg-slate-50 dark:bg-slate-800/30 p-2 rounded-lg">
                        {{ __('لا يوجد تسجيل صوتي مرفق') }}
                    </div>
                @endif

                <div class="flex items-center justify-between pt-1">
                    <div class="text-xs">
                        <span class="text-slate-400">{{ __('الدرجة:') }}</span>
                        @if($sub->score !== null)
                            <span class="font-bold text-sm mx-1 {{ $sub->score >= 80 ? 'text-emerald-600' : ($sub->score >= 60 ? 'text-blue-600' : 'text-amber-600') }}">
                                {{ $sub->score }} / 100
                            </span>
                        @else
                            <span class="text-slate-400 font-medium mx-1">—</span>
                        @endif
                    </div>
                    <button
                        type="button"
                        onclick="openGradeModal('{{ $sub->id }}', '{{ addslashes($sub->user?->name ?? '') }}', '{{ addslashes($sub->task?->title ?? '') }}', '{{ $sub->score ?? '' }}', '{{ addslashes($sub->clinical_feedback ?? '') }}')"
                        class="px-4 py-2 rounded-xl bg-[#012383] text-white hover:bg-[#0B2A8F] font-bold text-xs transition-colors shadow-2xs cursor-pointer"
                    >
                        {{ $sub->status === 'graded' ? __('تعديل الدرجة') : __('رصد الدرجة') }}
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-slate-400 text-xs">
                {{ __('لا توجد تسليمات مهام تطابق هذا التصنيف.') }}
            </div>
            @endforelse
        </div>

        @if($submissions->hasPages())
        <div class="p-4 bg-slate-50/70 dark:bg-slate-800/40 border-t border-[#DEE8F7] dark:border-slate-800">
            {{ $submissions->links() }}
        </div>
        @endif
    </div>

    <x-slot:modals>
        <!-- Modal: Grade Voice Submission -->
        <div id="modal-grade-task" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/50 backdrop-blur-xs hidden">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full max-h-[90dvh] overflow-y-auto p-5 sm:p-7 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800">
                <div class="flex items-center gap-2.5 text-[#012383] dark:text-blue-400">
                    <div class="size-9 rounded-xl bg-blue-50 dark:bg-slate-800 flex items-center justify-center shrink-0">
                        <svg class="size-5 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-blue-400 truncate">{{ __('رصد تقييم المهمة السريرية') }}</h3>
                        <p id="grade-task-subtitle" class="text-xs text-slate-500 truncate"></p>
                    </div>
                </div>

                <form id="grade-task-form" method="POST" action="" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            {{ __('الدرجة المستحقة (من 0 إلى 100)') }} *
                        </label>
                        <input type="number" name="score" id="grade-score-input" min="0" max="100" required placeholder="85" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-sm font-bold text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            {{ __('الملاحظات والتوجيهات السريرية للطالب') }}
                        </label>
                        <textarea name="clinical_feedback" id="grade-feedback-input" rows="4" placeholder="{{ __('اكتب ملاحظاتك التوجيهية وتصويب مخارج الحروف أو نبرة الصوت...') }}" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50"></textarea>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 pt-2 border-t border-[#DEE8F7] dark:border-slate-800">
                        <button type="button" onclick="closeGradeModal()" class="w-full sm:w-auto px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-5 h-10 rounded-xl bg-[#012383] text-white font-bold text-xs shadow-sm hover:bg-[#0B2A8F] cursor-pointer">
                            {{ __('حفظ واعتماد التقييم') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openGradeModal(id, studentName, taskTitle, score, feedback) {
                const modal = document.getElementById('modal-grade-task');
                const form = document.getElementById('grade-task-form');
                const subtitle = document.getElementById('grade-task-subtitle');
                const scoreInput = document.getElementById('grade-score-input');
                const feedbackInput = document.getElementById('grade-feedback-input');

                form.action = `/admin/tasks/${id}/grade`;
                subtitle.textContent = `${studentName} — ${taskTitle}`;
                scoreInput.value = score || '';
                feedbackInput.value = feedback || '';

                modal.classList.remove('hidden');
            }

            function closeGradeModal() {
                document.getElementById('modal-grade-task').classList.add('hidden');
            }
        </script>
    </x-slot:modals>
</x-admin-layout::admin>
