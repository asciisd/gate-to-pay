<?php

namespace Tests;

use ASCIISD\GateToPay\GateToPayServiceProvider;
use ASCIISD\GateToPay\Helpers\SignatureService;
use Mockery;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the SignatureService
        $this->mockSignatureService();
    }
    
    /**
     * Mock the SignatureService to avoid hex2bin errors.
     */
    protected function mockSignatureService(): void
    {
        $mock = Mockery::mock(SignatureService::class);
        $mock->shouldReceive('generate')
            ->withAnyArgs()
            ->andReturn('mocked-signature-value');
            
        $this->app->instance(SignatureService::class, $mock);
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            GateToPayServiceProvider::class,
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
        // Setup configuration from phpunit.xml environment variables
        $app['config']->set('gatetopay.api_key', $_SERVER['GATE_TO_PAY_API_KEY'] ?? $_ENV['GATE_TO_PAY_API_KEY'] ?? 'test-api-key');
        $app['config']->set('gatetopay.username', $_SERVER['GATE_TO_PAY_USERNAME'] ?? $_ENV['GATE_TO_PAY_USERNAME'] ?? 'test-username');
        $app['config']->set('gatetopay.password', $_SERVER['GATE_TO_PAY_PASSWORD'] ?? $_ENV['GATE_TO_PAY_PASSWORD'] ?? 'test-password');
        $app['config']->set('gatetopay.base_url', $_SERVER['GATE_TO_PAY_BASE_URL'] ?? $_ENV['GATE_TO_PAY_BASE_URL'] ?? 'https://tradetest.gatetopay.com');
        $app['config']->set('gatetopay.currency', $_SERVER['GATE_TO_PAY_CURRENCY'] ?? $_ENV['GATE_TO_PAY_CURRENCY'] ?? 'USD');
        $app['config']->set('gatetopay.customer_id', $_SERVER['GATE_TO_PAY_CUSTOMER_ID'] ?? $_ENV['GATE_TO_PAY_CUSTOMER_ID'] ?? 'test-customer-id');
        $app['config']->set('gatetopay.logging.enabled', false);
    }
    
    /**
     * Define routes setup.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function defineRoutes($router)
    {
        // Define package routes for testing if needed
    }
    
    /**
     * Clean up the testing environment before the next test.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
