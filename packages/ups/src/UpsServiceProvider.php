<?php

declare(strict_types=1);

namespace Shopper\Ups;

use Illuminate\Support\ServiceProvider;
use Shopper\Shipping\Facades\Shipping;

final class UpsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ups.php', 'shopper.ups');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/ups.php' => config_path('shopper/ups.php'),
        ], 'shopper-ups-config');

        Shipping::extend('ups', fn (): UpsDriver => new UpsDriver(
            clientId: (string) config('shopper.ups.client_id'),
            clientSecret: (string) config('shopper.ups.client_secret'),
            userId: (string) config('shopper.ups.user_id'),
            accountNumber: (string) config('shopper.ups.account_number'),
            sandbox: (bool) config('shopper.ups.sandbox', false),
        ));
    }
}
