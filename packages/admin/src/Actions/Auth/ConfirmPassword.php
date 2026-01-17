<?php

declare(strict_types=1);

namespace Shopper\Actions\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;

class ConfirmPassword
{
    public function __invoke(StatefulGuard $guard, Authenticatable $user, string $password): bool
    {
        return $guard->validate([
            'email' => $user->email, // @phpstan-ignore-line
            'password' => $password,
        ]);
    }
}
