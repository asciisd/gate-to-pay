<?php

namespace ASCIISD\GateToPay\Services;

use ASCIISD\GateToPay\Exceptions\GateToPayException;
use ASCIISD\GateToPay\Exceptions\GateToPayMissingParamsException;
use ASCIISD\GateToPay\Helpers\SignatureService;
use ASCIISD\GateToPay\Http\ApiClient;
use Illuminate\Support\Str;

class GateToPayService
{
    /**
     * The API client.
     *
     * @var ApiClient
     */
    protected ApiClient $apiClient;

    /**
     * The username.
     *
     * @var string
     */
    protected string $username;

    /**
     * The password.
     *
     * @var string
     */
    protected string $password;

    /**
     * The currency.
     *
     * @var string
     */
    protected string $currency;

    /**
     * The signature service.
     *
     * @var SignatureService
     */
    protected SignatureService $signatureService;

    /**
     * Create a new GateToPayService instance.
     *
     * @param SignatureService $signatureService
     * @return void
     */
    public function __construct(SignatureService $signatureService)
    {
        $this->signatureService = $signatureService;

        // Set Trade API credentials
        $this->username = config('gatetopay.trade_api.username', '');
        $this->password = config('gatetopay.trade_api.password', '');
        $this->currency = config('gatetopay.trade_api.currency', 'USD');

        // Get API client for Trade API from the container
        $this->apiClient = app('gatetopay.api.trade');
    }

    /**
     * Get the customer's cards.
     *
     * @param string $customerId
     * @return array
     * @throws GateToPayException
     */
    public function getCustomerCards(string $customerId): array
    {
        // Validate customerId
        if (empty($customerId)) {
            throw new GateToPayException('Customer ID is required');
        }

        // Generate signature
        $signature = $this->signatureService->generate($customerId);

        // Make the request
        return $this->apiClient->request(
            'GET',
            '/api/Brokers/GetCustomerCards',
            [
                'customerId' => $customerId,
                'signature' => $signature,
            ]
        );
    }

    /**
     * Perform a card cash-out transaction.
     *
     * @param array $params
     * @return array
     * @throws GateToPayException
     */
    public function cardCashOut(array $params): array
    {
        if (! isset($params['customerId'])) {
            throw new GateToPayException('Customer ID is required for card cash out');
        }

        $customerId = $params['customerId'];
        $cardId = $params['cardId'] ?? null;
        $depositAmount = $params['depositAmount'] ?? null;
        $currency = $params['currency'] ?? $this->currency;
        $transactionId = $params['transactionId'] ?? (string)Str::uuid();
        $cardExpiryDate = $params['cardExpiryDate'] ?? null;
        $otp = $params['otp'] ?? null;

        if (! $cardId || ! $depositAmount || ! $cardExpiryDate) {
            throw new GateToPayException('Missing required parameters for card cash out');
        }

        $signatureData = $customerId.$cardId.$depositAmount.$currency.$transactionId.$cardExpiryDate;
        if ($otp) {
            $signatureData .= $otp;
        }

        $signature = $this->signatureService->generate($signatureData);

        $payload = [
            'customerId' => $customerId,
            'cardId' => $cardId,
            'depositAmount' => $depositAmount,
            'currency' => $currency,
            'transactionId' => $transactionId,
            'cardExpiryDate' => $cardExpiryDate,
            'signature' => $signature,
        ];

        if ($otp) {
            $payload['otp'] = $otp;
        }

        $response = $this->apiClient->request('POST', '/api/Brokers/CardCashOut', [], $payload);

        // Check if OTP is required but not provided
        if (isset($response['otpRequired']) && $response['otpRequired'] === true && ! $otp) {
            // Wait for OTP input or callback
            // This is a simplified implementation - in a real-world scenario,
            // you might want to return a response indicating OTP is required
            // and let the client handle the OTP input

            // For this example, we'll throw an exception with a specific code
            throw new GateToPayException(
                'OTP required for this transaction. Please provide OTP and retry.',
                1001
            );
        }

        return $response;
    }

    /**
     * Perform a card cash in transaction.
     *
     * @param array $params
     * @return array
     * @throws GateToPayException|GateToPayMissingParamsException
     */
    public function cardCashIn(array $params): array
    {
        if (! isset($params['customerId'])) {
            throw new GateToPayMissingParamsException('Customer ID is required for card cash in');
        }

        $customerId = $params['customerId'];
        $cardId = $params['cardId'] ?? null;
        $withdrawalAmount = $params['withdrawalAmount'] ?? null;
        $currency = $params['currency'] ?? $this->currency;
        $transactionId = $params['transactionId'] ?? (string)Str::uuid();
        $cardExpiryDate = $params['cardExpiryDate'] ?? null;

        if (! $cardId || ! $withdrawalAmount || ! $cardExpiryDate) {
            throw new GateToPayMissingParamsException('Missing required parameters for card cash in');
        }

        $signatureData = $customerId.$cardId.$withdrawalAmount.$currency.$transactionId.$cardExpiryDate;
        $signature = $this->signatureService->generate($signatureData);

        $payload = [
            'customerId' => $customerId,
            'cardId' => $cardId,
            'withdrawalAmount' => $withdrawalAmount,
            'currency' => $currency,
            'transactionId' => $transactionId,
            'cardExpiryDate' => $cardExpiryDate,
            'signature' => $signature,
        ];

        return $this->apiClient->request('POST', '/api/Brokers/CardCashIn', [], $payload);
    }

    /**
     * Create a new customer profile.
     *
     * This method is now moved to CMSApiService.
     * This is kept for backward compatibility.
     *
     * @param array $params
     * @return array
     * @throws GateToPayException
     */
    public function createNewProfile(array $params): array
    {
        return app(CMSApiService::class)->createNewProfile($params);
    }

    /**
     * Generate a unique customer ID.
     *
     * This method is now moved to CMSApiService.
     * This is kept for backward compatibility.
     *
     * @return string
     */
    public function generateCustomerId(): string
    {
        return app(CMSApiService::class)->generateCustomerId();
    }
}
