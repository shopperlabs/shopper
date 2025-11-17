<?php

declare(strict_types=1);

namespace Shopper\Actions\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Shopper\Core\Models\User;

class ConfirmPassword
{
    public function __invoke(StatefulGuard $guard, User $user, string $password): bool
    {
        return $guard->validate([
            'email' => $user->email,
            'password' => $password,
        ]);
    }
}
