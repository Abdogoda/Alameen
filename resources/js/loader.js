/**
 * Al-Ameen Themed Loader & Smooth Content Reveal Controller
 * Ensures loader stays on top of everything from 0ms, then fades out smoothly to reveal page content.
 */

export function initLoader() {
    const loader = document.getElementById('alameen-loader');
    if (!loader) {
        document.body.classList.add('content-ready');
        return;
    }

    // Sync dark theme immediately
    const isDark = document.documentElement.classList.contains('dark') || 
                   document.documentElement.getAttribute('data-theme') === 'dark';
    if (isDark) {
        loader.classList.add('dark');
    }

    const startTime = performance.now();
    let isDismissed = false;

    const dismissLoader = () => {
        if (isDismissed) return;
        isDismissed = true;

        const elapsed = performance.now() - startTime;
        const minDisplayTime = 300; // brief brand impression
        const remaining = Math.max(0, minDisplayTime - elapsed);

        setTimeout(() => {
            // Trigger smooth content reveal and loader fade out synchronously
            document.body.classList.add('content-ready');
            loader.classList.add('loader-hidden');

            setTimeout(() => {
                loader.style.display = 'none';
            }, 600);
        }, remaining);
    };

    if (document.readyState === 'complete') {
        dismissLoader();
    } else {
        window.addEventListener('load', dismissLoader, { once: true });
        // Fallback safeguard so loader never gets stuck
        setTimeout(dismissLoader, 1200);
    }

    // Support for Livewire 3 wire:navigate
    document.addEventListener('livewire:navigated', () => {
        document.body.classList.add('content-ready');
        const currentLoader = document.getElementById('alameen-loader');
        if (currentLoader) {
            currentLoader.classList.add('loader-hidden');
            currentLoader.style.display = 'none';
        }
    });
}

// Auto-run on load
if (typeof document !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLoader, { once: true });
    } else {
        initLoader();
    }
}

