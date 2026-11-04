@extends('errors.layout')

@section('title', __('الوصول محظور | 403 Forbidden'))
@section('code', '403')
@section('badge_text', '403 | الوصول محظور')
@section('title_ar', 'ليس لديك صلاحية كافية للوصول إلى هذا القسم')
@section('title_en', 'Access Denied / Forbidden')
@section('description_ar', 'عذراً، هذا المسار محمي ومخصص فقط للمستخدمين المصرح لهم. إذا كنت تعتقد أن هذا خطأ، يرجى التحقق من تسجيل دخولك بالصلاحية المناسبة أو التواصل مع الدعم الفني.')
@section('description_en', 'You do not have permission to access this resource or administrative area.')
