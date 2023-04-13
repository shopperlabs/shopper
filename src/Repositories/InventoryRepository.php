<?php

declare(strict_types=1);

namespace Shopper\Framework\Repositories;

use Shopper\Framework\Models\Shop\Inventory\Inventory;

class InventoryRepository extends BaseRepository
{
    public function model(): string
    {
        return Inventory::class;
    }
}
