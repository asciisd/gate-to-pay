# Create New Profile

This document explains how to use the Create New Profile feature in the GateToPay package.

## Overview

The Create New Profile feature allows you to create a new customer profile in the GateToPay system. This is typically the first step before issuing cards or performing transactions for a customer.

## Usage

### Using the Service Directly

```php
use ASCIISD\GateToPay\Facades\GateToPay;
use ASCIISD\GateToPay\Helpers\GenderHelper;
use ASCIISD\GateToPay\Helpers\CardTypeHelper;
use ASCIISD\GateToPay\Helpers\NationalityHelper;

// Prepare the profile data
$profileData = [
    'customerId' => '1234567890',
    'gender' => GenderHelper::MALE,
    'firstName' => 'User',
    'middleName' => 'User',     // Optional
    'lastName' => 'User',
    'birthDate' => '1985-05-04', // Format: YYYY-MM-DD
    'nationalNumberOrPassport' => '101010',
    'cardType' => CardTypeHelper::STANDARD,
    'nationality' => NationalityHelper::getCodeByCountry('Jordan'), // Returns '400'
    'address' => 'XXXXX',
    'phoneNumber' => '00962XXXXXXXXX',
    'nameOnCard' => 'User User',
    'email' => 'email@email.com'
];

// Create the profile
try {
    $response = GateToPay::createNewProfile($profileData);
    
    if ($response['isSuccess']) {
        // Profile created successfully
        echo "Profile created successfully!";
    } else {
        // Handle error
        echo "Error: " . ($response['message'] ?? 'Unknown error');
    }
} catch (\ASCIISD\GateToPay\Exceptions\GateToPayException $e) {
    // Handle exception
    echo "Error: " . $e->getMessage();
}
```

### Using the Command Line

The package includes a command-line tool to create profiles:

```bash
php artisan gatetopay:create-profile
```

This command will interactively prompt you for all required information and create the profile.

## Required Parameters

| Parameter | Description | Example |
|-----------|-------------|---------|
| customerId | Unique identifier for the customer | '1234567890' |
| gender | Customer's gender (M or F) | 'M' |
| firstName | Customer's first name | 'John' |
| lastName | Customer's last name | 'Doe' |
| birthDate | Customer's birth date (YYYY-MM-DD) | '1985-05-04' |
| nationalNumberOrPassport | National ID or passport number | '101010' |
| cardType | Type of card to issue | 'Standard' |
| nationality | Nationality code | '400' |
| address | Customer's address | '123 Main St' |
| phoneNumber | Customer's phone number | '00962XXXXXXXXX' |
| nameOnCard | Name to appear on the card | 'John Doe' |
| email | Customer's email address | 'john.doe@example.com' |

## Optional Parameters

| Parameter | Description | Example |
|-----------|-------------|---------|
| middleName | Customer's middle name | 'Robert' |

## Response

A successful response will have the following structure:

```json
{
    "isSuccess": true,
    "message": ""
}
```

If there's an error, the response will include an error message:

```json
{
    "isSuccess": false,
    "message": "Error message details"
}
```

## Lookups

The package provides helper classes to make it easier to work with lookup values:

### Genders

Use the `GenderHelper` class to work with gender codes:

```php
use ASCIISD\GateToPay\Helpers\GenderHelper;

// Constants
$male = GenderHelper::MALE;       // 'M'
$female = GenderHelper::FEMALE;   // 'F'

// Get all genders
$genders = GenderHelper::all();

// Get gender by code
$gender = GenderHelper::getByCode('M');  // Returns ['code' => 'M', 'ar' => 'ذكر', 'en' => 'Male']

// Get gender code by name (works with English or Arabic names)
$code = GenderHelper::getCodeByName('Male');  // Returns 'M'
$code = GenderHelper::getCodeByName('ذكر');   // Returns 'M'
```

### Card Types

Use the `CardTypeHelper` class to work with card type codes:

```php
use ASCIISD\GateToPay\Helpers\CardTypeHelper;

// Constants
$standard = CardTypeHelper::STANDARD;  // 'Standard'
$premium = CardTypeHelper::PREMIUM;    // 'Premium'
$gold = CardTypeHelper::GOLD;          // 'Gold'

// Get all card types
$cardTypes = CardTypeHelper::all();

// Get card type by code
$cardType = CardTypeHelper::getByCode('Standard');  // Returns ['code' => 'Standard', 'ar' => 'قياسي', 'en' => 'Standard']

// Get card type code by name (works with English or Arabic names)
$code = CardTypeHelper::getCodeByName('Gold');    // Returns 'Gold'
$code = CardTypeHelper::getCodeByName('ذهبي');    // Returns 'Gold'
```

### Nationalities

Use the `NationalityHelper` class to work with nationality codes:

```php
use ASCIISD\GateToPay\Helpers\NationalityHelper;

// Get all nationalities
$nationalities = NationalityHelper::all();

// Get nationality by code
$nationality = NationalityHelper::getByCode('400');  // Returns Jordan details

// Get nationality code by country name (works with English or Arabic names)
$code = NationalityHelper::getCodeByCountry('Jordan');  // Returns '400'
$code = NationalityHelper::getCodeByCountry('الأردن');  // Returns '400'
```

## Error Handling

The service will throw a `GateToPayException` if there are any issues with the API call, such as missing required parameters, network errors, or API-level errors.
