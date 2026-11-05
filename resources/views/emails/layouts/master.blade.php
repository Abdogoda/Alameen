<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ $locale ?? 'ar' }}" dir="{{ ($locale ?? 'ar') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light" />
    <meta name="supported-color-schemes" content="light" />
    <title>{{ $emailTitle ?? platform_name('منصة الأمين') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet" />
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; background-color: #F4F7FB; }
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; max-width: 100% !important; margin: 0 !important; border-radius: 0 !important; border-left: none !important; border-right: none !important; }
            .content-cell { padding: 24px 20px !important; }
            .header-cell { padding: 28px 20px !important; }
            .footer-cell { padding: 22px 20px !important; }
            .otp-digits { font-size: 30px !important; letter-spacing: 8px !important; }
        }
    </style>
</head>
@php
    $resolvedLocale = $locale ?? app()->getLocale();
    $isRtl = ($resolvedLocale === 'ar');
    $dir = $isRtl ? 'rtl' : 'ltr';
    $align = $isRtl ? 'right' : 'left';
    $oppAlign = $isRtl ? 'left' : 'right';

    $siteName = platform_name('منصة الأمين');
    $siteTagline = platform_tagline('المنصة الأكاديمية الأولى لتدريب وتأهيل أخصائي النطق والتخاطب');
    $contactEmail = platform_setting('contact_email', 'contact@alameen-speech.com');

    // Logo Resolution (prefer inline CID embedding if available, fallback to asset URL)
    $logoSrc = null;
    $publicLogoPath = public_path('assets/images/logo.png');
    if (isset($message) && is_object($message) && method_exists($message, 'embed') && file_exists($publicLogoPath)) {
        try {
            $logoSrc = $message->embed($publicLogoPath);
        } catch (\Throwable $e) {
            $logoSrc = asset('assets/images/logo.png');
        }
    } else {
        $logoSrc = asset('assets/images/logo.png');
    }
@endphp
<body style="margin: 0; padding: 0; background-color: #F4F7FB; font-family: 'Cairo', 'Tajawal', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; direction: {{ $dir }}; text-align: {{ $align }}; color: #1E293B; -webkit-font-smoothing: antialiased;">
    <!-- Outer Wrapper Table -->
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #F4F7FB; width: 100%; margin: 0; padding: 32px 12px;" bgcolor="#F4F7FB">
        <tr>
            <td align="center" style="padding: 0;">
                <!-- Main Container Card -->
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" class="email-container" style="max-width: 580px; width: 100%; margin: 0 auto; background-color: #FFFFFF; border-radius: 20px; overflow: hidden; border: 1px solid #DEE8F7; box-shadow: 0 12px 36px rgba(1, 35, 131, 0.07);" bgcolor="#FFFFFF">
                    
                    <!-- Top Emerald/Navy Gradient Line -->
                    <tr>
                        <td style="height: 5px; background-color: #012383; background: linear-gradient(90deg, #012383 0%, #0B2A8F 35%, #2563EB 70%, #10B77F 100%); line-height: 5px; font-size: 1px;">&nbsp;</td>
                    </tr>

                    <!-- Header -->
                    <tr>
                        <td class="header-cell" align="center" style="background-color: #012383; background: linear-gradient(135deg, #012383 0%, #0B2A8F 100%); padding: 32px 28px 28px 28px; text-align: center;" bgcolor="#012383">
                            <!-- Logo Box -->
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto 16px auto;">
                                <tr>
                                    <td align="center" style="background-color: #FFFFFF; padding: 10px; border-radius: 18px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12); width: 64px; height: 64px; text-align: center;" bgcolor="#FFFFFF">
                                        <a href="{{ url('/') }}" target="_blank" style="text-decoration: none; display: block;">
                                            <img src="{{ $logoSrc }}" alt="{{ $siteName }}" width="64" height="64" style="width: 64px; height: 64px; max-width: 64px; max-height: 64px; object-fit: contain; display: block; margin: 0 auto; border: 0;" />
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Brand Name -->
                            <div style="color: #FFFFFF; font-size: 20px; font-weight: 800; letter-spacing: -0.3px; margin: 0 0 6px 0; font-family: 'Cairo', 'Tajawal', sans-serif;">
                                {{ $siteName }}
                            </div>

                            <!-- Tagline -->
                            <div style="color: #DEE8F7; font-size: 13px; font-weight: 500; opacity: 0.92; line-height: 1.5; max-width: 420px; margin: 0 auto; font-family: 'Cairo', 'Tajawal', sans-serif;">
                                {{ $siteTagline }}
                            </div>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class="content-cell" style="padding: 36px 32px 30px 32px; background-color: #FFFFFF; direction: {{ $dir }}; text-align: {{ $align }};" bgcolor="#FFFFFF">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer-cell" align="center" style="background-color: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 24px 30px; text-align: center; direction: {{ $dir }}; font-family: 'Cairo', 'Tajawal', sans-serif;" bgcolor="#F8FAFC">
                            
                            <!-- Support / Contact Line -->
                            <div style="color: #64748B; font-size: 13px; font-weight: 600; margin-bottom: 10px; line-height: 1.6;">
                                @if($isRtl)
                                    هل تحتاج إلى مساعدة أو لديك استفسار؟ 
                                    <a href="mailto:{{ $contactEmail }}" style="color: #012383; font-weight: 700; text-decoration: underline; margin-right: 4px;">{{ $contactEmail }}</a>
                                @else
                                    Need assistance or have questions? 
                                    <a href="mailto:{{ $contactEmail }}" style="color: #012383; font-weight: 700; text-decoration: underline; margin-left: 4px;">{{ $contactEmail }}</a>
                                @endif
                            </div>

                            <!-- Automated Disclaimer -->
                            <div style="color: #94A3B8; font-size: 12px; margin-bottom: 12px; line-height: 1.5;">
                                {{ $isRtl ? 'هذه الرسالة تم إرسالها تلقائياً من منصة الأمين، يرجى عدم الرد المباشر على هذا البريد.' : 'This is an automated notification from Alameen Platform. Please do not reply directly to this email.' }}
                            </div>

                            <!-- Divider line -->
                            <div style="height: 1px; background-color: #E2E8F0; width: 80px; margin: 12px auto;"></div>

                            <!-- Copyright Notice -->
                            <div style="color: #94A3B8; font-size: 12px; font-weight: 500;">
                                &copy; {{ date('Y') }} {{ $siteName }}. {{ $isRtl ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
                            </div>
                        </td>
                    </tr>

                </table>
                <!-- /Main Container Card -->
            </td>
        </tr>
    </table>
    <!-- /Outer Wrapper Table -->
</body>
</html>
