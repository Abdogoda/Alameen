<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminFirebaseAuthController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\ClinicalTaskController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StudentManagerController;
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

        // Admin Google Authentication via Firebase
        Route::post('auth/google/firebase', [AdminFirebaseAuthController::class, 'authenticate'])->name('auth.google.firebase');
        Route::post('login/firebase', [AdminFirebaseAuthController::class, 'authenticate'])->name('login.firebase');
    });

    // -------------------------------------------------------------
    // Admin Authenticated Routes
    // -------------------------------------------------------------
    Route::middleware('admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::view('dashboard', 'admin.dashboard')->name('dashboard');

        // Curriculum, Levels & Video Management
        Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
        Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');
        Route::get('courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::post('courses/{course}/price', [CourseController::class, 'updatePrice'])->name('courses.price.update');

        // Dynamic Levels Hierarchy
        Route::post('courses/{course}/levels', [CourseController::class, 'storeLevel'])->name('courses.levels.store');
        Route::put('courses/{course}/levels/{level}', [CourseController::class, 'updateLevel'])->name('courses.levels.update');
        Route::delete('courses/{course}/levels/{level}', [CourseController::class, 'destroyLevel'])->name('courses.levels.destroy');

        // Video & Lessons Management with Resilient Driver & Chunked Upload
        Route::get('courses/{course}/lessons/create', [CourseController::class, 'createLesson'])->name('courses.videos.create');
        Route::get('courses/{course}/lessons/{video}/edit', [CourseController::class, 'editLesson'])->name('courses.videos.edit');
        Route::post('courses/{course}/lessons/upload-chunk', [CourseController::class, 'uploadLessonChunk'])->name('courses.videos.chunk.upload');
        Route::post('courses/{course}/lessons/direct-upload-url', [CourseController::class, 'getMuxDirectUploadUrl'])->name('courses.videos.mux.direct_upload');
        Route::post('courses/{course}/videos', [CourseController::class, 'storeVideo'])->name('courses.videos.store');
        Route::post('courses/{course}/videos/bulk-settings', [CourseController::class, 'applyVideoSettings'])->name('courses.videos.settings.apply');
        Route::put('courses/{course}/videos/{video}', [CourseController::class, 'updateVideo'])->name('courses.videos.update');
        Route::delete('courses/{course}/videos/{video}', [CourseController::class, 'destroyVideo'])->name('courses.videos.destroy');

        // Mux Direct Upload Endpoint
        Route::post('mux/direct-upload', [CourseController::class, 'createMuxDirectUpload'])->name('mux.direct-upload');

        // Clinical Tasks Evaluation & Voice Grading
        Route::get('tasks', [ClinicalTaskController::class, 'index'])->name('tasks.index');
        Route::post('tasks/{submission}/grade', [ClinicalTaskController::class, 'grade'])->name('tasks.grade');

        // Certificates Approval & Issue (95% standard)
        Route::get('certificates', [CertificateController::class, 'index'])->name('certificates.index');

        // Manual Payment Receipts Verification & Auto-Grant
        Route::get('receipts', [ReceiptController::class, 'index'])->name('receipts.index');
        Route::post('receipts/{receipt}/approve', [ReceiptController::class, 'approve'])->name('receipts.approve');
        Route::post('receipts/{receipt}/reject', [ReceiptController::class, 'reject'])->name('receipts.reject');

        // Students & Interns Management
        Route::get('students', [StudentManagerController::class, 'index'])->name('students.index');
        Route::post('students/{user}/toggle', [StudentManagerController::class, 'toggleStatus'])->name('students.toggle');

        // Dynamic CMS, Menus & System Settings
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/menus/{menu}/items', [SettingsController::class, 'saveMenuItem'])->name('settings.menus.item');

        // Admin Notification Broadcasting
        Route::livewire('notifications', NotificationManager::class)->name('notifications.index');

        // System Logs Viewer Shortcut
        Route::redirect('logs', '/admin/log-viewer')->name('logs');
    });
});
