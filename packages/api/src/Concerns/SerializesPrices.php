<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Shopper\Core\Models\Price;

trait SerializesPrices
{
    /**
     * Map the loaded prices relation to a flat multi-currency array. Amounts
     * stay in cents; the storefront picks the currency it needs.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function pricesPayload(): array
    {
        return $this->prices->map(fn (Price $price): array => [
            'amount' => $price->amount,
            'compare_amount' => $price->compare_amount,
            'currency_code' => $price->currency_code,
        ])->values()->all();
    }
}
