<?php

declare(strict_types=1);

namespace Shopper\Navigation\Product\Sections;

use BackedEnum;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Enum\ProductSectionGroup;
use Shopper\Navigation\Product\AbstractProductSection;

final class ShippingSection extends AbstractProductSection
{
    public function name(): string
    {
        return __('shopper::words.shipping');
    }

    public function icon(): BackedEnum
    {
        return Untitledui::Plane;
    }

    public function group(): string
    {
        return ProductSectionGroup::Sales->value;
    }

    public function order(): int
    {
        return 20;
    }

    public function url(Product $product): string
    {
        return route('shopper.products.edit.shipping', ['product' => $product]);
    }

    public function visible(Product $product): bool
    {
        return $product->canUseShipping();
    }
}
