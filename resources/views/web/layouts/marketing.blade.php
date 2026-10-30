@props([
    'title' => null,
    'active' => 'home',
    'modals' => null,
    'scripts' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
    <head>
        @include('partials.head')
        <title>{{ filled($title ?? null) ? $title . ' | ' . config('app.name', 'منصة مركز الأمين') : config('app.name', 'منصة مركز الأمين') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FAFCFF] dark:bg-slate-950 min-h-screen text-[#334155] dark:text-slate-100 font-body antialiased selection:bg-[#EAF1FB] selection:text-[#0B2A8F] overflow-x-hidden relative transition-colors">
        
        <!-- Top-Level Pre-Rendered Themed Loader (At the top of everything from 0ms) -->
        <x-alameen-loader />

        <!-- Toast Notification Element -->
        <div
            id="toast"
            class="fixed bottom-6 start-6 z-50 bg-[#012383] text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none"
        >
            <svg class="size-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span id="toast-message" class="text-sm font-semibold"></span>
        </div>

        <!-- Main Full-Width Canvas Container with Smooth Reveal -->
        <div class="w-full min-h-screen flex flex-col relative bg-[#FAFCFF] dark:bg-slate-950 transition-colors alameen-content-wrap" id="landing-frame">
            
            <!-- Master Marketing Header -->
            <x-marketing-header :active="$active" />

            <!-- Main Content Area -->
            <main class="flex-1 flex flex-col">
                {{ $slot }}
            </main>

            <!-- Master Marketing Footer -->
            <x-marketing-footer />

        </div>

        {{ $modals ?? '' }}

        <!-- Landing & Animation Scripts -->
        <script type="module" src="/src/js/landing.js"></script>
        <script type="module" src="/src/js/animations.js"></script>
        {{ $scripts ?? '' }}

        @fluxScripts
    </body>
</html>
