<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

/**
 * @method static string Reserve()
 * @method static string Release()
 */
enum CampaignBudgetDirection: string
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Reserve = 'reserve';

    case Release = 'release';
}
