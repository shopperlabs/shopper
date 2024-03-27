<?php

declare(strict_types=1);

namespace Shopper\Observers;

use Shopper\Core\Models\Inventory;

class InventoryObserver
{
    public function creating(Inventory $inventory): void
    {
        if ($inventory->is_default) {
            Inventory::query()->update(['is_default' => false]);
        }
    }

    public function updating(Inventory $inventory): void
    {
        if ($inventory->is_default) {
            Inventory::query()->update(['is_default' => false]);
        }
    }
}
