<?php

declare(strict_types=1);

namespace Shopper\Actions\Store;

use Shopper\Core\Models\Inventory;

final readonly class InitialQuantityInventory
{
    public function __invoke(int $quantity, $product): void
    {
        /** @var Inventory $inventory */
        $inventory = Inventory::query()->scopes('default')->first();

        if ($inventory) {
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
