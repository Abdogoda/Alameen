<x-admin-layout::admin
    :title="__('إدارة الفيديوهات والإعلانات التعليمية')"
    :description="__('التحكم في الإعلانات المنبثقة والإلزامية والفيديوهات التوجيهية عبر الموقع، تطبيق الهاتف، ولوحة التحكم')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-6 w-full min-w-0"
>
    @if(auth('admin')->user()?->hasPermission('announcements.manage'))
    <x-slot:actions>
        <a
            href="{{ route('admin.announcements.create') }}"
            class="group relative h-10 sm:h-11 px-4 sm:px-5 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-bold text-xs sm:text-sm shadow-sm hover:shadow-lg hover:shadow-blue-900/30 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer w-full sm:w-auto"
        >
            <svg class="size-4 group-hover:rotate-90 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>{{ __('إضافة إعلان أو فيديو تعليمي جديد') }}</span>
        </a>
    </x-slot:actions>
    @endif

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-bold flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="size-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 cursor-pointer">✕</button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#012383] dark:text-blue-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('إجمالي المحتويات') }}</span>
                <span class="text-xl sm:text-2xl font-black text-[#012383] dark:text-white">{{ $metrics['total'] }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('المفعلة حالياً') }}</span>
                <span class="text-xl sm:text-2xl font-black text-emerald-700 dark:text-emerald-400">{{ $metrics['active'] }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-red-50 dark:bg-red-950/50 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('نوافذ إجبارية (Force)') }}</span>
                <span class="text-xl sm:text-2xl font-black text-red-600 dark:text-red-400">{{ $metrics['forced'] }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('نوافذ منبثقة (Popup)') }}</span>
                <span class="text-xl sm:text-2xl font-black text-purple-700 dark:text-purple-400">{{ $metrics['popups'] }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">{{ __('فيديوهات تعليمية') }}</span>
                <span class="text-xl sm:text-2xl font-black text-amber-700 dark:text-amber-300">{{ $metrics['videos'] }}</span>
            </div>
        </div>
    </div>

    {{-- Filter & Search Form --}}
    <form method="GET" action="{{ route('admin.announcements.index') }}" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl p-4 shadow-xs flex flex-col md:flex-row items-center gap-3">
        <div class="relative flex-1 w-full">
            <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-slate-400">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input
                type="text"
                name="search"
                value="{{ $filters['search'] ?? '' }}"
                placeholder="{{ __('البحث في عناوين ونصوص الإعلانات والفيديوهات...') }}"
                class="w-full h-10 ps-9 pe-4 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#012383]"
            />
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto flex-wrap sm:flex-nowrap">
            {{-- Platform filter --}}
            <select name="platform" class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300">
                <option value="all">{{ __('جميع المنصات') }}</option>
                <option value="web" {{ ($filters['platform'] ?? '') === 'web' ? 'selected' : '' }}>{{ __('الموقع الإلكتروني (Web)') }}</option>
                <option value="mobile" {{ ($filters['platform'] ?? '') === 'mobile' ? 'selected' : '' }}>{{ __('تطبيق الهاتف (Mobile)') }}</option>
                <option value="admin" {{ ($filters['platform'] ?? '') === 'admin' ? 'selected' : '' }}>{{ __('لوحة التحكم (Admin)') }}</option>
            </select>

            {{-- Display mode filter --}}
            <select name="display_mode" class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300">
                <option value="all">{{ __('جميع أنماط العرض') }}</option>
                <option value="popup" {{ ($filters['display_mode'] ?? '') === 'popup' ? 'selected' : '' }}>{{ __('نافذة منبثقة (Popup)') }}</option>
                <option value="force" {{ ($filters['display_mode'] ?? '') === 'force' ? 'selected' : '' }}>{{ __('إجباري مانع (Force)') }}</option>
                <option value="banner" {{ ($filters['display_mode'] ?? '') === 'banner' ? 'selected' : '' }}>{{ __('شريط علوي (Banner)') }}</option>
                <option value="inline" {{ ($filters['display_mode'] ?? '') === 'inline' ? 'selected' : '' }}>{{ __('بطاقة مدمجة (Inline)') }}</option>
            </select>

            {{-- Content Type filter --}}
            <select name="content_type" class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300">
                <option value="all">{{ __('جميع الأنواع') }}</option>
                <option value="video" {{ ($filters['content_type'] ?? '') === 'video' ? 'selected' : '' }}>{{ __('فيديو فقط') }}</option>
                <option value="text" {{ ($filters['content_type'] ?? '') === 'text' ? 'selected' : '' }}>{{ __('نص فقط') }}</option>
                <option value="mixed" {{ ($filters['content_type'] ?? '') === 'mixed' ? 'selected' : '' }}>{{ __('فيديو مع نص (Mixed)') }}</option>
            </select>

            {{-- Status filter --}}
            <select name="status" class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300">
                <option value="">{{ __('جميع الحالات') }}</option>
                <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>{{ __('مفعل') }}</option>
                <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>{{ __('معطل') }}</option>
            </select>

            <button type="submit" class="h-10 px-4 bg-[#012383] text-white rounded-xl text-xs font-bold hover:bg-[#0B2A8F] transition-colors shrink-0">
                {{ __('تصفية') }}
            </button>
            <a href="{{ route('admin.announcements.index') }}" class="h-10 px-3 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-700 flex items-center justify-center shrink-0">
                {{ __('إعادة تعيين') }}
            </a>
        </div>
    </form>

    {{-- Announcements Table --}}
    <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-b border-[#DEE8F7] dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold">
                        <th class="py-3.5 px-4 text-start">{{ __('المحتوى والعنوان') }}</th>
                        <th class="py-3.5 px-3 text-start">{{ __('المنصات المستهدفة') }}</th>
                        <th class="py-3.5 px-3 text-start">{{ __('الجمهور') }}</th>
                        <th class="py-3.5 px-3 text-start">{{ __('طريقة العرض') }}</th>
                        <th class="py-3.5 px-3 text-center">{{ __('التكرار') }}</th>
                        <th class="py-3.5 px-3 text-center">{{ __('الحالة') }}</th>
                        <th class="py-3.5 px-4 text-center">{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#DEE8F7]/60 dark:divide-slate-800/60 font-semibold text-slate-700 dark:text-slate-200">
                    @forelse($announcements as $announcement)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-800/40 transition-colors">
                            {{-- Title & Content Type --}}
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-xl flex items-center justify-center shrink-0 {{ in_array($announcement->content_type, ['video', 'mixed']) ? 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400' : 'bg-blue-100 text-[#012383] dark:bg-blue-950/60 dark:text-blue-400' }}">
                                        @if(in_array($announcement->content_type, ['video', 'mixed']))
                                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.announcements.show', $announcement) }}" class="font-bold text-slate-900 dark:text-white hover:text-[#012383] dark:hover:text-blue-400 block truncate max-w-xs sm:max-w-md">
                                            {{ $announcement->title_ar }}
                                        </a>
                                        @if($announcement->title_en)
                                            <span class="text-xs text-slate-400 block truncate max-w-xs sm:max-w-md">{{ $announcement->title_en }}</span>
                                        @endif
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold {{ in_array($announcement->content_type, ['video', 'mixed']) ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                                                {{ match($announcement->content_type) { 'video' => '🎥 فيديو فقط', 'mixed' => '🎬 فيديو وشرح', default => '📝 إعلان نصي' } }}
                                            </span>
                                            @if($announcement->priority > 0)
                                                <span class="text-[10px] font-bold text-purple-600 bg-purple-50 dark:bg-purple-950/60 px-2 py-0.5 rounded-full">
                                                    ★ أولوية: {{ $announcement->priority }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Target Platforms --}}
                            <td class="py-3.5 px-3">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @php $platforms = $announcement->target_platforms ?? ['web', 'mobile', 'admin']; @endphp
                                    @if(in_array('all', $platforms) || (in_array('web', $platforms) && in_array('mobile', $platforms) && in_array('admin', $platforms)))
                                        <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-100 dark:bg-blue-950/80 text-[#012383] dark:text-blue-300">
                                            {{ __('جميع المنصات') }}
                                        </span>
                                    @else
                                        @if(in_array('web', $platforms))
                                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">🌐 Web</span>
                                        @endif
                                        @if(in_array('mobile', $platforms))
                                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300">📱 Mobile</span>
                                        @endif
                                        @if(in_array('admin', $platforms))
                                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-purple-100 dark:bg-purple-950/80 text-purple-800 dark:text-purple-300">🛡️ Admin</span>
                                        @endif
                                    @endif
                                </div>
                            </td>

                            {{-- Target Audience --}}
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ match($announcement->target_audience) { 'students' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300', 'admins' => 'bg-purple-50 text-purple-700 dark:bg-purple-950/50 dark:text-purple-300', 'guests' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300', default => 'bg-blue-50 text-[#012383] dark:bg-blue-950/50 dark:text-blue-300' } }}">
                                    {{ match($announcement->target_audience) { 'students' => __('الطلاب والمتدربون'), 'admins' => __('طاقم الإدارة'), 'guests' => __('الزوار والضيوف'), default => __('الجميع (عام)') } }}
                                </span>
                            </td>

                            {{-- Display Mode --}}
                            <td class="py-3.5 px-3">
                                @if($announcement->display_mode === 'force')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black bg-red-100 text-red-700 dark:bg-red-950/80 dark:text-red-300 animate-pulse border border-red-200 dark:border-red-800">
                                        <span>🔒</span>
                                        <span>{{ __('إجباري (Force)') }}</span>
                                    </span>
                                @elseif($announcement->display_mode === 'popup')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 dark:bg-purple-950/80 dark:text-purple-300">
                                        <span>🗔</span>
                                        <span>{{ __('منبثق (Popup)') }}</span>
                                    </span>
                                @elseif($announcement->display_mode === 'banner')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-[#012383] dark:bg-blue-950/80 dark:text-blue-300">
                                        <span>📢</span>
                                        <span>{{ __('شريط علوي (Banner)') }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        <span>🪟</span>
                                        <span>{{ __('مدمج (Inline)') }}</span>
                                    </span>
                                @endif
                            </td>

                            {{-- Frequency --}}
                            <td class="py-3.5 px-3 text-center">
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">
                                    {{ match($announcement->frequency) { 'once' => __('مرة واحدة فقط'), 'until_dismissed' => __('حتى الإغلاق'), 'every_session' => __('كل جلسة'), default => __('دائماً') } }}
                                </span>
                            </td>

                            {{-- Active Status Toggle --}}
                            <td class="py-3.5 px-3 text-center">
                                <button
                                    type="button"
                                    onclick="toggleAnnouncementStatus({{ $announcement->id }}, this)"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $announcement->is_active ? 'bg-emerald-600' : 'bg-slate-300 dark:bg-slate-700' }}"
                                    role="switch"
                                    aria-checked="{{ $announcement->is_active ? 'true' : 'false' }}"
                                    title="{{ $announcement->is_active ? __('مفعل - اضغط للإيقاف') : __('معطل - اضغط للتفعيل') }}"
                                >
                                    <span class="sr-only">{{ __('تبديل الحالة') }}</span>
                                    <span class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out {{ $announcement->is_active ? 'translate-x-5 rtl:-translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </td>

                            {{-- Actions --}}
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a
                                        href="{{ route('admin.announcements.show', $announcement) }}"
                                        class="p-2 rounded-xl text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/50 transition-colors"
                                        title="{{ __('عرض الإحصائيات والمعاينة') }}"
                                    >
                                        <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>

                                    @if(auth('admin')->user()?->hasPermission('announcements.manage'))
                                        <a
                                            href="{{ route('admin.announcements.edit', $announcement) }}"
                                            class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                                            title="{{ __('تعديل') }}"
                                        >
                                            <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.announcements.destroy', $announcement) }}"
                                            onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الإعلان نهائياً؟') }}')"
                                            class="inline-block"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="p-2 rounded-xl text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50 transition-colors cursor-pointer"
                                                title="{{ __('حذف') }}"
                                            >
                                                <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="size-10 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    <p class="font-bold text-sm">{{ __('لا توجد إعلانات أو فيديوهات تعليمية مطابقة للشروط.') }}</p>
                                    @if(auth('admin')->user()?->hasPermission('announcements.manage'))
                                        <a href="{{ route('admin.announcements.create') }}" class="text-xs font-bold text-[#012383] dark:text-blue-400 hover:underline mt-1">
                                            + {{ __('إنشاء أول إعلان الآن') }}
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($announcements->hasPages())
            <div class="p-4 border-t border-[#DEE8F7] dark:border-slate-800">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>

    <script>
    async function toggleAnnouncementStatus(id, btn) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/admin/announcements/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token || ''
                }
            });
            const data = await res.json();
            if (data.success) {
                const dot = btn.querySelector('span:last-child');
                if (data.is_active) {
                    btn.classList.remove('bg-slate-300', 'dark:bg-slate-700');
                    btn.classList.add('bg-emerald-600');
                    dot.classList.add('translate-x-5', 'rtl:-translate-x-5');
                    dot.classList.remove('translate-x-0');
                } else {
                    btn.classList.remove('bg-emerald-600');
                    btn.classList.add('bg-slate-300', 'dark:bg-slate-700');
                    dot.classList.remove('translate-x-5', 'rtl:-translate-x-5');
                    dot.classList.add('translate-x-0');
                }
            }
        } catch (err) {
            console.error('Failed to toggle announcement status:', err);
        }
    }
    </script>
</x-admin-layout::admin>
