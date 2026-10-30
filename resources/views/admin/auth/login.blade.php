<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('تسجيل دخول المسؤولين | منصة الأمين') }}</title>

    @include('partials.head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCFF] dark:bg-[#0B1120] min-h-screen text-[#1E293B] dark:text-slate-100 flex flex-col justify-center items-center px-3.5 py-6 sm:px-6 lg:px-10 font-['Cairo'] antialiased relative selection:bg-[#0B2A8F]/10 selection:text-[#0B2A8F] overflow-x-hidden">

    <!-- Language & Theme Switcher & Home Return in Corner -->
    <div class="fixed top-3 sm:top-6 end-3 sm:end-8 z-30 flex items-center gap-2 sm:gap-2.5">
        <x-admin::theme-switcher variant="icon" />
        <x-admin::language-switcher variant="pill" />

        <a
            href="{{ route('home') }}"
            class="size-9 sm:size-10 rounded-full bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 shadow-xs flex items-center justify-center text-[#475569] dark:text-slate-300 hover:text-[#012383] dark:hover:text-blue-400 hover:border-[#012383] transition-all cursor-pointer"
            title="{{ __('العودة إلى الرئيسية') }}"
        >
            <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    <!-- Soft Decorative Ambient Mesh Blobs (Light Aesthetic) -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-32 -end-32 size-96 rounded-full bg-blue-100/40 dark:bg-blue-900/10 blur-3xl"></div>
        <div class="absolute -bottom-32 -start-32 size-96 rounded-full bg-amber-100/35 dark:bg-amber-900/10 blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 size-[650px] rounded-full bg-indigo-50/50 dark:bg-indigo-950/20 blur-[130px]"></div>
    </div>

    <!-- Main Container with dedicated mobile horizontal padding and top clearance -->
    <div class="w-full max-w-md relative z-10 my-auto pt-16 pb-8 sm:py-10 px-1 sm:px-0">
        
        <!-- Header Branding -->
        <div class="flex flex-col items-center text-center mb-6 sm:mb-7">
            <!-- Polished White Logo Badge -->
            <div class="size-16 rounded-2xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 p-2.5 shadow-md shadow-blue-900/5 flex items-center justify-center mb-3.5 transition-transform hover:scale-105 duration-300">
                <img src="/assets/images/logo.png" alt="منصة الأمين" class="size-full object-contain pointer-events-none" />
            </div>

            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50 dark:bg-amber-950/40 border border-amber-200/90 dark:border-amber-800/50 text-amber-900 dark:text-amber-300 text-xs font-bold mb-2.5 tracking-wide shadow-xs">
                <svg class="size-3.5 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>{{ __('بوابة الإدارة المركزية والتحكم') }}</span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-[#012383] dark:text-blue-400 font-['Baloo_Bhaijaan_2'] tracking-tight mb-1">
                {{ __('منصة الأمين') }}
            </h1>
            <p class="text-xs sm:text-sm text-[#475569] dark:text-slate-400 font-medium max-w-sm leading-relaxed px-2">
                {{ __('تسجيل الدخول المخصص للمشرفين، المحاضرين، وإدارة المنصة') }}
            </p>
        </div>

        <!-- Crisp Light Login Card -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-9 shadow-[0_20px_50px_-12px_rgba(1,35,131,0.08),0_1px_3px_rgba(0,0,0,0.02)]">
            
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-sm">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>{{ __('تنبيه تحقق') }}</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-600 dark:text-rose-400 font-semibold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Google Sign In via Firebase for Admins -->
            <div class="mb-5">
                <button
                    type="button"
                    data-firebase-auth="admin"
                    class="w-full py-3.5 px-4 rounded-2xl border border-[#DEE8F7] dark:border-slate-700 hover:border-[#012383]/40 dark:hover:border-blue-400/40 bg-[#FAFCFF] dark:bg-slate-800 hover:bg-white dark:hover:bg-slate-700 text-[#1E293B] dark:text-slate-100 font-bold text-sm shadow-xs transition-all flex items-center justify-center gap-3 cursor-pointer group active:scale-[0.99]"
                    title="{{ __('تسجيل الدخول السريع باستخدام حساب Google للمشرفين') }}"
                >
                    <!-- Google G Logo -->
                    <svg class="size-5 shrink-0 transition-transform group-hover:scale-105" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                        <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.14-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                    </svg>
                    <span>{{ __('تسجيل الدخول عبر Google (Firebase)') }}</span>
                </button>

                <div class="relative my-5">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-[#DEE8F7] dark:border-slate-700"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-3 text-[#475569] dark:text-slate-400 bg-white dark:bg-slate-900 font-semibold">
                            {{ __('أو المتابعة ببيانات الاعتماد') }}
                        </span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4 sm:space-y-5">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="admin_email" class="block text-xs font-bold text-[#012383] dark:text-blue-400 mb-1.5">
                        {{ __('البريد الإلكتروني للإدارة') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-[#0B2A8F] dark:text-blue-400">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="admin_email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="admin@alameen.edu.sa"
                            class="w-full ps-11 pe-4 py-3 sm:py-3.5 rounded-xl bg-[#FAFCFF] dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700 text-[#1E293B] dark:text-slate-100 placeholder-slate-400 text-sm focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F] dark:focus:border-blue-400 transition-all"
                        />
                    </div>
                </div>

                <!-- Password Field with Show/Hide -->
                <div x-data="{ show: false }">
                    <label for="admin_password" class="block text-xs font-bold text-[#012383] dark:text-blue-400 mb-1.5">
                        {{ __('كلمة المرور') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-[#0B2A8F] dark:text-blue-400">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            :type="show ? 'text' : 'password'"
                            id="admin_password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full ps-11 pe-12 py-3 sm:py-3.5 rounded-xl bg-[#FAFCFF] dark:bg-slate-800/60 border border-[#DEE8F7] dark:border-slate-700 text-[#1E293B] dark:text-slate-100 placeholder-slate-400 text-sm focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F] dark:focus:border-blue-400 transition-all"
                        />
                        <button
                            type="button"
                            @click="show = !show"
                            class="!absolute end-3 top-1/2 -translate-y-1/2 size-9 flex items-center justify-center text-slate-400 hover:text-[#012383] dark:hover:text-blue-400 transition-colors cursor-pointer z-10"
                            aria-label="Toggle password visibility"
                        >
                            <svg x-show="!show" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-[#475569] dark:text-slate-300 hover:text-[#012383] dark:hover:text-blue-400 transition-colors font-medium">
                        <input type="checkbox" name="remember" class="rounded border-[#CFDBEA] text-[#0B2A8F] focus:ring-[#0B2A8F] size-4 cursor-pointer">
                        <span>{{ __('تذكر جلستي على هذا الجهاز') }}</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3.5 sm:py-4 px-5 rounded-2xl font-bold text-sm sm:text-base bg-[#012383] hover:bg-[#0B2A8F] text-white shadow-lg shadow-[#012383]/20 hover:shadow-xl hover:shadow-[#012383]/25 active:scale-[0.99] transition-all flex items-center justify-center gap-2.5 cursor-pointer"
                >
                    <svg class="size-5 shrink-0 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>{{ __('دخول لوحة الإدارة') }}</span>
                </button>
            </form>
        </div>

        <!-- Footer Notice -->
        <div class="text-center mt-6 text-xs text-[#94A3B8] font-medium">
            <p>{{ __('منصة الأمين التعليمية • نظام إدارة المحتوى والتحكم المركزي') }}</p>
        </div>

    </div>

    @fluxScripts
</body>
</html>
