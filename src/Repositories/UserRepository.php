<?php

namespace Shopper\Framework\Repositories;

use Shopper\Framework\Models\User\User;

class UserRepository extends BaseRepository
{
    /**
     * Specify Model class name.
     */
    public function model()
    {
        return config('auth.providers.users.model', User::class);
    }
}
