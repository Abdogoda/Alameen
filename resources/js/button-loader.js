/**
 * Al-Ameen Global Button Processing & Loader Controller
 * Automatically equips buttons making requests/actions across all pages with a matching loader & contextual title.
 */

const ARABIC_REGEX = /[\u0600-\u06FF]/;

/**
 * Extract clean, human-readable text from an element, removing icons, badge counters, and aria-hidden elements.
 */
export function extractCleanText(element) {
    if (!element) return '';
    try {
        const clone = element.cloneNode(true);
        clone.querySelectorAll('svg, img, [aria-hidden="true"], .badge, [class*="badge"], [class*="pill"]').forEach(el => el.remove());
        return (clone.innerText || clone.textContent || '').trim().replace(/\s+/g, ' ');
    } catch {
        return (element.textContent || '').trim().replace(/\s+/g, ' ');
    }
}

/**
 * Determine the most suitable contextual processing title for a button based on its text, attributes, and context.
 */
export function getProcessingTitle(button, explicitTitle = null) {
    if (explicitTitle) return explicitTitle;
    if (button.dataset?.processingText) return button.dataset.processingText;
    if (button.dataset?.loadingText) return button.dataset.loadingText;

    const rawText = extractCleanText(button);
    const isArabic = ARABIC_REGEX.test(rawText) ||
                     (document.documentElement.lang || '').startsWith('ar') ||
                     document.documentElement.dir === 'rtl' ||
                     Boolean(button.closest && button.closest('[dir="rtl"]'));

    const text = rawText.trim();
    const lower = text.toLowerCase();

    if (isArabic) {
        // High-precision Arabic action detection
        if (text.includes('تسجيل الدخول') || text.includes('دخول لوحة') || text.includes('دخول')) {
            return 'جاري تسجيل الدخول...';
        }
        if (text.includes('تسجيل الخروج') || text.includes('خروج')) {
            return 'جاري تسجيل الخروج...';
        }
        if (text.includes('إنشاء حساب') || text.includes('تسجيل حساب') || (text.includes('تسجيل') && !text.includes('دخول'))) {
            return 'جاري إنشاء الحساب...';
        }
        if (text.includes('رمز التحقق') || text.includes('OTP') || text.includes('إرسال رمز') || text.includes('إرسال الرمز')) {
            return 'جاري إرسال رمز التحقق...';
        }
        if (text.includes('إعادة إرسال') || text.includes('أعد الإرسال')) {
            return 'جاري إعادة الإرسال...';
        }
        if (text.includes('إعادة تعيين')) {
            return 'جاري إعادة التعيين...';
        }
        if (text.includes('إعادة التنشيط')) {
            return 'جاري إعادة التنشيط...';
        }
        if (text.includes('تعليق الحساب')) {
            return 'جاري تعليق الحساب...';
        }
        if (text.includes('حفظ التعديلات') || text.includes('حفظ التغييرات') || text.includes('حفظ الإعدادات') || text.includes('حفظ ونشر') || text.includes('حفظ التقييم') || text.includes('حفظ')) {
            return 'جاري الحفظ...';
        }
        if (text.includes('إرسال الرسالة') || text.includes('إرسال الاستفسار')) {
            return 'جاري إرسال الرسالة...';
        }
        if (text.includes('إرسال الإشعار')) {
            return 'جاري إرسال الإشعار...';
        }
        if (text.includes('إرسال التقييم')) {
            return 'جاري إرسال التقييم...';
        }
        if (text.includes('إرسال التسجيل') || text.includes('تسليم المهمة') || text.includes('تسليم')) {
            return 'جاري تسليم المهمة...';
        }
        if (text.includes('إرسال')) {
            return 'جاري الإرسال...';
        }
        if (text.includes('حذف الكورس') || text.includes('حذف الحساب') || text.includes('حذف المستوى') || text.includes('حذف الدرس') || text.includes('حذف') || text.includes('إزالة')) {
            return 'جاري الحذف...';
        }
        if (text.includes('تأكيد الرفض')) {
            return 'جاري تأكيد الرفض...';
        }
        if (text.includes('تأكيد المتابعة') || text.includes('تأكيد النقل') || text.includes('تأكيد كلمة المرور') || text.includes('تأكيد')) {
            return 'جاري التأكيد...';
        }
        if (text.includes('اعتماد فوري') || text.includes('اعتماد الإيصال') || text.includes('اعتماد ومنح الوصول') || text.includes('اعتماد')) {
            return 'جاري الاعتماد...';
        }
        if (text.includes('رفض الإيصال') || text.includes('رفض')) {
            return 'جاري الرفض...';
        }
        if (text.includes('رفع الإيصال') || text.includes('رفع')) {
            return 'جاري رفع الملف...';
        }
        if (text.includes('تحديث كلمة المرور') || text.includes('تحديث السعر') || text.includes('تحديث البيانات') || text.includes('تحديث')) {
            return 'جاري التحديث...';
        }
        if (text.includes('إنشاء الكورس') || text.includes('إنشاء')) {
            return 'جاري إنشاء الكورس...';
        }
        if (text.includes('إضافة درس') || text.includes('إضافة عنصر') || text.includes('إضافة مستوى') || text.includes('إضافة')) {
            return 'جاري الإضافة...';
        }
        if (text.includes('تفعيل') || text.includes('منح الصلاحية')) {
            return 'جاري تفعيل الصلاحية...';
        }
        if (text.includes('إلغاء الصلاحية') || text.includes('سحب الصلاحية')) {
            return 'جاري إلغاء الصلاحية...';
        }
        if (text.includes('تحديد كمقروء') || text.includes('تمييز كمقروء')) {
            return 'جاري التحديث...';
        }
        if (text.includes('بحث') || text.includes('تصفية')) {
            return 'جاري البحث...';
        }
        if (text.includes('تحقق') || text.includes('متابعة')) {
            return 'جاري المتابعة...';
        }
        if (text.includes('تحميل') || text.includes('تنزيل')) {
            return 'جاري التحميل...';
        }

        return 'جاري المعالجة...';
    } else {
        // High-precision English action detection
        if (lower.includes('log in') || lower.includes('sign in') || lower === 'login') return 'Logging in...';
        if (lower.includes('log out') || lower.includes('sign out') || lower === 'logout') return 'Logging out...';
        if (lower.includes('register') || lower.includes('sign up') || lower.includes('create account')) return 'Creating account...';
        if (lower.includes('save')) return 'Saving...';
        if (lower.includes('send')) return 'Sending...';
        if (lower.includes('submit')) return 'Submitting...';
        if (lower.includes('delete') || lower.includes('remove')) return 'Deleting...';
        if (lower.includes('confirm')) return 'Confirming...';
        if (lower.includes('update')) return 'Updating...';
        if (lower.includes('reset')) return 'Resetting...';
        if (lower.includes('resend')) return 'Resending...';
        if (lower.includes('approve')) return 'Approving...';
        if (lower.includes('reject')) return 'Rejecting...';
        if (lower.includes('upload')) return 'Uploading...';
        if (lower.includes('create')) return 'Creating...';
        if (lower.includes('add')) return 'Adding...';
        if (lower.includes('enable')) return 'Enabling...';
        if (lower.includes('disable')) return 'Disabling...';
        if (lower.includes('regenerate')) return 'Regenerating...';
        if (lower.includes('verify')) return 'Verifying...';
        if (lower.includes('search')) return 'Searching...';
        if (lower.includes('download')) return 'Downloading...';
        if (lower.includes('cancel')) return 'Cancelling...';

        return 'Processing...';
    }
}

