<?php

declare(strict_types=1);

namespace Shopper\Navigation;

use Shopper\Contracts\NavigationItem;

abstract class AbstractNavigationItem implements NavigationItem
{
    public function order(): int
    {
        return 0;
    }

    public function group(): ?string
    {
        return null;
    }

    public function permission(): ?string
    {
        return null;
    }
}
