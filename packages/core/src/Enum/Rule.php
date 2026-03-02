<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string ProductTitle()
 * @method static string ProductBrand()
 * @method static string ProductCategory()
 * @method static string ProductPrice()
 * @method static string CompareAtPrice()
 * @method static string InventoryStock()
 * @method static string ProductCreatedAt()
 * @method static string ProductFeatured()
 * @method static string ProductRating()
 * @method static string ProductSalesCount()
 */
enum Rule: string implements HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case ProductTitle = 'product_title';

    case ProductBrand = 'product_brand';

    case ProductCategory = 'product_category';

    case ProductPrice = 'product_price';

    case CompareAtPrice = 'compare_at_price';

    case InventoryStock = 'inventory_stock';

    case ProductCreatedAt = 'product_created_at';

    case ProductFeatured = 'product_featured';

    case ProductRating = 'product_rating';

    case ProductSalesCount = 'product_sales_count';

    public function getLabel(): string
    {
        return match ($this) {
            self::ProductTitle => __('shopper-core::enum/collection.rules.product_title'),
            self::ProductBrand => __('shopper-core::enum/collection.rules.product_brand'),
            self::ProductCategory => __('shopper-core::enum/collection.rules.product_category'),
            self::ProductPrice => __('shopper-core::enum/collection.rules.product_price'),
            self::CompareAtPrice => __('shopper-core::enum/collection.rules.compare_at_price'),
            self::InventoryStock => __('shopper-core::enum/collection.rules.inventory_stock'),
            self::ProductCreatedAt => __('shopper-core::enum/collection.rules.product_created_at'),
            self::ProductFeatured => __('shopper-core::enum/collection.rules.product_featured'),
            self::ProductRating => __('shopper-core::enum/collection.rules.product_rating'),
            self::ProductSalesCount => __('shopper-core::enum/collection.rules.product_sales_count'),
        };
    }

    /**
     * @return array<int, Operator>
     */
    public function allowedOperators(): array
    {
        return match ($this) {
            self::ProductTitle, self::ProductBrand, self::ProductCategory => [
                Operator::Contains,
                Operator::NotContains,
                Operator::StartsWith,
                Operator::EndsWith,
                Operator::EqualsTo,
                Operator::NotEqualTo,
            ],
            self::ProductPrice, self::CompareAtPrice, self::InventoryStock, self::ProductRating, self::ProductSalesCount => [
                Operator::EqualsTo,
                Operator::NotEqualTo,
                Operator::LessThan,
                Operator::GreaterThan,
            ],
            self::ProductCreatedAt => [
                Operator::EqualsTo,
                Operator::LessThan,
                Operator::GreaterThan,
            ],
            self::ProductFeatured => [
                Operator::EqualsTo,
            ],
        };
    }

    public function isPrice(): bool
    {
        return in_array($this, [self::ProductPrice, self::CompareAtPrice], true);
    }

    public function isNumeric(): bool
    {
        return in_array($this, [
            self::ProductPrice,
            self::CompareAtPrice,
            self::InventoryStock,
            self::ProductRating,
            self::ProductSalesCount,
        ], true);
    }

    public function isDate(): bool
    {
        return $this === self::ProductCreatedAt;
    }

    public function isBoolean(): bool
    {
        return $this === self::ProductFeatured;
    }
}
