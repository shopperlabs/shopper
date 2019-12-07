<?php

namespace Shopper\Framework\Repositories\Shop;

use Shopper\Framework\Models\Shop\Shop;
use Shopper\Framework\Repositories\BaseRepository;

class ShopRepository extends BaseRepository
{
    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return Shop::class;
    }
}
