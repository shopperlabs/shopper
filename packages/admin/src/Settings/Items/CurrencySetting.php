<?php

declare(strict_types=1);

namespace Shopper\Settings\Items;

use Shopper\Settings\Setting;

final class CurrencySetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.currency');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.currency_description');
    }

    public function icon(): string
    {
        return 'untitledui-currency-dollar-circle';
    }

    public function url(): string
    {
        return route('shopper.settings.currencies');
    }

    public function order(): int
    {
        return 9;
    }
}
