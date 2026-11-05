@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin: 28px auto; text-align: {{ $align }};">
<tr>
<td align="{{ $align }}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table border="0" cellpadding="0" cellspacing="0" role="presentation" align="{{ $align }}">
<tr>
<td align="center" style="border-radius: 12px; background-color: #012383;" bgcolor="#012383">
<a href="{{ $url }}" class="button button-{{ $color }}" target="_blank" rel="noopener" style="display: inline-block; padding: 14px 34px; background-color: #012383; color: #FFFFFF; font-size: 15px; font-weight: 700; text-decoration: none; border-radius: 12px; font-family: 'Cairo', 'Tajawal', sans-serif; box-shadow: 0 4px 14px rgba(1, 35, 131, 0.25);">{!! $slot !!}</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
