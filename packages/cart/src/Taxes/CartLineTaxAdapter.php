<?php

declare(strict_types=1);

namespace Shopper\Cart\Taxes;

use Shopper\Cart\Models\CartLine;
use Shopper\Core\Contracts\TaxableItem;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;

final readonly class CartLineTaxAdapter implements TaxableItem
{
    public function __construct(
        private CartLine $line,
        private int $taxableTotal,
    ) {}

    public function getTaxableAmount(): int
    {
        return (int) round($this->taxableTotal / $this->line->quantity);
    }

    public function getQuantity(): int
    {
        return $this->line->quantity;
    }

    public function getProductType(): ?string
    {
        return $this->resolveProduct()?->type?->value;
    }

    public function getProductId(): ?int
    {
        $product = $this->resolveProduct();

        return $product?->id;
    }

    /** @return array<int, int> */
    public function getCategoryIds(): array
    {
        $product = $this->resolveProduct();

        if (! $product) {
            return [];
        }

        return $product->categories()->pluck('id')->all();
    }

    private function resolveProduct(): ?Product
    {
        $model = $this->line->purchasable;

        if ($model instanceof ProductVariant) {
            return $model->product;
        }

        if ($model instanceof Product) {
            return $model;
        }

        return null;
    }
}
