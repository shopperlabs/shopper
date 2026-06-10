<?php

declare(strict_types=1);

namespace Shopper\Http\Facades;

use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Facade;
use Shopper\Http\ShopperApiRouter;

/**
 * @method static RouteRegistrar store(\Closure $routes)
 * @method static RouteRegistrar authenticated(\Closure $routes)
 * @method static string prefix()
 *
 * @see ShopperApiRouter
 */
final class ShopperApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ShopperApiRouter::class;
    }
}
