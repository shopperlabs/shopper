<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Closure;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;
use Shopper\Cart\Models\CartLineTaxLine;
use Shopper\Cart\Taxes\CartLineTaxAdapter;
use Shopper\Core\Taxes\ShippingTaxableItem;
use Shopper\Core\Taxes\TaxCalculationContext;
use Shopper\Core\Taxes\TaxCalculator;

final readonly class CalculateTax
{
    public function __construct(
        private TaxCalculator $calculator,
    ) {}

    public function handle(CartPipelineContext $context, Closure $next): mixed
    {
        $cart = $context->cart;
        $frozen = $cart->holdsProviderPaymentSession();

        if (! $frozen) {
            $this->clearTaxLines($cart);
        }

        $shippingAddress = $cart->shippingAddress();
        $countryCode = $shippingAddress?->country?->cca2;

        if (! $countryCode) {
            return $next($context);
        }

        $taxContext = new TaxCalculationContext(
            countryCode: $countryCode,
            provinceCode: $shippingAddress->state,
            customerId: $cart->customer_id,
        );

        if ($frozen) {
            $context->taxTotal += (int) $cart->lines->sum(fn (CartLine $line): int => (int) $line->taxLines->sum('amount'));
            $context->taxTotal += (int) $cart->shippingTaxLines->sum('amount');
        } else {
            $this->taxLines($context, $taxContext);
            $this->shippingTaxLines($context, $taxContext);
        }

        $context->taxInclusive = $cart->heldTaxInclusive() ?? $this->calculator->resolveZone($taxContext)->is_tax_inclusive ?? false;

        return $next($context);
    }

    private function clearTaxLines(Cart $cart): void
    {
        CartLineTaxLine::query()->whereIn('cart_line_id', $cart->lines->modelKeys())->delete();
        $cart->shippingTaxLines()->delete();

        $cart->lines->each(fn (CartLine $line) => $line->unsetRelation('taxLines'));
        $cart->unsetRelation('shippingTaxLines');
    }

    private function taxLines(CartPipelineContext $context, TaxCalculationContext $taxContext): void
    {
        CartLineTaxLine::query()
            ->whereKey(CartLineTaxLine::query()->whereIn('cart_line_id', $context->cart->lines->modelKeys())->pluck('id'))
            ->delete();

        foreach ($context->cart->lines as $line) {
            $discountAmount = (int) $line->adjustments->sum('amount');
            $taxableAmount = ($context->lineSubtotals[$line->id] ?? 0) - $discountAmount;

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

    private function shippingTaxLines(CartPipelineContext $context, TaxCalculationContext $taxContext): void
    {
        $cart = $context->cart;

        if (($cart->shipping_amount ?? 0) <= 0) {
            return;
        }

        foreach ($this->calculator->calculate(new ShippingTaxableItem($cart->shipping_amount), $taxContext) as $taxLine) {
            $cart->shippingTaxLines()->create([
                'code' => $taxLine->code ?? $taxLine->name,
                'name' => $taxLine->name,
                'rate' => $taxLine->rate,
                'amount' => $taxLine->amount,
                'tax_rate_id' => $taxLine->taxRateId,
            ]);

            $context->taxTotal += $taxLine->amount;
        }
    }
}
