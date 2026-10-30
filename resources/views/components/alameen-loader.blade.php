<div
    id="alameen-loader"
    role="status"
    aria-label="{{ __('جاري تحميل منصة الأمين') }}"
    class="fixed inset-0 z-[999999] flex flex-col items-center justify-center select-none"
>
    <div class="alameen-loader-resonator">
        <div class="alameen-voice-wave alameen-voice-wave-1"></div>
        <div class="alameen-voice-wave alameen-voice-wave-2"></div>
        <div class="alameen-voice-wave alameen-voice-wave-3"></div>
        <div class="alameen-loader-logo-capsule">
            <img src="/assets/images/logo.png" alt="{{ config('app.name', 'منصة الأمين') }}" onerror="this.src='/assets/images/logo.png'; this.onerror=null;" />
        </div>
    </div>

    <div class="alameen-loader-equalizer">
        <div class="alameen-eq-bar"></div>
        <div class="alameen-eq-bar"></div>
        <div class="alameen-eq-bar"></div>
        <div class="alameen-eq-bar"></div>
        <div class="alameen-eq-bar"></div>
    </div>

    <h2 class="alameen-loader-title">{{ config('app.name', 'منصة الأمين') }}</h2>
    <p class="alameen-loader-subtitle">{{ __('المنصة التعليمية المتخصصة في التخاطب وعلاج النطق') }}</p>

    <div class="alameen-loader-track">
        <div class="alameen-loader-indicator"></div>
    </div>
</div>

