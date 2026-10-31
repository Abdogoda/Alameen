<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Video Service Driver
    |--------------------------------------------------------------------------
    |
    | Supported drivers: "mux", "local", "bunny"
    | If the default driver (e.g. mux) is not configured or fails,
    | the manager automatically falls back to "local" server storage.
    |
    */
    'default' => env('VIDEO_DRIVER', 'mux'),

    /*
    |--------------------------------------------------------------------------
    | Video Service Drivers Configuration
    |--------------------------------------------------------------------------
    */
    'drivers' => [

        'mux' => [
            'token_id' => env('MUX_TOKEN_ID'),
            'token_secret' => env('MUX_TOKEN_SECRET'),
            'webhook_secret' => env('MUX_WEBHOOK_SECRET'),
            'base_url' => env('MUX_BASE_URL', 'https://api.mux.com/video/v1'),
            'playback_policy' => ['public'],
            'cors_origin' => env('MUX_CORS_ORIGIN', '*'),
        ],

        'local' => [
            'disk' => env('VIDEO_LOCAL_DISK', 'public'),
            'path' => 'videos/lessons',
        ],

        'bunny' => [
            'api_key' => env('BUNNY_API_KEY'),
            'library_id' => env('BUNNY_LIBRARY_ID'),
            'pull_zone' => env('BUNNY_PULL_ZONE'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Chunked Upload Settings
    |--------------------------------------------------------------------------
    */
    'chunking' => [
        'chunk_size' => 2 * 1024 * 1024, // 2MB
        'temp_disk' => 'local',
        'temp_path' => 'chunks',
        'max_file_size' => 2 * 1024 * 1024 * 1024, // 2GB
    ],

];
