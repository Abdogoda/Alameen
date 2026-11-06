<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailOtpRequest;
use App\Services\Auth\EmailVerificationOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EmailVerificationOtpController extends Controller
{
    public function __construct(
        protected EmailVerificationOtpService $otpService
    ) {}

    /**
     * Display the email verification prompt with OTP input form.
     */
    public function showVerificationPrompt(Request $request): View|RedirectResponse
    {
        if ($request->user()?->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        return view('web.auth.verify-email');
    }

    /**
     * Verify the user's email address using the submitted 6-digit OTP code.
     *
     * @throws ValidationException
     */
    public function verify(VerifyEmailOtpRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        $this->otpService->verifyOtp($user, (string) $request->validated('otp'));

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1')
            ->with('status', __('تم تأكيد بريدك الإلكتروني بنجاح!'));
    }

    /**
     * Resend a fresh 6-digit verification OTP code to the authenticated user's email.
     */
    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            $this->otpService->sendOtp($user);

            return back()->with('status', 'verification-otp-sent');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }
}

