<section class="w-full text-start">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('الملف الشخصي والبيانات الأكاديمية')" :subheading="__('تحديث الاسم والبريد الإلكتروني وتفضيلات الحساب')">
        
        <!-- User Identity Summary Banner -->
        <div class="bg-gradient-to-r from-[#EAF1FB] to-white dark:from-slate-800 dark:to-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 flex items-center justify-between gap-4 mb-6 shadow-xs">
            <div class="flex items-center gap-3.5">
                <div class="size-14 rounded-2xl bg-gradient-to-tr from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-xl shadow-sm shrink-0">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="flex flex-col text-start">
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white leading-tight">
                            {{ auth()->user()->name }}
                        </h3>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold">
                            <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>{{ __('طالب معتمد') }}</span>
                        </span>
                    </div>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ auth()->user()->email }}</span>
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-2">
                <span class="text-xs text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 px-3 py-1.5 rounded-xl font-semibold">
                    {{ __('حساب نشط') }}
                </span>
            </div>
        </div>

        <!-- Profile Edit Form -->
        <form wire:submit="updateProfileInformation" class="w-full space-y-6">
            
            <!-- Name Input -->
            <div class="flex flex-col gap-1.5 w-full">
                <label for="profile_name" class="font-bold text-xs sm:text-sm text-[#012383] dark:text-blue-300 flex items-center gap-1.5">
                    <svg class="size-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>{{ __('الاسم الكامل') }}</span>
                </label>
                <div class="relative w-full">
                    <input
                        id="profile_name"
                        wire:model="name"
                        type="text"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="{{ __('أدخل اسمك الكامل') }}"
                        class="w-full h-12 px-4 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-[#DEE8F7] dark:border-slate-700 text-sm text-[#1E293B] dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-[#012383] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#012383]/10 dark:focus:ring-blue-500/10 outline-none transition-all"
                    />
                </div>
                @error('name')
                    <span class="text-xs text-rose-600 font-semibold mt-0.5">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email Input -->
            <div class="flex flex-col gap-1.5 w-full">
                <label for="profile_email" class="font-bold text-xs sm:text-sm text-[#012383] dark:text-blue-300 flex items-center gap-1.5">
                    <svg class="size-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>{{ __('البريد الإلكتروني') }}</span>
                </label>
                <div class="relative w-full">
                    <input
                        id="profile_email"
                        wire:model="email"
                        type="email"
                        required
                        autocomplete="email"
                        placeholder="example@domain.com"
                        class="w-full h-12 px-4 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-[#DEE8F7] dark:border-slate-700 text-sm text-[#1E293B] dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-[#012383] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#012383]/10 dark:focus:ring-blue-500/10 outline-none transition-all"
                    />
                </div>
                @error('email')
                    <span class="text-xs text-rose-600 font-semibold mt-0.5">{{ $message }}</span>
                @enderror

                @if ($this->hasUnverifiedEmail)
                    <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-300 flex items-center justify-between gap-3 mt-1.5">
                        <span class="font-medium">{{ __('بريدك الإلكتروني غير مؤكد حالياً.') }}</span>
                        <button type="button" wire:click.prevent="resendVerificationNotification" class="font-bold underline hover:text-amber-950 dark:hover:text-amber-200 cursor-pointer">
                            {{ __('إعادة إرسال رابط التأكيد') }}
                        </button>
                    </div>
                @endif
            </div>

            <!-- Language Switcher Option -->
            <div class="flex flex-col gap-2 w-full pt-1">
                <label class="font-bold text-xs sm:text-sm text-[#012383] dark:text-blue-300 flex items-center gap-1.5">
                    <svg class="size-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                    <span>{{ __('لغة المنصة وتجربة الاستخدام') }}</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-lg w-full">
                    <label class="flex items-center gap-3 p-3.5 rounded-2xl border transition-all cursor-pointer {{ $locale === 'ar' ? 'border-[#012383] dark:border-blue-500 bg-[#EAF1FB]/80 dark:bg-blue-950/50 text-[#012383] dark:text-white font-bold shadow-xs' : 'border-[#DEE8F7] dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-900' }}">
                        <input type="radio" wire:model.live="locale" value="ar" class="accent-[#012383] size-4" />
                        <img src="/assets/images/flags/eg.svg" alt="العلم المصري" class="w-7 h-5 rounded-[3px] object-cover shadow-xs border border-slate-200/80 dark:border-slate-700 shrink-0" onerror="this.outerHTML='<span class=\'text-xl\'>🇪🇬</span>'" />
                        <div class="flex flex-col text-start">
                            <span class="text-xs sm:text-sm font-bold">{{ __('العربية (الافتراضية)') }}</span>
                            <span class="text-[11px] text-slate-400 font-normal">{{ __('واجهة كاملة باللغة العربية') }}</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 rounded-2xl border transition-all cursor-pointer {{ $locale === 'en' ? 'border-[#012383] dark:border-blue-500 bg-[#EAF1FB]/80 dark:bg-blue-950/50 text-[#012383] dark:text-white font-bold shadow-xs' : 'border-[#DEE8F7] dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-900' }}">
                        <input type="radio" wire:model.live="locale" value="en" class="accent-[#012383] size-4" />
                        <img src="/assets/images/flags/us.svg" alt="English" class="w-7 h-5 rounded-[3px] object-cover shadow-xs border border-slate-200/80 dark:border-slate-700 shrink-0" onerror="this.outerHTML='<span class=\'text-xl\'>🇺🇸</span>'" />
                        <div class="flex flex-col text-start">
                            <span class="text-xs sm:text-sm font-bold">English (US)</span>
                            <span class="text-[11px] text-slate-400 font-normal">English interface</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2 flex items-center gap-3">
                <button
                    type="submit"
                    class="h-12 px-7 rounded-2xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-['Cairo'] font-bold text-sm shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer active:scale-[0.99] flex items-center justify-center gap-2"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ __('حفظ التغييرات') }}</span>
                </button>
            </div>

        </form>

        <!-- Delete Account Danger Zone Section -->
        @if ($this->showDeleteUser)
            <livewire:settings.delete-user-form />
        @endif

    </x-settings.layout>
</section>
