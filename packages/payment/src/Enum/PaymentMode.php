<?php

declare(strict_types=1);

namespace Shopper\Payment\Enum;

use Shopper\Core\Contracts\HasColor;
use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Live()
 * @method static string Test()
 */
enum PaymentMode: string implements HasColor, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Live = 'live';

    case Test = 'test';

    public function getLabel(): string
    {
        return match ($this) {
            self::Live => __('shopper-core::enum/mode.live'),
            self::Test => __('shopper-core::enum/mode.test'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Live => 'success',
            self::Test => 'warning',
        };
    }
}
