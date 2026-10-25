@props([
    'title',
    'description',
    'eyebrow' => null,
])

<div class="flex w-full flex-col gap-1 text-start">
    @if ($eyebrow)
        <p class="font-semibold text-sm text-[#0B2A8F]">
            {{ $eyebrow }}
        </p>
    @endif
    <h1 class="font-['Cairo'] font-extrabold text-2xl sm:text-3xl text-[#012383] leading-tight">
        {{ $title }}
    </h1>
    @if ($description)
        <p class="text-sm text-[#475569] leading-relaxed">
            {{ $description }}
        </p>
    @endif
</div>
