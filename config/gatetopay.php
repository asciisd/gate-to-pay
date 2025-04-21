<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gate To Pay API Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Gate To Pay API credentials. These values will
    | be used when connecting and sending requests to the Gate To Pay API.
    |
    */

    'api_key' => env('GATE_TO_PAY_API_KEY', ''),
    'username' => env('GATE_TO_PAY_USERNAME', ''),
    'password' => env('GATE_TO_PAY_PASSWORD', ''),
    'base_url' => env('GATE_TO_PAY_BASE_URL', 'https://tradetest.gatetopay.com'),
    'currency' => env('GATE_TO_PAY_CURRENCY', 'USD'),
    'customer_id' => env('GATE_TO_PAY_CUSTOMER_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the logging options for the package.
    |
    */

    'logging' => [
        'enabled' => true,
        'channel' => 'gatetopay',
    ],
];
