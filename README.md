# Gate To Pay Laravel Package

A Laravel package to integrate with Gate To Pay (GateToPay) payment service, supporting both Trade API and CMS Open API.

## Features

- Retrieve a list of cards
- Perform Cash Out transactions with internal OTP handling
- Perform Cash In transactions
- Create customer profiles via CMS Open API
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
# Trade API Configuration
GATE_TO_PAY_TRADE_API_KEY=your-trade-api-key
GATE_TO_PAY_TRADE_USERNAME=your-username
GATE_TO_PAY_TRADE_PASSWORD=your-password
GATE_TO_PAY_TRADE_BASE_URL=https://tradetest.gatetopay.com
GATE_TO_PAY_TRADE_CURRENCY=USD

# CMS Open API Configuration
GATE_TO_PAY_CMS_API_KEY=your-cms-api-key
GATE_TO_PAY_CMS_BASE_URL=https://cmsopenapitest.gatetopay.com
```

## Migrations

This package includes migrations to:
1. Add a `gate_to_pay_customer_id` column to your users table
2. Create a `gate_to_pay_cards` table to store customer card information

To publish the migrations:

```bash
php artisan vendor:publish --provider="ASCIISD\GateToPay\GateToPayServiceProvider" --tag="migrations"
```

Then run the migrations:

```bash
php artisan migrate
```

## Usage

### Trade API Operations

The Trade API is used for card-related operations like retrieving cards, deposits, and withdrawals.

#### Get Customer Cards

```php
use ASCIISD\GateToPay\Facades\GateToPay;

$cards = GateToPay::getCustomerCards($customerId);
```

#### Card Cash Out (Deposit)

```php
use ASCIISD\GateToPay\Facades\GateToPay;

$response = GateToPay::cardCashOut([
    'customerId' => 'customer-id',
    'cardId' => 'card-id',
    'depositAmount' => 100.00,
    'transactionId' => 'unique-transaction-id',
    'cardExpiryDate' => '09/25',
]);
```

#### Card Cash In (Withdrawal)

```php
use ASCIISD\GateToPay\Facades\GateToPay;

$response = GateToPay::cardCashIn([
    'customerId' => 'customer-id',
    'cardId' => 'card-id',
    'withdrawalAmount' => 100.00,
    'transactionId' => 'unique-transaction-id',
    'cardExpiryDate' => '09/25',
]);
```

### CMS Open API Operations

The CMS Open API is used for profile management operations.

#### Create a New Customer Profile

```php
use ASCIISD\GateToPay\Facades\GateToPayCMS;

$response = GateToPayCMS::createNewProfile([
    'gender' => 'M',
    'firstName' => 'John',
    'lastName' => 'Doe',
    'birthDate' => '1990-01-01',
    'nationalNumberOrPassport' => 'AB123456',
    'cardType' => 'VISA',
    'nationality' => 'US',
    'address' => '123 Main St, New York, NY',
    'phoneNumber' => '+1234567890',
    'nameOnCard' => 'JOHN DOE',
    'email' => 'john.doe@example.com'
]);
```

#### Generate a Customer ID

```php
use ASCIISD\GateToPay\Facades\GateToPayCMS;

$customerId = GateToPayCMS::generateCustomerId();
```

For backward compatibility, you can still use the original GateToPay facade for CMS operations:

```php
use ASCIISD\GateToPay\Facades\GateToPay;

// These methods delegate to the CMSApiService internally
$response = GateToPay::createNewProfile([/* ... */]);
$customerId = GateToPay::generateCustomerId();
```

## Architecture

This package uses a clean architecture with separation of concerns:

1. **ApiClient**: Handles HTTP requests, responses, and logging
2. **GateToPayService**: Manages Trade API business logic
3. **CMSApiService**: Manages CMS API business logic
4. **SignatureService**: Handles secure signature generation

## Testing

This package includes a comprehensive test suite. To run the tests, you need to set up the testing environment first:

1. Copy the phpunit.xml.dist file to phpunit.xml:

```bash
cp phpunit.xml.dist phpunit.xml
```

2. Update the phpunit.xml file with your test credentials:

```xml
<!-- Trade API Credentials -->
<env name="GATE_TO_PAY_TRADE_API_KEY" value="your-trade-api-key-here"/>
<env name="GATE_TO_PAY_TRADE_USERNAME" value="your-username-here"/>
<env name="GATE_TO_PAY_TRADE_PASSWORD" value="your-password-here"/>

<!-- CMS API Credentials -->
<env name="GATE_TO_PAY_CMS_API_KEY" value="your-cms-api-key-here"/>

<!-- Test Data -->
<env name="GATE_TO_PAY_CUSTOMER_ID" value="your-customer-id-here"/>
```

3. Run the tests:

```bash
composer test
```

Note: The phpunit.xml file is gitignored to prevent committing sensitive credentials to your repository.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
