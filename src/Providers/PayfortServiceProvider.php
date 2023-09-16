<?php

namespace Payfort\Providers;

use Illuminate\Support\ServiceProvider;

class PayfortServiceProvider extends ServiceProvider
{
    private $config = __DIR__ . '/../../config/payfort.php';
    protected $defer = false;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->publishes([
                $this->config => config_path('payfort.php'),
            ], ['payfortConfig']);
        }

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'payfort');
    }
}
