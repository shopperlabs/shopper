<?php

namespace Shopper\Framework\Repositories;

use Shopper\Framework\Models\Shop\Discount;

class DiscountRepository extends BaseRepository
{
    /**
     * Specify Model class name.
     */
    public function model()
    {
        return Discount::class;
    }
}
