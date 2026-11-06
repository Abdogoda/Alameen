<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\StudentAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;

class GoogleAuthController extends Controller
{
    public function __construct(
        protected StudentAuthService $studentAuthService
    ) {}

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(): RedirectResponse
    {
        if (blank(config('services.google.client_id')) || blank(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('status', __('تسجيل الدخول عبر Google يتطلب ضبط بيانات الاعتماد في ملف الإعدادات (.env).'));
        }

        /** @var GoogleProvider $driver */
        $driver = Socialite::driver('google');

        return $driver->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            return redirect()->route('login')->withErrors([
                'email' => __('تم إلغاء عملية تسجيل الدخول عبر Google.'),
            ]);
        }

        try {
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors([
                'email' => __('تعذر الاتصال بـ Google لتسجيل الدخول: ').$e->getMessage(),
            ]);
        }

        try {
            $user = $this->studentAuthService->resolveOrCreateGoogleUser($googleUser);
        } catch (ValidationException $e) {
            return redirect()->route('login')->withErrors($e->errors());
        }

        if ($user->status === 'pending') {
            $msg = __('حسابك قيد المراجعة وبانتظار موافقة إدارة المنصة. يرجى الانتظار حتى يتم اعتماد الحساب لتتمكن من تسجيل الدخول.');
            return redirect()->route('login')
                ->with('error', $msg)
                ->withErrors(['email' => $msg]);
        }

        if ($user->status === 'suspended') {
            $msg = __('تم إيقاف هذا الحساب. يرجى التواصل مع إدارة المنصة.');
            return redirect()->route('login', ['suspended' => 1])
                ->with('error', $msg)
                ->withErrors(['email' => $msg]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
