<?php

namespace App\Providers;

require_once __DIR__ . '/../helpers.php';

use App\Models\Admin;
use App\Services\Cms\SettingsService;
use App\Services\Localization\PhpFileLoader;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
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
}
