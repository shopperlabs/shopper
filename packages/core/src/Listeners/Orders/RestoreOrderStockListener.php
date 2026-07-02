<?php

declare(strict_types=1);

namespace Shopper\Core\Listeners\Orders;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Models\Contracts\Stockable;

final class RestoreOrderStockListener implements ShouldQueue
{
    /**
     * Each reservation row is stamped `released_at` in the same transaction
     * as its compensation, under a row lock: a queue retry after a partial
     * run only compensates the rows the previous attempt never stamped, so
     * the restock can never be applied twice.
     */
    public function handle(OrderCancelled $event): void
    {
        $order = $event->order->load('items.product');

        foreach ($order->items as $item) {
            if (! $item->product instanceof Stockable) {
                continue;
            }

            DB::transaction(function () use ($order, $item): void {
                $reservations = $item->product->inventoryHistories()
                    ->where('reference_type', $order->getMorphClass())
                    ->where('reference_id', $order->getKey())
                    ->where('quantity', '<', 0)
                    ->whereNull('released_at')
                    ->lockForUpdate()
                    ->get();

                foreach ($reservations as $reservation) {
                    $item->product->mutateStock(
                        inventoryId: $reservation->inventory_id,
                        quantity: abs($reservation->quantity),
                        oldQuantity: $item->product->stockInventory($reservation->inventory_id),
                        event: __('shopper-core::status.stock.cancelled'),
                        reference: $order,
                    );

                    $reservation->updateQuietly(['released_at' => now()]);
                }
            });
        }
    }
}
