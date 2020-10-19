<?php

namespace Shopper\Framework\Http\Controllers\Api\Settings;

use Illuminate\Routing\Controller;
use Shopper\Framework\Http\Resources\Currency as CurrencyResource;
use Shopper\Framework\Models\System\Currency;

class CurrencyController extends Controller
{
    public function lists()
    {
        return CurrencyResource::collection(Currency::all());
    }

    public function getCurrencyByCode(string $code)
    {
        return new CurrencyResource(Currency::query()->where('code', $code)->first());
    }
}
