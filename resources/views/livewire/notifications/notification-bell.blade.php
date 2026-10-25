<div class="relative" x-data="{ open: @entangle('open') }" @click.outside="open = false">
    {{-- Bell Button --}}
    <button
        type="button"
        @click="open = !open"
        class="relative inline-flex items-center justify-center h-10 w-10 rounded-lg text-zinc-500 hover:text-zinc-800 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-zinc-100 dark:hover:bg-zinc-800 transition focus:outline-none"
        aria-label="{{ __('notifications.notifications') }}"
    >
        <flux:icon.bell class="size-5" />

        @if ($unreadCount > 0)
            <span class="absolute top-1.5 end-1.5 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white shadow-sm ring-2 ring-white dark:ring-zinc-900 animate-pulse">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute end-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-2xl z-50 overflow-hidden"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/70 dark:bg-zinc-800/40">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-sm text-zinc-900 dark:text-zinc-100">
                    {{ __('notifications.notifications') }}
                </span>
                @if ($unreadCount > 0)
                    <span class="rounded-full bg-zinc-200 dark:bg-zinc-700 px-2 py-0.5 text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                        {{ $unreadCount }}
                    </span>
                @endif
            </div>

            @if ($unreadCount > 0)
                <button
                    type="button"
                    wire:click="markAllAsRead"
                    class="text-xs font-medium text-accent hover:underline focus:outline-none"
                >
                    {{ __('notifications.mark_all_as_read') }}
                </button>
            @endif
        </div>

        {{-- Notifications List --}}
        <div class="max-h-96 overflow-y-auto divide-y divide-zinc-100 dark:divide-zinc-850">
            @forelse ($recentNotifications as $notification)
                @php
                    $isUnread = $notification->read_at === null;
                    $type = $notification->data['type'] ?? 'info';
                    $title = $notification->data['title'] ?? '';
                    $body = $notification->data['body'] ?? '';
                    $actionUrl = $notification->data['action_url'] ?? null;
                    $typeColors = match($type) {
                        'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400',
                        'warning' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400',
                        'error' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400',
                        'security' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400',
                        'system' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400',
                        default => 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400',
                    };
                @endphp
                <div class="relative p-3.5 flex gap-3 transition hover:bg-zinc-50 dark:hover:bg-zinc-800/60 {{ $isUnread ? 'bg-zinc-50/50 dark:bg-zinc-800/30' : '' }}">
                    <div class="shrink-0">
                        <span class="inline-flex size-9 items-center justify-center rounded-xl {{ $typeColors }}">
                            @if ($type === 'security')
                                <flux:icon.shield-check class="size-4" />
                            @elseif ($type === 'success')
                                <flux:icon.check-circle class="size-4" />
                            @elseif ($type === 'warning')
                                <flux:icon.exclamation-triangle class="size-4" />
                            @elseif ($type === 'error')
                                <flux:icon.x-circle class="size-4" />
                            @elseif ($type === 'system')
                                <flux:icon.cog class="size-4" />
                            @else
                                <flux:icon.bell class="size-4" />
                            @endif
                        </span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-1">
                            <p class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 truncate">
                                {{ $title }}
                            </p>
                            @if ($isUnread)
                                <span class="size-2 rounded-full bg-accent shrink-0 mt-1"></span>
                            @endif
                        </div>
                        <p class="text-xs text-zinc-600 dark:text-zinc-400 line-clamp-2 mt-0.5">
                            {{ $body }}
                        </p>
                        <div class="flex items-center justify-between gap-2 mt-2">
                            <span class="text-[10px] text-zinc-400 dark:text-zinc-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>

                            <div class="flex items-center gap-2">
                                @if ($actionUrl)
                                    <a
                                        href="{{ $actionUrl }}"
                                        wire:navigate
                                        @click="open = false"
                                        class="text-[11px] font-medium text-accent hover:underline"
                                    >
                                        {{ $notification->data['action_text'] ?? __('notifications.view_details') }}
                                    </a>
                                @endif

                                @if ($isUnread)
                                    <button
                                        type="button"
                                        wire:click="markAsRead('{{ $notification->id }}')"
                                        class="text-[11px] text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200"
                                        title="{{ __('notifications.mark_as_read') }}"
                                    >
                                        ✓
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center px-4">
                    <flux:icon.bell class="size-8 mx-auto text-zinc-300 dark:text-zinc-600 mb-2" />
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        {{ __('notifications.no_notifications') }}
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="border-t border-zinc-100 dark:border-zinc-800 p-2.5 text-center bg-zinc-50/60 dark:bg-zinc-800/40">
            <a
                href="{{ route('notifications.index') }}"
                wire:navigate
                @click="open = false"
                class="block w-full py-1 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white"
            >
                {{ __('notifications.view_all') }} &rarr;
            </a>
        </div>
    </div>
</div>
