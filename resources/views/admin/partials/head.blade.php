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

@include('partials.firebase-config')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

