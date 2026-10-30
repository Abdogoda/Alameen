@props([
    'variant' => 'dropdown', // 'dropdown', 'pill', 'inline'
    'align' => 'end',
])

@php
    $locales = config('localization.locales', [
        'en' => ['name' => 'English', 'native' => 'English', 'dir' => 'ltr', 'flag' => '🇺🇸', 'flag_svg' => '/assets/images/flags/us.svg'],
        'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'dir' => 'rtl', 'flag' => '🇪🇬', 'flag_svg' => '/assets/images/flags/eg.svg'],
    ]);
    $current = app()->getLocale();
    $currentFlag = $locales[$current]['flag_svg'] ?? ($current === 'ar' ? '/assets/images/flags/eg.svg' : '/assets/images/flags/us.svg');
@endphp

@if ($variant === 'pill')
    <div {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full border border-[#D7E3F5] bg-[#EAF1FB] p-1 text-xs shadow-xs']) }}>
        @foreach ($locales as $code => $locale)
            @php
                $itemFlag = $locale['flag_svg'] ?? ($code === 'ar' ? '/assets/images/flags/eg.svg' : '/assets/images/flags/us.svg');
            @endphp
            <a
                href="{{ route('locale.switch', $code) }}"
                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold transition-all {{ $current === $code ? 'bg-white text-[#0B2A8F] shadow-sm' : 'text-[#475569] hover:text-[#012383]' }}"
                title="{{ $locale['name'] }}"
            >
                <img src="{{ $itemFlag }}" alt="{{ $locale['name'] }}" class="w-4 h-2.5 rounded-[2px] object-cover shadow-xs border border-slate-200/80 dark:border-slate-700 shrink-0" />
                <span>{{ $locale['native'] }}</span>
            </a>
        @endforeach
    </div>
@elseif ($variant === 'inline')
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2 text-sm']) }}>
        @foreach ($locales as $code => $locale)
            @php
                $itemFlag = $locale['flag_svg'] ?? ($code === 'ar' ? '/assets/images/flags/eg.svg' : '/assets/images/flags/us.svg');
            @endphp
            <a
                href="{{ route('locale.switch', $code) }}"
                class="inline-flex items-center gap-1.5 transition-colors {{ $current === $code ? 'font-semibold text-zinc-900 dark:text-white underline underline-offset-4' : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white' }}"
            >
                <img src="{{ $itemFlag }}" alt="{{ $locale['name'] }}" class="w-4.5 h-3 rounded-[2px] object-cover shadow-xs border border-slate-200/80 dark:border-slate-700 shrink-0" />
                <span>{{ $locale['native'] }}</span>
            </a>
            @if (! $loop->last)
                <span class="text-zinc-300 dark:text-zinc-700">|</span>
            @endif
        @endforeach
    </div>
@else
    {{-- Default: Elegant Dropdown Button --}}
    <div
        x-data="{ open: false }"
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        class="relative inline-block text-start"
        {{ $attributes }}
    >
        <button
            type="button"
            @click="open = !open"
            class="h-8 sm:h-10 px-2 sm:px-3 rounded-full bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:bg-[#EAF1FB] dark:hover:bg-slate-700/80 text-[#012383] dark:text-slate-200 transition-all cursor-pointer shadow-xs flex items-center gap-1.5 sm:gap-2 group text-xs sm:text-sm font-bold font-['Cairo'] shrink-0"
            aria-label="{{ __('اللغة') }}"
        >
            <img src="{{ $currentFlag }}" alt="{{ $locales[$current]['name'] ?? $current }}" class="w-4.5 sm:w-5 h-3 sm:h-3.5 rounded-[3px] object-cover shadow-xs border border-slate-200/80 dark:border-slate-700 shrink-0" />
            <span class="hidden sm:inline">{{ $locales[$current]['native'] ?? strtoupper($current) }}</span>
            <svg class="size-2.5 sm:size-3 text-slate-400 group-hover:text-[#012383] dark:group-hover:text-white transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute {{ $align === 'end' ? 'ltr:right-0 rtl:left-0' : 'ltr:left-0 rtl:right-0' }} mt-2 w-44 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xl p-1.5 z-50 flex flex-col gap-1 font-['Cairo']"
            style="display: none;"
        >
            <div class="px-3 py-1.5 border-b border-[#DEE8F7] dark:border-slate-800 mb-1">
                <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('اللغة') }}</p>
            </div>

            @foreach ($locales as $code => $locale)
                @php
                    $itemFlag = $locale['flag_svg'] ?? ($code === 'ar' ? '/assets/images/flags/eg.svg' : '/assets/images/flags/us.svg');
                @endphp
                <a
                    href="{{ route('locale.switch', $code) }}"
                    class="flex items-center justify-between w-full px-3 py-2 rounded-xl text-xs font-bold transition-colors cursor-pointer {{ $current === $code ? 'bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400' : 'text-[#475569] dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60' }}"
                >
                    <div class="flex items-center gap-2">
                        <img src="{{ $itemFlag }}" alt="{{ $locale['name'] }}" class="w-5 h-3.5 rounded-[3px] object-cover shadow-xs border border-slate-200/80 dark:border-slate-700 shrink-0" />
                        <span>{{ $locale['native'] }}</span>
                    </div>
                    @if ($current === $code)
                        <svg class="size-4 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endif
