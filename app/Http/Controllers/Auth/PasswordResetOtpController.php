<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordWithOtpRequest;
use App\Http\Requests\Auth\SendPasswordResetOtpRequest;
use App\Services\Auth\PasswordResetOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetOtpController extends Controller
{
    public function __construct(
        protected PasswordResetOtpService $otpService
    ) {}

    /**
     * Show form to request password reset OTP code.
     */
    public function showForgotForm(): View
    {
        return view('web.auth.forgot-password');
    }

    /**
     * Send password reset OTP code to the given email address.
     */
    public function sendOtp(SendPasswordResetOtpRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $email = (string) $validated['email'];

        $this->otpService->sendOtp($email);

        return redirect()->route('password.reset', ['email' => $email])
            ->with('status', __('auth.otp_sent'));
    }

    /**
     * Display the password reset form with OTP input.
     */
    public function showResetForm(Request $request, ?string $token = null): View
    {
        return view('web.auth.reset-password', [
            'email' => $request->query('email', old('email')),
            'otp' => $request->query('otp', $token ?? old('otp')),
        ]);
    }

    /**
     * Reset the user password using OTP.
     */
    public function reset(ResetPasswordWithOtpRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $email = (string) $validated['email'];
        $otp = (string) ($validated['otp'] ?? $validated['token'] ?? '');
        $password = (string) $validated['password'];

        $this->otpService->resetPassword($email, $otp, $password);

        return redirect()->route('login')
            ->with('status', __('auth.password_reset_success'));
    }
}
