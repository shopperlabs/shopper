<?php

declare(strict_types=1);

namespace Shopper\Navigation\Setting;

use Shopper\Contracts\SettingItem;
use Shopper\Navigation\AbstractNavigationItem;

abstract class Setting extends AbstractNavigationItem implements SettingItem
{
    public function url(): ?string
    {
        return null;
    }
}
