@extends('errors.layout')

@section('title', __('انتهت مهلة الاتصال | 504 Gateway Timeout'))
@section('code', '504')
@section('badge_text', '504 | انتهاء مهلة الاستجابة')
@section('title_ar', 'استغرق الخادم وقتاً أطول من المسموح به للاستجابة')
@section('title_en', 'Gateway Timeout')
@section('description_ar', 'تعذر إكمال طلبك بسبب بطء أو انشغال الخادم في معالجة العمليات. يُرجى إعادة المحاولة بعد قليل.')
@section('description_en', 'The upstream server did not send a response in time. Please try refreshing.')
