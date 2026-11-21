<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Order;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @mixin Order
 */
class OrderResource extends JsonApiResource
{
    public static function filters(): array
    {
        return [
            AllowedFilter::exact('status'),
        ];
    }

    public static function sorts(): array
    {
        return [
            'created_at',
        ];
    }

    public static function includes(): array
    {
        return [
            'items',
            'shippingAddress',
            'billingAddress',
            'paymentMethod',
            'shippings',
            'shippings.events',
            'refund',
        ];
    }

    public static function includeLoads(): array
    {
        return [
            'shippings' => ['shippings.carrier'],
        ];
    }

    final public function toType(Request $request): string
    {
        return 'orders';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'number' => $this->number,
            'status' => $this->status->value,
            'payment_status' => $this->payment_status->value,
            'shipping_status' => $this->shipping_status->value,
            'total' => (int) $this->resource->getAttribute('items_total'),
            'tax_amount' => $this->tax_amount,
            'shipping_amount' => $this->shipping_amount,
            'price_amount' => $this->price_amount,
            'currency_code' => $this->currency_code,
            // the buyer email is only exposed to the authenticated owner.
            'email' => $request->user('sanctum') ? $this->email : null,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        return [
            'items' => OrderItemResource::class,
            'shippingAddress' => OrderAddressResource::class,
            'billingAddress' => OrderAddressResource::class,
            'paymentMethod' => PaymentMethodResource::class,
            'shippings' => OrderShippingResource::class,
            'refund' => OrderRefundResource::class,
        ];
    }
}
