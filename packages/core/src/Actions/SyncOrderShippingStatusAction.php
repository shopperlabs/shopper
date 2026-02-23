<?php

declare(strict_types=1);

namespace Shopper\Core\Actions;

use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Events\Orders\OrderShipped;
use Shopper\Core\Models\Contracts\Order;

final class SyncOrderShippingStatusAction
{
    public function execute(Order $order): void
    {
        $items = $order->items()->get();
        $total = $items->count();

        if ($total === 0) {
            return;
        }

        $cancelled = $items->where('fulfillment_status', FulfillmentStatus::Cancelled)->count();
        $delivered = $items->where('fulfillment_status', FulfillmentStatus::Delivered)->count();
        $shipped = $items->where('fulfillment_status', FulfillmentStatus::Shipped)->count();
        $fulfilled = $shipped + $delivered;

        $shippingStatus = match (true) {
            $cancelled === $total => ShippingStatus::Returned,
            $cancelled > 0 && ($fulfilled + $cancelled) === $total => ShippingStatus::PartiallyReturned,
            $fulfilled === 0 => ShippingStatus::Unfulfilled,
            $delivered === $total => ShippingStatus::Delivered,
            $delivered > 0 => ShippingStatus::PartiallyDelivered,
            ($fulfilled + $cancelled) === $total => ShippingStatus::Shipped,
            default => ShippingStatus::PartiallyShipped,
        };

        $previousStatus = $order->shipping_status;

        $order->update(['shipping_status' => $shippingStatus]);

        if ($shippingStatus === ShippingStatus::Shipped && $previousStatus !== ShippingStatus::Shipped) {
            event(new OrderShipped($order));
        }
    }
}
