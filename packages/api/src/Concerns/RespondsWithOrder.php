<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;

trait RespondsWithOrder
{
    /**
     * The order total exposed by the API aggregates the same expression as
     * the OrderItem total accessor ((unit_price * qty) - discount), so every
     * order response carries it without loading the items.
     *
     * @return Builder<Order>
     */
    protected function orderQuery(): Builder
    {
        return resolve(OrderContract::class)::query()
            ->addSelect([
                'items_total' => OrderItem::query()
                    ->selectRaw('COALESCE(SUM((unit_price_amount * quantity) - discount_amount), 0)')
                    ->whereColumn('order_id', shopper_table('orders').'.id'),
            ]);
    }
}