/**
 * Put a button into loading state or restore it to its original state.
 */
export function setButtonLoading(button, isLoading = true, customTitle = null) {
    if (!button || !(button instanceof HTMLElement)) return;

    if (isLoading) {
        if (button.dataset.alameenLoading === 'true') return;

        // Preserve button dimensions to avoid layout jumping
        const rect = button.getBoundingClientRect();
        if (rect.width > 0 && !button.style.minWidth) {
            button.dataset.alameenOriginalMinWidth = button.style.minWidth || '';
            button.style.minWidth = `${Math.ceil(rect.width)}px`;
        }

        button.dataset.alameenLoading = 'true';
        button.__alameenOriginalHtml = button.innerHTML;
        button.__alameenOriginalDisabled = button.disabled;

        const title = getProcessingTitle(button, customTitle);

        button.innerHTML = `
            <span class="alameen-btn-loading-content inline-flex items-center justify-center gap-2 pointer-events-none select-none text-current transition-all">
                <svg class="alameen-btn-spinner size-4 shrink-0 animate-spin text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3.5" fill="none"></circle>
                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="alameen-btn-loading-text text-current font-inherit leading-none tracking-normal">${title}</span>
            </span>
        `;

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        button.classList.add('alameen-btn-loading', 'cursor-wait', 'pointer-events-none');

        // Safety auto-reset safeguard (30s) so buttons never get permanently frozen
        if (button.__alameenSafetyTimer) clearTimeout(button.__alameenSafetyTimer);
        button.__alameenSafetyTimer = setTimeout(() => {
            if (button.dataset.alameenLoading === 'true') {
                setButtonLoading(button, false);
            }
        }, 30000);
    } else {
        if (button.dataset.alameenLoading !== 'true') return;

        if (button.__alameenSafetyTimer) {
            clearTimeout(button.__alameenSafetyTimer);
            delete button.__alameenSafetyTimer;
        }

        if (button.__alameenOriginalHtml !== undefined) {
            button.innerHTML = button.__alameenOriginalHtml;
            delete button.__alameenOriginalHtml;
        }

        if (button.__alameenOriginalDisabled !== undefined) {
            button.disabled = button.__alameenOriginalDisabled;
            delete button.__alameenOriginalDisabled;
        } else {
            button.disabled = false;
        }

        if (button.dataset.alameenOriginalMinWidth !== undefined) {
            button.style.minWidth = button.dataset.alameenOriginalMinWidth;
            delete button.dataset.alameenOriginalMinWidth;
        } else {
            button.style.minWidth = '';
        }

        delete button.dataset.alameenLoading;
        button.removeAttribute('aria-busy');
        button.classList.remove('alameen-btn-loading', 'cursor-wait', 'pointer-events-none');
    }
}

