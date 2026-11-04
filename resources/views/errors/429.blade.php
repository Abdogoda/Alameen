@extends('errors.layout')

@section('title', __('طلبات متكررة | 429 Too Many Requests'))
@section('code', '429')
@section('badge_text', '429 | حد الاستخدام المؤقت')
@section('title_ar', 'تم إرسال عدد كبير من الطلبات في وقت قصير')
@section('title_en', 'Too Many Requests')
@section('description_ar', 'حرصاً على استقرار وأمان خوادمنا لجميع المتدربين، تم تعليق الطلبات مؤقتاً. يُرجى الانتظار لبضع ثوانٍ ثم المحاولة مجدداً.')
@section('description_en', 'You have reached the temporary rate limit. Please pause for a few moments and try again.')
