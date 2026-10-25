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
    <div {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full border border-[#D7E3F5] bg-[#EAF1FB] p-1 text-xs shadow-xs']) }}>
        @foreach ($locales as $code => $locale)
            <a
                href="{{ route('locale.switch', $code) }}"
                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold transition-all {{ $current === $code ? 'bg-white text-[#0B2A8F] shadow-sm' : 'text-[#475569] hover:text-[#012383]' }}"
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
                class="inline-flex items-center gap-1 transition-colors {{ $current === $code ? 'font-semibold text-zinc-900 dark:text-white underline underline-offset-4' : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white' }}"
            >
                <span>{{ $locale['flag'] }}</span>
                <span>{{ $locale['native'] }}</span>
            </a>
            @if (! $loop->last)
                <span class="text-zinc-300 dark:text-zinc-700">|</span>
            @endif
        @endforeach
    </div>
@else
    {{-- Default: Dropdown --}}
    <flux:dropdown :align="$align" {{ $attributes }}>
        <flux:button
            variant="ghost"
            size="sm"
            icon="globe-alt"
            icon-trailing="chevron-down"
            class="cursor-pointer text-xs font-medium"
        >
            <span class="me-1">{{ $locales[$current]['flag'] ?? '🌐' }}</span>
            <span>{{ $locales[$current]['native'] ?? strtoupper($current) }}</span>
        </flux:button>

        <flux:menu class="min-w-36">
            <flux:menu.radio.group>
                @foreach ($locales as $code => $locale)
                    <flux:menu.item
                        as="a"
                        :href="route('locale.switch', $code)"
                        :checked="$current === $code"
                        class="flex items-center justify-between cursor-pointer"
                    >
                        <span class="flex items-center gap-2">
                            <span>{{ $locale['flag'] }}</span>
                            <span class="font-medium">{{ $locale['native'] }}</span>
                        </span>
                        <span class="text-xs text-zinc-400 uppercase">({{ $code }})</span>
                    </flux:menu.item>
                @endforeach
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
@endif
