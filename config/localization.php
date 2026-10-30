<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default & Fallback Locales
    |--------------------------------------------------------------------------
    */
    'default' => env('APP_LOCALE', 'ar'),
    'fallback' => env('APP_FALLBACK_LOCALE', 'ar'),

    /*
    |--------------------------------------------------------------------------
    | Supported Locales & Metadata
    |--------------------------------------------------------------------------
    */
    'locales' => [
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'dir' => 'ltr',
            'flag' => '🇺🇸',
            'flag_svg' => '/assets/images/flags/us.svg',
            'code' => 'en',
            'regional' => 'en_US',
        ],
        'ar' => [
            'name' => 'Arabic',
            'native' => 'العربية',
            'dir' => 'rtl',
            'flag' => '🇪🇬',
            'flag_svg' => '/assets/images/flags/eg.svg',
            'code' => 'ar',
            'regional' => 'ar_EG',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Persistence Options
    |--------------------------------------------------------------------------
    */
    'cookie_name' => 'locale',
    'cookie_lifetime' => 60 * 24 * 365, // 1 year
    'session_key' => 'locale',
];
