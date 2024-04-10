<?php

declare(strict_types=1);

namespace Shopper\Actions\Store;

use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;

final class InitialQuantityInventory
{
    public function handle($quantity, Product $product): void
    {
        /** @var Inventory $inventory */
        $inventory = Inventory::default()->first();

        if ($inventory) {
            $product->mutateStock(
                inventoryId: $inventory->id,
                quantity: (int) $quantity,
                arguments: [
                    'event' => __('shopper::pages/products.inventory.initial'),
                    'old_quantity' => $quantity,
                ]
            );
        }
    }
}
