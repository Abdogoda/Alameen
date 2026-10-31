<x-admin-layout::admin :title="__('الملف الشخصي وإعدادات الأمان')">

    <div class="space-y-6 sm:space-y-8 max-w-5xl mx-auto" x-data="{ activeTab: 'general' }">

        <!-- Status Alerts -->
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2.5 font-bold">
                    <svg class="size-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-sm shadow-xs">
                <div class="flex items-center gap-2 font-bold mb-1.5">
                    <svg class="size-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>{{ __('يرجى تصحيح الأخطاء التالية:') }}</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-600 dark:text-rose-400 font-semibold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 1. Executive Summary Profile Banner -->
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 relative overflow-hidden">
            <div class="absolute -top-24 -end-24 size-64 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex items-center gap-5 z-10 min-w-0">
                <div class="size-20 sm:size-24 rounded-2xl bg-gradient-to-br from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-black text-2xl sm:text-3xl shadow-lg shadow-[#012383]/15 shrink-0">
                    {{ $admin->initials() }}
                </div>
                <div class="min-w-0 space-y-1">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-black text-[#012383] dark:text-white font-['Cairo'] tracking-tight truncate">
                            {{ $admin->name }}
                        </h2>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#EAF1FB] dark:bg-slate-800 text-[#0B2A8F] dark:text-blue-300 border border-blue-200/60 dark:border-slate-700">
                            <span class="size-2 rounded-full bg-emerald-500"></span>
                            {{ $admin->role_name }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-mono truncate">
                        {{ $admin->email }}
                    </p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 pt-0.5">
                        {{ __('عضو فريق الإدارة منذ :date', ['date' => $admin->created_at?->translatedFormat('d F Y') ?? '—']) }}
                    </p>
                </div>
            </div>

            <!-- Profile Quick Stats -->
            <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto border-t sm:border-t-0 sm:border-s border-[#DEE8F7] dark:border-slate-800 pt-4 sm:pt-0 sm:ps-6 z-10 shrink-0">
                <div class="flex-1 sm:flex-initial text-center px-2">
                    <span class="block text-2xl font-black text-[#012383] dark:text-blue-400">{{ count($passkeys) }}</span>
                    <span class="text-[11px] font-bold text-slate-400 uppercase">{{ __('مفاتيح Passkey') }}</span>
                </div>
                <div class="flex-1 sm:flex-initial text-center px-2">
                    <span class="block text-2xl font-black text-emerald-600 dark:text-emerald-400">
                        {{ $admin->isSuperAdmin() ? '∞' : count($admin->getAllPermissions()) }}
                    </span>
                    <span class="text-[11px] font-bold text-slate-400 uppercase">{{ __('الصلاحيات') }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Navigation Tabs -->
        <div class="flex items-center gap-2 border-b border-[#DEE8F7] dark:border-slate-800 overflow-x-auto pb-px">
            <button
                type="button"
                @click="activeTab = 'general'"
                :class="activeTab === 'general' ? 'border-[#012383] text-[#012383] dark:border-blue-400 dark:text-blue-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                class="px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors flex items-center gap-2 cursor-pointer"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>{{ __('البيانات الأساسية') }}</span>
            </button>

            <button
                type="button"
                @click="activeTab = 'password'"
                :class="activeTab === 'password' ? 'border-[#012383] text-[#012383] dark:border-blue-400 dark:text-blue-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                class="px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors flex items-center gap-2 cursor-pointer"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>{{ __('كلمة المرور') }}</span>
            </button>

            <button
                type="button"
                @click="activeTab = 'passkeys'"
                :class="activeTab === 'passkeys' ? 'border-[#012383] text-[#012383] dark:border-blue-400 dark:text-blue-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                class="px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors flex items-center gap-2 cursor-pointer"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11a7.96 7.96 0 001.272 4.316m5.748 4.288A19.92 19.92 0 0112 21"/></svg>
                <span>{{ __('مفاتيح المرور (Passkeys)') }}</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-blue-100 text-[#012383] dark:bg-blue-900/40 dark:text-blue-300 font-bold">
                    {{ count($passkeys) }}
                </span>
            </button>

            <button
                type="button"
                @click="activeTab = 'permissions'"
                :class="activeTab === 'permissions' ? 'border-[#012383] text-[#012383] dark:border-blue-400 dark:text-blue-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                class="px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors flex items-center gap-2 cursor-pointer"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>{{ __('الصلاحيات والأدوار') }}</span>
            </button>
        </div>

        <!-- 3. Tab Contents -->

        <!-- TAB 1: General Info -->
        <div x-show="activeTab === 'general'" x-cloak class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-[#012383] dark:text-white">{{ __('تعديل البيانات الأساسية') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('حدّث اسمك وعنوان بريدك الإلكتروني المستخدم في لوحة الإدارة.') }}</p>
            </div>

            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5 max-w-xl">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                        {{ __('الاسم الكامل') }}
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $admin->name) }}"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-[#FAFCFF] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F]"
                    />
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                        {{ __('البريد الإلكتروني') }}
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $admin->email) }}"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-[#FAFCFF] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F]"
                    />
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-sm shadow-xs transition-colors flex items-center gap-2 cursor-pointer"
                    >
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ __('حفظ التعديلات') }}</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 2: Password Update -->
        <div x-show="activeTab === 'password'" x-cloak class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-[#012383] dark:text-white">{{ __('تغيير كلمة المرور') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('احرص على استخدام كلمة مرور قوية مكونة من أحرف وأرقام ورموز خاصة لحماية حسابك الإداري.') }}</p>
            </div>

            <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-5 max-w-xl">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                        {{ __('كلمة المرور الحالية') }}
                    </label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-[#FAFCFF] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F]"
                    />
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                        {{ __('كلمة المرور الجديدة') }}
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-[#FAFCFF] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F]"
                    />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                        {{ __('تأكيد كلمة المرور الجديدة') }}
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-[#FAFCFF] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F]"
                    />
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-sm shadow-xs transition-colors flex items-center gap-2 cursor-pointer"
                    >
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>{{ __('تحديث كلمة المرور') }}</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 3: Passkeys Management -->
        <div
            x-show="activeTab === 'passkeys'"
            x-cloak
            x-data="{
                registering: false,
                passkeyName: '',
                showModal: false,
                errorMessage: null,
                successMessage: null,
                async addPasskey() {
                    if (!this.passkeyName.trim()) {
                        this.errorMessage = '{{ __('يرجى كتابة اسم تعريفي لمفتاح المرور أو الجهاز.') }}';
                        return;
                    }
                    this.registering = true;
                    this.errorMessage = null;
                    try {
                        await window.Passkeys.register({
                            name: this.passkeyName.trim(),
                            routes: {
                                options: '{{ route('admin.passkey.registration-options') }}',
                                submit: '{{ route('admin.passkey.store') }}',
                            },
                        });
                        this.showModal = false;
                        this.passkeyName = '';
                        window.location.reload();
                    } catch (e) {
                        if (window.translatePasskeyError) {
                            this.errorMessage = window.translatePasskeyError(e);
                        } else {
                            this.errorMessage = e?.message || '{{ __('فشل تسجيل مفتاح المرور.') }}';
                        }
                    } finally {
                        this.registering = false;
                    }
                },
                deleteKey(id) {
                    window.AlameenConfirm({
                        title: '{{ __('حذف مفتاح المرور') }}',
                        message: '{{ __('هل أنت متأكد من رغبتك في حذف مفتاح المرور هذا نهائياً؟') }}',
                        confirmText: '{{ __('نعم، احذف المفتاح') }}',
                        onConfirm: async () => {
                            try {
                                const res = await fetch(`/admin/passkeys/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                        'Accept': 'application/json',
                                    },
                                });
                                if (res.ok) {
                                    window.location.reload();
                                } else {
                                    alert('{{ __('تعذر حذف مفتاح المرور.') }}');
                                }
                            } catch (e) {
                                alert('{{ __('حدث خطأ أثناء محاولة الحذف.') }}');
                            }
                        }
                    });
                }
            }"
            class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs"
        >
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-[#DEE8F7] dark:border-slate-800">
                <div>
                    <h3 class="text-lg font-bold text-[#012383] dark:text-white flex items-center gap-2">
                        <svg class="size-5 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11a7.96 7.96 0 001.272 4.316m5.748 4.288A19.92 19.92 0 0112 21"/>
                        </svg>
                        <span>{{ __('مفاتيح المرور البيومترية (Passkeys)') }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        {{ __('تتيح لك مفاتيح المرور تسجيل الدخول إلى لوحة التحكم فورياً باستخدام بصمة الإصبع أو الوجه (Touch ID / Face ID / Windows Hello) أو مفاتيح الأمان الفيزيائية.') }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="showModal = true; errorMessage = null;"
                    class="h-10 px-4 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2 shrink-0 self-start sm:self-center cursor-pointer"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>{{ __('تسجيل مفتاح مرور جديد') }}</span>
                </button>
            </div>

            <!-- List of Registered Passkeys -->
            @if (count($passkeys) > 0)
                <div class="divide-y divide-[#DEE8F7] dark:divide-slate-800">
                    @foreach ($passkeys as $pk)
                        <div class="py-4 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="size-10 rounded-xl bg-[#EAF1FB] dark:bg-slate-800 text-[#012383] dark:text-blue-300 flex items-center justify-center shrink-0">
                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-sm text-slate-800 dark:text-slate-100 truncate">
                                            {{ $pk['name'] }}
                                        </h4>
                                        @if (!empty($pk['authenticator']))
                                            <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-semibold">
                                                {{ $pk['authenticator'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-slate-400 mt-0.5">
                                        <span>{{ __('أُضيف :time', ['time' => $pk['created_at_human']]) }}</span>
                                        @if (!empty($pk['last_used_at_human']))
                                            <span>•</span>
                                            <span class="text-emerald-600 dark:text-emerald-400">{{ __('آخر استخدام :time', ['time' => $pk['last_used_at_human']]) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <button
                                type="button"
                                @click="deleteKey({{ $pk['id'] }})"
                                class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer shrink-0"
                                title="{{ __('حذف مفتاح المرور') }}"
                            >
                                <svg class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center border-2 border-dashed border-[#DEE8F7] dark:border-slate-800 rounded-2xl">
                    <div class="size-14 rounded-2xl bg-blue-50 dark:bg-slate-800 text-[#012383] dark:text-blue-300 mx-auto flex items-center justify-center mb-3">
                        <svg class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11a7.96 7.96 0 001.272 4.316m5.748 4.288A19.92 19.92 0 0112 21"/></svg>
                    </div>
                    <h4 class="font-bold text-sm text-slate-700 dark:text-slate-200">{{ __('لم يتم تسجيل أي مفتاح مرور بعد') }}</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                        {{ __('أضف بصمة جهازك أو Windows Hello لتسجيل الدخول بنقرة واحدة وبأعلى معايير الأمان دون الحاجة لكلمة مرور.') }}
                    </p>
                </div>
            @endif

            <!-- Add Passkey Modal -->
            <div
                x-show="showModal"
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
                @keydown.escape.window="if (!registering) showModal = false"
            >
                <div
                    @click.away="if (!registering) showModal = false"
                    class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-2xl relative"
                >
                    <div class="flex items-center justify-between pb-4 border-b border-[#DEE8F7] dark:border-slate-800 mb-4">
                        <h4 class="font-bold text-base text-[#012383] dark:text-white flex items-center gap-2">
                            <svg class="size-5 text-[#0B2A8F] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11a7.96 7.96 0 001.272 4.316m5.748 4.288A19.92 19.92 0 0112 21"/></svg>
                            <span>{{ __('تسجيل مفتاح مرور جديد') }}</span>
                        </h4>
                        <button
                            type="button"
                            @click="showModal = false"
                            :disabled="registering"
                            class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer"
                        >
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                                {{ __('اسم الجهاز أو المفتاح') }}
                            </label>
                            <input
                                type="text"
                                x-model="passkeyName"
                                placeholder="{{ __('مثال: كمبيوتر العمل، MacBook Pro، هاتف iPhone') }}"
                                class="w-full px-4 py-3 rounded-xl bg-[#FAFCFF] dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#0B2A8F]/10 focus:border-[#0B2A8F]"
                            />
                        </div>

                        <div x-show="errorMessage" x-cloak class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 text-xs font-semibold" x-text="errorMessage"></div>

                        <div class="p-3.5 rounded-xl bg-blue-50/60 dark:bg-slate-800/40 border border-blue-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            {{ __('عند الضغط على "تسجيل الآن"، سيطلب متصفحك التحقق البيومتري (بصمة الإصبع أو الوجه أو مفتاح الأمان).') }}
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-2">
                            <button
                                type="button"
                                @click="showModal = false"
                                :disabled="registering"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer"
                            >
                                {{ __('إلغاء') }}
                            </button>
                            <button
                                type="button"
                                @click="addPasskey()"
                                :disabled="registering"
                                class="px-5 py-2.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2 cursor-pointer disabled:opacity-75"
                            >
                                <svg x-show="registering" x-cloak class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3.5" fill="none"></circle>
                                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-show="!registering">{{ __('تسجيل المفتاح الآن') }}</span>
                                <span x-show="registering" x-cloak>{{ __('جارٍ تسجيل المفتاح...') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 4: Permissions Overview -->
        <div x-show="activeTab === 'permissions'" x-cloak class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div>
                <h3 class="text-lg font-bold text-[#012383] dark:text-white">{{ __('الصلاحيات والأدوار الممنوحة') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('استعراض الصلاحيات الوظيفية المفعلة لحسابك في منصة الأمين.') }}</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-between gap-4">
                <div>
                    <span class="text-xs text-slate-400 font-bold block">{{ __('الدور النشط') }}</span>
                    <h4 class="font-bold text-base text-[#012383] dark:text-blue-300 mt-0.5">{{ $admin->role_name }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $admin->role?->description ?? __('لا يوجد وصف متاح') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/40 text-[#012383] dark:text-blue-300">
                    {{ $admin->isSuperAdmin() ? __('صلاحيات المالك الشاملة') : __('دور مخصص') }}
                </span>
            </div>

            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('قائمة الصلاحيات النشطة') }}</h4>
                @if ($admin->isSuperAdmin())
                    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200/60 dark:border-emerald-800/40 text-emerald-800 dark:text-emerald-300 text-xs font-bold flex items-center gap-2">
                        <svg class="size-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>{{ __('تمتلك صلاحيات شاملة وغير مقيدة على كافة أقسام المنصة، المحتوى، إيصالات الدفع، إعدادات النظام، وإدارة المشرفين.') }}</span>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                        @foreach ($admin->getAllPermissions() as $perm)
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs">
                                <span class="size-2 rounded-full bg-[#0B2A8F] dark:bg-blue-400 shrink-0"></span>
                                <span class="font-bold text-slate-700 dark:text-slate-300 truncate">{{ $perm }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

</x-admin-layout::admin>
