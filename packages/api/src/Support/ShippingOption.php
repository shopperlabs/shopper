<?php

declare(strict_types=1);

namespace Shopper\Api\Support;

use Shopper\Shipping\DataTransferObjects\ShippingRate;

/**
 * A selectable delivery choice for a cart. The composite id is the binding
 * contract of the checkout: the client sends it back when picking a method,
 * and the server re-resolves the price from it. The displayed amount is
 * advisory and is never trusted at order placement.
 */
final readonly class ShippingOption
{
    public function __construct(
        public ShippingRate $rate,
        public ?string $carrierName = null,
    ) {}

    public function id(): string
    {
        return "{$this->rate->carrierCode}:{$this->rate->serviceCode}";
    }
}
