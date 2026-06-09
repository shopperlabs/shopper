<?php

declare(strict_types=1);

namespace Shopper\Navigation\Product\Sections;

use BackedEnum;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Enum\ProductSectionGroup;
use Shopper\Feature;
use Shopper\Navigation\Product\AbstractProductSection;

final class AttributesSection extends AbstractProductSection
{
    public function name(): string
    {
        return __('shopper::pages/attributes.menu');
    }

    public function icon(): BackedEnum
    {
        return Untitledui::PuzzlePiece;
    }

    public function group(): string
    {
        return ProductSectionGroup::Product->value;
    }

    public function order(): int
    {
        return 30;
    }

    public function url(Product $product): string
    {
        return route('shopper.products.edit.attributes', ['product' => $product]);
    }

    public function visible(Product $product): bool
    {
        return Feature::enabled('attribute') && $product->canUseAttributes();
    }
}
