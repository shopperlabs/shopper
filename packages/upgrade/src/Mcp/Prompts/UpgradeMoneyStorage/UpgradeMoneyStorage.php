<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Mcp\Prompts\UpgradeMoneyStorage;

use Shopper\Upgrade\Mcp\Prompts\ShopperUpgradePrompt;

final class UpgradeMoneyStorage extends ShopperUpgradePrompt
{
    protected string $name = 'upgrade-money-storage';

    protected string $title = 'upgrade_money_storage';

    protected string $description = 'Guides the migration of storefront code to use smallest currency unit storage (cents). Required when upgrading to Shopper 2.7+.';
}
