<?php

declare(strict_types=1);

namespace Shopper\Core\Actions;

use Illuminate\Database\Eloquent\Builder;
use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Events\Orders\OrderCompleted;
use Shopper\Core\Models\Contracts\Order;

final class CompleteOrderIfFulfilledAction
{
    public function execute(Order $order): void
    {
        if ($order->status !== OrderStatus::Processing || ! $order->isPaid()) {
            return;
        }

        $undelivered = $order->items()
            ->where(fn (Builder $query) => $query
                ->whereNull('fulfillment_status')
                ->orWhere('fulfillment_status', '!=', FulfillmentStatus::Delivered))
            ->exists();

        if ($undelivered) {
            return;
        }

        $order->transitionTo(OrderStatus::Completed);

        event(new OrderCompleted($order));
    }
}
