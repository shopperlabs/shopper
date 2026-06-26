<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Contracts\Stockable;

interface StockReserver
{
    /**
     * @param  Model&Stockable  $product
     */
    public function reserve(Stockable $product, int $quantity, ?Model $reference = null, ?int $userId = null): int;
}
