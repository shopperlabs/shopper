<?php

namespace Shopper\Framework\Http\Controllers\Api\Settings;

use Shopper\Framework\Http\Controllers\ShopperBaseController;
use Shopper\Framework\Http\Resources\Currency as CurrencyResource;
use Shopper\Framework\Models\System\Currency;

class CurrencyController extends ShopperBaseController
{
    /**
     * Return Collections API for all currencies.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function lists()
    {
        return CurrencyResource::collection(Currency::all());
    }

    /**
     * Return Currency for the given code.
     *
     * @param  string  $code
     * @return CurrencyResource
     */
    public function getCurrencyByCode(string $code)
    {
        return new CurrencyResource(Currency::query()->where('code', $code)->first());
    }
}
