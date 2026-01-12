<?php

declare(strict_types=1);

namespace Shopper\Core\Listeners\Products;

use Illuminate\Contracts\Queue\ShouldQueue;
use Shopper\Core\Events\Products\ProductPurchased;
use Shopper\Core\Models\Inventory;

final class DecreaseProductInventory implements ShouldQueue
{
    public function handle(ProductPurchased $event): void
    {
        $product = $event->product;
        $quantity = $event->quantity;
        $inventoryId = $event->inventoryId ?: Inventory::query()->scopes('default')->firstOrFail()->id;

        $product->decreaseStock(
            inventoryId: $inventoryId,
            quantity: $quantity,
            arguments: [
                'event' => __('shopper::pages/products.inventory.remove'),
                'old_quantity' => $product->stockInventory($inventoryId),
            ]
        );
    }
}
