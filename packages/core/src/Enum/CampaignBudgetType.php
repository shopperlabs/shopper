<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Contracts\HasDescription;
use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string None()
 * @method static string Spend()
 * @method static string Count()
 * @method static string SpendAndCount()
 */
enum CampaignBudgetType: string implements HasDescription, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case None = 'none';

    case Spend = 'spend';

    case Count = 'count';

    case SpendAndCount = 'spend_and_count';

    public function hasSpendCap(): bool
    {
        return $this === self::Spend || $this === self::SpendAndCount;
    }

    public function hasCountCap(): bool
    {
        return $this === self::Count || $this === self::SpendAndCount;
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::None => __('shopper-core::enum/campaign.budget.none'),
            self::Spend => __('shopper-core::enum/campaign.budget.spend'),
            self::Count => __('shopper-core::enum/campaign.budget.count'),
            self::SpendAndCount => __('shopper-core::enum/campaign.budget.spend_and_count'),
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::None => __('shopper-core::enum/campaign.budget.none_description'),
            self::Spend => __('shopper-core::enum/campaign.budget.spend_description'),
            self::Count => __('shopper-core::enum/campaign.budget.count_description'),
            self::SpendAndCount => __('shopper-core::enum/campaign.budget.spend_and_count_description'),
        };
    }
}
