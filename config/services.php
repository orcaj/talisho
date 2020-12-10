<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'stripe' => [
        'model'  => App\User::class,
        'pk' => env('STRIPE_PUBLIC_KEY'),
        'sk' => env('STRIPE_SECRET_KEY'),
        'price_id' => env('STRIPE_PRICE_ID'),
        'trial_day' => env('STRIPE_TRIAL_DAY'),
        
        
        'test_hook' => env('STRIPE_TEST_WEB_HOOK'),
        'live_hook' => env('STRIPE_LIVE_WEB_HOOK'),
    ]

];
