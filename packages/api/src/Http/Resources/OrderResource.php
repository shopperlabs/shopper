<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Order;

/**
 * @mixin Order
 */
class OrderResource extends JsonApiResource
{
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
            'items' => fn () => OrderItemResource::collection($this->items),
            'shipping_address' => fn (): ?OrderAddressResource => $this->shippingAddress
                ? OrderAddressResource::make($this->shippingAddress)
                : null,
            'billing_address' => fn (): ?OrderAddressResource => $this->billingAddress
                ? OrderAddressResource::make($this->billingAddress)
                : null,
            'payment_method' => fn (): ?PaymentMethodResource => $this->paymentMethod
                ? PaymentMethodResource::make($this->paymentMethod)
                : null,
            'shippings' => fn () => OrderShippingResource::collection($this->shippings),
            'refund' => fn (): ?OrderRefundResource => $this->refund
                ? OrderRefundResource::make($this->refund)
                : null,
        ];
    }
}
