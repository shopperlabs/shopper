<?php

declare(strict_types=1);

namespace Shopper\Core\Repositories;

use Shopper\Core\Models\Discount;

final class DiscountRepository extends BaseRepository
{
    public function model(): string
    {
        return Discount::class;
    }
}
