@props([
    'user' => null,
])

<!-- Confirmation Modal: Delete Student Account (Requires Admin Password) -->
<div 
    id="modal-delete-student" 
    class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-hidden select-none"
    role="dialog"
    aria-modal="true"
    aria-labelledby="delete-student-modal-title"
>
    <div 
        class="modal-overlay fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity cursor-pointer" 
        onclick="closeDeleteStudentModal()"
    ></div>

    <div 
        class="modal-content relative bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 sm:p-7 border border-rose-200 dark:border-rose-950/80 shadow-2xl z-10 max-h-[92vh] overflow-y-auto text-start"
        onclick="event.stopPropagation()"
    >
        {{-- Header: Warning Badge + Close Button --}}
        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3.5">
                <div class="size-12 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 id="delete-student-modal-title" class="font-['Baloo_Bhaijaan_2'] font-bold text-lg sm:text-xl text-rose-900 dark:text-rose-300 leading-tight">
                        {{ __('حذف حساب الطالب نهائياً') }}
                    </h3>
                    <p class="text-[11px] text-rose-600/80 dark:text-rose-400/80 font-medium mt-0.5">
                        {{ __('إجراء أمني حساس لا يمكن التراجع عنه') }}
                    </p>
                </div>
            </div>

            <button 
                type="button" 
                onclick="closeDeleteStudentModal()" 
                class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                aria-label="{{ __('إغلاق') }}"
            >
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form --}}
        <form 
            id="delete-student-form" 
            method="POST" 
            action="{{ $user ? route('admin.students.destroy', $user) : (old('student_id') ? route('admin.students.destroy', old('student_id')) : '#') }}" 
            class="mt-4 flex flex-col gap-4.5"
            onsubmit="if (window.AlameenButtonLoader) { const b = document.getElementById('delete-student-submit-btn'); if (b) window.AlameenButtonLoader.setButtonLoading(b, true, '{{ __('جاري التحقق والحذف...') }}'); }"
        >
            @csrf
            @method('DELETE')

            <input type="hidden" name="student_id" id="delete-student-id" value="{{ old('student_id', $user?->id) }}">
            <input type="hidden" name="student_name" id="delete-student-name-input" value="{{ old('student_name', $user?->name) }}">
            <input type="hidden" name="student_email" id="delete-student-email-input" value="{{ old('student_email', $user?->email) }}">

            {{-- Target Student Highlight Card --}}
            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="size-10 rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="min-w-0 flex flex-col">
                        <span class="text-[11px] text-slate-400 font-medium">{{ __('الطالب المستهدف:') }}</span>
                        <h4 id="delete-student-target-name" class="font-bold text-sm text-slate-900 dark:text-white truncate">
                            {{ old('student_name', $user?->name ?? '—') }}
                        </h4>
                        <span id="delete-student-target-email" class="text-xs text-slate-500 dark:text-slate-400 font-mono truncate">
                            {{ old('student_email', $user?->email ?? '—') }}
                        </span>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/70 dark:text-rose-300 border border-rose-200 dark:border-rose-900 shrink-0">
                    {{ __('سيتم حذفه نهائياً') }}
                </span>
            </div>

            {{-- Detailed Consequences Notice --}}
            <div class="p-3.5 rounded-2xl bg-rose-50/70 dark:bg-rose-950/30 border border-rose-200/70 dark:border-rose-900/60 text-rose-800 dark:text-rose-300 flex items-start gap-2.5 text-xs leading-relaxed">
                <svg class="size-4 shrink-0 text-rose-600 dark:text-rose-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex flex-col gap-1">
                    <strong class="font-bold">{{ __('تأثير هذا الإجراء على بيانات الطالب:') }}</strong>
                    <ul class="list-disc list-inside text-[11px] text-rose-700 dark:text-rose-300/90 space-y-0.5 ps-1">
                        <li>{{ __('حذف كافة اشتراكات الكورسات وصلاحيات المشاهدة الخاصة به.') }}</li>
                        <li>{{ __('إلغاء الشهادات الممنوحة له وسجلات التوثيق الأكاديمية.') }}</li>
                        <li>{{ __('مسح سجل تقدم المحاضرات والفيديوهات وتقييمات المهام.') }}</li>
                        <li>{{ __('حذف حسابه نهائياً ومنعه من تسجيل الدخول مرة أخرى.') }}</li>
                    </ul>
                </div>
            </div>

            {{-- Admin Password Input Field --}}
            <div class="flex flex-col gap-1.5 pt-1">
                <label for="delete-student-admin-password" class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                    <span>{{ __('كلمة مرور المشرف لتأكيد الحذف') }}</span>
                    <span class="text-rose-500 font-bold">*</span>
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="admin_password" 
                        id="delete-student-admin-password" 
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••••••" 
                        class="w-full h-11 ps-4 pe-11 rounded-xl border {{ $errors->has('admin_password') ? 'border-rose-400 dark:border-rose-500 bg-rose-50/30' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800' }} text-xs font-medium text-slate-800 dark:text-slate-200 outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-500/20 transition-all font-mono"
                    >
                    <button 
                        type="button" 
                        onclick="toggleAdminDeletePasswordVisibility()" 
                        class="absolute inset-y-0 end-0 flex items-center pe-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer transition-colors"
                        tabindex="-1"
                        aria-label="{{ __('إظهار / إخفاء كلمة المرور') }}"
                    >
                        <svg id="eye-icon-open" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg id="eye-icon-closed" class="size-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    </button>
                </div>

                @if($errors->has('admin_password'))
                    <p class="text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1.5 mt-1">
                        <svg class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first('admin_password') }}</span>
                    </p>
                @else
                    <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">
                        {{ __('أدخل كلمة مرور حسابك كمسؤول للتحقق من هويتك وتأكيد تنفيذ عملية الحذف نهائياً.') }}
                    </span>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                <button 
                    type="button" 
                    onclick="closeDeleteStudentModal()" 
                    class="h-11 px-5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition-colors cursor-pointer border border-slate-200 dark:border-slate-700"
                >
                    {{ __('إلغاء الأمر') }}
                </button>
                <button 
                    type="submit" 
                    id="delete-student-submit-btn"
                    class="h-11 px-5.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>{{ __('تأكيد الحذف نهائياً') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleAdminDeletePasswordVisibility() {
        const input = document.getElementById('delete-student-admin-password');
        const openIcon = document.getElementById('eye-icon-open');
        const closedIcon = document.getElementById('eye-icon-closed');
        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            openIcon?.classList.add('hidden');
            closedIcon?.classList.remove('hidden');
        } else {
            input.type = 'password';
            openIcon?.classList.remove('hidden');
            closedIcon?.classList.add('hidden');
        }
    }

    function closeDeleteStudentModal() {
        if (typeof window.closeModal === 'function') {
            window.closeModal('modal-delete-student');
        } else {
            const m = document.getElementById('modal-delete-student');
            if (m) m.classList.add('modal-hidden');
        }
        const pwdInput = document.getElementById('delete-student-admin-password');
        if (pwdInput) {
            pwdInput.value = '';
        }
    }

    function openDeleteStudentModal(studentId, studentName, studentEmail, deleteUrl) {
        const form = document.getElementById('delete-student-form');
        if (form && deleteUrl) {
            form.action = deleteUrl;
        }

        const idInput = document.getElementById('delete-student-id');
        if (idInput && studentId) idInput.value = studentId;

        const nameInput = document.getElementById('delete-student-name-input');
        if (nameInput && studentName) nameInput.value = studentName;

        const emailInput = document.getElementById('delete-student-email-input');
        if (emailInput && studentEmail) emailInput.value = studentEmail;

        const nameEl = document.getElementById('delete-student-target-name');
        if (nameEl && studentName) nameEl.textContent = studentName;

        const emailEl = document.getElementById('delete-student-target-email');
        if (emailEl && studentEmail) emailEl.textContent = studentEmail;

        const pwdInput = document.getElementById('delete-student-admin-password');
        if (pwdInput) {
            pwdInput.value = '';
        }

        if (typeof window.openModal === 'function') {
            window.openModal('modal-delete-student');
        } else {
            const m = document.getElementById('modal-delete-student');
            if (m) m.classList.remove('modal-hidden');
        }

        setTimeout(() => {
            const p = document.getElementById('delete-student-admin-password');
            if (p) p.focus();
        }, 120);
    }

    @if($errors->has('admin_password'))
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof window.openModal === 'function') {
                window.openModal('modal-delete-student');
            } else {
                const m = document.getElementById('modal-delete-student');
                if (m) m.classList.remove('modal-hidden');
            }
            const p = document.getElementById('delete-student-admin-password');
            if (p) p.focus();
        });
    @endif
</script>
