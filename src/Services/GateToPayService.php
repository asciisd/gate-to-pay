<?php

namespace ASCIISD\GateToPay\Services;

use ASCIISD\GateToPay\Exceptions\GateToPayException;
use ASCIISD\GateToPay\Helpers\SignatureService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GateToPayService
{
    /**
     * The base URL for the API.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The username.
     *
     * @var string
     */
    protected $username;

    /**
     * The password.
     *
     * @var string
     */
    protected $password;

    /**
     * The currency.
     *
     * @var string
     */
    protected $currency;

    /**
     * The signature service.
     *
     * @var \ASCIISD\GateToPay\Helpers\SignatureService
     */
    protected $signatureService;

    /**
     * Create a new GateToPayService instance.
     *
     * @param string $baseUrl
     * @param string $apiKey
     * @param string $username
     * @param string $password
     * @param string $currency
     * @param \ASCIISD\GateToPay\Helpers\SignatureService $signatureService
     * @return void
     */
    public function __construct(
        string           $baseUrl,
        string           $apiKey,
        string           $username,
        string           $password,
        string           $currency,
        SignatureService $signatureService
    )
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->username = $username;
        $this->password = $password;
        $this->currency = $currency;
        $this->signatureService = $signatureService;
    }

    /**
     * Get the customer's cards.
     *
     * @param string $customerId
     * @return array
     * @throws \ASCIISD\GateToPay\Exceptions\GateToPayException
     */
    public function getCustomerCards(string $customerId): array
    {
        $signature = $this->signatureService->generate($customerId);

        return $this->makeRequest('GET', '/api/Brokers/GetCustomerCards', [
            'customerId' => $customerId,
            'signature' => $signature,
        ]);
    }

    /**
     * Perform a card cash out transaction.
     *
     * @param array $params
     * @return array
     * @throws \ASCIISD\GateToPay\Exceptions\GateToPayException
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

        $response = $this->makeRequest('POST', '/api/Brokers/CardCashOut', [], $payload);

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
     * @throws \ASCIISD\GateToPay\Exceptions\GateToPayException
     */
    public function cardCashIn(array $params): array
    {
        if (! isset($params['customerId'])) {
            throw new GateToPayException('Customer ID is required for card cash in');
        }

        $customerId = $params['customerId'];
        $cardId = $params['cardId'] ?? null;
        $withdrawalAmount = $params['withdrawalAmount'] ?? null;
        $currency = $params['currency'] ?? $this->currency;
        $transactionId = $params['transactionId'] ?? (string)Str::uuid();
        $cardExpiryDate = $params['cardExpiryDate'] ?? null;

        if (! $cardId || ! $withdrawalAmount || ! $cardExpiryDate) {
            throw new GateToPayException('Missing required parameters for card cash in');
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

        return $this->makeRequest('POST', '/api/Brokers/CardCashIn', [], $payload);
    }

    /**
     * Make an HTTP request to the API.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $query
     * @param array $data
     * @return array
     * @throws \ASCIISD\GateToPay\Exceptions\GateToPayException
     */
    protected function makeRequest(string $method, string $endpoint, array $query = [], array $data = []): array
    {
        $url = $this->baseUrl.$endpoint;

        // Prepare the request
        $request = Http::withHeaders([
            'APIKEY' => $this->apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // Log the request
        if (config('gatetopay.logging.enabled', true)) {
            $logData = [
                'method' => $method,
                'url' => $url,
                'query' => $query,
                'data' => $this->sanitizeDataForLogging($data),
            ];

            Log::channel(config('gatetopay.logging.channel', 'gatetopay'))
                ->info('GateToPay API Request', $logData);
        }

        // Make the request
        try {
            $response = match (strtoupper($method)) {
                'GET' => $request->get($url, $query),
                'POST' => $request->post($url, $data),
                'PUT' => $request->put($url, $data),
                'DELETE' => $request->delete($url, $data),
                default => throw new GateToPayException("Unsupported HTTP method: {$method}"),
            };

            return $this->handleResponse($response);
        } catch (\Exception $e) {
            if (config('gatetopay.logging.enabled', true)) {
                Log::channel(config('gatetopay.logging.channel', 'gatetopay'))
                    ->error('GateToPay API Error', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
            }

            throw new GateToPayException(
                'Error communicating with GateToPay API: '.$e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Handle the API response.
     *
     * @param \Illuminate\Http\Client\Response $response
     * @return array
     * @throws \ASCIISD\GateToPay\Exceptions\GateToPayException
     */
    protected function handleResponse(Response $response): array
    {
        $body = $response->json() ?: [];

        // Log the response
        if (config('gatetopay.logging.enabled', true)) {
            Log::channel(config('gatetopay.logging.channel', 'gatetopay'))
                ->info('GateToPay API Response', [
                    'status' => $response->status(),
                    'body' => $this->sanitizeDataForLogging($body),
                ]);
        }

        if (! $response->successful()) {
            $errorMessage = $body['message'] ?? $response->body();
            throw new GateToPayException(
                'GateToPay API Error: '.$errorMessage,
                $response->status()
            );
        }

        // Check for API-level errors in the new response structure
        if (isset($body['isSuccess']) && $body['isSuccess'] === false) {
            $errorMessage = $body['errorMessage'] ?? 'Unknown API error';
            $errorCode = $body['errorCode'] ?? 0;

            throw new GateToPayException(
                'GateToPay API Error: '.$errorMessage,
                (int)$errorCode
            );
        }

        return $body;
    }

    /**
     * Build a query string from an array of parameters.
     *
     * @param array $query
     * @return string
     */
    protected function buildQueryString(array $query): string
    {
        if (empty($query)) {
            return '';
        }

        return '?'.http_build_query($query);
    }

    /**
     * Sanitize sensitive data for logging.
     *
     * @param array $data
     * @return array
     */
    protected function sanitizeDataForLogging(array $data): array
    {
        $sensitiveFields = [
            'password',
            'cardNumber',
            'cvv',
            'otp',
        ];

        $sanitized = $data;

        foreach ($sensitiveFields as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = '********';
            }
        }

        return $sanitized;
    }
}
