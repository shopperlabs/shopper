<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

interface TaxableItem
{
    public function getTaxableAmount(): int;

    public function getQuantity(): int;

    public function getProductType(): ?string;

    public function getProductId(): ?int;

    /** @return array<int, int> */
    public function getCategoryIds(): array;
}
