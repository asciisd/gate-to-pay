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

This package includes a comprehensive test suite. To run the tests, you need to set up the testing environment first:

1. Copy the phpunit.xml.dist file to phpunit.xml:

```bash
cp phpunit.xml.dist phpunit.xml
```

2. Update the phpunit.xml file with your test credentials:

```xml
<env name="GATE_TO_PAY_API_KEY" value="your-api-key-here"/>
<env name="GATE_TO_PAY_USERNAME" value="your-username-here"/>
<env name="GATE_TO_PAY_PASSWORD" value="your-password-here"/>
<env name="GATE_TO_PAY_CUSTOMER_ID" value="your-customer-id-here"/>
```

3. Run the tests:

```bash
composer test
```

Note: The phpunit.xml file is gitignored to prevent committing sensitive credentials to your repository.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
