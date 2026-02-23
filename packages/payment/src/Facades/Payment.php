<?php

declare(strict_types=1);

namespace Shopper\Payment\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Shopper\Payment\Contracts\PaymentDriver;
use Shopper\Payment\PaymentManager;

/**
 * @method static PaymentDriver driver(?string $name = null)
 * @method static PaymentManager extend(string $name, \Closure $callback)
 * @method static array availableDrivers()
 * @method static Collection configuredDrivers()
 * @method static bool isConfigured(string $name)
 *
 * @see PaymentManager
 */
final class Payment extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PaymentManager::class;
    }
}
