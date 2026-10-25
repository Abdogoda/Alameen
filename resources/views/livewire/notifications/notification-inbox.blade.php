<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-zinc-200 dark:border-zinc-800">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">
                    {{ __('notifications.notification_center') }}
                </h1>
                @if ($unreadCount > 0)
                    <span class="rounded-full bg-accent/10 text-accent px-2.5 py-0.5 text-xs font-semibold">
                        {{ $unreadCount }} {{ __('notifications.unread_notifications') }}
                    </span>
                @endif
            </div>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                {{ __('notifications.all_notifications') }}
            </p>
        </div>

        @if ($unreadCount > 0)
            <div>
                <button
                    type="button"
                    wire:click="markAllAsRead"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-800 dark:bg-zinc-800 dark:hover:bg-zinc-700 dark:text-zinc-200 transition shadow-sm"
                >
                    <flux:icon.check class="size-4" />
                    {{ __('notifications.mark_all_as_read') }}
                </button>
            </div>
        @endif
    </div>

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-1.5 overflow-x-auto py-4 border-b border-zinc-100 dark:border-zinc-800/80 scrollbar-none">
        @php
            $tabs = [
                'all' => __('notifications.all_notifications'),
                'unread' => __('notifications.unread_notifications'),
                'security' => __('notifications.types.security'),
                'info' => __('notifications.types.info'),
                'warning' => __('notifications.types.warning'),
                'system' => __('notifications.types.system'),
            ];
        @endphp

        @foreach ($tabs as $key => $label)
            <button
                type="button"
                wire:click="setFilter('{{ $key }}')"
                class="px-3.5 py-1.5 text-xs font-medium rounded-lg whitespace-nowrap transition {{ $filter === $key ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 shadow-sm' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-zinc-100 dark:hover:bg-zinc-800' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Notifications List --}}
    <div class="mt-6 divide-y divide-zinc-100 dark:divide-zinc-800 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        @forelse ($notifications as $notification)
            @php
                $isUnread = $notification->read_at === null;
                $type = $notification->data['type'] ?? 'info';
                $title = $notification->data['title'] ?? '';
                $body = $notification->data['body'] ?? '';
                $actionUrl = $notification->data['action_url'] ?? null;
                $actionText = $notification->data['action_text'] ?? __('notifications.view_details');
                $typeColors = match($type) {
                    'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400',
                    'warning' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400',
                    'error' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400',
                    'security' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400',
                    'system' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400',
                    default => 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400',
                };
            @endphp
            <div class="p-5 flex flex-col sm:flex-row sm:items-start gap-4 transition hover:bg-zinc-50/60 dark:hover:bg-zinc-800/40 {{ $isUnread ? 'bg-zinc-50/40 dark:bg-zinc-800/20' : '' }}">
                <div class="shrink-0">
                    <span class="inline-flex size-11 items-center justify-center rounded-2xl {{ $typeColors }}">
                        @if ($type === 'security')
                            <flux:icon.shield-check class="size-5" />
                        @elseif ($type === 'success')
                            <flux:icon.check-circle class="size-5" />
                        @elseif ($type === 'warning')
                            <flux:icon.exclamation-triangle class="size-5" />
                        @elseif ($type === 'error')
                            <flux:icon.x-circle class="size-5" />
                        @elseif ($type === 'system')
                            <flux:icon.cog class="size-5" />
                        @else
                            <flux:icon.bell class="size-5" />
                        @endif
                    </span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-xs uppercase font-semibold tracking-wider text-zinc-400 dark:text-zinc-500">
                            {{ __('notifications.types.' . $type) }}
                        </span>
                        <span class="text-xs text-zinc-300 dark:text-zinc-700">&bull;</span>
                        <span class="text-xs text-zinc-400 dark:text-zinc-500">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                        @if ($isUnread)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-500">
                                {{ __('notifications.unread_notifications') }}
                            </span>
                        @endif
                    </div>

                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mt-1">
                        {{ $title }}
                    </h2>

                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1 leading-relaxed">
                        {{ $body }}
                    </p>

                    @if ($actionUrl)
                        <div class="mt-3">
                            <a
                                href="{{ $actionUrl }}"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent hover:underline"
                            >
                                {{ $actionText }} &rarr;
                            </a>
                        </div>
                    @endif
                </div>

                <div class="shrink-0 flex items-center sm:flex-col gap-2 self-end sm:self-auto pt-2 sm:pt-0">
                    @if ($isUnread)
                        <button
                            type="button"
                            wire:click="markAsRead('{{ $notification->id }}')"
                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-lg bg-zinc-100 hover:bg-zinc-200 text-zinc-700 dark:bg-zinc-800 dark:hover:bg-zinc-700 dark:text-zinc-300 transition"
                            title="{{ __('notifications.mark_as_read') }}"
                        >
                            <flux:icon.check class="size-3.5" />
                            <span class="hidden sm:inline">{{ __('notifications.mark_as_read') }}</span>
                        </button>
                    @endif

                    <button
                        type="button"
                        wire:click="delete('{{ $notification->id }}')"
                        class="p-1.5 text-xs rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition"
                        title="{{ __('Delete') }}"
                    >
                        <flux:icon.trash class="size-4" />
                    </button>
                </div>
            </div>
        @empty
            <div class="py-16 text-center px-4">
                <div class="size-14 mx-auto rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-400 dark:text-zinc-500 mb-4">
                    <flux:icon.bell-slash class="size-7" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ __('notifications.no_notifications') }}
                </h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 max-w-sm mx-auto">
                    {{ __('notifications.no_unread_notifications') }}
                </p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
