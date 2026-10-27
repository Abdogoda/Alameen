<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\FirebaseApiController;
use App\Http\Controllers\Api\Auth\PasskeyController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\TokenController;
use App\Http\Controllers\Api\Auth\TwoFactorController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Backward-compatible user endpoint
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Authentication & Security API Routes (v1)
|--------------------------------------------------------------------------
*/
Route::prefix('v1/auth')->group(function () {
    // Public routes (rate limited)
    Route::middleware('throttle:login')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/firebase', [FirebaseApiController::class, 'authenticate'])->name('api.v1.auth.firebase');
        Route::post('/2fa/challenge', [TwoFactorController::class, 'challenge']);
        Route::post('/password/forgot', [PasswordController::class, 'forgot']);
        Route::post('/password/reset', [PasswordController::class, 'reset']);
    });

    // Passkeys authentication (public)
    Route::prefix('passkeys')->middleware('throttle:passkeys')->group(function () {
        Route::get('/login/options', [PasskeyController::class, 'loginOptions']);
        Route::post('/login', [PasskeyController::class, 'login']);
    });

    // Email verification
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('api.verification.verify');

    // Authenticated security & management routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/password/update', [PasswordController::class, 'update']);
        Route::post('/email/resend', [EmailVerificationController::class, 'resend']);

        // Two-Factor Authentication Management
        Route::prefix('2fa')->group(function () {
            Route::post('/enable', [TwoFactorController::class, 'enable']);
            Route::post('/confirm', [TwoFactorController::class, 'confirm']);
            Route::delete('/disable', [TwoFactorController::class, 'disable']);
            Route::get('/recovery-codes', [TwoFactorController::class, 'recoveryCodes']);
            Route::post('/recovery-codes', [TwoFactorController::class, 'regenerateRecoveryCodes']);
        });

        // Passkeys Management
        Route::prefix('passkeys')->group(function () {
            Route::get('/', [PasskeyController::class, 'index']);
            Route::get('/register/options', [PasskeyController::class, 'registerOptions']);
            Route::post('/register', [PasskeyController::class, 'register']);
            Route::delete('/{id}', [PasskeyController::class, 'destroy']);
        });

        // Personal Access Token Management
        Route::prefix('tokens')->group(function () {
            Route::get('/', [TokenController::class, 'index']);
            Route::post('/', [TokenController::class, 'store']);
            Route::delete('/{id}', [TokenController::class, 'destroy']);
            Route::delete('/', [TokenController::class, 'destroyOthers']);
        });
    });
});

/*
|--------------------------------------------------------------------------
| Notification API Routes (v1)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // User Notification Endpoints
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
    });

    // Admin Notification Management Endpoints
    Route::prefix('admin/notifications')->middleware('admin')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\Admin\NotificationController::class, 'index']);
        Route::get('/stats', [App\Http\Controllers\Api\Admin\NotificationController::class, 'stats']);
        Route::post('/send', [App\Http\Controllers\Api\Admin\NotificationController::class, 'send']);
        Route::delete('/{id}', [App\Http\Controllers\Api\Admin\NotificationController::class, 'destroy']);
    });
});
