<?php

namespace ASCIISD\GateToPay\Http;

use ASCIISD\GateToPay\Exceptions\GateToPayException;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiClient
{
    /**
     * The base URL for the API.
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * The API key.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * The API key header name.
     *
     * @var string
     */
    protected string $apiKeyHeaderName;

    /**
     * The logging channel.
     *
     * @var string
     */
    protected string $loggingChannel;

    /**
     * Whether logging is enabled.
     *
     * @var bool
     */
    protected bool $loggingEnabled;

    /**
     * The API client name for logging.
     *
     * @var string
     */
    protected string $clientName;

    /**
     * Create a new ApiClient instance.
     *
     * @param string $baseUrl
     * @param string $apiKey
     * @param string $apiKeyHeaderName
     * @param string $clientName
     * @return void
     */
    public function __construct(
        string $baseUrl,
        string $apiKey,
        string $apiKeyHeaderName = 'APIKEY',
        string $clientName = 'GateToPay API'
    )
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->apiKeyHeaderName = $apiKeyHeaderName;
        $this->clientName = $clientName;
        $this->loggingEnabled = config('gatetopay.logging.enabled', true);
        $this->loggingChannel = config('gatetopay.logging.channel', 'gatetopay');
    }

    /**
     * Make an HTTP request to the API.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $query
     * @param array $data
     * @param array $additionalHeaders
     * @return array
     * @throws GateToPayException
     */
    public function request(
        string $method,
        string $endpoint,
        array  $query = [],
        array  $data = [],
        array  $additionalHeaders = []
    ): array
    {
        try {
            // Build the request URL
            $url = $this->baseUrl.$endpoint;

            // Prepare headers
            $headers = array_merge([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                $this->apiKeyHeaderName => $this->apiKey,
            ], $additionalHeaders);

            // Log the API request if logging is enabled
            $this->logRequest($method, $url, $query, $data);

            // Make the HTTP request
            $response = Http::withHeaders($headers)
                ->$method($url, $method === 'GET' ? $query : $data);

            return $this->handleResponse($response);
        } catch (Exception $e) {
            $this->logError($e->getMessage(), $e->getTraceAsString());
            throw new GateToPayException(
                "Error communicating with {$this->clientName}: ".$e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Handle the API response.
     *
     * @param Response $response
     * @return array
     * @throws GateToPayException
     */
    protected function handleResponse(Response $response): array
    {
        // Log the API response if logging is enabled
        $this->logResponse($response->status(), $response->json() ?? $response->body());

        // Check if the response is successful
        if ($response->successful()) {
            return $response->json() ?? [];
        }

        // Log the API error if logging is enabled
        $errorMessage = $response->body();
        $this->logError($errorMessage);

        throw new GateToPayException("{$this->clientName} Error: ".$errorMessage);
    }

    /**
     * Log an API request.
     *
     * @param string $method
     * @param string $url
     * @param array $query
     * @param array $data
     * @return void
     */
    protected function logRequest(string $method, string $url, array $query, array $data): void
    {
        if ($this->loggingEnabled) {
            Log::channel($this->loggingChannel)
                ->info("{$this->clientName} Request", [
                    'method' => $method,
                    'url' => $url,
                    'query' => $this->sanitizeDataForLogging($query),
                    'data' => $this->sanitizeDataForLogging($data),
                ]);
        }
    }

    /**
     * Log an API response.
     *
     * @param int $status
     * @param mixed $body
     * @return void
     */
    protected function logResponse(int $status, $body): void
    {
        if ($this->loggingEnabled) {
            Log::channel($this->loggingChannel)
                ->info("{$this->clientName} Response", [
                    'status' => $status,
                    'body' => $body,
                ]);
        }
    }

    /**
     * Log an API error.
     *
     * @param string $message
     * @param string|null $trace
     * @return void
     */
    protected function logError(string $message, ?string $trace = null): void
    {
        if ($this->loggingEnabled) {
            $logData = ['message' => $message];

            if ($trace) {
                $logData['trace'] = $trace;
            }

            Log::channel($this->loggingChannel)
                ->error("{$this->clientName} Error", $logData);
        }
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
