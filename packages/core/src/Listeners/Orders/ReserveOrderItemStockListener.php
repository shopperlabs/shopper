<?php

declare(strict_types=1);

namespace Shopper\Core\Listeners\Orders;

use Illuminate\Contracts\Queue\ShouldQueue;
use Shopper\Core\Contracts\StockAllocator;
use Shopper\Core\Events\Orders\OrderItemCreated;
use Shopper\Core\Models\Contracts\Stockable;

final readonly class ReserveOrderItemStockListener implements ShouldQueue
{
    public function __construct(
        private StockAllocator $allocator,
    ) {}

    public function handle(OrderItemCreated $event): void
    {
        $item = $event->orderItem->load(['product', 'order']);

        if (! $item->product instanceof Stockable) {
            return;
        }

        $allocations = $this->allocator->allocate($item->product, $item->quantity);

        foreach ($allocations as $allocation) {
            $item->product->decreaseStock(
                inventoryId: $allocation->inventoryId,
                quantity: $allocation->quantity,
                arguments: [
                    'event' => __('shopper-core::status.stock.reserved'),
                    'old_quantity' => $item->product->stockInventory($allocation->inventoryId),
                    'reference' => $item->order,
                ],
            );
        }
    }
}
