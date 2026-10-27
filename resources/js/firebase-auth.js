import { initializeApp, getApps, getApp } from 'firebase/app';
import { getAuth, GoogleAuthProvider, signInWithPopup } from 'firebase/auth';

/**
 * Retrieve or initialize the Firebase app instance using configuration from the window.
 */
export function getFirebaseInstance() {
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
    return { app, auth };
}

/**
 * Perform Google sign-in via Firebase popup and authenticate with Laravel session.
 *
 * @param {'user'|'admin'} targetRole
 * @param {HTMLElement|null} buttonElement
 */
window.signInWithFirebaseGoogle = async function (targetRole = 'user', buttonElement = null) {
    const fb = getFirebaseInstance();
    if (!fb || !window.__firebaseConfig?.projectId) {
        showAuthAlert(
            'لم يتم ضبط بيانات Firebase (FIREBASE_PROJECT_ID) في ملف الإعدادات .env. يرجى مراجعة إعدادات الخادم.',
            'error'
        );
        return;
    }

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

    try {
        const provider = new GoogleAuthProvider();
        provider.setCustomParameters({ prompt: 'select_account' });

        const result = await signInWithPopup(fb.auth, provider);
        const idToken = await result.user.getIdToken();

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
    } catch (error) {
        if (error.code === 'auth/popup-closed-by-user') {
            showAuthAlert('تم إلغاء نافذة تسجيل الدخول عبر Google.', 'info');
        } else if (error.code === 'auth/cancelled-popup-request') {
            // Ignored when multiple popups triggered
        } else {
            showAuthAlert(error.message || 'حدث خطأ غير متوقع أثناء تسجيل الدخول عبر Google.', 'error');
        }
        resetButton(buttonElement, originalContent);
    }
};

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
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-firebase-auth]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            const role = btn.getAttribute('data-firebase-auth') || 'user';
            // Only intercept if Firebase project id is present
            if (window.__firebaseConfig?.projectId) {
                e.preventDefault();
                window.signInWithFirebaseGoogle(role, btn);
            }
        });
    });
});
