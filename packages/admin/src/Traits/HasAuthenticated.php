<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Shopper\Core\Models\User;

trait HasAuthenticated
{
    public function getUser(): User
    {
        /** @var User $user */
        $user = shopper()->auth()->user();

        return $user;
    }
}
