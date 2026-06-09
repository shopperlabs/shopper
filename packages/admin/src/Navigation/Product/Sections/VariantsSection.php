<?php

declare(strict_types=1);

namespace Shopper\Navigation\Product\Sections;

use BackedEnum;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Enum\ProductSectionGroup;
use Shopper\Navigation\Product\AbstractProductSection;

final class VariantsSection extends AbstractProductSection
{
    public function name(): string
    {
        return __('shopper::words.variants');
    }

    public function icon(): BackedEnum
    {
        return Untitledui::BookOpen;
    }

    public function group(): string
    {
        return ProductSectionGroup::Product->value;
    }

    public function order(): int
    {
        return 40;
    }

    public function url(Product $product): string
    {
        return route('shopper.products.edit.variants', ['product' => $product]);
    }

    public function visible(Product $product): bool
    {
        return $product->canUseVariants();
    }
}
