<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Closure;
use Shopper\Cart\Taxes\CartLineTaxAdapter;
use Shopper\Core\Taxes\TaxCalculationContext;
use Shopper\Core\Taxes\TaxCalculator;

final readonly class CalculateTax
{
    public function __construct(
        private TaxCalculator $calculator,
    ) {}

    public function handle(CartPipelineContext $context, Closure $next): mixed
    {
        $shippingAddress = $context->cart->shippingAddress();
        $countryCode = $shippingAddress?->country?->cca2;

        if (! $countryCode) {
            return $next($context);
        }

        $taxContext = new TaxCalculationContext(
            countryCode: $countryCode,
            provinceCode: $shippingAddress->state,
            customerId: $context->cart->customer_id,
        );

        foreach ($context->cart->lines as $line) {
            $discountAmount = (int) $line->adjustments->sum(fn ($adj) => $adj->getRawOriginal('amount'));
            $taxableAmount = ($context->lineSubtotals[$line->id] ?? 0) - $discountAmount;

            if ($taxableAmount <= 0) {
                continue;
            }

            $adapter = new CartLineTaxAdapter($line, $taxableAmount);
            $taxLines = $this->calculator->calculate($adapter, $taxContext);

            $line->taxLines()->delete();
            $lineTaxTotal = 0;

            foreach ($taxLines as $taxLine) {
                $line->taxLines()->create([
                    'code' => $taxLine->code ?? $taxLine->name,
                    'name' => $taxLine->name,
                    'rate' => $taxLine->rate,
                    'amount' => $taxLine->amount / 100,
                    'tax_rate_id' => $taxLine->taxRateId,
                ]);

                $lineTaxTotal += $taxLine->amount;
            }

            $context->taxTotal += $lineTaxTotal;
        }

        $zone = $this->calculator->resolveZone($taxContext);
        $context->taxInclusive = $zone->is_tax_inclusive ?? false;

        return $next($context);
    }
}
