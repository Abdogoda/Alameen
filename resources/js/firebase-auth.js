import { initializeApp, getApps, getApp } from 'firebase/app';
import { getAuth, GoogleAuthProvider, signInWithPopup, signInWithRedirect, getRedirectResult } from 'firebase/auth';

let cachedInstance = null;
let isAuthInProgress = false;

/**
 * Retrieve or initialize the Firebase app instance using configuration from the window.
 */
export function getFirebaseInstance() {
    if (cachedInstance) {
        return cachedInstance;
    }

    const config = window.__firebaseConfig || {};
    if (!config.projectId && !config.apiKey) {
        return null;
    }

    const app = getApps().length
        ? getApp()
        : initializeApp({
              apiKey: config.apiKey,
              authDomain: config.authDomain || (config.projectId ? `${config.projectId}.firebaseapp.com` : undefined),
              projectId: config.projectId,
              storageBucket: config.storageBucket,
              messagingSenderId: config.messagingSenderId,
              appId: config.appId,
              measurementId: config.measurementId,
          });

    const auth = getAuth(app);
    cachedInstance = { app, auth };
    return cachedInstance;
}

/**
 * Perform Google sign-in via Firebase popup and authenticate with Laravel session.
 *
 * @param {'user'|'admin'} targetRole
 * @param {HTMLElement|null} buttonElement
 */
window.signInWithFirebaseGoogle = async function (targetRole = 'user', buttonElement = null) {
    if (isAuthInProgress) {
        console.warn('[Firebase Auth] Sign-in operation already in progress, ignoring duplicate call.');
        return;
    }

    const fb = getFirebaseInstance();
    if (!fb || !window.__firebaseConfig?.projectId) {
        showAuthAlert(
            'لم يتم ضبط بيانات Firebase (FIREBASE_PROJECT_ID) في ملف الإعدادات .env. يرجى مراجعة إعدادات الخادم.',
            'error'
        );
        return;
    }

    isAuthInProgress = true;
    const originalContent = buttonElement ? buttonElement.innerHTML : null;

    if (buttonElement) {
        buttonElement.disabled = true;
        buttonElement.classList.add('opacity-75', 'cursor-wait');
        const loadingText = targetRole === 'admin'
            ? 'جاري التحقق من هوية المشرف عبر Google...'
            : 'جاري تسجيل الدخول عبر Google...';
        buttonElement.innerHTML = `
            <svg class="animate-spin size-5 shrink-0 text-current" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>${loadingText}</span>
        `;
    }

    let unhandledHandler = null;
    let timeoutId = null;

    try {
        const provider = new GoogleAuthProvider();
        provider.setCustomParameters({ prompt: 'select_account' });

        // Safety timeout (60s) to guarantee the button never spins infinitely if the browser or popup hangs
        const timeoutPromise = new Promise((_, reject) => {
            timeoutId = setTimeout(() => {
                const timeoutErr = new Error('AUTH_TIMEOUT');
                timeoutErr.code = 'auth/timeout';
                reject(timeoutErr);
            }, 60000);
        });

        // Intercept unhandled assertion errors thrown inside Firebase popup event handlers
        const assertionPromise = new Promise((_, reject) => {
            unhandledHandler = (event) => {
                const msg = event?.reason?.message || String(event?.reason || '');
                if (msg.includes('Pending promise was never set') || msg.includes('INTERNAL ASSERTION')) {
                    event.preventDefault();
                    console.warn('[Firebase Auth] Intercepted internal popup assertion error:', msg);
                    const assertionErr = new Error('INTERNAL_ASSERTION_RACE');
                    assertionErr.code = 'auth/internal-assertion';
                    reject(assertionErr);
                }
            };
            window.addEventListener('unhandledrejection', unhandledHandler);
        });

        const result = await Promise.race([
            signInWithPopup(fb.auth, provider),
            timeoutPromise,
            assertionPromise,
        ]);

        const idToken = await result.user.getIdToken();
        await postTokenToBackend(idToken, targetRole, buttonElement, originalContent);
    } catch (error) {
        console.error('[Firebase Auth Error]', error);
        if (error.code === 'auth/popup-closed-by-user') {
            showAuthAlert(getFirebaseErrorMessage(error), 'info');
        } else if (error.code === 'auth/cancelled-popup-request') {
            // Ignored when multiple popups triggered
        } else {
            showAuthAlert(getFirebaseErrorMessage(error), 'error');
        }
        resetButton(buttonElement, originalContent);
    } finally {
        if (timeoutId) {
            clearTimeout(timeoutId);
        }
        if (unhandledHandler) {
            window.removeEventListener('unhandledrejection', unhandledHandler);
        }
        isAuthInProgress = false;
    }
};

