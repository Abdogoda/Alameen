<?php

namespace App\Providers;

require_once __DIR__.'/../helpers.php';

use App\Models\Admin;
use App\Models\ContentBlock;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Services\Cms\SettingsService;
use App\Services\Localization\PhpFileLoader;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Rules\Password;
use Opcodes\LogViewer\Facades\LogViewer;
use Throwable;

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
        $this->configureMailTheming();
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
                        $siteLogoWhite = $settingsService->getLogoWhiteUrl();
                        $footerLogo = $settingsService->getFooterLogoUrl();
                        $authLoginImage = $settingsService->getAuthLoginImageUrl();
                        $siteTagline = $settingsService->getSiteTagline();
                        $currencySymbol = $settingsService->getCurrencySymbol();
                        $currencyName = $settingsService->getCurrencyName();

                        if (! empty($siteName)) {
                            config(['app.name' => $siteName]);
                        }

                        $view->with([
                            'siteName' => $siteName,
                            'siteLogo' => $siteLogo,
                            'siteLogoWhite' => $siteLogoWhite,
                            'footerLogo' => $footerLogo,
                            'authLoginImage' => $authLoginImage,
                            'siteTagline' => $siteTagline,
                            'currencySymbol' => $currencySymbol,
                            'currencyName' => $currencyName,
                            'cmsSettings' => $settingsService,
                        ]);
                    } catch (Throwable) {
                        // Ignore view rendering issues
                    }
                });

                // Also initialize at boot time for config('app.name') and early global share
                $settingsService = app(SettingsService::class);
                $siteName = $settingsService->getSiteName();
                $siteLogo = $settingsService->getLogoUrl();
                $siteLogoWhite = $settingsService->getLogoWhiteUrl();
                $footerLogo = $settingsService->getFooterLogoUrl();
                $authLoginImage = $settingsService->getAuthLoginImageUrl();
                $siteTagline = $settingsService->getSiteTagline();
                $currencySymbol = $settingsService->getCurrencySymbol();
                $currencyName = $settingsService->getCurrencyName();

                if (! empty($siteName)) {
                    config(['app.name' => $siteName]);
                }

                View::share('siteName', $siteName);
                View::share('siteLogo', $siteLogo);
                View::share('siteLogoWhite', $siteLogoWhite);
                View::share('footerLogo', $footerLogo);
                View::share('authLoginImage', $authLoginImage);
                View::share('siteTagline', $siteTagline);
                View::share('currencySymbol', $currencySymbol);
                View::share('currencyName', $currencyName);
                View::share('cmsSettings', $settingsService);

                // Auto-sync dynamic media defaults for Hero and Slide 2 if legacy values are present
                if (Schema::hasTable('content_blocks')) {
                    $heroBlock = ContentBlock::where('section_key', 'hero')->first();
                    if ($heroBlock) {
                        $meta = $heroBlock->metadata ?? [];
                        $imgs = $meta['images'] ?? [];
                        if (empty($imgs) || (isset($imgs[0]) && $imgs[0] === '/assets/images/alameen-images/6.jpeg')) {
                            $meta['images'] = [
                                0 => '/assets/images/alameen-images/7.jpeg',
                                1 => '/assets/images/alameen-images/1.mp4',
                                2 => '/assets/images/alameen-images/2.mp4',
                                3 => '/assets/images/alameen-images/8.jpeg',
                            ];
                            $heroBlock->metadata = $meta;
                            $heroBlock->save();
                        }
                    }

                    $feedbackBlock = ContentBlock::where('section_key', 'feedback')->first();
                    if ($feedbackBlock) {
                        $meta = $feedbackBlock->metadata ?? [];
                        $imgs = $meta['images'] ?? [];
                        if (empty($imgs) || ! isset($imgs[4]) || $imgs[4] !== '/assets/images/alameen-images/9.jpeg' || ! isset($imgs[7]) || $imgs[7] !== '/assets/images/alameen-images/certificate.png') {
                            $meta['images'] = [
                                0 => '/assets/images/alameen-images/6.jpeg',
                                1 => '/assets/images/alameen-images/1.jpeg',
                                2 => '/assets/images/alameen-images/2.jpeg',
                                3 => '/assets/images/alameen-images/3.jpeg',
                                4 => '/assets/images/alameen-images/9.jpeg',
                                5 => '/assets/images/alameen-images/4.jpeg',
                                6 => '/assets/images/alameen-images/5.jpeg',
                                7 => '/assets/images/alameen-images/certificate.png',
                                8 => '/assets/images/alameen-images/12.jpeg',
                            ];
                            $feedbackBlock->metadata = $meta;
                            $feedbackBlock->save();
                        }
                    }

                    // Auto-sync auth artwork to 13.jpeg
                    $authImgSetting = Setting::where('key', 'auth_login_image')->first();
                    if ($authImgSetting && (str_contains((string) $authImgSetting->value_ar, '6.jpeg') || str_contains((string) $authImgSetting->value_ar, 'auth-sculpture'))) {
                        $authImgSetting->update([
                            'value_ar' => '/assets/images/alameen-images/13.jpeg',
                            'value_en' => '/assets/images/alameen-images/13.jpeg',
                        ]);
                    }
                }

                // Auto-sync real Admins & purge dummy users
                if (Schema::hasTable('roles') && Schema::hasTable('admins')) {
                    $superAdminRole = Role::where('slug', 'super_admin')->first();
                    $superRoleId = $superAdminRole?->id;

                    // 1. Dr. Hana El-Mekawy as Owner
                    Admin::query()->updateOrCreate(
                        ['email' => 'hm2991479@gmail.com'],
                        [
                            'name' => 'د. هنا المكاوي',
                            'password' => Hash::make('password'),
                            'role_id' => $superRoleId,
                            'permissions' => ['*'],
                            'locale' => 'ar',
                        ]
                    );

                    // 2. Abdo Goda with All Permissions
                    Admin::query()->updateOrCreate(
                        ['email' => 'abdogoda0a@gmail.com'],
                        [
                            'name' => 'Abdo Goda',
                            'password' => Hash::make('password'),
                            'role_id' => $superRoleId,
                            'permissions' => ['*'],
                            'locale' => 'ar',
                        ]
                    );

                    // Purge dummy demo admins
                    Admin::whereIn('email', [
                        'supervisor@alameen.edu.sa',
                        'instructor@alameen.edu.sa',
                        'finance@alameen.edu.sa',
                        'support@alameen.edu.sa',
                    ])->delete();
                }

                // Purge all dummy seeded users so the application is used directly with real students
                if (Schema::hasTable('users')) {
                    User::whereIn('email', [
                        'student@alameen.edu.sa',
                        'maryam@alameen.edu.sa',
                        'omar@alameen.edu.sa',
                        'fatima@alameen.edu.sa',
                        'youssef@alameen.edu.sa',
                        'test@example.com',
                    ])->delete();
                }
            }
        } catch (Throwable) {
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

            return Limit::perMinute(5)->by($key)->response(function (Request $request) {
                $message = __('لقد قمت بمحاولات عديدة في وقت قصير. يرجى الانتظار دقيقة قبل طلب رمز جديد.');
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['success' => false, 'message' => $message], 429);
                }

                return back()->with('error', $message);
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

            return Limit::perMinute(10)->by($key)->response(function (Request $request) {
                $message = __('لقد قمت برفع ملفات متعددة في وقت قصير. يرجى الانتظار دقيقة قبل المحاولة مجدداً.');
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['success' => false, 'message' => $message], 429);
                }

                return back()->with('error', $message);
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
