<?php

declare(strict_types=1);

namespace Tests\Core\Stubs;

use Shopper\Core\Contracts\TaxableItem;

final readonly class TaxableItemStub implements TaxableItem
{
    /**
     * @param  array<int, int>  $categoryIds
     */
    public function __construct(
        private int $amount,
        private int $quantity = 1,
        private ?string $productType = null,
        private ?int $productId = null,
        private array $categoryIds = [],
    ) {}

    public function getTaxableAmount(): int
    {
        return $this->amount;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getProductType(): ?string
    {
        return $this->productType;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /** @return array<int, int> */
    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }
}
