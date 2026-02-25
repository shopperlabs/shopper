<?php

declare(strict_types=1);

namespace Shopper\Core\Taxes;

use Shopper\Core\Contracts\TaxableItem;
use Shopper\Core\Models\Contracts\OrderItem;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;

final readonly class OrderItemTaxAdapter implements TaxableItem
{
    public function __construct(
        private OrderItem $item,
    ) {}

    public function getTaxableAmount(): int
    {
        return (int) $this->item->getRawOriginal('unit_price_amount');
    }

    public function getQuantity(): int
    {
        return $this->item->quantity;
    }

    public function getProductType(): ?string
    {
        return $this->resolveProduct()?->type?->value;
    }

    public function getProductId(): ?int
    {
        return $this->item->product_id;
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
        $model = $this->item->product;

        if ($model instanceof ProductVariant) {
            return $model->product;
        }

        if ($model instanceof Product) {
            return $model;
        }

        return null;
    }
}
