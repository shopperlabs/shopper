<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation;

use Shopper\Sidebar\Presentation\View\BadgeRenderer;

final class ShopperBadgeRenderer extends BadgeRenderer
{
    protected string $view = 'shopper::sidebar.badge';
}
