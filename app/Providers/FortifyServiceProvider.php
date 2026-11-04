<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Custom registration response: logout immediately and require admin approval
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse
            {
                public function toResponse($request)
                {
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->with('status', __(
                        'تم تسجيل حسابك بنجاح! حسابك حالياً قيد المراجعة وبانتظار موافقة إدارة المنصة. ستتمكن من تسجيل الدخول فور اعتماد الحساب.'
                    ));
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureAuthentication();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify authentication rules and pending approval enforcement.
     */
    private function configureAuthentication(): void
    {
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                if ($user->status === 'pending') {
                    throw ValidationException::withMessages([
                        Fortify::username() => [__('حسابك قيد المراجعة والموافقة من قِبل إدارة المنصة. يرجى الانتظار حتى يتم تفعيل الحساب لتتمكن من تسجيل الدخول.')],
                    ]);
                }

                if ($user->status === 'suspended') {
                    throw ValidationException::withMessages([
                        Fortify::username() => [__('تم إيقاف هذا الحساب. يرجى التواصل مع إدارة المنصة.')],
                    ]);
                }

                return $user;
            }

            return null;
        });
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn () => view('web.auth.login'));
        Fortify::verifyEmailView(fn () => view('web.auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('web.auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('web.auth.confirm-password'));
        Fortify::registerView(fn () => view('web.auth.register'));
        Fortify::resetPasswordView(fn () => view('web.auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn () => view('web.auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            $key = $request->hasSession() ? $request->session()->get('login.id') : null;

            return Limit::perMinute(5)->by(($key ?: 'api').'|'.$request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');
            $sessionKey = $request->hasSession() ? $request->session()->getId() : 'api';

            return Limit::perMinute(10)->by(
                ($credentialId ?: $sessionKey).'|'.$request->ip(),
            );
        });
    }
}
