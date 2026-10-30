<div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-start gap-6 lg:gap-8 w-full min-w-0">
    <div class="settings-nav-col w-full md:w-60 md:basis-60 shrink-0 border-b md:border-b-0 md:border-e border-[#DEE8F7] dark:border-slate-800 pb-4 md:pb-0 md:pe-6">
        <flux:navlist aria-label="{{ __('Settings') }}">
            <flux:navlist.item :href="route('profile.edit')" wire:navigate :current="request()->routeIs('profile.*')">{{ __('الملف الشخصي') }}</flux:navlist.item>
            <flux:navlist.item :href="route('security.edit')" wire:navigate :current="request()->routeIs('security.*')">{{ __('الأمان وكلمة المرور') }}</flux:navlist.item>
            <flux:navlist.item :href="route('appearance.edit')" wire:navigate :current="request()->routeIs('appearance.*')">{{ __('المظهر والتخصيص') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <div class="settings-content-col flex-1 self-stretch min-w-0 w-full">
        @if (isset($heading))
            <h2 class="font-bold text-lg text-[#012383] dark:text-white">{{ $heading }}</h2>
        @endif
        @if (isset($subheading))
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $subheading }}</p>
        @endif

        <div class="mt-5 w-full max-w-xl">
            {{ $slot }}
        </div>
    </div>
</div>
