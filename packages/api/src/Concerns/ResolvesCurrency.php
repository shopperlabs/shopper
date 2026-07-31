<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Shopper\Core\Models\Currency;

trait ResolvesCurrency
{
    /**
     * The currency every price-aware value of the response is expressed in,
     * resolved once per request: an explicit `filter[currency]` wins, then the
     * currency of the zone resolved from the request, then the shop default.
     * An unknown or disabled code is a client error, never a silent fallback.
     * A disabled currency never resolves on any path, so `meta.currency` can
     * always be echoed back as `filter[currency]`. Null when no enabled
     * currency resolves at all, so a plain listing keeps working on a
     * half-configured installation.
     */
    protected function resolvedCurrency(): ?Currency
    {
        $request = request();

        if (! $request->attributes->has('shopper_price_currency')) {
            $request->attributes->set('shopper_price_currency', $this->currencyFromRequest($request));
        }

        return $request->attributes->get('shopper_price_currency');
    }

    private function currencyFromRequest(Request $request): ?Currency
    {
        $code = $request->input('filter.currency');

        if (is_string($code) && $code !== '') {
            $currency = Currency::query()->where('code', mb_strtoupper($code))->first();

            if ($currency === null) {
                throw ValidationException::withMessages([
                    'filter.currency' => __('shopper-api::messages.catalog.unknown_currency', ['code' => $code]),
                ]);
            }

            return $currency;
        }

        $zone = $request->attributes->get('shopper_zone');

        if ($zone !== null && $zone->currency_id !== null) {
            $currency = $zone->loadMissing('currency')->currency;

            if ($currency !== null) {
                return $currency;
            }
        }

        $code = shopper_currency();

        return Cache::remember(
            'shopper.api.currency.'.$code,
            3600,
            fn (): ?Currency => Currency::query()->where('code', $code)->first(),
        );
    }
}
