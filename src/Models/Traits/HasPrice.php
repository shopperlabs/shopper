<?php

namespace Shopper\Framework\Models\Traits;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

trait HasPrice
{
    /**
     * Return formatted price.
     *
     * @param  int|string  $price
     * @return bool|string
     */
    public function formattedPrice($price)
    {
        $money = new Money($price, new Currency(shopper_currency()));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}
