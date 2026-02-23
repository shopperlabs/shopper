<?php

declare(strict_types=1);

namespace Shopper\Shipping\Services;

use Illuminate\Support\Collection;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Zone;
use Shopper\Shipping\Contracts\ShippingDriver;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\Facades\Shipping;

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
        $driver = $this->resolveDriver($carrier);

        if ($driver?->isConfigured()) {
            return $this->getApiRates($driver, $from, $to, $packages);
        }

        return $this->getManualRates($carrier, $zone);
    }

    /**
     * Resolve the shipping driver for a carrier.
     */
    public function resolveDriver(Carrier $carrier): ?ShippingDriver
    {
        if (! filled($carrier->driver) || $carrier->driver === 'manual') {
            return null;
        }

        return Shipping::driver($carrier->driver);
    }

    /**
     * Check if a carrier uses an API driver and is properly configured.
     */
    public function isDriverConfigured(Carrier $carrier): bool
    {
        return $this->resolveDriver($carrier)?->isConfigured() ?? false;
    }

    /**
     * Get the logo URL for a carrier, with driver logo as fallback.
     */
    public function getLogoUrl(Carrier $carrier): ?string
    {
        return $carrier->logoUrl() ?? $this->resolveDriver($carrier)?->logo();
    }

    public function getLogoHtml(Carrier $carrier): string
    {
        $logo = $this->getLogoUrl($carrier);

        return $logo
            ? '<img src="'.e($logo).'" alt="'.e($carrier->name).'" class="size-5 rounded object-contain" />'
            : '<span class="size-5 rounded bg-gray-200 dark:bg-white/30"></span>';
    }

    /**
     * @return array<int, string>
     */
    public function getCarrierSelectOptions(): array
    {
        return Carrier::query()
            ->enabled()
            ->with('media')
            ->get()
            ->mapWithKeys(fn (Carrier $carrier): array => [
                $carrier->id => '<div class="flex items-center gap-2">'
                    .$this->getLogoHtml($carrier)
                    .'<span>'.e($carrier->name).'</span>'
                    .'</div>',
            ])
            ->all();
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
        ShippingDriver $driver,
        Address $from,
        Address $to,
        array $packages
    ): Collection {
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
