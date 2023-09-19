<?php

declare(strict_types=1);

namespace Shopper\Framework\Actions;

use Illuminate\Contracts\Auth\StatefulGuard;
use Shopper\Framework\Shopper;

final class ConfirmPassword
{
    public function __invoke(StatefulGuard $guard, $user, string $password): bool
    {
        return $guard->validate([
            Shopper::username() => $user->{Shopper::username()},
            'password' => $password,
        ]);
    }
}
