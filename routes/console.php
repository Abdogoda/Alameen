<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Artisan::call('courses:expire-subscriptions');
})->hourly()->name('courses:expire-subscriptions')->description('Scan time-bound course subscriptions and mark expired ones as expired');
