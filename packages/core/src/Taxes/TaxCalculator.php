<?php

declare(strict_types=1);

namespace Shopper\Core\Taxes;

use Illuminate\Support\Facades\Cache;
use Shopper\Core\Contracts\TaxableItem;
use Shopper\Core\Contracts\TaxCalculationProvider;
use Shopper\Core\Models\Contracts\TaxZone;

final class TaxCalculator
{
    /** @var array<string, TaxCalculationProvider> */
    private array $providerCache = [];

    /** @var array<string, ?TaxZone> */
    private array $zoneCache = [];

    public function __construct(
        private readonly TaxCalculationProvider $defaultProvider,
    ) {}

    /**
     * Calculate tax lines for a single item.
     *
     * @return array<int, TaxLine>
     */
    public function calculate(TaxableItem $item, TaxCalculationContext $context): array
    {
        $enriched = $this->enrichContext($context);
        $provider = $this->resolveProvider($enriched);

        return $provider->getTaxLines($item, $enriched);
    }

    /**
     * Calculate tax lines for multiple items.
     *
     * @param  array<int, TaxableItem>  $items
     * @return array<int, array<int, TaxLine>>
     */
    public function calculateMany(array $items, TaxCalculationContext $context): array
    {
        $enriched = $this->enrichContext($context);
        $provider = $this->resolveProvider($enriched);

        return array_map(
            fn (TaxableItem $item): array => $provider->getTaxLines($item, $enriched),
            $items,
        );
    }

    /**
     * Zone resolution is memoized for the request, then shared across
     * requests through a versioned cache: tax zones almost never change,
     * yet every cart calculation used to re-run this lookup. Saving or
     * deleting a zone bumps the version and orphans every cached entry.
     */
    public function resolveZone(TaxCalculationContext $context): ?TaxZone
    {
        $key = $context->cacheKey();

        if (array_key_exists($key, $this->zoneCache)) {
            return $this->zoneCache[$key];
        }

        $version = (int) Cache::get('shopper.tax-zones.version', 0);

        /** @var array{zone: ?TaxZone} $cached */
        $cached = Cache::flexible(
            "shopper.tax-zone.{$version}.{$key}",
            [3600, 86400],
            fn (): array => ['zone' => $this->lookupZone($context)],
        );

        return $this->zoneCache[$key] = $cached['zone'];
    }

    private function lookupZone(TaxCalculationContext $context): ?TaxZone
    {
        if ($context->provinceCode) {
            $zone = resolve(TaxZone::class)::query()
                ->whereHas('country', fn ($q) => $q->where('cca2', $context->countryCode))
                ->where('province_code', $context->provinceCode)
                ->first();

            if ($zone) {
                return $zone;
            }
        }

        return resolve(TaxZone::class)::query()
            ->whereHas('country', fn ($q) => $q->where('cca2', $context->countryCode))
            ->whereNull('province_code')
            ->first();
    }

    private function enrichContext(TaxCalculationContext $context): TaxCalculationContext
    {
        if ($context->resolvedZone) {
            return $context;
        }

        $zone = $this->resolveZone($context);

        return new TaxCalculationContext(
            countryCode: $context->countryCode,
            provinceCode: $context->provinceCode,
            customerId: $context->customerId,
            resolvedZone: $zone,
        );
    }

    private function resolveProvider(TaxCalculationContext $context): TaxCalculationProvider
    {
        $key = $context->cacheKey();

        if (isset($this->providerCache[$key])) {
            return $this->providerCache[$key];
        }

        $taxZone = $context->resolvedZone;

        if ($taxZone?->provider_id) {
            $providerModel = $taxZone->provider;

            if ($providerModel?->isEnabled()) {
                return $this->providerCache[$key] = app()->make(
                    TaxCalculationProvider::class,
                    ['provider' => $providerModel->identifier],
                );
            }
        }

        return $this->providerCache[$key] = $this->defaultProvider;
    }
}
