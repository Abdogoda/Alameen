<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-[#DEE8F7]">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center justify-center size-9 rounded-xl bg-[#EAF1FB] text-[#0B2A8F] font-bold">
                    <flux:icon.bell class="size-5" />
                </span>
                <h1 class="text-2xl font-bold font-['Baloo_Bhaijaan_2'] text-[#012383]">
                    {{ __('notifications.admin.title') }}
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                {{ __('notifications.admin.subtitle') }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a
                href="{{ route('notifications.index') }}"
                wire:navigate
                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#0B2A8F] transition shadow-xs cursor-pointer"
            >
                <flux:icon.inbox class="size-4" />
                {{ __('notifications.notification_center') }}
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-[#DEE8F7] shadow-xs hover:shadow-card transition-all">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                {{ __('notifications.admin.stats.total_sent') }}
            </span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-[#012383]">
                    {{ number_format($stats['total']) }}
                </span>
                <div class="size-8 rounded-lg bg-blue-50 text-[#0B2A8F] flex items-center justify-center">
                    <flux:icon.paper-airplane class="size-4" />
                </div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-[#DEE8F7] shadow-xs hover:shadow-card transition-all">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                {{ __('notifications.admin.stats.unread') }}
            </span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-amber-600">
                    {{ number_format($stats['unread']) }}
                </span>
                <div class="size-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <flux:icon.envelope class="size-4" />
                </div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-[#DEE8F7] shadow-xs hover:shadow-card transition-all">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                {{ __('notifications.admin.stats.read') }}
            </span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-emerald-600">
                    {{ number_format($stats['read']) }}
                </span>
                <div class="size-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <flux:icon.envelope-open class="size-4" />
                </div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-[#DEE8F7] shadow-xs hover:shadow-card transition-all">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                {{ __('notifications.admin.stats.recipients_reached') }}
            </span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-[#0B2A8F]">
                    {{ number_format($stats['recipients_count']) }}
                </span>
                <div class="size-8 rounded-lg bg-indigo-50 text-[#0B2A8F] flex items-center justify-center">
                    <flux:icon.users class="size-4" />
                </div>
            </div>
        </div>
    </div>

    @if ($feedbackMessage)
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <flux:icon.check-circle class="size-5 text-emerald-600 dark:text-emerald-400" />
                <span>{{ $feedbackMessage }}</span>
            </div>
            <button type="button" wire:click="$set('feedbackMessage', null)" class="text-emerald-600 hover:text-emerald-800">&times;</button>
        </div>
    @endif

    {{-- Send Notification Panel --}}
    <div class="p-6 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
        <h2 class="text-base font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-2 mb-4">
            <flux:icon.paper-airplane class="size-5 text-accent" />
            {{ __('notifications.admin.send_new') }}
        </h2>

        <form wire:submit="sendNotification" class="space-y-6">
            {{-- Target & Recipient --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        {{ __('notifications.admin.recipient') }}
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer">
                            <input type="radio" wire:model.live="target" value="all" class="text-accent focus:ring-accent" />
                            <span>{{ __('notifications.admin.all_users') }}</span>
                        </label>
                        <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer">
                            <input type="radio" wire:model.live="target" value="user" class="text-accent focus:ring-accent" />
                            <span>{{ __('notifications.admin.specific_user') }}</span>
                        </label>
                    </div>
                </div>

                @if ($target === 'user')
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            {{ __('notifications.admin.select_user') }}
                        </label>
                        <select
                            wire:model="selectedUserId"
                            class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent"
                        >
                            <option value="">{{ __('notifications.admin.select_user') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('selectedUserId') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif
            </div>

            {{-- Channels & Type --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        {{ __('notifications.admin.channels') }}
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer">
                            <input type="checkbox" wire:model="channels" value="database" class="rounded text-accent focus:ring-accent" />
                            <span>{{ __('notifications.channels.database') }}</span>
                        </label>
                        <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer">
                            <input type="checkbox" wire:model="channels" value="mail" class="rounded text-accent focus:ring-accent" />
                            <span>{{ __('notifications.channels.mail') }}</span>
                        </label>
                    </div>
                    @error('channels') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        {{ __('notifications.admin.type') }}
                    </label>
                    <select
                        wire:model="type"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent"
                    >
                        <option value="info">{{ __('notifications.types.info') }}</option>
                        <option value="success">{{ __('notifications.types.success') }}</option>
                        <option value="warning">{{ __('notifications.types.warning') }}</option>
                        <option value="error">{{ __('notifications.types.error') }}</option>
                        <option value="security">{{ __('notifications.types.security') }}</option>
                        <option value="system">{{ __('notifications.types.system') }}</option>
                    </select>
                </div>
            </div>

            {{-- Bilingual Title --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                        {{ __('notifications.admin.title_en') }} *
                    </label>
                    <input
                        type="text"
                        wire:model="titleEn"
                        dir="ltr"
                        placeholder="e.g. System Maintenance Notice"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent"
                    />
                    @error('titleEn') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                        {{ __('notifications.admin.title_ar') }}
                    </label>
                    <input
                        type="text"
                        wire:model="titleAr"
                        dir="rtl"
                        placeholder="مثال: إشعار أعمال صيانة للنظام"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent"
                    />
                </div>
            </div>

            {{-- Bilingual Body --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                        {{ __('notifications.admin.body_en') }} *
                    </label>
                    <textarea
                        wire:model="bodyEn"
                        rows="3"
                        dir="ltr"
                        placeholder="Provide details in English..."
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent"
                    ></textarea>
                    @error('bodyEn') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                        {{ __('notifications.admin.body_ar') }}
                    </label>
                    <textarea
                        wire:model="bodyAr"
                        rows="3"
                        dir="rtl"
                        placeholder="تفاصيل الإشعار باللغة العربية..."
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent"
                    ></textarea>
                </div>
            </div>

            {{-- Optional Action Link & Text --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                        {{ __('notifications.admin.action_url') }}
                    </label>
                    <input
                        type="url"
                        wire:model="actionUrl"
                        placeholder="https://example.com/update"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent"
                    />
                    @error('actionUrl') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                        {{ __('notifications.admin.action_text_en') }}
                    </label>
                    <input
                        type="text"
                        wire:model="actionTextEn"
                        placeholder="e.g. View Details"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent"
                    />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                        {{ __('notifications.admin.action_text_ar') }}
                    </label>
                    <input
                        type="text"
                        wire:model="actionTextAr"
                        placeholder="مثال: عرض التفاصيل"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent"
                    />
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end pt-2">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 font-semibold text-sm hover:opacity-90 transition shadow-sm"
                >
                    <span wire:loading.remove>{{ __('notifications.admin.send_button') }}</span>
                    <span wire:loading>{{ __('notifications.admin.sending') }}</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Notification History Table --}}
    <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">
                {{ __('notifications.admin.history') }}
            </h3>

            <div class="flex items-center gap-3">
                <select
                    wire:model.live="filterType"
                    class="rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-1.5 text-xs text-zinc-900 dark:text-zinc-100 focus:outline-none"
                >
                    <option value="all">{{ __('notifications.admin.all_types') }}</option>
                    <option value="info">{{ __('notifications.types.info') }}</option>
                    <option value="success">{{ __('notifications.types.success') }}</option>
                    <option value="warning">{{ __('notifications.types.warning') }}</option>
                    <option value="error">{{ __('notifications.types.error') }}</option>
                    <option value="security">{{ __('notifications.types.security') }}</option>
                    <option value="system">{{ __('notifications.types.system') }}</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-5 py-3 text-start">{{ __('notifications.admin.recipient') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('notifications.admin.title_label') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('notifications.admin.type') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('Status') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('Date') }}</th>
                        <th class="px-5 py-3 text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 text-zinc-700 dark:text-zinc-300">
                    @forelse ($history as $item)
                        @php
                            $user = $item->notifiable;
                            $data = $item->data;
                            $type = $data['type'] ?? 'info';
                            $title = $data['title'] ?? '-';
                            $typeColors = match($type) {
                                'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400',
                                'warning' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400',
                                'error' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400',
                                'security' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400',
                                'system' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400',
                                default => 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400',
                            };
                        @endphp
                        <tr class="hover:bg-zinc-50/60 dark:hover:bg-zinc-800/40">
                            <td class="px-5 py-3.5">
                                @if ($user)
                                    <div class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $user->name }}</div>
                                    <div class="text-[11px] text-zinc-400">{{ $user->email }} ({{ strtoupper($user->locale ?? 'EN') }})</div>
                                @else
                                    <span class="text-zinc-400">#{{ $item->notifiable_id }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 max-w-xs truncate font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $title }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $typeColors }}">
                                    {{ __('notifications.types.' . $type) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if ($item->read_at)
                                    <span class="inline-flex items-center gap-1 text-[11px] text-emerald-600 dark:text-emerald-400">
                                        ✓ {{ __('notifications.admin.stats.read') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] text-amber-600 dark:text-amber-400 font-medium">
                                        &bull; {{ __('notifications.admin.stats.unread') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-zinc-400 whitespace-nowrap">
                                {{ $item->created_at->diffForHumans() }}
                            </td>
                            <td class="px-5 py-3.5 text-end">
                                <button
                                    type="button"
                                    wire:click="deleteNotification('{{ $item->id }}')"
                                    wire:confirm="{{ __('notifications.admin.delete_confirm') }}"
                                    class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition"
                                    title="{{ __('Delete') }}"
                                >
                                    <flux:icon.trash class="size-4" />
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-zinc-400">
                                {{ __('notifications.no_notifications') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($history->hasPages())
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                {{ $history->links() }}
            </div>
        @endif
    </div>
</div>
