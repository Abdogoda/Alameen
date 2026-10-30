<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureCourseAccessValid;
use App\Http\Middleware\SetCrossOriginHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        then: function () {
            Route::middleware('web')
                ->group(__DIR__.'/../routes/admin.php');
        },
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            SetCrossOriginHeaders::class,
            SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'course.access' => EnsureCourseAccessValid::class,
        ]);

        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin*') ? route('admin.login') : route('login'));
        $middleware->redirectUsersTo(fn (Request $request) => auth('admin')->check() ? route('admin.dashboard') : route('dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->expectsJson(),
        );
    })->create();
