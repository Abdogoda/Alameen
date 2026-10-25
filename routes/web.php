<?php

use App\Http\Controllers\LocaleController;
use App\Livewire\Admin\Notifications\NotificationManager;
use App\Livewire\Notifications\NotificationInbox;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], 'locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Notifications Inbox
    Route::livewire('notifications', NotificationInbox::class)
        ->name('notifications.index');

    // Admin Notification Management
    Route::middleware('admin')->group(function () {
        Route::livewire('admin/notifications', NotificationManager::class)
            ->name('admin.notifications.index');
    });
});

require __DIR__.'/settings.php';
