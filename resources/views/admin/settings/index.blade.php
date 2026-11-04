<x-admin-layout::admin
    :title="__('إعدادات المنصة ولوحة التحكم والموقع (Control Tower)')"
    :description="__('تحكم شامل وفوري في الهوية، محتوى الموقع، الأسئلة الشائعة، قنوات الدفع، السياسات الأكاديمية والقوائم')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-6 w-full min-w-0"
>

    {{-- Notification Alerts --}}
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-bold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="size-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <span class="text-xs bg-emerald-100 dark:bg-emerald-900/60 text-emerald-900 dark:text-emerald-200 px-2.5 py-1 rounded-lg font-mono">{{ __('تم تفريغ الكاش وتحديث الموقع فورياً') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm flex flex-col gap-1.5 shadow-xs">
            <span class="font-bold flex items-center gap-2">
                <svg class="size-5 text-rose-600 dark:text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ __('يرجى تصحيح الأخطاء التالية:') }}
            </span>
            <ul class="list-disc list-inside text-xs ps-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Top Platform Command Status Bar --}}
    <div class="bg-gradient-to-r from-[#012383] via-[#0B2A8F] to-[#011C6B] text-white rounded-3xl p-5 sm:p-6 shadow-md flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="size-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shrink-0">
                <svg class="size-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <h2 class="font-['Baloo_Bhaijaan_2'] font-extrabold text-xl sm:text-2xl leading-none">
                        {{ $settings['site_name']['value_ar'] ?? config('app.name', 'منصة الأمين') }}
                    </h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 flex items-center gap-1">
                        <span class="size-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>{{ __('المنصة نشطة 100%') }}</span>
                    </span>
                </div>
                <p class="text-xs text-blue-200/80 font-medium mt-1">
                    {{ __('مركز التحكم المركزي الموحد: إدارة كاملة للعلامة التجارية، الموقع، المحاضرات، بوابات الدفع، والسياسات الأكاديمية') }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('home') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-xs font-bold transition-all flex items-center gap-2">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>{{ __('معاينة الموقع الرئيسي') }}</span>
            </a>
            <a href="{{ route('dashboard') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 text-xs font-extrabold transition-all flex items-center gap-2 shadow-xs">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>{{ __('بوابة الطالب (LMS)') }}</span>
            </a>
        </div>
    </div>

    {{-- Interactive Multi-Tab Control Tower Container --}}
    <div x-data="{ 
        validTabs: ['branding', 'contact', 'cms', 'faq', 'academic', 'payments', 'mux', 'menus', 'theme', 'pages'],
        activeTab: 'branding',
        init() {
            const rawHash = (window.location.hash || '').replace(/^#/, '').trim();
            const urlTab = new URLSearchParams(window.location.search).get('tab');
            const stored = localStorage.getItem('alameen_admin_settings_tab');

            if (rawHash && this.validTabs.includes(rawHash)) {
                this.activeTab = rawHash;
            } else if (urlTab && this.validTabs.includes(urlTab)) {
                this.activeTab = urlTab;
            } else if (stored && this.validTabs.includes(stored)) {
                this.activeTab = stored;
            } else {
                this.activeTab = 'branding';
            }
        },
        setTab(tab) {
            if (!this.validTabs.includes(tab)) return;
            this.activeTab = tab;
            try {
                localStorage.setItem('alameen_admin_settings_tab', tab);
                history.replaceState(null, '', '#' + tab);
            } catch (e) {}
        }
    }" class="flex flex-col gap-6">

        {{-- Tab Pills Navigation Bar --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-1.5 shadow-xs overflow-x-auto flex items-center gap-1.5 scrollbar-none">
            
            <button type="button" @click="setTab('branding')" :class="activeTab === 'branding' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="shrink-0 px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <span class="shrink-0 text-sm">🏢</span>
                <span class="shrink-0 font-bold whitespace-nowrap">{{ __('الهوية والعلامة التجارية') }}</span>
            </button>

            <button type="button" @click="setTab('pages')" :class="activeTab === 'pages' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="shrink-0 px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <span class="shrink-0 text-sm">👁️</span>
                <span class="shrink-0 font-bold whitespace-nowrap">{{ __('إدارة صفحات الطالب (ظهور/إخفاء)') }}</span>
            </button>

            <button type="button" @click="setTab('contact')" :class="activeTab === 'contact' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="shrink-0 px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <span class="shrink-0 text-sm">📞</span>
                <span class="shrink-0 font-bold whitespace-nowrap">{{ __('التواصل والدعم الفني') }}</span>
            </button>

            <button type="button" @click="setTab('cms')" :class="activeTab === 'cms' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="shrink-0 px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <span class="shrink-0 text-sm">🚀</span>
                <span class="shrink-0 font-bold whitespace-nowrap">{{ __('الواجهة ومحتوى الموقع') }}</span>
            </button>

            <button type="button" @click="setTab('faq')" :class="activeTab === 'faq' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="shrink-0 px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <span class="shrink-0 text-sm">❓</span>
                <span class="shrink-0 font-bold whitespace-nowrap">{{ __('الأسئلة الشائعة (FAQ)') }}</span>
            </button>

            <button type="button" @click="setTab('academic')" :class="activeTab === 'academic' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="shrink-0 px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <span class="shrink-0 text-sm">🎓</span>
                <span class="shrink-0 font-bold whitespace-nowrap">{{ __('السياسات الأكاديمية والمحاضرات') }}</span>
            </button>

            <button type="button" @click="setTab('payments')" :class="activeTab === 'payments' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="shrink-0 px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <span class="shrink-0 text-sm">💳</span>
                <span class="shrink-0 font-bold whitespace-nowrap">{{ __('قنوات الدفع والتحويل') }}</span>
            </button>

            <button type="button" @click="setTab('mux')" :class="activeTab === 'mux' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="shrink-0 px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <span class="shrink-0 text-sm">🎬</span>
                <span class="shrink-0 font-bold whitespace-nowrap">{{ __('سحابة MUX للبث والتشفير') }}</span>
            </button>

            <button type="button" @click="setTab('menus')" :class="activeTab === 'menus' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="shrink-0 px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <span class="shrink-0 text-sm">🧭</span>
                <span class="shrink-0 font-bold whitespace-nowrap">{{ __('مدير القوائم والتنقل') }}</span>
            </button>

            <button type="button" @click="setTab('theme')" :class="activeTab === 'theme' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="shrink-0 px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <span class="shrink-0 text-sm">🎨</span>
                <span class="shrink-0 font-bold whitespace-nowrap">{{ __('المظهر والنظام') }}</span>
            </button>

        </div>

        {{-- ======================================================== --}}
        {{-- TAB 1: الهوية والعلامة التجارية (General & Branding)     --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'branding'" x-cloak class="flex flex-col gap-6">
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 mb-6">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                        {{ __('إعدادات الهوية والعلامة التجارية (Brand & Identity)') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('الاسم الرسمي للمنصة باللغتين، الشعار العام، مسار اللوجو، العملة الافتراضية، وحقوق الملكية') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}#branding" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @csrf
                    
                    {{-- Site Name AR --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('اسم المنصة الرسمي (بالعربية):') }}</label>
                        <input type="text" name="settings[site_name][ar]" value="{{ $settings['site_name']['value_ar'] ?? '' }}" required class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[site_name][group]" value="branding" />
                    </div>

                    {{-- Site Name EN --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('اسم المنصة الرسمي (بالإنجليزية):') }}</label>
                        <input type="text" name="settings[site_name][en]" value="{{ $settings['site_name']['value_en'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    </div>

                    {{-- Tagline AR --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('شعار المنصة / الوصف المختصر (بالعربية):') }}</label>
                        <textarea name="settings[site_tagline][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['site_tagline']['value_ar'] ?? '' }}</textarea>
                        <input type="hidden" name="settings[site_tagline][group]" value="branding" />
                    </div>

                    {{-- Tagline EN --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('شعار المنصة / الوصف المختصر (بالإنجليزية):') }}</label>
                        <textarea name="settings[site_tagline][en]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['site_tagline']['value_en'] ?? '' }}</textarea>
                    </div>

                    {{-- Logo Asset URL & File Upload --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('رابط أو مسار الشعار (Logo Image Path):') }}</label>
                        <div class="flex items-center gap-3">
                            <input type="text" name="settings[site_logo][ar]" value="{{ $settings['site_logo']['value_ar'] ?? '/assets/images/logo.png' }}" class="flex-1 h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                            <div class="size-11 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-1.5 shrink-0 flex items-center justify-center">
                                <img src="{{ platform_logo() }}" alt="Logo" class="size-full object-contain" />
                            </div>
                        </div>
                        <input type="hidden" name="settings[site_logo][group]" value="branding" />
                        <div>
                            <span class="text-[11px] font-bold text-slate-500 block mb-1">{{ __('أو ارفع ملف شعار جديد من جهازك (PNG / SVG / WEBP):') }}</span>
                            <input type="file" name="site_logo_file" accept="image/png,image/jpeg,image/svg+xml,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#012383] file:text-white hover:file:bg-[#0B2A8F] cursor-pointer" />
                        </div>
                    </div>

                    {{-- Currency Settings --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('رمز واسم العملة الافتراضية (Currency):') }}</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" name="settings[currency_name][ar]" placeholder="جنيه مصري" value="{{ $settings['currency_name']['value_ar'] ?? 'جنيه مصري' }}" class="h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                            <input type="text" name="settings[currency_symbol][ar]" placeholder="ج.م" value="{{ $settings['currency_symbol']['value_ar'] ?? 'ج.م' }}" class="h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-center bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        </div>
                        <input type="hidden" name="settings[currency_name][group]" value="branding" />
                        <input type="hidden" name="settings[currency_symbol][group]" value="branding" />
                    </div>

                    {{-- Copyright Notice --}}
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('نص حقوق النشر في التذييل (Footer Copyright):') }}</label>
                        <input type="text" name="settings[copyright_text][ar]" value="{{ $settings['copyright_text']['value_ar'] ?? 'جميع الحقوق محفوظة' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[copyright_text][group]" value="branding" />
                    </div>

                    <div class="md:col-span-2 flex justify-end pt-2">
                        <button type="submit" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer flex items-center justify-center gap-2">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('حفظ وتطبيق إعدادات الهوية فورياً') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 2: التواصل والدعم الفني ووسائل التواصل (Contact)    --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'contact'" x-cloak class="flex flex-col gap-6">
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 mb-6">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                        {{ __('بيانات التواصل، الدعم الفني، وروابط السوشيال ميديا') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('تظهر هذه البيانات مباشرة في صفحة تواصل معنا، التذييل العام، وزر المحادثة الفوري للطلاب') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}#contact" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @csrf

                    {{-- Support Email --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('البريد الإلكتروني الرسمي للدعم:') }}</label>
                        <input type="email" name="settings[contact_email][ar]" value="{{ $settings['contact_email']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[contact_email][group]" value="contact" />
                    </div>

                    {{-- Support Phone --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('رقم الهاتف / الخط الساخن:') }}</label>
                        <input type="text" name="settings[contact_phone][ar]" value="{{ $settings['contact_phone']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold dir-ltr text-start bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[contact_phone][group]" value="contact" />
                    </div>

                    {{-- WhatsApp Link --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('رابط محادثة واتساب المباشر:') }}</label>
                        <input type="text" name="settings[social_whatsapp][ar]" placeholder="https://wa.me/201000000000" value="{{ $settings['social_whatsapp']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[social_whatsapp][group]" value="social" />
                    </div>

                    {{-- Clinic Address --}}
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان المركز / المقر الرئيسي:') }}</label>
                        <input type="text" name="settings[headquarters_address][ar]" value="{{ $settings['headquarters_address']['value_ar'] ?? ($settings['contact_address']['value_ar'] ?? 'الزقازيق - شارع الغشام') }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[headquarters_address][group]" value="contact" />
                    </div>

                    {{-- Working Hours --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('مواعيد العمل الرسمية:') }}</label>
                        <input type="text" name="settings[working_hours][ar]" value="{{ $settings['working_hours']['value_ar'] ?? ($settings['contact_working_hours']['value_ar'] ?? 'السبت - الخميس من 12 - 9 م') }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[working_hours][group]" value="contact" />
                    </div>

                    {{-- Footer Badge Subtitle --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الشارة النصية تحت اسم الموقع بالتذييل:') }}</label>
                        <input type="text" name="settings[footer_badge][ar]" value="{{ $settings['footer_badge']['value_ar'] ?? 'مركز الامين للتدريب والتاهيل التخاطبي' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[footer_badge][group]" value="branding" />
                    </div>

                    {{-- Support Status Indicator --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('شارة جاهزية الدعم في صفحة التواصل:') }}</label>
                        <div class="flex items-center gap-3 h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                            <input type="hidden" name="settings[support_status_online][ar]" value="0" />
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="settings[support_status_online][ar]" value="1" {{ ($settings['support_status_online']['value_ar'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer" />
                                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('فريق الدعم متاح وجاهز (Online)') }}</span>
                        </div>
                        <input type="hidden" name="settings[support_status_online][group]" value="contact" />
                    </div>

                    {{-- Social Profiles: Facebook Center & Personal --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('فيسبوك - مركز الأمين:') }}</label>
                        <input type="url" name="settings[social_facebook_center][ar]" placeholder="https://facebook.com/..." value="{{ $settings['social_facebook_center']['value_ar'] ?? ($settings['social_facebook']['value_ar'] ?? '') }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[social_facebook_center][group]" value="social" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('فيسبوك - د. هنا المكاوي (شخصي):') }}</label>
                        <input type="url" name="settings[social_facebook_personal][ar]" placeholder="https://facebook.com/..." value="{{ $settings['social_facebook_personal']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[social_facebook_personal][group]" value="social" />
                    </div>

                    {{-- Social Profiles: TikTok Center & Personal --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('تيك توك - مركز الأمين:') }}</label>
                        <input type="url" name="settings[social_tiktok_center][ar]" placeholder="https://tiktok.com/@alameen_center" value="{{ $settings['social_tiktok_center']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[social_tiktok_center][group]" value="social" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('تيك توك - د. هنا المكاوي (شخصي):') }}</label>
                        <input type="url" name="settings[social_tiktok_personal][ar]" placeholder="https://tiktok.com/@slt.hanamekawy" value="{{ $settings['social_tiktok_personal']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[social_tiktok_personal][group]" value="social" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('رابط حساب إنستغرام (Instagram):') }}</label>
                        <input type="url" name="settings[social_instagram][ar]" placeholder="https://instagram.com/..." value="{{ $settings['social_instagram']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[social_instagram][group]" value="social" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('رابط صفحة لينكد إن (LinkedIn):') }}</label>
                        <input type="url" name="settings[social_linkedin][ar]" placeholder="https://linkedin.com/..." value="{{ $settings['social_linkedin']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[social_linkedin][group]" value="social" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('رابط قناة يوتيوب (YouTube):') }}</label>
                        <input type="url" name="settings[social_youtube][ar]" placeholder="https://youtube.com/..." value="{{ $settings['social_youtube']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[social_youtube][group]" value="social" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('رابط قناة تيليجرام (Telegram):') }}</label>
                        <input type="url" name="settings[social_telegram][ar]" placeholder="https://t.me/..." value="{{ $settings['social_telegram']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[social_telegram][group]" value="social" />
                    </div>

                    <div class="col-span-full flex justify-end pt-2">
                        <button type="submit" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer flex items-center justify-center gap-2">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('حفظ وتطبيق بيانات التواصل فورياً') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 3: الواجهة ومحتوى الموقع (Website CMS & Hero)        --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'cms'" x-cloak class="flex flex-col gap-6">

            {{-- 1. Top Announcement Notification Banner --}}
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 mb-5">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg text-[#012383] dark:text-blue-400">
                        {{ __('شريط الإعلانات والتنبيهات العلوي (Top Announcement Bar)') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('شريط لافت يظهر أعلى صفحات الموقع للترويج لخصم جديد، إطلاق دفعة تدريبية، أو تحديث هام') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}#cms" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('حالة الشريط العلوي:') }}</label>
                        <div class="flex items-center gap-3 h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                            <input type="hidden" name="settings[announcement_enabled][ar]" value="0" />
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="settings[announcement_enabled][ar]" value="1" {{ ($settings['announcement_enabled']['value_ar'] ?? '0') === '1' ? 'checked' : '' }} class="sr-only peer" />
                                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#012383]"></div>
                            </label>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('تفعيل الشريط العلوي') }}</span>
                        </div>
                        <input type="hidden" name="settings[announcement_enabled][group]" value="announcement" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('نص الإعلان (Announcement Text):') }}</label>
                        <input type="text" name="settings[announcement_text][ar]" placeholder="🎉 خصم 20% لفترة محدودة على المسار السريري الشامل!" value="{{ $settings['announcement_text']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[announcement_text][group]" value="announcement" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('رابط الزر (Target URL):') }}</label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="settings[announcement_url][ar]" placeholder="/courses" value="{{ $settings['announcement_url']['value_ar'] ?? '' }}" class="flex-1 h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                            <button type="submit" class="h-11 px-5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold shrink-0 cursor-pointer">
                                {{ __('حفظ') }}
                            </button>
                        </div>
                        <input type="hidden" name="settings[announcement_url][group]" value="announcement" />
                    </div>
                </form>
            </div>

            {{-- 2. Hero Section & Stats Counters --}}
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 mb-6">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                        {{ __('القسم الرئيسي والبانر الإعلاني (Hero Section & Counters)') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('تعديل العنوان الرئيسي والفرعي لواجهة الموقع، أزرار الدعوة للإجراء (CTAs)، وعدادات الإحصائيات الحية') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.settings.content.update', 'hero') }}#cms" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @csrf

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان الهيرو الرئيسي (بالعربية):') }}</label>
                        <input type="text" name="title_ar" value="{{ $heroBlock->title_ar }}" required class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان الهيرو الرئيسي (بالإنجليزية):') }}</label>
                        <input type="text" name="title_en" value="{{ $heroBlock->title_en }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الوصف الفرعي للهيرو (بالعربية):') }}</label>
                        <textarea name="subtitle_ar" rows="3" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $heroBlock->subtitle_ar }}</textarea>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الوصف الفرعي للهيرو (بالإنجليزية):') }}</label>
                        <textarea name="subtitle_en" rows="3" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $heroBlock->subtitle_en }}</textarea>
                    </div>

                    {{-- CTAs --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('نص ورابط الزر الأساسي (Primary CTA):') }}</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" name="metadata[cta_primary_ar]" placeholder="استكشف الدورات" value="{{ $heroBlock->metadata['cta_primary_ar'] ?? '' }}" class="h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                            <input type="text" name="metadata[cta_primary_url]" placeholder="/courses" value="{{ $heroBlock->metadata['cta_primary_url'] ?? '/courses' }}" class="h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('نص ورابط الزر الثانوي (Secondary CTA):') }}</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" name="metadata[cta_secondary_ar]" placeholder="تواصل معنا" value="{{ $heroBlock->metadata['cta_secondary_ar'] ?? '' }}" class="h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                            <input type="text" name="metadata[cta_secondary_url]" placeholder="/contact" value="{{ $heroBlock->metadata['cta_secondary_url'] ?? '/contact' }}" class="h-11 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        </div>
                    </div>

                    {{-- Live Counter Badges --}}
                    <div class="md:col-span-2 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-800">
                        <label class="text-xs font-bold text-[#012383] dark:text-blue-400 block mb-3">{{ __('إحصائيات المنصة الظاهرة للزوار (Statistics Counters):') }}</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block mb-1">{{ __('عدد الطلاب والمتدربين:') }}</span>
                                <input type="text" name="metadata[students_count]" value="{{ $heroBlock->metadata['students_count'] ?? '2500+' }}" class="w-full h-10 px-3 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                            </div>
                            <div>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block mb-1">{{ __('عدد الدورات السريرية:') }}</span>
                                <input type="text" name="metadata[courses_count]" value="{{ $heroBlock->metadata['courses_count'] ?? '45+' }}" class="w-full h-10 px-3 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                            </div>
                            <div>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block mb-1">{{ __('عدد الاستشاريين والمشرفين:') }}</span>
                                <input type="text" name="metadata[supervisors_count]" value="{{ $heroBlock->metadata['supervisors_count'] ?? '12+' }}" class="w-full h-10 px-3 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                            </div>
                            <div>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block mb-1">{{ __('نسبة رضا واجتياز المتدربين:') }}</span>
                                <input type="text" name="metadata[success_rate]" value="{{ $heroBlock->metadata['success_rate'] ?? '98%' }}" class="w-full h-10 px-3 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer flex items-center justify-center gap-2">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('تحديث وحفظ قسم الهيرو فورياً') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- 3. About Us & 4 Clinical Pillars --}}
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 mb-6">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                        {{ __('قسم من نحن وركائز المنصة الأربعة (About Us & Pillars)') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('تحكم في ركائز التميز السريري التي تميز منصة الأمين في الصفحة الرئيسية') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.settings.content.update', 'about') }}#cms" class="flex flex-col gap-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان قسم من نحن (بالعربية):') }}</label>
                            <input type="text" name="title_ar" value="{{ $aboutBlock->title_ar }}" required class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان قسم من نحن (بالإنجليزية):') }}</label>
                            <input type="text" name="title_en" value="{{ $aboutBlock->title_en }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الوصف التمهيدي للقسم:') }}</label>
                            <textarea name="subtitle_ar" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $aboutBlock->subtitle_ar }}</textarea>
                        </div>
                    </div>

                    {{-- 4 Pillars Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $pillars = $aboutBlock->metadata['pillars'] ?? [];
                        @endphp
                        @for($i = 0; $i < 4; $i++)
                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-800 flex flex-col gap-2.5">
                                <div class="flex items-center gap-2">
                                    <input type="text" name="metadata[pillars][{{ $i }}][icon]" value="{{ $pillars[$i]['icon'] ?? '🎯' }}" class="size-10 text-center text-lg rounded-lg border border-[#DEE8F7] dark:border-slate-700 bg-white dark:bg-slate-900" />
                                    <input type="text" name="metadata[pillars][{{ $i }}][title_ar]" value="{{ $pillars[$i]['title_ar'] ?? '' }}" placeholder="{{ __('عنوان الركيزة') }} {{ $i + 1 }}" class="flex-1 h-10 px-3 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                                    <input type="hidden" name="metadata[pillars][{{ $i }}][title_en]" value="{{ $pillars[$i]['title_en'] ?? '' }}" />
                                </div>
                                <textarea name="metadata[pillars][{{ $i }}][desc_ar]" rows="2" placeholder="{{ __('وصف الركيزة') }} {{ $i + 1 }}" class="w-full p-2.5 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $pillars[$i]['desc_ar'] ?? '' }}</textarea>
                                <input type="hidden" name="metadata[pillars][{{ $i }}][desc_en]" value="{{ $pillars[$i]['desc_en'] ?? '' }}" />
                            </div>
                        @endfor
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer flex items-center justify-center gap-2">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('حفظ وتطبيق ركائز المنصة فورياً') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 4: الأسئلة الشائعة (FAQ Management)                  --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'faq'" x-cloak class="flex flex-col gap-6">
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 mb-6">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                        {{ __('مدير الأسئلة الشائعة والأكثر تكراراً (FAQ Manager)') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('إضافة وتعديل وحذف الأسئلة والإجابات الشائعة في الموقع بدون لمس أي سطر برمجي') }}
                    </p>
                </div>

                @php
                    $faqItems = $faqBlock->metadata['items'] ?? [];
                @endphp

                <form method="POST" action="{{ route('admin.settings.content.update', 'faq') }}#faq" 
                      x-data="{ 
                          items: @js($faqItems),
                          addItem() {
                              this.items.push({
                                  question_ar: '',
                                  question_en: '',
                                  answer_ar: '',
                                  answer_en: ''
                              });
                          },
                          removeItem(index) {
                              window.AlameenConfirm({
                                  title: '{{ __('حذف السؤال') }}',
                                  message: '{{ __('هل أنت متأكد من حذف هذا السؤال من قائمة الأسئلة الشائعة؟') }}',
                                  confirmText: '{{ __('نعم، احذف السؤال') }}',
                                  onConfirm: () => {
                                      this.items.splice(index, 1);
                                  }
                              });
                          }
                      }" 
                      class="flex flex-col gap-6"
                >
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان قسم الأسئلة الشائعة:') }}</label>
                            <input type="text" name="title_ar" value="{{ $faqBlock->title_ar }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الوصف التوضيحي للقسم:') }}</label>
                            <input type="text" name="subtitle_ar" value="{{ $faqBlock->subtitle_ar }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        </div>
                    </div>

                    {{-- Dynamic FAQ Items List --}}
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="text-xs font-bold text-[#012383] dark:text-blue-400">{{ __('قائمة الأسئلة والإجابات المعتمدة:') }}</span>
                            <button type="button" @click="addItem()" class="px-3.5 py-1.5 rounded-lg bg-[#EAF1FB] dark:bg-slate-800 hover:bg-[#DEE8F7] text-[#012383] dark:text-blue-300 text-xs font-bold flex items-center gap-1.5 transition-colors cursor-pointer">
                                <span>+</span>
                                <span>{{ __('إضافة سؤال جديد') }}</span>
                            </button>
                        </div>

                        <template x-for="(item, idx) in items" :key="idx">
                            <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/70 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-800 flex flex-col gap-3 relative group">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-[#012383] dark:text-blue-400 flex items-center gap-2">
                                        <span class="size-6 rounded-full bg-[#012383] text-white flex items-center justify-center text-[11px]" x-text="idx + 1"></span>
                                        <span>{{ __('السؤال رقم') }} <span x-text="idx + 1"></span></span>
                                    </span>
                                    <button type="button" @click="removeItem(idx)" class="text-rose-600 hover:text-rose-800 text-xs font-bold p-1 rounded hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer">
                                        {{ __('حذف السؤال') }} ✕
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-[11px] font-bold text-slate-600 dark:text-slate-400 block mb-1">{{ __('نص السؤال (بالعربية):') }}</label>
                                        <input type="text" :name="'metadata[items][' + idx + '][question_ar]'" x-model="item.question_ar" required placeholder="مثال: كيف يتم اعتماد شهادتي السريرية؟" class="w-full h-10 px-3 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-bold text-slate-600 dark:text-slate-400 block mb-1">{{ __('نص السؤال (بالإنجليزية - اختياري):') }}</label>
                                        <input type="text" :name="'metadata[items][' + idx + '][question_en]'" x-model="item.question_en" placeholder="Question in English..." class="w-full h-10 px-3 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-[11px] font-bold text-slate-600 dark:text-slate-400 block mb-1">{{ __('نص الإجابة التفصيلية (بالعربية):') }}</label>
                                        <textarea :name="'metadata[items][' + idx + '][answer_ar]'" x-model="item.answer_ar" rows="2" required placeholder="اكتب الإجابة التي ستظهر للطلاب والزوار..." class="w-full p-2.5 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]"></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="items.length === 0" class="text-center py-8 text-slate-400 text-xs bg-slate-50 dark:bg-slate-800/30 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
                            {{ __('لا توجد أسئلة شائعة حالياً. انقر على "إضافة سؤال جديد" للبدء.') }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <button type="button" @click="addItem()" class="px-4 py-2.5 rounded-xl border border-[#012383] text-[#012383] dark:text-blue-400 hover:bg-[#EAF1FB] text-xs font-bold transition-colors cursor-pointer">
                            {{ __('+ إضافة سؤال آخر') }}
                        </button>
                        <button type="submit" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer flex items-center justify-center gap-2">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('حفظ وتطبيق الأسئلة الشائعة فورياً') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 5: السياسات الأكاديمية والمحاضرات (Academic Rules)   --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'academic'" x-cloak class="flex flex-col gap-6">
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 mb-6">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                        {{ __('السياسات الأكاديمية وحماية المحتوى والمحاضرات (Academic & LMS Policies)') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('معايير إصدار الشهادات، تفعيل/تعطيل القفل المتسلسل للفيديوهات، والعلامة المائية لمكافحة القرصنة') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}#academic" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @csrf

                    {{-- 1. Certificate Threshold % --}}
                    <div class="p-5 rounded-2xl bg-slate-50/70 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-800 flex flex-col justify-between gap-4">
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center gap-2 text-[#012383] dark:text-blue-400 font-bold text-sm">
                                <span class="size-2.5 rounded-full bg-amber-500 shrink-0"></span>
                                <span>{{ __('معيار إصدار الشهادات المعتمدة (Certificate Threshold)') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                {{ __('النسبة المئوية الإلزامية التي يجب على الطالب إكمالها في مشاهدة المحاضرات والمهام لإصدار الشهادة الرقمية المعتمدة برمز التحقق وQR') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="relative flex-1">
                                <input type="number" step="0.5" min="1" max="100" name="settings[certificate_min_percentage][ar]" value="{{ $settings['certificate_min_percentage']['value_ar'] ?? '95' }}" required class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-black font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                                <span class="absolute end-4 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">%</span>
                            </div>
                            <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-2 rounded-xl shrink-0">
                                {{ __('المعيار الموصى به: 95%') }}
                            </span>
                        </div>
                        <input type="hidden" name="settings[certificate_min_percentage][group]" value="academic" />
                    </div>

                    {{-- 2. Sequential Video Lock --}}
                    <div class="p-5 rounded-2xl bg-slate-50/70 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-800 flex flex-col justify-between gap-4">
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center gap-2 text-[#012383] dark:text-blue-400 font-bold text-sm">
                                <span class="size-2.5 rounded-full bg-blue-600 shrink-0"></span>
                                <span>{{ __('القفل المتسلسل للمحاضرات (Sequential Video Progression)') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                {{ __('عند التفعيل، لن يتمكن الطالب من فتح المحاضرة التالية إلا بعد إكمال نسبة المشاهدة المطلوبة للمحاضرة السابقة') }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-white dark:bg-slate-900">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('إلزام الطلاب بالتسلسل العلمي') }}</span>
                            <input type="hidden" name="settings[sequential_video_lock][ar]" value="0" />
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="settings[sequential_video_lock][ar]" value="1" {{ ($settings['sequential_video_lock']['value_ar'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer" />
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#012383]"></div>
                            </label>
                        </div>
                        <input type="hidden" name="settings[sequential_video_lock][group]" value="academic" />
                    </div>

                    {{-- 3. Dynamic Video Watermarking --}}
                    <div class="p-5 rounded-2xl bg-slate-50/70 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-800 flex flex-col justify-between gap-4">
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center gap-2 text-rose-700 dark:text-rose-400 font-bold text-sm">
                                <span class="size-2.5 rounded-full bg-rose-600 shrink-0"></span>
                                <span>{{ __('العلامة المائية المتحركة لمنع التسريب (Dynamic Watermark)') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                {{ __('إظهار رقم هاتف الطالب واسمه وعنوان IP عائماً بشكل عشوائي فوق مشغل الفيديو لمنع تصوير الشاشة وتسريب المحاضرات') }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-white dark:bg-slate-900">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('تفعيل الحماية المائية الصارمة') }}</span>
                            <input type="hidden" name="settings[dynamic_video_watermark][ar]" value="0" />
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="settings[dynamic_video_watermark][ar]" value="1" {{ ($settings['dynamic_video_watermark']['value_ar'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer" />
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>
                        <input type="hidden" name="settings[dynamic_video_watermark][group]" value="academic" />
                    </div>

                    {{-- 4. Student Self Registration --}}
                    <div class="p-5 rounded-2xl bg-slate-50/70 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-800 flex flex-col justify-between gap-4">
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center gap-2 text-[#012383] dark:text-blue-400 font-bold text-sm">
                                <span class="size-2.5 rounded-full bg-emerald-600 shrink-0"></span>
                                <span>{{ __('تسجيل الطلاب الجدد (Student Self-Registration)') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                {{ __('السماح للطلاب الجدد بإنشاء حسابات ذاتياً، أو حصر التسجيل على الإدارة ومسؤولي القبول فقط') }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 bg-white dark:bg-slate-900">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('التسجيل العام مفتوح للجميع') }}</span>
                            <input type="hidden" name="settings[student_registration_enabled][ar]" value="0" />
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="settings[student_registration_enabled][ar]" value="1" {{ ($settings['student_registration_enabled']['value_ar'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer" />
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>
                        <input type="hidden" name="settings[student_registration_enabled][group]" value="academic" />
                    </div>

                    <div class="md:col-span-2 flex justify-end pt-2">
                        <button type="submit" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer flex items-center justify-center gap-2">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('حفظ وتطبيق السياسات الأكاديمية فورياً') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Luxury Certificate Engine & Canva Template Customization --}}
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs flex flex-col gap-6">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🏆</span>
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                            {{ __('تصميم وتخصيص قالب الشهادة المعتمدة (Canva Maroon & Gold Master)') }}
                        </h3>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        {{ __('إدارة صورة قالب الشهادة الرسمي الفارغ، وتعديل اسم ومسمى التوقيع المعتمد، مع معاينة حية فورية') }}
                    </p>
                </div>

                {{-- Live Interactive Certificate Preview --}}
                <div class="p-4 sm:p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-800 flex flex-col items-center gap-3">
                    <span class="text-xs font-bold text-[#012383] dark:text-blue-300 self-start">
                        {{ __('معاينة حية للقالب المعتمد المعروض للطلاب عند الإتمام والتحقق:') }}
                    </span>
                    <div class="w-full max-w-xl mx-auto shadow-md rounded-xl overflow-hidden">
                        <x-certificate-preview 
                            student-name="Yara Mahmoud Ahmed" 
                            course-title="Level 1 of the Comprehensive Speech and Communication Course"
                        />
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}#academic" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @csrf

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('مسار أو رابط القالب الفارغ (Certificate Template Path):') }}</label>
                        <input type="text" name="settings[certificate_custom_bg][ar]" value="{{ $settings['certificate_custom_bg']['value_ar'] ?? '/assets/images/alameen-images/certificate.png' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <input type="hidden" name="settings[certificate_custom_bg][group]" value="certificates" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('أو رفع قالب صورة جديد من جهازك (PNG / JPG):') }}</label>
                        <input type="file" name="certificate_custom_bg_file" accept="image/png,image/jpeg,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#012383] file:text-white hover:file:bg-[#0B2A8F] cursor-pointer" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('اسم المدير العام المعتمد (Signatory Name):') }}</label>
                        <input type="text" name="certificate_signatory_name" value="{{ $settings['certificate_signatory_name']['value_ar'] ?? 'Hana El-Mekawy' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الصفة / المسمى الوظيفي (Signatory Title):') }}</label>
                        <input type="text" name="certificate_signatory_title" value="{{ $settings['certificate_signatory_title']['value_ar'] ?? 'General Manager' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    </div>

                    <div class="md:col-span-2 flex justify-end pt-2">
                        <button type="submit" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer flex items-center justify-center gap-2">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('حفظ وتطبيق إعدادات الشهادة فورياً') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 6: قنوات الدفع والتحويل (Payment Gateways)          --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'payments'" x-cloak class="flex flex-col gap-6">
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 mb-6">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                        {{ __('بيانات وقنوات التحصيل والدفع اليدوي (Vodafone Cash, InstaPay, Bank Wire)') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('تظهر هذه البيانات للطلاب فوراً عند اختيار الدفع اليدوي للاشتراك في المسارات التدريبية') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}#payments" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @csrf
                    
                    {{-- 1. Vodafone Cash --}}
                    <div class="p-5 rounded-2xl bg-slate-50/70 dark:bg-slate-800/40 border border-[#DEE8F7]/80 dark:border-slate-800 flex flex-col gap-3.5">
                        <div class="flex items-center justify-between border-b border-[#DEE8F7]/80 dark:border-slate-700/60 pb-2.5">
                            <div class="flex items-center gap-2 text-rose-700 dark:text-rose-400 font-bold text-sm">
                                <span class="size-2.5 rounded-full bg-rose-600 shrink-0"></span>
                                <span>{{ __('محفظة فودافون كاش (Vodafone Cash)') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="settings[vodafone_cash_enabled][ar]" value="0" />
                                <input type="checkbox" name="settings[vodafone_cash_enabled][ar]" value="1" {{ ($settings['vodafone_cash_enabled']['value_ar'] ?? '1') === '1' ? 'checked' : '' }} class="rounded border-slate-300 text-rose-600 focus:ring-rose-500" />
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ __('مفعل') }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-300 block mb-1">{{ __('رقم المحفظة المعتمد:') }}</label>
                            <input type="text" name="settings[vodafone_cash_number][ar]" value="{{ $settings['vodafone_cash_number']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 outline-none focus:border-[#0B2A8F]" />
                            <input type="hidden" name="settings[vodafone_cash_number][group]" value="payment_vodafone" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-300 block mb-1">{{ __('تعليمات التحويل (تظهر للطالب):') }}</label>
                            <input type="text" name="settings[vodafone_cash_instructions][ar]" value="{{ $settings['vodafone_cash_instructions']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 outline-none focus:border-[#0B2A8F]" />
                            <input type="hidden" name="settings[vodafone_cash_instructions][group]" value="payment_vodafone" />
                        </div>
                    </div>

                    {{-- 2. InstaPay --}}
                    <div class="p-5 rounded-2xl bg-slate-50/70 dark:bg-slate-800/40 border border-[#DEE8F7]/80 dark:border-slate-800 flex flex-col gap-3.5">
                        <div class="flex items-center justify-between border-b border-[#DEE8F7]/80 dark:border-slate-700/60 pb-2.5">
                            <div class="flex items-center gap-2 text-purple-700 dark:text-purple-400 font-bold text-sm">
                                <span class="size-2.5 rounded-full bg-purple-600 shrink-0"></span>
                                <span>{{ __('حساب إنستاباي للدفع اللحظي (InstaPay IPA)') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="settings[instapay_enabled][ar]" value="0" />
                                <input type="checkbox" name="settings[instapay_enabled][ar]" value="1" {{ ($settings['instapay_enabled']['value_ar'] ?? '1') === '1' ? 'checked' : '' }} class="rounded border-slate-300 text-purple-600 focus:ring-purple-500" />
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ __('مفعل') }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-300 block mb-1">{{ __('عنوان الدفع اللحظي (IPA Handle):') }}</label>
                            <input type="text" name="settings[instapay_handle][ar]" value="{{ $settings['instapay_handle']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold font-mono text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 outline-none focus:border-[#0B2A8F]" />
                            <input type="hidden" name="settings[instapay_handle][group]" value="payment_instapay" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-300 block mb-1">{{ __('تعليمات الدفع عبر إنستاباي:') }}</label>
                            <input type="text" name="settings[instapay_instructions][ar]" value="{{ $settings['instapay_instructions']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 outline-none focus:border-[#0B2A8F]" />
                            <input type="hidden" name="settings[instapay_instructions][group]" value="payment_instapay" />
                        </div>
                    </div>

                    {{-- 3. Direct Bank Wire Transfer --}}
                    <div class="p-5 rounded-2xl bg-slate-50/70 dark:bg-slate-800/40 border border-[#DEE8F7]/80 dark:border-slate-800 flex flex-col gap-3.5 md:col-span-2">
                        <div class="flex items-center justify-between border-b border-[#DEE8F7]/80 dark:border-slate-700/60 pb-2.5">
                            <div class="flex items-center gap-2 text-[#012383] dark:text-blue-400 font-bold text-sm">
                                <span class="size-2.5 rounded-full bg-[#0B2A8F] shrink-0"></span>
                                <span>{{ __('التحويل البنكي المباشر (Bank Transfer)') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="settings[bank_transfer_enabled][ar]" value="0" />
                                <input type="checkbox" name="settings[bank_transfer_enabled][ar]" value="1" {{ ($settings['bank_transfer_enabled']['value_ar'] ?? '1') === '1' ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ __('مفعل') }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-300 block mb-1">{{ __('اسم البنك:') }}</label>
                                <input type="text" name="settings[bank_name][ar]" value="{{ $settings['bank_name']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 outline-none focus:border-[#0B2A8F]" />
                                <input type="hidden" name="settings[bank_name][group]" value="payment_bank" />
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-300 block mb-1">{{ __('اسم صاحب الحساب:') }}</label>
                                <input type="text" name="settings[bank_account_holder][ar]" value="{{ $settings['bank_account_holder']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 outline-none focus:border-[#0B2A8F]" />
                                <input type="hidden" name="settings[bank_account_holder][group]" value="payment_bank" />
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-300 block mb-1">{{ __('رقم الآيبان (IBAN):') }}</label>
                                <input type="text" name="settings[bank_iban][ar]" value="{{ $settings['bank_iban']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono font-bold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 outline-none focus:border-[#0B2A8F]" />
                                <input type="hidden" name="settings[bank_iban][group]" value="payment_bank" />
                            </div>
                        </div>
                    </div>

                    {{-- 4. Auto Grant Enrollment --}}
                    <div class="md:col-span-2 p-4 rounded-2xl bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-200/80 dark:border-emerald-800/60 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="size-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">✓</span>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-emerald-900 dark:text-emerald-200">{{ __('التفعيل التلقائي عند اعتماد الإيصال (Auto-Grant Enrollment)') }}</span>
                                <span class="text-[11px] text-emerald-700/80 dark:text-emerald-300/80">{{ __('عند قبول إيصال السداد من المشرف المالي، يتم فتح الكورس تلقائياً بحساب الطالب دون أي تدخل يدوي') }}</span>
                            </div>
                        </div>
                        <input type="hidden" name="settings[receipt_auto_enroll][ar]" value="0" />
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="settings[receipt_auto_enroll][ar]" value="1" {{ ($settings['receipt_auto_enroll']['value_ar'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer" />
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                        <input type="hidden" name="settings[receipt_auto_enroll][group]" value="payment" />
                    </div>

                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer flex items-center justify-center gap-2">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('حفظ وتطبيق قنوات الدفع فورياً') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 7: مدير القوائم والتنقل (Dynamic Menu Manager)       --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'menus'" x-cloak class="flex flex-col gap-6">
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 mb-6">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                        {{ __('مدير القوائم والتنقل الديناميكي (Dynamic Menu Manager)') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('إضافة وتعديل وحذف روابط القائمة العلوية الرئيسية (Header) وقوائم التذييل (Footer) بدون أي تعديل بالكود') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($menus as $menu)
                        <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-800 flex flex-col gap-4">
                            <div class="flex items-center justify-between border-b border-[#DEE8F7] dark:border-slate-700/60 pb-3">
                                <div>
                                    <span class="font-bold text-sm text-[#012383] dark:text-blue-400 block">{{ $menu->name }}</span>
                                    <span class="font-mono text-[10px] text-slate-400">Location: {{ $menu->location }}</span>
                                </div>
                                <span class="font-mono text-[11px] text-blue-700 bg-blue-50 dark:bg-blue-950/40 dark:text-blue-300 px-2.5 py-1 rounded-lg border border-blue-200 dark:border-blue-800">
                                    {{ $menu->items->count() }} {{ __('روابط') }}
                                </span>
                            </div>

                            {{-- Menu Items List --}}
                            <div class="divide-y divide-[#DEE8F7]/60 dark:divide-slate-700/60 max-h-[360px] overflow-y-auto pr-1">
                                @forelse($menu->items as $item)
                                    <div class="py-3 flex items-center justify-between text-xs gap-3">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-800 dark:text-slate-200 truncate">{{ $item->title_ar }}</span>
                                                @if(!empty($item->title_en))
                                                    <span class="text-[10px] text-slate-400 font-medium">({{ $item->title_en }})</span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono block truncate mt-0.5">{{ $item->url }}</span>
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0">
                                            {{-- Toggle Active Status --}}
                                            <form method="POST" action="{{ route('admin.settings.menus.item.toggle', $item->id) }}#menus">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold cursor-pointer transition-colors {{ $item->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                                                    {{ $item->is_active ? __('نشط ✓') : __('معطل ✕') }}
                                                </button>
                                            </form>

                                            {{-- Delete Item --}}
                                            <form 
                                                method="POST" 
                                                action="{{ route('admin.settings.menus.item.delete', $item->id) }}#menus" 
                                                data-confirm="{{ __('هل أنت متأكد من حذف هذا الرابط من القائمة نهائياً؟') }}"
                                                data-confirm-title="{{ __('حذف رابط القائمة') }}"
                                                data-confirm-btn="{{ __('نعم، احذف الرابط') }}"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="size-7 rounded-lg text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 flex items-center justify-center transition-colors cursor-pointer" title="{{ __('حذف الرابط') }}">
                                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-4 text-center text-slate-400 text-xs">{{ __('لا توجد روابط مضافة في هذه القائمة بعد.') }}</div>
                                @endforelse
                            </div>

                            {{-- Add New Link Form --}}
                            <form method="POST" action="{{ route('admin.settings.menus.item', $menu->id) }}#menus" class="mt-2 pt-3 border-t border-[#DEE8F7] dark:border-slate-700/60 flex flex-col gap-2.5">
                                @csrf
                                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">{{ __('+ إضافة رابط جديد إلى القائمة:') }}</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <input type="text" name="title_ar" placeholder="{{ __('العنوان بالعربية (مطلوب)') }}" required class="h-9 px-3 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                                    <input type="text" name="title_en" placeholder="{{ __('العنوان بالإنجليزية (اختياري)') }}" class="h-9 px-3 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                                </div>
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                    <input type="text" name="url" placeholder="{{ __('الرابط e.g. /courses or https://...') }}" required class="h-9 px-3 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F] flex-1" />
                                    <select name="target" class="h-9 px-2 rounded-lg border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none shrink-0">
                                        <option value="_self">{{ __('نفس النافذة') }}</option>
                                        <option value="_blank">{{ __('نافذة جديدة') }}</option>
                                    </select>
                                    <button type="submit" class="h-9 px-4 rounded-lg bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold shrink-0 cursor-pointer text-center">
                                        {{ __('+ إضافة رابط') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 7: سحابة MUX للبث والتشفير (MUX Cloud & Security)    --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'mux'" x-cloak class="flex flex-col gap-6" x-data="{
            testing: false,
            testResult: null,
            async runTest() {
                this.testing = true;
                this.testResult = null;
                try {
                    const res = await fetch('{{ route('admin.settings.mux.test') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    this.testResult = await res.json();
                } catch (e) {
                    this.testResult = {
                        success: false,
                        message: '{{ __('تعذر تنفيذ اختبار الاتصال بسبب خطأ غير متوقع.') }}'
                    };
                } finally {
                    this.testing = false;
                }
            }
        }">
            {{-- Main MUX Settings Card --}}
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs flex flex-col gap-6">
                
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl">🎬</span>
                            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                                {{ __('إدارة سحابة MUX للبث الفيديوي والتشفير الصارم (MUX Video Cloud Control)') }}
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            {{ __('تحكم كامل ومباشر في مفاتيح API، التوقيع الرقمي (RS256 JWT)، سياسات حظر فتح ومشاركة الروابط، وتأمين الفيديوهات') }}
                        </p>
                    </div>

                    {{-- Connection Tester Button --}}
                    <button 
                        type="button" 
                        @click="runTest()" 
                        :disabled="testing"
                        class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white text-xs font-bold shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 shrink-0"
                    >
                        <svg x-show="!testing" class="size-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <svg x-show="testing" class="size-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span x-text="testing ? '{{ __('جاري الفحص المباشر...') }}' : '{{ __('⚡ فحص الاتصال والتشفير الآن') }}'"></span>
                    </button>
                </div>

                {{-- Dynamic Test Results Display --}}
                <div x-show="testResult !== null" x-cloak class="transition-all">
                    <div 
                        :class="testResult && testResult.success ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200' : 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 text-rose-900 dark:text-rose-200'" 
                        class="p-4 rounded-2xl border text-xs flex flex-col gap-2.5 shadow-xs"
                    >
                        <div class="flex items-center justify-between">
                            <span class="font-bold flex items-center gap-2 text-sm">
                                <span x-text="testResult && testResult.success ? '✓' : '✕'"></span>
                                <span x-text="testResult ? testResult.message : ''"></span>
                            </span>
                            <button type="button" @click="testResult = null" class="text-xs opacity-60 hover:opacity-100 cursor-pointer">✕</button>
                        </div>

                        <div x-show="testResult && testResult.api_connected" class="flex flex-wrap items-center gap-3 pt-2 border-t border-current/20 text-[11px] font-mono">
                            <span class="px-2.5 py-1 rounded-lg bg-white/60 dark:bg-black/30">
                                <strong>API:</strong> {{ __('متصل بنجاح ✓') }}
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-white/60 dark:bg-black/30">
                                <strong>Signing Key (RS256):</strong> 
                                <span x-text="testResult && testResult.signing_ready ? '{{ __('مفعل ومحمي ✓') }}' : '{{ __('غير مكتمل ✕') }}'"></span>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-white/60 dark:bg-black/30">
                                <strong>Policy:</strong> <span x-text="testResult ? testResult.playback_policy : ''"></span>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-white/60 dark:bg-black/30">
                                <strong>Active Assets:</strong> <span x-text="testResult ? testResult.recent_assets_count : 0"></span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Security Educational Alert --}}
                <div class="p-4 rounded-2xl bg-blue-50/70 dark:bg-blue-950/30 border border-blue-200/80 dark:border-blue-900 text-blue-900 dark:text-blue-200 text-xs flex items-start gap-3">
                    <svg class="size-5 text-[#012383] dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <div class="flex-1 space-y-1">
                        <span class="font-bold block text-sm">{{ __('كيف تحمي سياسة البث المشفر (Signed Policy) فيديوهاتك من المشاركة والتسريب؟') }}</span>
                        <p class="leading-relaxed text-blue-800/80 dark:text-blue-300">
                            {{ __('عند ضبط سياسة البث على "مشفر ومحمي برمز توقيع مؤقت (Signed)"، فإن رابط البث الأصلي') }} <code class="bg-blue-100 dark:bg-blue-900/60 px-1.5 py-0.5 rounded font-mono text-[11px]">https://stream.mux.com/ID.m3u8</code> {{ __('يُغلق تماماً برمز 403 Forbidden أمام أي شخص يحاول فتحه في المتصفح أو في برامج التنزيل، ويقوم السيرفر تلقائياً بإصدار رمز توقيع رقمي (JWT) مشفر بمفتاحك الخاص ومدته مؤقتة لا يمكن مشاركتها خارج جلسة الطالب المصرح له.') }}
                        </p>
                    </div>
                </div>

                {{-- MUX Credentials Form --}}
                <form method="POST" action="{{ route('admin.settings.update') }}#mux" class="flex flex-col gap-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        {{-- 1. MUX Token ID --}}
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                                {{ __('معرّف وصول MUX API (Token ID):') }} <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="settings[mux_token_id][ar]" 
                                value="{{ $settings['mux_token_id']['value_ar'] ?? config('video.drivers.mux.token_id') }}" 
                                placeholder="e.g. 74a9b1c2-xxxx-xxxx-xxxx-xxxxxxxxxxxx" 
                                class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" 
                            />
                            <input type="hidden" name="settings[mux_token_id][group]" value="mux" />
                            <span class="text-[11px] text-slate-400 mt-1 block">{{ __('من حسابك في Mux Dashboard > Settings > Access Tokens') }}</span>
                        </div>

                        {{-- 2. MUX Token Secret --}}
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                                {{ __('المفتاح السري (Token Secret):') }} <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="settings[mux_token_secret][ar]" 
                                value="{{ $settings['mux_token_secret']['value_ar'] ?? config('video.drivers.mux.token_secret') }}" 
                                placeholder="••••••••••••••••••••••••••••••••" 
                                class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" 
                            />
                            <input type="hidden" name="settings[mux_token_secret][group]" value="mux" />
                            <span class="text-[11px] text-slate-400 mt-1 block">{{ __('الرمز السري الخاص بالـ Access Token') }}</span>
                        </div>

                        {{-- 3. MUX Signing Key ID --}}
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                                {{ __('معرّف مفتاح التوقيع الرقمي (Signing Key ID):') }} <span class="text-emerald-600 font-extrabold">{{ __('(ضروري لتأمين الروابط)') }}</span>
                            </label>
                            <input 
                                type="text" 
                                name="settings[mux_signing_key_id][ar]" 
                                value="{{ $settings['mux_signing_key_id']['value_ar'] ?? config('video.drivers.mux.signing_key_id') }}" 
                                placeholder="e.g. k01xxxxxxx" 
                                class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" 
                            />
                            <input type="hidden" name="settings[mux_signing_key_id][group]" value="mux" />
                            <span class="text-[11px] text-slate-400 mt-1 block">{{ __('من Mux Dashboard > Settings > Signing Keys') }}</span>
                        </div>

                        {{-- 4. MUX Webhook Secret --}}
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                                {{ __('رمز أمان الـ Webhook (Webhook Secret):') }}
                            </label>
                            <input 
                                type="text" 
                                name="settings[mux_webhook_secret][ar]" 
                                value="{{ $settings['mux_webhook_secret']['value_ar'] ?? config('video.drivers.mux.webhook_secret') }}" 
                                placeholder="e.g. whsec_xxxxxxxxxxxx" 
                                class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" 
                            />
                            <input type="hidden" name="settings[mux_webhook_secret][group]" value="mux" />
                            <span class="text-[11px] text-slate-400 mt-1 block">{{ __('رابط الويب هوك الخاص بك:') }} <code class="font-mono">{{ url('webhooks/mux') }}</code></span>
                        </div>

                        {{-- 5. MUX Signing Private Key --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                                {{ __('مفتاح التشفير الخاص للتوقيع الرقمي (Signing Private Key - RSA 2048):') }} <span class="text-rose-500">*</span>
                            </label>
                            <textarea 
                                name="settings[mux_signing_key_private][ar]" 
                                rows="3" 
                                placeholder="-----BEGIN RSA PRIVATE KEY-----&#10;MIIEowIBAAKCAQEA...&#10;-----END RSA PRIVATE KEY----- (أو نص Base64 المحمل من MUX)" 
                                class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]"
                            >{{ $settings['mux_signing_key_private']['value_ar'] ?? config('video.drivers.mux.signing_key_private') }}</textarea>
                            <input type="hidden" name="settings[mux_signing_key_private][group]" value="mux" />
                            <span class="text-[11px] text-slate-400 mt-1 block">{{ __('الملف المشفر الذي يتم تحميله عند إنشاء مفتاح التوقيع من Mux (يدعم صيغة PEM أو Base64 مباشرة).') }}</span>
                        </div>

                        {{-- 6. Playback Security Policy --}}
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                                {{ __('سياسة أمان البث الافتراضية (Playback Policy):') }}
                            </label>
                            @php
                                $currentPolicy = $settings['mux_playback_policy']['value_ar'] ?? (is_array(config('video.drivers.mux.playback_policy')) ? config('video.drivers.mux.playback_policy')[0] : 'signed');
                            @endphp
                            <select 
                                name="settings[mux_playback_policy][ar]" 
                                class="w-full h-11 px-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 outline-none focus:border-[#0B2A8F] cursor-pointer"
                            >
                                <option value="signed" {{ $currentPolicy === 'signed' ? 'selected' : '' }}>
                                    🔒 {{ __('مشفر ومحمي برمز توقيع مؤقت (Signed JWT - موصى به للأمان الأقصى)') }}
                                </option>
                                <option value="public" {{ $currentPolicy === 'public' ? 'selected' : '' }}>
                                    🌐 {{ __('مفتوح للعامة (Public - غير آمن، يتيح فتح الرابط ومشاركته لأي شخص)') }}
                                </option>
                            </select>
                            <input type="hidden" name="settings[mux_playback_policy][group]" value="mux" />
                        </div>

                        {{-- 7. Token Lifetime --}}
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                                {{ __('مدة صلاحية توكن المشاهدة (بالثواني):') }}
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    min="300" 
                                    max="86400" 
                                    step="60" 
                                    name="settings[mux_token_lifetime][ar]" 
                                    value="{{ $settings['mux_token_lifetime']['value_ar'] ?? config('video.drivers.mux.token_lifetime', 3600) }}" 
                                    class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-mono font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" 
                                />
                                <span class="absolute end-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">{{ __('ثانية (3600 = ساعة)') }}</span>
                            </div>
                            <input type="hidden" name="settings[mux_token_lifetime][group]" value="mux" />
                            <span class="text-[11px] text-slate-400 mt-1 block">{{ __('يقوم المشغل بتجديد التوكن تلقائياً أثناء مشاهدة الطالب دون أي انقطاع.') }}</span>
                        </div>

                    </div>

                    <div class="flex items-center justify-end pt-3 border-t border-[#DEE8F7] dark:border-slate-800">
                        <button 
                            type="submit" 
                            class="px-8 h-12 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-extrabold text-sm shadow-md transition-all flex items-center gap-2 cursor-pointer active:scale-95"
                        >
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('حفظ إعدادات MUX وتطبيق الحماية فورياً') }}</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 8: المظهر والنظام (Theme & Appearance)               --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'theme'" x-cloak class="flex flex-col gap-6">
            
            {{-- Dark / Light Mode Segmented Control --}}
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs flex flex-col gap-5">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                        {{ __('مظهر لوحة التحكم والنظام (Theme & Appearance)') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('اختر نمط العرض المناسب لك: فاتح نهاري، داكن ليلي، أو متوافق تلقائياً مع نظام جهازك') }}
                    </p>
                </div>

                <x-admin::theme-switcher variant="segmented" />
            </div>

            {{-- Colors & Custom Branding Palette --}}
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs flex flex-col gap-6">
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🎨</span>
                        <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                            {{ __('ألوان المنصة والموقع (Platform Theme Palette)') }}
                        </h3>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        {{ __('تحكم كامل ومباشر في باليتة الألوان لجميع صفحات المنصة، الأزرار، التذييل، والتدرجات اللونية') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}#theme" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @csrf

                    {{-- 1. Primary Color --}}
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-700 flex flex-col gap-2.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('اللون الأساسي الرئيسي (Primary):') }}</label>
                        <div class="flex items-center gap-2.5">
                            <input type="color" oninput="document.getElementById('hex_primary').value = this.value" value="{{ $settings['theme_primary_color']['value_ar'] ?? '#012383' }}" class="size-10 rounded-xl border border-slate-300 dark:border-slate-600 cursor-pointer p-0.5 bg-white" />
                            <input type="text" id="hex_primary" name="settings[theme_primary_color][ar]" value="{{ $settings['theme_primary_color']['value_ar'] ?? '#012383' }}" class="flex-1 h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none" />
                        </div>
                        <input type="hidden" name="settings[theme_primary_color][group]" value="theme" />
                        <span class="text-[10px] text-slate-400">{{ __('الأزرار الرئيسية، العناوين، وشريط الترويسة') }}</span>
                    </div>

                    {{-- 2. Secondary Color --}}
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-700 flex flex-col gap-2.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('اللون الثانوي التفاعلي (Secondary):') }}</label>
                        <div class="flex items-center gap-2.5">
                            <input type="color" oninput="document.getElementById('hex_secondary').value = this.value" value="{{ $settings['theme_secondary_color']['value_ar'] ?? '#0B2A8F' }}" class="size-10 rounded-xl border border-slate-300 dark:border-slate-600 cursor-pointer p-0.5 bg-white" />
                            <input type="text" id="hex_secondary" name="settings[theme_secondary_color][ar]" value="{{ $settings['theme_secondary_color']['value_ar'] ?? '#0B2A8F' }}" class="flex-1 h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none" />
                        </div>
                        <input type="hidden" name="settings[theme_secondary_color][group]" value="theme" />
                        <span class="text-[10px] text-slate-400">{{ __('تأثيرات Hover، الروابط النشطة، والتدرجات') }}</span>
                    </div>

                    {{-- 3. Accent / Emerald Color --}}
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-700 flex flex-col gap-2.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('لون التمييز والنجاح (Accent / Emerald):') }}</label>
                        <div class="flex items-center gap-2.5">
                            <input type="color" oninput="document.getElementById('hex_accent').value = this.value" value="{{ $settings['theme_accent_color']['value_ar'] ?? '#10B77F' }}" class="size-10 rounded-xl border border-slate-300 dark:border-slate-600 cursor-pointer p-0.5 bg-white" />
                            <input type="text" id="hex_accent" name="settings[theme_accent_color][ar]" value="{{ $settings['theme_accent_color']['value_ar'] ?? '#10B77F' }}" class="flex-1 h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none" />
                        </div>
                        <input type="hidden" name="settings[theme_accent_color][group]" value="theme" />
                        <span class="text-[10px] text-slate-400">{{ __('شارات النجاح، نسب الإنجاز، وزر واتساب') }}</span>
                    </div>

                    {{-- 4. Gold / Amber Color --}}
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-700 flex flex-col gap-2.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('اللون الذهبي الملكي (Gold / Luxury):') }}</label>
                        <div class="flex items-center gap-2.5">
                            <input type="color" oninput="document.getElementById('hex_gold').value = this.value" value="{{ $settings['theme_gold_color']['value_ar'] ?? '#C59B27' }}" class="size-10 rounded-xl border border-slate-300 dark:border-slate-600 cursor-pointer p-0.5 bg-white" />
                            <input type="text" id="hex_gold" name="settings[theme_gold_color][ar]" value="{{ $settings['theme_gold_color']['value_ar'] ?? '#C59B27' }}" class="flex-1 h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none" />
                        </div>
                        <input type="hidden" name="settings[theme_gold_color][group]" value="theme" />
                        <span class="text-[10px] text-slate-400">{{ __('الشهادات المعتمدة، الشارات الذهبية، ونجوم التقييم') }}</span>
                    </div>

                    {{-- 5. Footer Background Color --}}
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-700 flex flex-col gap-2.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('لون خلفية التذييل (Footer Dark BG):') }}</label>
                        <div class="flex items-center gap-2.5">
                            <input type="color" oninput="document.getElementById('hex_footer_bg').value = this.value" value="{{ $settings['theme_footer_bg']['value_ar'] ?? '#031338' }}" class="size-10 rounded-xl border border-slate-300 dark:border-slate-600 cursor-pointer p-0.5 bg-white" />
                            <input type="text" id="hex_footer_bg" name="settings[theme_footer_bg][ar]" value="{{ $settings['theme_footer_bg']['value_ar'] ?? '#031338' }}" class="flex-1 h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none" />
                        </div>
                        <input type="hidden" name="settings[theme_footer_bg][group]" value="theme" />
                        <span class="text-[10px] text-slate-400">{{ __('الخلفية الداكنة الفاخرة لتذييل الموقع الرئيسي') }}</span>
                    </div>

                    {{-- 6. Login Split Visual Image --}}
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-[#DEE8F7] dark:border-slate-700 flex flex-col gap-2.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('صورة صفحة الدخول (Login Art Image):') }}</label>
                        <input type="text" name="settings[auth_login_image][ar]" value="{{ $settings['auth_login_image']['value_ar'] ?? '/assets/images/alameen-images/6.jpeg' }}" class="h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none" />
                        <input type="hidden" name="settings[auth_login_image][group]" value="theme" />
                        <span class="text-[10px] text-slate-400">{{ __('الصورة الجانبية الفاخرة المعروضة بصفحة تسجيل الدخول') }}</span>
                    </div>

                    <div class="col-span-full flex justify-end pt-2">
                        <button type="submit" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer flex items-center justify-center gap-2">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('حفظ وتطبيق ألوان المنصة فورياً') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 10: إدارة صفحات الطالب (Student Pages Visibility)    --}}
        {{-- ======================================================== --}}
        <div
            x-show="activeTab === 'pages'"
            x-cloak
            class="flex flex-col gap-6"
            x-data="{
                dashboardEnabled: {{ ($settings['page_student_dashboard_enabled']['value_ar'] ?? '0') === '1' ? 'true' : 'false' }},
                coursesEnabled: {{ ($settings['page_courses_enabled']['value_ar'] ?? '1') === '1' ? 'true' : 'false' }},
                tasksEnabled: {{ ($settings['page_tasks_enabled']['value_ar'] ?? '0') === '1' ? 'true' : 'false' }},
                gradesEnabled: {{ ($settings['page_grades_enabled']['value_ar'] ?? '0') === '1' ? 'true' : 'false' }},
                notificationsEnabled: {{ ($settings['page_notifications_enabled']['value_ar'] ?? '0') === '1' ? 'true' : 'false' }},
                settingsEnabled: {{ ($settings['page_settings_enabled']['value_ar'] ?? '1') === '1' ? 'true' : 'false' }}
            }"
        >
            <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs">
                
                {{-- Header --}}
                <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="size-8 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-[#012383] dark:text-blue-400 flex items-center justify-center font-bold text-base">👁️</span>
                            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400">
                                {{ __('التحكم في ظهور وإخفاء صفحات بوابة الطالب (Student Portal Pages)') }}
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            {{ __('تحديد الصفحات المتاحة في القائمة الجانبية للطلاب. يمكنك تمكين الكورسات فقط للطلاب حالياً وتفعيل أي صفحة أخرى مستقبلاً بضغطة زر واحدة.') }}
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>{{ __('توجيه ذكي تلقائي مفعل') }}</span>
                        </span>
                    </div>
                </div>

                {{-- Alert Callout --}}
                <div class="p-4 rounded-2xl bg-gradient-to-l from-blue-50 via-[#F4F8FD] to-white dark:from-slate-800/60 dark:to-slate-800/30 border border-[#DEE8F7] dark:border-slate-700 mb-6 flex gap-3.5 items-start text-xs text-slate-700 dark:text-slate-300">
                    <span class="text-lg shrink-0">💡</span>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold text-[#012383] dark:text-blue-300">{{ __('كيف يعمل نظام إخفاء الصفحات؟') }}</span>
                        <p class="leading-relaxed text-slate-600 dark:text-slate-400">
                            {{ __('عند إخفاء أي صفحة، تختفي فوراً من القائمة الجانبية وشريط التنقل للطلاب، وإذا حاول أي طالب الوصول إليها مباشرة عبر الرابط يتم تحويله تلقائياً وبأمان إلى صفحة (دوراتي ومحاضراتي).') }}
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}#pages" class="flex flex-col gap-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Card 1: Student Dashboard --}}
                        <div class="p-5 rounded-2xl border transition-all duration-200 flex flex-col justify-between gap-4"
                            :class="dashboardEnabled 
                                ? 'bg-white dark:bg-slate-900 border-blue-200 dark:border-blue-800/80 shadow-xs ring-1 ring-blue-500/10' 
                                : 'bg-slate-50/70 dark:bg-slate-800/30 border-dashed border-slate-300 dark:border-slate-700 opacity-80'"
                        >
                            <input type="hidden" name="settings[page_student_dashboard_enabled][ar]" :value="dashboardEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_student_dashboard_enabled][en]" :value="dashboardEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_student_dashboard_enabled][group]" value="pages" />

                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <div class="size-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center shrink-0 shadow-xs font-bold text-base">
                                        📊
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ __('لوحة الطالب الأكاديمية (Student Dashboard)') }}</h4>
                                        </div>
                                        <span class="font-mono text-[11px] text-slate-400 dark:text-slate-500 dir-ltr text-start">/dashboard</span>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mt-1">
                                            {{ __('عرض الإحصائيات الشاملة، نسبة الإنجاز العامة، بطاقات متابعة المحاضرات الأخيرة، والتوصيات.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                                <div>
                                    <span x-show="dashboardEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        {{ __('مفعلة وظاهرة للطلاب') }}
                                    </span>
                                    <span x-show="!dashboardEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200/80 text-slate-600 dark:bg-slate-700/60 dark:text-slate-300">
                                        <span class="size-1.5 rounded-full bg-slate-400"></span>
                                        {{ __('مخفية مؤقتاً') }}
                                    </span>
                                </div>

                                <button
                                    type="button"
                                    @click="dashboardEnabled = !dashboardEnabled"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="dashboardEnabled ? 'bg-[#012383]' : 'bg-slate-300 dark:bg-slate-700'"
                                    role="switch"
                                    :aria-checked="dashboardEnabled"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                        :class="dashboardEnabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0'"
                                    ></span>
                                </button>
                            </div>
                        </div>

                        {{-- Card 2: My Courses & Lectures --}}
                        <div class="p-5 rounded-2xl border transition-all duration-200 flex flex-col justify-between gap-4"
                            :class="coursesEnabled 
                                ? 'bg-white dark:bg-slate-900 border-blue-200 dark:border-blue-800/80 shadow-xs ring-1 ring-blue-500/10' 
                                : 'bg-slate-50/70 dark:bg-slate-800/30 border-dashed border-slate-300 dark:border-slate-700 opacity-80'"
                        >
                            <input type="hidden" name="settings[page_courses_enabled][ar]" :value="coursesEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_courses_enabled][en]" :value="coursesEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_courses_enabled][group]" value="pages" />

                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <div class="size-10 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white flex items-center justify-center shrink-0 shadow-xs font-bold text-base">
                                        📚
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ __('دوراتي ومحاضراتي (My Courses & Lectures)') }}</h4>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">{{ __('الأساسية') }}</span>
                                        </div>
                                        <span class="font-mono text-[11px] text-slate-400 dark:text-slate-500 dir-ltr text-start">/courses</span>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mt-1">
                                            {{ __('الصفحة التعليمية المركزية: استعراض الكورسات، المستويات، الفيديوهات، ومتابعة الدروس المكتملة.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                                <div>
                                    <span x-show="coursesEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        {{ __('مفعلة ونشطة حالياً للطلاب') }}
                                    </span>
                                    <span x-show="!coursesEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200/80 text-slate-600 dark:bg-slate-700/60 dark:text-slate-300">
                                        <span class="size-1.5 rounded-full bg-slate-400"></span>
                                        {{ __('مخفية مؤقتاً') }}
                                    </span>
                                </div>

                                <button
                                    type="button"
                                    @click="coursesEnabled = !coursesEnabled"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="coursesEnabled ? 'bg-[#012383]' : 'bg-slate-300 dark:bg-slate-700'"
                                    role="switch"
                                    :aria-checked="coursesEnabled"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                        :class="coursesEnabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0'"
                                    ></span>
                                </button>
                            </div>
                        </div>

                        {{-- Card 3: Clinical Tasks --}}
                        <div class="p-5 rounded-2xl border transition-all duration-200 flex flex-col justify-between gap-4"
                            :class="tasksEnabled 
                                ? 'bg-white dark:bg-slate-900 border-blue-200 dark:border-blue-800/80 shadow-xs ring-1 ring-blue-500/10' 
                                : 'bg-slate-50/70 dark:bg-slate-800/30 border-dashed border-slate-300 dark:border-slate-700 opacity-80'"
                        >
                            <input type="hidden" name="settings[page_tasks_enabled][ar]" :value="tasksEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_tasks_enabled][en]" :value="tasksEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_tasks_enabled][group]" value="pages" />

                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <div class="size-10 rounded-xl bg-gradient-to-br from-purple-600 to-indigo-700 text-white flex items-center justify-center shrink-0 shadow-xs font-bold text-base">
                                        📝
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ __('المهام والتطبيقات السريرية (Clinical Tasks)') }}</h4>
                                        </div>
                                        <span class="font-mono text-[11px] text-slate-400 dark:text-slate-500 dir-ltr text-start">/assignment</span>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mt-1">
                                            {{ __('تسليم المهام والتسجيلات الصوتية ومخارج الحروف لمراجعتها وتقييمها من المشرفين.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                                <div>
                                    <span x-show="tasksEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        {{ __('مفعلة وظاهرة للطلاب') }}
                                    </span>
                                    <span x-show="!tasksEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200/80 text-slate-600 dark:bg-slate-700/60 dark:text-slate-300">
                                        <span class="size-1.5 rounded-full bg-slate-400"></span>
                                        {{ __('مخفية مؤقتاً') }}
                                    </span>
                                </div>

                                <button
                                    type="button"
                                    @click="tasksEnabled = !tasksEnabled"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="tasksEnabled ? 'bg-[#012383]' : 'bg-slate-300 dark:bg-slate-700'"
                                    role="switch"
                                    :aria-checked="tasksEnabled"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                        :class="tasksEnabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0'"
                                    ></span>
                                </button>
                            </div>
                        </div>

                        {{-- Card 4: My Grades & Evaluations --}}
                        <div class="p-5 rounded-2xl border transition-all duration-200 flex flex-col justify-between gap-4"
                            :class="gradesEnabled 
                                ? 'bg-white dark:bg-slate-900 border-blue-200 dark:border-blue-800/80 shadow-xs ring-1 ring-blue-500/10' 
                                : 'bg-slate-50/70 dark:bg-slate-800/30 border-dashed border-slate-300 dark:border-slate-700 opacity-80'"
                        >
                            <input type="hidden" name="settings[page_grades_enabled][ar]" :value="gradesEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_grades_enabled][en]" :value="gradesEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_grades_enabled][group]" value="pages" />

                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <div class="size-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center shrink-0 shadow-xs font-bold text-base">
                                        📈
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ __('درجاتي وتقييماتي (My Grades & Evaluations)') }}</h4>
                                        </div>
                                        <span class="font-mono text-[11px] text-slate-400 dark:text-slate-500 dir-ltr text-start">/grades</span>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mt-1">
                                            {{ __('كشف الدرجات والتقييمات، ملاحظات المحاضرين السريرية، ونتائج التكليفات.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                                <div>
                                    <span x-show="gradesEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        {{ __('مفعلة وظاهرة للطلاب') }}
                                    </span>
                                    <span x-show="!gradesEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200/80 text-slate-600 dark:bg-slate-700/60 dark:text-slate-300">
                                        <span class="size-1.5 rounded-full bg-slate-400"></span>
                                        {{ __('مخفية مؤقتاً') }}
                                    </span>
                                </div>

                                <button
                                    type="button"
                                    @click="gradesEnabled = !gradesEnabled"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="gradesEnabled ? 'bg-[#012383]' : 'bg-slate-300 dark:bg-slate-700'"
                                    role="switch"
                                    :aria-checked="gradesEnabled"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                        :class="gradesEnabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0'"
                                    ></span>
                                </button>
                            </div>
                        </div>

                        {{-- Card 5: Notifications & Support --}}
                        <div class="p-5 rounded-2xl border transition-all duration-200 flex flex-col justify-between gap-4"
                            :class="notificationsEnabled 
                                ? 'bg-white dark:bg-slate-900 border-blue-200 dark:border-blue-800/80 shadow-xs ring-1 ring-blue-500/10' 
                                : 'bg-slate-50/70 dark:bg-slate-800/30 border-dashed border-slate-300 dark:border-slate-700 opacity-80'"
                        >
                            <input type="hidden" name="settings[page_notifications_enabled][ar]" :value="notificationsEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_notifications_enabled][en]" :value="notificationsEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_notifications_enabled][group]" value="pages" />

                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <div class="size-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 text-white flex items-center justify-center shrink-0 shadow-xs font-bold text-base">
                                        🔔
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ __('الإشعارات والدعم الفني (Notifications & Support)') }}</h4>
                                        </div>
                                        <span class="font-mono text-[11px] text-slate-400 dark:text-slate-500 dir-ltr text-start">/notifications & /support</span>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mt-1">
                                            {{ __('صندوق الإشعارات المباشرة ونظام تذاكر الدعم الفني والمحادثة التفاعلية مع الإدارة.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                                <div>
                                    <span x-show="notificationsEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        {{ __('مفعلة وظاهرة للطلاب') }}
                                    </span>
                                    <span x-show="!notificationsEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200/80 text-slate-600 dark:bg-slate-700/60 dark:text-slate-300">
                                        <span class="size-1.5 rounded-full bg-slate-400"></span>
                                        {{ __('مخفية مؤقتاً') }}
                                    </span>
                                </div>

                                <button
                                    type="button"
                                    @click="notificationsEnabled = !notificationsEnabled"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="notificationsEnabled ? 'bg-[#012383]' : 'bg-slate-300 dark:bg-slate-700'"
                                    role="switch"
                                    :aria-checked="notificationsEnabled"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                        :class="notificationsEnabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0'"
                                    ></span>
                                </button>
                            </div>
                        </div>

                        {{-- Card 6: Settings & Security --}}
                        <div class="p-5 rounded-2xl border transition-all duration-200 flex flex-col justify-between gap-4"
                            :class="settingsEnabled 
                                ? 'bg-white dark:bg-slate-900 border-blue-200 dark:border-blue-800/80 shadow-xs ring-1 ring-blue-500/10' 
                                : 'bg-slate-50/70 dark:bg-slate-800/30 border-dashed border-slate-300 dark:border-slate-700 opacity-80'"
                        >
                            <input type="hidden" name="settings[page_settings_enabled][ar]" :value="settingsEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_settings_enabled][en]" :value="settingsEnabled ? '1' : '0'" />
                            <input type="hidden" name="settings[page_settings_enabled][group]" value="pages" />

                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <div class="size-10 rounded-xl bg-gradient-to-br from-slate-700 to-slate-900 text-white flex items-center justify-center shrink-0 shadow-xs font-bold text-base">
                                        ⚙️
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ __('الإعدادات والأمان (Settings & Security)') }}</h4>
                                        </div>
                                        <span class="font-mono text-[11px] text-slate-400 dark:text-slate-500 dir-ltr text-start">/settings/profile</span>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mt-1">
                                            {{ __('تعديل الاسم والبريد، تغيير كلمة المرور، تفضيلات المظهر المظلم/الفاتح، وبيانات الحساب.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                                <div>
                                    <span x-show="settingsEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        {{ __('مفعلة وظاهرة للطلاب') }}
                                    </span>
                                    <span x-show="!settingsEnabled" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200/80 text-slate-600 dark:bg-slate-700/60 dark:text-slate-300">
                                        <span class="size-1.5 rounded-full bg-slate-400"></span>
                                        {{ __('مخفية مؤقتاً') }}
                                    </span>
                                </div>

                                <button
                                    type="button"
                                    @click="settingsEnabled = !settingsEnabled"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="settingsEnabled ? 'bg-[#012383]' : 'bg-slate-300 dark:bg-slate-700'"
                                    role="switch"
                                    :aria-checked="settingsEnabled"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                        :class="settingsEnabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0'"
                                    ></span>
                                </button>
                            </div>
                        </div>

                    </div>

                    {{-- Submit button --}}
                    <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="submit" class="px-7 h-12 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-sm shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center justify-center gap-2.5">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('حفظ وتطبيق إعدادات ظهور الصفحات فورياً') }}</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</x-admin-layout::admin>
