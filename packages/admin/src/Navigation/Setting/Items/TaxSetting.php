<?php

declare(strict_types=1);

namespace Shopper\Navigation\Setting\Items;

use BackedEnum;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Enum\SettingGroup;
use Shopper\Navigation\Setting\Setting;

final class TaxSetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.tax');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.tax_description');
    }

    public function icon(): BackedEnum
    {
        return Untitledui::Scales;
    }

    public function url(): string
    {
        return route('shopper.settings.taxes');
    }

    public function order(): int
    {
        return 8;
    }

    public function group(): string
    {
        return SettingGroup::Selling->value;
    }
}
