<?php

use ASCIISD\GateToPay\Exceptions\GateToPayException;
use ASCIISD\GateToPay\Facades\GateToPayCMS;
use ASCIISD\GateToPay\Helpers\CardTypeHelper;
use ASCIISD\GateToPay\Helpers\GenderHelper;
use Illuminate\Support\Facades\Http;

it('can create a new profile', function () {
    Http::fake([
        'cmsopenapitest.gatetopay.com/api/Profile/CreateProfile*' => Http::response([
            'isSuccess' => true,
            'message' => '',
        ], 200),
    ]);

    $profileData = [
        'customerId' => '1234567890',
        'gender' => GenderHelper::MALE,
        'firstName' => 'John',
        'middleName' => 'Robert',
        'lastName' => 'Doe',
        'birthDate' => '1985-05-04',
        'nationalNumberOrPassport' => '101010',
        'cardType' => CardTypeHelper::STANDARD,
        'nationality' => '400', // Jordan
        'address' => '123 Main St, Amman',
        'phoneNumber' => '00962123456789',
        'nameOnCard' => 'John R Doe',
        'email' => 'john.doe@example.com'
    ];

    $response = GateToPayCMS::createNewProfile($profileData);

    expect($response)->toBeArray()
        ->and($response['isSuccess'])->toBeTrue()
        ->and($response['message'])->toBe('');

    Http::assertSent(function ($request) use ($profileData) {
        return $request->url() == 'https://cmsopenapitest.gatetopay.com/api/Profile/CreateProfile' &&
            $request->method() == 'POST' &&
            $request->data()['customerId'] == $profileData['customerId'] &&
            $request->data()['gender'] == $profileData['gender'] &&
            $request->data()['firstName'] == $profileData['firstName'] &&
            $request->data()['lastName'] == $profileData['lastName'] &&
            $request->data()['nationality'] == $profileData['nationality'] &&
            $request->data()['signature'] == 'mocked-signature-value';
    });
});

it('throws exception when missing required parameters', function () {
    // Missing firstName
    $profileData = [
        'customerId' => '1234567890',
        'gender' => GenderHelper::MALE,
        'lastName' => 'Doe',
        'birthDate' => '1985-05-04',
        'nationalNumberOrPassport' => '101010',
        'cardType' => CardTypeHelper::STANDARD,
        'nationality' => '400',
        'address' => '123 Main St, Amman',
        'phoneNumber' => '00962123456789',
        'nameOnCard' => 'John Doe',
        'email' => 'john.doe@example.com'
    ];

    expect(fn () => GateToPayCMS::createNewProfile($profileData))
        ->toThrow(GateToPayException::class, 'Missing required parameter: firstName');
});

it('handles api error response', function () {
    Http::fake([
        'cmsopenapitest.gatetopay.com/api/Profile/CreateProfile*' => Http::response([
            'isSuccess' => false,
            'message' => 'The customer id already exist',
        ], 200),
    ]);

    $profileData = [
        'customerId' => '1234567890',
        'gender' => GenderHelper::MALE,
        'firstName' => 'John',
        'lastName' => 'Doe',
        'birthDate' => '1985-05-04',
        'nationalNumberOrPassport' => '101010',
        'cardType' => CardTypeHelper::STANDARD,
        'nationality' => '400',
        'address' => '123 Main St, Amman',
        'phoneNumber' => '00962123456789',
        'nameOnCard' => 'John Doe',
        'email' => 'john.doe@example.com'
    ];

    expect(fn () => GateToPayCMS::createNewProfile($profileData))
        ->toThrow(GateToPayException::class, 'GateToPay CMS API Error: The customer id already exist');
});

it('handles server error', function () {
    Http::fake([
        'cmsopenapitest.gatetopay.com/api/Profile/CreateProfile*' => Http::response([
            'error' => 'Internal Server Error'
        ], 500),
    ]);

    $profileData = [
        'customerId' => '1234567890',
        'gender' => GenderHelper::MALE,
        'firstName' => 'John',
        'lastName' => 'Doe',
        'birthDate' => '1985-05-04',
        'nationalNumberOrPassport' => '101010',
        'cardType' => CardTypeHelper::STANDARD,
        'nationality' => '400',
        'address' => '123 Main St, Amman',
        'phoneNumber' => '00962123456789',
        'nameOnCard' => 'John Doe',
        'email' => 'john.doe@example.com'
    ];

    $closure = fn () => GateToPayCMS::createNewProfile($profileData);

    expect($closure)->toThrow(GateToPayException::class);

    try {
        $closure();
    } catch (GateToPayException $e) {
        expect($e->getMessage())->toContain('Error communicating with GateToPay CMS API');
    }
});

it('handles network error', function () {
    Http::fake([
        'cmsopenapitest.gatetopay.com/api/Profile/CreateProfile*' => Http::response(null, 500),
    ]);

    $profileData = [
        'customerId' => '1234567890',
        'gender' => GenderHelper::MALE,
        'firstName' => 'John',
        'lastName' => 'Doe',
        'birthDate' => '1985-05-04',
        'nationalNumberOrPassport' => '101010',
        'cardType' => CardTypeHelper::STANDARD,
        'nationality' => '400',
        'address' => '123 Main St, Amman',
        'phoneNumber' => '00962123456789',
        'nameOnCard' => 'John Doe',
        'email' => 'john.doe@example.com'
    ];

    expect(fn () => GateToPayCMS::createNewProfile($profileData))
        ->toThrow(GateToPayException::class);
});
