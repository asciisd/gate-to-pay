<?php

namespace Tests\Feature;

use ASCIISD\GateToPay\Exceptions\GateToPayException;
use ASCIISD\GateToPay\Facades\GateToPay;
use Illuminate\Support\Facades\Http;

it('can get customer cards', function () {
    Http::fake([
        'tradetest.gatetopay.com/api/Brokers/GetCustomerCards*' => Http::response([
            'customerCards' => [
                [
                    'id' => 'card-123',
                    'cardNumber' => '5104 13 XX XXXX 1234',
                ],
            ],
            'isSuccess' => true,
            'errorCode' => '',
            'errorMessage' => '',
        ], 200),
    ]);

    $customerId = '6541321568'; // Sample customer ID
    $cards = GateToPay::getCustomerCards($customerId);

    expect($cards)->toBeArray()
        ->and($cards['customerCards'])->toBeArray()
        ->toHaveCount(1)
        ->and($cards['customerCards'][0]['id'])->toBe('card-123')
        ->and($cards['customerCards'][0]['cardNumber'])->toBe('5104 13 XX XXXX 1234')
        ->and($cards['isSuccess'])->toBeTrue();
});

it('can perform card cash out', function () {
    Http::fake([
        'tradetest.gatetopay.com/api/Brokers/CardCashOut*' => Http::response([
            'transactionId' => 'tx-123',
            'status' => 'completed',
            'isSuccess' => true,
            'errorCode' => '',
            'errorMessage' => '',
        ], 200),
    ]);

    $response = GateToPay::cardCashOut([
        'customerId' => '6541321568', // Sample customer ID
        'cardId' => 'card-123',
        'depositAmount' => 100.00,
        'cardExpiryDate' => '09/25',
    ]);

    expect($response['isSuccess'])->toBeTrue()
        ->and($response['transactionId'])->toBe('tx-123')
        ->and($response['status'])->toBe('completed');
});

it('can perform card cash in', function () {
    Http::fake([
        'tradetest.gatetopay.com/api/Brokers/CardCashIn*' => Http::response([
            'transactionId' => 'tx-456',
            'status' => 'completed',
            'isSuccess' => true,
            'errorCode' => '',
            'errorMessage' => '',
        ], 200),
    ]);

    $response = GateToPay::cardCashIn([
        'customerId' => '6541321568', // Sample customer ID
        'cardId' => 'card-123',
        'withdrawalAmount' => 50.00,
        'cardExpiryDate' => '09/25',
    ]);

    expect($response['isSuccess'])->toBeTrue()
        ->and($response['transactionId'])->toBe('tx-456')
        ->and($response['status'])->toBe('completed');
});

it('handles otp requirement for cash out', function () {
    Http::fake([
        'tradetest.gatetopay.com/api/Brokers/CardCashOut*' => Http::response([
            'isSuccess' => false,
            'otpRequired' => true,
            'errorCode' => '1001',
            'errorMessage' => 'OTP verification required',
        ], 200),
    ]);

    try {
        GateToPay::cardCashOut([
            'customerId' => '6541321568', // Sample customer ID
            'cardId' => 'card-123',
            'depositAmount' => 100.00,
            'cardExpiryDate' => '09/25',
        ]);

        // If we reach here, the test should fail
        $this->fail('Expected GateToPayException was not thrown');
    } catch (GateToPayException $e) {
        expect($e->getCode())->toBe(1001)
            ->and($e->getMessage())->toContain('OTP verification required');
    }
});

it('handles api errors', function () {
    Http::fake([
        'tradetest.gatetopay.com/api/Brokers/GetCustomerCards*' => Http::response([
            'isSuccess' => false,
            'errorCode' => '401',
            'errorMessage' => 'Invalid API key',
        ], 200),
    ]);

    try {
        GateToPay::getCustomerCards('6541321568'); // Sample customer ID

        // If we reach here, the test should fail
        $this->fail('Expected GateToPayException was not thrown');
    } catch (GateToPayException $e) {
        expect($e->getCode())->toBe(401)
            ->and($e->getMessage())->toContain('Invalid API key');
    }
});
