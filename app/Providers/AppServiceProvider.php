<?php

namespace App\Providers;

require_once __DIR__.'/../helpers.php';

use App\Models\Admin;
use App\Services\Cms\SettingsService;
use App\Services\Localization\PhpFileLoader;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Rules\Password;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend('translation.loader', function ($loader, $app) {
            return new PhpFileLoader($app['files'], [$app['path.lang'], $app->langPath()]);
        });

        $this->app->extend('translator', function ($translator, $app) {
            $loader = $app['translation.loader'];
            $trans = new Translator($loader, $app->getLocale());
            $trans->setFallback($app->getFallbackLocale());

            return $trans;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureViewPaths();
        $this->configureAuthorization();
        $this->configureGlobalSettings();
        $this->configureRateLimiting();
    }

    /**
     * Share dynamic platform branding, logo, and settings with all views and runtime config.
     */
    protected function configureGlobalSettings(): void
    {
        try {
            if (Schema::hasTable('settings')) {
                // Reactive View Composer: evaluates settings per view render with active request locale
                View::composer('*', function ($view) {
                    try {
                        $settingsService = app(SettingsService::class);
                        $siteName = $settingsService->getSiteName();
                        $siteLogo = $settingsService->getLogoUrl();
                        $siteTagline = $settingsService->getSiteTagline();
                        $currencySymbol = $settingsService->getCurrencySymbol();
                        $currencyName = $settingsService->getCurrencyName();

                        if (! empty($siteName)) {
                            config(['app.name' => $siteName]);
                        }

                        $view->with([
                            'siteName' => $siteName,
                            'siteLogo' => $siteLogo,
                            'siteTagline' => $siteTagline,
                            'currencySymbol' => $currencySymbol,
                            'currencyName' => $currencyName,
                            'cmsSettings' => $settingsService,
                        ]);
                    } catch (\Throwable) {
                        // Ignore view rendering issues
                    }
                });

                // Also initialize at boot time for config('app.name') and early global share
                $settingsService = app(SettingsService::class);
                $siteName = $settingsService->getSiteName();
                $siteLogo = $settingsService->getLogoUrl();
                $siteTagline = $settingsService->getSiteTagline();
                $currencySymbol = $settingsService->getCurrencySymbol();
                $currencyName = $settingsService->getCurrencyName();

                if (! empty($siteName)) {
                    config(['app.name' => $siteName]);
                }

                View::share('siteName', $siteName);
                View::share('siteLogo', $siteLogo);
                View::share('siteTagline', $siteTagline);
                View::share('currencySymbol', $currencySymbol);
                View::share('currencyName', $currencyName);
                View::share('cmsSettings', $settingsService);
            }
        } catch (\Throwable) {
            // Gracefully ignore during installation or CLI migrations
        }
    }

    /**
     * Configure Gates and authorization logic for Admin guard and permissions.
     */
    protected function configureAuthorization(): void
    {
        Gate::before(function ($user, string $ability) {
            if ($user instanceof Admin) {
                if ($user->isSuperAdmin()) {
                    return true;
                }

                return $user->hasPermission($ability) ? true : null;
            }
        });

        Gate::define('viewLogViewer', function ($user = null) {
            $admin = auth('admin')->user() ?? ($user instanceof Admin ? $user : null);

            return $admin instanceof Admin;
        });

        LogViewer::auth(function ($request) {
            $admin = auth('admin')->user()
                ?? ($request->user('admin') instanceof Admin ? $request->user('admin') : null)
                ?? ($request->user() instanceof Admin ? $request->user() : null);

            return $admin instanceof Admin;
        });
    }

    /**
     * Configure isolated view paths and component namespaces for web and admin.
     */
    protected function configureViewPaths(): void
    {
        View::addNamespace('web', resource_path('views/web'));
        View::addNamespace('admin', resource_path('views/admin'));
        View::addLocation(resource_path('views/web'));
        View::addLocation(resource_path('views/admin'));

        Blade::anonymousComponentPath(resource_path('views/web/components'));
        Blade::anonymousComponentPath(resource_path('views/web/components'), 'components');
        Blade::anonymousComponentPath(resource_path('views/web/components'), 'web');
        Blade::anonymousComponentPath(resource_path('views/web/layouts'));
        Blade::anonymousComponentPath(resource_path('views/web/layouts'), 'layouts');
        Blade::anonymousComponentPath(resource_path('views/web/layouts'), 'web-layouts');
        Blade::anonymousComponentPath(resource_path('views/admin/layouts'), 'admin-layouts');
        Blade::anonymousComponentPath(resource_path('views/admin/layouts'), 'admin-layout');
        Blade::anonymousComponentPath(resource_path('views/admin/components'), 'admin');
        Blade::anonymousComponentPath(resource_path('views/admin/components'), 'admin-components');
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * Configure enterprise-grade, balanced rate limiters for security and abuse prevention.
     */
    protected function configureRateLimiting(): void
    {
        // 1. Password Reset OTP requests (protect SMS / Mailers from spam flooding - 5 req/min)
        RateLimiter::for('otp', function (Request $request) {
            $key = $request->input('email')
                ? Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip())
                : $request->ip();

            return Limit::perMinute(5)->by($key)->response(function () {
                return back()->with('error', __('لقد قمت بمحاولات عديدة في وقت قصير. يرجى الانتظار دقيقة قبل طلب رمز جديد.'));
            });
        });

        // 2. Firebase & Social Auth Endpoints (protect authentication services - 10 req/min)
        RateLimiter::for('firebase-auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip())->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => __('تجاوزت الحد المسموح لمحاولات المصادقة. يرجى الانتظار دقيقة والمحاولة مجدداً.'),
                ], 429);
            });
        });

        // 3. Admin Login & Authentication Tower (protect against brute-force attacks - 5 req/min)
        RateLimiter::for('admin-login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower((string) $request->input('email')).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey)->response(function () {
                return back()->with('error', __('تم إيقاف محاولات الدخول مؤقتاً بسبب المحاولات المتكررة. يرجى الانتظار دقيقة واحدة.'));
            });
        });

        // 4. Video Watch Progress Sync (Heartbeat - Normal playback sends ~6-12 req/min; limit is 30/min)
        RateLimiter::for('video-progress', function (Request $request) {
            $key = $request->user()?->id ? 'user:'.$request->user()->id : 'ip:'.$request->ip();

            return Limit::perMinute(30)->by($key)->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => __('تم تسجيل التقدم مؤخراً. يرجى الانتظار بضع ثوانٍ.'),
                ], 429);
            });
        });

        // 5. Signed Playback Token Generation (RSA cryptographic signing - 20 req/min)
        RateLimiter::for('playback-token', function (Request $request) {
            $key = $request->user()?->id ? 'user:'.$request->user()->id : 'ip:'.$request->ip();

            return Limit::perMinute(20)->by($key)->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => __('يرجى الانتظار قليلاً قبل طلب رمز تشغيل جديد.'),
                ], 429);
            });
        });

        // 6. File & Media Uploads (Receipts & Voice Assignments - 10 uploads/min)
        RateLimiter::for('uploads', function (Request $request) {
            $key = $request->user()?->id ? 'user:'.$request->user()->id : 'ip:'.$request->ip();

            return Limit::perMinute(10)->by($key)->response(function () {
                return back()->with('error', __('لقد قمت برفع ملفات متعددة في وقت قصير. يرجى الانتظار دقيقة قبل المحاولة مجدداً.'));
            });
        });

        // 7. Public Certificate Verification (Anti-scraping / Enumeration - 30 req/min)
        RateLimiter::for('certificate-verify', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip())->response(function () {
                abort(429, __('لقد تجاوزت عدد طلبات الفحص المسموحة بالدقيقة. يرجى الانتظار دقيقة والمحاولة مرة أخرى.'));
            });
        });

        // 8. Support Tickets & Student Messaging (15 actions/min, polling 60/min)
        RateLimiter::for('support-action', function (Request $request) {
            $key = $request->user()?->id ? 'user:'.$request->user()->id : 'ip:'.$request->ip();

            return Limit::perMinute(15)->by($key);
        });

        RateLimiter::for('support-poll', function (Request $request) {
            $key = $request->user()?->id ? 'user:'.$request->user()->id : 'ip:'.$request->ip();

            return Limit::perMinute(60)->by($key);
        });
    }
}
