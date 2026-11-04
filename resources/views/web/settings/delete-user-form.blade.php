<section class="mt-10 pt-8 border-t border-[#DEE8F7] dark:border-slate-800 text-start">
    <div class="rounded-2xl border border-rose-200 dark:border-rose-900/50 bg-rose-50/50 dark:bg-rose-950/20 p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="size-11 rounded-xl bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex flex-col text-start">
                <h3 class="font-bold text-base text-rose-900 dark:text-rose-300">
                    {{ __('منطقة الخطر: حذف الحساب') }}
                </h3>
                <p class="text-xs text-rose-700/80 dark:text-rose-400/80 mt-0.5 max-w-xl leading-relaxed">
                    {{ __('عند حذف حسابك، سيتم إزالة كافة السجلات الأكاديمية والشهادات وبيانات التقدّم نهائياً وبشكل لا يمكن استرجاعه.') }}
                </p>
            </div>
        </div>

        <flux:modal.trigger name="confirm-user-deletion">
            <button
                type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="h-11 px-5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-xs transition-colors shrink-0 flex items-center gap-1.5 cursor-pointer"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>{{ __('حذف الحساب نهائياً') }}</span>
            </button>
        </flux:modal.trigger>
    </div>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form method="POST" wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Are you sure you want to delete your account?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Password')" type="password" viewable />

            <div class="flex items-center justify-end gap-3 sm:gap-4 pt-3">
                <flux:modal.close>
                    <flux:button variant="filled" class="cursor-pointer px-4">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit" class="cursor-pointer px-4 ms-3 sm:ms-4">{{ __('Delete account') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
