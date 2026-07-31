<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Shopper\Core\Models\Price;

trait SerializesPrices
{
    /**
     * Map the loaded prices relation to a flat multi-currency array. Amounts
     * stay in cents; the storefront picks the currency it needs. Prices in a
     * disabled currency are dropped: a storefront must never quote a
     * currency the shop no longer sells in.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function pricesPayload(): array
    {
        return $this->prices
            ->filter(fn (Price $price): bool => $price->loadMissing('currency')->currency->is_enabled)
            ->map(fn (Price $price): array => [
                'public_id' => $price->public_id,
                'amount' => $price->amount,
                'compare_amount' => $price->compare_amount,
                'currency_code' => $price->currency_code,
            ])->values()->all();
    }
}
