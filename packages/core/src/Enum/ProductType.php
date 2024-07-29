<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string External()
 * @method static string Downloadable()
 * @method static string Simple()
 * @method static string VariableProduct()
 */
enum ProductType: string implements HasDescription, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case External = 'external';

    case Downloadable = 'downloadable';

    case Simple = 'simple_product';

    case VariableProduct = 'variable_product';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Downloadable => __('shopper-core::enum/product.downloadable'),
            self::External => __('shopper-core::enum/product.external'),
            self::Simple => __('shopper-core::enum/product.simple_product'),
            self::VariableProduct => __('shopper-core::enum/product.variable_product'),
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::Downloadable => __('shopper-core::enum/product.downloadable_description'),
            self::External => __('shopper-core::enum/product.external_description'),
            self::Simple => __('shopper-core::enum/product.simple_product_description'),
            self::VariableProduct => __('shopper-core::enum/product.variable_product_description'),
        };
    }
}
