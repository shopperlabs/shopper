<?php

declare(strict_types=1);

namespace Shopper\Navigation\Setting\Items;

use Shopper\Enum\SettingGroup;
use Shopper\Navigation\Setting\Setting;

final class ChannelSetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.sales');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.sales_description');
    }

    public function icon(): string
    {
        return 'untitledui-shop';
    }

    public function url(): string
    {
        return route('shopper.settings.channels');
    }

    public function order(): int
    {
        return 3;
    }

    public function group(): string
    {
        return SettingGroup::Selling->value;
    }
}
