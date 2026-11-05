<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PublicController;
use App\Http\Controllers\Api\V1\Student\AssignmentController;
use App\Http\Controllers\Api\V1\Student\CertificateController;
use App\Http\Controllers\Api\V1\Student\CourseController;
use App\Http\Controllers\Api\V1\Student\DashboardController;
use App\Http\Controllers\Api\V1\Student\LessonController;
use App\Http\Controllers\Api\V1\Student\NotificationController;
use App\Http\Controllers\Api\V1\Student\SettingsController;
use App\Http\Controllers\Api\V1\Student\SupportTicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes (/api/v1/*)
|--------------------------------------------------------------------------
|
| Unified RESTful API for all Public, Authentication, and Student features.
| Shares 100% of business logic with Web Controllers and Livewire components
| via the Service Layer under app/Services/.
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // OpenAPI 3.0.3 Specification Endpoint
    Route::get('/openapi.json', [PublicController::class, 'openApiSpec'])->name('openapi');

    /*
    |--------------------------------------------------------------------------
    | 1. Public & Marketing Endpoints
    |--------------------------------------------------------------------------
    */
    Route::prefix('public')->name('public.')->group(function () {
        Route::get('/bootstrap', [PublicController::class, 'bootstrap'])->name('bootstrap');
        Route::get('/home', [PublicController::class, 'home'])->name('home');
        Route::get('/courses', [PublicController::class, 'courses'])->name('courses.index');
        Route::get('/courses/{slug}', [PublicController::class, 'courseShow'])->name('courses.show');
        Route::get('/reviews', [PublicController::class, 'reviews'])->name('reviews');
        Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
        Route::get('/seo/{pageKey}', [PublicController::class, 'seo'])->name('seo');
        Route::get('/certificates/verify/{code}', [PublicController::class, 'verifyCertificate'])
            ->middleware('throttle:certificate-verify')
            ->name('certificates.verify');
        Route::post('/locale', [PublicController::class, 'switchLocale'])->name('locale.switch');
    });

    /*
    |--------------------------------------------------------------------------
    | 2. Authentication & Account Recovery Endpoints
    |--------------------------------------------------------------------------
    */
    Route::prefix('auth')->name('auth.')->group(function () {
        // Guest / Public Auth Routes
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/two-factor-challenge', [AuthController::class, 'twoFactorChallenge'])->name('two-factor.login');

        // Social & Firebase Auth
        Route::post('/firebase', [AuthController::class, 'firebase'])
            ->middleware('throttle:firebase-auth')
            ->name('firebase');
        Route::get('/google/redirect', [AuthController::class, 'googleRedirect'])->name('google.redirect');
        Route::post('/google/token', [AuthController::class, 'googleTokenLogin'])
            ->middleware('throttle:firebase-auth')
            ->name('google.token');

        // WebAuthn Passkey Login
        Route::post('/passkeys/login-options', [AuthController::class, 'passkeyLoginOptions'])->name('passkeys.login-options');
        Route::post('/passkeys/login', [AuthController::class, 'passkeyLogin'])->name('passkeys.login');

        // Password Reset via 6-Digit Email OTP
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
            ->middleware('throttle:otp')
            ->name('password.forgot');
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])
            ->middleware('throttle:otp')
            ->name('password.verify-otp');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])
            ->middleware('throttle:otp')
            ->name('password.reset');

        // Authenticated Auth Routes
        Route::middleware(['auth:sanctum', 'student.approved'])->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
                ->middleware('throttle:6,1')
                ->name('verification.send');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | 3. Authenticated Student Workspace Endpoints
    |--------------------------------------------------------------------------
    */
    Route::prefix('student')
        ->name('student.')
        ->middleware(['auth:sanctum', 'student.approved'])
        ->group(function () {

            // Dashboard Overview
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->middleware('page.enabled:student_dashboard')
                ->name('dashboard');

            // Courses & Payment Receipts
            Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
            Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
            Route::post('/courses/{slug}/receipt', [CourseController::class, 'submitReceipt'])
                ->middleware('throttle:uploads')
                ->name('courses.receipt.submit');
            Route::get('/receipts', [CourseController::class, 'receipts'])->name('receipts.index');

            // Lessons, DRM Playback Tokens, Watch Progress & Anti-Piracy
            Route::get('/courses/{slug}/lessons/{lesson?}', [LessonController::class, 'show'])->name('lessons.show');
            Route::post('/courses/{slug}/lessons/{lesson}/playback-token', [LessonController::class, 'playbackToken'])
                ->middleware('throttle:playback-token')
                ->name('lessons.playback-token');
            Route::post('/courses/{slug}/lessons/{lesson}/progress', [LessonController::class, 'recordProgress'])
                ->middleware('throttle:video-progress')
                ->name('lessons.progress');
            Route::post('/security/violation', [LessonController::class, 'reportViolation'])->name('security.violation');

            // Clinical Tasks (Voice Assignments) & Grades
            Route::get('/assignments', [AssignmentController::class, 'index'])
                ->middleware('page.enabled:tasks')
                ->name('assignments.index');
            Route::get('/assignments/{task}', [AssignmentController::class, 'show'])
                ->middleware('page.enabled:tasks')
                ->name('assignments.show');
            Route::post('/assignments/{task}/submit', [AssignmentController::class, 'submit'])
                ->middleware(['page.enabled:tasks', 'throttle:uploads'])
                ->name('assignments.submit');
            Route::get('/grades', [AssignmentController::class, 'grades'])
                ->middleware('page.enabled:grades')
                ->name('grades.index');
            Route::get('/submissions/{submission}', [AssignmentController::class, 'submissionResult'])
                ->middleware('page.enabled:grades')
                ->name('submissions.show');

            // Certificates
            Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
            Route::get('/certificates/{code}', [CertificateController::class, 'show'])->name('certificates.show');

            // Notifications Inbox
            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [NotificationController::class, 'index'])->name('index');
                Route::get('/recent', [NotificationController::class, 'recent'])->name('recent');
                Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
                Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
                Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
            });

            // Support Tickets & Real-Time Polling
            Route::prefix('support-tickets')->name('support.')->group(function () {
                Route::get('/', [SupportTicketController::class, 'index'])->name('index');
                Route::post('/', [SupportTicketController::class, 'store'])
                    ->middleware('throttle:support-action')
                    ->name('store');
                Route::get('/{ticket}', [SupportTicketController::class, 'show'])->name('show');
                Route::post('/{ticket}/reply', [SupportTicketController::class, 'reply'])
                    ->middleware('throttle:support-action')
                    ->name('reply');
                Route::post('/{ticket}/close', [SupportTicketController::class, 'close'])
                    ->middleware('throttle:support-action')
                    ->name('close');
                Route::post('/{ticket}/reopen', [SupportTicketController::class, 'reopen'])
                    ->middleware('throttle:support-action')
                    ->name('reopen');
                Route::get('/{ticket}/poll', [SupportTicketController::class, 'poll'])
                    ->middleware('throttle:support-poll')
                    ->name('poll');
            });

            // Profile, Password, 2FA & Passkey Settings
            Route::prefix('settings')->name('settings.')->group(function () {
                Route::get('/profile', [SettingsController::class, 'profile'])->name('profile.show');
                Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
                Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('profile.put');
                Route::delete('/profile', [SettingsController::class, 'deleteAccount'])->name('profile.delete');

                Route::put('/password', [SettingsController::class, 'updatePassword'])->name('password.update');

                Route::get('/security', [SettingsController::class, 'securityOverview'])->name('security.overview');

                // Two-Factor Authentication (TOTP)
                Route::post('/two-factor/enable', [SettingsController::class, 'enableTwoFactor'])->name('two-factor.enable');
                Route::post('/two-factor/confirm', [SettingsController::class, 'confirmTwoFactor'])->name('two-factor.confirm');
                Route::delete('/two-factor', [SettingsController::class, 'disableTwoFactor'])->name('two-factor.disable');
                Route::get('/two-factor/recovery-codes', [SettingsController::class, 'recoveryCodes'])->name('two-factor.recovery-codes');
                Route::post('/two-factor/recovery-codes', [SettingsController::class, 'regenerateRecoveryCodes'])->name('two-factor.recovery-codes.regenerate');

                // WebAuthn Passkeys
                Route::get('/passkeys', [SettingsController::class, 'listPasskeys'])->name('passkeys.index');
                Route::post('/passkeys/options', [SettingsController::class, 'passkeyOptions'])->name('passkeys.options');
                Route::post('/passkeys', [SettingsController::class, 'registerPasskey'])->name('passkeys.store');
                Route::delete('/passkeys/{passkeyId}', [SettingsController::class, 'deletePasskey'])->name('passkeys.destroy');
            });
        });
});
