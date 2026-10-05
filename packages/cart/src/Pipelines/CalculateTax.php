<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Closure;
use Shopper\Cart\Models\CartLine;
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

        if ($context->cart->holdsProviderPaymentSession()) {
            $context->taxTotal += (int) $context->cart->lines->sum(fn (CartLine $line): int => (int) $line->taxLines->sum('amount'));
        } else {
            $this->taxLines($context, $taxContext);
        }

        $context->taxInclusive = $context->cart->heldTaxInclusive() ?? $this->calculator->resolveZone($taxContext)->is_tax_inclusive ?? false;

        return $next($context);
    }

    private function taxLines(CartPipelineContext $context, TaxCalculationContext $taxContext): void
    {
        foreach ($context->cart->lines as $line) {
            $discountAmount = (int) $line->adjustments->sum('amount');
            $taxableAmount = ($context->lineSubtotals[$line->id] ?? 0) - $discountAmount;

            // Clear before the taxable guard: a line discounted to zero since
            // the last run must not keep stale tax lines behind, they would
            // otherwise be frozen onto the order.
            $line->taxLines()->delete();

            if ($taxableAmount <= 0) {
                continue;
            }

            $adapter = new CartLineTaxAdapter($line, $taxableAmount);
            $taxLines = $this->calculator->calculate($adapter, $taxContext);

            $lineTaxTotal = 0;

            foreach ($taxLines as $taxLine) {
                $line->taxLines()->create([
                    'code' => $taxLine->code ?? $taxLine->name,
                    'name' => $taxLine->name,
                    'rate' => $taxLine->rate,
                    'amount' => $taxLine->amount,
                    'tax_rate_id' => $taxLine->taxRateId,
                ]);

                $lineTaxTotal += $taxLine->amount;
            }

            $context->taxTotal += $lineTaxTotal;
        }
    }
}
