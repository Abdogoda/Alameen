@extends('errors.layout')

@section('title', __('خطأ فني في الخادم | 500 Server Error'))
@section('code', '500')
@section('badge_text', '500 | خطأ فني مؤقت')
@section('title_ar', 'نعتذر، حدث خطأ فني غير متوقع في الخادم')
@section('title_en', 'Internal Server Error')
@section('description_ar', 'واجهت أنظمتنا مشكلة فنية طارئة. تم تسجيل تفاصيل الخطأ تلقائياً في سجلات الأمان، ويعمل الفريق التقني حالياً على حلها.')
@section('description_en', 'An unexpected technical issue occurred on our servers. Our technical team has been alerted and is working on resolving it.')
