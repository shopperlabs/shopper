<?php

declare(strict_types=1);

namespace Shopper\FedEx;

use Illuminate\Support\ServiceProvider;
use Shopper\Shipping\Facades\Shipping;

final class FedExServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/fedex.php', 'shopper.shipping.drivers.fedex');
    }

    public function boot(): void
    {
        Shipping::extend('fedex', fn (): FedExDriver => new FedExDriver(
            clientId: (string) config('shopper.shipping.drivers.fedex.credentials.client_id'),
            clientSecret: (string) config('shopper.shipping.drivers.fedex.credentials.client_secret'),
            accountNumber: (string) config('shopper.shipping.drivers.fedex.credentials.account_number'),
            sandbox: (bool) config('shopper.shipping.drivers.fedex.sandbox', false),
        ));
    }
}
