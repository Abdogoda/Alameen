<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading level="2" class="sr-only">{{ __('المظهر والتخصيص') }}</flux:heading>

    <x-settings.layout :heading="__('المظهر وتخصيص تجربة التعلم')" :subheading="__('خصص نمط الألوان، حجم خطوط الدروس، وتفضيلات الحركة والتحميل بما يناسب راحتك')">
        
        <div
            x-data="{
                theme: (window.AlameenTheme ? window.AlameenTheme.getTheme() : (localStorage.getItem('alameen_theme') || '{{ $theme }}')),
                fontSize: (function() { try { return localStorage.getItem('alameen_font_size') || '{{ $fontSize }}'; } catch (e) { return '{{ $fontSize }}'; } })(),
                motion: (function() { try { return localStorage.getItem('alameen_motion') || '{{ $motion }}'; } catch (e) { return '{{ $motion }}'; } })(),
                loaderEnabled: (function() { try { const v = localStorage.getItem('alameen_loader_enabled'); return v !== null ? (v !== 'false') : {{ $loaderEnabled ? 'true' : 'false' }}; } catch (e) { return {{ $loaderEnabled ? 'true' : 'false' }}; } })(),
                scrollProgressEnabled: (function() { try { const v = localStorage.getItem('alameen_scroll_progress'); return v !== null ? (v !== 'false') : {{ $scrollProgressEnabled ? 'true' : 'false' }}; } catch (e) { return {{ $scrollProgressEnabled ? 'true' : 'false' }}; } })(),
                showToast: false,
                toastMessage: '',

                init() {
                    // Sync initial attributes on DOM
                    const root = document.documentElement;
                    root.setAttribute('data-font-size', this.fontSize);
                    root.setAttribute('data-motion', this.motion);
                    root.setAttribute('data-loader-enabled', this.loaderEnabled ? 'true' : 'false');
                    root.setAttribute('data-scroll-progress-enabled', this.scrollProgressEnabled ? 'true' : 'false');

                    const bar = document.getElementById('alameen-scroll-progress');
                    if (bar) {
                        bar.style.display = this.scrollProgressEnabled ? 'block' : 'none';
                    }

                    window.addEventListener('alameen-theme-changed', (e) => {
                        this.theme = e.detail.theme;
                    });
                },

                setTheme(val) {
                    this.theme = val;
                    if (window.AlameenTheme) {
                        window.AlameenTheme.setTheme(val);
                    } else {
                        try { localStorage.setItem('alameen_theme', val); } catch (e) {}
                        const isDark = val === 'dark' || (val === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                        document.documentElement.classList.toggle('dark', isDark);
                        document.documentElement.setAttribute('data-theme', val);
                    }
                },

                setFontSize(size) {
                    this.fontSize = size;
                    try { localStorage.setItem('alameen_font_size', size); } catch (e) {}
                    document.documentElement.setAttribute('data-font-size', size);
                },

                setMotion(mode) {
                    this.motion = mode;
                    try { localStorage.setItem('alameen_motion', mode); } catch (e) {}
                    document.documentElement.setAttribute('data-motion', mode);
                },

                toggleLoader() {
                    this.loaderEnabled = !this.loaderEnabled;
                    try { localStorage.setItem('alameen_loader_enabled', this.loaderEnabled ? 'true' : 'false'); } catch (e) {}
                    document.documentElement.setAttribute('data-loader-enabled', this.loaderEnabled ? 'true' : 'false');
                },

                toggleScrollProgress() {
                    this.scrollProgressEnabled = !this.scrollProgressEnabled;
                    try { localStorage.setItem('alameen_scroll_progress', this.scrollProgressEnabled ? 'true' : 'false'); } catch (e) {}
                    document.documentElement.setAttribute('data-scroll-progress-enabled', this.scrollProgressEnabled ? 'true' : 'false');
                    const bar = document.getElementById('alameen-scroll-progress');
                    if (bar) {
                        bar.style.display = this.scrollProgressEnabled ? 'block' : 'none';
                    }
                },

                savePreferences() {
                    // 1. Client-side persistence
                    try {
                        localStorage.setItem('alameen_theme', this.theme);
                        localStorage.setItem('alameen_font_size', this.fontSize);
                        localStorage.setItem('alameen_motion', this.motion);
                        localStorage.setItem('alameen_loader_enabled', this.loaderEnabled ? 'true' : 'false');
                        localStorage.setItem('alameen_scroll_progress', this.scrollProgressEnabled ? 'true' : 'false');
                    } catch (e) {}

                    // 2. Server-side persistence via Livewire
                    if (window.Livewire && $wire && typeof $wire.saveSettings === 'function') {
                        $wire.saveSettings({
                            theme: this.theme,
                            fontSize: this.fontSize,
                            motion: this.motion,
                            loaderEnabled: this.loaderEnabled,
                            scrollProgressEnabled: this.scrollProgressEnabled
                        });
                    }

                    this.toastMessage = '{{ __('تم حفظ وتطبيق تفضيلات المظهر بنجاح!') }}';
                    this.showToast = true;
                    setTimeout(() => { this.showToast = false; }, 3500);
                }
            }"
            class="space-y-10 w-full"
        >

            <!-- ======================================================== -->
            <!-- 1. COLOR THEME MODE CARDS                                -->
            <!-- ======================================================== -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base text-[#012383] dark:text-blue-400 flex items-center gap-2">
                            <svg class="size-5 text-[#0B2A8F] dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 4.5 4.5 0 013.9-4.4M17 21a4 4 0 004-4 4.5 4.5 0 00-3.9-4.4M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>{{ __('نمط ألوان الواجهة') }}</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('اختر السمة اللونية المناسبة لعينيك أثناء المذاكرة وجلسات التدريب') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-4">
                    
                    <!-- A. Light Theme Card -->
                    <button
                        type="button"
                        @click="setTheme('light')"
                        :class="theme === 'light' ? 'ring-2 ring-[#012383] dark:ring-blue-400 border-transparent bg-blue-50/40 dark:bg-slate-800' : 'border-[#DEE8F7] dark:border-slate-800 hover:border-[#012383]/40 bg-white dark:bg-slate-900/60'"
                        class="group relative flex flex-col items-start p-4 rounded-2xl border text-start transition-all cursor-pointer shadow-xs hover:shadow-md"
                    >
                        <!-- Visual Miniature Mockup -->
                        <div class="w-full h-24 rounded-xl bg-[#FAFCFF] border border-[#DEE8F7] p-2 flex flex-col justify-between mb-3.5 shadow-2xs group-hover:scale-[1.01] transition-transform">
                            <div class="flex items-center justify-between">
                                <div class="h-2 w-12 rounded-full bg-[#012383]/20"></div>
                                <div class="size-2 rounded-full bg-emerald-500"></div>
                            </div>
                            <div class="space-y-1.5">
                                <div class="h-2 w-8 rounded-full bg-[#012383]/30"></div>
                                <div class="h-1.5 w-full rounded bg-slate-200"></div>
                                <div class="h-3 w-10 rounded bg-[#012383]"></div>
                            </div>
                        </div>

                        <!-- Card Header & Status -->
                        <div class="w-full flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="size-7 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-500 dark:text-amber-400 flex items-center justify-center">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="font-bold text-sm text-slate-800 dark:text-white block">{{ __('النمط النهاري') }}</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 block">{{ __('فاتح ومنعش') }}</span>
                                </div>
                            </div>

                            <span
                                x-show="theme === 'light'"
                                class="size-5 rounded-full bg-[#012383] text-white flex items-center justify-center shadow-xs"
                            >
                                <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                        </div>
                    </button>

                    <!-- B. Dark Theme Card -->
                    <button
                        type="button"
                        @click="setTheme('dark')"
                        :class="theme === 'dark' ? 'ring-2 ring-[#012383] dark:ring-blue-400 border-transparent bg-blue-50/40 dark:bg-slate-800' : 'border-[#DEE8F7] dark:border-slate-800 hover:border-[#012383]/40 bg-white dark:bg-slate-900/60'"
                        class="group relative flex flex-col items-start p-4 rounded-2xl border text-start transition-all cursor-pointer shadow-xs hover:shadow-md"
                    >
                        <!-- Visual Miniature Mockup -->
                        <div class="w-full h-24 rounded-xl bg-[#0B1120] border border-slate-700/80 p-2 flex flex-col justify-between mb-3.5 shadow-2xs group-hover:scale-[1.01] transition-transform">
                            <div class="flex items-center justify-between">
                                <div class="h-2 w-12 rounded-full bg-blue-400/20"></div>
                                <div class="size-2 rounded-full bg-emerald-400"></div>
                            </div>
                            <div class="space-y-1.5">
                                <div class="h-2 w-8 rounded-full bg-blue-400/30"></div>
                                <div class="h-1.5 w-full rounded bg-slate-700"></div>
                                <div class="h-3 w-10 rounded bg-blue-600"></div>
                            </div>
                        </div>

                        <!-- Card Header & Status -->
                        <div class="w-full flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="size-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-500 dark:text-indigo-400 flex items-center justify-center">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="font-bold text-sm text-slate-800 dark:text-white block">{{ __('النمط الليلي') }}</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 block">{{ __('مريح للعين') }}</span>
                                </div>
                            </div>

                            <span
                                x-show="theme === 'dark'"
                                class="size-5 rounded-full bg-[#012383] dark:bg-blue-500 text-white flex items-center justify-center shadow-xs"
                            >
                                <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                        </div>
                    </button>

                    <!-- C. System Match Card -->
                    <button
                        type="button"
                        @click="setTheme('system')"
                        :class="theme === 'system' ? 'ring-2 ring-[#012383] dark:ring-blue-400 border-transparent bg-blue-50/40 dark:bg-slate-800' : 'border-[#DEE8F7] dark:border-slate-800 hover:border-[#012383]/40 bg-white dark:bg-slate-900/60'"
                        class="group relative flex flex-col items-start p-4 rounded-2xl border text-start transition-all cursor-pointer shadow-xs hover:shadow-md"
                    >
                        <!-- Visual Miniature Mockup (Split Light/Dark) -->
                        <div class="w-full h-24 rounded-xl border border-[#DEE8F7] dark:border-slate-700/80 p-2 flex flex-col justify-between mb-3.5 shadow-2xs group-hover:scale-[1.01] transition-transform overflow-hidden relative bg-gradient-to-r from-[#FAFCFF] via-[#FAFCFF] to-[#0B1120]">
                            <div class="flex items-center justify-between">
                                <div class="h-2 w-12 rounded-full bg-[#012383]/20"></div>
                                <div class="size-2 rounded-full bg-emerald-500"></div>
                            </div>
                            <div class="space-y-1.5">
                                <div class="h-2 w-8 rounded-full bg-blue-400/30"></div>
                                <div class="h-1.5 w-full rounded bg-slate-700"></div>
                                <div class="h-3 w-10 rounded bg-blue-500/70"></div>
                            </div>
                        </div>

                        <!-- Card Header & Status -->
                        <div class="w-full flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="size-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="font-bold text-sm text-slate-800 dark:text-white block">{{ __('تلقائي (النظام)') }}</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 block">{{ __('يتزامن مع جهازك') }}</span>
                                </div>
                            </div>

                            <span
                                x-show="theme === 'system'"
                                class="size-5 rounded-full bg-[#012383] dark:bg-blue-500 text-white flex items-center justify-center shadow-xs"
                            >
                                <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                        </div>
                    </button>

                </div>
            </div>

            <!-- ======================================================== -->
            <!-- 2. READING TYPOGRAPHY & FONT SIZE SCALE                  -->
            <!-- ======================================================== -->
            <div class="space-y-4 pt-4 border-t border-[#DEE8F7] dark:border-slate-800">
                <div>
                    <h3 class="font-bold text-base text-[#012383] dark:text-blue-400 flex items-center gap-2">
                        <svg class="size-5 text-[#0B2A8F] dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 5v14m6-8h6m-3-4v8" />
                        </svg>
                        <span>{{ __('حجم نصوص الدروس والتخاطب') }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('تحكم في حجم خط القراءة لملاحظة التشكيل ومخارج الحروف بوضوح') }}</p>
                </div>

                <!-- Font Size Selector Options -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    
                    <!-- Standard (100%) -->
                    <button
                        type="button"
                        @click="setFontSize('normal')"
                        :class="fontSize === 'normal' ? 'border-[#012383] bg-blue-50/50 dark:bg-slate-800 dark:border-blue-400 text-[#012383] dark:text-blue-300 font-bold ring-1 ring-[#012383]/20' : 'border-[#DEE8F7] dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-[#012383]/40 bg-white dark:bg-slate-900/60'"
                        class="p-3.5 rounded-xl border flex items-center justify-between text-start transition-all cursor-pointer shadow-2xs"
                    >
                        <div>
                            <span class="text-sm block leading-none">{{ __('عادي (100%)') }}</span>
                            <span class="text-[11px] text-slate-400 mt-1 block">{{ __('الحجم القياسي') }}</span>
                        </div>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">Aa</span>
                    </button>

                    <!-- Medium (110%) -->
                    <button
                        type="button"
                        @click="setFontSize('medium')"
                        :class="fontSize === 'medium' ? 'border-[#012383] bg-blue-50/50 dark:bg-slate-800 dark:border-blue-400 text-[#012383] dark:text-blue-300 font-bold ring-1 ring-[#012383]/20' : 'border-[#DEE8F7] dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-[#012383]/40 bg-white dark:bg-slate-900/60'"
                        class="p-3.5 rounded-xl border flex items-center justify-between text-start transition-all cursor-pointer shadow-2xs"
                    >
                        <div>
                            <span class="text-sm block leading-none">{{ __('مريح (110%)') }}</span>
                            <span class="text-[11px] text-slate-400 mt-1 block">{{ __('تسهيل القراءة') }}</span>
                        </div>
                        <span class="text-sm font-semibold px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">Aa</span>
                    </button>

                    <!-- Large (120%) -->
                    <button
                        type="button"
                        @click="setFontSize('large')"
                        :class="fontSize === 'large' ? 'border-[#012383] bg-blue-50/50 dark:bg-slate-800 dark:border-blue-400 text-[#012383] dark:text-blue-300 font-bold ring-1 ring-[#012383]/20' : 'border-[#DEE8F7] dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-[#012383]/40 bg-white dark:bg-slate-900/60'"
                        class="p-3.5 rounded-xl border flex items-center justify-between text-start transition-all cursor-pointer shadow-2xs"
                    >
                        <div>
                            <span class="text-sm block leading-none">{{ __('كبير (120%)') }}</span>
                            <span class="text-[11px] text-slate-400 mt-1 block">{{ __('وضوح فائق وميسر') }}</span>
                        </div>
                        <span class="text-base font-bold px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">Aa</span>
                    </button>

                </div>

                <!-- Live Typography Interactive Preview Box -->
                <div class="rounded-2xl border border-[#DEE8F7] dark:border-slate-800 bg-[#FAFCFF] dark:bg-slate-950 p-4 sm:p-5 transition-all">
                    <div class="flex items-center justify-between mb-2 pb-2 border-b border-[#DEE8F7] dark:border-slate-800">
                        <span class="text-xs font-bold text-[#0B2A8F] dark:text-blue-400 flex items-center gap-1.5">
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ __('معاينة حية لنصوص التمارين النطقية') }}
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono" x-text="fontSize === 'large' ? '120% Large' : (fontSize === 'medium' ? '110% Medium' : '100% Standard')"></span>
                    </div>

                    <p
                        :class="{
                            'text-sm leading-relaxed': fontSize === 'normal',
                            'text-base leading-relaxed': fontSize === 'medium',
                            'text-lg sm:text-xl leading-loose font-medium': fontSize === 'large'
                        }"
                        class="text-slate-700 dark:text-slate-200 transition-all duration-200"
                    >
                        {{ __('التدريب الصوتي: «يُراعى عند نُطق الحروف الحلقية خروج الهواء بانسيابية مع ضبط ضغط الحبال الصوتية لسلامة النغمة والنطق الصحيح.»') }}
                    </p>
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- 3. MOTION & ANIMATION PREFERENCES                        -->
            <!-- ======================================================== -->
            <div class="space-y-4 pt-4 border-t border-[#DEE8F7] dark:border-slate-800">
                <div>
                    <h3 class="font-bold text-base text-[#012383] dark:text-blue-400 flex items-center gap-2">
                        <svg class="size-5 text-[#0B2A8F] dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span>{{ __('تفضيلات الحركة والتحميل') }}</span>
                            <span class="text-xs font-mono opacity-80" dir="ltr">(Motion & Animations)</span>
                        </div>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('تحكم في الحركات التفاعلية، شاشة التحميل، وشريط التقدم لتخصيص الأداء والتركيز') }}</p>
                </div>

                <div class="space-y-3">
                    
                    <!-- Feature A: Smooth Motion Toggle -->
                    <div class="p-4 rounded-2xl border border-[#DEE8F7] dark:border-slate-800 bg-white dark:bg-slate-900/60 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="size-9 rounded-xl bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0">
                                <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <div>
                                <span class="font-bold text-sm text-slate-800 dark:text-white block">{{ __('الحركات والانتقالات الانسيابية') }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 block">{{ __('تأثيرات الظهور عند التمرير والانتقال بين صفحات المنصة (مُفعل افتراضياً)') }}</span>
                            </div>
                        </div>

                        <button
                            type="button"
                            role="switch"
                            :aria-checked="motion === 'full'"
                            @click="setMotion(motion === 'full' ? 'reduced' : 'full')"
                            :class="motion === 'full' ? 'bg-[#012383]' : 'bg-slate-300 dark:bg-slate-700'"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            dir="ltr"
                        >
                            <span
                                :class="motion === 'full' ? 'translate-x-5' : 'translate-x-0'"
                                class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out"
                            ></span>
                        </button>
                    </div>

                    <!-- Feature B: Sound Wave Loader Toggle -->
                    <div class="p-4 rounded-2xl border border-[#DEE8F7] dark:border-slate-800 bg-white dark:bg-slate-900/60 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="size-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </span>
                            <div>
                                <span class="font-bold text-sm text-slate-800 dark:text-white block">{{ __('شاشة معادل النطق التفاعلية (Loader)') }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 block">{{ __('إظهار رنين موجات الصوت وشعار المنصة عند التنقل وتحديث الصفحة') }}</span>
                            </div>
                        </div>

                        <button
                            type="button"
                            role="switch"
                            :aria-checked="loaderEnabled"
                            @click="toggleLoader()"
                            :class="loaderEnabled ? 'bg-[#012383]' : 'bg-slate-300 dark:bg-slate-700'"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            dir="ltr"
                        >
                            <span
                                :class="loaderEnabled ? 'translate-x-5' : 'translate-x-0'"
                                class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out"
                            ></span>
                        </button>
                    </div>

                    <!-- Feature C: Scroll Progress Bar Toggle -->
                    <div class="p-4 rounded-2xl border border-[#DEE8F7] dark:border-slate-800 bg-white dark:bg-slate-900/60 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="size-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            </span>
                            <div>
                                <span class="font-bold text-sm text-slate-800 dark:text-white block">{{ __('شريط مؤشر التمرير العلوي') }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 block">{{ __('خط التدرج اللوني في أعلى الشاشة لعرض نسبة تقدم القراءة') }}</span>
                            </div>
                        </div>

                        <button
                            type="button"
                            role="switch"
                            :aria-checked="scrollProgressEnabled"
                            @click="toggleScrollProgress()"
                            :class="scrollProgressEnabled ? 'bg-[#012383]' : 'bg-slate-300 dark:bg-slate-700'"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            dir="ltr"
                        >
                            <span
                                :class="scrollProgressEnabled ? 'translate-x-5' : 'translate-x-0'"
                                class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out"
                            ></span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- ======================================================== -->
            <!-- 4. LIVE COMPONENT PREVIEW (Speech-Therapy Lesson Card)   -->
            <!-- ======================================================== -->
            <div class="space-y-4 pt-4 border-t border-[#DEE8F7] dark:border-slate-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base text-[#012383] dark:text-blue-400 flex items-center gap-2">
                            <svg class="size-5 text-[#0B2A8F] dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                            <span>{{ __('المعاينة الحية لبطاقات المنصة') }}</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('شاهد كيف تبدو بطاقات الدروس وأزرار التفاعل وفق خياراتك المحددة') }}</p>
                    </div>
                </div>

                <!-- Simulation Card -->
                <div class="rounded-2xl border border-[#DEE8F7] dark:border-slate-800 bg-white dark:bg-slate-900 p-5 sm:p-6 shadow-sm hover-lift transition-all">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="size-12 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 p-2 flex items-center justify-center shrink-0">
                                <img src="/assets/images/logo.png" alt="منصة الأمين" class="size-full object-contain" />
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-300 px-2.5 py-0.5 rounded-full inline-block">
                                        {{ __('مستوى متقدم • معتمد') }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ __('12 تمرين صوتي') }}</span>
                                </div>
                                <h4 class="font-bold text-base text-[#012383] dark:text-white mt-1">
                                    {{ __('دورة علاج اضطرابات اللدغات والتأتأة') }}
                                </h4>
                            </div>
                        </div>

                        <!-- Action Button with Shine -->
                        <button
                            type="button"
                            class="px-4 py-2 rounded-xl bg-[#012383] text-white text-xs font-bold btn-shine shadow-xs hover:bg-[#0B2A8F] transition-all cursor-pointer shrink-0"
                        >
                            {{ __('متابعة التدريب') }}
                        </button>
                    </div>

                    <!-- Progress Bar Simulation -->
                    <div class="mt-4 pt-4 border-t border-[#DEE8F7] dark:border-slate-800/80 flex items-center gap-3">
                        <div class="flex-1 h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-[#012383] to-[#10B77F] rounded-full" style="width: 72%;"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">72%</span>
                    </div>
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- 5. SAVE BUTTON & TOAST FEEDBACK                         -->
            <!-- ======================================================== -->
            <div class="pt-6 border-t border-[#DEE8F7] dark:border-slate-800 flex items-center justify-between gap-4">
                <button
                    type="button"
                    @click="savePreferences()"
                    class="px-6 py-2.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white text-sm font-bold shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center gap-2 btn-shine shrink-0"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ __('حفظ وتطبيق التفضيلات') }}</span>
                </button>

                <span class="text-xs text-slate-400 dark:text-slate-500 text-start">
                    {{ __('يتم تفعيل التغييرات فورياً وتخزينها في حسابك') }}
                </span>
            </div>

            <!-- Floating Success Toast Notification -->
            <div
                x-show="showToast"
                x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-3"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-3"
                class="fixed top-6 end-6 z-50 bg-[#012383] text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-blue-400/30"
            >
                <span class="size-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <span class="text-sm font-medium" x-text="toastMessage"></span>
            </div>

        </div>

    </x-settings.layout>
</section>
