<x-admin-layout::admin
    :title="__('إعدادات المنصة وقنوات الدفع وإدارة القوائم')"
    :description="__('تحديد أرقام المحافظ البنكية، إدارة القوائم والتنقل الديناميكي (Menu Manager)، وشروط إصدار الشهادات')"
    contentClass="p-4 sm:p-8 flex flex-col gap-6 max-w-[1200px] w-full mx-auto"
>

    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center gap-2">
            <svg class="size-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Section 0: Theme & Appearance -->
    <div class="bg-white border border-[#DEE8F7] rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col gap-5">
        <div class="border-b border-[#DEE8F7] pb-4">
            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">{{ __('مظهر لوحة التحكم والنظام (Theme)') }}</h3>
            <p class="text-xs text-slate-500">{{ __('اختر مظهر لوحة الإدارة: فاتح نهاري، داكن ليلي، أو متوافق تلقائياً مع نظام جهازك') }}</p>
        </div>

        <x-admin::theme-switcher variant="segmented" />
    </div>

    <!-- Section 1: Manual Payment Channels Form -->
    <div class="bg-white border border-[#DEE8F7] rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col gap-6">
        <div class="border-b border-[#DEE8F7] pb-4">
            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">{{ __('بيانات استلام التحويلات اليدوية (تظهر للطلاب عند التسجيل)') }}</h3>
            <p class="text-xs text-slate-500">{{ __('سيقوم الطالب بتحويل المبلغ المطلوب إلى هذه الحسابات ثم رفع صورة الإيصال للتحصيل التلقائي') }}</p>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf
            
            <!-- Vodafone Cash -->
            <div class="p-5 rounded-2xl bg-slate-50/70 border border-[#DEE8F7]/80 flex flex-col gap-3">
                <div class="flex items-center gap-2 text-rose-700 font-bold text-sm">
                    <span class="size-2.5 rounded-full bg-rose-600"></span>
                    <span>{{ __('محفظة فودافون كاش (Vodafone Cash)') }}</span>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">{{ __('رقم المحفظة المعتمد:') }}</label>
                    <input type="text" name="vodafone_cash_number[ar]" value="{{ $settings['vodafone_cash_number']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-slate-800 bg-white" />
                    <input type="hidden" name="vodafone_cash_number[en]" value="{{ $settings['vodafone_cash_number']['value_en'] ?? '' }}" />
                    <input type="hidden" name="vodafone_cash_number[group]" value="payment_vodafone" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">{{ __('تعليمات التحويل (بالعربية):') }}</label>
                    <input type="text" name="vodafone_cash_instructions[ar]" value="{{ $settings['vodafone_cash_instructions']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-800 bg-white" />
                    <input type="hidden" name="vodafone_cash_instructions[en]" value="{{ $settings['vodafone_cash_instructions']['value_en'] ?? '' }}" />
                    <input type="hidden" name="vodafone_cash_instructions[group]" value="payment_vodafone" />
                </div>
            </div>

            <!-- InstaPay -->
            <div class="p-5 rounded-2xl bg-slate-50/70 border border-[#DEE8F7]/80 flex flex-col gap-3">
                <div class="flex items-center gap-2 text-purple-700 font-bold text-sm">
                    <span class="size-2.5 rounded-full bg-purple-600"></span>
                    <span>{{ __('حساب إنستاباي (InstaPay Address)') }}</span>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">{{ __('عنوان الدفع اللحظي (IPA):') }}</label>
                    <input type="text" name="instapay_handle[ar]" value="{{ $settings['instapay_handle']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold font-mono text-slate-800 bg-white" />
                    <input type="hidden" name="instapay_handle[en]" value="{{ $settings['instapay_handle']['value_en'] ?? '' }}" />
                    <input type="hidden" name="instapay_handle[group]" value="payment_instapay" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">{{ __('تعليمات الدفع عبر إنستاباي:') }}</label>
                    <input type="text" name="instapay_instructions[ar]" value="{{ $settings['instapay_instructions']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-800 bg-white" />
                    <input type="hidden" name="instapay_instructions[en]" value="{{ $settings['instapay_instructions']['value_en'] ?? '' }}" />
                    <input type="hidden" name="instapay_instructions[group]" value="payment_instapay" />
                </div>
            </div>

            <!-- Bank Wire -->
            <div class="p-5 rounded-2xl bg-slate-50/70 border border-[#DEE8F7]/80 flex flex-col gap-3 md:col-span-2">
                <div class="flex items-center gap-2 text-[#012383] font-bold text-sm">
                    <span class="size-2.5 rounded-full bg-[#0B2A8F]"></span>
                    <span>{{ __('التحويل البنكي المباشر (Bank Transfer)') }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-600 block mb-1">{{ __('اسم البنك:') }}</label>
                        <input type="text" name="bank_name[ar]" value="{{ $settings['bank_name']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-bold text-slate-800 bg-white" />
                        <input type="hidden" name="bank_name[en]" value="{{ $settings['bank_name']['value_en'] ?? '' }}" />
                        <input type="hidden" name="bank_name[group]" value="payment_bank" />
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-600 block mb-1">{{ __('اسم صاحب الحساب:') }}</label>
                        <input type="text" name="bank_account_holder[ar]" value="{{ $settings['bank_account_holder']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-bold text-slate-800 bg-white" />
                        <input type="hidden" name="bank_account_holder[en]" value="{{ $settings['bank_account_holder']['value_en'] ?? '' }}" />
                        <input type="hidden" name="bank_account_holder[group]" value="payment_bank" />
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-600 block mb-1">{{ __('رقم الآيبان (IBAN):') }}</label>
                        <input type="text" name="bank_iban[ar]" value="{{ $settings['bank_iban']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-mono font-bold text-slate-800 bg-white" />
                        <input type="hidden" name="bank_iban[en]" value="{{ $settings['bank_iban']['value_en'] ?? '' }}" />
                        <input type="hidden" name="bank_iban[group]" value="payment_bank" />
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer flex items-center gap-2">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ __('حفظ وتطبيق قنوات الدفع فورياً') }}</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Section 2: Dynamic Menu Manager (Header & Footer) -->
    <div class="bg-white border border-[#DEE8F7] rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col gap-6">
        <div class="border-b border-[#DEE8F7] pb-4">
            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">{{ __('مدير القوائم والتنقل الديناميكي (Dynamic Menu Manager)') }}</h3>
            <p class="text-xs text-slate-500">{{ __('إضافة وتعديل روابط القائمة العلوية الرئيسية (Header) وقوائم التذييل (Footer) بدون أي تعديل بالكود') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($menus as $menu)
                <div class="p-5 rounded-2xl bg-slate-50 border border-[#DEE8F7] flex flex-col gap-4">
                    <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-2">
                        <span class="font-bold text-sm text-[#012383]">{{ $menu->name }}</span>
                        <span class="font-mono text-[10px] text-slate-400 bg-white px-2 py-0.5 rounded border border-[#DEE8F7]">{{ $menu->location }}</span>
                    </div>

                    <div class="divide-y divide-[#DEE8F7]/60">
                        @forelse($menu->items as $item)
                            <div class="py-2.5 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-slate-800">{{ $item->title_ar }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono block">{{ $item->url }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $item->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $item->is_active ? __('نشط') : __('معطل') }}
                                </span>
                            </div>
                        @empty
                            <div class="py-3 text-center text-slate-400 text-xs">{{ __('لا توجد روابط في هذه القائمة') }}</div>
                        @endforelse
                    </div>

                    <!-- Add Menu Link Form -->
                    <form method="POST" action="{{ route('admin.settings.menus.item', $menu->id) }}" class="mt-2 pt-3 border-t border-[#DEE8F7] flex flex-col gap-2">
                        @csrf
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" name="title_ar" placeholder="{{ __('العنوان بالعربية') }}" required class="h-9 px-3 rounded-lg border border-[#DEE8F7] text-xs bg-white" />
                            <input type="text" name="title_en" placeholder="{{ __('العنوان بالإنجليزية') }}" class="h-9 px-3 rounded-lg border border-[#DEE8F7] text-xs bg-white" />
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" name="url" placeholder="{{ __('الرابط (e.g. /courses or https://...)') }}" required class="h-9 px-3 rounded-lg border border-[#DEE8F7] text-xs bg-white flex-1" />
                            <button type="submit" class="h-9 px-3 rounded-lg bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold shrink-0">
                                {{ __('+ إضافة رابط') }}
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

</x-admin-layout::admin>
