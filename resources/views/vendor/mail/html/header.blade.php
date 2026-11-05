@props(['url'])
<tr>
<td class="header" align="center" style="background-color: #012383; background: linear-gradient(135deg, #012383 0%, #0B2A8F 100%); padding: 26px 20px; text-align: center;" bgcolor="#012383">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto;">
        <tr>
            <td align="center" style="background-color: #FFFFFF; padding: 10px; border-radius: 16px; box-shadow: 0 4px 14px rgba(0,0,0,0.12); width: 60px; height: 60px; text-align: center;" bgcolor="#FFFFFF">
                <a href="{{ $url }}" style="display: block; text-decoration: none;">
                    <img src="{{ asset('assets/images/logo.png') }}" class="logo" alt="{{ platform_name('منصة الأمين') }}" width="60" height="60" style="width: 60px; height: 60px; max-width: 60px; max-height: 60px; object-fit: contain; display: block; margin: 0 auto; border: 0;">
                </a>
            </td>
        </tr>
    </table>
    <div style="color: #FFFFFF; font-size: 18px; font-weight: 800; margin-top: 10px; font-family: 'Cairo', 'Tajawal', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
        {{ platform_name('منصة الأمين') }}
    </div>
    <div style="color: #DEE8F7; font-size: 12px; font-weight: 500; margin-top: 4px; font-family: 'Cairo', 'Tajawal', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
        {{ platform_tagline('المنصة الأكاديمية الأولى لتدريب وتأهيل أخصائي النطق والتخاطب') }}
    </div>
</td>
</tr>
