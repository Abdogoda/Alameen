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
@endphp

        <!-- Top Row: Main Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10">
            
            <!-- Column 1: Brand & Identity (Lg: 4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-5">
                
                <!-- Brand Logo with soft badge -->
                <a href="{{ route('home') }}" class="flex gap-3 items-center group w-fit">
                    <div class="size-11 rounded-2xl flex items-center justify-center group-hover:scale-105 transition-transform">
                        <img src="/assets/images/logo.png" alt="{{ $siteName }}" class="size-full object-contain" />
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
                <div class="flex items-center gap-2.5 pt-2">
                    <!-- WhatsApp -->
                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="واتساب"
                        class="size-9 rounded-xl bg-blue-950/80 border border-blue-800/50 hover:bg-[#10B77F] hover:border-[#10B77F] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                    >
                        <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.634.07-.999-.074-.755-.298-1.52-.752-2.227-1.458-.894-.893-1.425-1.923-1.688-2.338-.263-.414-.029-.636.143-.808.156-.156.342-.405.513-.607.172-.202.229-.344.343-.573.114-.229.057-.428-.029-.6-.086-.172-.771-1.859-1.057-2.545-.278-.669-.561-.579-.771-.59-.2-.01-.429-.012-.657-.012-.229 0-.6.086-.914.428-.314.343-1.2 1.171-1.2 2.858 0 1.687 1.229 3.315 1.4 3.543.172.229 2.417 3.691 5.857 5.176 2.851 1.231 3.432.986 4.05.928.618-.057 1.99-.813 2.275-1.599.285-.785.285-1.457.2-1.599-.085-.143-.314-.229-.657-.401z"/>
                        </svg>
                    </a>
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
