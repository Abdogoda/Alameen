<!-- UNIFIED MASTER FOOTER -->
<footer class="w-full bg-[#031338] text-white relative overflow-hidden border-t border-[#122A63]">
    
    <!-- Ambient Decorative Glows -->
    <div class="absolute -top-32 start-10 size-[380px] rounded-full bg-blue-600/10 blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute -bottom-24 end-12 size-[320px] rounded-full bg-indigo-500/10 blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-[1536px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-24 2xl:px-32 pt-14 lg:pt-16 pb-8 flex flex-col gap-12">
        
        <!-- Top Row: Main Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10">
            
            <!-- Column 1: Brand & Identity (Lg: 4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-5">
                
                <!-- Brand Logo with soft badge -->
                <a href="{{ route('home') }}" class="flex gap-3 items-center group w-fit">
                    <div class="size-11 rounded-2xl bg-white p-1.5 flex items-center justify-center shadow-md shadow-blue-950/50 group-hover:scale-105 transition-transform">
                        <img src="/assets/images/logo.png" alt="{{ config('app.name', 'منصة مركز الأمين') }}" class="size-full object-contain" />
                    </div>
                    <div class="flex flex-col text-start">
                        <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-2xl text-white tracking-tight leading-tight">
                            {{ config('app.name', 'منصة مركز الأمين') }}
                        </span>
                        <span class="text-[11px] text-blue-200/80 font-bold font-['Cairo']">
                            {{ __('لتأهيل التخاطب والنطق') }}
                        </span>
                    </div>
                </a>

                <!-- Mission Statement -->
                <p class="text-xs sm:text-[13px] leading-relaxed text-blue-100/70 font-medium max-w-sm">
                    {{ __('المنصة العربية المتخصصة في تدريب وتأهيل مهارات النطق والتخاطب، نجمع بين الأسس العلمية المعتمدة والتطبيقات السريرية العملية بإشراف نخبة من كبار أخصائيي التخاطب.') }}
                </p>

                <!-- Social Media Links -->
                <div class="flex items-center gap-2.5 pt-2">
                    <!-- WhatsApp -->
                    <a
                        href="https://wa.me/966501234567"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="واتساب"
                        class="size-9 rounded-xl bg-blue-950/80 border border-blue-800/50 hover:bg-[#10B77F] hover:border-[#10B77F] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                    >
                        <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.634.07-.999-.074-.755-.298-1.52-.752-2.227-1.458-.894-.893-1.425-1.923-1.688-2.338-.263-.414-.029-.636.143-.808.156-.156.342-.405.513-.607.172-.202.229-.344.343-.573.114-.229.057-.428-.029-.6-.086-.172-.771-1.859-1.057-2.545-.278-.669-.561-.579-.771-.59-.2-.01-.429-.012-.657-.012-.229 0-.6.086-.914.428-.314.343-1.2 1.171-1.2 2.858 0 1.687 1.229 3.315 1.4 3.543.172.229 2.417 3.691 5.857 5.176 2.851 1.231 3.432.986 4.05.928.618-.057 1.99-.813 2.275-1.599.285-.785.285-1.457.2-1.599-.085-.143-.314-.229-.657-.401z"/>
                        </svg>
                    </a>
                    <!-- X / Twitter -->
                    <a
                        href="#"
                        aria-label="منصة إكس"
                        class="size-9 rounded-xl bg-blue-950/80 border border-blue-800/50 hover:bg-[#0B2A8F] hover:border-blue-500 text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                    >
                        <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <!-- LinkedIn -->
                    <a
                        href="#"
                        aria-label="لينكد إن"
                        class="size-9 rounded-xl bg-blue-950/80 border border-blue-800/50 hover:bg-[#0077B5] hover:border-[#0077B5] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                    >
                        <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                    <!-- YouTube -->
                    <a
                        href="#"
                        aria-label="يوتيوب"
                        class="size-9 rounded-xl bg-blue-950/80 border border-blue-800/50 hover:bg-[#FF0000] hover:border-[#FF0000] text-blue-200 hover:text-white flex items-center justify-center transition-all shadow-xs group cursor-pointer"
                    >
                        <svg class="size-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>

            </div>

            <!-- Column 2: كورس التخاطب (Lg: 4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-4 rounded-full bg-[#10B77F]"></span>
                    <h4 class="font-['Cairo'] font-bold text-sm text-white tracking-wide">
                        {{ __('كورس التخاطب الشامل') }}
                    </h4>
                </div>
                <ul class="flex flex-col gap-2.5 text-xs font-semibold text-blue-200/75">
                    <li>
                        <a href="{{ route('courses.index') }}" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                            <span class="text-blue-400">›</span>
                            {{ __('محاور كورس التخاطب الشامل') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                            <span class="text-blue-400">›</span>
                            {{ __('استراتيجيات تنمية اللغة والتواصل') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                            <span class="text-blue-400">›</span>
                            {{ __('تطبيقات مخارج الحروف والأصوات') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}" class="hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1 transition-all inline-flex items-center gap-1.5">
                            <span class="text-blue-400">›</span>
                            {{ __('التدريب السريري لاضطرابات النطق') }}
                        </a>
                    </li>
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
                            <a href="mailto:support@alameen.edu.sa" class="font-medium hover:text-white transition-colors">support@alameen.edu.sa</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <div class="size-7 rounded-lg bg-blue-900/40 border border-blue-800/40 flex items-center justify-center text-blue-300 shrink-0">
                            📞
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-blue-300 font-bold">{{ __('واتساب المباشر') }}</span>
                            <a href="https://wa.me/966501234567" target="_blank" rel="noopener noreferrer" class="font-medium text-white dir-ltr text-start hover:text-emerald-400 transition-colors">+966 50 123 4567</a>
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
