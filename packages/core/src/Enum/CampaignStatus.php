<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Contracts\HasColor;
use Shopper\Core\Contracts\HasIcon;
use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Draft()
 * @method static string Scheduled()
 * @method static string Active()
 * @method static string Disabled()
 * @method static string Expired()
 * @method static string BudgetExhausted()
 */
enum CampaignStatus: string implements HasColor, HasIcon, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Draft = 'draft';

    case Scheduled = 'scheduled';

    case Active = 'active';

    case Disabled = 'disabled';

    case Expired = 'expired';

    case BudgetExhausted = 'budget_exhausted';

    public function getColor(): string
    {
        return match ($this) {
            self::Draft, self::Disabled => 'gray',
            self::Scheduled => 'info',
            self::Active => 'success',
            self::Expired, self::BudgetExhausted => 'warning',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Draft => 'untitledui-file-04',
            self::Scheduled => 'untitledui-clock',
            self::Active => 'untitledui-check-circle',
            self::Disabled => 'untitledui-eye-off',
            self::Expired => 'untitledui-hourglass-03',
            self::BudgetExhausted => 'untitledui-bar-chart-square-up',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => __('shopper-core::status.campaign.draft'),
            self::Scheduled => __('shopper-core::status.campaign.scheduled'),
            self::Active => __('shopper-core::status.campaign.active'),
            self::Disabled => __('shopper-core::status.campaign.disabled'),
            self::Expired => __('shopper-core::status.campaign.expired'),
            self::BudgetExhausted => __('shopper-core::status.campaign.budget_exhausted'),
        };
    }
}
