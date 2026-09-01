<?php

declare(strict_types=1);

namespace Shopper\Core\Actions;

use Illuminate\Support\Facades\DB;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Events\Orders\OrderShipmentCreated;
use Shopper\Core\Exceptions\CannotCreateEmptyShipmentException;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\OrderShipping;

final class CreateShipmentAction
{
    /**
     * @param  list<int>  $itemIds
     */
    public function execute(
        Order $order,
        ?int $carrierId,
        array $itemIds,
        ?string $trackingNumber = null,
        ?string $trackingUrl = null,
        ?string $description = null,
    ): OrderShipping {
        return DB::transaction(function () use ($order, $carrierId, $itemIds, $trackingNumber, $trackingUrl, $description): OrderShipping {
            $shipment = OrderShipping::query()->create([
                'order_id' => $order->id,
                'carrier_id' => $carrierId,
                'status' => ShipmentStatus::Pending,
                'tracking_number' => $trackingNumber,
                'tracking_url' => $trackingUrl,
            ]);

            $shipment->logEvent(ShipmentStatus::Pending, [
                'description' => $description,
                'causer_id' => auth()->id(),
            ]);

            $attached = $order->items()
                ->whereIn('id', $itemIds)
                ->whereNull('order_shipping_id')
                ->update(['order_shipping_id' => $shipment->id]);

            if ($attached === 0) {
                throw CannotCreateEmptyShipmentException::forOrder($order->id);
            }

            event(new OrderShipmentCreated($order, $shipment));

            return $shipment;
        });
    }
}
