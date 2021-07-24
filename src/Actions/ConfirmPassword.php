<?php

namespace Shopper\Framework\Actions;

use Shopper\Framework\Shopper;
use Illuminate\Contracts\Auth\StatefulGuard;

class ConfirmPassword
{
    /**
     * Confirm that the given password is valid for the given user.
     */
    public function __invoke(StatefulGuard $guard, $user, string $password): bool
    {
        return $guard->validate([
            Shopper::username() => $user->{Shopper::username()},
            'password' => $password,
        ]);
    }
}
