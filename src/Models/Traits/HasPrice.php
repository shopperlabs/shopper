<?php

namespace Shopper\Framework\Models\Traits;

use Money\Money;
use Money\Currency;
use NumberFormatter;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

trait HasPrice
{
    /**
     * Return formatted price.
     *
     * @param int|string $price
     *
     * @return bool|string
     */
    public function formattedPrice($price)
    {
        $money = new Money($price * 100, new Currency(shopper_currency()));
        $currencies = new ISOCurrencies();

        $numberFormatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}
