<?php

declare(strict_types=1);

namespace Shopper\Core\Repositories;

use Shopper\Core\Models\User;

class UserRepository extends BaseRepository
{
    public function model(): string
    {
        return config('auth.providers.users.model', User::class);
    }
}
