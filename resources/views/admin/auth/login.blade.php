<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('تسجيل دخول المسؤولين | :name', ['name' => $siteName ?? 'منصة الأمين']) }}</title>

    @include('partials.head')
    @vite(['resources/js/passkeys.js'])
</head>
<body class="bg-[#F8FAFC] dark:bg-[#070C18] min-h-screen text-[#1E293B] dark:text-slate-100 flex flex-col justify-center items-center px-4 py-8 sm:px-6 lg:px-8 font-['Cairo'] antialiased relative selection:bg-[#0B2A8F]/15 selection:text-[#0B2A8F] overflow-x-hidden">
    <!-- Top-Level Pre-Rendered Themed Loader -->
    @persist('alameen-loader')
    <x-alameen-loader />
    @endpersist

    <!-- Floating Top Toolbar (Theme, Language, Home) -->
    <div class="fixed top-4 sm:top-6 end-4 sm:end-8 z-30 flex items-center gap-2 p-1.5 rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-sm transition-all">
        <x-admin::theme-switcher variant="icon" />
        <div class="w-px h-5 bg-slate-200 dark:bg-slate-800"></div>
        <x-admin::language-switcher variant="pill" class="border-0 bg-transparent shadow-none p-0" />
        <div class="w-px h-5 bg-slate-200 dark:bg-slate-800"></div>
        <a
            href="{{ route('home') }}"
            class="size-8 sm:size-9 rounded-xl bg-slate-100/80 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:text-[#012383] dark:hover:text-blue-400 hover:bg-white dark:hover:bg-slate-700 transition-all cursor-pointer group"
            title="{{ __('العودة إلى الرئيسية') }}"
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
        <!-- Top Center Glow -->
        <div class="absolute -top-40 start-1/2 -translate-x-1/2 size-[620px] rounded-full bg-gradient-to-b from-blue-400/15 via-indigo-400/10 to-transparent blur-3xl pointer-events-none"></div>
        <!-- Corner Ambient Blobs -->
        <div class="absolute -top-24 -end-24 size-96 rounded-full bg-blue-100/40 dark:bg-blue-900/15 blur-3xl"></div>
        <div class="absolute -bottom-24 -start-24 size-96 rounded-full bg-indigo-100/35 dark:bg-indigo-950/20 blur-3xl"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-md relative z-10 my-auto py-10 px-1 sm:px-0 alameen-content-wrap" style="max-width: 440px;">
        
        <!-- Header Branding -->
        <div class="flex flex-col items-center text-center mb-6 sm:mb-7">
            <!-- Polished Logo Badge with Aura Ring -->
            <div class="relative group mb-4">
                <div class="absolute -inset-2 rounded-3xl bg-gradient-to-r from-blue-600/15 via-indigo-500/15 to-blue-400/15 blur-lg opacity-75 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative size-16 sm:size-18 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/90 dark:border-slate-700/80 p-2.5 shadow-xl shadow-blue-950/5 flex items-center justify-center transition-all duration-300 group-hover:scale-105 group-hover:shadow-2xl ring-4 ring-blue-500/5 dark:ring-blue-400/10">
                    <img src="{{ $siteLogo ?? '/assets/images/logo.png' }}" alt="{{ $siteName ?? 'منصة الأمين' }}" class="size-full object-contain pointer-events-none drop-shadow-xs dark:hidden logo-theme-light" />
                    <img src="{{ $siteLogoWhite ?? '/assets/images/logo-white.png' }}" alt="{{ $siteName ?? 'منصة الأمين' }}" class="size-full object-contain pointer-events-none drop-shadow-xs hidden dark:block logo-theme-dark" />
                </div>
            </div>

            <!-- Title with Gradient Depth -->
            <h1 class="text-2xl sm:text-3xl lg:text-[32px] font-black tracking-tight font-['Baloo_Bhaijaan_2'] bg-gradient-to-r from-[#012383] via-[#0B2A8F] to-[#1E3A8A] dark:from-blue-200 dark:via-blue-100 dark:to-indigo-200 bg-clip-text text-transparent mb-2 sm:mb-2.5">
                {{ $siteName ?? __('منصة الأمين') }}
            </h1>

            <!-- Security & Control Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-gradient-to-r from-blue-50/90 via-indigo-50/70 to-blue-50/90 dark:from-blue-950/50 dark:via-indigo-950/40 dark:to-blue-950/50 border border-blue-200/80 dark:border-blue-800/60 text-[#012383] dark:text-blue-300 text-xs font-bold tracking-wide shadow-xs">
                <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <svg class="size-3.5 text-[#0B2A8F] dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>{{ __('بوابة الإدارة المركزية والتحكم') }}</span>
            </div>
        </div>

        <!-- Luxury Glass Login Card -->
        <div class="relative bg-white/95 dark:bg-slate-900/90 backdrop-blur-2xl border border-slate-200/90 dark:border-slate-800/90 rounded-3xl p-6 sm:p-9 shadow-[0_20px_50px_-12px_rgba(1,35,131,0.09),0_0_0_1px_rgba(1,35,131,0.03)] dark:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)] overflow-hidden">
            <!-- Top Interior Luminous Edge -->
            <div class="absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-blue-500/35 dark:via-blue-400/35 to-transparent"></div>
            
            @if ($errors->any())
                <div class="mb-5 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-sm animate-shake">
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

            <!-- Quick Login Options (Google & Passkey) -->
            <div class="space-y-2.5">
                <!-- Google Sign In for Admins -->
                <button
                    type="button"
                    data-firebase-auth="admin"
                    class="w-full h-12 px-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 bg-white hover:bg-slate-50 dark:bg-slate-800 dark:hover:bg-slate-700/80 text-[#1E293B] dark:text-slate-100 font-bold text-xs sm:text-sm shadow-xs hover:shadow-sm transition-all flex items-center justify-center gap-3 cursor-pointer group active:scale-[0.99]"
                    title="{{ __('تسجيل الدخول السريع باستخدام حساب Google للمشرفين') }}"
                >
                    <!-- Google G Logo -->
                    <svg class="size-5 shrink-0 transition-transform group-hover:scale-105" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                        <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.14-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                    </svg>
                    <span>{{ __('تسجيل الدخول عبر Google') }}</span>
                </button>

                <!-- Passkey Sign In for Admins -->
                <div
                    x-data="{
                        supported: false,
                        loading: false,
                        error: null,
                        updateSupport() {
                            this.supported = Boolean(window.Passkeys?.isSupported());
                        },
                        init() {
                            this.updateSupport();
                            window.addEventListener('passkeys:ready', () => this.updateSupport(), { once: true });
                        },
                        async loginWithPasskey() {
                            this.loading = true;
                            this.error = null;
                            try {
                                const response = await window.Passkeys.verify({
                                    routes: {
                                        options: '{{ route('admin.passkey.login-options') }}',
                                        submit: '{{ route('admin.passkey.login') }}',
                                    },
                                });
                                window.location.href = response.redirect || '{{ route('admin.dashboard') }}';
                            } catch (e) {
                                if (window.translatePasskeyError) {
                                    this.error = window.translatePasskeyError(e);
                                } else {
                                    this.error = e?.message || '{{ __('فشل تسجيل الدخول بمفتاح المرور.') }}';
                                }
                            } finally {
                                this.loading = false;
                            }
                        }
                    }"
                    class="w-full"
                >
                    <button
                        type="button"
                        x-show="supported"
                        @click="loginWithPasskey()"
                        :disabled="loading"
                        class="w-full h-12 px-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-blue-400/60 dark:hover:border-blue-400/60 bg-white hover:bg-blue-50/40 dark:bg-slate-800 dark:hover:bg-slate-700/80 text-[#012383] dark:text-blue-300 font-bold text-xs sm:text-sm shadow-xs hover:shadow-sm transition-all flex items-center justify-center gap-2.5 cursor-pointer group active:scale-[0.99] disabled:opacity-75 disabled:cursor-wait"
                        title="{{ __('تسجيل الدخول السريع عبر مفتاح المرور (Touch ID / Face ID / Windows Hello)') }}"
                    >
                        <svg x-show="!loading" class="size-5 shrink-0 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11a7.96 7.96 0 001.272 4.316m5.748 4.288A19.92 19.92 0 0112 21"/>
                        </svg>
                        <svg x-show="loading" x-cloak class="size-4.5 shrink-0 animate-spin text-[#0B2A8F] dark:text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3.5" fill="none"></circle>
                            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="!loading">{{ __('تسجيل الدخول عبر مفتاح المرور (Passkey)') }}</span>
                        <span x-show="loading" x-cloak>{{ __('جارٍ التحقق البيومتري...') }}</span>
                    </button>

                    <div x-show="error" x-cloak class="mt-2.5 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 text-xs font-semibold text-center" x-text="error"></div>
                </div>
            </div>

            <!-- Divider -->
            <div class="relative my-5">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200 dark:border-slate-800"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-3.5 py-0.5 rounded-full bg-slate-100/90 dark:bg-slate-800/90 border border-slate-200/60 dark:border-slate-700/60 text-[#475569] dark:text-slate-400 font-bold text-[11px]">
                        {{ __('أو المتابعة ببيانات الاعتماد') }}
                    </span>
                </div>
            </div>

            <!-- Credentials Form -->
            <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
                @csrf

                <!-- Email Field -->
                <div class="space-y-1.5">
                    <label for="admin_email" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                        {{ __('البريد الإلكتروني للإدارة') }}
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0B2A8F] dark:group-focus-within:text-blue-400 transition-colors">
                            <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="admin_email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="admin@alameen.edu.sa"
                            class="w-full h-12 ps-10 pe-4 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-800 border border-slate-200 dark:border-slate-700/80 focus:border-[#0B2A8F] dark:focus:border-blue-400 focus:ring-4 focus:ring-[#0B2A8F]/10 dark:focus:ring-blue-400/10 text-[#1E293B] dark:text-slate-100 placeholder:text-slate-400 text-sm font-medium outline-none transition-all shadow-xs"
                        />
                    </div>
                </div>

                <!-- Password Field with Show/Hide -->
                <div class="space-y-1.5" x-data="{ show: false }">
                    <label for="admin_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                        {{ __('كلمة المرور') }}
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0B2A8F] dark:group-focus-within:text-blue-400 transition-colors">
                            <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            :type="show ? 'text' : 'password'"
                            id="admin_password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full h-12 ps-10 pe-11 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-800 border border-slate-200 dark:border-slate-700/80 focus:border-[#0B2A8F] dark:focus:border-blue-400 focus:ring-4 focus:ring-[#0B2A8F]/10 dark:focus:ring-blue-400/10 text-[#1E293B] dark:text-slate-100 placeholder:text-slate-400 text-sm font-medium outline-none transition-all shadow-xs"
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

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600 dark:text-slate-400 hover:text-[#012383] dark:hover:text-blue-300 transition-colors font-semibold group">
                        <input type="checkbox" name="remember" class="size-4 rounded-md border-slate-300 dark:border-slate-600 text-[#012383] focus:ring-[#012383]/20 accent-[#012383] cursor-pointer transition-all">
                        <span>{{ __('تذكر جلستي على هذا الجهاز') }}</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full h-12 sm:h-13 rounded-xl sm:rounded-2xl bg-gradient-to-r from-[#012383] via-[#0B2A8F] to-[#1E3A8A] hover:from-[#0B2A8F] hover:via-[#1E3A8A] hover:to-[#2563EB] text-white font-['Cairo'] font-bold text-sm sm:text-base shadow-[0_10px_25px_-5px_rgba(1,35,131,0.35)] hover:shadow-[0_15px_30px_-5px_rgba(1,35,131,0.45)] hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.99] transition-all duration-200 cursor-pointer flex items-center justify-center gap-2.5 relative overflow-hidden group mt-2"
                    style="color: #ffffff !important;"
                >
                    <!-- Glossy Light Sweep Effect -->
                    <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent pointer-events-none"></div>

                    <svg class="size-4.5 shrink-0 rtl:rotate-180 group-hover:-translate-x-0.5 rtl:group-hover:translate-x-0.5 transition-transform text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span class="text-white font-bold" style="color: #ffffff !important;">{{ __('دخول لوحة الإدارة') }}</span>
                </button>
            </form>
        </div>

        <!-- Footer Security & Platform Notice -->
        <div class="text-center mt-7 space-y-2 text-xs">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100/70 dark:bg-slate-800/60 border border-slate-200/50 dark:border-slate-800/80 text-slate-500 dark:text-slate-400 text-[11px] font-medium">
                <svg class="size-3.5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>{{ __('اتصال مشفر وآمن بمعيار 256-bit SSL') }}</span>
            </div>
            <p class="text-slate-400 dark:text-slate-500 font-medium text-[11px]">
                {{ ($siteName ?? platform_name()) . ' • ' . __('نظام إدارة المحتوى والتحكم المركزي') }}
            </p>
        </div>

    </div>

    @fluxScripts
</body>
</html>