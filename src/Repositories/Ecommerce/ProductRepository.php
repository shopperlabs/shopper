<?php

namespace Shopper\Framework\Repositories\Ecommerce;

use Shopper\Framework\Repositories\BaseRepository;

class ProductRepository extends BaseRepository
{
    /**
     * Specify Model class name.
     */
    public function model()
    {
        return config('shopper.system.models.product');
    }
}
