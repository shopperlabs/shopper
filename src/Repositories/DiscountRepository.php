<?php

declare(strict_types=1);

namespace Shopper\Framework\Repositories;

use Shopper\Framework\Models\Shop\Discount;

class DiscountRepository extends BaseRepository
{
    public function model(): string
    {
        return Discount::class;
    }
}
