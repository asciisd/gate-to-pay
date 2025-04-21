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

    'api_key' => '',
    'username' => '',
    'password' => '',
    'base_url' => 'https://tradetest.gatetopay.com',
    'currency' => 'USD',
    'customer_id' => '',

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
