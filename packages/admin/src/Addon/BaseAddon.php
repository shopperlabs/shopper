<?php

declare(strict_types=1);

namespace Shopper\Addon;

use Shopper\Contracts\ShopperAddon;
use Shopper\ShopperPanel;

abstract class BaseAddon implements ShopperAddon
{
    public function getName(): string
    {
        return str($this->getId())->title()->replace('-', ' ')->toString();
    }

    public function boot(ShopperPanel $panel): void {}

    public function isEnabled(): bool
    {
        return config("shopper.addons.{$this->getId()}", true) !== false;
    }
}
