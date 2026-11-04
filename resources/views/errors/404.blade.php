@extends('errors.layout')

@section('title', __('الصفحة غير موجودة | 404 Not Found'))
@section('code', '404')
@section('badge_text', '404 | الصفحة غير موجودة')
@section('title_ar', 'عذراً، لم نتمكن من العثور على الصفحة المطلوبة')
@section('title_en', 'Page Not Found')
@section('description_ar', 'يبدو أن الرابط الذي اتبعته غير صحيح، أو تم نقل الصفحة أو حذفها أثناء تحديث الموقع. يمكنك العودة للصفحة الرئيسية أو استكشاف دوراتنا التدريبية.')
@section('description_en', 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.')
