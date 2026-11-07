<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Admin;
use App\Services\Audit\AdminActivityLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;

class LogAdminAuthenticationEvents
{
    /**
     * Handle Login event.
     */
    public function handleLogin(Login $event): void
    {
        if ($event->guard === 'admin' || $event->user instanceof Admin) {
            AdminActivityLogger::logAuthentication(
                event: 'login',
                adminOrEmail: $event->user instanceof Admin ? $event->user : null,
                status: 'success'
            );
        }
    }

    /**
     * Handle Logout event.
     */
    public function handleLogout(Logout $event): void
    {
        if ($event->guard === 'admin' || $event->user instanceof Admin) {
            AdminActivityLogger::logAuthentication(
                event: 'logout',
                adminOrEmail: $event->user instanceof Admin ? $event->user : null,
                status: 'success'
            );
        }
    }

    /**
     * Handle Failed authentication attempt.
     */
    public function handleFailed(Failed $event): void
    {
        $isAdminGuard = $event->guard === 'admin';
        $isAdminRoute = request()->is('admin*') || str_starts_with((string) request()->route()?->getName(), 'admin.');

        if ($isAdminGuard || $isAdminRoute) {
            $email = $event->credentials['email'] ?? request()->input('email');

            AdminActivityLogger::logAuthentication(
                event: 'failed_login',
                adminOrEmail: is_string($email) ? $email : 'غير معروف',
                status: 'warning',
                reason: 'بيانات الاعتماد المدخلة غير صحيحة أو الحساب غير مصرح له'
            );
        }
    }

    /**
     * Handle Lockout event.
     */
    public function handleLockout(Lockout $event): void
    {
        $isAdminRoute = $event->request->is('admin*') || str_starts_with((string) $event->request->route()?->getName(), 'admin.');

        if ($isAdminRoute) {
            $email = $event->request->input('email');

            AdminActivityLogger::logAuthentication(
                event: 'lockout',
                adminOrEmail: is_string($email) ? $email : 'غير معروف',
                status: 'failed',
                reason: 'تجاوز عدد محاولات الدخول المسموح بها وتفعيل الحظر المؤقت'
            );
        }
    }

    /**
     * Register listeners for subscriber.
     *
     * @return array<class-string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
            Failed::class => 'handleFailed',
            Lockout::class => 'handleLockout',
        ];
    }
}
