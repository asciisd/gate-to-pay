<?php

namespace ASCIISD\GateToPay\Tests\Feature;

use ASCIISD\GateToPay\Exceptions\GateToPayException;
use ASCIISD\GateToPay\Facades\GateToPay;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class GateToPayServiceTest extends TestCase
{
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'ASCIISD\GateToPay\GateToPayServiceProvider',
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('gatetopay.api_key', '33a58c29-54ef-4858-8915-92fcae3a9103');
        $app['config']->set('gatetopay.username', 'CaveoUSD.test');
        $app['config']->set('gatetopay.password', 'P@ssw0rd');
        $app['config']->set('gatetopay.base_url', 'https://tradetest.gatetopay.com');
        $app['config']->set('gatetopay.currency', 'USD');
        $app['config']->set('gatetopay.customer_id', '6541321568');
        $app['config']->set('gatetopay.logging.enabled', false);
    }

    /** @test */
    public function it_can_get_customer_cards()
    {
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

        $this->assertIsArray($cards);
        $this->assertCount(1, $cards);
        $this->assertEquals('card-123', $cards[0]['id']);
        $this->assertEquals('VISA', $cards[0]['cardType']);
        $this->assertEquals('1234', $cards[0]['last4Digits']);
    }

    /** @test */
    public function it_can_perform_card_cash_out()
    {
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

        $this->assertTrue($response['success']);
        $this->assertEquals('tx-123', $response['transactionId']);
        $this->assertEquals('completed', $response['status']);
    }

    /** @test */
    public function it_can_perform_card_cash_in()
    {
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

        $this->assertTrue($response['success']);
        $this->assertEquals('tx-456', $response['transactionId']);
        $this->assertEquals('completed', $response['status']);
    }

    /** @test */
    public function it_handles_otp_requirement_for_cash_out()
    {
        Http::fake([
            'tradetest.gatetopay.com/api/Brokers/CardCashOut*' => Http::response([
                'success' => false,
                'otpRequired' => true,
                'message' => 'OTP verification required',
            ], 200),
        ]);

        $this->expectException(GateToPayException::class);
        $this->expectExceptionCode(GateToPayException::OTP_REQUIRED);

        GateToPay::cardCashOut([
            'cardId' => 'card-123',
            'depositAmount' => 100.00,
            'cardExpiryDate' => '09/25',
        ]);
    }

    /** @test */
    public function it_handles_api_errors()
    {
        Http::fake([
            'tradetest.gatetopay.com/api/Brokers/GetCustomerCards*' => Http::response([
                'success' => false,
                'message' => 'Invalid API key',
            ], 401),
        ]);

        $this->expectException(GateToPayException::class);
        $this->expectExceptionCode(401);

        GateToPay::getCustomerCards();
    }
}
