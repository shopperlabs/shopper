<?php

declare(strict_types=1);

namespace Shopper\Shipping\Services;

use Illuminate\Support\Collection;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Zone;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\ShippingRate;

final class CarrierRateService
{
    /**
     * Get shipping rates for a carrier.
     *
     * If the carrier uses an API driver (UPS, FedEx, etc.), rates are fetched from the API.
     * Otherwise, manual rates from CarrierOptions are returned.
     *
     * @param  array<int, Package>  $packages
     * @return Collection<int, ShippingRate>
     */
    public function getRatesForCarrier(
        Carrier $carrier,
        Address $from,
        Address $to,
        array $packages,
        ?Zone $zone = null
    ): Collection {
        if ($carrier->usesApiDriver() && $carrier->isDriverConfigured()) {
            return $this->getApiRates($carrier, $from, $to, $packages);
        }

        return $this->getManualRates($carrier, $zone);
    }

    /**
     * Get all available rates for a zone.
     *
     * @param  array<int, Package>  $packages
     * @return Collection<int, ShippingRate>
     */
    public function getRatesForZone(
        Zone $zone,
        Address $from,
        Address $to,
        array $packages
    ): Collection {
        return $zone->carriers()
            ->where('is_enabled', true)
            ->get()
            ->flatMap(fn (Carrier $carrier): Collection => $this->getRatesForCarrier(
                carrier: $carrier,
                from: $from,
                to: $to,
                packages: $packages,
                zone: $zone,
            ));
    }

    /**
     * Get rates from the carrier's API driver.
     *
     * @param  array<int, Package>  $packages
     * @return Collection<int, ShippingRate>
     */
    private function getApiRates(
        Carrier $carrier,
        Address $from,
        Address $to,
        array $packages
    ): Collection {
        $driver = $carrier->getShippingDriver();

        if (! $driver) {
            return collect();
        }

        return $driver->calculateRates($from, $to, $packages);
    }

    /**
     * Get manual rates from CarrierOptions.
     *
     * @return Collection<int, ShippingRate>
     */
    private function getManualRates(Carrier $carrier, ?Zone $zone): Collection
    {
        $query = $carrier->options()->where('is_enabled', true);

        if ($zone) {
            $query->where('zone_id', $zone->id);
        }

        return $query->get()->map(fn (CarrierOption $option): ShippingRate => new ShippingRate(
            serviceCode: $option->id,
            serviceName: $option->name,
            amount: $option->getRawOriginal('price'),
            currency: $zone->currency->code ?? shopper_currency(),
            carrierCode: $carrier->slug ?? $carrier->name,
        ));
    }
}
