<?php

declare(strict_types=1);

namespace Shopper\Core\Actions;

use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\OrderShipping;
use Shopper\Core\Models\OrderShippingEvent;

final class RecordShipmentEventAction
{
    /**
     * @param  array{
     *     description?: string,
     *     location?: string,
     *     latitude?: float,
     *     longitude?: float,
     *     occurred_at?: \Carbon\CarbonInterface,
     *     metadata?: array<string, mixed>,
     * }  $context
     */
    public function execute(
        OrderShipping $shipment,
        ShipmentStatus $status,
        array $context = [],
    ): ?OrderShippingEvent {
        if (! $shipment->canTransitionTo($status)) {
            return null;
        }

        $shipment->update(['status' => $status]);

        $event = $shipment->logEvent($status, $context);

        match ($status) {
            ShipmentStatus::PickedUp => $this->onPickedUp($shipment, $event),
            ShipmentStatus::Delivered => $this->onDelivered($shipment, $event),
            ShipmentStatus::Returned => $this->onReturned($shipment, $event),
            default => null,
        };

        return $event;
    }

    private function onPickedUp(OrderShipping $shipment, OrderShippingEvent $event): void
    {
        $shipment->update(['shipped_at' => $event->occurred_at]);

        $shipment->items()
            ->where('fulfillment_status', FulfillmentStatus::Pending)
            ->update(['fulfillment_status' => FulfillmentStatus::Shipped]);

        (new SyncOrderShippingStatusAction)->execute($shipment->order);
    }

    private function onDelivered(OrderShipping $shipment, OrderShippingEvent $event): void
    {
        $shipment->update(['received_at' => $event->occurred_at]);

        $shipment->items()
            ->where('fulfillment_status', FulfillmentStatus::Shipped)
            ->update(['fulfillment_status' => FulfillmentStatus::Delivered]);

        $order = $shipment->order;

        (new SyncOrderShippingStatusAction)->execute($order);

        $allDelivered = $order->items()
            ->where('fulfillment_status', '!=', FulfillmentStatus::Delivered)
            ->doesntExist();

        if ($allDelivered && $order->status === OrderStatus::Processing) {
            $order->update(['status' => OrderStatus::Completed]);
        }
    }

    private function onReturned(OrderShipping $shipment, OrderShippingEvent $event): void
    {
        $shipment->update(['returned_at' => $event->occurred_at]);

        $shipment->items()->update([
            'fulfillment_status' => FulfillmentStatus::Cancelled,
        ]);

        (new SyncOrderShippingStatusAction)->execute($shipment->order);
    }
}
