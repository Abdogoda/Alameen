@props([
    'size' => 'size-4',
    'class' => '',
])

<svg {{ $attributes->merge(['class' => "alameen-btn-spinner $size shrink-0 animate-spin text-current $class"]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3.5" fill="none"></circle>
    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>

