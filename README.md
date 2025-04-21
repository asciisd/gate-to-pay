# Gate To Pay Laravel Package

A Laravel package to integrate with Gate To Pay (GateToPay) payment service.

## Features

- Retrieve a list of cards
- Perform Cash Out transactions with internal OTP handling
- Perform Cash In transactions
- Handle request signature generation securely
- Log all request/response data for auditing

## Installation

You can install the package via composer:

```bash
composer require asciisd/gate-to-pay
```

## Configuration

Publish the config file with:

```bash
php artisan vendor:publish --provider="ASCIISD\GateToPay\GateToPayServiceProvider"
```

Then set up your environment variables in `.env`:

```
GATE_TO_PAY_API_KEY=your-api-key
GATE_TO_PAY_USERNAME=your-username
GATE_TO_PAY_PASSWORD=your-password
GATE_TO_PAY_BASE_URL=https://tradetest.gatetopay.com
GATE_TO_PAY_CURRENCY=USD
GATE_TO_PAY_CUSTOMER_ID=your-customer-id
```

## Usage

### Get Customer Cards

```php
use ASCIISD\GateToPay\Facades\GateToPay;

$cards = GateToPay::getCustomerCards();
```

### Card Cash Out

```php
use ASCIISD\GateToPay\Facades\GateToPay;

$response = GateToPay::cardCashOut([
    'cardId' => 'card-id',
    'depositAmount' => 100.00,
    'transactionId' => 'unique-transaction-id',
    'cardExpiryDate' => '09/25',
]);
```

### Card Cash In

```php
use ASCIISD\GateToPay\Facades\GateToPay;

$response = GateToPay::cardCashIn([
    'cardId' => 'card-id',
    'withdrawalAmount' => 100.00,
    'transactionId' => 'unique-transaction-id',
    'cardExpiryDate' => '09/25',
]);
```

## Testing

You can test your connection with:

```bash
php artisan gatetopay:test
```

Or run the test suite:

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
