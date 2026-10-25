<x-layouts::auth :title="__('استعادة كلمة المرور')">
    <div class="flex flex-col gap-6 w-full text-start">
        <div class="flex flex-col gap-1 items-start w-full">
            <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl text-[#012383] w-full leading-tight">
                {{ __('نسيت كلمة المرور؟') }}
            </h1>
            <p class="font-normal text-[15px] text-[#475569] w-full">
                {{ __('أدخل بريدك الإلكتروني لإرسال رابط إعادة تعيين كلمة المرور.') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-start" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5 w-full">
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
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="name@example.com"
                    class="w-full h-[52px] px-4 rounded-xl bg-white border border-[#DEE8F7] focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/10 text-sm text-[#475569] outline-none transition-all placeholder:text-slate-400"
                />
                @error('email')
                    <p class="text-xs text-rose-600 font-semibold mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full h-14 rounded-2xl bg-[#0B2A8F] hover:bg-[#012383] !text-white font-['Cairo'] font-bold text-base shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer active:scale-[0.99] flex items-center justify-center mt-2 text-white"
                style="color: #ffffff !important;"
                data-test="email-password-reset-link-button"
            >
                <span class="text-white" style="color: #ffffff !important;">{{ __('إرسال رابط إعادة التعيين') }}</span>
            </button>
        </form>

        <div class="text-center text-sm text-[#475569]">
            <span>{{ __('أو العودة إلى') }} </span>
            <a href="{{ route('login') }}" class="font-bold text-[#0B2A8F] underline hover:text-[#012383] transition-colors" wire:navigate>
                {{ __('تسجيل الدخول') }}
            </a>
        </div>
    </div>
</x-layouts::auth>
