{{-- ======================================================== --}}
{{-- TAB: محركات البحث وتحسين الظهور (SEO & Search Tower)     --}}
{{-- ======================================================== --}}
<div x-show="activeTab === 'seo'" x-cloak class="flex flex-col gap-6" x-data="{
    activePageTab: 'home',
    titleAr: '{{ addslashes($settings['seo_meta_title']['value_ar'] ?? 'مركز الأمين للتدريب والتأهيل التخاطبي | كورس التخاطب الشامل مع هنا المكاوي') }}',
    descAr: '{{ addslashes($settings['seo_meta_description']['value_ar'] ?? 'المنصة الأكاديمية والسريرية الأولى لتدريب وتأهيل أخصائي النطق والتخاطب في الوطن العربي. كورسات معتمدة، فيديوهات عملية سريرية، وشهادات موثقة مع نخبة استشاريي التخاطب.') }}',
    canonicalDomain: '{{ addslashes($settings['seo_canonical_base_url']['value_ar'] ?? url('/')) }}',
    resetRobotsTxt() {
        const defaultRobots = `# =========================================================================\n# Al-Ameen Speech Center - Robots.txt Rules for Search Engine Crawlers\n# =========================================================================\nUser-agent: *\nAllow: /\nAllow: /assets/\nAllow: /build/\nAllow: /src/\n\n# Prevent crawlers from accessing private student LMS and admin endpoints\nDisallow: /admin/\nDisallow: /dashboard\nDisallow: /lesson/\nDisallow: /assignment/\nDisallow: /grades\nDisallow: /result\nDisallow: /user-profile\nDisallow: /storage/videos/lessons/\nDisallow: /webhooks/\n\n# Dynamic XML Sitemap Index\nSitemap: {{ url('/sitemap.xml') }}`;
        if (this.$refs.robotsTxtInput) {
            this.$refs.robotsTxtInput.value = defaultRobots;
        }
    }
}">

    {{-- SEO Audit & Real-Time Performance Badges --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Card 1: Indexing Status --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col gap-1">
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">{{ __('حالة فهرسة محركات البحث') }}</span>
                <div class="flex items-center gap-2">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-sm font-black text-slate-800 dark:text-slate-100">{{ __('فهرسة كاملة (Index & Follow)') }}</span>
                </div>
            </div>
            <span class="text-2xl">🤖</span>
        </div>

        {{-- Card 2: XML Sitemap --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col gap-1">
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">{{ __('خريطة الموقع (Sitemap.xml)') }}</span>
                <div class="flex items-center gap-2">
                    <span class="size-2 rounded-full bg-emerald-500"></span>
                    <a href="{{ url('/sitemap.xml') }}" target="_blank" class="text-xs font-bold text-[#012383] dark:text-blue-400 hover:underline flex items-center gap-1">
                        <span>{{ __('مفعلة وتحدث آلياً') }}</span>
                        <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>
            <span class="text-2xl">🗺️</span>
        </div>

        {{-- Card 3: Google Search Console --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col gap-1">
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">{{ __('إثبات ملكية Google Console') }}</span>
                <div class="flex items-center gap-2">
                    @if(!empty($settings['seo_google_verification']['value_ar'] ?? null))
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-black text-emerald-700 dark:text-emerald-400">{{ __('تم التفعيل والربط') }}</span>
                    @else
                        <span class="size-2 rounded-full bg-amber-500"></span>
                        <span class="text-xs font-bold text-amber-700 dark:text-amber-400">{{ __('بانتظار إدخال الكود') }}</span>
                    @endif
                </div>
            </div>
            <span class="text-2xl">🔍</span>
        </div>

        {{-- Card 4: Schema.org JSON-LD --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col gap-1">
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">{{ __('البيانات المهيكلة (Rich Snippets)') }}</span>
                <div class="flex items-center gap-2">
                    <span class="size-2 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-black text-emerald-700 dark:text-emerald-400">{{ __('نشطة (Organization + Course)') }}</span>
                </div>
            </div>
            <span class="text-2xl">✨</span>
        </div>

    </div>

    {{-- Main Form Container --}}
    <form method="POST" action="{{ route('admin.settings.update') }}#seo" enctype="multipart/form-data" class="flex flex-col gap-6">
        @csrf

        {{-- Section 1: Global Metadata & Site Identity --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs flex flex-col gap-5">
            <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400 flex items-center gap-2">
                    <span>🏷️</span>
                    <span>{{ __('البيانات الوصفية العامة للموقع (Global Meta Tags)') }}</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('تطبق هذه البيانات كإعدادات افتراضية لكافة صفحات المنصة ما لم يتم تخصيص صفحة محددة') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Global Meta Title AR --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('عنوان الموقع الافتراضي في محركات البحث (Meta Title - عربي):') }}</label>
                        <span class="text-[11px] font-mono text-slate-400" x-text="(titleAr.length) + '/60 حرف'"></span>
                    </div>
                    <input type="text" name="settings[seo_meta_title][ar]" x-model="titleAr" value="{{ $settings['seo_meta_title']['value_ar'] ?? '' }}" required class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" placeholder="مركز الأمين | كورس التخاطب الشامل مع هنا المكاوي" />
                    <input type="hidden" name="settings[seo_meta_title][group]" value="seo" />
                    <span class="text-[10px] text-slate-400 mt-1 block">{{ __('الطول الموصى به: 50-60 حرفاً لضمان عدم اقتطاعه في صفحة نتائج بحث جوجل.') }}</span>
                </div>

                {{-- Global Meta Title EN --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان الموقع الافتراضي في محركات البحث (Meta Title - إنجليزي):') }}</label>
                    <input type="text" name="settings[seo_meta_title][en]" value="{{ $settings['seo_meta_title']['value_en'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" placeholder="Al-Ameen Center | Speech Therapy Course" />
                </div>

                {{-- Global Meta Description AR --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('الوصف الوصفي العام (Meta Description - عربي):') }}</label>
                        <span class="text-[11px] font-mono" :class="descAr.length > 160 ? 'text-amber-500 font-bold' : 'text-slate-400'" x-text="(descAr.length) + '/160 حرف (المثالي: 130-155)'"></span>
                    </div>
                    <textarea name="settings[seo_meta_description][ar]" x-model="descAr" rows="3" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F] leading-relaxed">{{ $settings['seo_meta_description']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_meta_description][group]" value="seo" />
                    <span class="text-[10px] text-slate-400 mt-1 block">{{ __('اكتب وصفاً جذاباً وغنياً بالكلمات المفتاحية يحفز الباحث على النقر وزيارة المنصة.') }}</span>
                </div>

                {{-- Global Meta Description EN --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الوصف الوصفي العام (Meta Description - إنجليزي):') }}</label>
                    <textarea name="settings[seo_meta_description][en]" rows="3" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F] leading-relaxed">{{ $settings['seo_meta_description']['value_en'] ?? '' }}</textarea>
                </div>

                {{-- Global Meta Keywords AR --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الكلمات المفتاحية الأساسية (Meta Keywords - عربي):') }}</label>
                    <textarea name="settings[seo_meta_keywords][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_meta_keywords']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_meta_keywords][group]" value="seo" />
                    <span class="text-[10px] text-slate-400 mt-1 block">{{ __('افصل بين الكلمات بفواصل، مثال: كورس تخاطب, هنا المكاوي, مركز الأمين, لدغات الحروف') }}</span>
                </div>

                {{-- Global Meta Keywords EN --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الكلمات المفتاحية الأساسية (Meta Keywords - إنجليزي):') }}</label>
                    <textarea name="settings[seo_meta_keywords][en]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_meta_keywords']['value_en'] ?? '' }}</textarea>
                </div>

                {{-- Author & Publisher --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('المؤلف والناشر (Meta Author):') }}</label>
                    <input type="text" name="settings[seo_author][ar]" value="{{ $settings['seo_author']['value_ar'] ?? 'مركز الأمين - أ.د. هناء المكاوي' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    <input type="hidden" name="settings[seo_author][group]" value="seo" />
                </div>

                {{-- Robots Directive --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('توجيهات عناكب الفهرسة (Robots Directive):') }}</label>
                    <select name="settings[seo_robots][ar]" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">
                        @php
                            $currentRobots = $settings['seo_robots']['value_ar'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
                        @endphp
                        <option value="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" @selected(str_contains($currentRobots, 'index') && str_contains($currentRobots, 'follow'))>
                            {{ __('فهرسة كاملة وظهور في كل النتائج (index, follow - موصى به للقمة)') }}
                        </option>
                        <option value="noindex, follow" @selected(str_contains($currentRobots, 'noindex'))>
                            {{ __('عدم الفهرسة مؤقتاً (noindex, follow)') }}
                        </option>
                    </select>
                    <input type="hidden" name="settings[seo_robots][group]" value="seo" />
                </div>

                {{-- Canonical Base URL --}}
                <div class="md:col-span-2">
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('النطاق الأساسي للرابط القانوني (Canonical Base URL):') }}</label>
                    <input type="url" name="settings[seo_canonical_base_url][ar]" value="{{ $settings['seo_canonical_base_url']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" placeholder="{{ url('/') }}" />
                    <input type="hidden" name="settings[seo_canonical_base_url][group]" value="seo" />
                    <span class="text-[10px] text-slate-400 mt-1 block">{{ __('اتركه فارغاً لاستخدام نطاق المنصة الحالي تلقائياً (' . url('/') . '). الرابط القانوني يمنع جوجل من اعتبار الصفحات مكررة.') }}</span>
                </div>

            </div>
        </div>

        {{-- Section 3: Social Media & Open Graph Cards --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs flex flex-col gap-5">
            <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400 flex items-center gap-2">
                    <span>📲</span>
                    <span>{{ __('بطاقات المشاركة وشبكات التواصل (Open Graph & Twitter Cards)') }}</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('تحدد الصورة والعنوان والوصف عند مشاركة رابط المنصة على WhatsApp, Facebook, X, Telegram, LinkedIn') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Default OG Image with Preview & File Upload --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('صورة المشاركة الافتراضية (Open Graph Social Image):') }}</label>
                    <div class="flex items-center gap-3">
                        <input type="text" name="settings[seo_og_image][ar]" value="{{ $settings['seo_og_image']['value_ar'] ?? '/assets/images/alameen-images/6.jpeg' }}" class="flex-1 h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                        <div class="size-14 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-1 shrink-0 overflow-hidden">
                            <img src="{{ $settings['seo_og_image']['value_ar'] ?? '/assets/images/alameen-images/6.jpeg' }}" alt="OG Preview" class="size-full object-cover rounded-lg" />
                        </div>
                    </div>
                    <input type="hidden" name="settings[seo_og_image][group]" value="seo" />
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 block mb-1">{{ __('رفع صورة مشاركة جديدة (المقاس المثالي 1200x630 بكسل):') }}</span>
                        <input type="file" name="seo_og_image_file" accept="image/png,image/jpeg,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#012383] file:text-white hover:file:bg-[#0B2A8F] cursor-pointer" />
                    </div>
                </div>

                {{-- Twitter Card & Handle --}}
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('نوع بطاقة منصة X / Twitter:') }}</label>
                        <select name="settings[seo_twitter_card][ar]" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">
                            <option value="summary_large_image" @selected(($settings['seo_twitter_card']['value_ar'] ?? 'summary_large_image') === 'summary_large_image')>{{ __('بطاقة عريضة وصورة كبيرة (summary_large_image - موصى به)') }}</option>
                            <option value="summary" @selected(($settings['seo_twitter_card']['value_ar'] ?? '') === 'summary')>{{ __('بطاقة مصغرة مربعة (summary)') }}</option>
                        </select>
                        <input type="hidden" name="settings[seo_twitter_card][group]" value="seo" />
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('حساب المنصة على X / Twitter:') }}</label>
                        <input type="text" name="settings[seo_twitter_handle][ar]" value="{{ $settings['seo_twitter_handle']['value_ar'] ?? '@alameencenter' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" placeholder="@alameencenter" />
                        <input type="hidden" name="settings[seo_twitter_handle][group]" value="seo" />
                    </div>
                </div>

            </div>
        </div>

        {{-- Section 4: Per-Page Specialized SEO Customization --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs flex flex-col gap-5">
            <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400 flex items-center gap-2">
                    <span>📄</span>
                    <span>{{ __('تخصيص السيو لكل صفحة عامة (Per-Page SEO Manager)') }}</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('يمكنك كتابة عناوين ووصف مخصص لكل صفحة من الصفحات العامة لرفع ترتيبها إلى المرتبة الأولى في تخصصها') }}
                </p>
            </div>

            {{-- Sub-Navigation Pills for Public Pages --}}
            <div class="bg-slate-100 dark:bg-slate-800/60 p-1.5 rounded-2xl flex items-center gap-1.5 overflow-x-auto scrollbar-none">
                <button type="button" @click="activePageTab = 'home'" :class="activePageTab === 'home' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    {{ __('الصفحة الرئيسية (/)') }}
                </button>
                <button type="button" @click="activePageTab = 'courses'" :class="activePageTab === 'courses' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    {{ __('دليل الكورسات (/courses)') }}
                </button>
                <button type="button" @click="activePageTab = 'reviews'" :class="activePageTab === 'reviews' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    {{ __('آراء المتدربين (/reviews)') }}
                </button>
                <button type="button" @click="activePageTab = 'contact'" :class="activePageTab === 'contact' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    {{ __('تواصل معنا (/contact)') }}
                </button>
                <button type="button" @click="activePageTab = 'audit'" :class="activePageTab === 'audit' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    {{ __('التقرير السريري (/audit-report)') }}
                </button>
                <button type="button" @click="activePageTab = 'verify'" :class="activePageTab === 'verify' ? 'bg-[#012383] text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer">
                    {{ __('فحص الشهادات (/verify-certificate)') }}
                </button>
            </div>

            {{-- 1. Home Page SEO --}}
            <div x-show="activePageTab === 'home'" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان الصفحة الرئيسية (Title):') }}</label>
                    <input type="text" name="settings[seo_page_home_title][ar]" value="{{ $settings['seo_page_home_title']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    <input type="hidden" name="settings[seo_page_home_title][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('العنوان بالإنجليزية (Home Title EN):') }}</label>
                    <input type="text" name="settings[seo_page_home_title][en]" value="{{ $settings['seo_page_home_title']['value_en'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الوصف الوصفي (Description):') }}</label>
                    <textarea name="settings[seo_page_home_description][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_home_description']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_home_description][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الكلمات الدلالية المفتاحية (Keywords):') }}</label>
                    <textarea name="settings[seo_page_home_keywords][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_home_keywords']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_home_keywords][group]" value="seo_pages" />
                </div>
            </div>

            {{-- 2. Courses Directory SEO --}}
            <div x-show="activePageTab === 'courses'" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان صفحة دليل الكورسات (Title):') }}</label>
                    <input type="text" name="settings[seo_page_courses_title][ar]" value="{{ $settings['seo_page_courses_title']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    <input type="hidden" name="settings[seo_page_courses_title][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('العنوان بالإنجليزية (Courses Title EN):') }}</label>
                    <input type="text" name="settings[seo_page_courses_title][en]" value="{{ $settings['seo_page_courses_title']['value_en'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الوصف الوصفي (Description):') }}</label>
                    <textarea name="settings[seo_page_courses_description][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_courses_description']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_courses_description][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الكلمات الدلالية المفتاحية (Keywords):') }}</label>
                    <textarea name="settings[seo_page_courses_keywords][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_courses_keywords']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_courses_keywords][group]" value="seo_pages" />
                </div>
            </div>

            {{-- 3. Reviews SEO --}}
            <div x-show="activePageTab === 'reviews'" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان صفحة آراء وتقييمات المتدربين (Title):') }}</label>
                    <input type="text" name="settings[seo_page_reviews_title][ar]" value="{{ $settings['seo_page_reviews_title']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    <input type="hidden" name="settings[seo_page_reviews_title][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('العنوان بالإنجليزية (Reviews Title EN):') }}</label>
                    <input type="text" name="settings[seo_page_reviews_title][en]" value="{{ $settings['seo_page_reviews_title']['value_en'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الوصف الوصفي (Description):') }}</label>
                    <textarea name="settings[seo_page_reviews_description][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_reviews_description']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_reviews_description][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الكلمات الدلالية المفتاحية (Keywords):') }}</label>
                    <textarea name="settings[seo_page_reviews_keywords][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_reviews_keywords']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_reviews_keywords][group]" value="seo_pages" />
                </div>
            </div>

            {{-- 4. Contact Us SEO --}}
            <div x-show="activePageTab === 'contact'" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان صفحة تواصل معنا (Title):') }}</label>
                    <input type="text" name="settings[seo_page_contact_title][ar]" value="{{ $settings['seo_page_contact_title']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    <input type="hidden" name="settings[seo_page_contact_title][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('العنوان بالإنجليزية (Contact Title EN):') }}</label>
                    <input type="text" name="settings[seo_page_contact_title][en]" value="{{ $settings['seo_page_contact_title']['value_en'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الوصف الوصفي (Description):') }}</label>
                    <textarea name="settings[seo_page_contact_description][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_contact_description']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_contact_description][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الكلمات الدلالية المفتاحية (Keywords):') }}</label>
                    <textarea name="settings[seo_page_contact_keywords][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_contact_keywords']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_contact_keywords][group]" value="seo_pages" />
                </div>
            </div>

            {{-- 5. Audit Report SEO --}}
            <div x-show="activePageTab === 'audit'" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان صفحة التقرير السريري (Title):') }}</label>
                    <input type="text" name="settings[seo_page_audit_title][ar]" value="{{ $settings['seo_page_audit_title']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    <input type="hidden" name="settings[seo_page_audit_title][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('العنوان بالإنجليزية (Audit Title EN):') }}</label>
                    <input type="text" name="settings[seo_page_audit_title][en]" value="{{ $settings['seo_page_audit_title']['value_en'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الوصف الوصفي (Description):') }}</label>
                    <textarea name="settings[seo_page_audit_description][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_audit_description']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_audit_description][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الكلمات الدلالية المفتاحية (Keywords):') }}</label>
                    <textarea name="settings[seo_page_audit_keywords][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_audit_keywords']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_audit_keywords][group]" value="seo_pages" />
                </div>
            </div>

            {{-- 6. Certificate Verification SEO --}}
            <div x-show="activePageTab === 'verify'" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('عنوان صفحة فحص الشهادات (Title):') }}</label>
                    <input type="text" name="settings[seo_page_verify_certificate_title][ar]" value="{{ $settings['seo_page_verify_certificate_title']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                    <input type="hidden" name="settings[seo_page_verify_certificate_title][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('العنوان بالإنجليزية (Verify Title EN):') }}</label>
                    <input type="text" name="settings[seo_page_verify_certificate_title][en]" value="{{ $settings['seo_page_verify_certificate_title']['value_en'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الوصف الوصفي (Description):') }}</label>
                    <textarea name="settings[seo_page_verify_certificate_description][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_verify_certificate_description']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_verify_certificate_description][group]" value="seo_pages" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('الكلمات الدلالية المفتاحية (Keywords):') }}</label>
                    <textarea name="settings[seo_page_verify_certificate_keywords][ar]" rows="2" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]">{{ $settings['seo_page_verify_certificate_keywords']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_page_verify_certificate_keywords][group]" value="seo_pages" />
                </div>
            </div>

        </div>

        {{-- Section 5: Webmaster Verification Tokens --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs flex flex-col gap-5">
            <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400 flex items-center gap-2">
                    <span>🛡️</span>
                    <span>{{ __('إثبات ملكية محركات البحث (Google Search Console & Bing Webmaster)') }}</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('ضع الرمز أو الميتا تاج هنا ليتم حقنه تلقائياً في <head> لإثبات ملكية الموقع فورياً وبدون تعديل ملفات') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Google Search Console --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('رمز التحقق من Google Search Console:') }}</label>
                    <input type="text" name="settings[seo_google_verification][ar]" value="{{ $settings['seo_google_verification']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" placeholder="google-site-verification=xxxx أو كود التحقق" />
                    <input type="hidden" name="settings[seo_google_verification][group]" value="seo" />
                    <span class="text-[10px] text-slate-400 mt-1 block">{{ __('يمكنك لصق كود التحقق مباشرة أو الميتا تاج كاملاً.') }}</span>
                </div>

                {{-- Bing Webmaster Tools --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('رمز التحقق من Bing Webmaster Tools:') }}</label>
                    <input type="text" name="settings[seo_bing_verification][ar]" value="{{ $settings['seo_bing_verification']['value_ar'] ?? '' }}" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" placeholder="msvalidate.01 كود التحقق" />
                    <input type="hidden" name="settings[seo_bing_verification][group]" value="seo" />
                </div>

            </div>
        </div>

        {{-- Section 6: XML Sitemap & Robots.txt Manager --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs flex flex-col gap-5">
            <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400 flex items-center gap-2">
                        <span>🗺️</span>
                        <span>{{ __('مدير خريطة الموقع وملف الروبوتات (Sitemap & Robots.txt)') }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('تحكم كامل في توجيه عناكب محركات البحث وتحديث خريطة الموقع الديناميكية') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ url('/sitemap.xml') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 text-[#012383] dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-xs font-bold transition-all flex items-center gap-1.5">
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>{{ __('عرض خريطة الموقع XML') }}</span>
                    </a>
                    <a href="{{ url('/robots.txt') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all flex items-center gap-1.5">
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>{{ __('عرض ملف robots.txt') }}</span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Sitemap Toggle --}}
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">{{ __('تفعيل خريطة الموقع الديناميكية (XML Sitemap):') }}</span>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('تضم كافة الصفحات والكورسات المنشورة بتحديث لحظي') }}</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="settings[seo_sitemap_enabled][ar]" value="1" @checked(($settings['seo_sitemap_enabled']['value_ar'] ?? '1') === '1') class="sr-only peer" />
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-[#012383]"></div>
                    </label>
                    <input type="hidden" name="settings[seo_sitemap_enabled][group]" value="seo" />
                </div>

                {{-- Schema.org Toggle --}}
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">{{ __('تفعيل البيانات المهيكلة (Schema.org JSON-LD):') }}</span>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('توليد الأكواد المهيكلة للمؤسسة والدورات والأسئلة الشائعة') }}</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="settings[seo_schema_org_enabled][ar]" value="1" @checked(($settings['seo_schema_org_enabled']['value_ar'] ?? '1') === '1') class="sr-only peer" />
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-[#012383]"></div>
                    </label>
                    <input type="hidden" name="settings[seo_schema_org_enabled][group]" value="seo" />
                </div>

                {{-- Robots.txt Custom Editor --}}
                <div class="md:col-span-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('محرر قواعد ملف robots.txt:') }}</label>
                        <button type="button" @click="resetRobotsTxt()" class="text-xs font-bold text-[#012383] dark:text-blue-400 hover:underline cursor-pointer flex items-center gap-1">
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>{{ __('استعادة القالب الافتراضي الموصى به لمحركات البحث') }}</span>
                        </button>
                    </div>
                    <textarea x-ref="robotsTxtInput" name="settings[seo_robots_txt][ar]" rows="9" class="w-full p-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-slate-900 text-emerald-400 dark:bg-slate-950 dark:text-emerald-400 outline-none focus:border-[#0B2A8F] leading-relaxed" dir="ltr">{{ $settings['seo_robots_txt']['value_ar'] ?? app(\App\Services\Seo\SeoService::class)->getDefaultRobotsTxt() }}</textarea>
                    <input type="hidden" name="settings[seo_robots_txt][group]" value="seo" />
                    <span class="text-[10px] text-slate-400 mt-1 block">{{ __('يتم تحديث الرابط وحفظه تلقائياً ومزامنته مع ملف robots.txt الفعلي على السيرفر.') }}</span>
                </div>

            </div>
        </div>

        {{-- Section 7: Analytics & Custom Tracking Injections --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-7 shadow-xs flex flex-col gap-5">
            <div class="border-b border-[#DEE8F7] dark:border-slate-800 pb-4">
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-[#012383] dark:text-blue-400 flex items-center gap-2">
                    <span>📊</span>
                    <span>{{ __('أكواد التحليلات والتتبع (Google Analytics GA4 & Custom Scripts)') }}</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('يمكنك إدراج كود تتبع إحصائيات Google Analytics, Google Tag Manager (GTM), Meta Pixel هنا وسيتم إدراجه في كل الصفحات') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Head Scripts --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('أكواد الرأس (Head Custom Scripts - تُحقن قبل إغلاق <head>):') }}</label>
                    <textarea name="settings[seo_head_custom_scripts][ar]" rows="5" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" dir="ltr" placeholder="<script async src='https://www.googletagmanager.com/gtag/js?id=G-XXXXX'></script>">{{ $settings['seo_head_custom_scripts']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_head_custom_scripts][group]" value="seo" />
                </div>

                {{-- Body Scripts --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1.5">{{ __('أكواد الجسم (Body Scripts - تُحقن بعد فتح <body> مباشرة):') }}</label>
                    <textarea name="settings[seo_body_custom_scripts][ar]" rows="5" class="w-full p-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 outline-none focus:border-[#0B2A8F]" dir="ltr" placeholder="<!-- Google Tag Manager (noscript) -->">{{ $settings['seo_body_custom_scripts']['value_ar'] ?? '' }}</textarea>
                    <input type="hidden" name="settings[seo_body_custom_scripts][group]" value="seo" />
                </div>

            </div>
        </div>

        {{-- Save Button Bar --}}
        <div class="flex justify-end pt-2 pb-6">
            <button type="submit" class="px-8 h-12 rounded-2xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-sm shadow-md transition-all cursor-pointer flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99]">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ __('حفظ وتطبيق إعدادات محركات البحث وSEO فورياً') }}</span>
            </button>
        </div>

    </form>

</div>
