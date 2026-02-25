<?php

declare(strict_types=1);

namespace Shopper\Core\Taxes;

use Shopper\Core\Contracts\TaxableItem;
use Shopper\Core\Contracts\TaxCalculationProvider;
use Shopper\Core\Models\TaxZone;

final readonly class TaxCalculator
{
    public function __construct(
        private TaxCalculationProvider $defaultProvider,
    ) {}

    /**
     * Calculate tax lines for a single item.
     *
     * @return array<int, TaxLine>
     */
    public function calculate(TaxableItem $item, TaxCalculationContext $context): array
    {
        $provider = $this->resolveProvider($context);

        return $provider->getTaxLines($item, $context);
    }

    /**
     * Calculate tax lines for multiple items.
     *
     * @param  array<int, TaxableItem>  $items
     * @return array<int, array<int, TaxLine>>
     */
    public function calculateMany(array $items, TaxCalculationContext $context): array
    {
        $provider = $this->resolveProvider($context);

        return array_map(
            fn (TaxableItem $item): array => $provider->getTaxLines($item, $context),
            $items,
        );
    }

    private function resolveProvider(TaxCalculationContext $context): TaxCalculationProvider
    {
        $taxZone = TaxZone::query()
            ->whereHas('country', fn ($q) => $q->where('cca2', $context->countryCode))
            ->when(
                $context->provinceCode,
                fn ($query) => $query->where('province_code', $context->provinceCode),
                fn ($query) => $query->whereNull('province_code'),
            )
            ->first();

        if ($taxZone?->provider_id) {
            $providerModel = $taxZone->provider;

            if ($providerModel?->isEnabled()) {
                return app()->make(TaxCalculationProvider::class, ['provider' => $providerModel->identifier]);
            }
        }

        return $this->defaultProvider;
    }
}
