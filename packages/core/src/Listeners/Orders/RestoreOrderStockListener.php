<?php

declare(strict_types=1);

namespace Shopper\Core\Listeners\Orders;

use Illuminate\Contracts\Queue\ShouldQueue;
use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Models\Contracts\Stockable;

final class RestoreOrderStockListener implements ShouldQueue
{
    public function handle(OrderCancelled $event): void
    {
        $order = $event->order->load('items.product');

        foreach ($order->items as $item) {
            if (! $item->product instanceof Stockable) {
                continue;
            }

            $reservations = $item->product->inventoryHistories()
                ->where('reference_type', $order->getMorphClass())
                ->where('reference_id', $order->getKey())
                ->where('quantity', '<', 0)
                ->get();

            foreach ($reservations as $reservation) {
                $item->product->mutateStock(
                    inventoryId: $reservation->inventory_id,
                    quantity: abs($reservation->quantity),
                    arguments: [
                        'event' => __('shopper-core::status.stock.cancelled'),
                        'old_quantity' => $item->product->stockInventory($reservation->inventory_id),
                        'reference' => $order,
                        'user_id' => $order->customer_id,
                    ],
                );
            }
        }
    }
}
