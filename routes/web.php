<?php

use App\Http\Controllers\LocaleController;
use App\Livewire\Notifications\NotificationInbox;
use Illuminate\Support\Facades\Route;

// Language Switcher
Route::match(['get', 'post'], 'locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// -------------------------------------------------------------
// Public Web Pages (Marketing & Catalogue)
// -------------------------------------------------------------
Route::view('/', 'web.welcome')->name('home');
Route::view('courses', 'web.pages.courses')->name('courses.index');
Route::view('course-details', 'web.pages.course-details')->name('courses.show');
Route::view('reviews', 'web.pages.reviews')->name('reviews.index');
Route::view('contact', 'web.pages.contact')->name('contact');
Route::view('audit-report', 'web.pages.audit-report')->name('audit.report');

// -------------------------------------------------------------
// Customer / Student Authenticated Routes (Guard: web)
// -------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'web.dashboard')->name('dashboard');
    Route::view('lesson', 'web.pages.lesson')->name('lessons.show');
    Route::view('assignment', 'web.pages.assignment')->name('assignments.index');
    Route::view('certificates', 'web.pages.certificates')->name('certificates.index');
    Route::view('certificate', 'web.pages.certificate')->name('certificates.show');
    Route::view('grades', 'web.pages.grades')->name('grades.index');
    Route::view('result', 'web.pages.result')->name('results.show');
    Route::view('user-profile', 'web.pages.profile')->name('user.profile');

    // Student Notifications Inbox
    Route::livewire('notifications', NotificationInbox::class)
        ->name('notifications.index');
});

require __DIR__.'/settings.php';
