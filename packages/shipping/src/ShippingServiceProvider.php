<?php

declare(strict_types=1);

namespace Shopper\Shipping;

use Illuminate\Support\ServiceProvider;
use Shopper\Shipping\Services\CarrierRateService;

final class ShippingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shipping.php', 'shopper.shipping');

        $this->app->singleton(ShippingManager::class, fn (): ShippingManager => new ShippingManager);
        $this->app->singleton(CarrierRateService::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/shipping.php' => config_path('shopper/shipping.php'),
        ], 'shopper-config');
    }
}
