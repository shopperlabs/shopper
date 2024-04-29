<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;

enum CollectionType: string implements HasDescription, HasLabel
{
    use ArrayableEnum;

    case Manual = 'manual';

    case Auto = 'auto';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Manual => __('shopper-core::enum/collection.manual'),
            self::Auto => __('shopper-core::enum/collection.automatic'),
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::Manual => __('shopper-core::enum/collection.manual_description'),
            self::Auto => __('shopper-core::enum/collection.automatic_description'),
        };
    }
}
