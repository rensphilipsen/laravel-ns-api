<?php

namespace RensPhilipsen\NSApi;

use Illuminate\Support\ServiceProvider;

class NSApiServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/nsapi.php', 'nsapi');

        $this->app->bind('ns-api', function($app) {
            return new NSApi();
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/nsapi.php' => config_path('nsapi.php'),
        ], 'config');
    }

}
