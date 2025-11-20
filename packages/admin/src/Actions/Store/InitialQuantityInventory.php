<?php

declare(strict_types=1);

namespace Shopper\Actions\Store;

use Shopper\Core\Models\Inventory;

final class InitialQuantityInventory
{
    public function __invoke(int $quantity, mixed $product): void
    {
        /** @var ?Inventory $inventory */
        $inventory = Inventory::query()->scopes('default')->first();

        if ($inventory instanceof Inventory) {
            $product->mutateStock(
                inventoryId: $inventory->id,
                quantity: $quantity,
                arguments: [
                    'event' => __('shopper::pages/products.inventory.initial'),
                    'old_quantity' => $quantity,
                ]
            );
        }
    }
}
