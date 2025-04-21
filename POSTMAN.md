# Gate To Pay API - Postman Collection

This document provides instructions on how to use the Postman collection for testing the Gate To Pay API integration.

## Getting Started

1. Download and install [Postman](https://www.postman.com/downloads/) if you haven't already.
2. Import the `GateToPay.postman_collection.json` file into Postman.

## Collection Structure

The collection includes the following requests:

1. **Get Customer Cards** - Retrieves the list of cards associated with a customer.
2. **Card Cash Out** - Performs a card cash out transaction (deposit funds to a card).
3. **Card Cash Out with OTP** - Performs a card cash out transaction with OTP verification.
4. **Card Cash In** - Performs a card cash in transaction (withdraw funds from a card).

## Environment Variables

The collection uses the following variables which are pre-configured with test values:

- `baseUrl`: The base URL for the Gate To Pay API (default: https://tradetest.gatetopay.com)
- `apiKey`: Your API key
- `username`: Your username
- `password`: Your password
- `currency`: The currency to use (default: USD)
- `customerId`: Your customer ID
- `cardId`: The ID of the card to use
- `signature`: This is generated automatically in the pre-request script

## Signature Generation

In a real-world scenario, you would need to implement the actual signature generation logic in the pre-request script. The current implementation uses a placeholder function that returns a dummy signature.

To implement the actual signature generation:

1. Open the collection
2. Click on the "..." (three dots) next to the collection name
3. Select "Edit"
4. Go to the "Pre-request Scripts" tab
5. Update the `generateSignature` function with your actual implementation

## Testing the API

1. Make sure you have updated the environment variables with your actual credentials.
2. Start with the "Get Customer Cards" request to retrieve the list of cards.
3. Use one of the card IDs from the response in the subsequent requests.
4. Test the "Card Cash Out" and "Card Cash In" requests.
5. If OTP is required, use the "Card Cash Out with OTP" request.

## Response Handling

The collection includes basic tests to validate the responses:

- Checks that the status code is 200
- Verifies that the response is valid JSON
- For "Get Customer Cards", checks that the response contains a cards array
- For "Card Cash Out" and "Card Cash In", checks for transaction details

## Notes

- This collection is designed for testing purposes only.
- The pre-request scripts include simplified versions of the actual logic used in the Laravel package.
- In a production environment, you should implement proper error handling and security measures.
