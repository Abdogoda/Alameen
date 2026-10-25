/**
 * Al-Ameen Multi-Theme Manager (Light / Dark / System-Device)
 * Seamlessly manages color schemes across the public website and admin dashboard.
 * Coordinates with Livewire Flux and stores choice in localStorage.
 */

const THEME_STORAGE_KEY = 'alameen_theme';
const FLUX_STORAGE_KEY = 'flux.appearance';

export function getStoredTheme() {
    try {
        return localStorage.getItem(THEME_STORAGE_KEY) || localStorage.getItem(FLUX_STORAGE_KEY) || 'system';
    } catch {
        return 'system';
    }
}

export function isSystemDark() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
}

export function isDarkActive() {
    const theme = getStoredTheme();
    if (theme === 'dark') return true;
    if (theme === 'light') return false;
    return isSystemDark();
}

export function applyTheme(theme) {
    const root = document.documentElement;
    const isDark = theme === 'dark' || (theme === 'system' && isSystemDark());

    if (isDark) {
        root.classList.add('dark');
        root.style.backgroundColor = '#0B1120';
        root.style.colorScheme = 'dark';
    } else {
        root.classList.remove('dark');
        root.style.backgroundColor = '#FAFCFF';
        root.style.colorScheme = 'light';
    }

    const loader = document.getElementById('alameen-loader');
    if (loader) {
        if (isDark) {
            loader.classList.add('dark');
        } else {
            loader.classList.remove('dark');
        }
    }

    root.setAttribute('data-theme', theme);

    // Notify any Flux appearance if active
    if (window.Flux && typeof window.Flux.applyAppearance === 'function') {
        try {
            window.Flux.applyAppearance(theme);
        } catch {
            // ignore
        }
    }

    // Dispatch global event for reactive UI updates
    window.dispatchEvent(
        new CustomEvent('alameen-theme-changed', {
            detail: {
                theme,
                isDark,
            },
        })
    );

    // Update active state in any non-Alpine theme pickers on the page
    document.querySelectorAll('[data-theme-option]').forEach((el) => {
        const option = el.getAttribute('data-theme-option');
        if (option === theme) {
            el.setAttribute('data-theme-active', 'true');
            el.classList.add('is-active-theme');
        } else {
            el.removeAttribute('data-theme-active');
            el.classList.remove('is-active-theme');
        }
    });

    // Update theme toggle icons if present
    document.querySelectorAll('[data-theme-icon-active]').forEach((el) => {
        el.setAttribute('data-theme-icon-active', theme);
    });
}

export function setTheme(theme) {
    if (!['light', 'dark', 'system'].includes(theme)) {
        theme = 'system';
    }

    try {
        localStorage.setItem(THEME_STORAGE_KEY, theme);
        if (theme === 'system') {
            localStorage.removeItem(FLUX_STORAGE_KEY);
        } else {
            localStorage.setItem(FLUX_STORAGE_KEY, theme);
        }
    } catch {
        // storage disabled or quota exceeded
    }

    applyTheme(theme);
}

// Initialize Theme Manager
export function initTheme() {
    const current = getStoredTheme();
    applyTheme(current);

    // Watch for OS preference change
    if (window.matchMedia) {
        const media = window.matchMedia('(prefers-color-scheme: dark)');
        const listener = () => {
            if (getStoredTheme() === 'system') {
                applyTheme('system');
            }
        };

        if (media.addEventListener) {
            media.addEventListener('change', listener);
        } else if (media.addListener) {
            media.addListener(listener);
        }
    }

    // Sync across tabs
    window.addEventListener('storage', (event) => {
        if (event.key === THEME_STORAGE_KEY || event.key === FLUX_STORAGE_KEY) {
            applyTheme(getStoredTheme());
        }
    });

    // Livewire page navigation support
    document.addEventListener('livewire:navigated', () => {
        applyTheme(getStoredTheme());
    });
}

// Global exposure for Alpine / inline script usage
if (typeof window !== 'undefined') {
    window.AlameenTheme = {
        getTheme: getStoredTheme,
        setTheme,
        applyTheme,
        isDark: isDarkActive,
        isSystemDark,
        init: initTheme,
    };

    // Auto-init on script load
    initTheme();
}
