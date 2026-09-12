<?php

declare(strict_types=1);

namespace Shopper\FedEx;

use Illuminate\Support\ServiceProvider;
use Shopper\Shipping\Facades\Shipping;

final class FedExServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/fedex.php', 'shopper.fedex');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/fedex.php' => config_path('shopper/fedex.php'),
        ], 'shopper-fedex-config');

        Shipping::extend('fedex', fn (): FedExDriver => new FedExDriver(
            clientId: (string) config('shopper.fedex.client_id'),
            clientSecret: (string) config('shopper.fedex.client_secret'),
            accountNumber: (string) config('shopper.fedex.account_number'),
            sandbox: (bool) config('shopper.fedex.sandbox', false),
        ));
    }
}
