<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('videos:fix-sources', function () {
    $count = \App\Models\Video::query()
        ->where('video_source', 'mux')
        ->whereNull('mux_playback_id')
        ->where(function ($q) {
            $q->where('video_url', 'like', '%/storage/%')
                ->orWhere('video_url', 'like', 'videos/%');
        })
        ->update([
            'video_source' => 'local',
            'mux_upload_id' => null,
        ]);

    $this->info("Corrected {$count} video(s) from 'mux' to 'local'.");
})->purpose('Fix inconsistent video sources where local videos were marked as mux');

Schedule::call(function () {
    Artisan::call('courses:expire-subscriptions');
})->hourly()->name('courses:expire-subscriptions')->description('Scan time-bound course subscriptions and mark expired ones as expired');

Schedule::command('storage:clean-temp --hours=24')
    ->daily()
    ->name('storage:clean-temp')
    ->description('Prune abandoned video chunks and temporary merged files older than 24 hours');

Schedule::command('videos:fix-sources')
    ->daily()
    ->name('videos:fix-sources')
    ->description('Automatically normalize inconsistent video source flags in database');


