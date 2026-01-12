<?php

declare(strict_types=1);

namespace Shopper\Actions\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Shopper\Core\Contracts\ShopperUser;

class ConfirmPassword
{
    public function __invoke(StatefulGuard $guard, ShopperUser $user, string $password): bool
    {
        return $guard->validate([
            'email' => $user->email,
            'password' => $password,
        ]);
    }
}
