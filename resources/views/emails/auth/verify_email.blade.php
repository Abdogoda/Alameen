@extends('emails.layouts.master', [
    'locale' => $locale ?? 'ar',
    'emailTitle' => ($locale ?? 'ar') === 'ar' ? 'تأكيد عنوان البريد الإلكتروني - منصة الأمين' : 'Verify Email Address - Alameen Platform'
])

@section('content')
@php
    $isRtl = ($locale ?? 'ar') === 'ar';
    $align = $isRtl ? 'right' : 'left';
    $userName = $user->name ?? ($isRtl ? 'عزيزنا المتدرب' : 'Valued User');
    $expires = $expiresInMinutes ?? 15;
@endphp

<!-- Icon Badge (Checkmark / Shield) -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto 20px auto; text-align: center;">
    <tr>
        <td align="center">
            <div style="width: 64px; height: 64px; line-height: 64px; border-radius: 50%; background-color: #ECFDF5; border: 2px solid #A7F3D0; text-align: center; margin: 0 auto;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
                    <tr>
                        <td align="center" valign="middle" style="padding-top: 14px;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#10B77F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<!-- Section Heading -->
<div style="font-size: 20px; font-weight: 800; color: #012383; text-align: center; margin-bottom: 18px; font-family: 'Cairo', 'Tajawal', sans-serif;">
    {{ $isRtl ? 'تأكيد عنوان بريدك الإلكتروني' : 'Verify Your Email Address' }}
</div>

<!-- Greeting -->
<div style="font-size: 16px; font-weight: 700; color: #012383; margin-bottom: 12px; font-family: 'Cairo', 'Tajawal', sans-serif;">
    @if ($isRtl)
        مرحباً {{ $userName }}،
    @else
        Hello {{ $userName }},
    @endif
</div>

<!-- Intro Explanation -->
<div style="font-size: 14.5px; line-height: 1.7; color: #334155; margin-bottom: 22px; font-family: 'Cairo', 'Tajawal', sans-serif;">
    @if ($isRtl)
        شكراً لانضمامك إلى <strong>منصة الأمين</strong> للتعليم الطبي والتدريب السريري. لإتمام تفعيل حسابك وتأكيد بريدك الإلكتروني، يرجى إدخال رمز التحقق لمرة واحدة (OTP) الموضح أدناه:
    @else
        Thank you for joining <strong>Alameen Platform</strong>. To complete your account activation and verify your email address, please use the one-time verification code (OTP) below:
    @endif
</div>

<!-- OTP Highlight Card -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #F0F5FF; border: 2px dashed #0B2A8F; border-radius: 16px; margin: 24px 0; text-align: center;" bgcolor="#F0F5FF">
    <tr>
        <td style="padding: 24px 20px; text-align: center;" align="center">
            <!-- OTP Card Label -->
            <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #0B2A8F; margin-bottom: 8px; font-family: 'Cairo', 'Tajawal', sans-serif;">
                {{ $isRtl ? 'رمز التحقق لمرة واحدة (OTP)' : 'One-Time Verification Code (OTP)' }}
            </div>

            <!-- OTP Digits -->
            <div class="otp-digits" style="direction: ltr; font-family: 'Courier New', Consolas, Monaco, monospace; font-size: 38px; font-weight: 800; color: #012383; letter-spacing: 12px; padding-left: 12px; margin: 8px 0; display: inline-block;">
                {{ $otp ?? '------' }}
            </div>

            <!-- Expiry Badge -->
            <div style="margin-top: 12px;">
                <span style="display: inline-block; background-color: #FEF3C7; border: 1px solid #FDE68A; color: #92400E; font-size: 12px; font-weight: 700; padding: 5px 14px; border-radius: 20px; font-family: 'Cairo', 'Tajawal', sans-serif;">
                    ⏱️ {{ $isRtl ? 'صالح لمدة ' . $expires . ' دقيقة فقط' : 'Valid for ' . $expires . ' minutes only' }}
                </span>
            </div>
        </td>
    </tr>
</table>

<!-- Security Warning Box -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; {{ $isRtl ? 'border-right: 4px solid #012383;' : 'border-left: 4px solid #012383;' }} border-radius: 12px; margin-top: 26px;" bgcolor="#F8FAFC">
    <tr>
        <td style="padding: 16px 18px; font-size: 13px; line-height: 1.6; color: #475569; font-family: 'Cairo', 'Tajawal', sans-serif;">
            @if ($isRtl)
                <strong style="color: #012383;">🛡️ ملاحظة أمنية:</strong> إذا لم تقم بإنشاء حساب في منصة الأمين، يمكنك تجاهل هذه الرسالة بأمان تام. لن يتم تفعيل الحساب دون إدخال هذا الرمز.
            @else
                <strong style="color: #012383;">🛡️ Security Notice:</strong> If you did not create an account on Alameen Platform, you can safely ignore this email. The account will not be activated without entering this code.
            @endif
        </td>
    </tr>
</table>

@endsection
