<?php

declare(strict_types=1);

namespace Shopper\Core\Repositories;

use Shopper\Core\Models\InventoryHistory;

class InventoryHistoryRepository extends BaseRepository
{
    public function model(): string
    {
        return InventoryHistory::class;
    }
}
