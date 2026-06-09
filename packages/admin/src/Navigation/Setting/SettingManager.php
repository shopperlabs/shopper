<?php

declare(strict_types=1);

namespace Shopper\Navigation\Setting;

use Shopper\Contracts\NavigationGroup;
use Shopper\Contracts\SettingItem;
use Shopper\Enum\SettingGroup;
use Shopper\Navigation\NavigationManager;

/**
 * @extends NavigationManager<SettingItem>
 */
final class SettingManager extends NavigationManager
{
    protected function resolveGroup(string $value): ?NavigationGroup
    {
        return SettingGroup::tryFrom($value);
    }
}
