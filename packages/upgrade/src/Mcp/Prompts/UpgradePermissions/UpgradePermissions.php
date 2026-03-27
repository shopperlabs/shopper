<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Mcp\Prompts\UpgradePermissions;

use Shopper\Upgrade\Mcp\Prompts\ShopperUpgradePrompt;

final class UpgradePermissions extends ShopperUpgradePrompt
{
    protected string $name = 'upgrade-permissions';

    protected string $title = 'upgrade_permissions';

    protected string $description = 'Guides migration of permission names from underscore format (read_orders) to dot notation (orders.read). Required when upgrading to Shopper 3.x.';
}
