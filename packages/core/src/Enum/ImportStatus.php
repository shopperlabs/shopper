<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Contracts\HasColor;
use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Pending()
 * @method static string Processing()
 * @method static string Completed()
 * @method static string CompletedWithErrors()
 * @method static string Failed()
 */
enum ImportStatus: string implements HasColor, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Pending = 'pending';

    case Processing = 'processing';

    case Completed = 'completed';

    case CompletedWithErrors = 'completed_with_errors';

    case Failed = 'failed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('shopper-core::enum/import.pending'),
            self::Processing => __('shopper-core::enum/import.processing'),
            self::Completed => __('shopper-core::enum/import.completed'),
            self::CompletedWithErrors => __('shopper-core::enum/import.completed_with_errors'),
            self::Failed => __('shopper-core::enum/import.failed'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Processing => 'info',
            self::Completed => 'success',
            self::CompletedWithErrors => 'warning',
            self::Failed => 'danger',
        };
    }

    public function isFinished(): bool
    {
        return in_array($this, [self::Completed, self::CompletedWithErrors, self::Failed], true);
    }
}
