<?php

declare(strict_types=1);

namespace Shopper\Actions;

use Illuminate\Contracts\Auth\StatefulGuard;

final class ConfirmPassword
{
    public function __invoke(StatefulGuard $guard, $user, string $password): bool
    {
        return $guard->validate([
            'email' => $user->email,
            'password' => $password,
        ]);
    }
}
