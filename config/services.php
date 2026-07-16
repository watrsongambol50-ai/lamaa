<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Online Payment — Moyasar (moyasar.com)
    |--------------------------------------------------------------------------
    | Saudi payment gateway (mada / Visa / Mastercard / Apple Pay). Sign up at
    | moyasar.com, verify your business, then paste both keys from your dashboard
    | into .env. Leave blank to keep online payment disabled ("coming soon").
    */
    'moyasar' => [
        'publishable_key' => env('MOYASAR_PUBLISHABLE_KEY'),
        'secret_key'      => env('MOYASAR_SECRET_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS — Msegat (msegat.com)
    |--------------------------------------------------------------------------
    | Saudi SMS gateway. Sign up, buy an SMS package, create an API key, then
    | set SMS_ENABLED=true and fill the credentials below.
    */
    'sms' => [
        'enabled' => env('SMS_ENABLED', false),
        'msegat'  => [
            'username' => env('SMS_MSEGAT_USERNAME'),
            'api_key'  => env('SMS_MSEGAT_API_KEY'),
            'sender'   => env('SMS_MSEGAT_SENDER', 'LAMAA'),
        ],
    ],

];
