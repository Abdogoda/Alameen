<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\PasswordResetOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
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
    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $email = $request->string('email')->toString();

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
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'otp' => ['required_without:token', 'nullable', 'string', 'digits:6'],
            'token' => ['required_without:otp', 'nullable', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::default()],
            'password_confirmation' => ['required', 'string'],
        ], [
            'otp.required_without' => __('auth.enter_otp'),
            'otp.digits' => __('auth.invalid_otp'),
        ]);

        $email = $request->string('email')->toString();
        $otp = (string) ($request->input('otp') ?? $request->input('token'));
        $password = $request->string('password')->toString();

        $this->otpService->resetPassword($email, $otp, $password);

        return redirect()->route('login')
            ->with('status', __('auth.password_reset_success'));
    }
}
