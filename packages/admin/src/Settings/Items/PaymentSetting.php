<?php

declare(strict_types=1);

namespace Shopper\Settings\Items;

use Shopper\Settings\Setting;

final class PaymentSetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.payment');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.payment_description');
    }

    public function icon(): string
    {
        return 'untitledui-coins-hand';
    }

    public function url(): string
    {
        return route('shopper.settings.payment-methods');
    }

    public function order(): int
    {
        return 4;
    }
}