/**
 * Send verified Firebase ID token to the Laravel session endpoint.
 */
async function postTokenToBackend(idToken, targetRole, buttonElement, originalContent) {
    const endpoint = targetRole === 'admin'
        ? '/admin/auth/google/firebase'
        : '/auth/google/firebase';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || document.querySelector('input[name="_token"]')?.value;

    const response = await fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken || '',
        },
        body: JSON.stringify({
            id_token: idToken,
            remember: true,
        }),
    });

    const data = await response.json();

    if (response.ok && data.success) {
        if (buttonElement) {
            buttonElement.innerHTML = `
                <svg class="size-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>تم الدخول بنجاح! جاري التوجيه...</span>
            `;
        }
        window.location.href = data.redirect || (targetRole === 'admin' ? '/admin/dashboard' : '/dashboard');
    } else {
        const errorMessage = data.message || 'فشل تسجيل الدخول. يرجى المحاولة مرة أخرى.';
        showAuthAlert(errorMessage, 'error');
        resetButton(buttonElement, originalContent);
    }
}

function getFirebaseErrorMessage(error) {
    const isEn = (document.documentElement.lang || 'ar').toLowerCase().startsWith('en');
    const code = error?.code || '';
    const message = error?.message || '';

    const arErrors = {
        'auth/popup-closed-by-user': 'تم إغلاق نافذة تسجيل الدخول عبر Google.',
        'auth/popup-blocked': 'تم حظر النافذة المنبثقة من قبل المتصفح. يرجى السماح بالنوافذ المنبثقة لمنصة الأمين.',
        'auth/network-request-failed': 'تعذر الاتصال بالخادم. يرجى التحقق من اتصالك بالإنترنت والمحاولة مجدداً.',
        'auth/unauthorized-domain': 'نطاق هذا الموقع غير مصرح به في إعدادات Google Firebase. يرجى إضافته إلى النطاقات المصرح بها (Authorized Domains) في Firebase Console.',
        'auth/operation-not-allowed': 'خدمة تسجيل الدخول عبر Google غير مفعلة حالياً في إعدادات المشروع.',
        'auth/user-disabled': 'تم تعطيل هذا الحساب. يرجى التواصل مع الدعم الفني.',
        'auth/account-exists-with-different-credential': 'يوجد حساب مسجل بالفعل بنفس البريد الإلكتروني ولكن بطريقة تسجيل دخول مختلفة.',
        'auth/invalid-credential': 'بيانات الاعتماد غير صالحة أو انتهت مدتها. يرجى المحاولة مرة أخرى.',
        'auth/timeout': 'استغرقت عملية تسجيل الدخول وقتاً أطول من المتوقع. يرجى إعادة المحاولة.',
        'auth/internal-assertion': 'حدث تعارض في نافذة تسجيل الدخول المنبثقة. يرجى إعادة المحاولة.',
    };

    const enErrors = {
        'auth/popup-closed-by-user': 'Google sign-in popup was closed.',
        'auth/popup-blocked': 'Popup was blocked by your browser. Please allow popups for this site.',
        'auth/network-request-failed': 'Network connection failed. Please check your internet connection.',
        'auth/unauthorized-domain': 'This domain is not authorized in Firebase Console settings.',
        'auth/operation-not-allowed': 'Google sign-in is not enabled in Firebase Console.',
        'auth/user-disabled': 'This account has been disabled.',
        'auth/account-exists-with-different-credential': 'An account already exists with the same email address but different sign-in credentials.',
        'auth/invalid-credential': 'Invalid or expired credentials. Please try again.',
        'auth/timeout': 'Sign-in operation timed out. Please try again.',
        'auth/internal-assertion': 'A popup conflict occurred. Please try again.',
    };

    if (isEn && enErrors[code]) {
        return enErrors[code];
    }
    if (!isEn && arErrors[code]) {
        return arErrors[code];
    }
    if (!isEn) {
        return message && /[\u0600-\u06FF]/.test(message)
            ? message
            : 'حدث خطأ أثناء تسجيل الدخول عبر Google. يرجى إعادة المحاولة.';
    }
    return message || 'An unexpected error occurred during Google Sign-in.';
}

