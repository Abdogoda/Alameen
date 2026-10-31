<?php

use App\Http\Controllers\Auth\FirebaseAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\PasswordResetOtpController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\Webhooks\MuxWebhookController;
use App\Livewire\Notifications\NotificationInbox;
use Illuminate\Support\Facades\Route;

// Language Switcher
Route::match(['get', 'post'], 'locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// -------------------------------------------------------------
// Google Social Authentication & Password Reset (Guest)
// -------------------------------------------------------------
Route::middleware('guest')->group(function () {
    // Socialite fallback routes
    Route::get('auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

    // Firebase Google Authentication routes
    Route::post('auth/google/firebase', [FirebaseAuthController::class, 'authenticate'])->name('auth.google.firebase');
    Route::post('auth/firebase', [FirebaseAuthController::class, 'authenticate'])->name('auth.firebase');

    // Password Reset via 6-Digit OTP
    Route::get('forgot-password', [PasswordResetOtpController::class, 'showForgotForm'])->name('password.request');
    Route::post('forgot-password', [PasswordResetOtpController::class, 'sendOtp'])->name('password.email');
    Route::get('reset-password/{token?}', [PasswordResetOtpController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [PasswordResetOtpController::class, 'reset'])->name('password.update');
});

// -------------------------------------------------------------
// Public Web Pages (Marketing & Catalogue)
// -------------------------------------------------------------
Route::view('/', 'web.welcome')->name('home');
Route::view('courses', 'web.pages.courses')->name('courses.index');
Route::view('course-details', 'web.pages.course-details')->name('courses.show');
Route::view('reviews', 'web.pages.reviews')->name('reviews.index');
Route::view('contact', 'web.pages.contact')->name('contact');
Route::view('audit-report', 'web.pages.audit-report')->name('audit.report');

// Public Certificate Verification
Route::get('verify-certificate/{code}', [StudentCourseController::class, 'verifyCertificate'])->name('certificates.verify');

// MUX Video Streaming Cloud Webhooks
Route::post('webhooks/mux', [MuxWebhookController::class, 'handle'])->name('webhooks.mux');

// Shortcut / fallback redirect for log-viewer to secure admin path
Route::redirect('log-viewer', '/admin/log-viewer');

// Resilient fallback streaming for storage files when symbolic link is missing (e.g. restricted shared hosting)
Route::get('storage/{path}', function (string $path) {
    $fullPath = storage_path('app/public/'.$path);
    $realStoragePath = realpath(storage_path('app/public'));
    $realFullPath = realpath($fullPath);

    if (! $realFullPath || ! $realStoragePath || ! str_starts_with($realFullPath, $realStoragePath) || ! file_exists($realFullPath)) {
        abort(404);
    }

    return response()->file($realFullPath);
})->where('path', '.*')->name('storage.fallback');

// -------------------------------------------------------------
// Customer / Student Authenticated Routes (Guard: web)
// -------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'web.dashboard')->name('dashboard');

    // Secure Lesson Playback with Dynamic Watermark & Sequential Locking
    Route::get('lesson/{course?}/{video?}', [StudentCourseController::class, 'lesson'])->name('lessons.show');
    Route::post('videos/{video}/progress', [StudentCourseController::class, 'recordProgress'])->name('videos.progress');
    Route::post('courses/{course}/receipt', [StudentCourseController::class, 'submitReceipt'])->name('courses.receipt.submit');

    Route::get('assignment/{task?}', [StudentCourseController::class, 'assignment'])->name('assignments.index');
    Route::post('assignment/{task}/submit', [StudentCourseController::class, 'submitAssignment'])->name('assignments.submit');
    Route::get('certificates', [StudentCourseController::class, 'certificates'])->name('certificates.index');
    Route::get('certificate/{certificate?}', [StudentCourseController::class, 'certificate'])->name('certificates.show');
    Route::view('grades', 'web.pages.grades')->name('grades.index');
    Route::view('result', 'web.pages.result')->name('results.show');
    Route::view('user-profile', 'web.pages.profile')->name('user.profile');

    // Student Notifications Inbox
    Route::livewire('notifications', NotificationInbox::class)
        ->name('notifications.index');
});

require __DIR__.'/settings.php';
