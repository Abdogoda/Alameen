<div
    id="alameen-loader"
    role="status"
    aria-label="{{ __('جاري تحميل :name', ['name' => $siteName ?? 'منصة الأمين']) }}"
    class="fixed inset-0 z-[999999] flex flex-col items-center justify-center select-none"
>
    <div class="alameen-loader-resonator">
        <div class="alameen-voice-wave alameen-voice-wave-1"></div>
        <div class="alameen-voice-wave alameen-voice-wave-2"></div>
        <div class="alameen-voice-wave alameen-voice-wave-3"></div>
        <div class="alameen-loader-logo-capsule">
            <img src="{{ filled($siteLogo ?? null) ? $siteLogo : platform_logo() }}" alt="{{ filled($siteName ?? null) ? $siteName : platform_name() }}" onerror="this.src='/assets/images/logo.png'; this.onerror=null;" />
        </div>
    </div>

    <div class="alameen-loader-equalizer">
        <div class="alameen-eq-bar"></div>
        <div class="alameen-eq-bar"></div>
        <div class="alameen-eq-bar"></div>
        <div class="alameen-eq-bar"></div>
        <div class="alameen-eq-bar"></div>
    </div>

    <h2 class="alameen-loader-title">{{ filled($siteName ?? null) ? $siteName : platform_name() }}</h2>
    <p class="alameen-loader-subtitle">{{ filled($siteTagline ?? null) ? $siteTagline : platform_tagline() }}</p>

    <div class="alameen-loader-track">
        <div class="alameen-loader-indicator"></div>
    </div>
</div>
