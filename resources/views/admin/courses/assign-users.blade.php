<x-admin-layout::admin
    :title="__('تعيين الطلاب للكورس: :title', ['title' => $course->title_ar])"
    :description="__('تحديد الطلاب وتعيين صلاحيات الوصول للكورس جماعياً وتحديث الاشتراكات في أي وقت')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-6 max-w-[1400px] w-full mx-auto min-w-0"
>
    <x-slot:actions>
        <div class="flex items-center gap-2 flex-wrap">
            <a 
                href="{{ route('admin.courses.index') }}" 
                class="h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs"
            >
                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>{{ __('دليل الكورسات') }}</span>
            </a>

            <a 
                href="{{ route('admin.courses.show', $course) }}" 
                class="h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-blue-50 dark:bg-slate-800 border border-blue-200 dark:border-slate-700 hover:bg-[#EAF1FB] text-[#0B2A8F] dark:text-blue-300 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>{{ __('عرض المنهج') }}</span>
            </a>

            <a 
                href="{{ route('admin.students.index') }}" 
                target="_blank"
                class="h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs"
            >
                <svg class="size-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>{{ __('سجل الطلاب') }}</span>
                <span class="text-xs">↗</span>
            </a>
        </div>
    </x-slot:actions>

    {{-- Session Flash Alerts --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-bold flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="size-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 cursor-pointer">✕</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-bold space-y-1 shadow-xs">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Top Course Info & Stats Banner --}}
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-5">
        <div class="flex items-start sm:items-center gap-4 min-w-0 flex-1">
            <div class="size-20 sm:size-24 rounded-2xl bg-[#012383] overflow-hidden shrink-0 border border-[#DEE8F7] dark:border-slate-700 shadow-2xs relative">
                <img src="{{ $course->thumbnail ?: '/assets/images/course-topic-basics.png' }}" alt="{{ $course->title_ar }}" class="size-full object-cover" />
                @if($course->promo_video_source === 'mux' || !empty($course->mux_playback_id))
                    <span class="absolute bottom-1 end-1 bg-black/70 backdrop-blur-xs text-white text-[10px] font-bold px-1.5 py-0.5 rounded-md flex items-center gap-0.5">
                        <span class="size-1.5 rounded-full bg-emerald-400"></span> MUX
                    </span>
                @endif
            </div>

            <div class="space-y-1.5 min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap text-xs">
                    <span class="px-2.5 py-0.5 rounded-full {{ $course->is_published ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }} font-bold font-['Cairo']">
                        {{ $course->is_published ? __('منشور ونشط') : __('مسودة') }}
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    @if($course->auto_enroll_students)
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-50 dark:bg-blue-950/40 text-[#012383] dark:text-blue-300 font-bold font-['Cairo'] flex items-center gap-1 border border-blue-200/60 dark:border-blue-900/40">
                            <svg class="size-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>{{ __('متاح تلقائياً لكافة الطلاب') }}</span>
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 font-bold font-['Cairo'] flex items-center gap-1 border border-amber-200/60 dark:border-amber-900/40">
                            <svg class="size-3 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>{{ __('يتطلب اشتراك محدد') }}</span>
                        </span>
                    @endif
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-slate-500 dark:text-slate-400 font-medium">{{ __('الصلاحية الافتراضية: :days يوم', ['days' => $course->access_duration_days ?: 365]) }}</span>
                </div>

                <h1 class="font-['Cairo'] font-extrabold text-lg sm:text-2xl text-[#012383] dark:text-white leading-snug truncate">
                    {{ $course->title_ar }}
                </h1>
                
                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 leading-relaxed">
                    {{ $course->description_ar ?: __('التحكم بتعيين الطلاب لهذا الكورس وتحديث الوصول في أي وقت.') }}
                </p>
            </div>
        </div>

        {{-- Quick Stats Chips --}}
        <div class="grid grid-cols-3 gap-2.5 sm:gap-3 shrink-0">
            <div class="bg-slate-50 dark:bg-slate-800/60 p-3 sm:p-3.5 rounded-2xl border border-[#DEE8F7] dark:border-slate-700 text-center">
                <span class="text-[11px] text-slate-400 font-bold block">{{ __('إجمالي طلاب المنصة') }}</span>
                <span class="text-lg sm:text-xl font-black text-[#012383] dark:text-white">{{ $students->count() }}</span>
            </div>
            <div class="bg-purple-50 dark:bg-purple-950/40 p-3 sm:p-3.5 rounded-2xl border border-purple-200 dark:border-purple-800 text-center">
                <span class="text-[11px] text-purple-600 dark:text-purple-300 font-bold block">{{ __('المشتركون حالياً') }}</span>
                <span id="stat-enrolled-count" class="text-lg sm:text-xl font-black text-purple-700 dark:text-purple-300">{{ $enrolledUsers->count() }}</span>
            </div>
            <div class="bg-emerald-50 dark:bg-emerald-950/40 p-3 sm:p-3.5 rounded-2xl border border-emerald-200 dark:border-emerald-800 text-center">
                <span class="text-[11px] text-emerald-600 dark:text-emerald-300 font-bold block">{{ __('المحددون للحفظ') }}</span>
                <span id="stat-selected-count" class="text-lg sm:text-xl font-black text-emerald-700 dark:text-emerald-400">0</span>
            </div>
        </div>
    </div>

    @if($course->auto_enroll_students)
        <div class="p-4 rounded-2xl bg-blue-50/90 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-200 text-xs sm:text-sm font-medium flex items-start gap-3 shadow-2xs">
            <svg class="size-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="space-y-1">
                <span class="font-bold block">{{ __('تنبيه: ميزة الإتاحة التلقائية مفعّلة لهذا الكورس') }}</span>
                <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed">
                    {{ __('هذا الكورس متاح حالياً لكافة الطلاب المسجلين بالمنصة. يمكنك تعيين الطلاب يدوياً هنا، وستبقى اشتراكاتهم مسجلة وموثقة في جدول الاشتراكات وتستمر سارية المفعول حتى لو قمت بإلغاء الإتاحة التلقائية لاحقاً.') }}
                </p>
            </div>
        </div>
    @endif

    {{-- Main Assignment Form --}}
    <form id="assign-users-form" action="{{ route('admin.courses.assign-users.update', $course) }}" method="POST" class="flex flex-col gap-6">
        @csrf

        {{-- Configuration Options Card --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-xs space-y-5">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="font-bold text-base text-[#012383] dark:text-white flex items-center gap-2">
                        <svg class="size-5 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        <span>{{ __('إعدادات الصلاحية ووضع التحديث') }}</span>
                    </h3>
                    <p class="text-xs text-slate-400">{{ __('حدد مدة الصلاحية التي سيتم منحها للطلاب المحددين، وكيفية تطبيق التحديث') }}</p>
                </div>
                
                {{-- Subscription status selector --}}
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ __('حالة الوصول:') }}</label>
                    <select name="status" class="h-9 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-slate-50 dark:bg-slate-800 text-[#012383] dark:text-white outline-none">
                        <option value="active" selected>{{ __('نشط ومفعل (Active)') }}</option>
                        <option value="suspended">{{ __('معلق مؤقتاً (Suspended)') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
                {{-- Access Duration Settings --}}
                <div class="space-y-3">
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block">
                        {{ __('مدة الصلاحية للطلاب المحددين:') }}
                    </label>

                    <div class="space-y-2.5">
                        {{-- Option 1: Course Default --}}
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer transition-colors duration-150">
                            <input 
                                type="radio" 
                                name="duration_type" 
                                value="default" 
                                checked 
                                onchange="toggleDurationInputs('default')"
                                class="size-4 text-[#012383] focus:ring-[#012383]" 
                            />
                            <div class="min-w-0 flex-1">
                                <span class="text-xs font-bold text-[#012383] dark:text-white block">
                                    {{ __('المدة الافتراضية للكورس (:days يوم)', ['days' => $course->access_duration_days ?: 365]) }}
                                </span>
                                <span class="text-[11px] text-slate-400 block">{{ __('سيبدأ الاشتراك من تاريخ اليوم وينتهي بعد :days يوماً', ['days' => $course->access_duration_days ?: 365]) }}</span>
                            </div>
                        </label>

                        {{-- Option 2: Custom Days --}}
                        <div class="p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 space-y-2.5">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="duration_type" 
                                    value="custom_days" 
                                    id="duration_type_custom_days"
                                    onchange="toggleDurationInputs('custom_days')"
                                    class="size-4 text-[#012383] focus:ring-[#012383]" 
                                />
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">
                                    {{ __('تحديد عدد أيام مخصص:') }}
                                </span>
                            </label>

                            <div id="custom-days-wrapper" class="flex flex-wrap items-center gap-2 ps-7 opacity-50 pointer-events-none transition-opacity">
                                <input 
                                    type="number" 
                                    name="duration_days" 
                                    id="duration_days_input"
                                    min="1" 
                                    max="3650" 
                                    placeholder="365"
                                    class="w-28 h-9 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-white dark:bg-slate-800"
                                />
                                <span class="text-xs text-slate-400 font-bold">{{ __('يوم') }}</span>

                                {{-- Quick Presets --}}
                                <div class="flex items-center gap-1 flex-wrap">
                                    <button type="button" onclick="setPresetDays(30)" class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-[11px] font-bold text-slate-600 dark:text-slate-300">30 {{ __('يوم') }}</button>
                                    <button type="button" onclick="setPresetDays(90)" class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-[11px] font-bold text-slate-600 dark:text-slate-300">90 {{ __('يوم') }}</button>
                                    <button type="button" onclick="setPresetDays(180)" class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-[11px] font-bold text-slate-600 dark:text-slate-300">6 {{ __('أشهر') }}</button>
                                    <button type="button" onclick="setPresetDays(365)" class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-[11px] font-bold text-slate-600 dark:text-slate-300">{{ __('سنة') }}</button>
                                </div>
                            </div>
                        </div>

                        {{-- Option 3: Exact Date --}}
                        <div class="p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-800 space-y-2.5">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="duration_type" 
                                    value="custom_date" 
                                    id="duration_type_custom_date"
                                    onchange="toggleDurationInputs('custom_date')"
                                    class="size-4 text-[#012383] focus:ring-[#012383]" 
                                />
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">
                                    {{ __('تاريخ انتهاء محدد وثابت:') }}
                                </span>
                            </label>

                            <div id="custom-date-wrapper" class="ps-7 opacity-50 pointer-events-none transition-opacity">
                                <input 
                                    type="date" 
                                    name="access_expires_at" 
                                    id="access_expires_at_input"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="h-9 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-white dark:bg-slate-800"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sync Mode Settings --}}
                <div class="space-y-3">
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block">
                        {{ __('طريقة تطبيق التحديث والمزامنة:') }}
                    </label>

                    <div class="p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-800 space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="sync_mode" 
                                value="1" 
                                id="sync_mode_checkbox" 
                                checked 
                                class="size-5 rounded border-slate-300 text-[#012383] focus:ring-[#012383] mt-0.5" 
                            />
                            <div class="space-y-1">
                                <span class="text-xs font-extrabold text-[#012383] dark:text-white block">
                                    {{ __('مزامنة كاملة: إلغاء وصول الطلاب غير المحددين (مستحسن)') }}
                                </span>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                    {{ __('عند تفعيل هذا الخيار: أي طالب محدد سيحصل على الكورس، وأي طالب تلغي تحديده سيتم إلغاء وصوله للكورس. مما يضمن تطابق قائمة المشتركين دائماً مع اختيارك.') }}
                                </p>
                            </div>
                        </label>

                        <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200/70 dark:border-amber-900/40 text-[11px] text-amber-800 dark:text-amber-300 leading-relaxed">
                            <span class="font-bold block mb-0.5">{{ __('ملاحظة حول إلغاء المزامنة:') }}</span>
                            {{ __('إذا قمت بإلغاء تحديد هذا الخيار، سيتم فقط إضافة/تجديد صلاحية الطلاب المحددين حالياً دون المساس بأي طالب مشترك سابق لم يتم تحديده.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search & Bulk Actions Toolbar --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex flex-col gap-4">
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                
                {{-- Live Instant Search Box --}}
                <div class="relative flex-1 min-w-0">
                    <input 
                        type="text" 
                        id="student-search-input" 
                        oninput="filterStudentsList()" 
                        placeholder="{{ __('ابحث فورياً باسم الطالب، البريد الإلكتروني، أو رقم الهاتف...') }}" 
                        class="w-full h-11 ps-10 pe-9 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                    <svg class="size-4 text-slate-400 absolute start-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <button 
                        type="button" 
                        id="clear-search-btn" 
                        onclick="clearSearchInput()" 
                        class="hidden absolute end-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer text-xs"
                    >✕</button>
                </div>

                {{-- Filter Tabs Pills --}}
                <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-none whitespace-nowrap shrink-0 pb-1 md:pb-0">
                    <button 
                        type="button" 
                        onclick="setActiveTab('all')" 
                        id="tab-btn-all" 
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer"
                    >
                        <span>{{ __('الكل') }}</span>
                        <span class="ms-1 px-1.5 py-0.2 rounded-full bg-white/20 text-[10px]">{{ $students->count() }}</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="setActiveTab('enrolled')" 
                        id="tab-btn-enrolled" 
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#EAF1FB] cursor-pointer"
                    >
                        <span>{{ __('المشتركون حالياً') }}</span>
                        <span class="ms-1 px-1.5 py-0.2 rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 text-[10px]">{{ $enrolledUsers->count() }}</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="setActiveTab('unenrolled')" 
                        id="tab-btn-unenrolled" 
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#EAF1FB] cursor-pointer"
                    >
                        <span>{{ __('غير المشتركين') }}</span>
                        <span class="ms-1 px-1.5 py-0.2 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[10px]">{{ $students->count() - $enrolledUsers->count() }}</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="setActiveTab('selected')" 
                        id="tab-btn-selected" 
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#EAF1FB] cursor-pointer"
                    >
                        <span>{{ __('المحددون') }}</span>
                        <span id="tab-selected-badge" class="ms-1 px-1.5 py-0.2 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-[10px]">0</span>
                    </button>
                </div>
            </div>

            {{-- Bulk Quick Action Buttons --}}
            <div class="flex items-center justify-between gap-3 flex-wrap pt-3 border-t border-[#DEE8F7]/70 dark:border-slate-800 text-xs">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-bold text-slate-400 text-[11px]">{{ __('تحديد سريع:') }}</span>

                    <button 
                        type="button" 
                        onclick="selectAllVisible(true)" 
                        class="px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-[#012383] dark:text-blue-300 border border-blue-200 dark:border-blue-900/50 font-bold hover:bg-blue-100 transition-colors cursor-pointer"
                    >
                        {{ __('تحديد جميع المعروضين') }}
                    </button>

                    <button 
                        type="button" 
                        onclick="selectAllVisible(false)" 
                        class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-200 transition-colors cursor-pointer"
                    >
                        {{ __('إلغاء تحديد المعروضين') }}
                    </button>

                    <button 
                        type="button" 
                        onclick="selectByEnrolledState(true)" 
                        class="px-2.5 py-1 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-900/50 font-bold hover:bg-purple-100 transition-colors cursor-pointer"
                    >
                        {{ __('تحديد المشتركين حالياً فقط') }}
                    </button>

                    <button 
                        type="button" 
                        onclick="selectByEnrolledState(false)" 
                        class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-200 transition-colors cursor-pointer"
                    >
                        {{ __('تحديد غير المشتركين فقط') }}
                    </button>
                </div>

                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <span>{{ __('المعروض:') }}</span>
                    <span id="visible-students-count" class="text-[#012383] dark:text-white font-extrabold">{{ $students->count() }}</span>
                    <span>/ {{ $students->count() }}</span>
                </div>
            </div>
        </div>

        {{-- Students List / Table --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto min-w-0">
                <table class="w-full text-right border-collapse min-w-[780px]">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-[#DEE8F7] dark:border-slate-800 text-[12px] font-bold text-slate-500">
                            <th class="py-4 px-4 w-12 text-center">
                                <label class="flex items-center justify-center cursor-pointer" title="{{ __('تحديد / إلغاء تحديد الكل') }}">
                                    <input 
                                        type="checkbox" 
                                        id="master-select-all" 
                                        onchange="toggleMasterCheckbox(this)" 
                                        class="size-4.5 rounded border-slate-300 text-[#012383] focus:ring-[#012383] cursor-pointer" 
                                    />
                                </label>
                            </th>
                            <th class="py-4 px-4">{{ __('الطالب وبيانات الاتصال') }}</th>
                            <th class="py-4 px-4">{{ __('المدينة / الدولة') }}</th>
                            <th class="py-4 px-4 text-center">{{ __('حالة الحساب') }}</th>
                            <th class="py-4 px-4">{{ __('حالة الاشتراك بهذا الكورس') }}</th>
                            <th class="py-4 px-4 text-center">{{ __('إجراء فوري') }}</th>
                        </tr>
                    </thead>
                    <tbody id="students-table-body" class="divide-y divide-[#DEE8F7]/70 dark:divide-slate-800 text-xs">
                        @forelse($students as $student)
                            @php
                                $enrolledPivot = $enrolledUsers->get($student->id)?->pivot;
                                $isEnrolled = ! is_null($enrolledPivot);
                                $expiresAt = ($isEnrolled && ! empty($enrolledPivot->access_expires_at)) ? \Illuminate\Support\Carbon::parse($enrolledPivot->access_expires_at) : null;
                                $isExpired = $expiresAt ? $expiresAt->isPast() : false;
                                $pivotStatus = $enrolledPivot->status ?? 'none';
                                $isActiveEnrollment = $isEnrolled && ! $isExpired && $pivotStatus === 'active';
                            @endphp
                            <tr 
                                id="student-row-{{ $student->id }}"
                                data-student-id="{{ $student->id }}"
                                data-name="{{ strtolower($student->name) }}"
                                data-email="{{ strtolower($student->email) }}"
                                data-phone="{{ strtolower($student->phone ?? '') }}"
                                data-is-enrolled="{{ $isEnrolled ? '1' : '0' }}"
                                data-is-active-enrolled="{{ $isActiveEnrollment ? '1' : '0' }}"
                                class="student-row hover:bg-slate-50/70 dark:hover:bg-slate-800/60 transition-colors {{ $isActiveEnrollment ? 'bg-purple-50/20 dark:bg-purple-950/10' : '' }}"
                            >
                                {{-- Checkbox --}}
                                <td class="py-3.5 px-4 text-center">
                                    <label class="flex items-center justify-center cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            name="user_ids[]" 
                                            value="{{ $student->id }}" 
                                            {{ $isEnrolled ? 'checked' : '' }}
                                            onchange="handleUserCheckboxChange(this)"
                                            class="user-select-checkbox size-4.5 rounded border-slate-300 text-[#012383] focus:ring-[#012383] cursor-pointer" 
                                        />
                                    </label>
                                </td>

                                {{-- Student Info --}}
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="size-10 rounded-full {{ $student->isActive() ? 'bg-[#EAF1FB] text-[#0B2A8F] dark:bg-blue-950/60 dark:text-blue-300' : 'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-300' }} flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ mb_substr($student->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-sm text-[#012383] dark:text-white truncate">
                                                {{ $student->name }}
                                            </div>
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono truncate flex items-center gap-1.5 flex-wrap">
                                                <span>{{ $student->email }}</span>
                                                @if(!empty($student->phone))
                                                    <span>• {{ $student->phone }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Location --}}
                                <td class="py-3.5 px-4">
                                    <span class="text-slate-700 dark:text-slate-300 font-medium">
                                        {{ $student->city ?: ($student->country ?: '—') }}
                                    </span>
                                    @if($student->city && $student->country)
                                        <div class="text-[10px] text-slate-400">{{ $student->country }}</div>
                                    @endif
                                </td>

                                {{-- Platform Account Status --}}
                                <td class="py-3.5 px-4 text-center">
                                    @if($student->isActive())
                                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold">
                                            {{ __('نشط') }}
                                        </span>
                                    @elseif($student->isPending())
                                        <span class="px-2.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 text-[11px] font-bold">
                                            {{ __('بانتظار الموافقة') }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 text-[11px] font-bold">
                                            {{ __('معلق') }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Course Subscription Status --}}
                                <td class="py-3.5 px-4" id="status-cell-{{ $student->id }}">
                                    @if($isActiveEnrollment)
                                        <div class="space-y-0.5">
                                            <span class="px-2.5 py-0.5 rounded-full bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 font-bold text-[11px] inline-flex items-center gap-1 border border-purple-200/80 dark:border-purple-800">
                                                <span class="size-1.5 rounded-full bg-purple-600 animate-pulse"></span>
                                                <span>{{ __('مشترك نشط') }}</span>
                                            </span>
                                            @if($expiresAt)
                                                <div class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                                                    {{ __('ينتهي: :date', ['date' => $expiresAt->format('Y-m-d')]) }}
                                                    <span class="text-slate-400">({{ __('متبقي :days يوم', ['days' => max(0, (int) now()->diffInDays($expiresAt, false))]) }})</span>
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($isEnrolled && $isExpired)
                                        <div class="space-y-0.5">
                                            <span class="px-2.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 font-bold text-[11px] inline-block border border-amber-200/80 dark:border-amber-800">
                                                {{ __('اشتراك منتهي') }}
                                            </span>
                                            @if($expiresAt)
                                                <div class="text-[10px] text-slate-400">
                                                    {{ __('انتهى في: :date', ['date' => $expiresAt->format('Y-m-d')]) }}
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($isEnrolled && $pivotStatus === 'suspended')
                                        <span class="px-2.5 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 font-bold text-[11px] inline-block border border-rose-200/80 dark:border-rose-800">
                                            {{ __('موقوف الوصول') }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[11px] inline-block">
                                            {{ __('غير مشترك') }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Fast Single-Action Button --}}
                                <td class="py-3.5 px-4 text-center">
                                    <button 
                                        type="button" 
                                        id="toggle-btn-{{ $student->id }}"
                                        onclick="quickToggleUserEnrollment({{ $student->id }}, '{{ addslashes($student->name) }}')"
                                        class="h-8 px-2.5 rounded-lg text-xs font-bold transition-all inline-flex items-center gap-1 cursor-pointer {{ $isEnrolled ? 'bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800' : 'bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' }}"
                                        title="{{ $isEnrolled ? __('إلغاء تعيين الطالب من الكورس فوراً') : __('تعيين فوري للطالب بالكورس') }}"
                                    >
                                        @if($isEnrolled)
                                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>{{ __('إلغاء التعيين') }}</span>
                                        @else
                                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                            <span>{{ __('تعيين فوري') }}</span>
                                        @endif
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-400">
                                    <div class="size-16 rounded-2xl bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-400 mx-auto flex items-center justify-center mb-3">
                                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <h4 class="font-bold text-base text-[#012383] dark:text-white">{{ __('لا يوجد طلاب مسجلون في المنصة حالياً') }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">{{ __('بمجرد تسجيل الطلاب، سيظهرون تلقائياً هنا لتعيين الكورس لهم.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- No Search Results State --}}
            <div id="no-results-state" class="hidden p-10 text-center text-slate-400 border-t border-[#DEE8F7] dark:border-slate-800">
                <svg class="size-10 mx-auto mb-2 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <p class="font-bold text-sm text-slate-600 dark:text-slate-300">{{ __('لا توجد نتائج تطابق بحثك') }}</p>
                <button type="button" onclick="clearSearchInput()" class="mt-2 text-xs text-[#0B2A8F] dark:text-blue-400 font-bold hover:underline cursor-pointer">{{ __('إعادة ضبط البحث') }}</button>
            </div>
        </div>

        {{-- Sticky Bottom Action Bar --}}
        <div class="sticky bottom-4 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 flex items-center justify-center shrink-0">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">{{ __('حالة التحديد الحالية:') }}</span>
                    <span class="text-sm font-extrabold text-[#012383] dark:text-white">
                        {{ __('تم تحديد') }} <span id="bar-selected-count" class="text-purple-700 dark:text-purple-300 font-black text-base">0</span> {{ __('من أصل :total طالب', ['total' => $students->count()]) }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <a 
                    href="{{ route('admin.courses.show', $course) }}" 
                    class="flex-1 sm:flex-none h-11 px-5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center transition-colors cursor-pointer"
                >
                    {{ __('إلغاء والعودة') }}
                </a>

                <button 
                    type="submit" 
                    id="submit-assign-btn"
                    class="flex-1 sm:flex-none h-11 px-6 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-bold text-xs sm:text-sm shadow-md hover:shadow-lg active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ __('حفظ وتحديث تعيينات الكورس') }}</span>
                </button>
            </div>
        </div>
    </form>

    {{-- Interactive JavaScript for Instant Search, Tabs, Presets, and AJAX Toggle --}}
    <script>
        let currentTab = 'all';

        function toggleDurationInputs(type) {
            const daysWrapper = document.getElementById('custom-days-wrapper');
            const dateWrapper = document.getElementById('custom-date-wrapper');

            if (type === 'custom_days') {
                daysWrapper.classList.remove('opacity-50', 'pointer-events-none');
                dateWrapper.classList.add('opacity-50', 'pointer-events-none');
                document.getElementById('duration_days_input').focus();
            } else if (type === 'custom_date') {
                daysWrapper.classList.add('opacity-50', 'pointer-events-none');
                dateWrapper.classList.remove('opacity-50', 'pointer-events-none');
                document.getElementById('access_expires_at_input').focus();
            } else {
                daysWrapper.classList.add('opacity-50', 'pointer-events-none');
                dateWrapper.classList.add('opacity-50', 'pointer-events-none');
            }
        }

        function setPresetDays(days) {
            document.getElementById('duration_type_custom_days').checked = true;
            toggleDurationInputs('custom_days');
            const input = document.getElementById('duration_days_input');
            input.value = days;
            input.focus();
        }

        function updateSelectedCounts() {
            const checkboxes = document.querySelectorAll('.user-select-checkbox');
            let selectedCount = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) selectedCount++;
            });

            document.getElementById('stat-selected-count').textContent = selectedCount;
            document.getElementById('bar-selected-count').textContent = selectedCount;
            document.getElementById('tab-selected-badge').textContent = selectedCount;

            const masterCheckbox = document.getElementById('master-select-all');
            if (masterCheckbox && checkboxes.length > 0) {
                const visibleCheckboxes = Array.from(checkboxes).filter(cb => {
                    const row = cb.closest('tr');
                    return row && row.style.display !== 'none';
                });

                if (visibleCheckboxes.length > 0) {
                    const allVisibleChecked = visibleCheckboxes.every(cb => cb.checked);
                    const someVisibleChecked = visibleCheckboxes.some(cb => cb.checked);
                    masterCheckbox.checked = allVisibleChecked;
                    masterCheckbox.indeterminate = !allVisibleChecked && someVisibleChecked;
                }
            }
        }

        function handleUserCheckboxChange(checkbox) {
            const row = checkbox.closest('tr');
            if (row) {
                if (checkbox.checked) {
                    row.classList.add('bg-blue-50/40', 'dark:bg-blue-950/20');
                } else {
                    row.classList.remove('bg-blue-50/40', 'dark:bg-blue-950/20');
                }
            }
            updateSelectedCounts();
        }

        function toggleMasterCheckbox(master) {
            const visibleCheckboxes = Array.from(document.querySelectorAll('.user-select-checkbox')).filter(cb => {
                const row = cb.closest('tr');
                return row && row.style.display !== 'none';
            });

            visibleCheckboxes.forEach(cb => {
                cb.checked = master.checked;
                handleUserCheckboxChange(cb);
            });
            updateSelectedCounts();
        }

        function selectAllVisible(state) {
            const visibleCheckboxes = Array.from(document.querySelectorAll('.user-select-checkbox')).filter(cb => {
                const row = cb.closest('tr');
                return row && row.style.display !== 'none';
            });

            visibleCheckboxes.forEach(cb => {
                cb.checked = state;
                handleUserCheckboxChange(cb);
            });
            updateSelectedCounts();
        }

        function selectByEnrolledState(targetEnrolledState) {
            const rows = document.querySelectorAll('.student-row');
            rows.forEach(row => {
                const isEnrolled = row.getAttribute('data-is-enrolled') === '1';
                const checkbox = row.querySelector('.user-select-checkbox');
                if (checkbox) {
                    if (targetEnrolledState && isEnrolled) {
                        checkbox.checked = true;
                    } else if (!targetEnrolledState && !isEnrolled) {
                        checkbox.checked = true;
                    }
                    handleUserCheckboxChange(checkbox);
                }
            });
            updateSelectedCounts();
        }

        function setActiveTab(tab) {
            currentTab = tab;

            const tabs = ['all', 'enrolled', 'unenrolled', 'selected'];
            tabs.forEach(t => {
                const btn = document.getElementById(`tab-btn-${t}`);
                if (btn) {
                    if (t === tab) {
                        btn.className = 'px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer';
                    } else {
                        btn.className = 'px-3.5 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#EAF1FB] cursor-pointer';
                    }
                }
            });

            filterStudentsList();
        }

        function filterStudentsList() {
            const query = (document.getElementById('student-search-input').value || '').trim().toLowerCase();
            const clearBtn = document.getElementById('clear-search-btn');
            if (clearBtn) {
                clearBtn.classList.toggle('hidden', query.length === 0);
            }

            const rows = document.querySelectorAll('.student-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const email = row.getAttribute('data-email') || '';
                const phone = row.getAttribute('data-phone') || '';
                const isEnrolled = row.getAttribute('data-is-enrolled') === '1';
                const checkbox = row.querySelector('.user-select-checkbox');
                const isSelected = checkbox ? checkbox.checked : false;

                // Match search query
                const matchesQuery = query === '' || name.includes(query) || email.includes(query) || phone.includes(query);

                // Match active tab
                let matchesTab = true;
                if (currentTab === 'enrolled') {
                    matchesTab = isEnrolled;
                } else if (currentTab === 'unenrolled') {
                    matchesTab = !isEnrolled;
                } else if (currentTab === 'selected') {
                    matchesTab = isSelected;
                }

                if (matchesQuery && matchesTab) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('visible-students-count').textContent = visibleCount;
            const noResults = document.getElementById('no-results-state');
            if (noResults) {
                noResults.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
            }

            updateSelectedCounts();
        }

        function clearSearchInput() {
            const input = document.getElementById('student-search-input');
            if (input) {
                input.value = '';
                filterStudentsList();
                input.focus();
            }
        }

        // Quick AJAX toggle enrollment for a single student
        async function quickToggleUserEnrollment(userId, studentName) {
            const btn = document.getElementById(`toggle-btn-${userId}`);
            const row = document.getElementById(`student-row-${userId}`);
            const checkbox = row ? row.querySelector('.user-select-checkbox') : null;
            const statusCell = document.getElementById(`status-cell-${userId}`);

            if (!btn) return;
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `<span class="size-3.5 border-2 border-current border-t-transparent rounded-full animate-spin"></span> <span>...</span>`;

            try {
                const response = await fetch(`{{ route('admin.courses.assign-users.toggle', $course) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    const isEnrolledNow = data.enrolled;

                    if (row) {
                        row.setAttribute('data-is-enrolled', isEnrolledNow ? '1' : '0');
                        row.setAttribute('data-is-active-enrolled', isEnrolledNow ? '1' : '0');
                    }

                    if (checkbox) {
                        checkbox.checked = isEnrolledNow;
                        handleUserCheckboxChange(checkbox);
                    }

                    if (statusCell) {
                        if (isEnrolledNow) {
                            statusCell.innerHTML = `
                                <div class="space-y-0.5">
                                    <span class="px-2.5 py-0.5 rounded-full bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 font-bold text-[11px] inline-flex items-center gap-1 border border-purple-200/80 dark:border-purple-800">
                                        <span class="size-1.5 rounded-full bg-purple-600 animate-pulse"></span>
                                        <span>{{ __('مشترك نشط') }}</span>
                                    </span>
                                    <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">
                                        {{ __('تم التعيين الآن') }}
                                    </div>
                                </div>
                            `;
                        } else {
                            statusCell.innerHTML = `
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-[11px] inline-block">
                                    {{ __('غير مشترك') }}
                                </span>
                            `;
                        }
                    }

                    if (isEnrolledNow) {
                        btn.className = 'h-8 px-2.5 rounded-lg text-xs font-bold transition-all inline-flex items-center gap-1 cursor-pointer bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800';
                        btn.innerHTML = `
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>{{ __('إلغاء التعيين') }}</span>
                        `;
                    } else {
                        btn.className = 'h-8 px-2.5 rounded-lg text-xs font-bold transition-all inline-flex items-center gap-1 cursor-pointer bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800';
                        btn.innerHTML = `
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            <span>{{ __('تعيين فوري') }}</span>
                        `;
                    }

                    if (typeof data.total_enrolled !== 'undefined') {
                        document.getElementById('stat-enrolled-count').textContent = data.total_enrolled;
                    }

                    updateSelectedCounts();
                } else {
                    alert(data.message || 'حدث خطأ أثناء تحديث حالة الطالب.');
                    btn.innerHTML = originalHtml;
                }
            } catch (err) {
                console.error(err);
                alert('فشل الاتصال بالخادم.');
                btn.innerHTML = originalHtml;
            } finally {
                btn.disabled = false;
            }
        }

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            updateSelectedCounts();
        });
    </script>
</x-admin-layout::admin>

