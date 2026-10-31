<!-- UNIFIED MASTER FOOTER -->
<footer class="w-full bg-[#031338] text-white relative overflow-hidden border-t border-[#122A63]">
    
    <!-- Ambient Decorative Glows -->
    <div class="absolute -top-32 start-10 size-[380px] rounded-full bg-blue-600/10 blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute -bottom-24 end-12 size-[320px] rounded-full bg-indigo-500/10 blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-[1536px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-24 2xl:px-32 pt-14 lg:pt-16 pb-8 flex flex-col gap-12">
        
@php
    $cmsSettings = app(\App\Services\Cms\SettingsService::class);
    $footerMenu = app(\App\Services\Cms\MenuService::class)->getMenu('footer_quick_links');
    $siteName = $cmsSettings->get('site_name', config('app.name', 'منصة مركز الأمين'));
    $siteTagline = $cmsSettings->get('site_tagline', __('المنصة العربية المتخصصة في تدريب وتأهيل مهارات النطق والتخاطب، نجمع بين الأسس العلمية المعتمدة والتطبيقات السريرية العملية بإشراف نخبة من كبار أخصائيي التخاطب.'));
    $contactEmail = $cmsSettings->get('contact_email', 'support@alameen.edu.sa');
    $contactPhone = $cmsSettings->get('contact_phone', '+20 100 000 0000');
    $whatsappUrl = $cmsSettings->get('social_whatsapp', 'https://wa.me/201000000000');
    $facebookUrl = $cmsSettings->get('social_facebook');
    $instagramUrl = $cmsSettings->get('social_instagram');
    $linkedinUrl = $cmsSettings->get('social_linkedin');
    $youtubeUrl = $cmsSettings->get('social_youtube');
    $telegramUrl = $cmsSettings->get('social_telegram');
    $siteLogo = $cmsSettings->get('site_logo') ?: '/assets/images/logo.png';
