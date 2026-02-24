<?php

declare(strict_types=1);

namespace Shopper\Core\Taxes;

use Shopper\Core\Contracts\TaxableItem;
use Shopper\Core\Contracts\TaxCalculationProvider;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxZone;

final readonly class SystemTaxProvider implements TaxCalculationProvider
{
    public function identifier(): string
    {
        return 'system';
    }

    /**
     * @return array<int, TaxLine>
     */
    public function getTaxLines(TaxableItem $item, TaxCalculationContext $context): array
    {
        $taxZone = $this->resolveTaxZone($context);

        if (! $taxZone) {
            return [];
        }

        $taxRate = $this->resolveApplicableTaxRate($taxZone, $item);

        if (! $taxRate) {
            return [];
        }

        $amount = $this->calculateTaxAmount(
            $item->getTaxableAmount() * $item->getQuantity(),
            $taxRate->rate,
            $taxZone->is_tax_inclusive,
        );

        return [
            new TaxLine(
                taxRateId: $taxRate->id,
                name: $taxRate->name,
                code: $taxRate->code,
                rate: $taxRate->rate,
                amount: $amount,
            ),
        ];
    }

    private function resolveTaxZone(TaxCalculationContext $context): ?TaxZone
    {
        if ($context->provinceCode) {
            $zone = TaxZone::query()
                ->where('country_code', $context->countryCode)
                ->where('province_code', $context->provinceCode)
                ->first();

            if ($zone) {
                return $zone;
            }
        }

        return TaxZone::query()
            ->where('country_code', $context->countryCode)
            ->whereNull('province_code')
            ->first();
    }

    private function resolveApplicableTaxRate(TaxZone $taxZone, TaxableItem $item): ?TaxRate
    {
        $rates = $taxZone->rates()->with('rules')->get();

        foreach ($rates as $rate) {
            if ($rate->rules->isEmpty()) {
                continue;
            }

            foreach ($rate->rules as $rule) {
                if ($this->ruleMatchesItem($rule, $item)) {
                    return $rate;
                }
            }
        }

        return $rates->firstWhere('is_default', true);
    }

    private function ruleMatchesItem(\Shopper\Core\Models\TaxRateRule $rule, TaxableItem $item): bool
    {
        return match ($rule->reference_type) {
            'product_type' => $item->getProductType() === $rule->reference_id,
            'product' => $item->getProductId() !== null && (string) $item->getProductId() === $rule->reference_id,
            'category' => in_array((int) $rule->reference_id, $item->getCategoryIds(), true),
            default => false,
        };
    }

    private function calculateTaxAmount(int $totalPrice, float $rate, bool $isInclusive): int
    {
        if ($isInclusive) {
            return (int) round($totalPrice - ($totalPrice / (1 + $rate / 100)));
        }

        return (int) round($totalPrice * $rate / 100);
    }
}
