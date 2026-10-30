<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-[#fafcff] dark:bg-[#0B1120] min-h-screen text-[#475569] dark:text-slate-200 font-['Cairo'] antialiased flex flex-col justify-center items-center selection:bg-[#0b2a8f]/10 selection:text-[#0b2a8f] relative px-4 sm:px-6 lg:px-10 py-6">
        
        <!-- Subtle Top-Corner Controls (Language Switcher & Return to Home) -->
        <div class="fixed top-3 sm:top-6 end-3 sm:end-8 z-30 flex items-center gap-2 sm:gap-3">
            <x-language-switcher variant="pill" />
            
            <a
                href="{{ route('home') }}"
                class="size-9 sm:size-10 rounded-full bg-white dark:bg-slate-800 border border-[#dee8f7] dark:border-slate-700 shadow-xs flex items-center justify-center text-[#475569] dark:text-slate-300 hover:text-[#012383] hover:border-[#012383] transition-all cursor-pointer"
                title="{{ __('العودة إلى الرئيسية') }}"
                wire:navigate
            >
                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>

        @php
            $isRegister = request()->routeIs('register');
            $artImage = $isRegister ? '/assets/images/auth-sculpture.png' : '/assets/images/auth-welcome-back.png';
            $artEyebrow = $isRegister ? 'ابدأ رحلتك اليوم' : 'عودة إلى رحلتك';
            $artTitle = $isRegister ? 'ابدأ رحلتك التعليمية مع مركز الأمين' : 'مرحبًا بعودتك إلى الأمين';
            $artSubtitle = $isRegister ? 'تعلّم التخاطب بأسلوب عملي وتطبيقي معتمد.' : 'سعداء بعودتك. لنواصل التعلّم معًا.';
        @endphp

        <!-- Main Authentication Content Container -->
        <main class="flex flex-col lg:flex-row gap-8 lg:gap-16 xl:gap-24 items-center justify-center w-full max-w-[1360px] mx-auto my-auto pt-14 pb-8 sm:py-10 px-3 sm:px-6 lg:px-8">
            
            <!-- Auth Form Card Area -->
            <div class="flex flex-1 flex-col items-center justify-center w-full max-w-[480px] shrink-0 px-2 sm:px-0">
                <!-- Brand Logo (Top of Form) -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-6 self-start group transition-transform hover:scale-[1.01]" wire:navigate>
                    <div class="size-11 sm:size-12 shrink-0 relative bg-white rounded-2xl p-2 border border-[#dee8f7] dark:border-white/20 shadow-xs flex items-center justify-center group-hover:shadow-sm transition-all" style="background-color: #FFFFFF !important;">
                        <img src="/assets/images/logo.png" alt="{{ __('brand.name') }}" class="size-full object-contain pointer-events-none drop-shadow-xs" />
                    </div>
                    <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-2xl sm:text-[26px] leading-tight text-[#012383] dark:text-white group-hover:text-[#0b2a8f] transition-colors">
                        {{ __('brand.name') }}
                    </span>
                </a>

                <div class="flex flex-col gap-6 w-full text-start">
                    {{ $slot }}
                </div>
            </div>

            <!-- Visual Illustration Card (Left side in RTL) -->
            <div class="hidden lg:flex flex-col h-[640px] xl:h-[700px] w-[440px] xl:w-[500px] items-center justify-end overflow-hidden relative rounded-[28px] shrink-0 shadow-xl border border-[#dee8f7] dark:border-slate-800">
                <!-- Desk / Sculpture Image -->
                <img src="{{ $artImage }}" alt="منصة مركز الأمين" class="absolute inset-0 size-full object-cover rounded-[28px] pointer-events-none" />
                
                <!-- Dark Blue Gradient Overlay for High Contrast Text Readability -->
                <div class="absolute inset-0 rounded-[28px] pointer-events-none" style="background: linear-gradient(180deg, rgba(1, 14, 64, 0) 30%, rgba(1, 14, 64, 0.72) 65%, rgba(1, 14, 64, 0.95) 100%);"></div>
                
                <!-- Editorial Copy -->
                <div class="flex flex-col gap-3 items-start pb-10 pt-8 px-8 sm:px-10 relative z-10 w-full text-start text-white">
                    <!-- Eyebrow row with diamonds -->
                    <div class="flex items-center gap-2">
                        <div class="size-1.5 rotate-45 bg-[#93C5FD]"></div>
                        <p class="font-bold text-xs uppercase tracking-wider !text-[#BFDBFE]" style="color: #BFDBFE !important;">
                            {{ __($artEyebrow) }}
                        </p>
                        <div class="size-1.5 rotate-45 bg-[#93C5FD]"></div>
                    </div>

                    <!-- Editorial Title -->
                    <h2 class="font-['Baloo_Bhaijaan_2'] font-extrabold text-3xl xl:text-4xl leading-tight !text-white drop-shadow-sm" style="color: #FFFFFF !important;">
                        {{ __($artTitle) }}
                    </h2>

                    <!-- Editorial Subtitle -->
                    <p class="text-sm xl:text-base leading-relaxed !text-slate-100/95" style="color: #F1F5F9 !important;">
                        {{ __($artSubtitle) }}
                    </p>
                </div>
            </div>

        </main>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
