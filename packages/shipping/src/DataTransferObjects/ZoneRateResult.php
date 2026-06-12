<?php

declare(strict_types=1);

namespace Shopper\Shipping\DataTransferObjects;

use Illuminate\Support\Collection;

/**
 * Rates collected across every carrier of a zone, with the carriers that
 * failed to quote kept aside so callers can degrade gracefully instead of
 * losing the whole result when a single carrier API is down.
 */
final readonly class ZoneRateResult
{
    /**
     * @param  Collection<int, ShippingRate>  $rates
     * @param  array<int, string>  $failedCarriers
     */
    public function __construct(
        public Collection $rates,
        public array $failedCarriers = [],
    ) {}
}
