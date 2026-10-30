<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- Theme Initialization (Zero Flash & Immediate Canvas Theme) -->
<script>
    (function () {
        try {
            var stored = localStorage.getItem('alameen_theme') || localStorage.getItem('flux.appearance') || 'system';
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            var isDark = stored === 'dark' || (stored === 'system' && prefersDark);
            var root = document.documentElement;
            if (isDark) {
                root.classList.add('dark');
                root.style.backgroundColor = '#0B1120';
                root.style.colorScheme = 'dark';
            } else {
                root.classList.remove('dark');
                root.style.backgroundColor = '#FAFCFF';
                root.style.colorScheme = 'light';
            }
            root.setAttribute('data-theme', stored);
        } catch (e) {}
    })();
</script>
<style>
    html.dark, html.dark body {
        background-color: #0B1120 !important;
        color-scheme: dark;
    }
    html.dark #alameen-loader,
    [data-theme="dark"] #alameen-loader {
        background: radial-gradient(circle at center, #0F172A 0%, #0B1120 70%, #060913 100%) !important;
    }
    html.dark .alameen-loader-logo-capsule,
    [data-theme="dark"] .alameen-loader-logo-capsule {
        background: #FFFFFF !important;
        box-shadow: 0 16px 36px -6px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.2) !important;
    }
    html.dark header#main-header a div:first-child,
    html.dark header a div:has(img[src*="logo.png"]) {
        background-color: #FFFFFF !important;
        border-radius: 9999px !important;
        padding: 4px !important;
    }
    html.dark .alameen-voice-wave,
    [data-theme="dark"] .alameen-voice-wave {
        border-color: rgba(96, 165, 250, 0.25) !important;
    }
    html.dark .alameen-voice-wave:nth-child(1),
    [data-theme="dark"] .alameen-voice-wave:nth-child(1) {
        border-color: rgba(96, 165, 250, 0.45) !important;
    }
    html.dark .alameen-voice-wave:nth-child(2),
    [data-theme="dark"] .alameen-voice-wave:nth-child(2) {
        border-color: rgba(59, 130, 246, 0.3) !important;
    }
    html.dark .alameen-voice-wave:nth-child(3),
    [data-theme="dark"] .alameen-voice-wave:nth-child(3) {
        border-color: rgba(30, 58, 138, 0.4) !important;
    }
    html.dark .alameen-loader-title,
    [data-theme="dark"] .alameen-loader-title {
        color: #F8FAFC !important;
    }
    html.dark .alameen-loader-subtitle,
    [data-theme="dark"] .alameen-loader-subtitle {
        background: linear-gradient(90deg, #93C5FD 0%, #60A5FA 50%, #34D399 100%) !important;
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
    }
    html.dark .alameen-loader-track,
    [data-theme="dark"] .alameen-loader-track {
        background: rgba(38, 53, 77, 0.8) !important;
    }
    html.dark .alameen-loader-indicator,
    [data-theme="dark"] .alameen-loader-indicator {
        background: linear-gradient(90deg, #3B82F6 0%, #10B77F 100%) !important;
    }
    html.dark .alameen-eq-bar,
    [data-theme="dark"] .alameen-eq-bar {
        background: linear-gradient(180deg, #60A5FA 0%, #34D399 100%) !important;
    }
</style>

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'منصة الأمين') : config('app.name', 'منصة الأمين') }}
</title>

<!-- Favicons & App Icons -->
<link rel="icon" type="image/svg+xml" href="/favicon.svg?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=2">
<link rel="shortcut icon" href="/favicon.ico?v=2">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=2">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@500;600;700;800&family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/src/css/tokens.css">
<link rel="stylesheet" href="/src/css/animations.css">

@fonts

@include('partials.firebase-config')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

