<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminAuthController;
use App\Livewire\Admin\Notifications\NotificationManager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Central Control Tower Routes (Guard: admin)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    
    // -------------------------------------------------------------
    // Admin Guest Routes
    // -------------------------------------------------------------
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.store');
    });

    // -------------------------------------------------------------
    // Admin Authenticated Routes
    // -------------------------------------------------------------
    Route::middleware('admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::view('dashboard', 'admin.dashboard')->name('dashboard');

        // Curriculum, Levels & Video Management
        Route::view('courses', 'admin.courses.index')->name('courses.index');

        // Clinical Tasks Evaluation & Voice Grading
        Route::view('tasks', 'admin.tasks.index')->name('tasks.index');

        // Certificates Approval & Issue (95% standard)
        Route::view('certificates', 'admin.certificates.index')->name('certificates.index');

        // Bank Wire Receipts Verification
        Route::view('receipts', 'admin.receipts.index')->name('receipts.index');

        // Students & Interns Management
        Route::view('students', 'admin.students.index')->name('students.index');

        // System Settings & Parameters
        Route::view('settings', 'admin.settings.index')->name('settings.index');

        // Admin Notification Broadcasting
        Route::livewire('notifications', NotificationManager::class)->name('notifications.index');
    });
});
