<?php

declare(strict_types=1);

namespace Shopper\Settings\Items;

use BackedEnum;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Settings\Setting;

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
}
