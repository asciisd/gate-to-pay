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
            $cards = $service->getCustomerCards();
            
            $this->info('Connection successful!');
            $this->info('Found ' . count($cards) . ' cards for the customer.');
            
            if (!empty($cards)) {
                $this->table(
                    ['Card ID', 'Card Type', 'Last 4 Digits'],
                    collect($cards)->map(function ($card) {
                        return [
                            'Card ID' => $card['id'] ?? 'N/A',
                            'Card Type' => $card['cardType'] ?? 'N/A',
                            'Last 4 Digits' => $card['last4Digits'] ?? 'N/A',
                        ];
                    })->toArray()
                );
            }
            
            return 0;
        } catch (GateToPayException $e) {
            $this->error('Connection failed: ' . $e->getMessage());
            return 1;
        }
    }
}
