<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Mcp\Prompts\UpgradeSlideOvers;

use Shopper\Upgrade\Mcp\Prompts\ShopperUpgradePrompt;

final class UpgradeSlideOvers extends ShopperUpgradePrompt
{
    protected string $name = 'upgrade-slide-overs';

    protected string $title = 'upgrade_slide_overs';

    protected string $description = 'Guides migration of custom slide-over components from Shopper internal SlideOverComponent to laravelcm/livewire-slide-overs package. Only needed if app/Shopper/ contains custom slide-over classes.';
}
