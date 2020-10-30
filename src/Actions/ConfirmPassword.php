<?php

namespace Shopper\Framework\Actions;

use Illuminate\Contracts\Auth\StatefulGuard;

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
    public function __invoke(StatefulGuard $guard, $user, string $password)
    {
        $username = config('shopper.auth.username');

        return $guard->validate([
            $username => $user->{$username},
            'password' => $password,
        ]);
    }
}
