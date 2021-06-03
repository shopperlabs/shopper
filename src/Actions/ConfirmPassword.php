<?php

namespace Shopper\Framework\Actions;

use Illuminate\Contracts\Auth\StatefulGuard;
use Shopper\Framework\Shopper;

class ConfirmPassword
{
    /**
     * Confirm that the given password is valid for the given user.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @param  mixed  $user
     * @param  string  $password
     * @return bool
     */
    public function __invoke(StatefulGuard $guard, $user, string $password): bool
    {
        return $guard->validate([
            Shopper::username() => $user->{Shopper::username()},
            'password' => $password,
        ]);
    }
}
