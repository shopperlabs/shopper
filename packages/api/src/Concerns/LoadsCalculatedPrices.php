<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Shopper\Core\Contracts\PreloadsPrices;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Contracts\PriceResolver;
use Shopper\Core\Contracts\QuantityRuleResolver;
use Shopper\Core\Pricing\PricingContext;

trait LoadsCalculatedPrices
{
    /**
     * @param  Collection<int, Model>  $purchasables
     */
    protected function loadCalculatedPrices(Collection $purchasables, PricingContext $context): void
    {
        /** @var Collection<int, Priceable&Model> $purchasables */
        $purchasables = $purchasables->filter(fn (Model $model): bool => $model instanceof Priceable)->values();

        if ($purchasables->isEmpty()) {
            return;
        }

        $prices = resolve(PriceResolver::class);
        $rules = resolve(QuantityRuleResolver::class);

        if ($prices instanceof PreloadsPrices && $context->currencyCode !== null) {
            $prices->preload($purchasables, $context);
        }

        foreach ($purchasables as $purchasable) {
            $price = $context->currencyCode === null ? null : $prices->resolve($purchasable, $context);

            $purchasable->setAttribute('calculated_price', $price === null ? null : [
                'amount' => $price->amount,
                'compare_amount' => $price->compareAmount,
                'original_amount' => $price->originalAmount,
                'currency_code' => $price->currencyCode,
                'meta' => $price->meta['public'] ?? null,
            ]);
            $purchasable->setAttribute('quantity_rule', $rules->resolve($purchasable, $context)?->toArray());
        }
    }
}
