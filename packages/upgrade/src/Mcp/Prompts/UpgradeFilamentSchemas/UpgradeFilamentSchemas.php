<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Mcp\Prompts\UpgradeFilamentSchemas;

use Shopper\Upgrade\Mcp\Prompts\ShopperUpgradePrompt;

final class UpgradeFilamentSchemas extends ShopperUpgradePrompt
{
    protected string $name = 'upgrade-filament-schemas';

    protected string $title = 'upgrade_filament_schemas';

    protected string $description = 'Guides migration of Filament InteractsWithForms to InteractsWithSchemas in custom Shopper components. Only needed if app/Shopper/ contains custom Filament components.';
}
