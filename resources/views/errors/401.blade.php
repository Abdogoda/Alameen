@extends('errors.layout')

@section('title', __('تسجيل الدخول مطلوب | 401 Unauthorized'))
@section('code', '401')
@section('badge_text', '401 | غير مصرح بالدخول')
@section('title_ar', 'يتطلب هذا القسم تسجيل الدخول أولاً')
@section('title_en', 'Authentication Required')
@section('description_ar', 'يرجى تسجيل الدخول بحسابك في مركز الأمين للتمكن من متابعة الدورات والمحتوى التعليمي الخاص بك.')
@section('description_en', 'Please sign in to your Alameen account to access your courses and private dashboard.')
