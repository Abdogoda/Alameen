<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading level="2" class="sr-only">{{ __('theme.theme') }}</flux:heading>

    <x-settings.layout :heading="__('theme.theme')" :subheading="__('theme.select_theme')">
        <div class="space-y-6">
            <x-theme-switcher variant="segmented" />
        </div>
    </x-settings.layout>
</section>
