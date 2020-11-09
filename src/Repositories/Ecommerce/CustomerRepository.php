<?php

namespace Shopper\Framework\Repositories\Ecommerce;

use Shopper\Framework\Repositories\BaseRepository;
use Shopper\Framework\Models\User\User;

class CustomerRepository extends BaseRepository
{
    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return config('auth.providers.users.model', User::class);
    }
}
