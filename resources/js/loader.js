/**
 * AL-AMEEN THEMED LOADER & CONTENT REVEAL CONTROLLER
 * 
 * Guarantees the branded speech-therapy loader:
 * 1. ZERO SCROLLING (ONLY WHILE LOADER IS ACTIVE):
 *    - Adds `alameen-loader-active` to document.body while loading, which applies `overflow: hidden !important;`
 *    - Automatically removed the moment the loader dissolves, leaving all pages with normal, silky-smooth scrolling
 * 2. ON REFRESH & LOAD:
 *    - Displays full-screen branded sound-wave & equalizer until content is ready
 *    - Smoothly dissolves with scale & blur and unlocks scrolling immediately
 * 3. ON NAVIGATE (Livewire wire:navigate & page transitions):
 *    - Activates during route navigation, locks scroll momentarily
 *    - Dismisses and restores full scrolling as soon as the target page is navigated and ready
 */

let isLoaderDismissed = false;
let pageLoadStartTime = performance.now();
let navStartTime = 0;
let dismissTimeout = null;

// Helper to get or dynamically inject the loader if missing
function getOrCreateLoader() {
    let loader = document.getElementById('alameen-loader');
    if (loader) return loader;

    loader = document.createElement('div');
    loader.id = 'alameen-loader';
    loader.setAttribute('role', 'status');
    loader.setAttribute('aria-label', 'جاري تحميل منصة الأمين');
    loader.className = 'fixed inset-0 z-[999999] flex flex-col items-center justify-center select-none';
    loader.innerHTML = `
        <div class="alameen-loader-resonator">
            <div class="alameen-voice-wave alameen-voice-wave-1"></div>
            <div class="alameen-voice-wave alameen-voice-wave-2"></div>
            <div class="alameen-voice-wave alameen-voice-wave-3"></div>
            <div class="alameen-loader-logo-capsule">
                <img src="/assets/images/logo.png" alt="منصة الأمين" onerror="this.src='/assets/images/logo.png'; this.onerror=null;" />
            </div>
        </div>
        <div class="alameen-loader-equalizer">
            <div class="alameen-eq-bar"></div>
            <div class="alameen-eq-bar"></div>
            <div class="alameen-eq-bar"></div>
            <div class="alameen-eq-bar"></div>
            <div class="alameen-eq-bar"></div>
        </div>
        <h2 class="alameen-loader-title">منصة الأمين</h2>
        <p class="alameen-loader-subtitle">المنصة التعليمية المتخصصة في التخاطب وعلاج النطق</p>
        <div class="alameen-loader-track">
            <div class="alameen-loader-indicator"></div>
        </div>
    `;
    if (document.body) {
        document.body.prepend(loader);
    }
    return loader;
}

// Sync current dark theme with loader
function syncLoaderTheme(loader) {
    if (!loader) return;
    const isDark = document.documentElement.classList.contains('dark') || 
                   document.documentElement.getAttribute('data-theme') === 'dark';
    if (isDark) {
        loader.classList.add('dark');
    } else {
        loader.classList.remove('dark');
    }
}

/**
 * Lock scrolling strictly on body while the loader is active
 */
function lockScroll() {
    if (typeof document === 'undefined' || !document.body) return;
    document.body.classList.add('alameen-loader-active');
    document.body.classList.remove('content-ready');
}

/**
 * Restore scrolling immediately when the loader dismisses
 */
function unlockScroll() {
    if (typeof document === 'undefined' || !document.body) return;
    document.body.classList.remove('alameen-loader-active');
    document.body.classList.add('content-ready');
}

/**
 * Show loader explicitly (used on navigation start)
 */
export function showLoader(reason = 'navigation') {
    // Respect user appearance settings: do not show if disabled
    try {
        if (localStorage.getItem('alameen_loader_enabled') === 'false' || 
            document.documentElement.getAttribute('data-loader-enabled') === 'false') {
            return;
        }
    } catch (e) {}

    clearTimeout(dismissTimeout);
    const loader = getOrCreateLoader();
    if (!loader) return;

    syncLoaderTheme(loader);
    isLoaderDismissed = false;
    navStartTime = performance.now();

    // Lock scrolling while loader is on screen
    lockScroll();

    // Bring loader into active state
    loader.style.display = 'flex';
    void loader.offsetWidth; // Force reflow
    loader.classList.remove('loader-hidden');
    loader.classList.add('loader-active');
}

