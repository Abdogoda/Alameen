<x-layouts::auth :title="__('تأكيد البريد الإلكتروني')">
    <div class="flex flex-col gap-6 w-full text-start">
        <div class="flex flex-col gap-1 items-start w-full">
            <h1 class="font-['Cairo'] font-extrabold text-3xl sm:text-4xl text-[#012383] w-full leading-tight">
                {{ __('تأكيد البريد الإلكتروني') }}
            </h1>
            <p class="font-normal text-[15px] text-[#475569] w-full">
                {{ __('يرجى التحقق من بريدك الإلكتروني والنقر على رابط التأكيد الذي أرسلناه لك.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-bold">
                {{ __('تم إرسال رابط تأكيد جديد إلى البريد الإلكتروني المسجل في حسابك.') }}
            </div>
        @endif

        <div class="flex flex-col gap-3 w-full">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <button
                    type="submit"
                    class="w-full h-14 rounded-2xl bg-[#0B2A8F] hover:bg-[#012383] !text-white font-['Cairo'] font-bold text-base shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer active:scale-[0.99] flex items-center justify-center text-white"
                    style="color: #ffffff !important;"
                >
                    <span class="text-white" style="color: #ffffff !important;">{{ __('إعادة إرسال بريد التأكيد') }}</span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button
                    type="submit"
                    class="w-full h-11 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 text-xs font-bold transition-colors cursor-pointer flex items-center justify-center"
                    data-test="logout-button"
                >
                    {{ __('تسجيل الخروج') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts::auth>
