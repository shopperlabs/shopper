<?php

declare(strict_types=1);

use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;

pest()->project()->github('shopperlabs/shopper');

/**
 * @param  array<string>  $currencies
 */
function setupCurrencies(array $currencies = ['USD'], string $defaultCurrency = 'USD'): void
{
    $currencies = Currency::query()->select('id', 'code')->whereIn('code', $currencies)->get();

    Setting::query()->updateOrCreate(
        ['key' => 'currencies'],
        [
            'value' => $currencies->pluck('id')->toArray(),
            'display_name' => Setting::lockedAttributesDisplayName('currencies'),
            'locked' => true,
        ]
    );

    $currency = Currency::query()->where('code', $defaultCurrency)->first();
    Setting::query()->updateOrCreate(
        ['key' => 'default_currency_id'],
        [
            'value' => $currency->id,
            'display_name' => Setting::lockedAttributesDisplayName('default_currency_id'),
            'locked' => true,
        ]
    );
}
