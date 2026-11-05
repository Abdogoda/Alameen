@extends('emails.layouts.master', [
    'locale' => $locale ?? 'ar',
    'emailTitle' => ($locale ?? 'ar') === 'ar' ? 'تأكيد عنوان البريد الإلكتروني - منصة الأمين' : 'Verify Email Address - Alameen Platform'
])

@section('content')
@php
    $isRtl = ($locale ?? 'ar') === 'ar';
    $align = $isRtl ? 'right' : 'left';
    $userName = $user->name ?? ($isRtl ? 'عزيزنا المتدرب' : 'Valued User');
@endphp

<!-- Icon Badge (Checkmark / Email) -->
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
<div style="font-size: 14.5px; line-height: 1.7; color: #334155; margin-bottom: 24px; font-family: 'Cairo', 'Tajawal', sans-serif;">
    @if ($isRtl)
        شكراً لانضمامك إلى <strong>منصة الأمين</strong> للتعليم الطبي والتدريب السريري. لإتمام تفعيل حسابك والبدء في استكشاف البرامج التدريبية المتاحة، يرجى تأكيد عنوان بريدك الإلكتروني بالضغط على الزر التالي:
    @else
        Thank you for joining <strong>Alameen Platform</strong>. To complete your account activation and access your medical courses, please verify your email address by clicking the button below:
    @endif
</div>

<!-- Action CTA Button -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 32px auto; text-align: center;">
    <tr>
        <td align="center">
            <a href="{{ $url }}" target="_blank" style="display: inline-block; background-color: #012383; color: #FFFFFF; font-size: 15px; font-weight: 700; text-decoration: none; padding: 15px 36px; border-radius: 12px; box-shadow: 0 4px 14px rgba(1, 35, 131, 0.25); font-family: 'Cairo', 'Tajawal', sans-serif;">
                {{ $isRtl ? 'تأكيد البريد الإلكتروني الآن' : 'Verify Email Address Now' }}
            </a>
        </td>
    </tr>
</table>

<!-- Security / Subcopy Box -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; margin-top: 28px;" bgcolor="#F8FAFC">
    <tr>
        <td style="padding: 16px 18px; font-size: 13px; line-height: 1.6; color: #64748B; font-family: 'Cairo', 'Tajawal', sans-serif;">
            @if ($isRtl)
                إذا كنت تواجه مشكلة في النقر على الزر أعلاه، يمكنك نسخ الرابط التالي ولصقه مباشرة في متصفحك:
            @else
                If you're having trouble clicking the button, copy and paste the URL below into your web browser:
            @endif
            <div style="direction: ltr; text-align: left; word-break: break-all; margin-top: 8px;">
                <a href="{{ $url }}" style="color: #012383; text-decoration: underline; font-size: 12px;">{{ $url }}</a>
            </div>
        </td>
    </tr>
</table>

@endsection
