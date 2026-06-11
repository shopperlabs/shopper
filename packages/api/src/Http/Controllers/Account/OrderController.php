<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Account;

use Illuminate\Http\Request;
use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\OrderResource;
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Core\Models\OrderItem;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class OrderController
{
    use BuildsApiQueries;

    public function index(Request $request): JsonApiResourceCollection
    {
        $query = resolve(OrderContract::class)::query()
            ->where('customer_id', $request->user()?->getAuthIdentifier())
            ->addSelect([
                // The item total is an accessor ((unit_price * qty) - discount),
                // so the order total is aggregated with the same expression.
                'items_total' => OrderItem::query()
                    ->selectRaw('COALESCE(SUM((unit_price_amount * quantity) - discount_amount), 0)')
                    ->whereColumn('order_id', shopper_table('orders').'.id'),
            ]);

        return OrderResource::collection(
            $this->paginated('order', $query, defaultSort: '-created_at')
        );
    }
}
