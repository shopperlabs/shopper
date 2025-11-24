<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Shopper\Core\Contracts\ShopperUser;

trait HasAuthenticated
{
    public function getUser(): ShopperUser
    {
        /** @var ShopperUser $user */
        $user = shopper()->auth()->user();

        return $user;
    }
}
