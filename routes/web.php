<?php

use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], 'locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
