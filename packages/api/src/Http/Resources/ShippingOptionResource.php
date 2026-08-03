<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Api\Support\ShippingOption;

/**
 * Serializes a computed delivery choice, not an Eloquent model. The id is
 * the composite `{carrier_code}:{service_code}` the checkout sends back to
 * pick a method; the server re-prices from it, never from the amount.
 *
 * @property ShippingOption $resource
 */
class ShippingOptionResource extends JsonApiResource
{
    public function toId(Request $request): string
    {
        return $this->resource->id();
    }

    final public function toType(Request $request): string
    {
        return 'shipping-options';
    }

    public function toAttributes(Request $request): array
    {
        $rate = $this->resource->rate;

        return [
            'name' => $rate->serviceName,
            'carrier_name' => $this->resource->carrierName ?? $rate->carrierCode,
            'carrier_code' => $rate->carrierCode,
            'service_code' => (string) $rate->serviceCode,
            'amount' => $rate->amount,
            'currency' => $rate->currency,
            'price_type' => $rate->priceType,
            'estimated_days' => $rate->estimatedDays,
            'estimated_delivery' => $rate->estimatedDelivery,
        ];
    }
}
