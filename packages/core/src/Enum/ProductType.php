<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use JaOcero\RadioDeck\Contracts\HasDescriptions;
use JaOcero\RadioDeck\Contracts\HasIcons;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string External()
 * @method static string Virtual()
 * @method static string Standard()
 * @method static string Variant()
 */
enum ProductType: string implements HasColor, HasDescription, HasDescriptions, HasIcon, HasIcons, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case External = 'external';

    case Virtual = 'virtual';

    case Standard = 'standard';

    case Variant = 'variant';

    public function getLabel(): string
    {
        return match ($this) {
            self::Virtual => __('shopper-core::enum/product.virtual'),
            self::External => __('shopper-core::enum/product.external'),
            self::Standard => __('shopper-core::enum/product.standard_product'),
            self::Variant => __('shopper-core::enum/product.variant_product'),
        };
    }

    public function getDescriptions(): string
    {
        return match ($this) {
            self::Virtual => __('shopper-core::enum/product.virtual_description'),
            self::External => __('shopper-core::enum/product.external_description'),
            self::Standard => __('shopper-core::enum/product.standard_product_description'),
            self::Variant => __('shopper-core::enum/product.variant_product_description'),
        };
    }

    public function getDescription(): string
    {
        return $this->getDescriptions();
    }

    public function getIcons(): string
    {
        return match ($this) {
            self::Virtual => 'phosphor-monitor-duotone',
            self::External => 'phosphor-link-simple-duotone',
            self::Standard => 'phosphor-tag-duotone',
            self::Variant => 'phosphor-swatches-duotone',
        };
    }

    public function getIcon(): string
    {
        return $this->getIcons();
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Virtual => 'info',
            self::External => 'indigo',
            self::Standard => 'gray',
            self::Variant => 'primary',
        };
    }
}
