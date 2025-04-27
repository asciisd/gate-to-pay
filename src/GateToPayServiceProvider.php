<?php

namespace ASCIISD\GateToPay;

use ASCIISD\GateToPay\Commands\CreateProfileCommand;
use ASCIISD\GateToPay\Commands\TestConnectionCommand;
use ASCIISD\GateToPay\Helpers\SignatureService;
use ASCIISD\GateToPay\Http\ApiClient;
use ASCIISD\GateToPay\Services\CMSApiService;
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
            return new SignatureService(
                config('gatetopay.trade_api.api_key')
            );
        });

        $this->app->singleton('gatetopay.api.trade', function ($app) {
            return new ApiClient(
                config('gatetopay.trade_api.base_url'),
                config('gatetopay.trade_api.api_key'),
                'APIKEY',
                'GateToPay Trade API'
            );
        });

        $this->app->singleton('gatetopay.api.cms', function ($app) {
            return new ApiClient(
                config('gatetopay.cms_api.base_url'),
                config('gatetopay.cms_api.api_key'),
                'API-Key',
                'GateToPay CMS API'
            );
        });

        $this->app->singleton(GateToPayService::class, function ($app) {
            return new GateToPayService($app->make(SignatureService::class));
        });

        $this->app->singleton(CMSApiService::class, function ($app) {
            return new CMSApiService($app->make(SignatureService::class));
        });

        $this->app->alias(GateToPayService::class, 'gatetopay');
        $this->app->alias(CMSApiService::class, 'gatetopay.cms');
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
                CreateProfileCommand::class,
            ]);
        }
    }
}
