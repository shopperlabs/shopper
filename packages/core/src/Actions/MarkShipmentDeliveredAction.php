<?php

declare(strict_types=1);

namespace Shopper\Core\Actions;

use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Events\Orders\OrderShipmentDelivered;
use Shopper\Core\Models\OrderShipping;

final class MarkShipmentDeliveredAction
{
    /**
     * @param  array{
     *     description?: string,
     *     location?: string,
     *     latitude?: float,
     *     longitude?: float,
     *     metadata?: array<string, mixed>,
     * }  $context
     */
    public function execute(OrderShipping $shipment, array $context = []): void
    {
        if (! $shipment->canBeDelivered()) {
            return;
        }

        (new RecordShipmentEventAction)->execute($shipment, ShipmentStatus::Delivered, $context);

        event(new OrderShipmentDelivered($shipment->order, $shipment));
    }
}
