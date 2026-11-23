/**
 * Global Anti-Inspect and Context Menu Disabler
 * Blocks developer tools shortcuts (F12, Ctrl+Shift+I/J/C, Cmd+Option+I/J/C, Ctrl+U)
 * and right-click context menu across the website without suspending user accounts.
 */

export function initAntiInspect() {
    if (typeof window === 'undefined' || typeof document === 'undefined') {
        return;
    }

    // 1. Disable Right Click Context Menu globally
    const blockContextMenu = (e) => {
        e.preventDefault();
        e.stopPropagation();
        return false;
    };

    document.addEventListener('contextmenu', blockContextMenu, true);
    window.addEventListener('contextmenu', blockContextMenu, true);

    // 2. Disable DevTools / Inspect Keyboard Shortcuts
    const blockInspectKeys = (e) => {
        const key = e.key ? e.key.toUpperCase() : '';
        const keyCode = e.keyCode || e.which;
        const isCtrl = Boolean(e.ctrlKey);
        const isMeta = Boolean(e.metaKey); // Command on macOS
        const isShift = Boolean(e.shiftKey);
        const isAlt = Boolean(e.altKey);

        // F12 or Shift+F12
        if (key === 'F12' || keyCode === 123) {
            e.preventDefault();
            e.stopPropagation();
            if (typeof e.stopImmediatePropagation === 'function') {
                e.stopImmediatePropagation();
            }
            return false;
        }

        // Ctrl/Cmd + Shift + (I, J, C, K, E)
        if ((isCtrl || isMeta) && isShift && ['I', 'J', 'C', 'K', 'E'].includes(key)) {
            e.preventDefault();
            e.stopPropagation();
            if (typeof e.stopImmediatePropagation === 'function') {
                e.stopImmediatePropagation();
            }
            return false;
        }

        // Mac Safari / Chrome: Cmd + Option + (I, J, C, U)
        if (isMeta && isAlt && ['I', 'J', 'C', 'U'].includes(key)) {
            e.preventDefault();
            e.stopPropagation();
            if (typeof e.stopImmediatePropagation === 'function') {
                e.stopImmediatePropagation();
            }
            return false;
        }

        // Ctrl/Cmd + U (View Source)
        if ((isCtrl || isMeta) && key === 'U') {
            e.preventDefault();
            e.stopPropagation();
            if (typeof e.stopImmediatePropagation === 'function') {
                e.stopImmediatePropagation();
            }
            return false;
        }

        // Ctrl/Cmd + S (Save Page)
        if ((isCtrl || isMeta) && key === 'S') {
            e.preventDefault();
            e.stopPropagation();
            if (typeof e.stopImmediatePropagation === 'function') {
                e.stopImmediatePropagation();
            }
            return false;
        }
    };

    window.addEventListener('keydown', blockInspectKeys, true);
    document.addEventListener('keydown', blockInspectKeys, true);
}

// Auto-initialize immediately
initAntiInspect();

// Re-bind on Livewire SPA navigations if needed
if (typeof document !== 'undefined') {
    document.addEventListener('livewire:navigated', initAntiInspect);
}
