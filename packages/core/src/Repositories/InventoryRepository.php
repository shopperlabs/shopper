<?php

declare(strict_types=1);

namespace Shopper\Core\Repositories;

use Shopper\Core\Models\Inventory;

final class InventoryRepository extends BaseRepository
{
    public function model(): string
    {
        return Inventory::class;
    }
}
