<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminFirebaseAuthController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\AdminPasskeyController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\ClinicalTaskController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StudentManagerController;
use App\Http\Controllers\Admin\SupportTicketController;
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
        Route::post('login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:admin-login')
            ->name('login.store');

        // Admin Google Authentication via Firebase
        Route::post('auth/google/firebase', [AdminFirebaseAuthController::class, 'authenticate'])
            ->middleware('throttle:firebase-auth')
            ->name('auth.google.firebase');
        Route::post('login/firebase', [AdminFirebaseAuthController::class, 'authenticate'])
            ->middleware('throttle:firebase-auth')
            ->name('login.firebase');

        // Admin Passkey Authentication (WebAuthn)
        Route::get('passkeys/login/options', [AdminPasskeyController::class, 'loginOptions'])->name('passkey.login-options');
        Route::post('passkeys/login', [AdminPasskeyController::class, 'login'])->name('passkey.login');
    });

    // -------------------------------------------------------------
    // Admin Authenticated Routes
    // -------------------------------------------------------------
    Route::middleware('admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::view('dashboard', 'admin.dashboard')->name('dashboard');

        // Curriculum, Levels & Video Management
        Route::middleware('admin.can:courses.view,courses.manage')->group(function () {
            Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
            Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');
        });

        Route::middleware('admin.can:courses.manage')->group(function () {
            Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
            Route::get('courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
            Route::put('courses/{course}', [CourseController::class, 'update'])->name('courses.update');
            Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
            Route::post('courses/{course}/price', [CourseController::class, 'updatePrice'])->name('courses.price.update');

            // Course Promo Video Chunked & Direct Upload
            Route::post('courses/{course}/promo/upload-chunk', [CourseController::class, 'uploadPromoChunk'])->name('courses.promo.chunk.upload');
            Route::post('courses/{course}/promo/mux-update', [CourseController::class, 'updatePromoMux'])->name('courses.promo.mux.update');
            Route::post('courses/{course}/promo/direct-upload-url', [CourseController::class, 'getMuxDirectUploadUrl'])->name('courses.promo.mux.direct_upload');
            Route::get('courses/{course}/promo/mux-upload-status/{uploadId}', [CourseController::class, 'checkMuxUploadStatus'])->name('courses.promo.mux.upload_status');

            // Dynamic Levels Hierarchy
            Route::post('courses/{course}/levels', [CourseController::class, 'storeLevel'])->name('courses.levels.store');
            Route::put('courses/{course}/levels/{level}', [CourseController::class, 'updateLevel'])->name('courses.levels.update');
            Route::delete('courses/{course}/levels/{level}', [CourseController::class, 'destroyLevel'])->name('courses.levels.destroy');

            // Video & Lessons Management with Resilient Driver & Chunked Upload
            Route::get('courses/{course}/lessons/create', [CourseController::class, 'createLesson'])->name('courses.videos.create');
            Route::get('courses/{course}/lessons/{video}/edit', [CourseController::class, 'editLesson'])->name('courses.videos.edit');
            Route::post('courses/{course}/lessons/upload-chunk', [CourseController::class, 'uploadLessonChunk'])->name('courses.videos.chunk.upload');
            Route::post('courses/{course}/lessons/direct-upload-url', [CourseController::class, 'getMuxDirectUploadUrl'])->name('courses.videos.mux.direct_upload');
            Route::get('courses/{course}/lessons/mux-upload-status/{uploadId}', [CourseController::class, 'checkMuxUploadStatus'])->name('courses.videos.mux.upload_status');
            Route::post('courses/{course}/videos', [CourseController::class, 'storeVideo'])->name('courses.videos.store');
            Route::post('courses/{course}/videos/bulk-settings', [CourseController::class, 'applyVideoSettings'])->name('courses.videos.settings.apply');
            Route::put('courses/{course}/videos/{video}', [CourseController::class, 'updateVideo'])->name('courses.videos.update');
            Route::delete('courses/{course}/videos/{video}', [CourseController::class, 'destroyVideo'])->name('courses.videos.destroy');
            Route::post('courses/{course}/videos/{video}/sync-mux', [CourseController::class, 'syncMuxVideo'])->name('courses.videos.mux.sync');

            // Mux Direct Upload Endpoint
            Route::post('mux/direct-upload', [CourseController::class, 'createMuxDirectUpload'])->name('mux.direct-upload');
        });

        Route::middleware('admin.can:courses.publish,courses.manage')->group(function () {
            Route::post('courses/{course}/toggle-auto-enroll', [CourseController::class, 'toggleAutoEnroll'])->name('courses.toggle-auto-enroll');
            Route::post('courses/{course}/levels/{level}/toggle-publish', [CourseController::class, 'togglePublishLevel'])->name('courses.levels.toggle-publish');
            Route::post('courses/{course}/videos/{video}/toggle-publish', [CourseController::class, 'togglePublishVideo'])->name('courses.videos.toggle-publish');
        });

        // Clinical Tasks Evaluation & Voice Grading
        Route::get('tasks', [ClinicalTaskController::class, 'index'])
            ->middleware('admin.can:tasks.view,tasks.grade')
            ->name('tasks.index');
        Route::post('tasks/{submission}/grade', [ClinicalTaskController::class, 'grade'])
            ->middleware('admin.can:tasks.grade')
            ->name('tasks.grade');

        // Certificates (Redirect to Students Directory)
        Route::get('certificates', function () {
            return redirect()->route('admin.students.index')->with('info', __('إدارة الشهادات متاحة حصرياً من خلال الملف الأكاديمي لكل طالب.'));
        })->middleware('admin.can:certificates.view,certificates.approve,students.view')->name('certificates.index');

        // Manual Payment Receipts Verification & Auto-Grant
        Route::get('receipts', [ReceiptController::class, 'index'])
            ->middleware('admin.can:receipts.view,receipts.verify,receipts.approve,receipts.reject')
            ->name('receipts.index');
        Route::post('receipts/{receipt}/approve', [ReceiptController::class, 'approve'])
            ->middleware('admin.can:receipts.approve')
            ->name('receipts.approve');
        Route::post('receipts/{receipt}/reject', [ReceiptController::class, 'reject'])
            ->middleware('admin.can:receipts.reject')
            ->name('receipts.reject');

        // Students & Interns Management
        Route::middleware('admin.can:students.view,students.manage')->group(function () {
            Route::get('students', [StudentManagerController::class, 'index'])->name('students.index');
            Route::get('students/{user}', [StudentManagerController::class, 'show'])->name('students.show');
        });

        Route::middleware('admin.can:students.manage')->group(function () {
            Route::put('students/{user}', [StudentManagerController::class, 'update'])->name('students.update');
            Route::put('students/{user}/password', [StudentManagerController::class, 'updatePassword'])->name('students.password.update');
            Route::post('students/{user}/toggle', [StudentManagerController::class, 'toggleStatus'])->name('students.toggle');
            Route::post('students/{user}/approve', [StudentManagerController::class, 'approve'])->name('students.approve');
            Route::post('students/{user}/verify', [StudentManagerController::class, 'verifyAccount'])->name('students.verify');
            Route::post('students/{user}/unverify', [StudentManagerController::class, 'unverifyAccount'])->name('students.unverify');
            Route::post('students/{user}/resend-verification', [StudentManagerController::class, 'resendVerificationEmail'])->name('students.resend-verification');
            Route::post('students/{user}/enroll', [StudentManagerController::class, 'enrollCourse'])->name('students.enroll');
            Route::put('students/{user}/enrollments/{course}', [StudentManagerController::class, 'updateEnrollment'])->name('students.enrollments.update');
            Route::delete('students/{user}/enrollments/{course}', [StudentManagerController::class, 'revokeEnrollment'])->name('students.enrollments.destroy');
            Route::delete('students/{user}', [StudentManagerController::class, 'destroy'])->name('students.destroy');
            Route::post('students/{user}/video-progress/{video}', [StudentManagerController::class, 'updateVideoProgress'])->name('students.video-progress.update');
        });

        Route::middleware('admin.can:certificates.approve,students.manage')->group(function () {
            Route::post('students/{user}/certificates', [StudentManagerController::class, 'issueCertificate'])->name('students.certificates.store');
            Route::delete('students/{user}/certificates/{certificate}', [StudentManagerController::class, 'destroyCertificate'])->name('students.certificates.destroy');
        });

        // Admin Profile & Security Settings
        Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

        // Admin Passkeys Management
        Route::get('passkeys/options', [AdminPasskeyController::class, 'registrationOptions'])->name('passkey.registration-options');
        Route::get('passkeys', [AdminPasskeyController::class, 'index'])->name('passkey.index');
        Route::post('passkeys', [AdminPasskeyController::class, 'store'])->name('passkey.store');
        Route::delete('passkeys/{passkey}', [AdminPasskeyController::class, 'destroy'])->name('passkey.destroy');

        // Staff & Roles Management
        Route::get('admins', [AdminManagementController::class, 'index'])
            ->middleware('admin.can:admins.view,admins.manage')
            ->name('admins.index');
        Route::middleware('admin.can:admins.manage')->group(function () {
            Route::get('admins/create', [AdminManagementController::class, 'create'])->name('admins.create');
            Route::post('admins', [AdminManagementController::class, 'store'])->name('admins.store');
            Route::get('admins/{admin}/edit', [AdminManagementController::class, 'edit'])->name('admins.edit');
            Route::put('admins/{admin}', [AdminManagementController::class, 'update'])->name('admins.update');
            Route::delete('admins/{admin}', [AdminManagementController::class, 'destroy'])->name('admins.destroy');
        });

        Route::resource('roles', RoleManagementController::class)
            ->except(['show'])
            ->middleware('admin.can:roles.manage');

        // Dynamic CMS, Menus & System Settings
        Route::middleware('admin.can:settings.manage')->group(function () {
            Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
            Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
            Route::post('settings/mux/test-connection', [SettingsController::class, 'testMuxConnection'])->name('settings.mux.test');
        });

        Route::middleware('admin.can:cms.manage,settings.manage')->group(function () {
            Route::post('settings/content/{sectionKey}', [SettingsController::class, 'updateContentBlock'])->name('settings.content.update');
        });

        Route::middleware('admin.can:menus.manage,settings.manage')->group(function () {
            Route::post('settings/menus/{menu}/items', [SettingsController::class, 'saveMenuItem'])->name('settings.menus.item');
            Route::post('settings/menus/items/{item}/toggle', [SettingsController::class, 'toggleMenuItem'])->name('settings.menus.item.toggle');
            Route::delete('settings/menus/items/{item}', [SettingsController::class, 'deleteMenuItem'])->name('settings.menus.item.delete');
        });

        // Support Tickets & Live Student Help Desk
        Route::middleware('admin.can:support.view,support.manage,inquiries.manage')->group(function () {
            Route::get('support', [SupportTicketController::class, 'index'])->name('support.index');
            Route::get('support/{ticket}', [SupportTicketController::class, 'show'])->name('support.show');
            Route::get('support/{ticket}/poll', [SupportTicketController::class, 'poll'])->name('support.poll');
        });

        Route::middleware('admin.can:support.manage,inquiries.manage')->group(function () {
            Route::post('support/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('support.reply');
            Route::post('support/{ticket}/status', [SupportTicketController::class, 'updateStatus'])->name('support.status.update');
            Route::post('support/{ticket}/priority', [SupportTicketController::class, 'updatePriority'])->name('support.priority.update');
            Route::post('support/{ticket}/assign', [SupportTicketController::class, 'assign'])->name('support.assign');
            Route::post('support/{ticket}/close', [SupportTicketController::class, 'close'])->name('support.close');
            Route::post('support/{ticket}/reopen', [SupportTicketController::class, 'reopen'])->name('support.reopen');
        });

        // Admin Notification Broadcasting
        Route::livewire('notifications', NotificationManager::class)
            ->middleware('admin.can:notifications.view,notifications.send,notifications.manage')
            ->name('notifications.index');

        // System Logs Viewer Shortcut
        Route::redirect('logs', '/admin/log-viewer')
            ->middleware('admin.can:logs.view,settings.manage')
            ->name('logs');
    });
});
