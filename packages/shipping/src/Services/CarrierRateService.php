<?php

declare(strict_types=1);

namespace Shopper\Shipping\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Zone;
use Shopper\Shipping\Contracts\ShippingDriver;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\DataTransferObjects\ZoneRateResult;
use Shopper\Shipping\Facades\Shipping;
use Throwable;

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
     * Get the logo URL for a carrier from its driver.
     */
    public function getLogoUrl(Carrier $carrier): ?string
    {
        return $carrier->logo();
    }

    public function getLogoHtml(Carrier $carrier): string
    {
        $logo = $this->getLogoUrl($carrier);

        return $logo
            ? '<img src="'.e($logo).'" alt="'.e($carrier->name).'" class="size-5 rounded object-contain" />'
            : '<span class="size-5 rounded bg-sh-skeleton"></span>';
    }

    /**
     * @return array<int, string>
     */
    public function getCarrierSelectOptions(): array
    {
        return Carrier::query()
            ->enabled()
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
        return $this->getZoneRates($zone, $from, $to, $packages)->rates;
    }

    /**
     * Get all available rates for a zone, fault-isolated per carrier.
     *
     * A carrier whose API times out or errors is skipped and reported in the
     * result instead of failing every other carrier. Manual rates only need
     * the zone; API rates are skipped when either address is missing, so the
     * caller can quote a cart before the customer typed a shipping address.
     *
     * @param  array<int, Package>  $packages
     */
    public function getZoneRates(
        Zone $zone,
        ?Address $from,
        ?Address $to,
        array $packages
    ): ZoneRateResult {
        $failedCarriers = [];

        $rates = $zone->carriers()
            ->where('is_enabled', true)
            ->get()
            ->flatMap(function (Carrier $carrier) use ($zone, $from, $to, $packages, &$failedCarriers): Collection {
                $driver = $this->resolveDriver($carrier);

                if (! $driver?->isConfigured()) {
                    return $this->getManualRates($carrier, $zone);
                }

                if (! $from || ! $to) {
                    return collect();
                }

                try {
                    return $this->getCachedApiRates($carrier, $driver, $from, $to, $packages);
                } catch (Throwable $exception) {
                    Log::warning('Shipping rates unavailable for carrier.', [
                        'carrier' => $carrier->name,
                        'driver' => $carrier->driver,
                        'exception' => $exception->getMessage(),
                    ]);

                    $failedCarriers[] = $carrier->name;

                    return collect();
                }
            });

        return new ZoneRateResult(
            rates: $rates->values(),
            failedCarriers: $failedCarriers,
        );
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
     * Carrier APIs are slow and rate-limited, so computed quotes are kept for
     * a short window. The key derives from the full rating input (addresses
     * and packages), never from a cart: any address fix or quantity change
     * naturally misses the cache instead of serving a stale price.
     *
     * @param  array<int, Package>  $packages
     * @return Collection<int, ShippingRate>
     */
    private function getCachedApiRates(
        Carrier $carrier,
        ShippingDriver $driver,
        Address $from,
        Address $to,
        array $packages
    ): Collection {
        $seconds = (int) config('shopper.shipping.rates_cache_ttl', 600);

        if ($seconds <= 0) {
            return $this->getApiRates($driver, $from, $to, $packages);
        }

        $key = sprintf(
            'shopper.shipping.rates.%s.%s',
            $carrier->driver,
            md5(serialize([
                $from->toArray(),
                $to->toArray(),
                array_map(fn (Package $package): array => $package->toArray(), $packages),
            ])),
        );

        return Cache::remember(
            $key,
            $seconds,
            fn (): Collection => $this->getApiRates($driver, $from, $to, $packages),
        );
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
            serviceCode: $option->public_id ?? $option->id,
            serviceName: $option->name,
            amount: $option->price,
            currency: $zone->currency->code ?? shopper_currency(),
            carrierCode: $carrier->slug ?? $carrier->name,
            priceType: 'flat',
        ));
    }
}
