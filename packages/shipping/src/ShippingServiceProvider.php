<?php

declare(strict_types=1);

namespace Shopper\Shipping;

use Closure;
use Illuminate\Support\ServiceProvider;
use Shopper\Core\Models\Carrier;
use Shopper\Shipping\Facades\Shipping;
use Shopper\Shipping\Services\CarrierRateService;

final class ShippingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shipping.php', 'shopper.shipping');

        $this->app->singleton(ShippingManager::class, fn (): ShippingManager => new ShippingManager);
        $this->app->singleton(CarrierRateService::class);

        $this->app->bind(
            'shopper.carrier.logo',
            fn (): Closure => fn (Carrier $carrier): ?string => Shipping::driver($carrier->driver ?? 'manual')->logo()
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/shipping.php' => config_path('shopper/shipping.php'),
        ], 'shopper-config');
    }
}
