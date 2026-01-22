<?php

declare(strict_types=1);

namespace Shopper\Shipping\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Shopper\Shipping\Contracts\ShippingDriver;
use Shopper\Shipping\ShippingManager;

/**
 * @method static ShippingDriver driver(?string $name = null)
 * @method static ShippingManager extend(string $name, \Closure $callback)
 * @method static array availableDrivers()
 * @method static Collection configuredDrivers()
 * @method static bool isConfigured(string $name)
 *
 * @see ShippingManager
 */
final class Shipping extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ShippingManager::class;
    }
}
