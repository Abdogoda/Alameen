<x-admin-layout::admin :title="__('لوحة الإحصائيات المركزية')">

    @php
        $totalStudents = \App\Models\User::count();
        $pendingReceipts = $pendingReceiptsCount ?? (\Illuminate\Support\Facades\Schema::hasTable('payment_receipts') ? \App\Models\PaymentReceipt::where('status', 'pending')->count() : 0);
        $pendingTasks = $pendingTasksCount ?? (\Illuminate\Support\Facades\Schema::hasTable('task_submissions') ? \App\Models\TaskSubmission::where('status', 'pending')->count() : 0);
        $totalVideos = \App\Models\Video::count();
        $activeCourse = \App\Models\Course::withCount('videos')->first();
        $certificatesCount = \Illuminate\Support\Facades\Schema::hasTable('certificates') ? \App\Models\Certificate::count() : 0;
        $adminName = auth('admin')->user()?->name ?? __('المسؤول العام');
    @endphp

    <div class="flex flex-col gap-6 sm:gap-8 w-full text-start min-w-0">
        
        <!-- ============================================== -->
        <!-- 1. EXECUTIVE WELCOME & PLATFORM STATUS         -->
        <!-- ============================================== -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-5 relative overflow-hidden">
            <!-- Decorative subtle background accents -->
            <div class="absolute -top-20 -end-20 size-56 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -start-20 size-56 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex items-start sm:items-center gap-4 z-10 min-w-0">
                <div class="size-12 sm:size-14 rounded-2xl bg-gradient-to-br from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center shrink-0 shadow-md">
                    <svg class="size-6 sm:size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div class="space-y-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap text-xs">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/40">
                            <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ __('نظام المنصة نشط') }}
                        </span>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ __('بث Bunny Stream مشفر') }}</span>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ __('معيار الشهادة ٩٥٪') }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-[#012383] dark:text-white font-['Cairo'] tracking-tight">
                        {{ __('مرحباً بك، :name', ['name' => $adminName]) }} 👋
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-2xl leading-relaxed">
                        {{ __('لوحة التحكم المركزية لإدارة البرنامج السريري، مراجعة إيصالات التحويل البنكي، واعتماد التكليفات والشهادات.') }}
                    </p>
                </div>
            </div>

            <!-- Quick Action Shortcuts -->
            <div class="flex items-center gap-2.5 z-10 w-full lg:w-auto shrink-0 flex-wrap sm:flex-nowrap">
                <a href="{{ route('admin.notifications.index') }}" class="flex-1 sm:flex-initial h-10 sm:h-11 px-4 sm:px-5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span>{{ __('إرسال إشعار') }}</span>
                </a>
                <a href="{{ route('dashboard') }}" target="_blank" class="flex-1 sm:flex-initial h-10 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-[#EAF1FB] dark:hover:bg-slate-700 text-[#012383] dark:text-blue-300 font-bold text-xs border border-[#DEE8F7] dark:border-slate-700 transition-all flex items-center justify-center gap-1.5 cursor-pointer" title="{{ __('معاينة بوابة الطالب') }}">
                    <span>{{ __('بوابة الطالب') }}</span>
                    <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <a href="{{ route('log-viewer.index') }}" target="_blank" class="h-10 sm:h-11 px-3 sm:px-3.5 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs border border-[#DEE8F7] dark:border-slate-700 transition-all flex items-center justify-center cursor-pointer" title="{{ __('سجلات النظام (Logs)') }}">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </a>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- 2. CORE KPIS STATS CARDS                       -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
            
            <!-- KPI 1: Registered Students -->
            <a href="{{ route('admin.students.index') }}" class="group bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-5 shadow-xs hover:shadow-card hover:border-[#0B2A8F]/40 dark:hover:border-blue-500/40 transition-all flex flex-col justify-between gap-4 cursor-pointer">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('إجمالي الطلاب المسجلين') }}</span>
                    <div class="size-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-[#012383] dark:text-blue-400 font-['Cairo'] tracking-tight">{{ number_format($totalStudents) }}</h3>
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 dark:border-slate-800/80">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ __('طالب في مسار التخاطب') }}</span>
                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold group-hover:translate-x-[-2px] rtl:group-hover:translate-x-[2px] transition-transform">عرض ←</span>
                    </div>
                </div>
            </a>

            <!-- KPI 2: Pending Bank Receipts -->
            <a href="{{ route('admin.receipts.index') }}" class="group bg-white dark:bg-slate-900 border {{ $pendingReceipts > 0 ? 'border-amber-300 dark:border-amber-700/60 bg-amber-50/20' : 'border-[#DEE8F7] dark:border-slate-800' }} rounded-2xl p-5 shadow-xs hover:shadow-card hover:border-amber-400 dark:hover:border-amber-500 transition-all flex flex-col justify-between gap-4 cursor-pointer">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('إيصالات الدفع المعلقة') }}</span>
                    <div class="size-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-amber-600 dark:text-amber-400 font-['Cairo'] tracking-tight">{{ number_format($pendingReceipts) }}</h3>
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 dark:border-slate-800/80">
                        @if($pendingReceipts > 0)
                            <span class="text-xs text-amber-700 dark:text-amber-400 font-bold flex items-center gap-1.5">
                                <span class="size-2 rounded-full bg-amber-500 animate-pulse"></span>
                                {{ __('بانتظار المراجعة والاعتماد') }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400 dark:text-slate-500">{{ __('جميع الإيصالات مدققة') }}</span>
                        @endif
                        <span class="text-xs text-amber-600 dark:text-amber-400 font-bold group-hover:translate-x-[-2px] rtl:group-hover:translate-x-[2px] transition-transform">مراجعة ←</span>
                    </div>
                </div>
            </a>

            <!-- KPI 3: Pending Tasks to Grade -->
            <a href="{{ route('admin.tasks.index') }}" class="group bg-white dark:bg-slate-900 border {{ $pendingTasks > 0 ? 'border-indigo-300 dark:border-indigo-700/60 bg-indigo-50/20' : 'border-[#DEE8F7] dark:border-slate-800' }} rounded-2xl p-5 shadow-xs hover:shadow-card hover:border-indigo-400 dark:hover:border-indigo-500 transition-all flex flex-col justify-between gap-4 cursor-pointer">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('مهام وتكليفات للتصحيح') }}</span>
                    <div class="size-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-[#012383] dark:text-blue-400 font-['Cairo'] tracking-tight">{{ number_format($pendingTasks) }}</h3>
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 dark:border-slate-800/80">
                        @if($pendingTasks > 0)
                            <span class="text-xs text-indigo-700 dark:text-indigo-400 font-bold flex items-center gap-1.5">
                                <span class="size-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                {{ __('تسجيلات سريرية قيد التدقيق') }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400 dark:text-slate-500">{{ __('تم تصحيح كافة التكليفات') }}</span>
                        @endif
                        <span class="text-xs text-indigo-600 dark:text-indigo-400 font-bold group-hover:translate-x-[-2px] rtl:group-hover:translate-x-[2px] transition-transform">تصحيح ←</span>
                    </div>
                </div>
            </a>

            <!-- KPI 4: Curriculum Lessons & Videos -->
            <a href="{{ route('admin.courses.index') }}" class="group bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-5 shadow-xs hover:shadow-card hover:border-[#0B2A8F]/40 dark:hover:border-blue-500/40 transition-all flex flex-col justify-between gap-4 cursor-pointer">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('المحتوى والدروس المفعلة') }}</span>
                    <div class="size-10 rounded-xl bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-[#012383] dark:text-blue-400 font-['Cairo'] tracking-tight">{{ number_format($totalVideos) }}</h3>
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 dark:border-slate-800/80">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ __('محاضرة فيديو ومهمة عملية') }}</span>
                        <span class="text-xs text-[#0B2A8F] dark:text-blue-400 font-bold group-hover:translate-x-[-2px] rtl:group-hover:translate-x-[2px] transition-transform">إدارة ←</span>
                    </div>
                </div>
            </a>

        </div>

        <!-- ============================================== -->
        <!-- 3. MAIN DASHBOARD PANELS (2-COL LAYOUT)        -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6 min-w-0">
            
            <!-- Left 2 Cols: Course Overview & Health Architecture -->
            <div class="lg:col-span-2 flex flex-col gap-5 sm:gap-6 min-w-0">
                
                <!-- Active Diploma Control Card -->
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col justify-between gap-5 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="size-12 rounded-xl bg-blue-50 dark:bg-slate-800 flex items-center justify-center shrink-0 border border-blue-100 dark:border-slate-700">
                                <img src="/assets/images/course-topic-basics.png" alt="كورس التخاطب" class="size-full object-cover rounded-xl" onerror="this.src='/assets/images/logo.png'" />
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-md bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 text-[11px] font-bold">{{ __('الكورس الأساسي النشط') }}</span>
                                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                    <span class="text-[11px] text-slate-400">{{ __('مستويات متسلسلة') }}</span>
                                </div>
                                <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white mt-0.5 truncate">
                                    {{ $activeCourse?->title_ar ?? __('دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة') }}
                                </h3>
                            </div>
                        </div>
                        <a href="{{ route('admin.courses.index') }}" class="h-9 px-4 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 hover:bg-[#DEE8F7] text-[#012383] dark:text-blue-300 font-bold text-xs transition-colors flex items-center gap-1.5 shrink-0 self-start sm:self-center cursor-pointer">
                            <span>{{ __('إدارة المستويات') }}</span>
                            <span class="text-xs">←</span>
                        </a>
                    </div>

                    <!-- Course Specifications Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <span class="text-[11px] text-slate-400 font-bold block">{{ __('عدد المحاضرات') }}</span>
                            <span class="text-lg font-black text-[#012383] dark:text-blue-300 mt-1 block">{{ $activeCourse?->videos_count ?? $totalVideos }} {{ __('فيديوهات') }}</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <span class="text-[11px] text-slate-400 font-bold block">{{ __('سعر الاشتراك') }}</span>
                            <span class="text-lg font-black text-emerald-600 dark:text-emerald-400 mt-1 block">{{ number_format($activeCourse?->price ?? 1200) }} {{ __('ج.م') }}</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <span class="text-[11px] text-slate-400 font-bold block">{{ __('مدة الصلاحية') }}</span>
                            <span class="text-lg font-black text-slate-700 dark:text-slate-200 mt-1 block">{{ $activeCourse?->access_duration_days ?? 365 }} {{ __('يوماً') }}</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <span class="text-[11px] text-slate-400 font-bold block">{{ __('معيار الشهادة') }}</span>
                            <span class="text-lg font-black text-indigo-600 dark:text-indigo-400 mt-1 block">٩٥٪ {{ __('إتمام') }}</span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ $activeCourse?->description_ar ?? __('البرنامج التدريبي المتكامل لتأهيل أخصائيي التخاطب وأولياء الأمور مع نظام المهام بعد كل درس ونظام القفل المتسلسل.') }}
                    </p>
                </div>

                <!-- Platform Activity & Student Engagement Summary -->
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col justify-between gap-5 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h4 class="font-bold text-base text-[#012383] dark:text-blue-400">{{ __('نشاط المشتركين والتفاعل الأكاديمي') }}</h4>
                            <p class="text-xs text-slate-400 mt-0.5">{{ __('مؤشرات الانضمام وإكمال المسار التدريبي لدبلوم التخاطب') }}</p>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 w-fit">
                            {{ __('إجمالي الطلاب: :count', ['count' => $totalStudents]) }}
                        </span>
                    </div>

                    <!-- Progress & Milestone Bars -->
                    <div class="flex flex-col gap-4 pt-2">
                        <!-- Progress Metric 1 -->
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-bold text-slate-600 dark:text-slate-300">{{ __('حسابات الطلاب المفعلة') }}</span>
                                <span class="font-black text-[#012383] dark:text-blue-400">{{ $totalStudents > 0 ? '100%' : '0%' }}</span>
                            </div>
                            <div class="h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-[#012383] to-[#0B2A8F] rounded-full transition-all duration-500" style="width: {{ $totalStudents > 0 ? '100%' : '0%' }}"></div>
                            </div>
                        </div>

                        <!-- Progress Metric 2 -->
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-bold text-slate-600 dark:text-slate-300">{{ __('نسبة جاهزية المنهج والدروس') }}</span>
                                <span class="font-black text-emerald-600 dark:text-emerald-400">١٠٠٪</span>
                            </div>
                            <div class="h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>

                        <!-- Progress Metric 3 -->
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-bold text-slate-600 dark:text-slate-300">{{ __('إيصالات التحويل البنكي المعتمدة') }}</span>
                                <span class="font-black text-amber-600 dark:text-amber-400">{{ $pendingReceipts > 0 ? __('قيد التدقيق') : __('مكتملة') }}</span>
                            </div>
                            <div class="h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                <div class="h-full bg-amber-500 rounded-full" style="width: {{ $pendingReceipts > 0 ? '60%' : '100%' }}"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Security & Protection Banner -->
                    <div class="p-3.5 rounded-xl bg-blue-50/60 dark:bg-slate-800/40 border border-blue-100 dark:border-slate-800 flex items-center gap-3">
                        <div class="size-8 rounded-lg bg-blue-100 dark:bg-slate-700 text-[#0B2A8F] dark:text-blue-300 flex items-center justify-center shrink-0">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            <span class="font-bold text-[#012383] dark:text-blue-300">{{ __('حماية حقوق المحتوى:') }}</span>
                            {{ __('الفيديوهات محمية ومشفرة عبر مكتبة Bunny Stream مع ختم العلامة المائية للطلاب.') }}
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right 1 Col: Quick Management Actions & Platform Status -->
            <div class="flex flex-col gap-5 sm:gap-6 min-w-0">
                
                <!-- Quick Operations Card -->
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col gap-4 min-w-0">
                    <div>
                        <h4 class="font-bold text-base text-[#012383] dark:text-blue-400">{{ __('إجراءات سريعة للإدارة') }}</h4>
                        <p class="text-xs text-slate-400 mt-0.5">{{ __('روابط سريعة لأهم العمليات اليومية') }}</p>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        
                        <!-- Review Receipts Action -->
                        <a href="{{ route('admin.receipts.index') }}" class="flex items-center justify-between p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 hover:border-amber-400 hover:bg-amber-50/40 dark:hover:bg-slate-800 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="size-9 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-bold text-xs text-[#012383] dark:text-blue-300 truncate">{{ __('مراجعة الإيصالات') }}</span>
                                    <span class="text-[10px] text-slate-400 truncate">
                                        @if($pendingReceipts > 0)
                                            <span class="text-amber-600 dark:text-amber-400 font-bold">{{ $pendingReceipts }} {{ __('إيصال بانتظار الاعتماد') }}</span>
                                        @else
                                            {{ __('جميع الإيصالات مدققة') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <span class="text-xs text-amber-600 dark:text-amber-400 font-bold group-hover:translate-x-[-2px] rtl:group-hover:translate-x-[2px] transition-transform">←</span>
                        </a>

                        <!-- Grade Tasks Action -->
                        <a href="{{ route('admin.tasks.index') }}" class="flex items-center justify-between p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 hover:border-indigo-400 hover:bg-indigo-50/40 dark:hover:bg-slate-800 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="size-9 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-bold text-xs text-[#012383] dark:text-blue-300 truncate">{{ __('تصحيح التكليفات') }}</span>
                                    <span class="text-[10px] text-slate-400 truncate">
                                        @if($pendingTasks > 0)
                                            <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $pendingTasks }} {{ __('تكليف بانتظار التقييم') }}</span>
                                        @else
                                            {{ __('تقييم تسجيلات الطلاب السريرية') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <span class="text-xs text-indigo-600 dark:text-indigo-400 font-bold group-hover:translate-x-[-2px] rtl:group-hover:translate-x-[2px] transition-transform">←</span>
                        </a>

                        <!-- Send Notifications Action -->
                        <a href="{{ route('admin.notifications.index') }}" class="flex items-center justify-between p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 hover:border-[#0B2A8F] hover:bg-[#EAF1FB]/40 dark:hover:bg-slate-800 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="size-9 rounded-lg bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-bold text-xs text-[#012383] dark:text-blue-300 truncate">{{ __('مركز الإشعارات') }}</span>
                                    <span class="text-[10px] text-slate-400 truncate">{{ __('إرسال تنبيه للطلاب أو فئة محددة') }}</span>
                                </div>
                            </div>
                            <span class="text-xs text-[#0B2A8F] dark:text-blue-400 font-bold group-hover:translate-x-[-2px] rtl:group-hover:translate-x-[2px] transition-transform">←</span>
                        </a>

                        <!-- Platform Settings Action -->
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center justify-between p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 hover:border-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="size-9 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center shrink-0">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-bold text-xs text-[#012383] dark:text-blue-300 truncate">{{ __('إعدادات المنصة') }}</span>
                                    <span class="text-[10px] text-slate-400 truncate">{{ __('تخصيص القوائم والعلامة التجارية') }}</span>
                                </div>
                            </div>
                            <span class="text-xs text-slate-400 group-hover:translate-x-[-2px] rtl:group-hover:translate-x-[2px] transition-transform">←</span>
                        </a>

                        <!-- Logs Viewer Action -->
                        <a href="{{ route('log-viewer.index') }}" target="_blank" class="flex items-center justify-between p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 hover:border-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="size-9 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center shrink-0">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-bold text-xs text-[#012383] dark:text-blue-300 truncate">{{ __('سجلات النظام (Log Viewer)') }}</span>
                                    <span class="text-[10px] text-slate-400 truncate">{{ __('مراقبة الأخطاء وسجلات التشغيل') }}</span>
                                </div>
                            </div>
                            <span class="text-xs text-slate-400 group-hover:translate-x-[-2px] rtl:group-hover:translate-x-[2px] transition-transform">↗</span>
                        </a>

                    </div>
                </div>

                <!-- System Services Health Card -->
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 shadow-xs flex flex-col gap-3 min-w-0">
                    <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('حالة الخدمات السحابية') }}</h5>
                    <div class="flex flex-col gap-2.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="flex items-center gap-2 font-medium text-slate-600 dark:text-slate-300">
                                <span class="size-2 rounded-full bg-emerald-500"></span>
                                Bunny Stream CDN
                            </span>
                            <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full">{{ __('متصل') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="flex items-center gap-2 font-medium text-slate-600 dark:text-slate-300">
                                <span class="size-2 rounded-full bg-emerald-500"></span>
                                Firebase Auth
                            </span>
                            <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full">{{ __('نشط') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="flex items-center gap-2 font-medium text-slate-600 dark:text-slate-300">
                                <span class="size-2 rounded-full bg-emerald-500"></span>
                                {{ __('قاعدة البيانات والمزامنة') }}
                            </span>
                            <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full">{{ __('مستقر') }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-admin-layout::admin>
