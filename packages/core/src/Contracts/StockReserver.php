<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Contracts\Stockable;

interface StockReserver
{
    /**
     * Reserve stock for a product under a row lock and return the quantity
     * actually reserved. A value lower than the requested quantity signals
     * insufficient stock; back-ordered products always reserve the full
     * quantity, letting the ledger go negative.
     *
     * @param  Model&Stockable  $product
     */
    public function reserve(Stockable $product, int $quantity, ?Model $reference = null, ?int $userId = null): int;
}
