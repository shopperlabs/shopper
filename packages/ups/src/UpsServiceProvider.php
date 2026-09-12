<?php

declare(strict_types=1);

namespace Shopper\Ups;

use Illuminate\Support\ServiceProvider;
use Shopper\Shipping\Facades\Shipping;

final class UpsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ups.php', 'shopper.shipping.drivers.ups');
    }

    public function boot(): void
    {
        Shipping::extend('ups', fn (): UpsDriver => new UpsDriver(
            clientId: (string) config('shopper.shipping.drivers.ups.credentials.client_id'),
            clientSecret: (string) config('shopper.shipping.drivers.ups.credentials.client_secret'),
            userId: (string) config('shopper.shipping.drivers.ups.credentials.user_id'),
            accountNumber: (string) config('shopper.shipping.drivers.ups.credentials.account_number'),
            sandbox: (bool) config('shopper.shipping.drivers.ups.sandbox', false),
        ));
    }
}
