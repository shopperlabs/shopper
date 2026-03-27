<?php

declare(strict_types=1);

namespace Shopper\Upgrade;

use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Prompt;
use Shopper\Upgrade\Mcp\Prompts\UpgradeFilamentSchemas\UpgradeFilamentSchemas;
use Shopper\Upgrade\Mcp\Prompts\UpgradeMoneyStorage\UpgradeMoneyStorage;
use Shopper\Upgrade\Mcp\Prompts\UpgradeSlideOvers\UpgradeSlideOvers;
use Shopper\Upgrade\Mcp\Prompts\UpgradeTwoFactorAuth\UpgradeTwoFactorAuth;

class ShopperUpgradeServer extends Server
{
    protected string $name = 'Shopper Upgrade';

    protected string $version = '1.0.0';

    protected string $instructions = 'Shopper upgrade assistant. Provides guided upgrade prompts for migrating between Shopper versions. Each prompt checks if the upgrade applies to the project before suggesting changes.';

    /**
     * @return array<int, class-string<Prompt>>
     */
    protected function discoverPrompts(): array
    {
        return [
            UpgradeMoneyStorage::class,
            UpgradeSlideOvers::class,
            UpgradeTwoFactorAuth::class,
            UpgradeFilamentSchemas::class,
        ];
    }
}
