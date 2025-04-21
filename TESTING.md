# Testing Guide

This document provides instructions on how to set up and run tests for the Gate To Pay package.

## Setting Up Test Credentials

For security reasons, real API credentials are not stored in the repository. Instead, we use the phpunit.xml file to configure the test environment.

### Local Development

1. Copy the `phpunit.xml.dist` file to `phpunit.xml`:

```bash
cp phpunit.xml.dist phpunit.xml
```

2. Edit the `phpunit.xml` file and add your actual Gate To Pay API credentials:

```xml
<env name="GATE_TO_PAY_API_KEY" value="your-api-key-here"/>
<env name="GATE_TO_PAY_USERNAME" value="your-username-here"/>
<env name="GATE_TO_PAY_PASSWORD" value="your-password-here"/>
<env name="GATE_TO_PAY_CUSTOMER_ID" value="your-customer-id-here"/>
```

The `phpunit.xml` file is gitignored and will not be committed to the repository.

### CI/CD Environments

For CI/CD environments, you should set the environment variables in your CI/CD platform's configuration. The phpunit.xml.dist file includes placeholder values that will be used if no environment variables are set.

## Running Tests

To run the tests, use the following command:

```bash
composer test
```

Or directly with Pest:

```bash
./vendor/bin/pest
```

## Test Structure

- `tests/Feature`: Contains integration tests that test the package's functionality with the Gate To Pay API
- `tests/Unit`: Contains unit tests for individual components

## Mock Responses

The tests use Laravel's HTTP client fake to mock API responses. This allows the tests to run without making actual API calls.

If you need to update the mock responses to match changes in the API, you can modify the `Http::fake()` calls in the test files.
