<?php

namespace RensPhilipsen\NSApi;

use Illuminate\Support\ServiceProvider;

class NSApiServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/nsapi.php', 'nsapi');

        $this->app->singleton(NSApi::class, function ($app) {
            return new NSApi();
        });

        $this->app->alias(NSApi::class, 'ns-api');
    }

    public function boot(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $destinationPath = function_exists('\\config_path')
            ? \config_path('nsapi.php')
            : $this->app->configPath('nsapi.php');

        $this->publishes([
            __DIR__ . '/../config/nsapi.php' => $destinationPath,
        ], 'config');
    }

}
