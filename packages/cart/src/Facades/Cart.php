<?php

declare(strict_types=1);

namespace Shopper\Cart\Facades;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Facade;
use Shopper\Cart\CartSessionManager;

/**
 * @method static \Shopper\Cart\Models\Cart current()
 * @method static \Shopper\Cart\Models\Cart create()
 * @method static void use(\Shopper\Cart\Models\Cart $cart)
 * @method static void forget()
 * @method static void associate(Authenticatable $user)
 *
 * @see CartSessionManager
 */
final class Cart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CartSessionManager::class;
    }
}
