<?php

namespace Tests\Feature;

use ASCIISD\GateToPay\Exceptions\GateToPayException;
use ASCIISD\GateToPay\Facades\GateToPay;
use Illuminate\Support\Facades\Http;

it('can get customer cards', function () {
    Http::fake([
        'tradetest.gatetopay.com/api/Brokers/GetCustomerCards*' => Http::response([
            'data' => [
                [
                    'id' => 'card-123',
                    'cardType' => 'VISA',
                    'last4Digits' => '1234',
                ],
            ],
        ], 200),
    ]);

    $cards = GateToPay::getCustomerCards();

    expect($cards)->toBeArray()
        ->toHaveCount(1);

    expect($cards[0]['id'])->toBe('card-123');
    expect($cards[0]['cardType'])->toBe('VISA');
    expect($cards[0]['last4Digits'])->toBe('1234');
});

it('can perform card cash out', function () {
    Http::fake([
        'tradetest.gatetopay.com/api/Brokers/CardCashOut*' => Http::response([
            'success' => true,
            'transactionId' => 'tx-123',
            'status' => 'completed',
        ], 200),
    ]);

    $response = GateToPay::cardCashOut([
        'cardId' => 'card-123',
        'depositAmount' => 100.00,
        'cardExpiryDate' => '09/25',
    ]);

    expect($response['success'])->toBeTrue();
    expect($response['transactionId'])->toBe('tx-123');
    expect($response['status'])->toBe('completed');
});

it('can perform card cash in', function () {
    Http::fake([
        'tradetest.gatetopay.com/api/Brokers/CardCashIn*' => Http::response([
            'success' => true,
            'transactionId' => 'tx-456',
            'status' => 'completed',
        ], 200),
    ]);

    $response = GateToPay::cardCashIn([
        'cardId' => 'card-123',
        'withdrawalAmount' => 50.00,
        'cardExpiryDate' => '09/25',
    ]);

    expect($response['success'])->toBeTrue();
    expect($response['transactionId'])->toBe('tx-456');
    expect($response['status'])->toBe('completed');
});

it('handles otp requirement for cash out', function () {
    Http::fake([
        'tradetest.gatetopay.com/api/Brokers/CardCashOut*' => Http::response([
            'success' => false,
            'otpRequired' => true,
            'message' => 'OTP verification required',
        ], 200),
    ]);

    try {
        GateToPay::cardCashOut([
            'cardId' => 'card-123',
            'depositAmount' => 100.00,
            'cardExpiryDate' => '09/25',
        ]);

        // If we reach here, the test should fail
        $this->fail('Expected GateToPayException was not thrown');
    } catch (GateToPayException $e) {
        expect($e->getCode())->toBe(GateToPayException::OTP_REQUIRED);
        expect($e->getMessage())->toContain('OTP required for this transaction');
        expect($e->isOtpRequired())->toBeTrue();
    }
});

it('handles api errors', function () {
    Http::fake([
        'tradetest.gatetopay.com/api/Brokers/GetCustomerCards*' => Http::response([
            'success' => false,
            'message' => 'Invalid API key',
        ], 401),
    ]);

    try {
        GateToPay::getCustomerCards();

        // If we reach here, the test should fail
        $this->fail('Expected GateToPayException was not thrown');
    } catch (GateToPayException $e) {
        expect($e->getCode())->toBe(401);
        expect($e->getMessage())->toContain('Invalid API key');
    }
});
