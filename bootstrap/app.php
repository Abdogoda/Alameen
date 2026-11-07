<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureAdminHasPermission;
use App\Http\Middleware\EnsureCourseAccessValid;
use App\Http\Middleware\EnsureStudentIsApproved;
use App\Http\Middleware\EnsureStudentPageEnabled;
use App\Http\Middleware\SetCrossOriginHeaders;
use App\Http\Middleware\SetLocale;
use App\Services\Cms\SettingsService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        then: function () {
            Route::middleware('web')
                ->group(__DIR__.'/../routes/admin.php');
        },
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
            'auth/google/firebase',
            'auth/firebase',
        ]);

        $middleware->web(append: [
            SetCrossOriginHeaders::class,
            SetLocale::class,
            EnsureStudentIsApproved::class,
        ]);

        $middleware->api(append: [
            SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'admin.can' => EnsureAdminHasPermission::class,
            'course.access' => EnsureCourseAccessValid::class,
            'page.enabled' => EnsureStudentPageEnabled::class,
            'student.approved' => EnsureStudentIsApproved::class,
        ]);

        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin*') ? route('admin.login') : route('login'));
        $middleware->redirectUsersTo(fn (Request $request) => auth('admin')->check()
            ? route('admin.dashboard')
            : (app(SettingsService::class)->isPageEnabled('student_dashboard') ? route('dashboard') : route('courses.index'))
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
