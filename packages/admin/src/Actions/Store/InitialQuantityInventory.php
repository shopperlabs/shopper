<?php

declare(strict_types=1);

namespace Shopper\Actions\Store;

use Shopper\Core\Models\Contracts\Inventory;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;

final class InitialQuantityInventory
{
    public function __invoke(int $quantity, Product|ProductVariant $product): void
    {
        /** @var ?Inventory $inventory */
        $inventory = resolve(Inventory::class)::query()->scopes('default')->first();

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
