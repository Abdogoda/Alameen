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
<body class="bg-gradient-to-br from-slate-900 via-[#011B65] to-[#012383] text-slate-100 min-h-screen flex flex-col items-center justify-center p-4 selection:bg-[#F2AE2E] selection:text-slate-900 font-['Cairo']">

    <!-- Language Switcher in Corner -->
    <div class="fixed top-6 end-6 z-50">
        <x-admin::language-switcher />
    </div>

    <!-- Background Ambient Glows -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -end-40 w-96 h-96 rounded-full bg-[#0B2A8F]/40 blur-3xl"></div>
        <div class="absolute -bottom-40 -start-40 w-96 h-96 rounded-full bg-[#F2AE2E]/15 blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-blue-600/10 blur-[120px]"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-md relative z-10 my-auto">
        
        <!-- Header Branding -->
        <div class="flex flex-col items-center text-center mb-8">
            <div class="size-16 rounded-2xl bg-white border border-white/30 p-2.5 shadow-2xl flex items-center justify-center mb-4 transition-transform hover:scale-105 duration-300">
                <img src="/assets/images/logo.png" alt="منصة الأمين" class="size-full object-contain" />
            </div>

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F2AE2E]/20 border border-[#F2AE2E]/40 text-[#F2AE2E] text-xs font-bold mb-2 tracking-wide">
                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>{{ __('بوابة الإدارة المركزية والتحكم') }}</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-white font-['Baloo_Bhaijaan_2'] tracking-tight">
                {{ __('منصة الأمين') }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xs">
                {{ __('تسجيل الدخول المخصص للمشرفين، المحاضرين، وإدارة المنصة') }}
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-3xl p-6 sm:p-8 shadow-[0_20px_50px_rgba(0,0,0,0.35)]">
            
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-500/20 border border-rose-500/30 text-rose-200 text-sm">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>{{ __('تنبيه تحقق') }}</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-200/90">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="admin_email" class="block text-xs font-bold text-slate-200 mb-1.5">
                        {{ __('البريد الإلكتروني للإدارة') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                        <input
                            type="email"
                            id="admin_email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="admin@alameen.edu.sa"
                            class="w-full ps-10 pe-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#F2AE2E] focus:border-transparent transition-all"
                        />
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="admin_password" class="block text-xs font-bold text-slate-200 mb-1.5">
                        {{ __('كلمة المرور') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input
                            type="password"
                            id="admin_password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full ps-10 pe-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#F2AE2E] focus:border-transparent transition-all"
                        />
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-slate-300 hover:text-white">
                        <input type="checkbox" name="remember" class="rounded border-white/30 text-[#F2AE2E] focus:ring-[#F2AE2E] bg-white/10 size-4">
                        <span>{{ __('تذكر جلستي على هذا الجهاز') }}</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3.5 px-4 rounded-xl font-bold text-sm bg-gradient-to-r from-[#F2AE2E] to-amber-500 hover:from-amber-400 hover:to-[#F2AE2E] text-slate-950 shadow-lg shadow-amber-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 cursor-pointer"
                >
                    <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>{{ __('دخول لوحة الإدارة') }}</span>
                </button>
            </form>

            <!-- Separation / Student Redirect Footer -->
            <div class="mt-8 pt-6 border-t border-white/10 text-center">
                <p class="text-xs text-slate-300">
                    {{ __('هل أنت طالب وتبحث عن دروسك؟') }}
                </p>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 mt-2 text-xs font-bold text-[#F2AE2E] hover:text-amber-300 transition-colors">
                    <span>{{ __('الانتقال إلى بوابة دخول الطلاب') }}</span>
                    <span class="rtl:rotate-180">→</span>
                </a>
            </div>

        </div>

        <!-- Footer Notice -->
        <div class="text-center mt-6 text-[11px] text-slate-400">
            <p>{{ __('منصة الأمين التعليمية • نظام إدارة المحتوى والتحكم') }}</p>
        </div>

    </div>

</body>
</html>
