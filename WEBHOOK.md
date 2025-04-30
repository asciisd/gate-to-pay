# GateToPay Webhook Integration Guide

This document provides instructions for GateToPay on how to integrate with our webhook endpoint to notify our system
about customer profile changes.

## Webhook Endpoint

Please send all webhook notifications to the following endpoint:

```
https://[CLIENT_DOMAIN]/gatetopay/webhook
```

Replace `[CLIENT_DOMAIN]` with the actual domain provided by the client.

## Authentication

All webhook requests must include a signature for verification:

- Add an `X-Signature` header to your HTTP request
- Generate the signature using the same algorithm as used for API requests
- The signature should be generated from the entire request body

Example:

```
X-Signature: 5a3f7d...
```

## Request Format

### HTTP Method

Use `POST` for all webhook requests.

### Headers

- `Content-Type: application/json`
- `Accept: application/json`
- `X-Signature: [SIGNATURE]`

### Request Body

The webhook payload should be a JSON object with the following structure:

```json
{
    "customerId": "2304251234",                     // Required: The customer ID in GateToPay
    "action": "created",                            // Required: One of "created", "updated", or "deleted"
    "status": "success",                            // Required: Status of the operation ("success" or "failed")
    "data": {
        "id": "6764327643",                         // Required: Encrypted card ID for secure reference
        "cardNumber": "1234 XXXX XXXX 6543"         // Required: Masked card number.
        // Include any other relevant profile fields
    }
}
```

### Required Fields

| Field             | Type   | Description                                                                |
|-------------------|--------|----------------------------------------------------------------------------|
| `customerId`      | String | The unique customer ID in GateToPay system                                 |
| `action`          | String | The action that triggered this webhook: "created", "updated", or "deleted" |
| `status`          | String | The status of the operation: "success" or "failed"                         |
| `data.id`         | String | The encrypted card ID for secure reference to the card                     |
| `data.cardNumber` | String | The Masked card number                                                     |

### Webhook Events

The webhook should be triggered for the following events:

1. **Profile Created** (`action: "created"`)
    - When a new customer profile is successfully created
    - Include all profile data in the request

2. **Profile Updated** (`action: "updated"`)
    - When an existing customer profile is modified
    - Include the updated profile data in the request

3. **Profile Deleted** (`action: "deleted"`)
    - When a customer profile is deleted
    - Only the `customerId` is required in this case

## Response Handling

Our webhook endpoint will respond with:

- **200 OK**: The webhook was successfully received and processed
- **400 Bad Request**: The request was invalid (missing required fields)
- **403 Forbidden**: Authentication failed (invalid signature)
- **500 Internal Server Error**: An error occurred while processing the webhook
