@extends('errors.layout')

@section('title', __('طلب غير صالح | 400 Bad Request'))
@section('code', '400')
@section('badge_text', '400 | طلب غير صالح')
@section('title_ar', 'الطلب المرسل غير صالح أو بياناته غير مكتملة')
@section('title_en', 'Bad Request')
@section('description_ar', 'تعذر على النظام معالجة الطلب بسبب بيانات مفقودة أو صيغة غير معتمدة. يرجى مراجعة الرابط أو المدخلات.')
@section('description_en', 'The server could not understand the request due to invalid syntax or parameters.')
