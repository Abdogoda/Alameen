<x-layouts::auth :title="__('تأكيد البريد الإلكتروني')">
    <div class="flex flex-col gap-6 w-full text-start">
        <div class="flex flex-col gap-1 items-start w-full">
            <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl text-[#012383] w-full leading-tight">
                {{ __('تأكيد البريد الإلكتروني') }}
            </h1>
            <p class="font-normal text-[15px] text-[#475569] w-full leading-relaxed">
                {{ __('يرجى إدخال رمز التحقق (OTP) المكون من 6 أرقام المرسل إلى بريدك الإلكتروني:') }}
                @if (auth()->check() && auth()->user()->email)
                    <span class="font-bold text-[#012383] inline-block dir-ltr text-sm bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">
                        {{ auth()->user()->email }}
                    </span>
                @endif
            </p>
        </div>

        @if (session('status') === 'verification-otp-sent' || session('status') === 'verification-link-sent')
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-bold flex items-center gap-2">
                <svg class="size-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ __('تم إرسال رمز تحقق جديد إلى بريدك الإلكتروني بنجاح.') }}</span>
            </div>
        @elseif (session('status'))
            <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-xs text-[#012383] font-bold">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 font-bold">
                {{ session('error') }}
            </div>
        @endif

        <!-- OTP Verification Form -->
        <form method="POST" action="{{ route('verification.verify-otp') }}" class="flex flex-col gap-4 w-full">
            @csrf

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
                        value="{{ old('otp') }}"
                        required
                        autofocus
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        placeholder="••••••"
                        class="w-full h-[54px] px-4 rounded-xl bg-[#FAFCFF] border border-[#DEE8F7] focus:bg-white focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/10 text-xl font-mono tracking-[10px] text-center text-[#012383] font-extrabold outline-none transition-all placeholder:tracking-normal placeholder:font-sans placeholder:text-slate-400"
                    />
                </div>
                @error('otp')
                    <p class="text-xs text-rose-600 font-semibold mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full h-14 rounded-2xl bg-[#0B2A8F] hover:bg-[#012383] !text-white font-['Cairo'] font-bold text-base shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer active:scale-[0.99] flex items-center justify-center text-white mt-1"
                style="color: #ffffff !important;"
            >
                <span class="text-white" style="color: #ffffff !important;">{{ __('تأكيد الحساب') }}</span>
            </button>
        </form>

        <!-- Secondary Actions (Resend & Logout) -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 w-full pt-2 border-t border-slate-100">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    type="submit"
                    class="text-sm font-bold text-[#0B2A8F] hover:text-[#012383] hover:underline transition-colors cursor-pointer flex items-center gap-1.5"
                >
                    <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>{{ __('لم يصلك الرمز؟ إعادة إرسال') }}</span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="text-sm font-semibold text-slate-500 hover:text-rose-600 transition-colors cursor-pointer"
                    data-test="logout-button"
                >
                    {{ __('تسجيل الخروج') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts::auth>
