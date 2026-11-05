@extends('emails.layouts.master', [
    'locale' => $locale ?? 'ar',
    'emailTitle' => ($title ?? platform_name('منصة الأمين')) . ' - ' . platform_name('منصة الأمين')
])

@section('content')
@php
    $isRtl = ($locale ?? 'ar') === 'ar';
    $align = $isRtl ? 'right' : 'left';
    $userName = $user->name ?? ($isRtl ? 'عزيزنا المتدرب' : 'Valued User');
    $type = $type ?? 'info';

    // Type styles & icons
    $typeConfig = match($type) {
        'success' => [
            'bg' => '#ECFDF5',
            'border' => '#A7F3D0',
            'color' => '#10B77F',
            'svg' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#10B77F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        'warning' => [
            'bg' => '#FFFBEB',
            'border' => '#FDE68A',
            'color' => '#D97706',
            'svg' => '<path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="#D97706" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        'error' => [
            'bg' => '#FEF2F2',
            'border' => '#FECACA',
            'color' => '#EF4444',
            'svg' => '<path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#EF4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        'security' => [
            'bg' => '#EAF1FB',
            'border' => '#DEE8F7',
            'color' => '#012383',
            'svg' => '<path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke="#012383" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        default => [
            'bg' => '#F0F4FF',
            'border' => '#DEE8F7',
            'color' => '#012383',
            'svg' => '<path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="#012383" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>',
        ]
    };
@endphp

<!-- Icon Badge -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto 20px auto; text-align: center;">
    <tr>
        <td align="center">
            <div style="width: 64px; height: 64px; line-height: 64px; border-radius: 50%; background-color: {{ $typeConfig['bg'] }}; border: 2px solid {{ $typeConfig['border'] }}; text-align: center; margin: 0 auto;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
                    <tr>
                        <td align="center" valign="middle" style="padding-top: 14px;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                {!! $typeConfig['svg'] !!}
                            </svg>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<!-- Title -->
<div style="font-size: 20px; font-weight: 800; color: #012383; text-align: center; margin-bottom: 20px; font-family: 'Cairo', 'Tajawal', sans-serif;">
    {{ $title }}
</div>

<!-- Greeting -->
<div style="font-size: 16px; font-weight: 700; color: #012383; margin-bottom: 12px; font-family: 'Cairo', 'Tajawal', sans-serif;">
    @if ($isRtl)
        مرحباً {{ $userName }}،
    @else
        Hello {{ $userName }},
    @endif
</div>

<!-- Body Message -->
<div style="font-size: 15px; line-height: 1.75; color: #334155; margin-bottom: 26px; font-family: 'Cairo', 'Tajawal', sans-serif;">
    {!! nl2br(e($body)) !!}
</div>

@if (! empty($actionUrl))
    <!-- Action CTA Button -->
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 32px auto; text-align: center;">
        <tr>
            <td align="center">
                <a href="{{ $actionUrl }}" target="_blank" style="display: inline-block; background-color: #012383; color: #FFFFFF; font-size: 15px; font-weight: 700; text-decoration: none; padding: 14px 34px; border-radius: 12px; box-shadow: 0 4px 14px rgba(1, 35, 131, 0.25); font-family: 'Cairo', 'Tajawal', sans-serif;">
                    {{ $actionText ?? ($isRtl ? 'عرض التفاصيل' : 'View Details') }}
                </a>
            </td>
        </tr>
    </table>
@endif

@if (! empty($data) && is_array($data) && count($data) > 0)
    <!-- Extra Details Card -->
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #DEE8F7; border-radius: 14px; margin-top: 24px;" bgcolor="#F8FAFC">
        <tr>
            <td style="padding: 18px 20px;">
                <div style="font-size: 13px; font-weight: 700; color: #012383; margin-bottom: 10px; font-family: 'Cairo', 'Tajawal', sans-serif;">
                    {{ $isRtl ? 'معلومات إضافية:' : 'Additional Details:' }}
                </div>
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                    @foreach ($data as $key => $val)
                        @if (is_scalar($val) && ! empty($val))
                            <tr>
                                <td style="padding: 4px 0; font-size: 13px; color: #64748B; font-weight: 600; width: 40%; font-family: 'Cairo', 'Tajawal', sans-serif;">
                                    {{ is_string($key) ? ucfirst(str_replace('_', ' ', $key)) : '' }}:
                                </td>
                                <td style="padding: 4px 0; font-size: 13px; color: #1E293B; font-weight: 700; font-family: 'Cairo', 'Tajawal', sans-serif;">
                                    {{ (string) $val }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </td>
        </tr>
    </table>
@endif

@endsection
