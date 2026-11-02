@props([
    'active' => 'notifications', // 'notifications' or 'support'
    'unreadNotifications' => null,
    'unreadSupport' => null,
])

@php
    $studentUser = auth()->user();
    $unreadNotificationsCount = $unreadNotifications ?? ($studentUser ? $studentUser->unreadNotifications()->count() : 0);
    $unreadSupportCount = $unreadSupport ?? ($studentUser ? \App\Models\SupportMessage::whereHas('ticket', fn($q) => $q->where('user_id', $studentUser->id))->where('sender_type', \App\Models\Admin::class)->where('type', 'message')->whereNull('read_at')->count() : 0);
@endphp

<!-- ============================================================== -->
<!-- INTERNAL PAGE TABS: صندوق الإشعارات  vs  الدعم والرسائل         -->
<!-- ============================================================== -->
<div class="inline-flex p-1.5 bg-white dark:bg-slate-900 rounded-2xl border border-[#DEE8F7] dark:border-slate-800 gap-1.5 w-full sm:w-auto shadow-xs">
    
    <!-- Tab 1: صندوق الإشعارات -->
    <a
        href="{{ route('notifications.index') }}"
        class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 cursor-pointer select-none {{ $active === 'notifications' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383] dark:hover:text-white hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800/80' }}"
        wire:navigate
    >
        <svg class="size-4.5 shrink-0 {{ $active === 'notifications' ? 'text-white' : 'text-[#0B2A8F] dark:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <span>{{ __('صندوق الإشعارات') }}</span>
        @if($unreadNotificationsCount > 0)
            <span class="px-2 py-0.5 text-[11px] rounded-full font-black leading-none shadow-2xs {{ $active === 'notifications' ? 'bg-white/20 text-white' : 'bg-rose-500 text-white animate-pulse' }}">
                {{ $unreadNotificationsCount }}
            </span>
        @endif
    </a>

    <!-- Tab 2: الدعم والرسائل -->
    <a
        href="{{ route('support.index') }}"
        class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 cursor-pointer select-none {{ $active === 'support' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383] dark:hover:text-white hover:bg-[#EAF1FB]/60 dark:hover:bg-slate-800/80' }}"
        wire:navigate
    >
        <svg class="size-4.5 shrink-0 {{ $active === 'support' ? 'text-white' : 'text-[#0B2A8F] dark:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <span>{{ __('الدعم والرسائل') }}</span>
        @if($unreadSupportCount > 0)
            <span class="px-2 py-0.5 text-[11px] rounded-full font-black leading-none shadow-2xs {{ $active === 'support' ? 'bg-white/20 text-white' : 'bg-[#F2AE2E] text-slate-900 animate-pulse' }}">
                {{ $unreadSupportCount }}
            </span>
        @endif
    </a>

</div>
