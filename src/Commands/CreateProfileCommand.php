<?php

namespace ASCIISD\GateToPay\Commands;

use ASCIISD\GateToPay\Exceptions\GateToPayException;
use ASCIISD\GateToPay\Helpers\CardTypeHelper;
use ASCIISD\GateToPay\Helpers\GenderHelper;
use ASCIISD\GateToPay\Helpers\NationalityHelper;
use ASCIISD\GateToPay\Services\GateToPayService;
use Illuminate\Console\Command;

class CreateProfileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gatetopay:create-profile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new customer profile in the GateToPay system';

    /**
     * Execute the console command.
     *
     * @param GateToPayService $service
     * @return int
     */
    public function handle(GateToPayService $service)
    {
        $this->info('Creating a new customer profile in GateToPay...');

        // Gather profile information
        $customerId = $this->ask('Enter customer ID');

        // Gender selection with helper
        $genderOptions = collect(GenderHelper::all())->mapWithKeys(function ($gender) {
            return [$gender['code'] => $gender['en'].' ('.$gender['ar'].')'];
        })->toArray();
        $gender = $this->choice('Select gender', $genderOptions, GenderHelper::MALE);

        $firstName = $this->ask('Enter first name');
        $middleName = $this->ask('Enter middle name (optional)') ?: null;
        $lastName = $this->ask('Enter last name');
        $birthDate = $this->ask('Enter birth date (YYYY-MM-DD)');
        $nationalNumberOrPassport = $this->ask('Enter national number or passport');

        // Card type selection with helper
        $cardTypeOptions = collect(CardTypeHelper::all())->mapWithKeys(function ($cardType) {
            return [$cardType['code'] => $cardType['en'].' ('.$cardType['ar'].')'];
        })->toArray();
        $cardType = $this->choice('Select card type', $cardTypeOptions, CardTypeHelper::STANDARD);

        // Nationality selection with helper
        // First, let the user search for a country
        $searchTerm = $this->ask('Enter country name to search (in English or Arabic)');
        $matchingCountries = collect(NationalityHelper::all())->filter(function ($country) use ($searchTerm) {
            return mb_stripos($country['en'], $searchTerm) !== false ||
                mb_stripos($country['ar'], $searchTerm) !== false;
        })->take(10)->mapWithKeys(function ($country) {
            return [$country['code'] => $country['en'].' ('.$country['ar'].')'];
        })->toArray();

        if (empty($matchingCountries)) {
            $this->warn('No countries found matching your search. Please enter a nationality code directly.');
            $nationality = $this->ask('Enter nationality code');
        } else {
            $nationality = $this->choice('Select nationality', $matchingCountries);
        }

        $address = $this->ask('Enter address');
        $phoneNumber = $this->ask('Enter phone number');
        $nameOnCard = $this->ask('Enter name on card');
        $email = $this->ask('Enter email address');

        // Prepare the profile data
        $profileData = [
            'customerId' => $customerId,
            'gender' => $gender,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'birthDate' => $birthDate,
            'nationalNumberOrPassport' => $nationalNumberOrPassport,
            'cardType' => $cardType,
            'nationality' => $nationality,
            'address' => $address,
            'phoneNumber' => $phoneNumber,
            'nameOnCard' => $nameOnCard,
            'email' => $email,
        ];

        // Add optional fields if provided
        if ($middleName) {
            $profileData['middleName'] = $middleName;
        }

        try {
            // Create the profile
            $response = $service->createNewProfile($profileData);

            if (isset($response['isSuccess']) && $response['isSuccess'] === true) {
                $this->info('Profile created successfully!');

                // Display the response
                $this->table(
                    ['Field', 'Value'],
                    collect($response)->map(function ($value, $key) {
                        return [
                            'Field' => $key,
                            'Value' => is_string($value) ? $value : json_encode($value),
                        ];
                    })->toArray()
                );
            } else {
                $this->warn('Profile creation completed but returned an unexpected response.');
                $this->line(json_encode($response, JSON_PRETTY_PRINT));
            }

            return 0;
        } catch (GateToPayException $e) {
            $this->error('Profile creation failed: '.$e->getMessage());
            return 1;
        }
    }
}
