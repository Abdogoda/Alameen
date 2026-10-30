import { Passkeys } from '@laravel/passkeys';

const passkeyTranslations = {
    ar: {
        cancelled: 'تم إلغاء عملية مفتاح المرور.',
        not_supported: 'مفاتيح المرور (Passkeys) غير مدعومة في هذا المتصفح أو الجهاز.',
        already_exists: 'هذا الجهاز مسجل بالفعل كمفتاح مرور.',
        invalid_domain: 'لا يمكن استخدام مفاتيح المرور على هذا النطاق. يرجى استخدام localhost أو اتصال آمن (HTTPS).',
        not_recognized: 'لم يتم التعرف على مفتاح المرور. قد يكون قد تم حذفه من حسابك.',
        expired: 'انتهت صلاحية جلسة التحقق من مفتاح المرور. يرجى المحاولة مرة أخرى.',
        credentials_mismatch: 'بيانات الاعتماد هذه غير متطابقة مع سجلاتنا.',
        unable_to_verify: 'تعذر التحقق من مفتاح المرور. يرجى المحاولة مرة أخرى.',
        unable_to_register: 'تعذر تسجيل مفتاح المرور. يرجى المحاولة مرة أخرى.',
        unable_to_sign_in: 'تعذر تسجيل الدخول بهذا الحساب.',
        timeout_or_not_allowed: 'انتهت مهلة التحقق من مفتاح المرور أو تم رفض العملية.',
        invalid_state: 'مفتاح المرور غير صالح أو مسجل مسبقاً على هذا الجهاز.',
        security_error: 'فشلت العملية لأسباب أمنية. تأكد من استخدام اتصال آمن (HTTPS).',
        network_error: 'فشل الاتصال بالخادم. يرجى التحقق من اتصالك بالإنترنت والمحاولة مجدداً.',
        generic: 'حدث خطأ أثناء استخدام مفتاح المرور. يرجى المحاولة مرة أخرى.',
    },
    en: {
        cancelled: 'The passkey operation was cancelled.',
        not_supported: 'Passkeys are not supported in this browser.',
        already_exists: 'This device is already registered as a passkey.',
        invalid_domain: 'Passkeys cannot be used on this domain. For local development, use localhost.',
        not_recognized: 'Passkey not recognized. It may have been removed from your account.',
        expired: 'Passkey verification challenge expired or is invalid. Please try again.',
        credentials_mismatch: 'These credentials do not match our records.',
        unable_to_verify: 'Unable to verify passkey. Please try again.',
        unable_to_register: 'Unable to register passkey. Please try again.',
        unable_to_sign_in: 'Unable to sign in with this account.',
        timeout_or_not_allowed: 'The passkey operation timed out or was not allowed.',
        invalid_state: 'Invalid passkey state or already registered.',
        security_error: 'The operation failed for security reasons. Ensure HTTPS is used.',
        network_error: 'Server connection failed. Please check your internet connection.',
        generic: 'An unexpected passkey error occurred. Please try again.',
    },
};

export function translatePasskeyError(e) {
    if (!e) return '';
    const lang = (document.documentElement.lang || 'ar').toLowerCase().startsWith('en') ? 'en' : 'ar';
    const dict = passkeyTranslations[lang] || passkeyTranslations.ar;

    const name = e.name || e.constructor?.name || '';
    const msg = (e.message || String(e)).toLowerCase();

    // Cancellation & NotAllowed
    if (
        name === 'UserCancelledError' ||
        name === 'NotAllowedError' ||
        name === 'AbortError' ||
        msg.includes('cancelled') ||
        msg.includes('canceled')
    ) {
        return dict.cancelled;
    }

    // Browser support
    if (name === 'NotSupportedError' || msg.includes('not supported')) {
        return dict.not_supported;
    }

    // Already registered
    if (name === 'PasskeyExistsError' || msg.includes('already registered') || msg.includes('already exists')) {
        return dict.already_exists;
    }

    // Domain mismatch
    if (name === 'InvalidDomainError' || msg.includes('domain') || msg.includes('localhost')) {
        return dict.invalid_domain;
    }

    // State / security
    if (name === 'InvalidStateError') {
        return dict.invalid_state;
    }
    if (name === 'SecurityError') {
        return dict.security_error;
    }

    // Backend validation responses
    if (msg.includes('challenge expired') || msg.includes('expired') || msg.includes('انتهت صلاحية')) {
        return dict.expired;
    }
    if (msg.includes('not recognized') || msg.includes('removed from your account')) {
        return dict.not_recognized;
    }
    if (msg.includes('credentials do not match') || msg.includes('غير متطابقة')) {
        return dict.credentials_mismatch;
    }
    if (msg.includes('unable to verify') || msg.includes('تعذر التحقق')) {
        return dict.unable_to_verify;
    }
    if (msg.includes('unable to register') || msg.includes('تعذر تسجيل')) {
        return dict.unable_to_register;
    }
    if (msg.includes('unable to sign in')) {
        return dict.unable_to_sign_in;
    }
    if (msg.includes('timed out') || msg.includes('not allowed')) {
        return dict.timeout_or_not_allowed;
    }
    if (msg.includes('failed to fetch') || msg.includes('network') || msg.includes('status 50') || msg.includes('status 419')) {
        return dict.network_error;
    }

    // If message is already translated in Arabic and locale is Arabic, keep it
    if (lang === 'ar' && /[\u0600-\u06FF]/.test(e.message || '')) {
        return e.message;
    }

    return lang === 'ar' ? dict.generic : (e.message || dict.generic);
}

window.translatePasskeyError = translatePasskeyError;
Passkeys.translateError = translatePasskeyError;
window.Passkeys = Passkeys;
window.dispatchEvent(new CustomEvent('passkeys:ready'));

