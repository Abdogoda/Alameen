<flux:dropdown position="bottom" align="start">
    <flux:sidebar.profile
        :name="auth()->user()->name"
        :initials="auth()->user()->initials()"
        icon:trailing="chevrons-up-down"
        data-test="sidebar-menu-button"
    />

    <flux:menu>
        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
            <flux:avatar
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
            />
            <div class="grid flex-1 text-start text-sm leading-tight">
                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
            </div>
        </div>
        <flux:menu.separator />
        <flux:menu.radio.group>
            <flux:menu.item :href="route('notifications.index')" icon="bell" wire:navigate>
                {{ __('notifications.notifications') }}
            </flux:menu.item>
            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                {{ __('Settings') }}
            </flux:menu.item>
        </flux:menu.radio.group>
        <flux:menu.separator />
        <flux:menu.submenu icon="globe-alt" :heading="__('Language')">
            @foreach (config('localization.locales', []) as $code => $loc)
                <flux:menu.item
                    as="a"
                    :href="route('locale.switch', $code)"
                    class="cursor-pointer {{ app()->getLocale() === $code ? 'font-bold' : '' }}"
                >
                    <span class="me-2">{{ $loc['flag'] }}</span>
                    <span>{{ $loc['native'] }}</span>
                    @if (app()->getLocale() === $code)
                        <span class="ms-auto text-xs text-accent">✓</span>
                    @endif
                </flux:menu.item>
            @endforeach
        </flux:menu.submenu>
        <flux:menu.separator />
        <flux:menu.radio.group>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item
                    as="button"
                    type="submit"
                    icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer"
                    data-test="logout-button"
                >
                    {{ __('Log out') }}
                </flux:menu.item>
            </form>
        </flux:menu.radio.group>
    </flux:menu>
</flux:dropdown>
