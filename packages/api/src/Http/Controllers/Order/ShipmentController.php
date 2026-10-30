<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Order;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Shopper\Api\Http\Resources\JsonApiResource;
use Shopper\Api\Http\Resources\OrderShippingResource;
use Shopper\Core\Models\OrderShipping;
use Spatie\QueryBuilder\Exceptions\InvalidIncludeQuery;
use Spatie\QueryBuilder\QueryBuilderRequest;

final class ShipmentController
{
    public function show(Request $request, string $shipmentId): JsonApiResource
    {
        $requested = QueryBuilderRequest::fromRequest($request)->includes();
        $allowed = new Collection(['events']);
        $unknown = $requested->diff($allowed);

        if ($unknown->isNotEmpty()) {
            throw InvalidIncludeQuery::includesNotAllowed($unknown, $allowed);
        }

        $shipment = OrderShipping::query()
            ->with('carrier')
            ->when($requested->contains('events'), fn (Builder $query) => $query->with([
                'events' => fn (Relation $events): Relation => $events->oldest('occurred_at')->oldest('id'),
            ]))
            ->where('public_id', $shipmentId)
            ->firstOrFail();

        return OrderShippingResource::make($shipment);
    }
}
