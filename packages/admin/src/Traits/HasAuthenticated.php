<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Contracts\ShopperUser;

trait HasAuthenticated
{
    public function getUser(): Model&ShopperUser
    {
        /** @var Model&ShopperUser $user */
        $user = shopper()->auth()->user();

        return $user;
    }
}
