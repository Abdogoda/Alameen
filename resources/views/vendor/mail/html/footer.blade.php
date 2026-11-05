@php
    $locale = app()->getLocale();
    $isRtl = ($locale === 'ar');
    $siteName = platform_name('منصة الأمين');
    $contactEmail = platform_setting('contact_email', 'contact@alameen-speech.com');
@endphp
<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 20px 24px; text-align: center;" bgcolor="#F8FAFC">
<tr>
<td class="content-cell" align="center" style="font-family: 'Cairo', 'Tajawal', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 12px; color: #64748B; line-height: 1.6;">
    <div style="margin-bottom: 8px;">
        @if($isRtl)
            للدعم الفني والاستفسارات: <a href="mailto:{{ $contactEmail }}" style="color: #012383; font-weight: 700; text-decoration: underline;">{{ $contactEmail }}</a>
        @else
            For technical support: <a href="mailto:{{ $contactEmail }}" style="color: #012383; font-weight: 700; text-decoration: underline;">{{ $contactEmail }}</a>
        @endif
    </div>
    <div style="color: #94A3B8; font-size: 11px; margin-bottom: 8px;">
        {{ $isRtl ? 'هذه الرسالة تم إرسالها آلياً من منصة الأمين، يرجى عدم الرد عليها مباشرة.' : 'This email was generated automatically by Alameen Platform. Please do not reply directly.' }}
    </div>
    <div style="color: #94A3B8; font-size: 11px;">
        &copy; {{ date('Y') }} {{ $siteName }}. {{ $isRtl ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
    </div>
</td>
</tr>
</table>
</td>
</tr>
