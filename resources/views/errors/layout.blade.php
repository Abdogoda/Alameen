<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'ar') }}" dir="{{ (app()->getLocale() ?? 'ar') === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">

    @php
        $siteName = 'مركز الأمين';
        $siteTagline = 'المنصة الأكاديمية والسريرية الأولى لتدريب وتأهيل أخصائي النطق والتخاطب';
        $logoUrl = '/assets/images/logo.png';
        $logoWhiteUrl = '/assets/images/logo-white.png';
        $whatsAppUrl = 'https://wa.me/201005123784';
        $contactPhone = '01005123784';
        $contactEmail = 'hm2991479@gmail.com';

        try {
            if (class_exists(\App\Services\Cms\SettingsService::class)) {
                $settings = app(\App\Services\Cms\SettingsService::class);
                $siteName = $settings->getSiteName() ?: $siteName;
                $siteTagline = $settings->getSiteTagline() ?: $siteTagline;
                $logoUrl = $settings->getLogoUrl() ?: $logoUrl;
                $logoWhiteUrl = $settings->getLogoWhiteUrl() ?: $logoWhiteUrl;
                $whatsAppUrl = $settings->getWhatsAppUrl() ?: $whatsAppUrl;
                $contactPhone = $settings->getContactPhone() ?: $contactPhone;
                $contactEmail = $settings->getContactEmail() ?: $contactEmail;
            }
        } catch (\Throwable $e) {}

        // Determine current error code and intelligent fallbacks
        $errorCode = trim($__env->yieldContent('code', '500'));
        if (empty($errorCode) || !is_numeric($errorCode)) {
            $errorCode = '500';
        }

        // Default dictionaries per error code
        $defaults = [
            '400' => [
                'badge' => 'طلب غير صالح | Bad Request',
                'title_ar' => 'طلب غير صالح أو غير مكتمل',
                'title_en' => 'Bad Request',
                'desc_ar' => 'تعذر على الخادم معالجة طلبك بسبب صيغة غير صالحة. يرجى التحقق من الرابط وإعادة المحاولة.',
                'desc_en' => 'The server could not understand the request due to invalid syntax. Please verify and try again.',
                'color' => '#F59E0B',
            ],
            '401' => [
                'badge' => 'غير مصرح | Unauthorized',
                'title_ar' => 'تسجيل الدخول مطلوب للمتابعة',
                'title_en' => 'Authentication Required',
                'desc_ar' => 'تحتاج إلى تسجيل الدخول بحسابك للوصول إلى هذا المحتوى أو الصفحة.',
                'desc_en' => 'You need to be signed in to access this page or resource.',
                'color' => '#3B82F6',
            ],
            '402' => [
                'badge' => 'الدفع مطلوب | Payment Required',
                'title_ar' => 'يتطلب هذا القسم اشتراكاً مفعلاً',
                'title_en' => 'Payment Required',
                'desc_ar' => 'هذا المحتوى متاح للمشتركين المسجلين في الدورة. يرجى تفعيل اشتراكك للاستمتاع بالمحتوى.',
                'desc_en' => 'This section requires an active enrollment or payment.',
                'color' => '#F59E0B',
            ],
            '403' => [
                'badge' => 'الوصول محظور | Forbidden',
                'title_ar' => 'ليس لديك صلاحية للوصول إلى هذه الصفحة',
                'title_en' => 'Access Denied / Forbidden',
                'desc_ar' => 'عذراً، هذا القسم مخصص لصلاحيات محددة ولا تملك إذناً كافياً لعرضه.',
                'desc_en' => 'Sorry, you do not have permission to view or manage this resource.',
                'color' => '#EF4444',
            ],
            '404' => [
                'badge' => 'الصفحة غير موجودة | 404 Not Found',
                'title_ar' => 'عذراً، لم نتمكن من العثور على هذه الصفحة',
                'title_en' => 'Page Not Found',
                'desc_ar' => 'الصفحة التي تبحث عنها قد تكون حُذفت، تغيّر مسارها، أو أصبحت غير متوفرة مؤقتاً.',
                'desc_en' => 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.',
                'color' => '#3B82F6',
            ],
            '419' => [
                'badge' => 'انتهت الجلسة | Page Expired',
                'title_ar' => 'انتهت صلاحية الجلسة بسبب عدم النشاط',
                'title_en' => 'Session Expired',
                'desc_ar' => 'تم إنهاء الجلسة لحماية أمان حسابك. يرجى تحديث الصفحة وإعادة المحاولة.',
                'desc_en' => 'Your security token or session has expired. Please refresh the page and submit again.',
                'color' => '#F59E0B',
            ],
            '429' => [
                'badge' => 'طلبات كثيرة | Too Many Requests',
                'title_ar' => 'طلبات متتالية كثيرة في وقت قصير',
                'title_en' => 'Rate Limit Exceeded',
                'desc_ar' => 'أرسلت عدة طلبات بسرعة. يُرجى الانتظار بضع لحظات ثم إعادة المحاولة.',
                'desc_en' => 'You have sent too many requests in a short time. Please wait a moment before trying again.',
                'color' => '#F59E0B',
            ],
            '500' => [
                'badge' => 'خطأ في الخادم | Server Error',
                'title_ar' => 'عذراً، حدث خطأ فني غير متوقع في الخادم',
                'title_en' => 'Internal Server Error',
                'desc_ar' => 'واجه نظامنا مشكلة تقنية مفاجئة. فريقنا الفني يعمل دائماً على معالجة المشكلات فوراً.',
                'desc_en' => 'We are experiencing an internal technical issue. Our engineering team is looking into it.',
                'color' => '#EF4444',
            ],
            '502' => [
                'badge' => 'بوابة غير صالحة | Bad Gateway',
                'title_ar' => 'خطأ مؤقت في الاتصال مع الخادم',
                'title_en' => 'Bad Gateway',
                'desc_ar' => 'حدث تعثر أثناء الاتصال بين خوادمنا. يرجى الانتظار ثوانٍ قليلة ثم إعادة المحاولة.',
                'desc_en' => 'The server received an invalid response from an upstream server. Please try again shortly.',
                'color' => '#EF4444',
            ],
            '503' => [
                'badge' => 'أعمال صيانة وترقية مجدولة',
                'title_ar' => 'المنصة تخضع للصيانة والتطوير الدوري',
                'title_en' => 'Platform Under Scheduled Maintenance',
                'desc_ar' => 'نعمل حالياً على تحديث منظومة مركز الأمين وتطوير البنية التحتية لتقديم أفضل تجربة تدريبية وسريرية. سنعود للعمل بكامل طاقتنا في أقرب وقت!',
                'desc_en' => 'We are currently upgrading our platform infrastructure to deliver the best learning and clinical experience. We will be back online very soon!',
                'color' => '#10B77F',
            ],
            '504' => [
                'badge' => 'انتهت المهلة | Gateway Timeout',
                'title_ar' => 'استغرق الخادم وقتاً أطول من المتوقع للرد',
                'title_en' => 'Gateway Timeout',
                'desc_ar' => 'انتهت مهلة انتظار استجابة الخادم. يُرجى إعادة تحديث الصفحة.',
                'desc_en' => 'The server took too long to respond. Please refresh the page.',
                'color' => '#F59E0B',
            ],
        ];

        $currentMeta = $defaults[$errorCode] ?? $defaults['500'];

        $pageTitle = trim($__env->yieldContent('title'));
        if (empty($pageTitle)) {
            $pageTitle = $errorCode . ' - ' . $currentMeta['title_ar'];
        }

        $badgeText = trim($__env->yieldContent('badge_text'));
        if (empty($badgeText)) {
            $badgeText = $currentMeta['badge'];
        }

        $titleAr = trim($__env->yieldContent('title_ar'));
        if (empty($titleAr)) {
            $rawMsg = trim($__env->yieldContent('message'));
            $titleAr = (!empty($rawMsg) && $rawMsg !== $errorCode) ? $rawMsg : $currentMeta['title_ar'];
        }

        $titleEn = trim($__env->yieldContent('title_en'));
        if (empty($titleEn)) {
            $titleEn = $currentMeta['title_en'];
        }

        $descAr = trim($__env->yieldContent('description_ar'));
        if (empty($descAr)) {
            $descAr = $currentMeta['desc_ar'];
        }

        $descEn = trim($__env->yieldContent('description_en'));
        if (empty($descEn)) {
            $descEn = $currentMeta['desc_en'];
        }
    @endphp

    <title>{{ $pageTitle }} | {{ $siteName }}</title>

    <!-- Theme & Appearance Initialization (Zero Flash) -->
    <script>
        (function () {
            try {
                var root = document.documentElement;
                var stored = localStorage.getItem('alameen_theme') || localStorage.getItem('flux.appearance') || 'light';
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                var isDark = stored === 'dark' || (stored === 'system' && prefersDark);
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

    <!-- Favicons -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=2">
    <link rel="shortcut icon" href="/favicon.ico?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=2">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@600;700;800&family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Standalone Self-Contained Resilient Styling (Never breaks under any condition) -->
    <style>
        :root {
            --alameen-navy: #012383;
            --alameen-navy-light: #0B2A8F;
            --alameen-accent: #10B77F;
            --alameen-gold: #F59E0B;
            --alameen-bg: #F8FAFC;
            --alameen-text: #0F172A;
            --alameen-muted: #64748B;
            --alameen-border: rgba(226, 232, 240, 0.85);
            --alameen-card: #FFFFFF;
        }

        html.dark {
            --alameen-bg: #0B1120;
            --alameen-text: #F8FAFC;
            --alameen-muted: #94A3B8;
            --alameen-border: rgba(255, 255, 255, 0.08);
            --alameen-card: #0F172A;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Cairo', system-ui, -apple-system, sans-serif;
            background-color: var(--alameen-bg);
            color: var(--alameen-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-x: hidden;
            transition: background-color 0.25s ease, color 0.25s ease;
            position: relative;
        }

        .font-brand {
            font-family: 'Baloo Bhaijaan 2', 'Cairo', cursive, sans-serif;
        }

        .font-en {
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* Ambient Glow & Background Mesh */
        .ambient-mesh {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.18;
            will-change: transform;
            animation: orbFloat 16s ease-in-out infinite alternate;
        }

        html.dark .orb {
            opacity: 0.14;
        }

        .orb-1 {
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, #012383 0%, rgba(1, 35, 131, 0) 70%);
            top: -160px;
            right: -120px;
        }

        .orb-2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #10B77F 0%, rgba(16, 183, 127, 0) 70%);
            bottom: -120px;
            left: -120px;
            animation-duration: 20s;
            animation-direction: alternate-reverse;
        }

        .orb-3 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #3B82F6 0%, rgba(59, 130, 246, 0) 70%);
            top: 35%;
            left: 25%;
            animation-duration: 24s;
        }

        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, 35px) scale(1.04); }
            100% { transform: translate(-25px, 20px) scale(0.96); }
        }

        /* Subtle crisp grid pattern */
        .bg-grid {
            background-size: 36px 36px;
            background-image: 
                linear-gradient(to right, rgba(1, 35, 131, 0.035) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(1, 35, 131, 0.035) 1px, transparent 1px);
        }

        html.dark .bg-grid {
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.025) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
        }

        /* Zero flash logo toggling */
        html.dark .logo-theme-light { display: none !important; }
        html.dark .logo-theme-dark { display: block !important; }
        html:not(.dark) .logo-theme-light { display: block !important; }
        html:not(.dark) .logo-theme-dark { display: none !important; }

        /* Header Navbar */
        .err-header {
            width: 100%;
            position: relative;
            z-index: 20;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--alameen-border);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.75);
        }

        html.dark .err-header {
            background: rgba(11, 17, 32, 0.75);
        }

        @media (max-width: 640px) {
            .err-header {
                padding: 14px 18px;
            }
        }

        /* Main Section & Card */
        .err-main {
            width: 100%;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            z-index: 10;
        }

        .err-card {
            width: 100%;
            max-width: 660px;
            background: var(--alameen-card);
            border: 1px solid var(--alameen-border);
            border-radius: 28px;
            padding: 44px 38px 36px 38px;
            text-align: center;
            position: relative;
            box-shadow: 
                0 20px 45px -12px rgba(1, 35, 131, 0.08),
                0 0 0 1px rgba(1, 35, 131, 0.02);
            transition: all 0.25s ease;
        }

        html.dark .err-card {
            box-shadow: 
                0 24px 50px -12px rgba(0, 0, 0, 0.55),
                0 0 0 1px rgba(255, 255, 255, 0.06);
        }

        @media (max-width: 640px) {
            .err-card {
                padding: 32px 20px 28px 20px;
                border-radius: 22px;
            }
        }

        /* Top decorative accent pill line */
        .err-card-line {
            position: absolute;
            top: 0;
            left: 10%;
            right: 10%;
            height: 3px;
            border-radius: 0 0 8px 8px;
            background: linear-gradient(90deg, transparent, #012383, #10B77F, transparent);
        }

        /* Badges */
        .err-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            background: #EAF1FB;
            color: #012383;
            border: 1px solid #D5E3F7;
            margin-bottom: 24px;
        }

        html.dark .err-badge {
            background: rgba(30, 41, 59, 0.9);
            color: #93C5FD;
            border-color: #334155;
        }

        .err-badge .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10B77F;
            display: inline-block;
            box-shadow: 0 0 0 2px rgba(16, 183, 127, 0.2);
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.85); }
        }

        /* Illustration Artwork Container */
        .err-illustration-box {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px auto;
            position: relative;
            width: 140px;
            height: 140px;
        }

        @keyframes gearRotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes gearRotateReverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }

        .anim-spin-cw {
            animation: gearRotate 22s linear infinite;
            transform-origin: center;
        }

        .anim-spin-ccw {
            animation: gearRotateReverse 18s linear infinite;
            transform-origin: center;
        }

        /* Error Code Number */
        .err-code-number {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 58px;
            font-weight: 900;
            line-height: 1;
            letter-spacing: -0.03em;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #012383 0%, #2563EB 50%, #10B77F 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        html.dark .err-code-number {
            background: linear-gradient(135deg, #60A5FA 0%, #93C5FD 50%, #34D399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Arabic Main Title */
        .err-heading-ar {
            font-family: 'Baloo Bhaijaan 2', 'Cairo', sans-serif;
            font-size: 25px;
            font-weight: 800;
            color: var(--alameen-navy);
            line-height: 1.35;
            margin-bottom: 6px;
        }

        html.dark .err-heading-ar {
            color: #FFFFFF;
        }

        @media (max-width: 640px) {
            .err-heading-ar {
                font-size: 21px;
            }
        }

        /* English Subtitle */
        .err-heading-en {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--alameen-muted);
            letter-spacing: 0.01em;
            margin-bottom: 18px;
        }

        /* Descriptions */
        .err-body-text {
            max-width: 540px;
            margin: 0 auto 26px auto;
        }

        .err-desc-ar {
            font-size: 14px;
            line-height: 1.8;
            color: #334155;
            font-weight: 500;
        }

        html.dark .err-desc-ar {
            color: #CBD5E1;
        }

        .err-desc-en {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 12.5px;
            line-height: 1.65;
            color: #64748B;
            margin-top: 6px;
            direction: ltr;
            text-align: center;
        }

        html.dark .err-desc-en {
            color: #94A3B8;
        }

        /* System Info Alert */
        .err-alert-banner {
            max-width: 480px;
            margin: 0 auto 20px auto;
            padding: 9px 16px;
            border-radius: 12px;
            background: #FEF3C7;
            border: 1px solid #FDE68A;
            color: #92400E;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.5;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        html.dark .err-alert-banner {
            background: rgba(120, 53, 15, 0.25);
            border-color: rgba(180, 83, 9, 0.4);
            color: #FDE68A;
        }

        /* Action Buttons Area */
        .err-actions-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 26px 0 20px 0;
            width: 100%;
        }

        .err-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: 14px;
            font-size: 13.5px;
            font-weight: 700;
            font-family: 'Cairo', sans-serif;
            text-decoration: none !important;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
            border: 1px solid transparent;
            min-height: 44px;
        }

        .err-btn:hover {
            transform: translateY(-2px);
        }

        .err-btn:active {
            transform: translateY(0);
        }

        .err-btn svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        .err-btn-primary {
            background: linear-gradient(135deg, #012383 0%, #0B2A8F 100%);
            color: #FFFFFF !important;
            box-shadow: 0 4px 14px -1px rgba(1, 35, 131, 0.32);
        }

        .err-btn-primary:hover {
            background: linear-gradient(135deg, #0B2A8F 0%, #012383 100%);
            box-shadow: 0 8px 20px -2px rgba(1, 35, 131, 0.42);
        }

        .err-btn-whatsapp {
            background: #10B77F;
            color: #FFFFFF !important;
            box-shadow: 0 4px 14px -1px rgba(16, 183, 127, 0.32);
        }

        .err-btn-whatsapp:hover {
            background: #0E9F6E;
            box-shadow: 0 8px 20px -2px rgba(16, 183, 127, 0.42);
        }

        .err-btn-secondary {
            background: #FFFFFF;
            color: #012383 !important;
            border: 1px solid #D5E3F7;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        html.dark .err-btn-secondary {
            background: #1E293B;
            color: #93C5FD !important;
            border-color: #334155;
        }

        .err-btn-secondary:hover {
            background: #F8FAFC;
            border-color: #CBD5E1;
        }

        html.dark .err-btn-secondary:hover {
            background: #334155;
            border-color: #475569;
        }

        /* Navigation Shortcuts */
        .err-nav-shortcuts {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 14px;
            padding-top: 18px;
            margin-top: 8px;
            border-top: 1px solid var(--alameen-border);
            font-size: 12.5px;
            color: var(--alameen-muted);
        }

        .err-nav-shortcuts a {
            color: var(--alameen-muted);
            text-decoration: none;
            transition: color 0.15s ease;
            font-weight: 600;
        }

        .err-nav-shortcuts a:hover {
            color: #012383;
        }

        html.dark .err-nav-shortcuts a:hover {
            color: #60A5FA;
        }

        /* Timer Badge */
        .err-countdown-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 16px;
            padding: 5px 14px;
            border-radius: 9999px;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            font-size: 11.5px;
            color: #475569;
            font-weight: 600;
        }

        html.dark .err-countdown-pill {
            background: #1E293B;
            border-color: #334155;
            color: #94A3B8;
        }

        .err-countdown-pill .radar-pulse {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10B77F;
            position: relative;
        }

        .err-countdown-pill .radar-pulse::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            border: 1.5px solid #10B77F;
            animation: pulseDot 1.5s infinite;
        }

        /* Footer */
        .err-footer {
            width: 100%;
            position: relative;
            z-index: 20;
            padding: 16px 32px;
            border-top: 1px solid var(--alameen-border);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.75);
            font-size: 12px;
            color: var(--alameen-muted);
        }

        html.dark .err-footer {
            background: rgba(11, 17, 32, 0.75);
        }

        .err-footer-content {
            max-width: 960px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        @media (max-width: 640px) {
            .err-footer-content {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }
        }
    </style>

    <!-- Optional precompiled platform CSS fallback -->
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="bg-grid">

    <!-- Ambient Mesh Background -->
    <div class="ambient-mesh">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <!-- Header Bar -->
    <header class="err-header">
        <!-- Logo & Center Identity -->
        <a href="/" style="display: flex; align-items: center; gap: 12px; text-decoration: none;" title="{{ $siteName }}">
            <div style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: #FFFFFF; border: 1px solid rgba(226, 232, 240, 0.85); padding: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" class="dark:bg-slate-900 dark:border-slate-800">
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="logo-theme-light" style="width: 100%; height: 100%; object-fit: contain;" />
                <img src="{{ $logoWhiteUrl }}" alt="{{ $siteName }}" class="logo-theme-dark hidden" style="width: 100%; height: 100%; object-fit: contain;" />
            </div>
            <div style="display: flex; flex-direction: column; text-align: start;">
                <span class="font-brand" style="font-weight: 800; font-size: 17px; color: #012383; line-height: 1.2;" class="dark:text-white">
                    {{ $siteName }}
                </span>
                <span style="font-size: 11px; color: #64748B; font-weight: 600;">
                    منصة تدريب وتأهيل التخاطب
                </span>
            </div>
        </a>

        <!-- Controls: Status Pill & Theme Toggle -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="err-badge" style="margin-bottom: 0; padding: 5px 12px; font-size: 11.5px;">
                <span class="dot"></span>
                <span>نظام الحماية والمراقبة نشط</span>
            </div>

            <!-- Dark / Light Theme Toggle -->
            <button
                type="button"
                id="theme-toggle-btn"
                style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border-radius: 11px; background: #FFFFFF; border: 1px solid #E2E8F0; cursor: pointer; color: #334155; transition: all 0.2s;"
                class="dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200"
                title="تبديل المظهر / Toggle theme"
                onclick="toggleErrorPageTheme()"
            >
                <!-- Sun Icon (dark mode) -->
                <svg id="sun-icon" style="width: 18px; height: 18px; display: none;" class="dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <!-- Moon Icon (light mode) -->
                <svg id="moon-icon" style="width: 18px; height: 18px; display: block;" class="dark:hidden text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="err-main">
        <div class="err-card">
            
            <!-- Top Gradient Line -->
            <div class="err-card-line"></div>

            <!-- Themed Status Pill -->
            <div class="err-badge">
                <span class="dot"></span>
                <span>{{ $badgeText }}</span>
            </div>

            <!-- Error Artwork / Illustration -->
            <div class="err-illustration-box">
                @hasSection('illustration')
                    @yield('illustration')
                @else
                    @if($errorCode === '503')
                        <!-- Bespoke High-Tech 503 Maintenance Beacon -->
                        <div style="position: relative; width: 130px; height: 130px; display: flex; align-items: center; justify-content: center;">
                            <!-- Outer pulsing soft radar ring -->
                            <div style="position: absolute; inset: 0; border-radius: 50%; border: 2px dashed rgba(16, 183, 127, 0.4);" class="anim-spin-cw"></div>
                            <!-- Middle rotating tech gear ring -->
                            <div style="position: absolute; inset: 10px; border-radius: 50%; border: 2px dashed rgba(1, 35, 131, 0.35);" class="anim-spin-ccw"></div>
                            <!-- Center High-Gloss Shield Badge -->
                            <div style="position: relative; width: 68px; height: 68px; border-radius: 20px; background: linear-gradient(135deg, #012383 0%, #0B2A8F 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 24px -4px rgba(1, 35, 131, 0.45); border: 1.5px solid rgba(255, 255, 255, 0.25);">
                                <svg style="width: 32px; height: 32px; color: #10B77F;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                    @elseif($errorCode === '404')
                        <!-- 404 Lost Compass -->
                        <div style="position: relative; width: 130px; height: 130px; display: flex; align-items: center; justify-content: center;">
                            <div style="position: absolute; inset: 0; border-radius: 50%; border: 2px dashed rgba(59, 130, 246, 0.35);" class="anim-spin-cw"></div>
                            <div style="position: relative; width: 68px; height: 68px; border-radius: 20px; background: linear-gradient(135deg, #012383 0%, #1D4ED8 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 24px -4px rgba(1, 35, 131, 0.45); border: 1.5px solid rgba(255, 255, 255, 0.25);">
                                <svg style="width: 32px; height: 32px; color: #93C5FD;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                </svg>
                            </div>
                        </div>
                    @else
                        <!-- Universal Security / Alert Shield -->
                        <div style="position: relative; width: 130px; height: 130px; display: flex; align-items: center; justify-content: center;">
                            <div style="position: relative; width: 68px; height: 68px; border-radius: 20px; background: linear-gradient(135deg, #DC2626 0%, #012383 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 24px -4px rgba(220, 38, 38, 0.4); border: 1.5px solid rgba(255, 255, 255, 0.25);">
                                <svg style="width: 32px; height: 32px; color: #FFFFFF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Error Code Headline -->
            <div class="err-code-number">
                {{ $errorCode }}
            </div>

            <!-- Arabic Main Title -->
            <h1 class="err-heading-ar">
                {{ $titleAr }}
            </h1>

            <!-- English Subtitle -->
            <h2 class="err-heading-en">
                {{ $titleEn }}
            </h2>

            <!-- Descriptions -->
            <div class="err-body-text">
                <p class="err-desc-ar">{{ $descAr }}</p>
                <p class="err-desc-en">{{ $descEn }}</p>
            </div>

            @php
                $customExceptionMessage = null;
                if (isset($exception) && is_object($exception) && method_exists($exception, 'getMessage')) {
                    $raw = trim($exception->getMessage());
                    // Suppress generic HTTP status repeats like "Service Unavailable"
                    $genericTexts = ['Service Unavailable', 'Internal Server Error', 'Not Found', 'Forbidden', 'Unauthorized', ''];
                    if (!empty($raw) && !in_array($raw, $genericTexts, true) && !str_starts_with($raw, 'SQLSTATE') && !str_contains($raw, 'password') && strlen($raw) < 180) {
                        $customExceptionMessage = $raw;
                    }
                }
            @endphp

            @if(!empty($customExceptionMessage))
                <div class="err-alert-banner">
                    <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><strong>ملاحظة من الخادم:</strong> {{ $customExceptionMessage }}</span>
                </div>
            @endif

            <!-- Action Buttons Area -->
            <div class="err-actions-row">
                @hasSection('actions')
                    @yield('actions')
                @else
                    @if($errorCode === '503')
                        <!-- Refresh / Retry CTA -->
                        <button
                            type="button"
                            onclick="window.location.reload()"
                            class="err-btn err-btn-primary"
                        >
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>إعادة المحاولة الآن</span>
                        </button>

                        <a
                            href="{{ $whatsAppUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="err-btn err-btn-whatsapp"
                        >
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.203c.043.071.043.419-.101.824z" />
                            </svg>
                            <span>تواصل عبر واتساب</span>
                        </a>

                        <a
                            href="/"
                            class="err-btn err-btn-secondary"
                        >
                            <span>الصفحة الرئيسية</span>
                        </a>
                    @elseif($errorCode === '401')
                        <a
                            href="/login"
                            class="err-btn err-btn-primary"
                        >
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>تسجيل الدخول</span>
                        </a>

                        <a
                            href="/"
                            class="err-btn err-btn-secondary"
                        >
                            <span>الرئيسية</span>
                        </a>
                    @else
                        <!-- Universal Buttons -->
                        <a
                            href="/"
                            class="err-btn err-btn-primary"
                        >
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>العودة للرئيسية</span>
                        </a>

                        <button
                            type="button"
                            onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '/'; }"
                            class="err-btn err-btn-secondary"
                        >
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transform: scaleX(-1);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>الرجوع للخلف</span>
                        </button>

                        <a
                            href="/courses"
                            class="err-btn err-btn-secondary"
                        >
                            <span>تصفح الدورات</span>
                        </a>
                    @endif
                @endif
            </div>

            <!-- Helpful Navigation Shortcuts Bar -->
            <div class="err-nav-shortcuts">
                <a href="/">الرئيسية</a>
                <span style="opacity: 0.4;">•</span>
                <a href="/courses">الدورات التدريبية</a>
                <span style="opacity: 0.4;">•</span>
                <a href="/contact">تواصل معنا</a>
                <span style="opacity: 0.4;">•</span>
                <a href="/admin/login">لوحة الإدارة</a>
            </div>

            @if($errorCode === '503')
                <!-- Live Maintenance Auto-Refresh Timer -->
                <div class="err-countdown-pill" id="auto-refresh-box">
                    <span class="radar-pulse"></span>
                    <span>إعادة فحص الاتصال تلقائياً خلال <strong id="refresh-seconds" style="color: #012383;" class="dark:text-blue-300">30</strong> ثانية...</span>
                </div>
            @endif

        </div>
    </main>

    <!-- Master Footer (Clean, Single, Perfectly Aligned) -->
    <footer class="err-footer">
        <div class="err-footer-content">
            <p>
                © {{ date('Y') }} <span style="font-weight: 700; color: #012383;" class="dark:text-blue-300">{{ $siteName }}</span>. جميع الحقوق محفوظة.
            </p>
            <div style="display: flex; align-items: center; justify-content: center; gap: 14px; font-size: 11.5px;">
                <span>هاتف: <a href="tel:{{ $contactPhone }}" style="color: inherit; text-decoration: none; font-family: Inter, sans-serif; direction: ltr; display: inline-block;">{{ $contactPhone }}</a></span>
                <span style="opacity: 0.4;">•</span>
                <span>دعم: <a href="mailto:{{ $contactEmail }}" style="color: inherit; text-decoration: none; font-family: Inter, sans-serif;">{{ $contactEmail }}</a></span>
            </div>
        </div>
    </footer>

    <!-- Theme Toggling and Auto-Refresh Script -->
    <script>
        function toggleErrorPageTheme() {
            try {
                var html = document.documentElement;
                var isDark = html.classList.contains('dark');
                var newTheme = isDark ? 'light' : 'dark';

                if (newTheme === 'dark') {
                    html.classList.add('dark');
                    html.style.backgroundColor = '#0B1120';
                    html.style.colorScheme = 'dark';
                } else {
                    html.classList.remove('dark');
                    html.style.backgroundColor = '#FAFCFF';
                    html.style.colorScheme = 'light';
                }

                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('alameen_theme', newTheme);
                localStorage.setItem('flux.appearance', newTheme);
            } catch (e) {
                console.warn('Theme toggle error:', e);
            }
        }

        @if($errorCode === '503')
        (function() {
            var seconds = 30;
            var countdownEl = document.getElementById('refresh-seconds');
            if (countdownEl) {
                var interval = setInterval(function() {
                    seconds--;
                    if (seconds <= 0) {
                        clearInterval(interval);
                        window.location.reload();
                    } else {
                        countdownEl.textContent = seconds;
                    }
                }, 1000);
            }
        })();
        @endif
    </script>
</body>
</html>