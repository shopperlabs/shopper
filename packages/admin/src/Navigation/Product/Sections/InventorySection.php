<?php

declare(strict_types=1);

namespace Shopper\Navigation\Product\Sections;

use BackedEnum;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Enum\ProductSectionGroup;
use Shopper\Navigation\Product\AbstractProductSection;

final class InventorySection extends AbstractProductSection
{
    public function name(): string
    {
        return __('shopper::pages/products.stock_inventory_heading');
    }

    public function icon(): BackedEnum
    {
        return Untitledui::Package;
    }

    public function group(): string
    {
        return ProductSectionGroup::Inventory->value;
    }

    public function order(): int
    {
        return 10;
    }

    public function url(Product $product): string
    {
        return route('shopper.products.edit.inventory', ['product' => $product]);
    }
}
