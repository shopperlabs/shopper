<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

trait HasPrice
{
    public function formattedPrice(int|string $price): string
    {
        $money = new Money(
            amount: $price,
            currency: new Currency(shopper_currency())
        );

        $numberFormatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

        $moneyFormatter = new IntlMoneyFormatter(
            formatter: $numberFormatter,
            currencies: new ISOCurrencies()
        );

        return $moneyFormatter->format($money);
    }
}