@endphp

        <!-- Top Row: Main Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10">
            
            <!-- Column 1: Brand & Identity (Lg: 4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-5">
                
                <!-- Brand Logo with soft badge -->
                <a href="{{ route('home') }}" class="flex gap-3 items-center group w-fit">
                    <div class="size-11 rounded-2xl flex items-center justify-center group-hover:scale-105 transition-transform">
                        <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="size-full object-contain" />
                    </div>
                    <div class="flex flex-col text-start">
                        <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-2xl text-white tracking-tight leading-tight">
                            {{ $siteName }}
                        </span>
                        <span class="text-[11px] text-blue-200/80 font-bold font-['Cairo']">
                            {{ __('لتأهيل التخاطب وعلاج النطق') }}
                        </span>
                    </div>
                </a>

                <!-- Mission Statement -->
                <p class="text-xs sm:text-[13px] leading-relaxed text-blue-100/70 font-medium max-w-sm">
                    {{ $siteTagline }}
                </p>

                <!-- Social Media Links -->
                <div class="flex items-center gap-2 pt-2 flex-wrap">
                    @if(!empty($whatsappUrl))
                    <!-- WhatsApp -->
                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="واتساب"
                        class="size-8 rounded-lg bg-blue-950/80 border border-blue-800/50 hover:bg-[#10B77F] hover:border-[#10B77F] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                    >
                        <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.634.07-.999-.074-.755-.298-1.52-.752-2.227-1.458-.894-.893-1.425-1.923-1.688-2.338-.263-.414-.029-.636.143-.808.156-.156.342-.405.513-.607.172-.202.229-.344.343-.573.114-.229.057-.428-.029-.6-.086-.172-.771-1.859-1.057-2.545-.278-.669-.561-.579-.771-.59-.2-.01-.429-.012-.657-.012-.229 0-.6.086-.914.428-.314.343-1.2 1.171-1.2 2.858 0 1.687 1.229 3.315 1.4 3.543.172.229 2.417 3.691 5.857 5.176 2.851 1.231 3.432.986 4.05.928.618-.057 1.99-.813 2.275-1.599.285-.785.285-1.457.2-1.599-.085-.143-.314-.229-.657-.401z"/></svg>
                    </a>
                    @endif

                    @if(!empty($facebookUrl))
                    <!-- Facebook -->
                    <a
                        href="{{ $facebookUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="فيسبوك"
                        class="size-8 rounded-lg bg-blue-950/80 border border-blue-800/50 hover:bg-[#1877F2] hover:border-[#1877F2] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                    >
                        <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif

                    @if(!empty($instagramUrl))
                    <!-- Instagram -->
                    <a
                        href="{{ $instagramUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="إنستغرام"
                        class="size-8 rounded-lg bg-blue-950/80 border border-blue-800/50 hover:bg-gradient-to-tr hover:from-amber-500 hover:to-rose-600 hover:border-transparent text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                    >
                        <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    @endif

                    @if(!empty($linkedinUrl))
                    <!-- LinkedIn -->
                    <a
                        href="{{ $linkedinUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="لينكد إن"
                        class="size-8 rounded-lg bg-blue-950/80 border border-blue-800/50 hover:bg-[#0A66C2] hover:border-[#0A66C2] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                    >
                        <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    @endif

                    @if(!empty($youtubeUrl))
                    <!-- YouTube -->
                    <a
                        href="{{ $youtubeUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="يوتيوب"
                        class="size-8 rounded-lg bg-blue-950/80 border border-blue-800/50 hover:bg-[#FF0000] hover:border-[#FF0000] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                    >
                        <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    @endif

                    @if(!empty($telegramUrl))
                    <!-- Telegram -->
                    <a
                        href="{{ $telegramUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="تيليجرام"
                        class="size-8 rounded-lg bg-blue-950/80 border border-blue-800/50 hover:bg-[#229ED9] hover:border-[#229ED9] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                    >
                        <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.34-.675.34l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.536-.196 1.006.128.832.941z"/></svg>
                    </a>
                    @endif
                </div>

            </div>

            <!-- Column 2: Dynamic Footer Links (Lg: 4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-4 rounded-full bg-[#10B77F]"></span>
                    <h4 class="font-['Cairo'] font-bold text-sm text-white tracking-wide">
                        {{ __('روابط سريعة واستكشاف') }}
                    </h4>
                </div>
                <ul class="flex flex-col gap-2.5 text-xs font-semibold text-blue-200/75">
                    @if($footerMenu->isNotEmpty())
                        @foreach($footerMenu as $fItem)
                            <li>
                                <a href="{{ $fItem->resolved_url }}" target="{{ $fItem->target }}" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                                    <span class="text-blue-400">›</span>
                                    {{ $fItem->title }}
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <a href="{{ route('courses.index') }}" class="hover:text-white inline-flex items-center gap-1.5">
                                <span class="text-blue-400">›</span>
                                {{ __('الدورات التدريبية المتاحة') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reviews.index') }}" class="hover:text-white inline-flex items-center gap-1.5">
                                <span class="text-blue-400">›</span>
                                {{ __('آراء وتقييمات المتدربين') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Column 3: قنوات الدعم المباشرة (Lg: 4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-4 rounded-full bg-amber-400"></span>
                    <h4 class="font-['Cairo'] font-bold text-sm text-white tracking-wide">
                        {{ __('الدعم الفني والأكاديمي') }}
                    </h4>
                </div>

                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-950/80 border border-blue-800/50 text-[11px] font-bold text-emerald-300 w-fit">
                    <span class="size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>{{ __('فريق الدعم متاح وجاهز لمساعدتك') }}</span>
                </div>

                <!-- Channels -->
                <div class="flex flex-col gap-2.5 text-xs text-blue-100/70">
                    <div class="flex items-center gap-2.5">
                        <div class="size-7 rounded-lg bg-blue-900/40 border border-blue-800/40 flex items-center justify-center text-blue-300 shrink-0">
                            ✉️
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-blue-300 font-bold">{{ __('البريد الأكاديمي') }}</span>
                            <a href="mailto:{{ $contactEmail }}" class="font-medium hover:text-white transition-colors">{{ $contactEmail }}</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <div class="size-7 rounded-lg bg-blue-900/40 border border-blue-800/40 flex items-center justify-center text-blue-300 shrink-0">
                            📞
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-blue-300 font-bold">{{ __('واتساب / الهاتف') }}</span>
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="font-medium text-white dir-ltr text-start hover:text-emerald-400 transition-colors">{{ $contactPhone }}</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Bottom Row: Sub-footer Divider & Legal Links -->
        <div class="pt-8 border-t border-blue-900/40 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-blue-200/60 font-medium">
            
            <div class="flex items-center gap-2 text-center md:text-start">
                <span>© ٢٠٢٦ {{ config('app.name', 'منصة مركز الأمين') }}. {{ __('جميع الحقوق محفوظة.') }}</span>
            </div>

            <!-- Security & Legal -->
            <div class="flex flex-wrap items-center justify-center gap-4 text-[11px] sm:text-xs">
                <a href="#" class="hover:text-white transition-colors">{{ __('سياسة الخصوصية') }}</a>
                <span>•</span>
                <a href="#" class="hover:text-white transition-colors">{{ __('الشروط والأحكام') }}</a>
                <span>•</span>
                <a href="#" class="hover:text-white transition-colors">{{ __('سياسة الاعتماد الأكاديمي') }}</a>
                <span>•</span>
                <a href="{{ route('admin.login') }}" class="text-[#F2AE2E] hover:underline font-bold">{{ __('بوابة الإدارة') }}</a>
            </div>

        </div>

    </div>

</footer>
