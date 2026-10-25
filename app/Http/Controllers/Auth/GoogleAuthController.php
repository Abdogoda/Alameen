<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(): RedirectResponse
    {
        if (blank(config('services.google.client_id')) || blank(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('status', __('تسجيل الدخول عبر Google يتطلب ضبط بيانات الاعتماد في ملف الإعدادات (.env).'));
        }

        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
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
                'email' => __('تعذر الاتصال بـ Google لتسجيل الدخول: ') . $e->getMessage(),
            ]);
        }

        $email = $googleUser->getEmail();
        if (blank($email)) {
            return redirect()->route('login')->withErrors([
                'email' => __('لم يتم العثور على بريد إلكتروني مرتبط بحساب Google.'),
            ]);
        }

        // 1. Match by google_id first
        $user = User::where('google_id', $googleUser->getId())->first();

        // 2. If not found by google_id, match by email
        if (! $user) {
            $user = User::where('email', $email)->first();
        }

        if ($user) {
            // Update google_id or avatar if needed
            $updates = [];
            if (empty($user->google_id)) {
                $updates['google_id'] = $googleUser->getId();
            }
            if (empty($user->avatar) && $googleUser->getAvatar()) {
                $updates['avatar'] = $googleUser->getAvatar();
            }
            if (is_null($user->email_verified_at)) {
                $updates['email_verified_at'] = now();
            }
            if (! empty($updates)) {
                $user->forceFill($updates)->save();
            }
        } else {
            // Create a new student user
            $user = new User();
            $user->forceFill([
                'name' => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'طالب الأمين'),
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'locale' => app()->getLocale(),
                'password' => bcrypt(Str::random(32)),
            ]);
            $user->save();
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
