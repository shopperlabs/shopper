<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Male()
 * @method static string Female()
 */
enum GenderType: string implements HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Male = 'male';

    case Female = 'female';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Male => __('shopper::words.male'),
            self::Female => __('shopper::words.female'),
        };
    }
}
