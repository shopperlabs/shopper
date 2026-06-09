<?php

declare(strict_types=1);

namespace Shopper\Navigation\Product\Sections;

use BackedEnum;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Enum\ProductSectionGroup;
use Shopper\Navigation\Product\AbstractProductSection;

final class OverviewSection extends AbstractProductSection
{
    public function name(): string
    {
        return __('shopper::words.overview');
    }

    public function icon(): BackedEnum
    {
        return Untitledui::File02;
    }

    public function group(): string
    {
        return ProductSectionGroup::Product->value;
    }

    public function order(): int
    {
        return 10;
    }

    public function url(Product $product): string
    {
        return route('shopper.products.edit', ['product' => $product]);
    }
}
