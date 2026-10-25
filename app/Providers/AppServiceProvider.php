<?php

namespace App\Providers;

use App\Services\Localization\PhpFileLoader;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Rules\Password;

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
    }

    /**
     * Configure Gates and authorization logic for Admin guard and permissions.
     */
    protected function configureAuthorization(): void
    {
        \Illuminate\Support\Facades\Gate::before(function ($user, string $ability) {
            if ($user instanceof \App\Models\Admin) {
                if ($user->isSuperAdmin()) {
                    return true;
                }

                return $user->hasPermission($ability) ? true : null;
            }
        });
    }

    /**
     * Configure isolated view paths and component namespaces for web and admin.
     */
    protected function configureViewPaths(): void
    {
        \Illuminate\Support\Facades\View::addNamespace('web', resource_path('views/web'));
        \Illuminate\Support\Facades\View::addNamespace('admin', resource_path('views/admin'));
        \Illuminate\Support\Facades\View::addLocation(resource_path('views/web'));
        \Illuminate\Support\Facades\View::addLocation(resource_path('views/admin'));

        \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('views/web/components'));
        \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('views/web/components'), 'components');
        \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('views/web/components'), 'web');
        \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('views/web/layouts'));
        \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('views/web/layouts'), 'layouts');
        \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('views/web/layouts'), 'web-layouts');
        \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('views/admin/layouts'), 'admin-layouts');
        \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('views/admin/layouts'), 'admin-layout');
        \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('views/admin/components'), 'admin');
        \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('views/admin/components'), 'admin-components');
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
