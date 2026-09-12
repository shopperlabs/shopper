<?php

declare(strict_types=1);

namespace Shopper\Usps;

use Illuminate\Support\ServiceProvider;
use Shopper\Shipping\Facades\Shipping;

final class UspsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/usps.php', 'shopper.shipping.drivers.usps');
    }

    public function boot(): void
    {
        Shipping::extend('usps', fn (): UspsDriver => new UspsDriver(
            clientId: (string) config('shopper.shipping.drivers.usps.credentials.client_id'),
            clientSecret: (string) config('shopper.shipping.drivers.usps.credentials.client_secret'),
            sandbox: (bool) config('shopper.shipping.drivers.usps.sandbox', false),
        ));
    }
}
