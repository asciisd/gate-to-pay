<?php

namespace Tests\Feature;

it('has a config file', function () {
    // Access config through the application instance
    $config = app('config')->get('gatetopay');

    expect($config)->toBeArray();
    expect($config['api_key'])->toBe($_SERVER['GATE_TO_PAY_API_KEY'] ?? $_ENV['GATE_TO_PAY_API_KEY'] ?? 'test-api-key');
    expect($config['username'])->toBe($_SERVER['GATE_TO_PAY_USERNAME'] ?? $_ENV['GATE_TO_PAY_USERNAME'] ?? 'test-username');
    expect($config['password'])->toBe($_SERVER['GATE_TO_PAY_PASSWORD'] ?? $_ENV['GATE_TO_PAY_PASSWORD'] ?? 'test-password');
    expect($config['customer_id'])->toBe($_SERVER['GATE_TO_PAY_CUSTOMER_ID'] ?? $_ENV['GATE_TO_PAY_CUSTOMER_ID'] ?? 'test-customer-id');
});
