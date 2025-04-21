<?php

namespace ASCIISD\GateToPay;

use ASCIISD\GateToPay\Commands\TestConnectionCommand;
use ASCIISD\GateToPay\Services\GateToPayService;
use ASCIISD\GateToPay\Helpers\SignatureService;
use Illuminate\Support\ServiceProvider;

class GateToPayServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/gatetopay.php', 'gatetopay'
        );

        // Register services
        $this->app->singleton(SignatureService::class, function ($app) {
            return new SignatureService(config('gatetopay.api_key'));
        });

        $this->app->singleton(GateToPayService::class, function ($app) {
            return new GateToPayService(
                config('gatetopay.base_url'),
                config('gatetopay.api_key'),
                config('gatetopay.username'),
                config('gatetopay.password'),
                config('gatetopay.currency'),
                config('gatetopay.customer_id'),
                $app->make(SignatureService::class)
            );
        });

        $this->app->alias(GateToPayService::class, 'gatetopay');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/gatetopay.php' => config_path('gatetopay.php'),
        ], 'config');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                TestConnectionCommand::class,
            ]);
        }
    }
}
