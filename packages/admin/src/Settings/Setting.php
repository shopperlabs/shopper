<?php

declare(strict_types=1);

namespace Shopper\Settings;

use Shopper\Contracts\SettingItem;

abstract class Setting implements SettingItem
{
    public function url(): ?string
    {
        return null;
    }

    public function permission(): ?string
    {
        return null;
    }

    public function order(): int
    {
        return 0;
    }
}
