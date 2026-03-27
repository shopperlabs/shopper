<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Mcp\Prompts\UpgradeTwoFactorAuth;

use Shopper\Upgrade\Mcp\Prompts\ShopperUpgradePrompt;

final class UpgradeTwoFactorAuth extends ShopperUpgradePrompt
{
    protected string $name = 'upgrade-two-factor-auth';

    protected string $title = 'upgrade_two_factor_auth';

    protected string $description = 'Guides migration of TwoFactorAuthenticatable trait to new SOLID interfaces. Only needed if the User model uses TwoFactorAuthenticatable.';
}
