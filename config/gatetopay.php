<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gate To Pay Trade API Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Gate To Pay Trade API credentials. These values will
    | be used when connecting and sending requests to the Gate To Pay Trade API.
    |
    */

    'trade_api' => [
        'api_key' => env('GATE_TO_PAY_TRADE_API_KEY', env('GATE_TO_PAY_API_KEY')),
        'username' => env('GATE_TO_PAY_TRADE_USERNAME', env('GATE_TO_PAY_USERNAME')),
        'password' => env('GATE_TO_PAY_TRADE_PASSWORD', env('GATE_TO_PAY_PASSWORD')),
        'base_url' => env('GATE_TO_PAY_TRADE_BASE_URL', 'https://tradetest.gatetopay.com'),
        'currency' => env('GATE_TO_PAY_TRADE_CURRENCY', 'USD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Gate To Pay CMS Open API Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Gate To Pay CMS Open API credentials. These values will
    | be used when connecting and sending requests to the Gate To Pay CMS Open API.
    |
    */

    'cms_api' => [
        'api_key' => env('GATE_TO_PAY_CMS_API_KEY'),
        'base_url' => env('GATE_TO_PAY_CMS_BASE_URL', 'https://cmsopenapitest.gatetopay.com'),
    ],

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
