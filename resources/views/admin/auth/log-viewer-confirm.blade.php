<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('تأكيد كلمة المرور للوصول إلى سجلات النظام | :name', ['name' => $siteName ?? 'منصة الأمين']) }}</title>

    @include('partials.head')
</head>
<body class="bg-[#F8FAFC] dark:bg-[#070C18] min-h-screen text-[#1E293B] dark:text-slate-100 flex flex-col justify-center items-center px-4 py-8 sm:px-6 lg:px-8 font-['Cairo'] antialiased relative selection:bg-[#0B2A8F]/15 selection:text-[#0B2A8F] overflow-x-hidden">
    <!-- Top-Level Pre-Rendered Themed Loader -->
    @persist('alameen-loader')
    <x-alameen-loader />
    @endpersist

    <!-- Floating Top Toolbar (Theme, Language, Dashboard) -->
    <div class="fixed top-4 sm:top-6 end-4 sm:end-8 z-30 flex items-center gap-2 p-1.5 rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-sm transition-all">
        <x-admin::theme-switcher variant="icon" />
        <div class="w-px h-5 bg-slate-200 dark:bg-slate-800"></div>
        <x-admin::language-switcher variant="pill" class="border-0 bg-transparent shadow-none p-0" />
        <div class="w-px h-5 bg-slate-200 dark:bg-slate-800"></div>
        <a
            href="{{ route('admin.dashboard') }}"
            class="size-8 sm:size-9 rounded-xl bg-slate-100/80 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:text-[#012383] dark:hover:text-blue-400 hover:bg-white dark:hover:bg-slate-700 transition-all cursor-pointer group"
            title="{{ __('العودة إلى لوحة التحكم') }}"
        >
            <svg class="size-4 rtl:rotate-180 group-hover:-translate-x-0.5 rtl:group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    <!-- Architectural Background: Dot Grid & Luminous Ambient Radial Meshes -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <!-- Blueprint Dot Matrix -->
        <div class="absolute inset-0 bg-[radial-gradient(#0B2A8F_1px,transparent_1px)] [background-size:26px_26px] opacity-[0.035] dark:opacity-[0.07]"></div>
        <!-- Top Center Amber/Red Glow for Protected Area -->
        <div class="absolute -top-40 start-1/2 -translate-x-1/2 size-[620px] rounded-full bg-gradient-to-b from-amber-500/15 via-rose-500/10 to-transparent blur-3xl pointer-events-none"></div>
        <!-- Corner Ambient Blobs -->
        <div class="absolute -top-24 -end-24 size-96 rounded-full bg-blue-100/40 dark:bg-blue-900/15 blur-3xl"></div>
        <div class="absolute -bottom-24 -start-24 size-96 rounded-full bg-indigo-100/35 dark:bg-indigo-950/20 blur-3xl"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-md relative z-10 my-auto py-10 px-1 sm:px-0 alameen-content-wrap" style="max-width: 460px;">
        
        <!-- Header Branding & Security Icon -->
        <div class="flex flex-col items-center text-center mb-6 sm:mb-7">
            <!-- Pulsing Security Badge -->
            <div class="relative group mb-4">
                <div class="absolute -inset-2 rounded-3xl bg-gradient-to-r from-amber-500/20 via-rose-500/20 to-blue-500/20 blur-lg opacity-85 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative size-16 sm:size-18 rounded-2xl bg-white dark:bg-slate-800 border border-amber-300/80 dark:border-amber-500/40 p-3 shadow-xl shadow-amber-950/5 flex items-center justify-center transition-all duration-300 group-hover:scale-105 ring-4 ring-amber-500/10 dark:ring-amber-400/15">
                    <svg class="size-8 sm:size-9 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight font-['Baloo_Bhaijaan_2'] bg-gradient-to-r from-slate-900 via-[#012383] to-slate-900 dark:from-white dark:via-blue-200 dark:to-slate-200 bg-clip-text text-transparent mb-2">
                {{ __('تأكيد هوية المسؤول') }}
            </h1>

            <!-- Security Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50/90 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 text-xs font-bold tracking-wide shadow-xs">
                <span class="size-2 rounded-full bg-amber-500 animate-ping"></span>
                <svg class="size-3.5 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ __('منطقة محمية: سجلات النظام (Log Viewer)') }}</span>
            </div>

            <p class="mt-3 text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-sm leading-relaxed">
                {{ __('تحتوي سجلات النظام على تفاصيل تقنية وأخطاء برمجية حساسة. يرجى إدخال كلمة مرور حسابك للمتابعة.') }}
            </p>
        </div>

        <!-- Luxury Glass Confirmation Card -->
        <div class="relative bg-white/95 dark:bg-slate-900/90 backdrop-blur-2xl border border-slate-200/90 dark:border-slate-800/90 rounded-3xl p-6 sm:p-8 shadow-[0_20px_50px_-12px_rgba(1,35,131,0.09),0_0_0_1px_rgba(1,35,131,0.03)] dark:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)] overflow-hidden">
            <!-- Top Interior Luminous Edge -->
            <div class="absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-amber-500/40 dark:via-amber-400/40 to-transparent"></div>

            <!-- Current Authenticated Admin Identity Snapshot -->
            <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/60 mb-6">
                <div class="size-11 rounded-xl bg-gradient-to-br from-[#012383] to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-xs shrink-0 overflow-hidden">
                    @if ($admin->avatar)
                        <img src="{{ $admin->avatar }}" alt="{{ $admin->name }}" class="size-full object-cover">
                    @else
                        <span>{{ mb_substr($admin->name, 0, 1) }}</span>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $admin->name }}</h3>
                        <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold bg-blue-100 dark:bg-blue-950/60 text-[#012383] dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/50">
                            {{ $admin->role?->name ?? __('مسؤول') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate dir-ltr text-start">{{ $admin->email }}</p>
                </div>
            </div>

            <!-- Error Notification Banner -->
            @if ($errors->has('password'))
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2.5 animate-shake">
                    <svg class="size-4.5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $errors->first('password') }}</span>
                </div>
            @endif

            <!-- Confirmation Form -->
            <form method="POST" action="{{ route('admin.log-viewer.confirm.store') }}" class="space-y-4">
                @csrf

                <!-- Password Field with Show/Hide -->
                <div class="space-y-1.5" x-data="{ show: false }">
                    <label for="admin_confirm_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                        {{ __('أدخل كلمة مرور حسابك للمتابعة') }}
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0B2A8F] dark:group-focus-within:text-blue-400 transition-colors">
                            <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            :type="show ? 'text' : 'password'"
                            id="admin_confirm_password"
                            name="password"
                            required
                            autofocus
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full h-12 ps-10 pe-11 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-800 border {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 dark:border-slate-700/80 focus:border-[#0B2A8F] dark:focus:border-blue-400 focus:ring-4 focus:ring-[#0B2A8F]/10 dark:focus:ring-blue-400/10' }} text-[#1E293B] dark:text-slate-100 placeholder:text-slate-400 text-sm font-medium outline-none transition-all shadow-xs"
                        />
                        <button
                            type="button"
                            @click="show = !show"
                            class="!absolute end-2 top-1/2 -translate-y-1/2 size-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-[#012383] dark:hover:text-blue-400 hover:bg-slate-100 dark:hover:bg-slate-700/60 transition-all cursor-pointer z-10"
                            aria-label="Toggle password visibility"
                        >
                            <svg x-show="!show" class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full h-12 rounded-xl bg-gradient-to-r from-[#012383] via-[#0B2A8F] to-[#1E3A8A] hover:from-[#0B2A8F] hover:via-[#1E3A8A] hover:to-[#2563EB] text-white font-['Cairo'] font-bold text-sm shadow-[0_10px_25px_-5px_rgba(1,35,131,0.35)] hover:shadow-[0_15px_30px_-5px_rgba(1,35,131,0.45)] hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.99] transition-all duration-200 cursor-pointer flex items-center justify-center gap-2.5 relative overflow-hidden group mt-3"
                >
                    <svg class="size-4.5 text-blue-200 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ __('تأكيد كلمة المرور وفتح السجلات') }}</span>
                </button>

                <!-- Cancel & Return Link -->
                <div class="text-center pt-2">
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors inline-flex items-center gap-1.5"
                    >
                        <svg class="size-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>{{ __('إلغاء والعودة إلى لوحة التحكم') }}</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer Note -->
        <p class="text-center text-xs text-slate-400 dark:text-slate-500 mt-6">
            {{ __('جلسة التحقق مؤقتة وتنقضي تلقائياً بعد انتهاء مهلة الأمان.') }}
        </p>
    </div>
</body>
</html>
