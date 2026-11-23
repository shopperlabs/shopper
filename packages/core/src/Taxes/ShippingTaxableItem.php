<?php

declare(strict_types=1);

namespace Shopper\Core\Taxes;

use Shopper\Core\Contracts\TaxableItem;

final readonly class ShippingTaxableItem implements TaxableItem
{
    public function __construct(
        private int $taxableTotal,
    ) {}

    public function getTaxableTotal(): int
    {
        return $this->taxableTotal;
    }

    public function getProductType(): ?string
    {
        return null;
    }

    public function getProductId(): ?int
    {
        return null;
    }

    public function getCategoryIds(): array
    {
        return [];
    }
}
