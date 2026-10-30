<x-admin-layout::admin :title="__('لوحة الإحصائيات المركزية')">

    <div class="flex flex-col gap-8 w-full text-start">
        
        <!-- Banner: Platform Overview & Focus -->
        <div class="bg-gradient-to-l from-[#012383] via-[#0B2A8F] to-[#152D4D] rounded-3xl p-6 sm:p-8 text-white flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 relative overflow-hidden shadow-card">
            <div class="flex-1 flex flex-col gap-2 z-10 text-start">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-xs font-bold text-white border border-white/20">
                        {{ __('كورس التخاطب الشامل • الإصدار النشط') }}
                    </span>
                    <span class="size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs text-white/80 font-medium">{{ __('مستويات ديناميكية') }}</span>
                </div>
                <h2 class="font-['Baloo_Bhaijaan_2'] font-extrabold text-2xl sm:text-3xl text-white mt-1">
                    {{ __('دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة') }}
                </h2>
                <p class="text-xs sm:text-sm text-white/80 max-w-2xl leading-relaxed">
                    {{ __('لوحة الإدارة المركزية لإدارة البرنامج السريري، مراجعة إيصالات التحويل البنكي، واعتماد شهادات الطلاب فور إتمام معيار الـ 95%.') }}
                </p>
            </div>

            <!-- Price & Fast Controls in Banner -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 z-10 w-full lg:w-auto shrink-0">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-center">
                    <p class="text-[11px] text-white/70">{{ __('السعر المعتمد حالياً') }}</p>
                    <p class="text-2xl font-black text-white mt-0.5">{{ __('١,٢٠٠ ج.م') }}</p>
                    <p class="text-[10px] text-white/50 line-through">{{ __('١,٨٠٠ ج.م') }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('admin.notifications.index') }}" class="h-11 px-5 rounded-xl bg-white hover:bg-white/95 text-[#012383] font-bold text-xs shadow-md transition-all cursor-pointer flex items-center justify-center gap-2">
                        <svg class="size-4 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span>{{ __('إرسال إشعار جماعي للطلاب') }}</span>
                    </a>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('log-viewer.index') }}" target="_blank" class="flex-1 h-11 px-4 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs border border-white/20 transition-all flex items-center justify-center gap-2">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ __('سجلات النظام (Logs)') }}</span>
                        </a>
                        <a href="{{ route('dashboard') }}" target="_blank" class="h-11 px-4 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs border border-white/20 transition-all flex items-center justify-center gap-1.5" title="{{ __('معاينة بوابة الطالب') }}">
                            <span>{{ __('بوابة الطالب') }}</span>
                            <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- 4 CORE KPIS CARDS                              -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            
            <!-- KPI 1: Total Completed Hours Across Platform -->
            <div class="bg-white border border-[#DEE8F7] rounded-2xl p-5 shadow-xs hover:shadow-card transition-all flex flex-col justify-between gap-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500">{{ __('إجمالي ساعات التعلّم') }}</span>
                    <div class="size-10 rounded-xl bg-blue-50 text-[#0B2A8F] flex items-center justify-center">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-[#012383] font-['Cairo'] tracking-tight">٤,٨٥٠</h3>
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5 font-medium">
                        <span class="text-emerald-600 font-bold flex items-center gap-0.5">
                            <svg class="size-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/></svg>
                            +١٤٪
                        </span>
                        {{ __('ساعة تدريبية معتمدة تم إكمالها') }}
                    </p>
                </div>
            </div>

            <!-- KPI 2: Total Registered Students -->
            <div class="bg-white border border-[#DEE8F7] rounded-2xl p-5 shadow-xs hover:shadow-card transition-all flex flex-col justify-between gap-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500">{{ __('إجمالي الطلاب المسجلين') }}</span>
                    <div class="size-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-[#012383] font-['Cairo'] tracking-tight">{{ number_format(\App\Models\User::count()) }}</h3>
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5 font-medium">
                        <span class="text-emerald-600 font-bold flex items-center gap-0.5">
                            <svg class="size-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/></svg>
                            +٨٪
                        </span>
                        {{ __('طالب متفاعل في مسار التخاطب') }}
                    </p>
                </div>
            </div>

            <!-- KPI 3: Pending Bank Receipts -->
            <div class="bg-white border border-[#DEE8F7] rounded-2xl p-5 shadow-xs hover:shadow-card transition-all flex flex-col justify-between gap-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500">{{ __('إيصالات الدفع المعلقة') }}</span>
                    <div class="size-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-amber-600 font-['Cairo'] tracking-tight">{{ $pendingReceiptsCount ?? 0 }}</h3>
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5 font-medium">
                        <span class="size-2 rounded-full bg-amber-500 animate-pulse"></span>
                        {{ __('بانتظار مراجعة الإدارة وتفعيل الوصول') }}
                    </p>
                </div>
            </div>

            <!-- KPI 4: Pending Tasks To Grade -->
            <div class="bg-white border border-[#DEE8F7] rounded-2xl p-5 shadow-xs hover:shadow-card transition-all flex flex-col justify-between gap-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500">{{ __('مهام بانتظار التصحيح') }}</span>
                    <div class="size-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-[#012383] font-['Cairo'] tracking-tight">{{ $pendingTasksCount ?? 0 }}</h3>
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5 font-medium">
                        <span class="text-blue-600 font-bold">{{ $pendingTasksCount ?? 0 }}</span>
                        {{ __('تسجيل صوتي وتطبيق سريري') }}
                    </p>
                </div>
            </div>

        </div>

        <!-- ============================================== -->
        <!-- ACTIVITY & RECENT ACTIONS                      -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Monthly Trends Bar Mock -->
            <div class="lg:col-span-2 bg-white border border-[#DEE8F7] rounded-2xl p-6 shadow-xs flex flex-col justify-between gap-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-base text-[#012383]">{{ __('معدل النشاط والانضمام الأسبوعي') }}</h4>
                        <p class="text-xs text-slate-400 mt-0.5">{{ __('متابعة إقبال المتدربين على دورة التخاطب الشاملة') }}</p>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-[#EAF1FB] text-[#0B2A8F]">آخر ٦ أشهر</span>
                </div>

                <div class="h-48 flex items-end justify-between gap-3 pt-6 border-b border-[#DEE8F7] pb-2">
                    @php
                        $bars = [
                            ['month' => 'مايو', 'height' => 'h-[40%]', 'val' => '١٢٠'],
                            ['month' => 'يونيو', 'height' => 'h-[55%]', 'val' => '١٨٠'],
                            ['month' => 'يوليو', 'height' => 'h-[75%]', 'val' => '٢٦٠'],
                            ['month' => 'أغسطس', 'height' => 'h-[60%]', 'val' => '٢١٠'],
                            ['month' => 'سبتمبر', 'height' => 'h-[85%]', 'val' => '٣١٠'],
                            ['month' => 'أكتوبر', 'height' => 'h-[95%]', 'val' => '٣٦٠'],
                        ];
                    @endphp

                    @foreach ($bars as $bar)
                        <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                            <span class="text-[10px] font-bold text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity">{{ $bar['val'] }}</span>
                            <div class="w-full max-w-[44px] {{ $bar['height'] }} bg-gradient-to-t from-[#012383] to-[#0B2A8F] rounded-t-xl group-hover:from-[#0B2A8F] group-hover:to-blue-400 transition-all"></div>
                            <span class="text-xs font-bold text-slate-500">{{ $bar['month'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Management Card -->
            <div class="bg-white border border-[#DEE8F7] rounded-2xl p-6 shadow-xs flex flex-col gap-5">
                <div>
                    <h4 class="font-bold text-base text-[#012383]">{{ __('إجراءات سريعة للإدارة') }}</h4>
                    <p class="text-xs text-slate-400 mt-0.5">{{ __('روابط سريعة لأهم العمليات اليومية') }}</p>
                </div>

                <div class="flex flex-col gap-2.5">
                    <a href="{{ route('admin.notifications.index') }}" class="flex items-center justify-between p-3 rounded-xl border border-[#DEE8F7] hover:border-[#0B2A8F] hover:bg-[#EAF1FB]/40 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="size-9 rounded-lg bg-blue-50 text-[#0B2A8F] flex items-center justify-center">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-xs text-[#012383]">{{ __('مركز الإشعارات') }}</span>
                                <span class="text-[10px] text-slate-400">{{ __('إرسال تنبيه للطلاب أو فئة محددة') }}</span>
                            </div>
                        </div>
                        <span class="text-xs text-[#0B2A8F] font-bold">←</span>
                    </a>

                    <a href="#receipts" class="flex items-center justify-between p-3 rounded-xl border border-[#DEE8F7] hover:border-amber-400 hover:bg-amber-50/40 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="size-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-xs text-[#012383]">{{ __('مراجعة الإيصالات') }}</span>
                                <span class="text-[10px] text-slate-400">{{ __('١٢ إيصال قيد التدقيق') }}</span>
                            </div>
                        </div>
                        <span class="text-xs text-amber-600 font-bold">←</span>
                    </a>

                    <a href="#tasks" class="flex items-center justify-between p-3 rounded-xl border border-[#DEE8F7] hover:border-indigo-400 hover:bg-indigo-50/40 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="size-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-xs text-[#012383]">{{ __('تصحيح التكليفات') }}</span>
                                <span class="text-[10px] text-slate-400">{{ __('تقييم تسجيلات الطلاب السريرية') }}</span>
                            </div>
                        </div>
                        <span class="text-xs text-indigo-600 font-bold">←</span>
                    </a>

                    <a href="{{ route('log-viewer.index') }}" target="_blank" class="flex items-center justify-between p-3 rounded-xl border border-[#DEE8F7] hover:border-amber-400 hover:bg-amber-50/40 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="size-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-xs text-[#012383]">{{ __('سجلات النظام (Log Viewer)') }}</span>
                                <span class="text-[10px] text-slate-400">{{ __('فحص وتتبع أخطاء النظام وسجلات التشغيل') }}</span>
                            </div>
                        </div>
                        <span class="text-xs text-amber-600 font-bold">↗</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</x-admin-layout::admin>
