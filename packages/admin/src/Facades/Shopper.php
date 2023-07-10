<?php

declare(strict_types=1);

namespace Shopper\Facades;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Facade;
use Shopper\Core\Shopper as ShopperCore;

/**
 * @method static StatefulGuard auth()
 * @method static string prefix()
 * @method static string version()
 *
 * @see ShopperCore
 */
class Shopper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'shopper';
    }
}
