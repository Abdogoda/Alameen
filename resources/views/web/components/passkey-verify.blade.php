@props([
    'optionsRoute' => 'passkey.login-options',
    'submitRoute' => 'passkey.login',
    'label' => __('Sign in with a passkey'),
    'loadingLabel' => __('Authenticating...'),
    'separator' => false,
])

@assets
@vite('resources/js/passkeys.js')
@endassets

<div
    x-data="{
        supported: false,
        loading: false,
        error: null,
        updateSupport() {
            this.supported = Boolean(window.Passkeys?.isSupported());
        },
        init() {
            this.updateSupport();

            window.addEventListener('passkeys:ready', () => this.updateSupport(), { once: true });
        },
        async verify() {
            this.loading = true;
            this.error = null;
            try {
                const response = await window.Passkeys.verify({
                    routes: {
                        options: '{{ route($optionsRoute) }}',
                        submit: '{{ route($submitRoute) }}',
                    },
                });
                Livewire.navigate(response.redirect || '/dashboard');
            } catch (e) {
                if (e.constructor?.name !== 'UserCancelledError') {
                    this.error = e.message;
                }
            } finally {
                this.loading = false;
            }
        },
    }"
>
    <template x-if="supported">
        <div>
            <div class="grid gap-2">
                <button
                    type="button"
                    class="h-[50px] w-full rounded-xl border border-[#DEE8F7] hover:border-[#0B2A8F]/40 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-[#F8FAFC] dark:hover:bg-slate-700/80 text-[#012383] dark:text-blue-300 font-bold text-sm shadow-xs hover:shadow-sm active:scale-[0.99] transition-all flex items-center justify-center gap-2.5 cursor-pointer select-none disabled:opacity-50"
                    x-on:click="verify()"
                    x-bind:disabled="loading"
                >
                    <svg class="size-5 text-[#0B2A8F] dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11a7.96 7.96 0 001.272 4.316m5.748 4.288A19.92 19.92 0 0112 21"/></svg>
                    <span x-show="!loading">{{ $label }}</span>
                    <span x-show="loading" x-cloak>{{ $loadingLabel }}</span>
                </button>
                <p x-show="error" x-text="error" x-cloak
                   class="text-xs text-center text-rose-600 font-semibold"></p>
            </div>

            @if ($separator)
            <div class="relative my-4">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-[#DEE8F7] dark:border-slate-800"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-3 text-[#475569] dark:text-slate-400 bg-[#FAFCFF] dark:bg-[#0B1120] font-semibold">
                        {{ $separator }}
                    </span>
                </div>
            </div>
            @endif
        </div>
    </template>
</div>
