<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

interface TaxableItem
{
    /**
     * The exact taxable total for the whole line. Returning the total, rather
     * than a per-unit amount the provider multiplies back by quantity, keeps
     * the tax base free of the rounding drift a divide-then-multiply round trip
     * introduces when a line discount is not divisible by the quantity.
     */
    public function getTaxableTotal(): int;

    public function getProductType(): ?string;

    public function getProductId(): ?int;

    /** @return array<int, int> */
    public function getCategoryIds(): array;
}
