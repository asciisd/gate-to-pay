<?php

namespace App\Listeners;

use ASCIISD\GateToPay\Events\WebhookReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class WebhookListener implements ShouldQueue
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
    public function handle(WebhookReceived $event): void
    {
        // Access the webhook data and action
        $data = $event->data;
        $action = $event->action;
        
        // Extract customer ID
        $customerId = $data['customerId'] ?? null;
        
        // Log the webhook
        Log::info('GateToPay webhook received', [
            'customerId' => $customerId,
            'action' => $action,
            'data' => $data
        ]);
        
        // Process the webhook based on the action
        switch ($action) {
            case 'created':
                $this->handleProfileCreated($data);
                break;
                
            case 'updated':
                $this->handleProfileUpdated($data);
                break;
                
            case 'deleted':
                $this->handleProfileDeleted($data);
                break;
                
            default:
                Log::warning('Unknown GateToPay webhook action', [
                    'action' => $action
                ]);
                break;
        }
    }
    
    /**
     * Handle profile created action.
     */
    protected function handleProfileCreated(array $data): void
    {
        // Example implementation:
        // User::create([
        //     'gatetopay_customer_id' => $data['customerId'],
        //     'profile_status' => 'active',
        //     'profile_data' => json_encode($data)
        // ]);
    }
    
    /**
     * Handle profile updated action.
     */
    protected function handleProfileUpdated(array $data): void
    {
        // Example implementation:
        // User::where('gatetopay_customer_id', $data['customerId'])->update([
        //     'profile_status' => 'active',
        //     'profile_data' => json_encode($data)
        // ]);
    }
    
    /**
     * Handle profile deleted action.
     */
    protected function handleProfileDeleted(array $data): void
    {
        // Example implementation:
        // User::where('gatetopay_customer_id', $data['customerId'])->update([
        //     'profile_status' => 'deleted'
        // ]);
    }
}
