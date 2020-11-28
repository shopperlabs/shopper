<?php

namespace Shopper\Framework\Repositories;

use Shopper\Framework\Models\Shop\Discount;

class DiscountRepository extends BaseRepository
{
    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return Discount::class;
    }
}
