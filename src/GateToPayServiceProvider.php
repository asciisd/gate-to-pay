<?php

namespace ASCIISD\GateToPay;

use ASCIISD\GateToPay\Commands\TestConnectionCommand;
use ASCIISD\GateToPay\Helpers\SignatureService;
use ASCIISD\GateToPay\Services\GateToPayService;
use Illuminate\Support\ServiceProvider;

class GateToPayServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/gatetopay.php', 'gatetopay'
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
                $app->make(SignatureService::class)
            );
        });

        $this->app->alias(GateToPayService::class, 'gatetopay');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/gatetopay.php' => config_path('gatetopay.php'),
        ], 'config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                TestConnectionCommand::class,
            ]);
        }
    }
}
