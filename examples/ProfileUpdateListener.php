<?php

namespace App\Listeners;

use ASCIISD\GateToPay\Events\ProfileUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ProfileUpdateListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProfileUpdated $event): void
    {
        // Access the profile data from the event
        $profileData = $event->profileData;
        
        // Extract customer ID and status
        $customerId = $profileData['customerId'] ?? null;
        $status = $profileData['status'] ?? null;
        
        // Log the profile update
        Log::info('GateToPay profile update received', [
            'customerId' => $customerId,
            'status' => $status,
            'data' => $profileData
        ]);
        
        // Here you can add your business logic to process the profile update
        // For example, update your local database with the new profile information
        
        // Example:
        // if ($status === 'success') {
        //     User::where('gatetopay_customer_id', $customerId)->update([
        //         'profile_status' => 'active',
        //         'profile_data' => json_encode($profileData)
        //     ]);
        // }
    }
}
