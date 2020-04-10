<?php

namespace Shopper\Framework\Repositories\Ecommerce;

use Shopper\Framework\Repositories\BaseRepository;

class ProductRepository extends BaseRepository
{
    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return config('shopper.models.product');
    }
}
