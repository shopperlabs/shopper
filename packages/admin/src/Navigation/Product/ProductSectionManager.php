<?php

declare(strict_types=1);

namespace Shopper\Navigation\Product;

use Illuminate\Support\Collection;
use Shopper\Contracts\NavigationGroup;
use Shopper\Contracts\ProductSection;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Enum\ProductSectionGroup;
use Shopper\Navigation\NavigationManager;

/**
 * @extends NavigationManager<ProductSection>
 */
final class ProductSectionManager extends NavigationManager
{
    /**
     * @return Collection<int, ProductSection>
     */
    public function forProduct(Product $product): Collection
    {
        /** @var Collection<int, ProductSection> */
        return $this->groupedForProduct($product)
            ->flatMap(fn (array $bucket): Collection => $bucket['items'])
            ->values();
    }

    /**
     * @return Collection<int, array{group: ?string, label: ?string, items: Collection<int, ProductSection>}>
     */
    public function groupedForProduct(Product $product): Collection
    {
        /** @phpstan-ignore return.type */
        return $this->grouped(fn (ProductSection $section): bool => $section->visible($product));
    }

    protected function resolveGroup(string $value): ?NavigationGroup
    {
        return ProductSectionGroup::tryFrom($value);
    }
}
