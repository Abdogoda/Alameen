@props([
    'variant' => 'dropdown', // 'dropdown', 'pill', 'inline'
    'align' => 'end',
])

@php
    $locales = config('localization.locales', [
        'en' => ['name' => 'English', 'native' => 'English', 'dir' => 'ltr', 'flag' => '🇺🇸'],
        'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'dir' => 'rtl', 'flag' => '🇸🇦'],
    ]);
    $current = app()->getLocale();
@endphp

@if ($variant === 'pill')
    <div {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full border border-slate-700 bg-slate-800/80 p-1 text-xs shadow-xs']) }}>
        @foreach ($locales as $code => $locale)
            <a
                href="{{ route('locale.switch', $code) }}"
                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold transition-all {{ $current === $code ? 'bg-[#0B2A8F] text-white shadow-sm' : 'text-slate-300 hover:text-white' }}"
                title="{{ $locale['name'] }}"
            >
                <span>{{ $locale['native'] }}</span>
            </a>
        @endforeach
    </div>
@elseif ($variant === 'inline')
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2 text-sm']) }}>
        @foreach ($locales as $code => $locale)
            <a
                href="{{ route('locale.switch', $code) }}"
                class="inline-flex items-center gap-1 transition-colors {{ $current === $code ? 'font-semibold text-white underline underline-offset-4' : 'text-slate-400 hover:text-white' }}"
            >
                <span>{{ $locale['flag'] }}</span>
                <span>{{ $locale['native'] }}</span>
            </a>
            @if (! $loop->last)
                <span class="text-slate-600">|</span>
            @endif
        @endforeach
    </div>
@else
    {{-- Dropdown style for Admin Topbars --}}
    <div x-data="{ open: false }" class="relative inline-block text-left" @click.outside="open = false">
        <button
            type="button"
            @click="open = !open"
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-700/80 bg-slate-800/90 hover:bg-slate-700/80 text-xs font-semibold text-slate-200 transition-all cursor-pointer shadow-xs"
        >
            <span>{{ $locales[$current]['flag'] ?? '🌐' }}</span>
            <span>{{ $locales[$current]['native'] ?? strtoupper($current) }}</span>
            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute {{ $align === 'end' ? 'ltr:right-0 rtl:left-0' : 'ltr:left-0 rtl:right-0' }} z-50 mt-2 w-36 origin-top-right rounded-xl border border-slate-700 bg-slate-900 p-1.5 shadow-xl ring-1 ring-black/50"
            style="display: none;"
        >
            @foreach ($locales as $code => $locale)
                <a
                    href="{{ route('locale.switch', $code) }}"
                    class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-all {{ $current === $code ? 'bg-[#0B2A8F] text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <span class="flex items-center gap-2">
                        <span>{{ $locale['flag'] }}</span>
                        <span>{{ $locale['native'] }}</span>
                    </span>
                    <span class="text-[10px] uppercase {{ $current === $code ? 'text-white/80' : 'text-slate-500' }}">({{ $code }})</span>
                </a>
            @endforeach
        </div>
    </div>
@endif