/**
 * Restore all active loading buttons across the current page.
 */
export function restoreAllButtons() {
    document.querySelectorAll('[data-alameen-loading="true"]').forEach(btn => {
        setButtonLoading(btn, false);
    });
}

/**
 * Check if a button is exempted from automatic loader (e.g. toggles, close buttons, sliders).
 */
function isExemptButton(btn) {
    if (!btn || !(btn instanceof HTMLElement)) return true;
    if (btn.dataset.noLoader !== undefined || btn.dataset.skipLoader !== undefined) return true;

    // Check specific UI non-action widgets
    if (
        btn.getAttribute('aria-haspopup') === 'menu' ||
        btn.classList.contains('faq-toggle') ||
        btn.classList.contains('slider-dot') ||
        btn.id === 'slider-prev' || btn.id === 'slider-next' ||
        btn.dataset.stepTarget !== undefined ||
        btn.dataset.cat !== undefined ||
        btn.dataset.topic !== undefined ||
        btn.getAttribute('aria-label')?.includes('visibility') ||
        btn.getAttribute('aria-label')?.includes('Toggle password') ||
        btn.getAttribute('onclick')?.includes('closeModal') ||
        btn.getAttribute('onclick')?.includes('openModal') ||
        btn.getAttribute('onclick')?.includes('closeGradeModal') ||
        btn.getAttribute('onclick')?.includes('closeRejectModal') ||
        btn.getAttribute('onclick')?.includes('parentElement.remove')
    ) {
        return true;
    }

    return false;
}

/**
 * Initialize event listeners for form submissions, Livewire, and action clicks.
 */
export function initButtonLoader() {
    if (typeof window === 'undefined' || typeof document === 'undefined') return;

    // 1. FORM SUBMISSIONS: Standard HTML forms, AJAX forms, search forms, and upload forms
    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!form || !(form instanceof HTMLFormElement)) return;
        if (form.dataset.noLoader !== undefined) return;

        // Determine submitter button
        const submitBtn = event.submitter || form.querySelector('button[type="submit"], input[type="submit"]');
        if (!submitBtn || isExemptButton(submitBtn)) return;

        // Skip if HTML5 form validation failed
        if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
            return;
        }

        setButtonLoading(submitBtn, true);

        // Auto-restore on form reset
        const handleReset = () => {
            setButtonLoading(submitBtn, false);
            form.removeEventListener('reset', handleReset);
        };
        form.addEventListener('reset', handleReset, { once: true });
    }, false);

    // 2. LIVEWIRE & EXPLICIT ACTION BUTTON CLICKS
    document.addEventListener('click', (event) => {
        const btn = event.target.closest('button, [role="button"]');
        if (!btn || isExemptButton(btn)) return;

        const isWireClick = btn.hasAttribute('wire:click') || btn.getAttribute('wire:click');
        const isActionBtn = btn.classList.contains('alameen-action-btn') || btn.dataset.action !== undefined;

        if (isWireClick || isActionBtn) {
            setButtonLoading(btn, true);
        }
    }, false);

    // 3. LIVEWIRE 3/4 LIFECYCLE HOOKS
    function setupLivewire(Livewire) {
        if (!Livewire || !Livewire.hook) return;

        Livewire.hook('commit', ({ respond, fail }) => {
            if (respond) respond(() => restoreAllButtons());
            if (fail) fail(() => restoreAllButtons());
        });

        Livewire.hook('request', ({ respond, fail }) => {
            if (respond) respond(() => restoreAllButtons());
            if (fail) fail(() => restoreAllButtons());
        });
    }

    if (window.Livewire) {
        setupLivewire(window.Livewire);
    } else {
        document.addEventListener('livewire:init', () => {
            setupLivewire(window.Livewire);
        });
    }

    // 4. BROWSER NAVIGATION & CACHE RESTORATION
    window.addEventListener('pageshow', () => {
        restoreAllButtons();
    });

    window.addEventListener('popstate', () => {
        restoreAllButtons();
    });

    document.addEventListener('livewire:navigated', () => {
        restoreAllButtons();
    });
}

// Global window exposure for easy access from inline scripts & modules
if (typeof window !== 'undefined') {
    window.AlameenButtonLoader = {
        setButtonLoading,
        restoreAllButtons,
        getProcessingTitle,
        extractCleanText,
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initButtonLoader, { once: true });
    } else {
        initButtonLoader();
    }
}