/**
 * Dismiss loader with smooth dissolve, reveal content, and restore scrolling
 */
export function dismissLoader(minDisplayTime = 300) {
    clearTimeout(dismissTimeout);
    const loader = document.getElementById('alameen-loader');
    if (!loader) {
        unlockScroll();
        return;
    }

    const elapsed = performance.now() - (navStartTime > 0 ? navStartTime : pageLoadStartTime);
    const remaining = Math.max(0, minDisplayTime - elapsed);

    dismissTimeout = setTimeout(() => {
        isLoaderDismissed = true;
        syncLoaderTheme(loader);

        // Instantly unlock scroll for user interaction
        unlockScroll();

        // Fade out loader
        loader.classList.remove('loader-active');
        loader.classList.add('loader-hidden');

        // Trigger entrance animation on the revealed content ONLY if navigating
        if (navStartTime > 0 && typeof window !== 'undefined' && window.AlameenAnimations && typeof window.AlameenAnimations.triggerPageEntrance === 'function') {
            window.AlameenAnimations.triggerPageEntrance();
        }

        setTimeout(() => {
            if (loader && loader.classList.contains('loader-hidden')) {
                loader.style.display = 'none';
            }
        }, 400);
    }, remaining);
}

/**
 * Initialize loader on page refresh / initial load:
 * The page is already server-rendered by Laravel. Ensure scrolling is immediately unlocked
 * and the loader stays off-screen to prevent any microsecond flash on refresh.
 */
export function initLoader() {
    isLoaderDismissed = true;
    unlockScroll();

    const loader = document.getElementById('alameen-loader');
    if (loader) {
        loader.classList.add('loader-hidden');
        loader.classList.remove('loader-active');
        loader.style.display = 'none';
    }
}

// =========================================================================
// NAVIGATION LIFECYCLE HOOKS (Livewire 3/4 wire:navigate & Link Clicks)
// =========================================================================

if (typeof document !== 'undefined') {
    // 1. Livewire wire:navigate start
    document.addEventListener('livewire:navigating', () => {
        showLoader('livewire-navigating');
    });

    // 2. Livewire wire:navigate complete
    document.addEventListener('livewire:navigated', () => {
        requestAnimationFrame(() => {
            dismissLoader(250);
        });
    });

    // 3. Regular Internal Link Clicks (Smooth departure transition before full page reload)
    document.addEventListener('click', (event) => {
        const link = event.target.closest('a');
        if (!link || !link.href) return;

        // Skip non-navigational clicks
        if (
            link.target === '_blank' ||
            link.hasAttribute('download') ||
            link.getAttribute('href').startsWith('#') ||
            link.getAttribute('href').startsWith('javascript:') ||
            link.getAttribute('href').startsWith('mailto:') ||
            link.getAttribute('href').startsWith('tel:') ||
            link.hasAttribute('data-no-animate') ||
            link.hasAttribute('data-no-loader') ||
            event.ctrlKey ||
            event.metaKey ||
            event.shiftKey ||
            event.altKey
        ) {
            return;
        }

        try {
            const linkUrl = new URL(link.href, window.location.origin);
            if (linkUrl.origin === window.location.origin && linkUrl.pathname !== window.location.pathname) {
                showLoader('link-navigation');
            }
        } catch {}
    }, { passive: true });
}

// Expose on window for global control
if (typeof window !== 'undefined') {
    window.AlameenLoader = {
        show: showLoader,
        dismiss: dismissLoader,
        init: initLoader,
        lockScroll,
        unlockScroll,
        syncTheme: () => syncLoaderTheme(document.getElementById('alameen-loader'))
    };
}

// Auto-run on script execution
if (typeof document !== 'undefined') {
    initLoader();
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLoader, { once: true });
    }
}
