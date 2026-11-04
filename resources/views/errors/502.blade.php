@extends('errors.layout')

@section('title', __('بوابة غير صالحة | 502 Bad Gateway'))
@section('code', '502')
@section('badge_text', '502 | خطأ في بوابة الاتصال')
@section('title_ar', 'تعذر استلام استجابة صالحة من الخادم الرئيسي')
@section('title_en', 'Bad Gateway')
@section('description_ar', 'حدث انقطاع مؤقت أثناء اتصال خوادمنا الوسيطة بالخادم الرئيسي. غالباً ما يتم حل هذه المشكلة تلقائياً خلال لحظات.')
@section('description_en', 'The server received an invalid response while attempting to contact upstream services. Please retry in a few moments.')
