<?php

namespace GabrielAndy\Coindesk;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class CoindeskServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/coindesk.php' => config_path('coindesk.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/coindesk.php', 'coindesk');

        $this->app->bind('coindesk', function ($app) {
            return (new Coindesk(new Client))
                ->setEndpoint(config('coindesk.endpoint'));
        });
    }
}
