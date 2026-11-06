<div 
    x-data="{
        deleteModalOpen: false,
        notificationToDelete: null,
        detailsModalOpen: false,
        activeNotification: null,
        openDetails(data) {
            this.activeNotification = data;
            this.detailsModalOpen = true;
        },
        promptDelete(id) {
            if (typeof window.AlameenConfirm === 'function') {
                window.AlameenConfirm({
                    title: '{{ __('حذف الإشعار') }}',
                    message: '{{ __('notifications.admin.delete_confirm') }}',
                    confirmText: '{{ __('نعم، احذف الإشعار') }}',
                    cancelText: '{{ __('إلغاء الأمر') }}',
                    isDanger: true,
                    onConfirm: () => {
                        $wire.deleteNotification(id);
                    }
                });
                return;
            }
            this.notificationToDelete = id;
            this.deleteModalOpen = true;
        },
        confirmDelete() {
            if (this.notificationToDelete) {
                $wire.deleteNotification(this.notificationToDelete);
            }
            this.deleteModalOpen = false;
            this.notificationToDelete = null;
        }
    }"
    class="max-w-7xl mx-auto py-3 sm:py-6 px-3 sm:px-6 lg:px-8 space-y-6 sm:space-y-8 min-w-0"
>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 sm:pb-6 border-b border-[#DEE8F7] dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center justify-center size-9 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400 font-bold shrink-0">
                    <flux:icon.bell class="size-5" />
                </span>
                <h1 class="text-xl sm:text-2xl font-bold font-['Baloo_Bhaijaan_2'] text-[#012383] dark:text-blue-400">
                    {{ __('notifications.admin.title') }}
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                {{ __('notifications.admin.subtitle') }}
            </p>
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <a
                href="{{ route('notifications.index') }}"
                wire:navigate
                class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-4 py-2 text-xs font-bold rounded-xl bg-[#EAF1FB] dark:bg-slate-800 hover:bg-[#DEE8F7] dark:hover:bg-slate-700 text-[#0B2A8F] dark:text-blue-400 transition shadow-xs cursor-pointer text-center"
            >
                <flux:icon.inbox class="size-4" />
                {{ __('notifications.notification_center') }}
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="p-3.5 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xs hover:shadow-card transition-all">
            <span class="text-[11px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider block truncate">
                {{ __('notifications.admin.stats.total_sent') }}
            </span>
            <div class="mt-2 flex items-baseline justify-between gap-1">
                <span class="text-xl sm:text-2xl font-black text-[#012383] dark:text-blue-400">
                    {{ number_format($stats['total']) }}
                </span>
                <div class="size-7 sm:size-8 rounded-lg bg-blue-50 dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <flux:icon.paper-airplane class="size-3.5 sm:size-4" />
                </div>
            </div>
        </div>

        <div class="p-3.5 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xs hover:shadow-card transition-all">
            <span class="text-[11px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider block truncate">
                {{ __('notifications.admin.stats.unread') }}
            </span>
            <div class="mt-2 flex items-baseline justify-between gap-1">
                <span class="text-xl sm:text-2xl font-black text-amber-600">
                    {{ number_format($stats['unread']) }}
                </span>
                <div class="size-7 sm:size-8 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 flex items-center justify-center shrink-0">
                    <flux:icon.envelope class="size-3.5 sm:size-4" />
                </div>
            </div>
        </div>

        <div class="p-3.5 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xs hover:shadow-card transition-all">
            <span class="text-[11px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider block truncate">
                {{ __('notifications.admin.stats.read') }}
            </span>
            <div class="mt-2 flex items-baseline justify-between gap-1">
                <span class="text-xl sm:text-2xl font-black text-emerald-600">
                    {{ number_format($stats['read']) }}
                </span>
                <div class="size-7 sm:size-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 flex items-center justify-center shrink-0">
                    <flux:icon.envelope-open class="size-3.5 sm:size-4" />
                </div>
            </div>
        </div>

        <div class="p-3.5 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-xs hover:shadow-card transition-all">
            <span class="text-[11px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider block truncate">
                {{ __('notifications.admin.stats.recipients_reached') }}
            </span>
            <div class="mt-2 flex items-baseline justify-between gap-1">
                <span class="text-xl sm:text-2xl font-black text-[#0B2A8F] dark:text-blue-400">
                    {{ number_format($stats['recipients_count']) }}
                </span>
                <div class="size-7 sm:size-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-[#0B2A8F] dark:text-indigo-400 flex items-center justify-center shrink-0">
                    <flux:icon.users class="size-3.5 sm:size-4" />
                </div>
            </div>
        </div>
    </div>

    @if ($feedbackMessage)
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <flux:icon.check-circle class="size-5 text-emerald-600 dark:text-emerald-400 shrink-0" />
                <span>{{ $feedbackMessage }}</span>
            </div>
            <button type="button" wire:click="$set('feedbackMessage', null)" class="text-emerald-600 hover:text-emerald-800 p-1">&times;</button>
        </div>
    @endif

    {{-- Send Notification Panel --}}
    <div class="p-4 sm:p-6 rounded-2xl bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 shadow-sm">
        <h2 class="text-base font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-2 mb-4">
            <flux:icon.paper-airplane class="size-5 text-accent" />
            {{ __('notifications.admin.send_new') }}
        </h2>

        <form wire:submit="sendNotification" class="space-y-5 sm:space-y-6">
            {{-- Target & Recipient --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        {{ __('notifications.admin.recipient') }}
                    </label>
                    <div class="flex flex-wrap gap-4">
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
                    <div class="flex flex-wrap gap-4">
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
                        placeholder="{{ __('مثال: إشعار أعمال صيانة للنظام') }}"
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
                        placeholder="{{ __('تفاصيل الإشعار باللغة العربية...') }}"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent"
                    ></textarea>
                </div>
            </div>

            {{-- Optional Action Link & Text --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                <div class="sm:col-span-2 md:col-span-1">
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
                        placeholder="{{ __('مثال: عرض التفاصيل') }}"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent"
                    />
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end pt-2">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 font-semibold text-sm hover:opacity-90 transition shadow-sm cursor-pointer disabled:cursor-wait"
                >
                    <svg wire:loading class="alameen-btn-spinner size-4 shrink-0 animate-spin text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3.5" fill="none"></circle>
                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove>{{ __('notifications.admin.send_button') }}</span>
                    <span wire:loading>{{ __('notifications.admin.sending') }}</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Notification History Table & Mobile Cards --}}
    <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">
                {{ __('notifications.admin.history') }}
            </h3>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select
                    wire:model.live="filterType"
                    class="w-full sm:w-auto rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-1.5 text-xs text-zinc-900 dark:text-zinc-100 focus:outline-none"
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

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto min-w-0">
            <table class="w-full text-start text-xs min-w-[760px]">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-5 py-3 text-start">{{ __('notifications.admin.offender') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('notifications.admin.recipient') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('notifications.admin.title_label') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('notifications.admin.type') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('الحالة') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('التاريخ') }}</th>
                        <th class="px-5 py-3 text-end">{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 text-zinc-700 dark:text-zinc-300">
                    @forelse ($history as $item)
                        @php
                            $recipient = $item->notifiable;
                            $data = $item->data ?? [];
                            $type = $data['type'] ?? 'info';
                            $title = $data['title'] ?? '-';
                            $body = $data['body'] ?? '';
                            $actionUrl = $data['action_url'] ?? null;
                            $actionText = $data['action_text'] ?? null;

                            $nestedData = (isset($data['data']) && is_array($data['data'])) ? $data['data'] : [];

                            $studentId = $nestedData['student_id'] ?? $data['student_id'] ?? $nestedData['user_id'] ?? $data['user_id'] ?? null;
                            $studentName = $nestedData['student_name'] ?? $data['student_name'] ?? $nestedData['user_name'] ?? $data['user_name'] ?? null;
                            $studentEmail = $nestedData['student_email'] ?? $data['student_email'] ?? $nestedData['user_email'] ?? $data['user_email'] ?? null;
                            $studentPhone = $nestedData['student_phone'] ?? $data['student_phone'] ?? $nestedData['user_phone'] ?? $data['user_phone'] ?? null;
                            $videoTitle = $nestedData['video_title'] ?? $data['video_title'] ?? null;
                            $courseTitle = $nestedData['course_title'] ?? $data['course_title'] ?? null;
                            $ipAddress = $nestedData['ip_address'] ?? $data['ip_address'] ?? null;
                            $userAgent = $nestedData['user_agent'] ?? $data['user_agent'] ?? null;
                            $incidentType = $nestedData['incident_type'] ?? $data['incident_type'] ?? null;
                            $reportedAt = $nestedData['reported_at'] ?? $data['reported_at'] ?? null;

                            // Fallbacks from body string if legacy/unstructured
                            if (! $studentName && ! empty($body)) {
                                if (preg_match('/بواسطة الطالب\s+([^(]+?)(?:\s*\(هاتف:\s*([^)]*)\))?/u', $body, $matches)) {
                                    $studentName = trim($matches[1]);
                                    if (! empty($matches[2]) && ! $studentPhone) {
                                        $studentPhone = trim($matches[2]);
                                    }
                                }
                            }
                            if (! $videoTitle && ! empty($body)) {
                                if (preg_match("/للمحاضرة\s*['\"]([^'\"]+)['\"]/u", $body, $matches)) {
                                    $videoTitle = trim($matches[1]);
                                }
                            }

                            $studentModel = ($studentId && isset($students)) ? $students->get($studentId) : null;
                            if ($studentModel) {
                                $studentName = $studentName ?: $studentModel->name;
                                $studentEmail = $studentEmail ?: $studentModel->email;
                                $studentPhone = $studentPhone ?: $studentModel->phone;
                            }

                            if (! $actionUrl && $studentId) {
                                $actionUrl = route('admin.students.show', $studentId);
                            }

                            $isAdminRecipient = ($item->notifiable_type === \App\Models\Admin::class) || ($recipient instanceof \App\Models\Admin);
                            $hasOffendingUser = ! empty($studentName) || ! empty($studentId);

                            $typeColors = match($type) {
                                'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60',
                                'warning' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60',
                                'error' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60',
                                'security' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60',
                                'system' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400 border border-purple-200 dark:border-purple-800/60',
                                default => 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60',
                            };

                            $modalPayload = [
                                'id' => (string) $item->id,
                                'title' => (string) $title,
                                'body' => (string) $body,
                                'type' => (string) $type,
                                'createdAt' => $item->created_at->format('Y-m-d H:i:s'),
                                'createdDiff' => $item->created_at->diffForHumans(),
                                'readAt' => $item->read_at ? $item->read_at->format('Y-m-d H:i:s') : null,
                                'recipientName' => (string) ($recipient?->name ?? ('#'.$item->notifiable_id)),
                                'recipientEmail' => (string) ($recipient?->email ?? ''),
                                'isAdminRecipient' => (bool) $isAdminRecipient,
                                'hasOffender' => (bool) $hasOffendingUser,
                                'studentId' => $studentId ? (int) $studentId : null,
                                'studentName' => (string) ($studentName ?? ''),
                                'studentEmail' => (string) ($studentEmail ?? ''),
                                'studentPhone' => (string) ($studentPhone ?? ''),
                                'studentStatus' => (string) ($studentModel?->status ?? ($hasOffendingUser ? 'suspended' : 'active')),
                                'actionUrl' => (string) ($actionUrl ?? ''),
                                'actionText' => (string) ($actionText ?? __('فحص ملف الطالب وبيانات الانتهاك')),
                                'videoTitle' => (string) ($videoTitle ?? ''),
                                'courseTitle' => (string) ($courseTitle ?? ''),
                                'ipAddress' => (string) ($ipAddress ?? ''),
                                'userAgent' => (string) ($userAgent ?? ''),
                                'incidentType' => (string) ($incidentType ?? 'screen_recording_attempt'),
                                'reportedAt' => (string) ($reportedAt ?? ''),
                            ];
                        @endphp
                        <tr class="hover:bg-zinc-50/70 dark:hover:bg-zinc-800/70 transition-colors">
                            {{-- Offending / Involved User --}}
                            <td class="px-5 py-3.5 max-w-xs">
                                @if ($hasOffendingUser)
                                    <div class="flex items-start gap-2.5">
                                        <div class="size-8 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 font-bold text-xs shadow-2xs">
                                            🚨
                                        </div>
                                        <div class="min-w-0">
                                            @if ($actionUrl)
                                                <a href="{{ $actionUrl }}" class="font-bold text-zinc-900 dark:text-zinc-100 hover:text-[#012383] dark:hover:text-blue-400 transition inline-flex items-center gap-1 group">
                                                    <span class="truncate">{{ $studentName ?: __('مستخدم غير محدد') }}</span>
                                                    <flux:icon.arrow-top-right-on-square class="size-3 text-slate-400 group-hover:text-[#012383] shrink-0" />
                                                </a>
                                            @else
                                                <span class="font-bold text-zinc-900 dark:text-zinc-100 truncate block">{{ $studentName }}</span>
                                            @endif

                                            <div class="flex flex-col gap-0.5 text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">
                                                @if ($studentPhone)
                                                    <span class="inline-flex items-center gap-1 font-mono text-zinc-700 dark:text-zinc-300" dir="ltr">
                                                        <span>📞</span> {{ $studentPhone }}
                                                    </span>
                                                @endif
                                                @if ($studentEmail)
                                                    <span class="truncate text-zinc-400">
                                                        {{ $studentEmail }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="mt-1 flex flex-wrap items-center gap-1">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300">
                                                    {{ __('notifications.admin.violator') }}
                                                </span>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-50 text-red-700 dark:bg-red-950/50 dark:text-red-400 border border-red-200/50 dark:border-red-900/40">
                                                    {{ __('notifications.admin.account_suspended') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($recipient && ! $isAdminRecipient)
                                    <div class="min-w-0">
                                        <div class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $recipient->name }}</div>
                                        <div class="text-[11px] text-zinc-400">{{ $recipient->email }}</div>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 mt-1">
                                            {{ __('notifications.admin.recipient_user_badge') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-zinc-400 italic text-[11px]">{{ __('notifications.admin.no_offender') }}</span>
                                @endif
                            </td>

                            {{-- Notification Recipient --}}
                            <td class="px-5 py-3.5 max-w-[200px]">
                                @if ($recipient)
                                    <div class="min-w-0">
                                        <div class="font-semibold text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
                                            <span class="truncate">{{ $recipient->name }}</span>
                                            @if ($isAdminRecipient)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 shrink-0">
                                                    {{ __('مدير') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-zinc-400 truncate">{{ $recipient->email }}</div>
                                        @if ($isAdminRecipient)
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-0.5">
                                                {{ __('notifications.admin.recipient_admin_badge') }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-zinc-400">#{{ $item->notifiable_id }}</span>
                                @endif
                            </td>

                            {{-- Title & Body & Incident Metadata --}}
                            <td class="px-5 py-3.5 max-w-sm">
                                <div class="space-y-1">
                                    <div class="font-bold text-zinc-900 dark:text-zinc-100 text-xs">
                                        {{ $title }}
                                    </div>
                                    @if (! empty($body))
                                        <div class="text-[11px] text-zinc-600 dark:text-zinc-400 line-clamp-2 leading-relaxed">
                                            {{ $body }}
                                        </div>
                                    @endif

                                    @if ($videoTitle || $courseTitle || $ipAddress)
                                        <div class="flex flex-wrap items-center gap-1.5 pt-1 text-[10px]">
                                            @if ($videoTitle)
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                                                    <span>🎬</span> {{ $videoTitle }}
                                                </span>
                                            @endif
                                            @if ($courseTitle)
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-medium">
                                                    <span>📚</span> {{ $courseTitle }}
                                                </span>
                                            @endif
                                            @if ($ipAddress)
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500 font-mono" dir="ltr">
                                                    IP: {{ $ipAddress }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    @if ($actionUrl)
                                        <div class="pt-0.5">
                                            <a href="{{ $actionUrl }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-[#012383] dark:text-blue-400 hover:underline">
                                                <span>{{ $actionText ?? __('notifications.admin.open_student_profile') }}</span>
                                                <span dir="ltr">&rarr;</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Type --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $typeColors }}">
                                    {{ __('notifications.types.' . $type) }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if ($item->read_at)
                                    <span class="inline-flex items-center gap-1 text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">
                                        ✓ {{ __('notifications.admin.stats.read') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] text-amber-600 dark:text-amber-400 font-medium">
                                        &bull; {{ __('notifications.admin.stats.unread') }}
                                    </span>
                                @endif
                            </td>

                            {{-- Date --}}
                            <td class="px-5 py-3.5 text-zinc-400 whitespace-nowrap">
                                <span title="{{ $item->created_at->format('Y-m-d H:i:s') }}">
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3.5 text-end whitespace-nowrap">
                                <div class="inline-flex items-center gap-1 justify-end">
                                    <button
                                        type="button"
                                        @click="openDetails({{ Js::from($modalPayload) }})"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-[#012383] hover:bg-[#EAF1FB] dark:hover:bg-slate-800 transition cursor-pointer"
                                        title="{{ __('notifications.admin.view_incident_details') }}"
                                    >
                                        <flux:icon.eye class="size-4" />
                                    </button>

                                    @if ($actionUrl)
                                        <a
                                            href="{{ $actionUrl }}"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition cursor-pointer inline-flex items-center justify-center"
                                            title="{{ __('notifications.admin.open_student_profile') }}"
                                        >
                                            <flux:icon.arrow-top-right-on-square class="size-4" />
                                        </a>
                                    @endif

                                    <button
                                        type="button"
                                        @click="promptDelete('{{ $item->id }}')"
                                        class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer"
                                        title="{{ __('حذف') }}"
                                    >
                                        <flux:icon.trash class="size-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-zinc-400">
                                {{ __('notifications.no_notifications') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards View -->
        <div class="block md:hidden divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse ($history as $item)
                @php
                    $recipient = $item->notifiable;
                    $data = $item->data ?? [];
                    $type = $data['type'] ?? 'info';
                    $title = $data['title'] ?? '-';
                    $body = $data['body'] ?? '';
                    $actionUrl = $data['action_url'] ?? null;
                    $actionText = $data['action_text'] ?? null;

                    $nestedData = (isset($data['data']) && is_array($data['data'])) ? $data['data'] : [];

                    $studentId = $nestedData['student_id'] ?? $data['student_id'] ?? $nestedData['user_id'] ?? $data['user_id'] ?? null;
                    $studentName = $nestedData['student_name'] ?? $data['student_name'] ?? $nestedData['user_name'] ?? $data['user_name'] ?? null;
                    $studentEmail = $nestedData['student_email'] ?? $data['student_email'] ?? $nestedData['user_email'] ?? $data['user_email'] ?? null;
                    $studentPhone = $nestedData['student_phone'] ?? $data['student_phone'] ?? $nestedData['user_phone'] ?? $data['user_phone'] ?? null;
                    $videoTitle = $nestedData['video_title'] ?? $data['video_title'] ?? null;
                    $courseTitle = $nestedData['course_title'] ?? $data['course_title'] ?? null;
                    $ipAddress = $nestedData['ip_address'] ?? $data['ip_address'] ?? null;
                    $userAgent = $nestedData['user_agent'] ?? $data['user_agent'] ?? null;
                    $incidentType = $nestedData['incident_type'] ?? $data['incident_type'] ?? null;
                    $reportedAt = $nestedData['reported_at'] ?? $data['reported_at'] ?? null;

                    if (! $studentName && ! empty($body)) {
                        if (preg_match('/بواسطة الطالب\s+([^(]+?)(?:\s*\(هاتف:\s*([^)]*)\))?/u', $body, $matches)) {
                            $studentName = trim($matches[1]);
                            if (! empty($matches[2]) && ! $studentPhone) {
                                $studentPhone = trim($matches[2]);
                            }
                        }
                    }
                    if (! $videoTitle && ! empty($body)) {
                        if (preg_match("/للمحاضرة\s*['\"]([^'\"]+)['\"]/u", $body, $matches)) {
                            $videoTitle = trim($matches[1]);
                        }
                    }

                    $studentModel = ($studentId && isset($students)) ? $students->get($studentId) : null;
                    if ($studentModel) {
                        $studentName = $studentName ?: $studentModel->name;
                        $studentEmail = $studentEmail ?: $studentModel->email;
                        $studentPhone = $studentPhone ?: $studentModel->phone;
                    }

                    if (! $actionUrl && $studentId) {
                        $actionUrl = route('admin.students.show', $studentId);
                    }

                    $isAdminRecipient = ($item->notifiable_type === \App\Models\Admin::class) || ($recipient instanceof \App\Models\Admin);
                    $hasOffendingUser = ! empty($studentName) || ! empty($studentId);

                    $typeColors = match($type) {
                        'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60',
                        'warning' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60',
                        'error' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60',
                        'security' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60',
                        'system' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400 border border-purple-200 dark:border-purple-800/60',
                        default => 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60',
                    };

                    $modalPayload = [
                        'id' => (string) $item->id,
                        'title' => (string) $title,
                        'body' => (string) $body,
                        'type' => (string) $type,
                        'createdAt' => $item->created_at->format('Y-m-d H:i:s'),
                        'createdDiff' => $item->created_at->diffForHumans(),
                        'readAt' => $item->read_at ? $item->read_at->format('Y-m-d H:i:s') : null,
                        'recipientName' => (string) ($recipient?->name ?? ('#'.$item->notifiable_id)),
                        'recipientEmail' => (string) ($recipient?->email ?? ''),
                        'isAdminRecipient' => (bool) $isAdminRecipient,
                        'hasOffender' => (bool) $hasOffendingUser,
                        'studentId' => $studentId ? (int) $studentId : null,
                        'studentName' => (string) ($studentName ?? ''),
                        'studentEmail' => (string) ($studentEmail ?? ''),
                        'studentPhone' => (string) ($studentPhone ?? ''),
                        'studentStatus' => (string) ($studentModel?->status ?? ($hasOffendingUser ? 'suspended' : 'active')),
                        'actionUrl' => (string) ($actionUrl ?? ''),
                        'actionText' => (string) ($actionText ?? __('فحص ملف الطالب وبيانات الانتهاك')),
                        'videoTitle' => (string) ($videoTitle ?? ''),
                        'courseTitle' => (string) ($courseTitle ?? ''),
                        'ipAddress' => (string) ($ipAddress ?? ''),
                        'userAgent' => (string) ($userAgent ?? ''),
                        'incidentType' => (string) ($incidentType ?? 'screen_recording_attempt'),
                        'reportedAt' => (string) ($reportedAt ?? ''),
                    ];
                @endphp
                <div class="p-4 flex flex-col gap-3">
                    {{-- Header Row: Type & Date --}}
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold shrink-0 {{ $typeColors }}">
                            {{ __('notifications.types.' . $type) }}
                        </span>
                        <div class="flex items-center gap-2 text-xs">
                            @if ($item->read_at)
                                <span class="text-[11px] text-emerald-600 dark:text-emerald-400">✓ {{ __('notifications.admin.stats.read') }}</span>
                            @else
                                <span class="text-[11px] text-amber-600 dark:text-amber-400 font-medium">&bull; {{ __('notifications.admin.stats.unread') }}</span>
                            @endif
                            <span class="text-zinc-400 text-[10px]">• {{ $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Offender Box if present --}}
                    @if ($hasOffendingUser)
                        <div class="p-3 rounded-xl bg-rose-50/70 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 flex flex-col gap-1.5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-bold text-rose-800 dark:text-rose-300 flex items-center gap-1">
                                    <span>🚨</span> {{ __('notifications.admin.offender') }}
                                </span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-600 text-white">
                                    {{ __('notifications.admin.violator') }}
                                </span>
                            </div>

                            <div class="text-sm font-bold text-zinc-900 dark:text-zinc-100">
                                @if ($actionUrl)
                                    <a href="{{ $actionUrl }}" class="hover:underline text-[#012383] dark:text-blue-400 inline-flex items-center gap-1">
                                        <span>{{ $studentName }}</span>
                                        <flux:icon.arrow-top-right-on-square class="size-3" />
                                    </a>
                                @else
                                    <span>{{ $studentName }}</span>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-zinc-600 dark:text-zinc-300">
                                @if ($studentPhone)
                                    <span class="font-mono" dir="ltr">📞 {{ $studentPhone }}</span>
                                @endif
                                @if ($studentEmail)
                                    <span class="truncate text-zinc-500">✉️ {{ $studentEmail }}</span>
                                @endif
                            </div>

                            <div class="pt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-800 dark:bg-red-900/60 dark:text-red-300">
                                    {{ __('notifications.admin.account_suspended') }}
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Recipient info --}}
                    <div class="text-xs text-zinc-500 dark:text-zinc-400 flex items-center justify-between">
                        <span>
                            <strong class="text-zinc-700 dark:text-zinc-300">{{ __('notifications.admin.recipient') }}:</strong>
                            {{ $recipient?->name ?? ('#'.$item->notifiable_id) }}
                            @if ($isAdminRecipient)
                                <span class="text-[10px] text-slate-400">({{ __('مدير') }})</span>
                            @endif
                        </span>
                        @if ($recipient?->email)
                            <span class="text-[11px] truncate max-w-[150px]">{{ $recipient->email }}</span>
                        @endif
                    </div>

                    {{-- Title & Body --}}
                    <div>
                        <div class="text-xs font-bold text-zinc-800 dark:text-zinc-200">
                            {{ $title }}
                        </div>
                        @if (! empty($body))
                            <p class="text-[11px] text-zinc-600 dark:text-zinc-400 mt-1 leading-relaxed">
                                {{ $body }}
                            </p>
                        @endif
                    </div>

                    {{-- Badges --}}
                    @if ($videoTitle || $courseTitle || $ipAddress)
                        <div class="flex flex-wrap gap-1.5 text-[10px]">
                            @if ($videoTitle)
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    🎬 {{ $videoTitle }}
                                </span>
                            @endif
                            @if ($courseTitle)
                                <span class="px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300">
                                    📚 {{ $courseTitle }}
                                </span>
                            @endif
                            @if ($ipAddress)
                                <span class="px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500 font-mono" dir="ltr">
                                    IP: {{ $ipAddress }}
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Footer actions --}}
                    <div class="flex items-center justify-between text-xs pt-2 border-t border-zinc-100 dark:border-zinc-800">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="openDetails({{ Js::from($modalPayload) }})"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-400 font-bold text-[11px] hover:bg-[#DEE8F7] transition cursor-pointer"
                            >
                                <flux:icon.eye class="size-3.5" />
                                <span>{{ __('notifications.admin.view_incident_details') }}</span>
                            </button>

                            @if ($actionUrl)
                                <a
                                    href="{{ $actionUrl }}"
                                    class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 hover:underline"
                                >
                                    <span>{{ __('notifications.admin.student_profile') }}</span>
                                    <flux:icon.arrow-top-right-on-square class="size-3" />
                                </a>
                            @endif
                        </div>

                        <button
                            type="button"
                            @click="promptDelete('{{ $item->id }}')"
                            class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer"
                            title="{{ __('حذف') }}"
                        >
                            <flux:icon.trash class="size-4" />
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-zinc-400 text-xs">
                    {{ __('notifications.no_notifications') }}
                </div>
            @endforelse
        </div>

        @if ($history->hasPages())
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                {{ $history->links() }}
            </div>
        @endif
    </div>

    {{-- Fallback Confirmation Modal (When standalone or outside admin.js context) --}}
    <div 
        x-show="deleteModalOpen" 
        x-cloak
        class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-xs select-none"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="deleteModalOpen = false"
        @click.self="deleteModalOpen = false"
    >
        <div 
            class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 sm:p-7 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 text-center"
            @click.stop
        >
            <div class="mx-auto size-14 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-600 dark:text-rose-400 flex items-center justify-center shadow-xs">
                <svg class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <div class="flex flex-col gap-2">
                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-slate-900 dark:text-white leading-tight">
                    {{ __('حذف الإشعار') }}
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                    {{ __('notifications.admin.delete_confirm') }}
                </p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button 
                    type="button" 
                    @click="confirmDelete()" 
                    class="flex-1 h-11 px-5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer"
                >
                    {{ __('نعم، احذف الإشعار') }}
                </button>
                <button 
                    type="button" 
                    @click="deleteModalOpen = false" 
                    class="flex-1 h-11 px-5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition-all flex items-center justify-center cursor-pointer border border-slate-200 dark:border-slate-700"
                >
                    {{ __('إلغاء الأمر') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Full Incident & Notification Details Modal --}}
    <div 
        x-show="detailsModalOpen" 
        x-cloak
        class="fixed inset-0 z-[9998] flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-xs select-none"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="detailsModalOpen = false"
        @click.self="detailsModalOpen = false"
    >
        <div 
            class="bg-white dark:bg-slate-900 rounded-3xl max-w-2xl w-full p-5 sm:p-7 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 text-start max-h-[90vh] overflow-y-auto"
            @click.stop
        >
            <template x-if="activeNotification">
                <div class="space-y-5">
                    {{-- Modal Header --}}
                    <div class="flex items-start justify-between gap-3 pb-4 border-b border-[#DEE8F7] dark:border-slate-800">
                        <div class="flex items-start gap-3">
                            <div class="size-10 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 text-lg shadow-xs">
                                🚨
                            </div>
                            <div>
                                <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-slate-900 dark:text-white leading-tight" x-text="activeNotification.title"></h3>
                                <div class="flex items-center gap-2 mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/60 dark:border-rose-900/40" x-text="activeNotification.type"></span>
                                    <span>&bull;</span>
                                    <span x-text="activeNotification.createdDiff"></span>
                                    <span class="text-[11px] text-slate-400" x-text="'(' + activeNotification.createdAt + ')'"></span>
                                </div>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            @click="detailsModalOpen = false"
                            class="size-8 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 flex items-center justify-center transition cursor-pointer text-lg font-bold"
                        >
                            &times;
                        </button>
                    </div>

                    {{-- Offender User Details Card (The user who did that) --}}
                    <template x-if="activeNotification.hasOffender">
                        <div class="p-4 sm:p-5 rounded-2xl bg-rose-50/70 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/60 space-y-3.5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-rose-900 dark:text-rose-300 flex items-center gap-1.5 uppercase tracking-wider">
                                    <span>👤</span> {{ __('notifications.admin.offender_details') }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-600 text-white shadow-2xs">
                                    {{ __('notifications.admin.violator') }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                                <div class="space-y-1">
                                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 block">{{ __('اسم الطالب:') }}</span>
                                    <div class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                        <span x-text="activeNotification.studentName || '—'"></span>
                                        <template x-if="activeNotification.studentId">
                                            <span class="text-[10px] text-slate-400 font-mono" x-text="'#' + activeNotification.studentId"></span>
                                        </template>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 block">{{ __('حالة الحساب:') }}</span>
                                    <div>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-xs font-bold bg-red-100 text-red-800 dark:bg-red-950/80 dark:text-red-300 border border-red-300/50">
                                            <span>⛔</span> {{ __('notifications.admin.account_suspended') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 block">{{ __('رقم الهاتف:') }}</span>
                                    <div class="text-xs font-mono font-bold text-slate-800 dark:text-slate-200" dir="ltr">
                                        <template x-if="activeNotification.studentPhone">
                                            <a :href="'tel:' + activeNotification.studentPhone" class="hover:underline text-blue-600 dark:text-blue-400 flex items-center gap-1">
                                                <span>📞</span> <span x-text="activeNotification.studentPhone"></span>
                                            </a>
                                        </template>
                                        <template x-if="!activeNotification.studentPhone">
                                            <span class="text-slate-400">—</span>
                                        </template>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 block">{{ __('البريد الإلكتروني:') }}</span>
                                    <div class="text-xs font-medium text-slate-800 dark:text-slate-200 truncate">
                                        <template x-if="activeNotification.studentEmail">
                                            <a :href="'mailto:' + activeNotification.studentEmail" class="hover:underline text-blue-600 dark:text-blue-400 flex items-center gap-1">
                                                <span>✉️</span> <span x-text="activeNotification.studentEmail"></span>
                                            </a>
                                        </template>
                                        <template x-if="!activeNotification.studentEmail">
                                            <span class="text-slate-400">—</span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <template x-if="activeNotification.actionUrl">
                                <div class="pt-2 border-t border-rose-200/60 dark:border-rose-900/40">
                                    <a 
                                        :href="activeNotification.actionUrl"
                                        class="inline-flex items-center justify-center gap-2 w-full py-2 px-4 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs transition shadow-xs cursor-pointer"
                                    >
                                        <span>{{ __('الانتقال لملف الطالب والتحكم في الحساب') }}</span>
                                        <flux:icon.arrow-top-right-on-square class="size-4" />
                                    </a>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Incident Technical Details --}}
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 space-y-3">
                        <span class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wider">
                            <span>📋</span> {{ __('notifications.admin.incident_details') }}
                        </span>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <template x-if="activeNotification.videoTitle">
                                <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                                    <span class="text-[10px] font-semibold text-slate-400 block mb-0.5">{{ __('المحاضرة المستهدفة:') }}</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200" x-text="activeNotification.videoTitle"></span>
                                </div>
                            </template>

                            <template x-if="activeNotification.courseTitle">
                                <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                                    <span class="text-[10px] font-semibold text-slate-400 block mb-0.5">{{ __('الكورس التدريبي:') }}</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200" x-text="activeNotification.courseTitle"></span>
                                </div>
                            </template>

                            <template x-if="activeNotification.ipAddress">
                                <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                                    <span class="text-[10px] font-semibold text-slate-400 block mb-0.5">{{ __('عنوان IP للرصد:') }}</span>
                                    <span class="font-mono text-xs font-bold text-slate-700 dark:text-slate-300" dir="ltr" x-text="activeNotification.ipAddress"></span>
                                </div>
                            </template>

                            <template x-if="activeNotification.reportedAt">
                                <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                                    <span class="text-[10px] font-semibold text-slate-400 block mb-0.5">{{ __('وقت الرصد الأمني:') }}</span>
                                    <span class="font-mono text-xs font-medium text-slate-700 dark:text-slate-300" dir="ltr" x-text="activeNotification.reportedAt"></span>
                                </div>
                            </template>

                            <template x-if="activeNotification.userAgent">
                                <div class="sm:col-span-2 p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                                    <span class="text-[10px] font-semibold text-slate-400 block mb-0.5">{{ __('المتصفح والجهاز:') }}</span>
                                    <span class="font-mono text-[11px] text-slate-600 dark:text-slate-400 break-all" dir="ltr" x-text="activeNotification.userAgent"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Notification Full Body --}}
                    <div class="space-y-1.5">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('نص البلاغ / الإشعار:') }}</span>
                        <div class="p-3.5 rounded-xl bg-slate-100/70 dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-200 leading-relaxed font-medium" x-text="activeNotification.body"></div>
                    </div>

                    {{-- Recipient Context --}}
                    <div class="text-[11px] text-slate-400 flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
                        <span>
                            {{ __('مستلم هذا الإشعار:') }} 
                            <strong class="text-slate-600 dark:text-slate-300" x-text="activeNotification.recipientName"></strong>
                            <span x-text="'(' + activeNotification.recipientEmail + ')'"></span>
                        </span>
                        <template x-if="activeNotification.readAt">
                            <span class="text-emerald-600" x-text="'تمت القراءة: ' + activeNotification.readAt"></span>
                        </template>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-200 dark:border-slate-800">
                        <button 
                            type="button" 
                            @click="detailsModalOpen = false"
                            class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition cursor-pointer"
                        >
                            {{ __('إغلاق') }}
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
