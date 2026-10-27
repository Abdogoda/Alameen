<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $locale === 'ar' ? 'رمز التحقق لإعادة تعيين كلمة المرور' : 'Password Reset Verification Code' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #F8FAFC;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1E293B;
            -webkit-text-size-adjust: none;
        }
        .container {
            max-width: 560px;
            margin: 40px auto;
            background-color: #FFFFFF;
            border-radius: 20px;
            border: 1px solid #DEE8F7;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(1, 35, 131, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #012383 0%, #0B2A8F 100%);
            padding: 35px 30px;
            text-align: center;
            color: #FFFFFF;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 8px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 35px 30px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #012383;
            margin-bottom: 12px;
        }
        .text {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 25px;
        }
        .otp-wrapper {
            background-color: #F0F4FF;
            border: 2px dashed #0B2A8F;
            border-radius: 16px;
            padding: 24px 20px;
            text-align: center;
            margin: 25px 0;
        }
        .otp-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0B2A8F;
            margin-bottom: 8px;
        }
        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 38px;
            font-weight: 800;
            color: #012383;
            letter-spacing: 12px;
            margin: 0;
            padding-left: 12px;
            direction: ltr;
            display: inline-block;
        }
        .expiry-badge {
            display: inline-block;
            margin-top: 10px;
            background-color: #FEF3C7;
            border: 1px solid #FDE68A;
            color: #92400E;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }
        .footer {
            background-color: #F8FAFC;
            border-top: 1px solid #E2E8F0;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #94A3B8;
        }
        .security-notice {
            font-size: 13px;
            color: #64748B;
            border-left: 3px solid #CBD5E1;
            padding-left: 12px;
            margin-top: 25px;
            line-height: 1.5;
        }
        [dir="rtl"] .security-notice {
            border-left: none;
            border-right: 3px solid #CBD5E1;
            padding-left: 0;
            padding-right: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $locale === 'ar' ? 'منصة الأمين' : 'Alameen Platform' }}</h1>
            <p>{{ $locale === 'ar' ? 'التعليم الطبي والتطوير المستمر' : 'Medical Education & Continuous Development' }}</p>
        </div>

        <!-- Content Body -->
        <div class="content">
            <div class="greeting">
                @if ($locale === 'ar')
                    مرحباً {{ $user->name ?? 'عزيزنا المتدرب' }}،
                @else
                    Hello {{ $user->name ?? 'Valued User' }},
                @endif
            </div>

            <div class="text">
                @if ($locale === 'ar')
                    لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك في منصة الأمين. يرجى إدخال رمز التحقق التالي لإتمام إعادة التعيين:
                @else
                    We received a request to reset the password for your Alameen Platform account. Please use the verification code below to complete your password reset:
                @endif
            </div>

            <!-- OTP Display Card -->
            <div class="otp-wrapper">
                <div class="otp-label">
                    {{ $locale === 'ar' ? 'رمز التحقق لمرة واحدة' : 'One-Time Verification Code' }}
                </div>
                <div class="otp-code">{{ $otp }}</div>
                <br>
                <div class="expiry-badge">
                    @if ($locale === 'ar')
                        ⏱️ صالح لمدة {{ $expiresInMinutes }} دقيقة فقط
                    @else
                        ⏱️ Valid for {{ $expiresInMinutes }} minutes only
                    @endif
                </div>
            </div>

            <div class="security-notice">
                @if ($locale === 'ar')
                    <strong>ملاحظة أمنية:</strong> إذا لم تقم بطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذه الرسالة بأمان وستظل كلمة مرورك الحالية دون تغيير.
                @else
                    <strong>Security Notice:</strong> If you did not request a password reset, you can safely ignore this email. Your password will remain unchanged.
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $locale === 'ar' ? 'منصة الأمين. جميع الحقوق محفوظة.' : 'Alameen Platform. All rights reserved.' }}</p>
        </div>
    </div>
</body>
</html>
