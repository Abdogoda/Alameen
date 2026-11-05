<x-admin-layout::admin
    :title="__('إدارة ملف الطالب: :name', ['name' => $user->name])"
    :description="__('سجل المشاهدة، اشتراكات الكورسات، التكليفات والمهام، الشهادات، وإيصالات الدفع')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-5 sm:gap-6 w-full min-w-0"
>
    <x-slot:actions>
        <div class="flex items-center gap-2 sm:gap-2.5 flex-wrap w-full 2xl:w-auto">
            {{-- Back to Students List --}}
            <a 
                href="{{ route('admin.students.index') }}" 
                class="h-9 sm:h-10 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs whitespace-nowrap"
            >
                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>{{ __('دليل الطلاب') }}</span>
            </a>

            {{-- Enroll Course Modal Trigger --}}
            <button 
                type="button" 
                onclick="openModal('modal-enroll-course')" 
                class="h-9 sm:h-10 px-3.5 sm:px-4 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs cursor-pointer whitespace-nowrap"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>{{ __('منح كورس جديد') }}</span>
            </button>

            {{-- Issue Certificate Modal Trigger --}}
            <button 
                type="button" 
                onclick="openModal('modal-issue-certificate')" 
                class="h-9 sm:h-10 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-[#012383] dark:text-blue-300 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs cursor-pointer whitespace-nowrap"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                <span>{{ __('رفع شهادة') }}</span>
            </button>

            {{-- Edit Profile Modal Trigger --}}
            <button 
                type="button" 
                onclick="openModal('modal-edit-student')" 
                class="h-9 sm:h-10 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs cursor-pointer whitespace-nowrap"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>{{ __('تعديل البيانات') }}</span>
            </button>

            {{-- Change Password Modal Trigger --}}
            <button 
                type="button" 
                onclick="openModal('modal-change-password')" 
                class="h-9 sm:h-10 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs cursor-pointer whitespace-nowrap"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                <span>{{ __('تغيير كلمة المرور') }}</span>
            </button>

            @if($user->isPending())
                <form method="POST" action="{{ route('admin.students.approve', $user->id) }}">
                    @csrf
                    <button type="submit" class="h-9 sm:h-10 px-4 sm:px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 transition-all shadow-sm cursor-pointer whitespace-nowrap animate-pulse">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ __('موافقة واعتماد حساب الطالب') }}</span>
                    </button>
                </form>
            @endif

            {{-- Toggle Status Form --}}
            <form 
                method="POST" 
                action="{{ route('admin.students.toggle', $user->id) }}"
                data-confirm="{{ $user->isActive() ? __('هل أنت متأكد من تعليق حساب هذا الطالب ومنعه من الوصول لمنصة التدريب؟') : ($user->isPending() ? __('هل أنت متأكد من اعتماد وتفعيل حساب الطالب؟') : __('هل أنت متأكد من إعادة تنشيط هذا الحساب؟')) }}"
                data-confirm-title="{{ $user->isActive() ? __('تعليق حساب الطالب') : ($user->isPending() ? __('اعتماد حساب الطالب') : __('تنشيط حساب الطالب')) }}"
                data-confirm-btn="{{ $user->isActive() ? __('نعم، علّق الحساب') : ($user->isPending() ? __('نعم، اعتمد الحساب') : __('نعم، نشّط الحساب')) }}"
                data-confirm-danger="{{ $user->isActive() ? 'true' : 'false' }}"
            >
                @csrf
                @if($user->isActive())
                    <button type="submit" class="h-9 sm:h-10 px-3.5 sm:px-4 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800 font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer whitespace-nowrap">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        <span>{{ __('تعليق الحساب') }}</span>
                    </button>
                @elseif($user->isPending())
                    <button type="submit" class="h-9 sm:h-10 px-3.5 sm:px-4 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer whitespace-nowrap">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ __('تفعيل الحساب') }}</span>
                    </button>
                @else
                    <button type="submit" class="h-9 sm:h-10 px-3.5 sm:px-4 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer whitespace-nowrap">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ __('تنشيط الحساب') }}</span>
                    </button>
                @endif
            </form>

            {{-- Delete Student Trigger --}}
            <button 
                type="button" 
                onclick="openDeleteStudentModal('{{ $user->id }}', @js($user->name), @js($user->email), '{{ route('admin.students.destroy', $user) }}')" 
                class="h-9 sm:h-10 px-3.5 sm:px-4 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800 dark:hover:bg-rose-900/60 font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer whitespace-nowrap"
                title="{{ __('حذف حساب الطالب نهائياً مع تأكيد كلمة مرور المشرف') }}"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>{{ __('حذف الحساب') }}</span>
            </button>
        </div>
    </x-slot:actions>

    {{-- Top Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-bold flex items-center justify-between gap-3 shadow-2xs">
            <div class="flex items-center gap-2.5">
                <svg class="size-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-sm font-bold flex items-center justify-between gap-3 shadow-2xs">
            <div class="flex items-center gap-2.5">
                <svg class="size-5 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ session('warning') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-bold flex flex-col gap-1 shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="size-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ __('يرجى التحقق من الأخطاء التالية:') }}</span>
            </div>
            <ul class="list-disc list-inside ps-5 text-xs font-medium space-y-0.5 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Student Info Banner --}}
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-2xs relative overflow-hidden">
        <div class="absolute -top-12 -left-12 size-48 bg-gradient-to-br from-blue-100/50 to-indigo-100/20 dark:from-blue-900/20 dark:to-transparent rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 relative z-10">
            {{-- Student Identity Block --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-5 min-w-0 flex-1">
                <div class="size-16 sm:size-20 rounded-2xl {{ $user->isActive() ? 'bg-gradient-to-br from-[#012383] to-[#0B2A8F] text-white' : 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300' }} flex items-center justify-center font-black text-2xl sm:text-3xl shrink-0 shadow-sm ring-4 ring-[#EAF1FB] dark:ring-slate-800">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight break-words">{{ $user->name }}</h2>
                        
                        @if($user->isActive())
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 inline-flex items-center gap-1.5 shrink-0">
                                <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ __('حساب نشط ومفعل') }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800 inline-flex items-center gap-1.5 shrink-0">
                                <span class="size-2 rounded-full bg-rose-500"></span>
                                {{ __('معلق / ملغي الصلاحية') }}
                            </span>
                        @endif

                        <span class="text-xs font-mono font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 rounded-lg shrink-0">
                            #STU-{{ str_pad((string)$user->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    {{-- Contact & Location Details --}}
                    <div class="flex items-center gap-3 sm:gap-5 text-xs text-slate-600 dark:text-slate-400 mt-2.5 flex-wrap">
                        {{-- Email --}}
                        <div class="flex items-center gap-1.5 font-mono min-w-0">
                            <svg class="size-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $user->email }}" class="hover:text-[#012383] dark:hover:text-blue-400 underline decoration-dotted truncate max-w-[220px] sm:max-w-none">{{ $user->email }}</a>
                            @if($user->email_verified_at)
                                <span class="text-emerald-600 dark:text-emerald-400 text-[10px] font-bold shrink-0" title="{{ __('تم توثيق البريد الإلكتروني') }}">✓ {{ __('موثق') }}</span>
                            @else
                                <span class="text-amber-500 text-[10px] font-bold shrink-0" title="{{ __('البريد غير موثق بعد') }}">{{ __('غير موثق بعد') }}</span>
                            @endif
                        </div>

                        {{-- Phone --}}
                        <div class="flex items-center gap-1.5 font-mono shrink-0">
                            <svg class="size-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            @if($user->phone)
                                <a href="tel:{{ $user->phone }}" class="hover:text-[#012383] dark:hover:text-blue-400 underline decoration-dotted dir-ltr">{{ $user->phone }}</a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="text-emerald-600 hover:text-emerald-700 font-bold text-[10px] bg-emerald-50 dark:bg-emerald-950/60 px-1.5 py-0.5 rounded" title="{{ __('محادثة واتساب مباشرة') }}">{{ __('واتساب') }}</a>
                            @else
                                <span class="text-slate-400">{{ __('لا يوجد رقم هاتف') }}</span>
                            @endif
                        </div>

                        {{-- Location --}}
                        <div class="flex items-center gap-1.5 shrink-0">
                            <svg class="size-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $user->city ?? '—' }} {{ $user->country ? "({$user->country})" : '' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Metadata Details --}}
            <div class="flex sm:flex-col items-start sm:items-end justify-between w-full lg:w-auto gap-2 text-xs border-t lg:border-t-0 pt-3 lg:pt-0 border-slate-100 dark:border-slate-800 shrink-0">
                <div class="text-slate-500 dark:text-slate-400">
                    <span class="text-slate-400 block sm:inline">{{ __('تاريخ التسجيل:') }}</span>
                    <strong class="font-bold text-slate-800 dark:text-slate-200 dir-ltr inline-block">{{ $user->created_at?->format('d/m/Y - h:i A') }}</strong>
                </div>
                <div class="text-[11px] text-slate-400 flex items-center gap-1.5 flex-wrap">
                    <span>{{ __('منذ:') }} {{ $user->created_at?->diffForHumans() }}</span>
                    @if($user->firebase_uid || $user->google_id)
                        <span class="px-2 py-0.5 bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 font-bold rounded">
                            Google SSO
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Key Performance Indicators Grid (KPIs) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
        {{-- Enrolled Courses --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                <span class="text-[11px] font-bold">{{ __('الكورسات') }}</span>
                <div class="size-7 rounded-lg bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-300 flex items-center justify-center">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-xl font-black text-slate-900 dark:text-white">{{ $user->courses->count() }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">{{ __('مقررات مسجلة') }}</div>
            </div>
        </div>

        {{-- Watch Time Hours --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                <span class="text-[11px] font-bold">{{ __('وقت المشاهدة') }}</span>
                <div class="size-7 rounded-lg bg-amber-50 dark:bg-slate-800 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-xl font-black text-slate-900 dark:text-white">{{ $hoursWatched }}<span class="text-xs font-bold text-slate-400 ms-1">{{ __('ساعة') }}</span></div>
                <div class="text-[10px] text-slate-400 mt-0.5">{{ $minutesWatched }} {{ __('دقيقة إجمالياً') }}</div>
            </div>
        </div>

        {{-- Completed Videos --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                <span class="text-[11px] font-bold">{{ __('الدروس المكتملة') }}</span>
                <div class="size-7 rounded-lg bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-xl font-black text-slate-900 dark:text-white">{{ $completedVideosCount }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">{{ __('محاضرة منتهية') }}</div>
            </div>
        </div>

        {{-- Tasks & Submissions --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                <span class="text-[11px] font-bold">{{ __('المهام والتقييم') }}</span>
                <div class="size-7 rounded-lg bg-indigo-50 dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-xl font-black text-slate-900 dark:text-white">{{ $totalTasksSubmitted }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">
                    @if($gradedTasksCount > 0)
                        {{ __('متوسط: :score%', ['score' => $avgScore]) }}
                    @else
                        {{ __('بانتظار التقييم') }}
                    @endif
                </div>
            </div>
        </div>

        {{-- Certificates --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                <span class="text-[11px] font-bold">{{ __('الشهادات') }}</span>
                <div class="size-7 rounded-lg bg-purple-50 dark:bg-slate-800 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-xl font-black text-slate-900 dark:text-white">{{ $user->certificates->count() }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">{{ __('شهادة موثقة') }}</div>
            </div>
        </div>

        {{-- Approved Payments --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                <span class="text-[11px] font-bold">{{ __('المدفوعات المعتمدة') }}</span>
                <div class="size-7 rounded-lg bg-teal-50 dark:bg-slate-800 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-xl font-black text-slate-900 dark:text-white">{{ number_format($approvedReceiptsTotal, 0) }}<span class="text-xs font-bold text-slate-400 ms-1">{{ $currencySymbol ?? platform_currency() }}</span></div>
                <div class="text-[10px] text-slate-400 mt-0.5">{{ $user->paymentReceipts->count() }} {{ __('إيصالات مسجلة') }}</div>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation Component --}}
    <div x-data="{ currentTab: 'courses' }" class="flex flex-col gap-5 min-w-0 w-full">
        {{-- Tabs Bar --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-1.5 shadow-2xs flex items-center gap-1.5 overflow-x-auto scrollbar-none w-full max-w-full overscroll-x-contain">
            <button 
                type="button" 
                @click="currentTab = 'courses'" 
                :class="currentTab === 'courses' ? 'bg-[#012383] text-white shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 cursor-pointer whitespace-nowrap"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>{{ __('الاشتراكات والكورسات') }}</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold" :class="currentTab === 'courses' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200'">{{ $user->courses->count() }}</span>
            </button>

            <button 
                type="button" 
                @click="currentTab = 'progress'" 
                :class="currentTab === 'progress' ? 'bg-[#012383] text-white shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 cursor-pointer whitespace-nowrap"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ __('سجل مشاهدة المحاضرات') }}</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold" :class="currentTab === 'progress' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200'">{{ $user->videoProgress->count() }}</span>
            </button>

            <button 
                type="button" 
                @click="currentTab = 'tasks'" 
                :class="currentTab === 'tasks' ? 'bg-[#012383] text-white shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 cursor-pointer whitespace-nowrap"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                <span>{{ __('التكليفات والمهام') }}</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold" :class="currentTab === 'tasks' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200'">{{ $user->taskSubmissions->count() }}</span>
            </button>

            <button 
                type="button" 
                @click="currentTab = 'certificates'" 
                :class="currentTab === 'certificates' ? 'bg-[#012383] text-white shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 cursor-pointer whitespace-nowrap"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                <span>{{ __('الشهادات المعتمدة') }}</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold" :class="currentTab === 'certificates' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200'">{{ $user->certificates->count() }}</span>
            </button>

            <button 
                type="button" 
                @click="currentTab = 'receipts'" 
                :class="currentTab === 'receipts' ? 'bg-[#012383] text-white shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 cursor-pointer whitespace-nowrap"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>{{ __('إيصالات الدفع') }}</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold" :class="currentTab === 'receipts' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200'">{{ $user->paymentReceipts->count() }}</span>
            </button>
        </div>

        {{-- TAB 1: COURSES & ENROLLMENTS --}}
        <div x-show="currentTab === 'courses'" x-cloak class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 min-w-0">
                <div class="min-w-0">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('الكورسات والاشتراكات الممنوحة للطالب') }}</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">{{ __('إدارة صلاحية الوصول وتاريخ الانتهاء ونسبة التقدم في كل مقرر') }}</p>
                </div>
                <button 
                    type="button" 
                    onclick="openModal('modal-enroll-course')" 
                    class="h-9 px-3.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs cursor-pointer shrink-0 self-start sm:self-auto"
                >
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>{{ __('منح كورس جديد') }}</span>
                </button>
            </div>

            @if($user->courses->isEmpty())
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-12 text-center shadow-2xs">
                    <div class="size-16 rounded-2xl bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-300 mx-auto flex items-center justify-center mb-3">
                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('لم يتم منح أي كورس لهذا الطالب بعد') }}</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">{{ __('يمكنك تفعيل الوصول لأي كورس أكاديمي مباشرة وتحديد فترة الاشتراك المناسبة.') }}</p>
                    <button 
                        type="button" 
                        onclick="openModal('modal-enroll-course')" 
                        class="mt-4 px-4 py-2 rounded-xl bg-[#012383] text-white font-bold text-xs inline-flex items-center gap-1.5 cursor-pointer shadow-sm"
                    >
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>{{ __('منح كورس الآن') }}</span>
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                    @foreach($user->courses as $course)
                        @php
                            $progressPct = $enrolledCourseProgress[$course->id] ?? 0;
                            $expiresAt = $course->pivot->access_expires_at ? \Illuminate\Support\Carbon::parse($course->pivot->access_expires_at) : null;
                            $isExpired = $expiresAt ? $expiresAt->isPast() : false;
                            $remainingDays = $expiresAt ? (int) now()->diffInDays($expiresAt, false) : 0;
                            $isActiveAccess = $course->pivot->status === 'active' && ! $isExpired && $user->isActive();
                            $hasCert = $user->certificates->firstWhere('course_id', $course->id);
                            $grantingAdmin = $course->pivot->granted_by ? ($grantingAdmins[$course->pivot->granted_by] ?? null) : null;
                        @endphp
                        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 shadow-2xs flex flex-col justify-between gap-4">
                            <div>
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 min-w-0">
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        @if($course->thumbnail)
                                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="size-12 rounded-xl object-cover shrink-0 border border-slate-100 dark:border-slate-800">
                                        @else
                                            <div class="size-12 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-300 flex items-center justify-center font-bold text-lg shrink-0">
                                                📚
                                            </div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-bold text-sm text-slate-900 dark:text-white truncate" title="{{ $course->title }}">{{ $course->title }}</h4>
                                            <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-2 flex-wrap">
                                                <span>{{ $course->videos_count ?? $course->videos->count() }} {{ __('درس') }}</span>
                                                <span>•</span>
                                                <span>{{ number_format($course->price, 0) }} {{ $currencySymbol ?? platform_currency() }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="shrink-0 self-start sm:self-auto">
                                        @if($isActiveAccess)
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 whitespace-nowrap inline-flex items-center gap-1.5 shrink-0">
                                                <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                <span>{{ __('نشط') }}</span>
                                                <span class="text-[10px] opacity-80 font-normal">({{ $remainingDays }} {{ __('يوم متبقي') }})</span>
                                            </span>
                                        @elseif($isExpired)
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800 whitespace-nowrap shrink-0">
                                                {{ __('منتهي الصلاحية') }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 whitespace-nowrap shrink-0">
                                                {{ $course->pivot->status }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Course Progress Bar --}}
                                <div class="mt-4 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center justify-between text-xs mb-1.5">
                                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ __('نسبة الإنجاز في الكورس:') }}</span>
                                        <span class="font-black {{ $progressPct >= 95 ? 'text-emerald-600' : 'text-[#012383] dark:text-blue-400' }}">{{ $progressPct }}%</span>
                                    </div>
                                    <div class="w-full h-2 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500 {{ $progressPct >= 95 ? 'bg-emerald-500' : 'bg-gradient-to-r from-[#012383] to-blue-500' }}" style="width: {{ $progressPct }}%"></div>
                                    </div>
                                </div>

                                {{-- Dates & Meta --}}
                                <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-500 dark:text-slate-400 mt-3">
                                    <div>
                                        <span class="text-slate-400 block">{{ __('تاريخ البدء:') }}</span>
                                        <strong class="text-slate-700 dark:text-slate-300 dir-ltr inline-block">{{ \Illuminate\Support\Carbon::parse($course->pivot->access_starts_at)->format('d/m/Y') }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block">{{ __('تاريخ الانتهاء:') }}</span>
                                        <strong class="text-slate-700 dark:text-slate-300 dir-ltr inline-block">{{ $expiresAt ? $expiresAt->format('d/m/Y') : '—' }}</strong>
                                    </div>
                                    @if($grantingAdmin)
                                        <div class="col-span-2 text-[10px] text-slate-400 pt-1 border-t border-slate-100 dark:border-slate-800">
                                            <span>{{ __('مُنح بواسطة المشرف:') }} <strong>{{ $grantingAdmin->name }}</strong></span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions Footer --}}
                            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    {{-- Edit Enrollment Modal Trigger --}}
                                    <button 
                                        type="button" 
                                        onclick="openEditEnrollmentModal({{ $course->id }}, '{{ addslashes($course->title) }}', '{{ $expiresAt ? $expiresAt->format('Y-m-d') : '' }}', '{{ $course->pivot->status }}')"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold bg-[#EAF1FB] text-[#012383] hover:bg-[#DEE8F7] dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-900 transition-colors cursor-pointer"
                                    >
                                        {{ __('تعديل الصلاحية والتاريخ') }}
                                    </button>

                                    {{-- If Certificate Not Issued, Quick Button --}}
                                    @if(! $hasCert)
                                        <button 
                                            type="button" 
                                            onclick="openCourseCertificateModal({{ $course->id }}, '{{ addslashes($course->title) }}', {{ $progressPct }})"
                                            class="px-2.5 py-1.5 rounded-lg text-xs font-bold {{ $progressPct >= 95 ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800' }} transition-colors cursor-pointer"
                                        >
                                            🎓 {{ $progressPct >= 95 ? __('منح الشهادة (مكتمل)') : __('إصدار / رفع شهادة') }}
                                        </button>
                                    @else
                                        <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                            🎓 {{ __('الشهادة موثقة') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Revoke / Delete Access --}}
                                <form 
                                    method="POST" 
                                    action="{{ route('admin.students.enrollments.destroy', [$user->id, $course->id]) }}"
                                    data-confirm="{{ __('هل أنت متأكد من إلغاء اشتراك الطالب في كورس :title وسحب الصلاحية؟', ['title' => $course->title]) }}"
                                    data-confirm-title="{{ __('إلغاء اشتراك الكورس') }}"
                                    data-confirm-btn="{{ __('نعم، ألغِ الاشتراك') }}"
                                    data-confirm-danger="true"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/60 transition-colors cursor-pointer self-end sm:self-auto" title="{{ __('إلغاء الاشتراك') }}">
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- TAB 2: LESSONS & PROGRESS --}}
        <div x-show="currentTab === 'progress'" x-cloak class="flex flex-col gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('سجل تفاصيل مشاهدة المحاضرات والدروس') }}</h3>
                <p class="text-xs text-slate-500">{{ __('متابعة دقيقة لنسبة مشاهدة كل فيديو، إمكانية إعادة تعيين التقدم أو إكماله') }}</p>
            </div>

            @if($user->videoProgress->isEmpty())
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-12 text-center shadow-2xs">
                    <div class="size-16 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center mb-3">
                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('لم يبدأ الطالب بمشاهدة أي درس حتى الآن') }}</h4>
                    <p class="text-xs text-slate-400 mt-1">{{ __('سيظهر هنا تلقائياً كل درس يتم فتحه ومقدار الثواني ونسبة الإنجاز المحققة.') }}</p>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl overflow-hidden shadow-2xs">
                    <div class="overflow-x-auto -mx-1 sm:mx-0">
                        <table class="w-full text-start border-collapse text-xs min-w-[720px]">
                            <thead>
                                <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-[#DEE8F7] dark:border-slate-800 text-slate-500 font-bold">
                                    <th class="py-3.5 px-5 text-start">{{ __('المحاضرة / الدرس') }}</th>
                                    <th class="py-3.5 px-4 text-start">{{ __('الكورس') }}</th>
                                    <th class="py-3.5 px-4 text-start">{{ __('وقت المشاهدة') }}</th>
                                    <th class="py-3.5 px-4 text-start">{{ __('نسبة الإنجاز') }}</th>
                                    <th class="py-3.5 px-4 text-center">{{ __('الحالة') }}</th>
                                    <th class="py-3.5 px-4 text-start">{{ __('آخر مشاهدة') }}</th>
                                    <th class="py-3.5 px-5 text-center">{{ __('إدارة التقدم') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#DEE8F7]/70 dark:divide-slate-800">
                                @foreach($user->videoProgress as $progress)
                                    @php
                                        $video = $progress->video;
                                        $course = $video?->course;
                                        $duration = $video ? $video->duration_seconds : 0;
                                        $watched = $progress->watched_seconds;
                                        $watchedMins = floor($watched / 60);
                                        $watchedSecs = $watched % 60;
                                        $totalMins = floor($duration / 60);
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/70 transition-colors">
                                        <td class="py-4 px-5 text-start">
                                            <div class="font-bold text-slate-900 dark:text-white">{{ $video?->title_ar ?? __('درس محذوف') }}</div>
                                            @if($video?->title_en)
                                                <div class="text-[10px] text-slate-400 font-mono">{{ $video->title_en }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-start text-slate-600 dark:text-slate-400 font-medium">
                                            {{ $course?->title ?? '—' }}
                                        </td>
                                        <td class="py-4 px-4 text-start font-mono">
                                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $watchedMins }}m {{ $watchedSecs }}s</span>
                                            <span class="text-slate-400 text-[10px]"> / {{ $totalMins }}m</span>
                                        </td>
                                        <td class="py-4 px-4 text-start">
                                            <div class="flex items-center gap-2">
                                                <div class="w-20 h-2 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden shrink-0">
                                                    <div class="h-full rounded-full {{ $progress->is_completed ? 'bg-emerald-500' : 'bg-blue-600' }}" style="width: {{ min(100, $progress->completion_percentage) }}%"></div>
                                                </div>
                                                <span class="font-bold font-mono text-[11px]">{{ $progress->completion_percentage }}%</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if($progress->is_completed)
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 whitespace-nowrap inline-block">
                                                    ✓ {{ __('مكتمل') }}
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800 whitespace-nowrap inline-block">
                                                    ⏳ {{ __('قيد المتابعة') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-start text-[11px] text-slate-500 whitespace-nowrap">
                                            <span>{{ $progress->last_watched_at?->diffForHumans() ?? '—' }}</span>
                                        </td>
                                        <td class="py-4 px-5 text-center">
                                            @if($video)
                                                <div class="flex items-center justify-center gap-1.5 flex-nowrap">
                                                    {{-- Mark 100% Complete --}}
                                                    <form method="POST" action="{{ route('admin.students.video-progress.update', [$user->id, $video->id]) }}">
                                                        @csrf
                                                        <input type="hidden" name="action" value="complete">
                                                        <button type="submit" class="px-2 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 transition-colors cursor-pointer whitespace-nowrap" title="{{ __('تعيين كمكتمل 100% لفتح الدرس التالي') }}">
                                                            {{ __('إكمال 100%') }}
                                                        </button>
                                                    </form>

                                                    {{-- Reset Progress to 0% --}}
                                                    <form 
                                                        method="POST" 
                                                        action="{{ route('admin.students.video-progress.update', [$user->id, $video->id]) }}"
                                                        data-confirm="{{ __('هل أنت متأكد من تصفير وإعادة تعيين تقدم هذا الدرس؟') }}"
                                                        data-confirm-title="{{ __('إعادة تعيين التقدم') }}"
                                                        data-confirm-btn="{{ __('نعم، صفّر التقدم') }}"
                                                        data-confirm-danger="true"
                                                    >
                                                        @csrf
                                                        <input type="hidden" name="action" value="reset">
                                                        <button type="submit" class="px-2 py-1 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 transition-colors cursor-pointer whitespace-nowrap" title="{{ __('إعادة التعيين لـ 0%') }}">
                                                            {{ __('تصفير') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- TAB 3: TASKS & SUBMISSIONS --}}
        <div x-show="currentTab === 'tasks'" x-cloak class="flex flex-col gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('التسجيلات والمهام التدريبية المقدمة من الطالب') }}</h3>
                <p class="text-xs text-slate-500">{{ __('الاستماع للتسجيلات الصوتية، مراجعة ملاحظات الحالة، ورصد التقييمات التدريبية') }}</p>
            </div>

            @if($user->taskSubmissions->isEmpty())
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-12 text-center shadow-2xs">
                    <div class="size-16 rounded-2xl bg-indigo-50 dark:bg-slate-800 text-indigo-500 mx-auto flex items-center justify-center mb-3">
                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('لا توجد أي مهام تدريبية مرفوعة حتى الآن') }}</h4>
                    <p class="text-xs text-slate-400 mt-1">{{ __('عندما يقوم الطالب برفع تسجيل صوتي للتطبيق العملي، سيظهر هنا مباشرة للمعاينة والتقييم.') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                    @foreach($user->taskSubmissions as $submission)
                        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 shadow-2xs flex flex-col justify-between gap-4">
                            <div>
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2.5 min-w-0">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-bold text-sm text-slate-900 dark:text-white truncate" title="{{ $submission->task?->title ?? __('مهمة تدريبية') }}">{{ $submission->task?->title ?? __('مهمة تدريبية') }}</h4>
                                        <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1.5 flex-wrap">
                                            <span class="truncate max-w-[200px]">{{ $submission->task?->course?->title ?? '—' }}</span>
                                            <span>•</span>
                                            <span class="dir-ltr inline-block">{{ $submission->created_at?->format('d/m/Y - h:i A') }}</span>
                                        </div>
                                    </div>

                                    <div class="shrink-0 self-start sm:self-auto">
                                        @if($submission->isGraded())
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 whitespace-nowrap inline-block">
                                                {{ __('الدرجة:') }} {{ $submission->score }} / {{ $submission->task?->max_score ?? 100 }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800 whitespace-nowrap inline-block">
                                                ⏳ {{ __('قيد المراجعة والتقييم') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Audio Player Component --}}
                                @if($submission->audio_recording_path)
                                    <div class="mt-4 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 w-full max-w-full overflow-hidden">
                                        <div class="flex items-center gap-2 mb-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                            <svg class="size-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                                            <span>{{ __('التسجيل الصوتي للطالب:') }}</span>
                                        </div>
                                        <audio controls class="w-full h-10 outline-none">
                                            <source src="{{ asset('storage/' . $submission->audio_recording_path) }}">
                                            {{ __('متصفحك لا يدعم تشغيل الملفات الصوتية.') }}
                                        </audio>
                                    </div>
                                @endif

                                {{-- Student Notes --}}
                                @if($submission->student_notes)
                                    <div class="mt-3 p-3 rounded-xl bg-slate-50/50 dark:bg-slate-800/30 text-xs text-slate-600 dark:text-slate-300 border border-slate-100 dark:border-slate-800 break-words">
                                        <strong class="text-slate-400 block text-[10px]">{{ __('ملاحظات الطالب مع التسليم:') }}</strong>
                                        <p class="mt-1 leading-relaxed">{{ $submission->student_notes }}</p>
                                    </div>
                                @endif

                                {{-- Feedback if graded --}}
                                @if($submission->clinical_feedback)
                                    <div class="mt-3 p-3 rounded-xl bg-blue-50/60 dark:bg-slate-800/60 text-xs text-slate-700 dark:text-slate-200 border border-blue-100 dark:border-slate-700 break-words">
                                        <strong class="text-[#0B2A8F] dark:text-blue-300 block text-[10px]">{{ __('ملاحظات المشرف (:grader):', ['grader' => $submission->grader?->name ?? __('المشرف')]) }}</strong>
                                        <p class="mt-1 font-medium leading-relaxed">{{ $submission->clinical_feedback }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Grade Submission Action Trigger --}}
                            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5">
                                <span class="text-[10px] text-slate-400">
                                    @if($submission->graded_at)
                                        {{ __('تم التقييم:') }} {{ $submission->graded_at->diffForHumans() }}
                                    @else
                                        {{ __('بانتظار تصحيح المشرف') }}
                                    @endif
                                </span>

                                <button 
                                    type="button" 
                                    onclick="openGradeModal({{ $submission->id }}, '{{ addslashes($submission->task?->title ?? __('مهمة تدريبية')) }}', {{ $submission->score ?? 0 }}, '{{ addslashes($submission->clinical_feedback ?? '') }}', {{ $submission->task?->max_score ?? 100 }})"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#012383] text-white hover:bg-[#0B2A8F] transition-colors cursor-pointer self-start sm:self-auto"
                                >
                                    {{ $submission->isGraded() ? __('تعديل التقييم') : __('تقييم ورصد الدرجة') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- TAB 4: CERTIFICATES --}}
        <div x-show="currentTab === 'certificates'" x-cloak class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 min-w-0">
                <div class="min-w-0">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('الشهادات الأكاديمية الصادرة للطالب') }}</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">{{ __('شهادات معتمدة مرفوعة بصيغة صورة أو PDF برمز QR ورابط تحقق علني') }}</p>
                </div>
                <button 
                    type="button" 
                    onclick="openModal('modal-issue-certificate')" 
                    class="h-9 px-3.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs cursor-pointer shrink-0 self-start sm:self-auto"
                >
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>{{ __('رفع شهادة جديدة') }}</span>
                </button>
            </div>

            @if($user->certificates->isEmpty())
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-12 text-center shadow-2xs">
                    <div class="size-16 rounded-2xl bg-purple-50 dark:bg-slate-800 text-purple-600 mx-auto flex items-center justify-center mb-3">
                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('لم يتم رفع أي شهادة لهذا الطالب بعد') }}</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">{{ __('يمكنك رفع ملف الشهادة الجاهز (صورة أو PDF) وربطه بأي كورس أكاديمي للطالب.') }}</p>
                    <button 
                        type="button" 
                        onclick="openModal('modal-issue-certificate')" 
                        class="mt-4 px-4 py-2 rounded-xl bg-purple-600 text-white font-bold text-xs inline-flex items-center gap-1.5 cursor-pointer shadow-sm"
                    >
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>{{ __('رفع شهادة الآن') }}</span>
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                    @foreach($user->certificates as $certificate)
                        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 shadow-2xs flex flex-col justify-between gap-4">
                            <div>
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 min-w-0">
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        @if($certificate->file_path)
                                            @if($certificate->isPdf())
                                                <a href="{{ $certificate->fileUrl() }}" target="_blank" class="size-16 rounded-2xl bg-rose-50 dark:bg-slate-800 p-2 flex flex-col items-center justify-center shrink-0 border border-rose-100 dark:border-slate-700 hover:scale-105 transition-transform" title="{{ __('فتح ملف PDF') }}">
                                                    <svg class="size-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    <span class="text-[9px] font-bold text-rose-700 dark:text-rose-400 uppercase mt-0.5">PDF</span>
                                                </a>
                                            @else
                                                <a href="{{ $certificate->fileUrl() }}" target="_blank" class="size-16 rounded-2xl overflow-hidden shrink-0 border border-slate-200 dark:border-slate-700 hover:scale-105 transition-transform block bg-slate-100 dark:bg-slate-800" title="{{ __('عرض صورة الشهادة') }}">
                                                    <img src="{{ $certificate->fileUrl() }}" alt="Certificate" class="w-full h-full object-cover">
                                                </a>
                                            @endif
                                        @else
                                            <div class="size-16 rounded-2xl bg-purple-50 dark:bg-slate-800 p-1 flex items-center justify-center shrink-0 border border-purple-100 dark:border-slate-700">
                                                {!! $certificate->qr_code_svg !!}
                                            </div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-bold text-sm text-slate-900 dark:text-white truncate" title="{{ $certificate->course?->title ?? __('شهادة أكاديمية') }}">{{ $certificate->course?->title ?? __('شهادة أكاديمية') }}</h4>
                                            <div class="font-mono text-xs font-bold text-purple-700 dark:text-purple-300 mt-0.5 truncate dir-ltr text-start">
                                                {{ $certificate->certificate_code }}
                                            </div>
                                            <div class="text-[10px] text-slate-400 mt-0.5">
                                                {{ __('تاريخ الإصدار:') }} <span class="dir-ltr inline-block">{{ $certificate->issued_at?->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 shrink-0 self-start sm:self-auto whitespace-nowrap inline-block">
                                        {{ $certificate->completion_percentage }}% {{ __('إنجاز') }}
                                    </span>
                                </div>

                                {{-- Verification Link Box --}}
                                <div class="mt-4 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2 min-w-0">
                                    <div class="text-[11px] font-mono text-slate-600 dark:text-slate-300 truncate min-w-0 flex-1 dir-ltr text-start">
                                        {{ $certificate->verification_url }}
                                    </div>
                                    <a 
                                        href="{{ $certificate->verification_url }}" 
                                        target="_blank" 
                                        class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-white dark:bg-slate-700 text-[#012383] dark:text-blue-300 border border-slate-200 dark:border-slate-600 hover:border-blue-500 shrink-0 inline-flex items-center justify-center gap-1 w-full sm:w-auto"
                                    >
                                        <span>{{ __('رابط التحقق') }}</span>
                                        <svg class="size-3 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Footer Actions --}}
                            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($certificate->file_path)
                                        <a 
                                            href="{{ $certificate->fileUrl() }}" 
                                            target="_blank"
                                            class="text-xs font-bold text-[#012383] dark:text-blue-400 hover:underline inline-flex items-center gap-1"
                                        >
                                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>{{ __('معاينة الملف') }}</span>
                                        </a>
                                        <a 
                                            href="{{ $certificate->fileUrl() }}" 
                                            download
                                            class="text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white inline-flex items-center gap-1"
                                        >
                                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            <span>{{ __('تحميل') }}</span>
                                        </a>
                                    @else
                                        <a 
                                            href="{{ route('certificates.show', $certificate->certificate_code) }}" 
                                            target="_blank"
                                            class="text-xs font-bold text-[#012383] dark:text-blue-400 hover:underline inline-flex items-center gap-1"
                                        >
                                            <span>{{ __('عرض الشهادة') }}</span>
                                            <svg class="size-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    @endif
                                </div>

                                {{-- Delete Certificate Form --}}
                                <form 
                                    method="POST" 
                                    action="{{ route('admin.students.certificates.destroy', [$user->id, $certificate->id]) }}"
                                    data-confirm="{{ __('هل أنت متأكد من حذف وإلغاء هذه الشهادة نهائياً؟') }}"
                                    data-confirm-title="{{ __('حذف الشهادة الأكاديمية') }}"
                                    data-confirm-btn="{{ __('نعم، احذف الشهادة') }}"
                                    data-confirm-danger="true"
                                    class="self-end sm:self-auto"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/60 transition-colors cursor-pointer" title="{{ __('حذف الشهادة') }}">
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- TAB 5: PAYMENT RECEIPTS --}}
        <div x-show="currentTab === 'receipts'" x-cloak class="flex flex-col gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('إيصالات الدفع والتحويلات البنكية') }}</h3>
                <p class="text-xs text-slate-500">{{ __('معاينة إيصالات السداد المرفوعة من الطالب وحالتها ومراجعتها') }}</p>
            </div>

            @if($user->paymentReceipts->isEmpty())
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-12 text-center shadow-2xs">
                    <div class="size-16 rounded-2xl bg-teal-50 dark:bg-slate-800 text-teal-600 mx-auto flex items-center justify-center mb-3">
                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('لا توجد إيصالات سداد مسجلة لهذا الطالب') }}</h4>
                    <p class="text-xs text-slate-400 mt-1">{{ __('عند إرفاق الطالب لإيصال تحويل فودافون كاش أو إنستاباي، ستظهر بياناته وصورة الإيصال هنا.') }}</p>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl overflow-hidden shadow-2xs">
                    <div class="overflow-x-auto -mx-1 sm:mx-0">
                        <table class="w-full text-start border-collapse text-xs min-w-[760px]">
                            <thead>
                                <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-[#DEE8F7] dark:border-slate-800 text-slate-500 font-bold">
                                    <th class="py-3.5 px-5 text-start">{{ __('الكورس') }}</th>
                                    <th class="py-3.5 px-4 text-start">{{ __('طريقة الدفع') }}</th>
                                    <th class="py-3.5 px-4 text-start">{{ __('المبلغ') }}</th>
                                    <th class="py-3.5 px-4 text-start">{{ __('بيانات المحوّل') }}</th>
                                    <th class="py-3.5 px-4 text-center">{{ __('صورة الإيصال') }}</th>
                                    <th class="py-3.5 px-4 text-center">{{ __('الحالة') }}</th>
                                    <th class="py-3.5 px-4 text-start">{{ __('المراجعة') }}</th>
                                    <th class="py-3.5 px-5 text-center">{{ __('الإجراء') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#DEE8F7]/70 dark:divide-slate-800">
                                @foreach($user->paymentReceipts as $receipt)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/70 transition-colors">
                                        <td class="py-4 px-5 text-start font-bold text-slate-900 dark:text-white">
                                            {{ $receipt->course?->title ?? '—' }}
                                        </td>
                                        <td class="py-4 px-4 text-start font-medium">
                                            {{ $receipt->paymentMethodLabel() }}
                                        </td>
                                        <td class="py-4 px-4 text-start font-mono font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                            {{ number_format((float)$receipt->amount, 2) }} {{ $currencySymbol ?? platform_currency() }}
                                        </td>
                                        <td class="py-4 px-4 text-start">
                                            <div class="font-medium text-slate-700 dark:text-slate-300">{{ $receipt->sender_name ?? '—' }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono">{{ $receipt->sender_account ?? '' }} {{ $receipt->transaction_reference ? '• #'.$receipt->transaction_reference : '' }}</div>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if($receipt->receipt_image_path)
                                                <button 
                                                    type="button" 
                                                    onclick="openReceiptPreview('{{ asset('storage/' . $receipt->receipt_image_path) }}')"
                                                    class="inline-block relative group cursor-pointer"
                                                >
                                                    <img src="{{ asset('storage/' . $receipt->receipt_image_path) }}" alt="Receipt" class="size-10 rounded-lg object-cover border border-slate-200 dark:border-slate-700 shadow-2xs group-hover:scale-105 transition-transform">
                                                </button>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if($receipt->isApproved())
                                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 whitespace-nowrap inline-block">
                                                    ✓ {{ __('معتمد ومفعّل') }}
                                                </span>
                                            @elseif($receipt->isRejected())
                                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800 whitespace-nowrap inline-block" title="{{ $receipt->rejection_reason }}">
                                                    ✕ {{ __('مرفوض') }}
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800 whitespace-nowrap inline-block">
                                                    ⏳ {{ __('قيد المراجعة') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-start text-[11px] text-slate-500 whitespace-nowrap">
                                            @if($receipt->reviewer)
                                                <div class="font-medium text-slate-700 dark:text-slate-300">{{ $receipt->reviewer->name }}</div>
                                                <div class="text-[10px] text-slate-400">{{ $receipt->reviewed_at?->diffForHumans() }}</div>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-5 text-center">
                                            @if($receipt->isPending())
                                                <div class="flex items-center justify-center gap-1.5 flex-nowrap">
                                                    <form method="POST" action="{{ route('admin.receipts.approve', $receipt->id) }}">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition-colors cursor-pointer whitespace-nowrap">
                                                            {{ __('اعتماد') }}
                                                        </button>
                                                    </form>

                                                    <button 
                                                        type="button" 
                                                        onclick="openRejectReceiptModal({{ $receipt->id }})"
                                                        class="px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 transition-colors cursor-pointer whitespace-nowrap"
                                                    >
                                                        {{ __('رفض') }}
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-slate-400 text-xs">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Danger Zone: Permanent Account Deletion --}}
        <div class="rounded-3xl border border-rose-200 dark:border-rose-950/80 bg-rose-50/50 dark:bg-rose-950/20 p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="size-11 rounded-2xl bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 shadow-xs border border-rose-200 dark:border-rose-900">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="flex flex-col text-start">
                    <h3 class="font-bold text-base text-rose-900 dark:text-rose-300">
                        {{ __('منطقة الخطر: حذف حساب الطالب نهائياً') }}
                    </h3>
                    <p class="text-xs text-rose-700/80 dark:text-rose-400/80 mt-0.5 max-w-2xl leading-relaxed">
                        {{ __('سيؤدي حذف الحساب إلى إزالة جميع بيانات الطالب بما في ذلك التسجيلات والشهادات والمهام نهائياً وبشكل لا يمكن التراجع عنه. يتطلب تأكيد العملية إدخال كلمة مرور المشرف.') }}
                    </p>
                </div>
            </div>

            <button
                type="button"
                onclick="openDeleteStudentModal('{{ $user->id }}', @js($user->name), @js($user->email), '{{ route('admin.students.destroy', $user) }}')"
                class="h-11 px-5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-xs transition-colors shrink-0 flex items-center gap-2 cursor-pointer"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>{{ __('حذف الحساب نهائياً') }}</span>
            </button>
        </div>
    </div>

    {{-- MODALS CONTAINER --}}
    <x-slot:modals>
        {{-- 1. EDIT STUDENT PROFILE MODAL --}}
        <div id="modal-edit-student" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity cursor-pointer" onclick="closeModal('modal-edit-student')"></div>
            <div class="modal-content relative bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-5 sm:p-7 border border-[#DEE8F7] dark:border-slate-800 shadow-2xl z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ __('تعديل بيانات الطالب') }}</h3>
                    <button type="button" onclick="closeModal('modal-edit-student')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.students.update', $user->id) }}" class="mt-4 flex flex-col gap-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('اسم الطالب رباعياً') }} *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('البريد الإلكتروني') }} *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383] font-mono">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('رقم الهاتف / واتساب') }}</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383] font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('حالة الحساب') }} *</label>
                            <select name="status" required class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                                <option value="active" @selected(old('status', $user->status) === 'active')>{{ __('نشط ومفعل') }}</option>
                                <option value="pending" @selected(old('status', $user->status) === 'pending')>{{ __('بانتظار الموافقة') }}</option>
                                <option value="suspended" @selected(old('status', $user->status) === 'suspended')>{{ __('معلق / ملغي الصلاحية') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('الدولة') }}</label>
                            <input type="text" name="country" value="{{ old('country', $user->country) }}" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('المدينة') }}</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('العنوان التفصيلي') }}</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                        <button type="button" onclick="closeModal('modal-edit-student')" class="px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="px-5 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs cursor-pointer shadow-sm">
                            {{ __('حفظ التعديلات') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 2. CHANGE PASSWORD MODAL --}}
        <div id="modal-change-password" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity cursor-pointer" onclick="closeModal('modal-change-password')"></div>
            <div class="modal-content relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-5 sm:p-7 border border-[#DEE8F7] dark:border-slate-800 shadow-2xl z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ __('تعيين كلمة مرور جديدة للطالب') }}</h3>
                    <button type="button" onclick="closeModal('modal-change-password')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.students.password.update', $user->id) }}" class="mt-4 flex flex-col gap-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('كلمة المرور الجديدة') }} *</label>
                        <input type="password" name="password" required minlength="6" placeholder="••••••••" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                        <span class="text-[11px] text-slate-400 mt-1 block">{{ __('يجب ألا تقل عن 6 خانات. سيتمكن الطالب من تسجيل الدخول بها فوراً.') }}</span>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                        <button type="button" onclick="closeModal('modal-change-password')" class="px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="px-5 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs cursor-pointer shadow-sm">
                            {{ __('تحديث كلمة المرور') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 3. ENROLL COURSE MODAL --}}
        <div id="modal-enroll-course" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity cursor-pointer" onclick="closeModal('modal-enroll-course')"></div>
            <div class="modal-content relative bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-5 sm:p-7 border border-[#DEE8F7] dark:border-slate-800 shadow-2xl z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ __('منح وتفعيل كورس للطالب') }}</h3>
                    <button type="button" onclick="closeModal('modal-enroll-course')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.students.enroll', $user->id) }}" class="mt-4 flex flex-col gap-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('اختر الكورس الأكاديمي') }} *</label>
                        <select name="course_id" required class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                            <option value="">{{ __('— اختر الكورس —') }}</option>
                            @foreach($allCourses as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->title }} ({{ $c->access_duration_days }} {{ __('يوم') }}) - {{ number_format($c->price, 0) }} {{ $currencySymbol ?? platform_currency() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('مدة الصلاحية بالأيام') }}</label>
                            <input type="number" name="duration_days" min="1" max="3650" placeholder="365" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                            <span class="text-[10px] text-slate-400 mt-1 block">{{ __('اتركه فارغاً لاستخدام مدة الكورس الافتراضية') }}</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('أو حدد تاريخ انتهاء محدد') }}</label>
                            <input type="date" name="access_expires_at" min="{{ now()->addDay()->format('Y-m-d') }}" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                        <button type="button" onclick="closeModal('modal-enroll-course')" class="px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="px-5 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs cursor-pointer shadow-sm">
                            {{ __('تأكيد ومنح الصلاحية') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 4. EDIT ENROLLMENT MODAL --}}
        <div id="modal-edit-enrollment" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity cursor-pointer" onclick="closeModal('modal-edit-enrollment')"></div>
            <div class="modal-content relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-5 sm:p-7 border border-[#DEE8F7] dark:border-slate-800 shadow-2xl z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ __('تعديل وتمديد اشتراك الكورس') }}</h3>
                    <button type="button" onclick="closeModal('modal-edit-enrollment')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="form-edit-enrollment" method="POST" action="" class="mt-4 flex flex-col gap-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('الكورس') }}</label>
                        <input type="text" id="edit-enrollment-course-name" readonly class="w-full h-10 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('تاريخ انتهاء الصلاحية الجديد') }} *</label>
                        <input type="date" id="edit-enrollment-expires-at" name="access_expires_at" required class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('حالة الاشتراك') }} *</label>
                        <select id="edit-enrollment-status" name="status" required class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                            <option value="active">{{ __('نشط (Active)') }}</option>
                            <option value="expired">{{ __('منتهي الصلاحية (Expired)') }}</option>
                            <option value="suspended">{{ __('معلق (Suspended)') }}</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                        <button type="button" onclick="closeModal('modal-edit-enrollment')" class="px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="px-5 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs cursor-pointer shadow-sm">
                            {{ __('حفظ التعديلات') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 5. ISSUE CERTIFICATE MODAL --}}
        <div id="modal-issue-certificate" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity cursor-pointer" onclick="closeModal('modal-issue-certificate')"></div>
            <div class="modal-content relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-5 sm:p-7 border border-[#DEE8F7] dark:border-slate-800 shadow-2xl z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ __('رفع وتوثيق شهادة للطالب') }}</h3>
                    <button type="button" onclick="closeModal('modal-issue-certificate')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.students.certificates.store', $user->id) }}" enctype="multipart/form-data" class="mt-4 flex flex-col gap-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('اختر الكورس الأكاديمي') }} *</label>
                        <select id="cert-course-id" name="course_id" required class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                            <option value="">{{ __('— اختر الكورس —') }}</option>
                            @foreach($user->courses as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->title }} ({{ __('التقدم الحقيقي: :pct%', ['pct' => $enrolledCourseProgress[$c->id] ?? 0]) }})
                                </option>
                            @endforeach
                            @if($user->courses->isEmpty())
                                @foreach($allCourses as $c)
                                    <option value="{{ $c->id }}">{{ $c->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('ملف الشهادة المرفق (اختياري - صورة أو PDF)') }}</label>
                        <input type="file" name="certificate_file" accept=".pdf,image/png,image/jpeg,image/webp" class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#012383] file:text-white hover:file:bg-[#0B2A8F] cursor-pointer">
                        <span class="text-[10px] text-slate-400 mt-1 block">{{ __('اختياري: يمكنك رفع ملف شهادة مصمم (PDF أو صورة)، أو تركه فارغاً للاعتماد على النموذج الرقمي المعتمد للمنصة برمز QR.') }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('تاريخ إصدار الشهادة') }}</label>
                        <input type="date" name="issued_at" value="{{ now()->format('Y-m-d') }}" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('نسبة الإنجاز الموثقة بالشهادة (%)') }}</label>
                        <input type="number" id="cert-completion-pct" name="completion_percentage" min="50" max="100" step="0.1" placeholder="100.0" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                        <span class="text-[10px] text-slate-400 mt-1 block">{{ __('اتركه فارغاً لاعتماد 100% أو نسبة المشاهدة الحالية تلقائياً.') }}</span>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                        <button type="button" onclick="closeModal('modal-issue-certificate')" class="px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="px-5 h-10 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs cursor-pointer shadow-sm">
                            {{ __('رفع وتوثيق الشهادة') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 6. GRADE TASK MODAL --}}
        <div id="modal-grade-task" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity cursor-pointer" onclick="closeModal('modal-grade-task')"></div>
            <div class="modal-content relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-5 sm:p-7 border border-[#DEE8F7] dark:border-slate-800 shadow-2xl z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ __('تقييم ورصد درجة المهمة التدريبية') }}</h3>
                    <button type="button" onclick="closeModal('modal-grade-task')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="form-grade-task" method="POST" action="" class="mt-4 flex flex-col gap-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('المهمة التدريبية') }}</label>
                        <input type="text" id="grade-task-title" readonly class="w-full h-10 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('الدرجة المستحقة') }} *</label>
                        <div class="flex items-center gap-2">
                            <input type="number" id="grade-task-score" name="score" required min="0" max="100" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383]">
                            <span class="text-xs font-bold text-slate-400 shrink-0" id="grade-task-max">/ 100</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('التغذية الراجعة والملاحظات والتقييم') }}</label>
                        <textarea id="grade-task-feedback" name="clinical_feedback" rows="4" placeholder="{{ __('اكتب توجيهاتك للطالب بناء على التسجيل الصوتي...') }}" class="w-full p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-[#012383] resize-none"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                        <button type="button" onclick="closeModal('modal-grade-task')" class="px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="px-5 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs cursor-pointer shadow-sm">
                            {{ __('اعتماد ورصد الدرجة') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 7. REJECT RECEIPT MODAL --}}
        <div id="modal-reject-receipt" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity cursor-pointer" onclick="closeModal('modal-reject-receipt')"></div>
            <div class="modal-content relative bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-5 sm:p-7 border border-[#DEE8F7] dark:border-slate-800 shadow-2xl z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold text-base text-rose-600">{{ __('رفض إيصال السداد') }}</h3>
                    <button type="button" onclick="closeModal('modal-reject-receipt')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="form-reject-receipt" method="POST" action="" class="mt-4 flex flex-col gap-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('سبب الرفض الموجه للطالب') }} *</label>
                        <textarea name="rejection_reason" required rows="3" placeholder="{{ __('مثال: رقم العملية غير مطابق، صورة الإيصال غير واضحة...') }}" class="w-full p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-rose-500 resize-none"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                        <button type="button" onclick="closeModal('modal-reject-receipt')" class="px-4 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs cursor-pointer">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="px-5 h-10 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs cursor-pointer shadow-sm">
                            {{ __('تأكيد الرفض') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 8. RECEIPT PREVIEW MODAL --}}
        <div id="modal-receipt-preview" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden">
            <div class="modal-overlay fixed inset-0 bg-slate-900/80 backdrop-blur-xs transition-opacity cursor-pointer" onclick="closeModal('modal-receipt-preview')"></div>
            <div class="modal-content relative bg-white dark:bg-slate-900 rounded-3xl max-w-2xl w-full p-4 border border-[#DEE8F7] dark:border-slate-800 shadow-2xl z-10 flex flex-col items-center max-h-[90vh] overflow-y-auto">
                <button type="button" onclick="closeModal('modal-receipt-preview')" class="absolute top-4 end-4 p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-full bg-slate-100 dark:bg-slate-800 cursor-pointer">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="mt-8 mb-4 max-h-[75vh] overflow-auto rounded-xl">
                    <img id="receipt-preview-img" src="" alt="Receipt Large Preview" class="max-w-full max-h-[70vh] rounded-xl object-contain mx-auto shadow-md">
                </div>
            </div>
        </div>

        {{-- 9. DELETE STUDENT MODAL (CONFIRMATION WITH ADMIN PASSWORD) --}}
        <x-admin::delete-student-modal :user="$user" />
    </x-slot:modals>

    <script>
        function openModal(id) {
            const m = document.getElementById(id);
            if (m) {
                m.classList.remove('hidden', 'modal-hidden');
                m.style.display = 'flex';
                document.documentElement.classList.add('modal-open');
                document.body.classList.add('modal-open');
                const content = m.querySelector('.modal-content') || m;
                if (content && typeof content.scrollTop !== 'undefined') {
                    content.scrollTop = 0;
                }
            }
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if (m) {
                m.classList.add('hidden', 'modal-hidden');
                m.style.display = 'none';
                const anyOpen = document.querySelectorAll('.modal-backdrop:not(.modal-hidden):not(.hidden), [id^="modal-"].fixed:not(.modal-hidden):not(.hidden)');
                if (anyOpen.length === 0) {
                    document.documentElement.classList.remove('modal-open');
                    document.body.classList.remove('modal-open');
                }
            }
        }

        function openEditEnrollmentModal(courseId, courseTitle, expiresAt, status) {
            const form = document.getElementById('form-edit-enrollment');
            form.action = `/admin/students/{{ $user->id }}/enrollments/${courseId}`;
            document.getElementById('edit-enrollment-course-name').value = courseTitle;
            document.getElementById('edit-enrollment-expires-at').value = expiresAt;
            document.getElementById('edit-enrollment-status').value = status;
            openModal('modal-edit-enrollment');
        }

        function openCourseCertificateModal(courseId, courseTitle, progressPct) {
            const select = document.getElementById('cert-course-id');
            if (select) {
                select.value = courseId;
            }
            const inputPct = document.getElementById('cert-completion-pct');
            if (inputPct) {
                inputPct.value = progressPct > 0 ? progressPct : 100;
            }
            openModal('modal-issue-certificate');
        }

        function openGradeModal(submissionId, taskTitle, score, feedback, maxScore) {
            const form = document.getElementById('form-grade-task');
            form.action = `/admin/tasks/${submissionId}/grade`;
            document.getElementById('grade-task-title').value = taskTitle;
            document.getElementById('grade-task-score').value = score || 0;
            document.getElementById('grade-task-score').max = maxScore || 100;
            document.getElementById('grade-task-max').innerText = `/ ${maxScore || 100}`;
            document.getElementById('grade-task-feedback').value = feedback || '';
            openModal('modal-grade-task');
        }

        function openRejectReceiptModal(receiptId) {
            const form = document.getElementById('form-reject-receipt');
            form.action = `/admin/receipts/${receiptId}/reject`;
            openModal('modal-reject-receipt');
        }

        function openReceiptPreview(imgUrl) {
            const img = document.getElementById('receipt-preview-img');
            if (img) {
                img.src = imgUrl;
                openModal('modal-receipt-preview');
            }
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.modal-backdrop:not(.modal-hidden)');
                openModals.forEach(m => closeModal(m.id));
            }
        });
    </script>
</x-admin-layout::admin>
