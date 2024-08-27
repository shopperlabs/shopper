<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Filament\Support\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string EqualsTo()
 * @method static string NotEqualTo()
 * @method static string LessThan()
 * @method static string GreaterThan()
 * @method static string StartsWith()
 * @method static string EndsWith()
 * @method static string Contains()
 * @method static string NotContains()
 */
enum Operator: string implements HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case EqualsTo = 'equals_to';

    case NotEqualTo = 'not_equal_to';

    case LessThan = 'less_than';

    case GreaterThan = 'greater_than';

    case StartsWith = 'starts_with';

    case EndsWith = 'ends_with';

    case Contains = 'contains';

    case NotContains = 'not_contains';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::EqualsTo => __('shopper-core::enum/collection.operator.equals_to'),
            self::NotEqualTo => __('shopper-core::enum/collection.operator.not_equals_to'),
            self::LessThan => __('shopper-core::enum/collection.operator.less_than'),
            self::GreaterThan => __('shopper-core::enum/collection.operator.greater_than'),
            self::StartsWith => __('shopper-core::enum/collection.operator.starts_with'),
            self::EndsWith => __('shopper-core::enum/collection.operator.ends_with'),
            self::Contains => __('shopper-core::enum/collection.operator.contains'),
            self::NotContains => __('shopper-core::enum/collection.operator.not_contains'),
        };
    }
}
