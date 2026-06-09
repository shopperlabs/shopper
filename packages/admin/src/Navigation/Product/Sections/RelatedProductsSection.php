<?php

declare(strict_types=1);

namespace Shopper\Navigation\Product\Sections;

use BackedEnum;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Enum\ProductSectionGroup;
use Shopper\Navigation\Product\AbstractProductSection;

final class RelatedProductsSection extends AbstractProductSection
{
    public function name(): string
    {
        return __('shopper::pages/products.related_products');
    }

    public function icon(): BackedEnum
    {
        return Untitledui::Dataflow04;
    }

    public function group(): string
    {
        return ProductSectionGroup::Marketing->value;
    }

    public function order(): int
    {
        return 20;
    }

    public function url(Product $product): string
    {
        return route('shopper.products.edit.related', ['product' => $product]);
    }
}
