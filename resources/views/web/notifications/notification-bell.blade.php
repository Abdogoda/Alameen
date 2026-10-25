<div class="relative" x-data="{ open: @entangle('open') }" @click.outside="open = false">
    {{-- Bell Button --}}
    <button
        type="button"
        @click="open = !open"
        class="size-10 rounded-xl border border-[#DEE8F7] bg-white hover:bg-[#EAF1FB] flex items-center justify-center text-slate-600 transition-colors cursor-pointer relative"
        aria-label="{{ __('notifications.notifications') }}"
    >
        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>

        @if ($unreadCount > 0)
            <span class="absolute top-1.5 end-1.5 size-2 bg-amber-500 rounded-full animate-ping"></span>
            <span class="absolute top-1.5 end-1.5 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-[#012383] px-1 text-[9px] font-bold text-white shadow-sm">
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
        class="absolute end-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white border border-[#DEE8F7] shadow-xl z-50 overflow-hidden"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-[#DEE8F7] bg-[#FAFCFF]">
            <div class="flex items-center gap-2">
                <span class="font-bold text-sm text-[#012383]">
                    {{ __('notifications.notifications') }}
                </span>
                @if ($unreadCount > 0)
                    <span class="rounded-full bg-[#EAF1FB] text-[#0B2A8F] px-2 py-0.5 text-xs font-bold">
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
