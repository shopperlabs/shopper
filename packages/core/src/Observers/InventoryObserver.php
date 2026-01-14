<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Models\Contracts\Inventory;

class InventoryObserver
{
    public function creating(Inventory $inventory): void
    {
        $this->ensureOnlyOneIsDefault($inventory);
    }

    public function updating(Inventory $inventory): void
    {
        $this->ensureOnlyOneIsDefault($inventory);
    }

    /**
     * Ensures that only one default inventory exists.
     */
    private function ensureOnlyOneIsDefault(Inventory $inventory): void
    {
        if ($inventory->is_default) {
            /** @var Inventory|null $defaultInventory */
            $defaultInventory = resolve(Inventory::class)::query()
                ->where('id', '!=', $inventory->id)
                ->where('is_default', false)
                ->first();

            if ($defaultInventory instanceof Inventory) {
                $defaultInventory->updateQuietly(['is_default' => false]);
            }
        }
    }
}
