<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Contracts\HasLabel;
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

    case Female = 'female';

    case Male = 'male';

    public function getLabel(): string
    {
        return match ($this) {
            self::Female => __('shopper::words.female'),
            self::Male => __('shopper::words.male'),
        };
    }
}
