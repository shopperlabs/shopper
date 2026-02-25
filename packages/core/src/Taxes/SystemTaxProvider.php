<?php

declare(strict_types=1);

namespace Shopper\Core\Taxes;

use Illuminate\Database\Eloquent\Collection;
use Shopper\Core\Contracts\TaxableItem;
use Shopper\Core\Contracts\TaxCalculationProvider;
use Shopper\Core\Models\Contracts\TaxRate;
use Shopper\Core\Models\Contracts\TaxZone;
use Shopper\Core\Models\TaxRateRule;

final class SystemTaxProvider implements TaxCalculationProvider
{
    /** @var array<int, Collection<int, TaxRate>> */
    private array $ratesCache = [];

    public function identifier(): string
    {
        return 'system';
    }

    /**
     * @return array<int, TaxLine>
     */
    public function getTaxLines(TaxableItem $item, TaxCalculationContext $context): array
    {
        $taxZone = $context->resolvedZone;

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

    private function resolveApplicableTaxRate(TaxZone $taxZone, TaxableItem $item): ?TaxRate
    {
        $rates = $this->ratesCache[$taxZone->id] ??= $taxZone->rates()->with('rules')->get();

        foreach ($rates as $rate) {
            if ($rate->rules->isEmpty()) { // @phpstan-ignore-line
                continue;
            }

            foreach ($rate->rules as $rule) { // @phpstan-ignore-line
                if ($this->ruleMatchesItem($rule, $item)) {
                    return $rate;
                }
            }
        }

        return $rates->firstWhere('is_default', true);
    }

    private function ruleMatchesItem(TaxRateRule $rule, TaxableItem $item): bool
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
