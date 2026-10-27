<x-layouts::auth :title="__('إعادة تعيين كلمة المرور')">
    <div class="flex flex-col gap-6 w-full text-start">
        <div class="flex flex-col gap-1 items-start w-full">
            <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl text-[#012383] w-full leading-tight">
                {{ __('إعادة تعيين كلمة المرور') }}
            </h1>
            <p class="font-normal text-[15px] text-[#475569] w-full">
                {{ __('أدخل رمز التحقق (OTP) المرسل إلى بريدك الإلكتروني مع كلمة المرور الجديدة.') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-start" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-4 w-full">
            @csrf

            <!-- Email Address -->
            <div class="flex flex-col gap-1.5 items-start w-full">
                <label for="email" class="font-bold text-[15px] text-[#012383] block">
                    {{ __('البريد الإلكتروني') }}
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ request('email', old('email')) }}"
                    required
                    autocomplete="email"
                    class="w-full h-[52px] px-4 rounded-xl bg-white border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/10 text-sm text-[#475569] outline-none transition-all placeholder:text-slate-400"
                />
                @error('email')
                    <p class="text-xs text-rose-600 font-semibold mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- 6-Digit OTP Field -->
            <div class="flex flex-col gap-1.5 items-start w-full">
                <div class="flex items-center justify-between w-full">
                    <label for="otp" class="font-bold text-[15px] text-[#012383] block">
                        {{ __('رمز التحقق (OTP)') }}
                    </label>
                    <span class="text-xs text-[#64748B] font-medium">
                        {{ __('مكون من 6 أرقام') }}
                    </span>
                </div>
                <div class="relative w-full">
                    <input
                        type="text"
                        id="otp"
                        name="otp"
                        value="{{ request('otp', old('otp', request()->route('token'))) }}"
                        required
                        autofocus
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        placeholder="••••••"
                        class="w-full h-[52px] px-4 rounded-xl bg-[#FAFCFF] border border-[#DEE8F7] focus:bg-white focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/10 text-lg font-mono tracking-[8px] text-[#012383] font-bold outline-none transition-all placeholder:tracking-normal placeholder:font-sans placeholder:text-slate-400"
                    />
                </div>
                @error('otp')
                    <p class="text-xs text-rose-600 font-semibold mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-1.5 items-start w-full" x-data="{ show: false }">
                <label for="password" class="font-bold text-[15px] text-[#012383] block">
                    {{ __('كلمة المرور الجديدة') }}
                </label>
                <div class="w-full relative flex items-center">
                    <input
                        :type="show ? 'text' : 'password'"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="{{ __('أنشئ كلمة مرور جديدة') }}"
                        class="w-full h-[52px] ps-4 pe-12 rounded-xl bg-white border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/10 text-sm text-[#475569] outline-none transition-all placeholder:text-slate-400"
                    />
                    <button
                        type="button"
                        @click="show = !show"
                        class="absolute end-3 top-1/2 -translate-y-1/2 size-9 flex items-center justify-center text-slate-400 hover:text-[#012383] transition-colors cursor-pointer"
                        aria-label="Toggle password visibility"
                    >
                        <svg x-show="!show" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" x-cloak class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-rose-600 font-semibold mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="flex flex-col gap-1.5 items-start w-full" x-data="{ show: false }">
                <label for="password_confirmation" class="font-bold text-[15px] text-[#012383] block">
                    {{ __('تأكيد كلمة المرور الجديدة') }}
                </label>
                <div class="w-full relative flex items-center">
                    <input
                        :type="show ? 'text' : 'password'"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="{{ __('أعد إدخال كلمة المرور الجديدة') }}"
                        class="w-full h-[52px] ps-4 pe-12 rounded-xl bg-white border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/10 text-sm text-[#475569] outline-none transition-all placeholder:text-slate-400"
                    />
                    <button
                        type="button"
                        @click="show = !show"
                        class="absolute end-3 top-1/2 -translate-y-1/2 size-9 flex items-center justify-center text-slate-400 hover:text-[#012383] transition-colors cursor-pointer"
                        aria-label="Toggle password visibility"
                    >
                        <svg x-show="!show" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" x-cloak class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    </button>
                </div>
            </div>

            <button
                type="submit"
                class="w-full h-14 rounded-2xl bg-[#0B2A8F] hover:bg-[#012383] !text-white font-['Cairo'] font-bold text-base shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer active:scale-[0.99] flex items-center justify-center mt-2 text-white"
                style="color: #ffffff !important;"
                data-test="reset-password-button"
            >
                <span class="text-white" style="color: #ffffff !important;">{{ __('تحديث كلمة المرور') }}</span>
            </button>
        </form>

        <div class="flex items-center justify-between text-sm text-[#475569] pt-1">
            <a href="{{ route('password.request') }}" class="font-bold text-[#0B2A8F] hover:underline" wire:navigate>
                {{ __('لم يصلك الرمز؟ أعد الإرسال') }}
            </a>
            <a href="{{ route('login') }}" class="text-slate-500 hover:text-[#012383]" wire:navigate>
                {{ __('العودة لتسجيل الدخول') }}
            </a>
        </div>
    </div>
</x-layouts::auth>
