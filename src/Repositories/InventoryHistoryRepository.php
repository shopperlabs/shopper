<?php

declare(strict_types=1);

namespace Shopper\Framework\Repositories;

use Shopper\Framework\Models\Shop\Inventory\InventoryHistory;

class InventoryHistoryRepository extends BaseRepository
{
    public function model(): string
    {
        return InventoryHistory::class;
    }
}
