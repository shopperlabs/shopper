<?php

declare(strict_types=1);

namespace Shopper\Usps;

use Illuminate\Support\ServiceProvider;
use Shopper\Shipping\Facades\Shipping;

final class UspsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/usps.php', 'shopper.usps');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/usps.php' => config_path('shopper/usps.php'),
        ], 'shopper-usps-config');

        Shipping::extend('usps', fn (): UspsDriver => new UspsDriver(
            clientId: (string) config('shopper.usps.client_id'),
            clientSecret: (string) config('shopper.usps.client_secret'),
            sandbox: (bool) config('shopper.usps.sandbox', false),
        ));
    }
}
