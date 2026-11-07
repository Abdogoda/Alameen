<x-admin-layout::admin
    :title="__('سجل نشاط الإدارة المركزية والعمليات (Audit Logs)')"
    :description="__('سجل رقابي شامل لجميع تحركات وزيارات وتعديلات المشرفين داخل لوحة التحكم لتتبع من قام بماذا')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-5 sm:gap-6 w-full min-w-0"
>
    <x-slot:actions>
        <div class="flex items-center gap-2 flex-wrap">
            <!-- Export CSV -->
            <a
                href="{{ route('admin.audit-logs.export', request()->query()) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 text-slate-700 dark:text-slate-200 hover:bg-[#EAF1FB] dark:hover:bg-slate-800 hover:text-[#012383] dark:hover:text-white transition-all shadow-xs"
                title="{{ __('تصدير السجلات المحددة كملف CSV') }}"
            >
                <svg class="size-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>{{ __('تصدير تقرير (CSV)') }}</span>
            </a>

            <!-- Prune Old Logs Modal Trigger (Super Admin Only) -->
            @if(auth('admin')->user()?->isSuperAdmin())
            <button
                type="button"
                x-data
                @click="$dispatch('open-prune-modal')"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-300 hover:bg-rose-100 transition-all shadow-xs cursor-pointer"
                title="{{ __('تنظيف السجلات القديمة') }}"
            >
                <svg class="size-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span>{{ __('تنظيف السجلات') }}</span>
            </button>
            @endif

            <!-- Refresh Button -->
            <a
                href="{{ route('admin.audit-logs.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold bg-[#012383] text-white hover:bg-[#0B2A8F] transition-all shadow-xs"
                title="{{ __('تحديث السجل') }}"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>{{ __('تحديث مباشر') }}</span>
            </a>
        </div>
    </x-slot:actions>

    <!-- Top KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- 1. Total Activity -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-4 sm:p-5 shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('إجمالي العمليات المسجلة') }}</span>
                <span class="text-2xl font-black text-[#012383] dark:text-white mt-1">{{ number_format($stats['total'] ?? 0) }}</span>
                <span class="text-[11px] text-slate-400 mt-0.5">{{ __('سجل متواصل دون انقطاع') }}</span>
            </div>
            <div class="size-12 rounded-2xl bg-[#EAF1FB] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 flex items-center justify-center text-[#012383] dark:text-blue-400 shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>

        <!-- 2. Today's Activity -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-4 sm:p-5 shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('نشاط اليوم') }}</span>
                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($stats['today'] ?? 0) }}</span>
                <span class="text-[11px] text-emerald-700 dark:text-emerald-400 mt-0.5">{{ __('تحركات وزيارات اليوم') }}</span>
            </div>
            <div class="size-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- 3. Modifications / Mutations Today -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-4 sm:p-5 shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('تعديلات وإجراءات اليوم') }}</span>
                <span class="text-2xl font-black text-[#0B2A8F] dark:text-blue-400 mt-1">{{ number_format($stats['todayMutations'] ?? 0) }}</span>
                <span class="text-[11px] text-slate-400 mt-0.5">{{ __('إضافة / تعديل / حذف / اعتماد') }}</span>
            </div>
            <div class="size-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 flex items-center justify-center text-[#0B2A8F] dark:text-blue-400 shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
        </div>

        <!-- 4. Active Admins -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-4 sm:p-5 shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('المشرفون النشطون (7 أيام)') }}</span>
                <span class="text-2xl font-black text-purple-600 dark:text-purple-400 mt-1">{{ number_format($stats['activeAdmins'] ?? 0) }}</span>
                <span class="text-[11px] text-slate-400 mt-0.5">{{ __('من أعضاء فريق العمل') }}</span>
            </div>
            <div class="size-12 rounded-2xl bg-purple-50 dark:bg-purple-950/50 border border-purple-200 dark:border-purple-800 flex items-center justify-center text-purple-600 dark:text-purple-400 shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filters & Live Search Toolbar -->
    <div
        x-data="{
            showAdvanced: false,
            setDateRange(preset) {
                const now = new Date();
                let from = new Date();
                let to = new Date();

                if (preset === 'today') {
                    // today
                } else if (preset === 'yesterday') {
                    from.setDate(now.getDate() - 1);
                    to.setDate(now.getDate() - 1);
                } else if (preset === '7days') {
                    from.setDate(now.getDate() - 7);
                } else if (preset === '30days') {
                    from.setDate(now.getDate() - 30);
                }

                const formatDate = (d) => d.toISOString().split('T')[0];
                document.getElementById('filter_date_from').value = formatDate(from);
                document.getElementById('filter_date_to').value = formatDate(to);
                document.getElementById('audit-filter-form').submit();
            }
        }"
        class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-4 sm:p-5 shadow-xs flex flex-col gap-4"
    >
        <form id="audit-filter-form" method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex flex-col gap-4">
            <!-- Row 1: Primary Search & Quick Dropdowns -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4 items-center">
                <!-- Search Keyword -->
                <div class="md:col-span-5 relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] }}"
                        placeholder="{{ __('ابحث بالمشرف، البريد، الإجراء، عنوان IP، الرابط...') }}"
                        class="w-full h-11 ps-10 pe-4 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 outline-none focus:border-[#0B2A8F] dark:focus:border-blue-500 bg-slate-50/60 dark:bg-slate-800/60 transition-all placeholder:text-slate-400"
                    />
                    <div class="absolute start-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- Admin Filter -->
                <div class="md:col-span-3">
                    <select
                        name="admin_id"
                        onchange="this.form.submit()"
                        class="w-full h-11 px-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] dark:focus:border-blue-500 bg-slate-50/60 dark:bg-slate-800/60 cursor-pointer"
                    >
                        <option value="all">{{ __('جميع المشرفين') }}</option>
                        @foreach($admins as $adm)
                            <option value="{{ $adm->id }}" {{ (string)$filters['admin_id'] === (string)$adm->id ? 'selected' : '' }}>
                                {{ $adm->name }} ({{ $adm->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Module Filter -->
                <div class="md:col-span-2">
                    <select
                        name="module"
                        onchange="this.form.submit()"
                        class="w-full h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] dark:focus:border-blue-500 bg-slate-50/60 dark:bg-slate-800/60 cursor-pointer"
                    >
                        <option value="all">{{ __('كافة الأقسام') }}</option>
                        @foreach($availableModules as $modKey => $modLabel)
                            <option value="{{ $modKey }}" {{ $filters['module'] === $modKey ? 'selected' : '' }}>
                                {{ $modLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter -->
                <div class="md:col-span-2 flex items-center gap-2">
                    <select
                        name="action"
                        onchange="this.form.submit()"
                        class="flex-1 h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] dark:focus:border-blue-500 bg-slate-50/60 dark:bg-slate-800/60 cursor-pointer"
                    >
                        <option value="all">{{ __('كافة الإجراءات') }}</option>
                        @foreach($availableActions as $actKey => $actLabel)
                            <option value="{{ $actKey }}" {{ $filters['action'] === $actKey ? 'selected' : '' }}>
                                {{ $actLabel }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="button"
                        @click="showAdvanced = !showAdvanced"
                        class="h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-[#012383] text-xs font-bold transition-all shrink-0 cursor-pointer"
                        :class="{ 'bg-[#EAF1FB] text-[#012383] border-[#DEE8F7]': showAdvanced }"
                        title="{{ __('فلاتر متقدمة (التاريخ، الحالة، الطريقة)') }}"
                    >
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    </button>
                </div>
            </div>

            <!-- Row 2: Advanced Filters (Collapsible) -->
            <div x-show="showAdvanced" x-collapse x-cloak class="pt-3 border-t border-[#DEE8F7] dark:border-slate-800 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-center">
                <!-- Status -->
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">{{ __('حالة العملية') }}</label>
                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/60 dark:bg-slate-800/60"
                    >
                        <option value="all">{{ __('الكل') }}</option>
                        <option value="success" {{ $filters['status'] === 'success' ? 'selected' : '' }}>{{ __('ناجح (Success)') }}</option>
                        <option value="warning" {{ $filters['status'] === 'warning' ? 'selected' : '' }}>{{ __('تحذير (Warning / 422)') }}</option>
                        <option value="failed" {{ $filters['status'] === 'failed' ? 'selected' : '' }}>{{ __('فشل (Failed / 500)') }}</option>
                    </select>
                </div>

                <!-- HTTP Method -->
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">{{ __('طريقة الطلب') }}</label>
                    <select
                        name="method"
                        onchange="this.form.submit()"
                        class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/60 dark:bg-slate-800/60"
                    >
                        <option value="all">{{ __('الكل') }}</option>
                        <option value="GET" {{ $filters['method'] === 'GET' ? 'selected' : '' }}>GET (تصفح / عرض)</option>
                        <option value="POST" {{ $filters['method'] === 'POST' ? 'selected' : '' }}>POST (إضافة / اعتماد)</option>
                        <option value="PUT" {{ $filters['method'] === 'PUT' ? 'selected' : '' }}>PUT (تعديل / تحديث)</option>
                        <option value="DELETE" {{ $filters['method'] === 'DELETE' ? 'selected' : '' }}>DELETE (حذف)</option>
                    </select>
                </div>

                <!-- Date From -->
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">{{ __('من تاريخ') }}</label>
                    <input
                        type="date"
                        id="filter_date_from"
                        name="date_from"
                        value="{{ $filters['date_from'] }}"
                        class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/60 dark:bg-slate-800/60"
                    />
                </div>

                <!-- Date To -->
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">{{ __('إلى تاريخ') }}</label>
                    <input
                        type="date"
                        id="filter_date_to"
                        name="date_to"
                        value="{{ $filters['date_to'] }}"
                        class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/60 dark:bg-slate-800/60"
                    />
                </div>

                <!-- Per Page -->
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">{{ __('عدد السجلات') }}</label>
                    <select
                        name="per_page"
                        onchange="this.form.submit()"
                        class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/60 dark:bg-slate-800/60"
                    >
                        <option value="15" {{ $filters['per_page'] === 15 ? 'selected' : '' }}>15 في الصفحة</option>
                        <option value="25" {{ $filters['per_page'] === 25 ? 'selected' : '' }}>25 في الصفحة</option>
                        <option value="50" {{ $filters['per_page'] === 50 ? 'selected' : '' }}>50 في الصفحة</option>
                        <option value="100" {{ $filters['per_page'] === 100 ? 'selected' : '' }}>100 في الصفحة</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="lg:col-span-2 flex items-end gap-2 pt-5">
                    <button
                        type="submit"
                        class="flex-1 h-10 px-4 rounded-xl text-xs font-bold bg-[#012383] text-white hover:bg-[#0B2A8F] transition-all cursor-pointer"
                    >
                        {{ __('تطبيق') }}
                    </button>
                    <a
                        href="{{ route('admin.audit-logs.index') }}"
                        class="h-10 px-3.5 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 flex items-center justify-center transition-all"
                        title="{{ __('إعادة تعيين الفلاتر') }}"
                    >
                        {{ __('إلغاء') }}
                    </a>
                </div>
            </div>

            <!-- Quick Date Presets Row -->
            <div class="flex items-center gap-2 flex-wrap pt-1 text-xs">
                <span class="text-slate-400 font-bold text-[11px]">{{ __('نطاق سريع:') }}</span>
                <button type="button" @click="setDateRange('today')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-[#EAF1FB] text-slate-600 dark:text-slate-300 hover:text-[#012383] font-semibold text-[11px] transition-all cursor-pointer">{{ __('اليوم') }}</button>
                <button type="button" @click="setDateRange('yesterday')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-[#EAF1FB] text-slate-600 dark:text-slate-300 hover:text-[#012383] font-semibold text-[11px] transition-all cursor-pointer">{{ __('أمس') }}</button>
                <button type="button" @click="setDateRange('7days')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-[#EAF1FB] text-slate-600 dark:text-slate-300 hover:text-[#012383] font-semibold text-[11px] transition-all cursor-pointer">{{ __('آخر 7 أيام') }}</button>
                <button type="button" @click="setDateRange('30days')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-[#EAF1FB] text-slate-600 dark:text-slate-300 hover:text-[#012383] font-semibold text-[11px] transition-all cursor-pointer">{{ __('آخر 30 يوماً') }}</button>
            </div>
        </form>
    </div>

    <!-- Logs Roster Table Container -->
    <div
        x-data="{
            selectedLog: null,
            loadingDetails: false,
            activeTab: 'payload',
            openModal(logId) {
                this.loadingDetails = true;
                this.selectedLog = null;
                this.activeTab = 'payload';
                fetch('{{ url('/admin/audit-logs') }}/' + logId)
                    .then(res => res.json())
                    .then(data => {
                        this.selectedLog = data;
                        this.loadingDetails = false;
                    })
                    .catch(() => {
                        this.loadingDetails = false;
                        alert('تعذر تحميل تفاصيل العملية.');
                    });
            }
        }"
        class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl overflow-hidden shadow-xs flex flex-col"
    >
        <!-- Desktop Table View -->
        <div class="overflow-x-auto min-w-0">
            <table class="w-full text-right border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-[#DEE8F7] dark:border-slate-800 text-[12px] font-bold text-slate-500">
                        <th class="py-4 px-4 w-48">{{ __('المشرف المسؤول') }}</th>
                        <th class="py-4 px-3 w-40">{{ __('الإجراء والوحدة') }}</th>
                        <th class="py-4 px-4">{{ __('بيان وتفاصيل العملية') }}</th>
                        <th class="py-4 px-3 w-44">{{ __('طريقة الطلب والمسار') }}</th>
                        <th class="py-4 px-3 w-36">{{ __('عنوان IP والجهاز') }}</th>
                        <th class="py-4 px-3 text-center w-24">{{ __('الحالة') }}</th>
                        <th class="py-4 px-3 text-center w-36">{{ __('التوقيت') }}</th>
                        <th class="py-4 px-4 text-center w-20">{{ __('فحص') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#DEE8F7]/70 dark:divide-slate-800 text-xs">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/70 transition-colors group/row">
                        <!-- Admin column -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="size-8 rounded-full bg-[#EAF1FB] text-[#012383] dark:bg-slate-800 dark:text-blue-300 flex items-center justify-center font-bold text-xs shrink-0 border border-[#DEE8F7] dark:border-slate-700">
                                    {{ mb_substr($log->admin_name ?? '?', 0, 1) }}
                                </div>
                                <div class="min-w-0 flex flex-col">
                                    <span class="font-bold text-slate-800 dark:text-slate-100 truncate text-xs">{{ $log->admin_name ?? __('زائر / غير مسجل') }}</span>
                                    <span class="text-[10px] text-slate-400 truncate">{{ $log->admin_email ?? '—' }}</span>
                                    @if($log->role_name)
                                        <span class="inline-block text-[9px] font-bold text-[#0B2A8F] dark:text-blue-300 bg-[#EAF1FB]/80 dark:bg-slate-800 px-1.5 py-0.2 rounded mt-0.5 max-w-fit truncate">
                                            {{ $log->role_name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Action & Module badges -->
                        <td class="py-3.5 px-3">
                            <div class="flex flex-col gap-1 items-start">
                                <span class="px-2 py-0.5 rounded-md text-[10.5px] font-bold border {{ $log->getActionBadgeClass() }}">
                                    {{ $log->getActionLabel() }}
                                </span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-semibold flex items-center gap-1">
                                    <span class="size-1.5 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                                    <span>{{ $log->getModuleLabel() }}</span>
                                </span>
                            </div>
                        </td>

                        <!-- Description & Subject -->
                        <td class="py-3.5 px-4">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-bold text-slate-800 dark:text-slate-100 text-xs leading-snug">
                                    {{ $log->description }}
                                </span>
                                @if($log->subject_name)
                                    <div class="flex items-center gap-1 text-[11px] text-slate-500 dark:text-slate-400">
                                        <span class="font-semibold text-slate-400">{{ __('العنصر:') }}</span>
                                        <span class="font-bold text-[#012383] dark:text-blue-300 truncate max-w-xs">{{ $log->subject_name }}</span>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <!-- Method & Route URL -->
                        <td class="py-3.5 px-3">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-black {{ $log->getMethodBadgeClass() }}">
                                        {{ $log->method }}
                                    </span>
                                    @if(isset($log->properties['response_status']))
                                        <span class="text-[10px] font-mono font-bold text-slate-400">
                                            {{ $log->properties['response_status'] }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10.5px] font-mono text-slate-500 dark:text-slate-400 truncate max-w-[170px]" title="{{ $log->url }}">
                                    {{ $log->route_name ?: parse_url($log->url, PHP_URL_PATH) }}
                                </span>
                            </div>
                        </td>

                        <!-- IP & Device -->
                        <td class="py-3.5 px-3">
                            <div class="flex flex-col">
                                <span class="font-mono text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $log->ip_address ?? '—' }}</span>
                                <span class="text-[10px] text-slate-400 truncate max-w-[130px]" title="{{ $log->user_agent }}">
                                    {{ $log->getBrowserName() }}{{ $log->getPlatformName() ? ' • ' . $log->getPlatformName() : '' }}
                                </span>
                            </div>
                        </td>

                        <!-- Status badge -->
                        <td class="py-3.5 px-3 text-center">
                            @if($log->status === 'success')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                    <span>{{ __('ناجح') }}</span>
                                </span>
                            @elseif($log->status === 'warning')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800">
                                    <span class="size-1.5 rounded-full bg-amber-500"></span>
                                    <span>{{ __('تحذير') }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800">
                                    <span class="size-1.5 rounded-full bg-rose-500"></span>
                                    <span>{{ __('فشل') }}</span>
                                </span>
                            @endif
                        </td>

                        <!-- Timestamp -->
                        <td class="py-3.5 px-3 text-center">
                            <div class="flex flex-col items-center">
                                <span class="font-semibold text-slate-700 dark:text-slate-200 text-xs">{{ $log->getTimeAgo() }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $log->created_at?->format('H:i:s Y-m-d') }}</span>
                                @if($log->getFormattedDuration())
                                    <span class="text-[9px] font-mono text-slate-400">{{ $log->getFormattedDuration() }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Inspect button -->
                        <td class="py-3.5 px-4 text-center">
                            <button
                                type="button"
                                @click="openModal({{ $log->id }})"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-[#012383] hover:bg-[#EAF1FB] dark:hover:bg-slate-800 dark:hover:text-blue-300 transition-colors cursor-pointer"
                                title="{{ __('فحص تفاصيل العملية') }}"
                            >
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 px-4 text-center">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="size-12 rounded-2xl bg-[#EAF1FB] dark:bg-slate-800 flex items-center justify-center text-[#012383] dark:text-blue-300">
                                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <span class="font-bold text-sm text-slate-700 dark:text-slate-200">{{ __('لا توجد سجلات تطابق شروط التصفية الحالية') }}</span>
                                <span class="text-xs text-slate-400">{{ __('جرب تعديل كلمات البحث أو اختيار فترة زمنية أخرى.') }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if($logs->hasPages())
        <div class="p-4 border-t border-[#DEE8F7] dark:border-slate-800 flex items-center justify-between">
            {{ $logs->links() }}
        </div>
        @endif

        <!-- Details Inspection Modal -->
        <div
            x-show="selectedLog !== null || loadingDetails"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            @keydown.window.escape="selectedLog = null; loadingDetails = false"
        >
            <div
                @click.away="selectedLog = null; loadingDetails = false"
                class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl w-full max-w-3xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200"
            >
                <!-- Modal Header -->
                <div class="p-4 sm:p-5 border-b border-[#DEE8F7] dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/40">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-300 flex items-center justify-center font-bold text-sm">
                            #<span x-text="selectedLog?.id"></span>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-800 dark:text-white" x-text="selectedLog?.description"></h3>
                            <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                                <span x-text="selectedLog?.admin_name"></span>
                                <span>•</span>
                                <span x-text="selectedLog?.created_at"></span>
                                <span>•</span>
                                <span x-text="selectedLog?.ip_address"></span>
                            </div>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="selectedLog = null; loadingDetails = false"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800"
                    >
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 overflow-y-auto p-4 sm:p-5 flex flex-col gap-4">
                    <template x-if="loadingDetails">
                        <div class="py-12 flex flex-col items-center justify-center gap-3">
                            <svg class="size-8 text-[#012383] animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs font-bold text-slate-500">{{ __('جاري تحميل التفاصيل...') }}</span>
                        </div>
                    </template>

                    <template x-if="!loadingDetails && selectedLog">
                        <div class="flex flex-col gap-4">
                            <!-- Metadata Capsules -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                <div class="bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded-xl border border-[#DEE8F7] dark:border-slate-800">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">{{ __('القسم / الوحدة') }}</span>
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200" x-text="selectedLog.module_label"></span>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded-xl border border-[#DEE8F7] dark:border-slate-800">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">{{ __('نوع الإجراء') }}</span>
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200" x-text="selectedLog.action_label"></span>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded-xl border border-[#DEE8F7] dark:border-slate-800">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">{{ __('طريقة الطلب') }}</span>
                                    <span class="text-xs font-bold font-mono text-[#012383] dark:text-blue-400" x-text="selectedLog.method"></span>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded-xl border border-[#DEE8F7] dark:border-slate-800">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">{{ __('كود الاستجابة') }}</span>
                                    <span class="text-xs font-bold font-mono text-emerald-600 dark:text-emerald-400" x-text="selectedLog.properties?.response_status || '200 OK'"></span>
                                </div>
                            </div>

                            <!-- Tabs Switcher -->
                            <div class="flex items-center gap-2 border-b border-[#DEE8F7] dark:border-slate-800 pb-2">
                                <button
                                    type="button"
                                    @click="activeTab = 'payload'"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all"
                                    :class="activeTab === 'payload' ? 'bg-[#012383] text-white' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800'"
                                >
                                    {{ __('مدخلات الطلب (Payload)') }}
                                </button>
                                <button
                                    type="button"
                                    @click="activeTab = 'changes'"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all"
                                    :class="activeTab === 'changes' ? 'bg-[#012383] text-white' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800'"
                                >
                                    {{ __('التغييرات في البيانات') }}
                                </button>
                                <button
                                    type="button"
                                    @click="activeTab = 'client'"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all"
                                    :class="activeTab === 'client' ? 'bg-[#012383] text-white' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800'"
                                >
                                    {{ __('معلومات الاتصال والعميل') }}
                                </button>
                            </div>

                            <!-- Tab 1: Payload -->
                            <div x-show="activeTab === 'payload'" class="flex flex-col gap-3">
                                <div class="bg-slate-900 text-slate-100 p-4 rounded-2xl font-mono text-xs overflow-x-auto max-h-80 ltr text-left">
                                    <pre class="whitespace-pre-wrap" x-text="JSON.stringify(selectedLog.properties?.request_params || selectedLog.properties?.query_params || {}, null, 2)"></pre>
                                </div>
                                <span class="text-[11px] text-slate-400">{{ __('تم تعتيم وحجب كلمات المرور والمفاتيح السرية تلقائياً لأمان النظام.') }}</span>
                            </div>

                            <!-- Tab 2: Model Changes -->
                            <div x-show="activeTab === 'changes'" class="flex flex-col gap-3">
                                <template x-if="selectedLog.properties?.model_changes && selectedLog.properties.model_changes.length > 0">
                                    <div class="flex flex-col gap-3">
                                        <template x-for="(change, idx) in selectedLog.properties.model_changes" :key="idx">
                                            <div class="p-3.5 rounded-2xl border border-[#DEE8F7] dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 flex flex-col gap-2">
                                                <div class="flex items-center justify-between text-xs font-bold text-slate-800 dark:text-slate-200">
                                                    <span x-text="change.model + ' #' + change.id + ' (' + change.name + ')'"></span>
                                                    <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold" :class="change.type === 'created' ? 'bg-emerald-100 text-emerald-800' : (change.type === 'deleted' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800')" x-text="change.type"></span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2 text-xs font-mono ltr text-left">
                                                    <div class="p-2 rounded bg-rose-50/50 dark:bg-rose-950/30 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-900">
                                                        <span class="block font-bold mb-1 text-[10px] uppercase text-rose-600">{{ __('قبل التعديل (Old)') }}</span>
                                                        <pre class="whitespace-pre-wrap text-[11px]" x-text="JSON.stringify(change.old, null, 2)"></pre>
                                                    </div>
                                                    <div class="p-2 rounded bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900">
                                                        <span class="block font-bold mb-1 text-[10px] uppercase text-emerald-600">{{ __('بعد التعديل (New)') }}</span>
                                                        <pre class="whitespace-pre-wrap text-[11px]" x-text="JSON.stringify(change.new, null, 2)"></pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!selectedLog.properties?.model_changes || selectedLog.properties.model_changes.length === 0">
                                    <div class="p-8 text-center text-xs text-slate-400">
                                        {{ __('لا توجد تعديلات مباشرة على نماذج قواعد البيانات مسجلة لهذا الطلب.') }}
                                    </div>
                                </template>
                            </div>

                            <!-- Tab 3: Client & Connection Details -->
                            <div x-show="activeTab === 'client'" class="flex flex-col gap-2.5 text-xs">
                                <div class="p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 bg-slate-50 dark:bg-slate-800 flex justify-between items-center">
                                    <span class="font-bold text-slate-500">{{ __('الرابط الكامل (Full URL):') }}</span>
                                    <span class="font-mono text-slate-700 dark:text-slate-300 ltr truncate max-w-sm" x-text="selectedLog.url"></span>
                                </div>
                                <div class="p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 bg-slate-50 dark:bg-slate-800 flex justify-between items-center">
                                    <span class="font-bold text-slate-500">{{ __('اسم المسار (Route Name):') }}</span>
                                    <span class="font-mono text-slate-700 dark:text-slate-300" x-text="selectedLog.route_name || '—'"></span>
                                </div>
                                <div class="p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 bg-slate-50 dark:bg-slate-800 flex justify-between items-center">
                                    <span class="font-bold text-slate-500">{{ __('عنوان IP:') }}</span>
                                    <span class="font-mono text-slate-700 dark:text-slate-300" x-text="selectedLog.ip_address || '—'"></span>
                                </div>
                                <div class="p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 bg-slate-50 dark:bg-slate-800 flex justify-between items-center">
                                    <span class="font-bold text-slate-500">{{ __('المتصفح والنظام:') }}</span>
                                    <span class="text-slate-700 dark:text-slate-300" x-text="(selectedLog.browser || '') + ' • ' + (selectedLog.platform || '')"></span>
                                </div>
                                <div class="p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 bg-slate-50 dark:bg-slate-800 flex flex-col gap-1">
                                    <span class="font-bold text-slate-500">{{ __('سلسلة تعريف العميل (User-Agent):') }}</span>
                                    <span class="font-mono text-[10.5px] text-slate-600 dark:text-slate-300 ltr text-left break-all" x-text="selectedLog.user_agent"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="p-4 border-t border-[#DEE8F7] dark:border-slate-800 flex justify-end">
                    <button
                        type="button"
                        @click="selectedLog = null; loadingDetails = false"
                        class="px-5 py-2 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"
                    >
                        {{ __('إغلاق') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Prune Logs Modal (Super Admin) -->
        @if(auth('admin')->user()?->isSuperAdmin())
        <div
            x-data="{ open: false }"
            @open-prune-modal.window="open = true"
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
        >
            <div
                @click.away="open = false"
                class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl w-full max-w-md p-6 flex flex-col gap-4 shadow-2xl animate-in fade-in zoom-in-95 duration-200"
            >
                <div class="flex items-center gap-3 text-rose-600">
                    <div class="size-10 rounded-2xl bg-rose-50 dark:bg-rose-950/60 flex items-center justify-center">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ __('تنظيف وأرشفة السجلات') }}</h3>
                        <span class="text-xs text-slate-400">{{ __('صلاحية خاصة بمالك المنصة فقط') }}</span>
                    </div>
                </div>

                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ __('سيتم حذف سجلات النشاط الأقدم من الفترة المحددة نهائياً لتوفير مساحة التخزين. سيتم تسجيل عملية التنظيف ذاتها في النظام كإجراء أمان.') }}
                </p>

                <form method="POST" action="{{ route('admin.audit-logs.clear') }}" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">{{ __('اختر فترة الاحتفاظ بالسجلات:') }}</label>
                        <select
                            name="retention_days"
                            class="w-full h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 outline-none focus:border-rose-500 bg-slate-50 dark:bg-slate-800"
                        >
                            <option value="30">{{ __('حذف الأقدم من 30 يوماً') }}</option>
                            <option value="60" selected>{{ __('حذف الأقدم من 60 يوماً (موصى به)') }}</option>
                            <option value="90">{{ __('حذف الأقدم من 90 يوماً') }}</option>
                            <option value="180">{{ __('حذف الأقدم من 180 يوماً (6 أشهر)') }}</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button
                            type="button"
                            @click="open = false"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 transition-colors"
                        >
                            {{ __('إلغاء') }}
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold bg-rose-600 text-white hover:bg-rose-700 transition-colors shadow-xs"
                        >
                            {{ __('تأكيد تنظيف السجلات') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</x-admin-layout::admin>

