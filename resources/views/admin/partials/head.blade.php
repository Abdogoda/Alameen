<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

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
<link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@500;600;700;800&family=Cairo:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/src/css/tokens.css">
<link rel="stylesheet" href="/src/css/animations.css">
<link rel="stylesheet" href="/assets/css/admin.css">
<!-- Theme Initialization (Zero Flash) -->
<script>
    (function () {
        try {
            var stored = localStorage.getItem('alameen_theme') || localStorage.getItem('flux.appearance') || 'light';
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            var isDark = stored === 'dark' || (stored === 'system' && prefersDark);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            document.documentElement.setAttribute('data-theme', stored);
        } catch (e) {}
    })();
</script>
<style>
    #alameen-loader {
        position: fixed !important;
        inset: 0 !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        height: 100dvh !important;
        z-index: 999999 !important;
        background: radial-gradient(circle at center, #FFFFFF 0%, #FAFCFF 60%, #EAF1FB 100%);
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        opacity: 1;
        visibility: visible;
        transition: opacity 0.55s cubic-bezier(0.16, 1, 0.3, 1),
                    visibility 0.55s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.55s cubic-bezier(0.16, 1, 0.3, 1),
                    filter 0.55s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform, filter;
    }
    #alameen-loader.loader-hidden {
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
        transform: scale(1.03) !important;
        filter: blur(4px) !important;
    }
    .alameen-content-wrap {
        opacity: 0;
        transform: translateY(6px);
        transition: opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }
    body.content-ready .alameen-content-wrap,
    #alameen-loader.loader-hidden ~ .alameen-content-wrap {
        opacity: 1 !important;
        transform: none !important;
        will-change: auto !important;
    }
    html.dark #alameen-loader,
    [data-theme="dark"] #alameen-loader {
        background: radial-gradient(circle at center, #0F172A 0%, #0B1120 70%, #060913 100%) !important;
    }
</style>

@include('partials.firebase-config')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

