@extends('errors.layout')

@section('title', __('انتهت صلاحية الجلسة | 419 Page Expired'))
@section('code', '419')
@section('badge_text', '419 | انتهت صلاحية الجلسة')
@section('title_ar', 'انتهت صلاحية الصفحة بسبب فترة عدم النشاط')
@section('title_en', 'Page & Security Token Expired')
@section('description_ar', 'انتهت صلاحية رمز الأمان المؤقت لحماية حسابك من العمليات غير المصرح بها. ما عليك سوى النقر على زر التحديث أدناه والمتابعة بسهولة.')
@section('description_en', 'Your session token has timed out for security purposes. Please refresh the page to continue.')
