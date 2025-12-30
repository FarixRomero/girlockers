<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Izipay Mode
    |--------------------------------------------------------------------------
    |
    | This option controls whether the application is in test or production
    | mode. Available options: 'test', 'production'
    |
    */
    'mode' => env('IZIPAY_MODE', 'test'),

    /*
    |--------------------------------------------------------------------------
    | Test Credentials
    |--------------------------------------------------------------------------
    |
    | Credentials for testing with Izipay sandbox environment
    |
    */
    'test' => [
        'username' => env('IZIPAY_TEST_USERNAME'),
        'password' => env('IZIPAY_TEST_PASSWORD'),
        'public_key' => env('IZIPAY_TEST_PUBLIC_KEY'),
        'hmac_key' => env('IZIPAY_TEST_HMAC_KEY'),
        'api_url' => 'https://api.micuentaweb.pe',
    ],

    /*
    |--------------------------------------------------------------------------
    | Production Credentials
    |--------------------------------------------------------------------------
    |
    | Credentials for production environment
    |
    */
    'production' => [
        'username' => env('IZIPAY_PROD_USERNAME'),
        'password' => env('IZIPAY_PROD_PASSWORD'),
        'public_key' => env('IZIPAY_PROD_PUBLIC_KEY'),
        'hmac_key' => env('IZIPAY_PROD_HMAC_KEY'),
        'api_url' => 'https://api.micuentaweb.pe',
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | URL where Izipay will send IPN notifications
    |
    */
    'webhook_url' => env('IZIPAY_WEBHOOK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Default currency and timeout for payment requests
    |
    */
    'currency' => 'PEN',
    'timeout' => 30,

    /*
    |--------------------------------------------------------------------------
    | Callback URLs
    |--------------------------------------------------------------------------
    |
    | URLs for payment success and error callbacks
    |
    */
    'success_url' => env('APP_URL') . '/payment/callback/success',
    'error_url' => env('APP_URL') . '/payment/callback/error',
];
