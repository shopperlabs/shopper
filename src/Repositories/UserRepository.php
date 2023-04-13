<?php

declare(strict_types=1);

namespace Shopper\Framework\Repositories;

use Shopper\Framework\Models\User\User;

class UserRepository extends BaseRepository
{
    public function model(): string
    {
        return config('auth.providers.users.model', User::class);
    }
}
