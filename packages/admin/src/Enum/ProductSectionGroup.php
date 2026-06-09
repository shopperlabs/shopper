<?php

declare(strict_types=1);

namespace Shopper\Enum;

use Shopper\Contracts\NavigationGroup;
use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Product()
 * @method static string Inventory()
 * @method static string Sales()
 * @method static string Marketing()
 */
enum ProductSectionGroup: string implements HasLabel, NavigationGroup
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Product = 'product';

    case Inventory = 'inventory';

    case Sales = 'sales';

    case Marketing = 'marketing';

    public function getLabel(): string
    {
        return match ($this) {
            self::Product => __('shopper::pages/products/groups.product'),
            self::Inventory => __('shopper::pages/products/groups.inventory'),
            self::Sales => __('shopper::pages/products/groups.sales'),
            self::Marketing => __('shopper::pages/products/groups.marketing'),
        };
    }

    public function order(): int
    {
        return match ($this) {
            self::Product => 10,
            self::Inventory => 20,
            self::Sales => 30,
            self::Marketing => 40,
        };
    }
}
