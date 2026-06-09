<?php

declare(strict_types=1);

namespace Shopper\Contracts;

interface SettingItem extends NavigationItem
{
    public function description(): string;

    public function url(): ?string;
}
