<?php

namespace ASCIISD\GateToPay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use ASCIISD\GateToPay\Events\ProfileUpdated;
use ASCIISD\GateToPay\Events\WebhookReceived;
use ASCIISD\GateToPay\Helpers\SignatureService;

class WebhookController extends Controller
{
    /**
     * The signature service.
     *
     * @var SignatureService
     */
    protected $signatureService;

    /**
     * Create a new controller instance.
     *
     * @param SignatureService $signatureService
     * @return void
     */
    public function __construct(SignatureService $signatureService)
    {
        $this->signatureService = $signatureService;
    }

    /**
     * Handle the incoming webhook request from GateToPay.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        // Log the incoming webhook if debug is enabled
        if (config('gatetopay.debug', false)) {
            Log::channel(config('gatetopay.logging.channel', 'gatetopay'))
                ->info('GateToPay webhook received', [
                    'payload' => $this->sanitizeDataForLogging($request->all()),
                    'headers' => $request->headers->all(),
                ]);
        }

        // Validate the webhook signature if provided
        if ($request->header('X-Signature') && config('gatetopay.verify_webhook_signature', true)) {
            $payload = $request->getContent();
            $signature = $request->header('X-Signature');
            
            // Verify the signature
            if (!$this->verifySignature($payload, $signature)) {
                Log::channel(config('gatetopay.logging.channel', 'gatetopay'))
                    ->warning('GateToPay webhook signature verification failed');
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid signature'
                ], 403);
            }
        }

        // Process the webhook data
        $data = $request->all();
        
        // Validate required fields
        if (!isset($data['customerId'])) {
            return response()->json([
                'success' => false,
                'message' => 'Missing required field: customerId'
            ], 400);
        }

        // Get the action (created, updated, deleted) with default as 'updated'
        $action = $data['action'] ?? 'updated';
        
        // Add the action to the data if it's not already there
        if (!isset($data['action'])) {
            $data['action'] = $action;
        }

        // Dispatch a general webhook event
        event(new WebhookReceived($data, $action));
        
        // For backward compatibility, also dispatch the ProfileUpdated event
        event(new ProfileUpdated($data));

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully',
            'action' => $action
        ]);
    }

    /**
     * Verify the webhook signature.
     *
     * @param string $payload
     * @param string $signature
     * @return bool
     */
    protected function verifySignature($payload, $signature)
    {
        // Use the signature service to verify the signature
        $expectedSignature = $this->signatureService->generate($payload);
        
        return hash_equals($expectedSignature, $signature);
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
