# GateToPay Webhook Integration

This document explains how to set up your application to receive webhook from GateToPay when customer profiles are
created, updated, or deleted.

## Overview

webhook allow GateToPay to send real-time notifications to your application when events occur in their system. This
integration enables you to:

- Receive notifications when customer profiles are created
- Get updates when customer profiles are modified
- Be notified when customer profiles are deleted
- Process these events in your application to keep your data in sync

## Setup Instructions

1. **Publish Configuration**

   Ensure you have published the GateToPay configuration:

   ```php
   php artisan vendor:publish --provider="ASCIISD\GateToPay\GateToPayServiceProvider" --tag="config"
   ```

2. **Configure Environment Variables**

   Add the following settings to your `.env` file:

   ```
   GATE_TO_PAY_WEBHOOK_PATH=gatetopay/webhook
   GATE_TO_PAY_VERIFY_WEBHOOK_SIGNATURE=true
   GATE_TO_PAY_DEBUG=false
   ```

3. **Register Event Listener**

   In your `app/Providers/EventServiceProvider.php` file, register the webhook event listener:

   ```php
   use App\Listeners\WebhookListener;
   use ASCIISD\GateToPay\Events\WebhookReceived;

   protected $listen = [
       // Other event listeners...
       
       WebhookReceived::class => [
           WebhookListener::class,
       ],
   ];
   ```

4. **Create Webhook Listener**

   Create a listener class in your application to handle the webhook events. You can use the provided example in
   `WebhookListener.php` as a starting point.

   ```php
   php artisan make:listener WebhookListener
   ```

5. **Configure GateToPay**

   Provide your webhook URL to GateToPay. The URL will be:

   ```
   https://your-domain.com/gatetopay/webhook
   ```

## Webhook Payload Format

The webhook payload should include the following fields:

| Field        | Required | Description                                                                                         |
|--------------|----------|-----------------------------------------------------------------------------------------------------|
| `customerId` | Yes      | The customer ID in GateToPay                                                                        |
| `action`     | No       | The action that occurred: 'created', 'updated', or 'deleted'. Defaults to 'updated' if not provided |
| `status`     | No       | Status of the operation (e.g., 'success', 'failed')                                                 |
| `data`       | No       | Additional data related to the profile                                                              |

### Example Payload

```json
{
    "customerId": "2304251234",
    "action": "created",
    "status": "success",
    "data": {
        "id": "6746783267",
        "cardNumber": "1234 XXXX XXXX 3345"
    }
}
```

## Handling Different Actions

Your webhook listener should handle different actions accordingly:

- **created**: A new customer profile has been created
- **updated**: An existing customer profile has been modified
- **deleted**: A customer profile has been removed

Example implementation:

```php
switch ($action) {
    case 'created':
        // Handle new profile creation
        break;
        
    case 'updated':
        // Handle profile updates
        break;
        
    case 'deleted':
        // Handle profile deletion
        break;
}
```

## Testing webhook

You can test your webhook integration using tools like [Postman](https://www.postman.com/) or [curl](https://curl.se/).

### Example curl command:

```bash
curl --location 'https://your-domain.com/gatetopay/webhook' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--header 'Signature: your-signature-here' \
--data '{
    "customerId": "2304251234",
    "action": "created",
    "status": "success",
    "data": {
        "id": "6377827323",
        "cardNumber": "1234 XXXX XXXX 9932"
    }
}'
```

## Security Considerations

- **Signature Verification**: The webhook endpoint is protected by signature verification if enabled in your
  configuration. This ensures that the webhook is genuinely from GateToPay.
- **HTTPS**: Always use HTTPS for secure communication.
- **Access Control**: Make sure your webhook URL is accessible from GateToPay's servers but protected from unauthorized
  access.
- **Idempotency**: Implement idempotent processing to handle potential duplicate webhook deliveries.

## Troubleshooting

- **Webhook Not Received**: Ensure your server is publicly accessible and the webhook URL is correctly configured in
  GateToPay.
- **Signature Verification Fails**: Check that you're using the correct API key for signature verification.
- **Event Not Processed**: Verify that your event listener is registered correctly and there are no errors in your
  processing logic.

If you enable debug mode (`GATE_TO_PAY_DEBUG=true`), all incoming webhook will be logged for easier troubleshooting.

## Additional Resources

- [Laravel Events Documentation](https://laravel.com/docs/events)
- [GateToPay API Documentation](https://docs.gatetopay.com)
