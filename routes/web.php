<?php

use App\Http\Controllers\Auth\FirebaseAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\PasswordResetOtpController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Student\SupportTicketController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\Webhooks\MuxWebhookController;
use App\Livewire\Notifications\NotificationInbox;
use App\Models\User;
use App\Models\Video;
use App\Services\Video\VideoProgressService;
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
Route::get('courses', [StudentCourseController::class, 'index'])->name('courses.index');
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

    // Protect private lesson videos from unauthorized direct download
    if (str_starts_with($path, 'videos/lessons/')) {
        $user = auth()->user();
        $admin = auth('admin')->user();

        if (! $admin) {
            if (! $user instanceof User) {
                abort(403, __('تسجيل الدخول مطلوب للوصول إلى هذا المحتوى.'));
            }

            $video = Video::query()->where('video_url', 'like', '%'.$path.'%')->first();
            if ($video && ! app(VideoProgressService::class)->canAccessVideo($user, $video)) {
                abort(403, __('غير مصرح لك بالوصول إلى هذا الفيديو.'));
            }
        }
    }

    return response()->file($realFullPath);
})->where('path', '.*')->name('storage.fallback');

// -------------------------------------------------------------
// Customer / Student Authenticated Routes (Guard: web)
// -------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [StudentCourseController::class, 'dashboard'])->name('dashboard');

    // Secure Lesson Playback with Dynamic Watermark & Sequential Locking
    Route::get('lesson/{course?}/{video?}', [StudentCourseController::class, 'lesson'])->name('lessons.show');
    Route::post('videos/{video}/progress', [StudentCourseController::class, 'recordProgress'])->name('videos.progress');
    Route::post('courses/{course}/receipt', [StudentCourseController::class, 'submitReceipt'])->name('courses.receipt.submit');

    Route::get('assignment/{task?}', [StudentCourseController::class, 'assignment'])->name('assignments.index');
    Route::post('assignment/{task}/submit', [StudentCourseController::class, 'submitAssignment'])->name('assignments.submit');
    Route::get('certificates', fn () => redirect()->route('settings.certificates'))->name('certificates.index');
    Route::get('certificate/{certificate?}', fn () => redirect()->route('settings.certificates'))->name('certificates.show');
    Route::view('grades', 'web.pages.grades')->name('grades.index');
    Route::view('result', 'web.pages.result')->name('results.show');
    Route::view('user-profile', 'web.pages.profile')->name('user.profile');

    // Student Notifications Inbox
    Route::livewire('notifications', NotificationInbox::class)
        ->name('notifications.index');

    // Student Support Tickets & Interactive Messaging
    Route::get('support', [SupportTicketController::class, 'index'])->name('support.index');
    Route::get('support/create', [SupportTicketController::class, 'create'])->name('support.create');
    Route::post('support', [SupportTicketController::class, 'store'])->name('support.store');
    Route::get('support/{ticket}', [SupportTicketController::class, 'show'])->name('support.show');
    Route::post('support/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('support.reply');
    Route::post('support/{ticket}/close', [SupportTicketController::class, 'close'])->name('support.close');
    Route::post('support/{ticket}/reopen', [SupportTicketController::class, 'reopen'])->name('support.reopen');
    Route::get('support/{ticket}/poll', [SupportTicketController::class, 'poll'])->name('support.poll');
});

require __DIR__.'/settings.php';
