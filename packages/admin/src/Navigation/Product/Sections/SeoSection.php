<?php

declare(strict_types=1);

namespace Shopper\Navigation\Product\Sections;

use BackedEnum;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Enum\ProductSectionGroup;
use Shopper\Navigation\Product\AbstractProductSection;

final class SeoSection extends AbstractProductSection
{
    public function name(): string
    {
        return __('shopper::words.seo.slug');
    }

    public function icon(): BackedEnum
    {
        return Untitledui::Monitor02;
    }

    public function group(): string
    {
        return ProductSectionGroup::Marketing->value;
    }

    public function order(): int
    {
        return 10;
    }

    public function url(Product $product): string
    {
        return route('shopper.products.edit.seo', ['product' => $product]);
    }
}
