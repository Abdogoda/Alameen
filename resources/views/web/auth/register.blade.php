<x-layouts::auth :title="__('إنشاء حساب جديد')">
    <div class="flex flex-col gap-6 w-full text-start">
        
        <!-- Welcome Heading -->
        <div class="flex flex-col gap-1 items-start w-full">
            <p class="font-semibold text-[15px] leading-relaxed text-[#0B2A8F] w-full">
                {{ __('ابدأ رحلتك اليوم') }}
            </p>
            <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl text-[#012383] w-full leading-tight">
                {{ __('إنشاء حساب') }}
            </h1>
            <p class="font-normal text-[15px] text-[#475569] w-full">
                {{ __('خطوتك الأولى نحو تعلّم أساليب التخاطب.') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-start" :status="session('status')" />

        <!-- Social Signup Options -->
        <div class="flex flex-col gap-3 w-full">
            <x-google-button action="signup" />
        </div>

        <!-- Section Divider -->
        <div class="relative my-1">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-[#DEE8F7] dark:border-slate-800"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="px-3 text-[#475569] dark:text-slate-400 bg-[#FAFCFF] dark:bg-[#0B1120] font-semibold">
                    {{ __('أو التسجيل بالبريد الإلكتروني') }}
                </span>
            </div>
        </div>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-4 w-full">
            @csrf

            <!-- Name -->
            <div class="flex flex-col gap-1.5 items-start w-full">
                <label for="name" class="font-bold text-[15px] text-[#012383] dark:text-blue-300 block">
                    {{ __('الاسم الكامل') }}
                </label>
                <div class="w-full relative flex items-center">
                    <div class="absolute start-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 dark:text-slate-500">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="{{ __('أدخل اسمك الكامل') }}"
                        class="w-full h-[52px] ps-11 pe-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 focus:border-[#0B2A8F] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#0B2A8F]/10 dark:focus:ring-blue-500/10 text-sm text-[#1E293B] dark:text-white outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 shadow-xs"
                    />
                </div>
                @error('name')
                    <p class="text-xs text-rose-600 font-semibold mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="flex flex-col gap-1.5 items-start w-full">
                <label for="email" class="font-bold text-[15px] text-[#012383] dark:text-blue-300 block">
                    {{ __('البريد الإلكتروني') }}
                </label>
                <div class="w-full relative flex items-center">
                    <div class="absolute start-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 dark:text-slate-500">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="name@example.com"
                        class="w-full h-[52px] ps-11 pe-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 focus:border-[#0B2A8F] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#0B2A8F]/10 dark:focus:ring-blue-500/10 text-sm text-[#1E293B] dark:text-white outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 shadow-xs"
                    />
                </div>
                @error('email')
                    <p class="text-xs text-rose-600 font-semibold mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-1.5 items-start w-full" x-data="{ show: false }">
                <label for="password" class="font-bold text-[15px] text-[#012383] dark:text-blue-300 block">
                    {{ __('كلمة المرور') }}
                </label>
                <div class="w-full relative flex items-center">
                    <div class="absolute start-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 dark:text-slate-500">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input
                        :type="show ? 'text' : 'password'"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="{{ __('أنشئ كلمة مرور قوية') }}"
                        class="w-full h-[52px] ps-11 pe-12 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 focus:border-[#0B2A8F] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#0B2A8F]/10 dark:focus:ring-blue-500/10 text-sm text-[#1E293B] dark:text-white outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 shadow-xs"
                    />
                    <button
                        type="button"
                        @click="show = !show"
                        class="absolute end-2.5 top-1/2 -translate-y-1/2 size-9 rounded-lg flex items-center justify-center text-slate-400 hover:text-[#012383] dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700/60 transition-all cursor-pointer"
                        aria-label="Toggle password visibility"
                    >
                        <svg x-show="!show" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" x-cloak class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-rose-600 font-semibold mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="flex flex-col gap-1.5 items-start w-full">
                <label for="password_confirmation" class="font-bold text-[15px] text-[#012383] dark:text-blue-300 block">
                    {{ __('تأكيد كلمة المرور') }}
                </label>
                <div class="w-full relative flex items-center">
                    <div class="absolute start-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 dark:text-slate-500">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="{{ __('أعد إدخال كلمة المرور') }}"
                        class="w-full h-[52px] ps-11 pe-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 focus:border-[#0B2A8F] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#0B2A8F]/10 dark:focus:ring-blue-500/10 text-sm text-[#1E293B] dark:text-white outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 shadow-xs"
                    />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex flex-col gap-4 w-full mt-2">
                <button
                    type="submit"
                    class="w-full h-13 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-r from-[#0B2A8F] to-[#012383] hover:from-[#012383] hover:to-[#0A1E5C] text-white font-['Cairo'] font-bold text-base shadow-[0_8px_20px_-4px_rgba(11,42,143,0.35)] hover:shadow-[0_12px_24px_-4px_rgba(11,42,143,0.45)] transition-all duration-200 cursor-pointer active:scale-[0.99] flex items-center justify-center gap-2.5"
                    style="color: #ffffff !important;"
                    data-test="register-user-button"
                >
                    <span class="text-white" style="color: #ffffff !important;">{{ __('إنشاء الحساب') }}</span>
                    <svg class="size-4.5 rtl:rotate-180 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>

                <p class="font-normal text-sm text-center w-full text-[#475569] dark:text-slate-400 mt-1">
                    <span>{{ __('لديك حساب بالفعل؟') }} </span>
                    <a href="{{ route('login') }}" class="font-bold text-[#0B2A8F] hover:text-[#012383] dark:text-blue-400 underline underline-offset-4 transition-colors" wire:navigate>
                        {{ __('تسجيل الدخول') }}
                    </a>
                </p>
            </div>
        </form>

    </div>
</x-layouts::auth>