function resetButton(buttonElement, originalContent) {
    if (buttonElement && originalContent) {
        buttonElement.disabled = false;
        buttonElement.classList.remove('opacity-75', 'cursor-wait');
        buttonElement.innerHTML = originalContent;
    }
}

function showAuthAlert(message, type = 'error') {
    let alertBox = document.getElementById('firebase-auth-alert');
    if (!alertBox) {
        alertBox = document.createElement('div');
        alertBox.id = 'firebase-auth-alert';
        document.body.appendChild(alertBox);
    }

    const isError = type === 'error';
    alertBox.className = `fixed bottom-5 end-5 z-50 max-w-md p-4 rounded-2xl shadow-xl transition-all duration-300 font-['Cairo'] flex items-start gap-3 text-sm ${
        isError
            ? 'bg-rose-50 border border-rose-200 text-rose-800 dark:bg-rose-950 dark:border-rose-900 dark:text-rose-200'
            : 'bg-blue-50 border border-blue-200 text-blue-800 dark:bg-blue-950 dark:border-blue-900 dark:text-blue-200'
    }`;

    alertBox.innerHTML = `
        <svg class="size-5 shrink-0 ${isError ? 'text-rose-600' : 'text-blue-600'} mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                isError
                    ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                    : 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            }"/>
        </svg>
        <div class="flex-1">
            <p class="font-bold mb-0.5">${isError ? 'تنبيه تسجيل الدخول' : 'إشعار'}</p>
            <p class="text-xs leading-relaxed opacity-90">${message}</p>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-current opacity-60 hover:opacity-100 p-1">
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;

    setTimeout(() => {
        if (alertBox && alertBox.parentElement) {
            alertBox.remove();
        }
    }, 6000);
}

// Auto-bind to elements with data-firebase-auth attribute on DOM ready
function initFirebaseAuthButtons() {
    document.querySelectorAll('[data-firebase-auth]').forEach((btn) => {
        if (btn.dataset.firebaseBound === 'true') {
            return;
        }
        btn.dataset.firebaseBound = 'true';
        btn.addEventListener('click', (e) => {
            const role = btn.getAttribute('data-firebase-auth') || 'user';
            // Only intercept if Firebase project id is present
            if (window.__firebaseConfig?.projectId) {
                e.preventDefault();
                window.signInWithFirebaseGoogle(role, btn);
            }
        });
    });
}

// Check for redirect result on page load (if user was redirected)
async function checkRedirectResult() {
    try {
        const fb = getFirebaseInstance();
        if (!fb?.auth) return;
        const result = await getRedirectResult(fb.auth);
        if (result && result.user) {
            const idToken = await result.user.getIdToken();
            const targetRole = sessionStorage.getItem('firebase_auth_role') || 'user';
            sessionStorage.removeItem('firebase_auth_role');
            await postTokenToBackend(idToken, targetRole, null, null);
        }
    } catch (err) {
        console.warn('[Firebase Auth] Redirect result check:', err);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initFirebaseAuthButtons();
        checkRedirectResult();
    });
} else {
    initFirebaseAuthButtons();
    checkRedirectResult();
}
