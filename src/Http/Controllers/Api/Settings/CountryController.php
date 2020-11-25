<?php

namespace Shopper\Framework\Http\Controllers\Api\Settings;

use Shopper\Framework\Http\Controllers\ShopperBaseController;
use Shopper\Framework\Http\Resources\Country as CountryResource;
use Shopper\Framework\Models\System\Country;

class CountryController extends ShopperBaseController
{
    /**
     * Return Countries API lists.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function lists()
    {
        return CountryResource::collection(Country::query()->orderBy('name')->get());
    }
}
