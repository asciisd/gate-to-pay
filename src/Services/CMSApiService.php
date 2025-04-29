<?php

namespace ASCIISD\GateToPay\Services;

use ASCIISD\GateToPay\Exceptions\GateToPayException;
use ASCIISD\GateToPay\Helpers\SignatureService;
use ASCIISD\GateToPay\Http\ApiClient;

class CMSApiService
{
    /**
     * The API client.
     *
     * @var ApiClient
     */
    protected ApiClient $apiClient;

    /**
     * The signature service.
     *
     * @var SignatureService
     */
    protected SignatureService $signatureService;

    /**
     * Create a new CMSApiService instance.
     *
     * @param SignatureService $signatureService
     * @return void
     */
    public function __construct(SignatureService $signatureService)
    {
        $this->signatureService = $signatureService;

        // Get API client for CMS API from the container
        $this->apiClient = app('gatetopay.api.cms');
    }

    /**
     * Create a new customer profile.
     *
     * @param array $params
     * @return array
     * @throws GateToPayException
     */
    public function createNewProfile(array $params): array
    {
        $params['customerId'] = $params['customerId'] ?? $this->generateCustomerId();

        // Validate required parameters
        $requiredParams = [
            'customerId', 'gender', 'firstName', 'lastName', 'birthDate',
            'nationalNumberOrPassport', 'cardType', 'nationality', 'address',
            'phoneNumber', 'nameOnCard', 'email'
        ];

        foreach ($requiredParams as $param) {
            if (empty($params[$param])) {
                throw new GateToPayException("Missing required parameter: {$param}");
            }
        }

        // Generate signature using customerId
        $signature = $this->signatureService->generate($params['customerId']);

        // Add signature to the payload
        $payload = $params;
        $payload['signature'] = $signature;

        // Make the API request
        $response = $this->apiClient->request('POST', '/api/Profile/CreateProfile', [], $payload);
        
        // Check if the response indicates that the customer ID already exists
        if (isset($response['isSuccess']) && $response['isSuccess'] === false && 
            isset($response['message']) && str_contains($response['message'], 'customer id already exist')) {
            throw new GateToPayException('Customer ID already exists: ' . $params['customerId'], 409);
        }
        
        return $response;
    }

    /**
     * Generate a unique customer ID.
     *
     * @return string
     */
    public function generateCustomerId(): string
    {
        // Get the current date in format YYMDD (Year, Month, Day)
        $datePrefix = date('ymd');

        // Generate a random 4-digit number
        $randomSuffix = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Combine to create a 10-digit customer ID
        return $datePrefix.$randomSuffix;
    }

    /**
     * Sanitize sensitive data for logging.
     *
     * @param array $data
     * @return array
     */
    protected function sanitizeDataForLogging(array $data): array
    {
        // Create a copy of the data to avoid modifying the original
        $sanitized = $data;

        // List of sensitive fields that should be masked
        $sensitiveFields = [
            'password',
            'cardNumber',
            'cvv',
            'pin',
            'otp',
        ];

        // Mask sensitive fields
        foreach ($sensitiveFields as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = '********';
            }
        }

        return $sanitized;
    }
}
