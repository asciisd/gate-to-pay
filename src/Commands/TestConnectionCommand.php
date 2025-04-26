<?php

namespace ASCIISD\GateToPay\Commands;

use ASCIISD\GateToPay\Exceptions\GateToPayException;
use ASCIISD\GateToPay\Services\GateToPayService;
use Illuminate\Console\Command;

class TestConnectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gatetopay:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the connection to the GateToPay API';

    /**
     * Execute the console command.
     *
     * @param \ASCIISD\GateToPay\Services\GateToPayService $service
     * @return int
     */
    public function handle(GateToPayService $service)
    {
        $this->info('Testing connection to GateToPay API...');

        try {
            // Test the connection by fetching customer cards
            $cards = $service->getCustomerCards('6999999999');
            
            $this->info('Connection successful!');
            
            if (isset($cards['customerCards']) && !empty($cards['customerCards'])) {
                $this->info('Found ' . count($cards['customerCards']) . ' cards for the customer.');
                
                $this->table(
                    ['Card ID', 'Card Number'],
                    collect($cards['customerCards'])->map(function ($card) {
                        return [
                            'Card ID' => $card['id'] ?? 'N/A',
                            'Card Number' => $card['cardNumber'] ?? 'N/A',
                        ];
                    })->toArray()
                );
            } else {
                $this->info('No cards found for the customer.');
            }
            
            return 0;
        } catch (GateToPayException $e) {
            $this->error('Connection failed: ' . $e->getMessage());
            return 1;
        }
    }
}
